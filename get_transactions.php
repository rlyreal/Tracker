<?php
require_once 'config/db.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';

if (empty($type)) {
    echo json_encode(['error' => 'Type parameter is required']);
    exit;
}

try {
    $sql = "SELECT t.*, 
            CASE 
                WHEN t.type = 'income' THEN ic.name 
                ELSE ec.name 
            END as category_name 
            FROM transactions t 
            LEFT JOIN income_categories ic ON t.category_id = ic.id AND t.type = 'income'
            LEFT JOIN expense_categories ec ON t.category_id = ec.id AND t.type = 'expense'
            WHERE t.type = :type 
            AND DATE(t.transaction_date) = CURRENT_DATE()
            ORDER BY t.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['type' => $type]);
    
    $transactions = $stmt->fetchAll();
    echo json_encode($transactions);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
