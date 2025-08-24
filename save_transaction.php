<?php
require_once 'config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $category = $_POST['category'] ?? '';
    
    // Validate inputs
    if (empty($type) || empty($amount) || empty($category)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (!is_numeric($amount)) {
        echo json_encode(['success' => false, 'message' => 'Amount must be a number']);
        exit;
    }
    
    try {
        // Get category ID
        $categoryTable = $type == 'income' ? 'income_categories' : 'expense_categories';
        $stmt = $conn->prepare("SELECT id FROM {$categoryTable} WHERE name = :name");
        $stmt->execute(['name' => $category]);
        $result = $stmt->fetch();
        
        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Invalid category']);
            exit;
        }
        
        $categoryId = $result['id'];
        
        // Insert transaction
        $stmt = $conn->prepare("INSERT INTO transactions (type, amount, category_id, transaction_date) 
                               VALUES (:type, :amount, :category_id, CURRENT_DATE())");
        
        $success = $stmt->execute([
            'type' => $type,
            'amount' => $amount,
            'category_id' => $categoryId
        ]);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Transaction saved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error saving transaction']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
