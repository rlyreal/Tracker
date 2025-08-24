<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'financial_tracker');

try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
        DB_USER, 
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Set charset to UTF8
    $conn->exec("SET NAMES 'utf8'");
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to get all transactions for today
function getTodayTransactions($type = null) {
    global $conn;
    
    $sql = "SELECT t.*, 
            CASE 
                WHEN t.type = 'income' THEN ic.name 
                ELSE ec.name 
            END as category_name 
            FROM transactions t 
            LEFT JOIN income_categories ic ON t.category_id = ic.id AND t.type = 'income'
            LEFT JOIN expense_categories ec ON t.category_id = ec.id AND t.type = 'expense'
            WHERE DATE(t.transaction_date) = CURDATE()";
    
    if ($type) {
        $sql .= " AND t.type = :type";
    }
    
    $sql .= " ORDER BY t.created_at DESC";
    
    try {
        $stmt = $conn->prepare($sql);
        if ($type) {
            $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        return [];
    }
}

// Function to get today's total income
function getTodayIncome() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COALESCE(SUM(amount), 0) as total 
                             FROM transactions 
                             WHERE type = 'income' 
                             AND DATE(transaction_date) = CURDATE()");
        return $stmt->fetch()['total'];
    } catch(PDOException $e) {
        return 0;
    }
}

// Function to get today's total expenses
function getTodayExpenses() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT COALESCE(SUM(amount), 0) as total 
                             FROM transactions 
                             WHERE type = 'expense' 
                             AND DATE(transaction_date) = CURDATE()");
        return $stmt->fetch()['total'];
    } catch(PDOException $e) {
        return 0;
    }
}

// Function to add new transaction
function addTransaction($type, $amount, $category, $date = null) {
    global $conn;
    
    if (!$date) {
        $date = date('Y-m-d');
    }
    
    try {
        // Get category ID
        $categoryTable = $type == 'income' ? 'income_categories' : 'expense_categories';
        $stmt = $conn->prepare("SELECT id FROM {$categoryTable} WHERE name = :name");
        $stmt->execute(['name' => $category]);
        $categoryId = $stmt->fetch()['id'];
        
        // Insert transaction
        $sql = "INSERT INTO transactions (type, amount, category_id, transaction_date) 
                VALUES (:type, :amount, :category_id, :date)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            'type' => $type,
            'amount' => $amount,
            'category_id' => $categoryId,
            'date' => $date
        ]);
    } catch(PDOException $e) {
        return false;
    }
}

// Function to delete transaction
function deleteTransaction($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM transactions WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    } catch(PDOException $e) {
        return false;
    }
}

// Function to get categories
function getCategories($type) {
    global $conn;
    try {
        $table = $type == 'income' ? 'income_categories' : 'expense_categories';
        $stmt = $conn->query("SELECT * FROM {$table} ORDER BY name");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        return [];
    }
}
