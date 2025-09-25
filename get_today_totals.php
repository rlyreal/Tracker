<?php
require_once 'config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT 
        COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
        COALESCE(SUM(CASE WHEN type = 'expense' AND category_id != 18 THEN amount ELSE 0 END), 0) as expenses,
        COALESCE(SUM(CASE WHEN type = 'expense' AND category_id = 18 THEN amount ELSE 0 END), 0) as borrowed_load
        FROM transactions 
        WHERE DATE(transaction_date) = CURRENT_DATE()");
    
    $totals = $stmt->fetch();
    echo json_encode($totals);
    
} catch (PDOException $e) {
    echo json_encode(['income' => 0, 'expenses' => 0, 'borrowed_load' => 0, 'error' => $e->getMessage()]);
}
?>
