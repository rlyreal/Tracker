<?php
require_once 'config/db.php';

header('Content-Type: application/json');

try {
    // Get all-time totals
    $stmt = $conn->query("SELECT 
        COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
        COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expenses
        FROM transactions");
    
    $totals = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Calculate net balance (you can adjust the base balance here if needed)
    $baseBalance = 42774.8; // Your starting balance
    $netBalance = $baseBalance + $totals['total_income'] - $totals['total_expenses'];
    
    $totals['net_balance'] = $netBalance;
    
    echo json_encode($totals);
    
} catch (PDOException $e) {
    echo json_encode([
        'total_income' => 0, 
        'total_expenses' => 0, 
        'net_balance' => $baseBalance,
        'error' => $e->getMessage()
    ]);
}
?>
