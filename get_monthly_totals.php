<?php
require_once 'config/db.php';

header('Content-Type: application/json');

try {
    $monthStart = date('Y-m-01');
    $monthEnd = date('Y-m-t');
    
    $stmt = $conn->prepare("SELECT 
        COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
        COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expenses
        FROM transactions 
        WHERE transaction_date BETWEEN :start AND :end");
    
    $stmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
    $totals = $stmt->fetch();
    echo json_encode($totals);
    
} catch (PDOException $e) {
    echo json_encode(['income' => 0, 'expenses' => 0, 'error' => $e->getMessage()]);
}
?>
