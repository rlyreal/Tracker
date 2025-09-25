<?php
require_once 'config/db.php';

header('Content-Type: application/json');

try {
    $extended = isset($_GET['extended']) && $_GET['extended'] === 'true';
    $monthStart = date('Y-m-01');
    $monthEnd = date('Y-m-t');
    
    // Get current month totals
    $stmt = $conn->prepare("SELECT 
        COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
        COALESCE(SUM(CASE WHEN type = 'expense' OR category_id IN (18, 19) THEN amount ELSE 0 END), 0) as expenses
        FROM transactions 
        WHERE transaction_date BETWEEN :start AND :end");
    
    $stmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($extended) {
        // Get current month's daily stats
        $dailyStmt = $conn->prepare("SELECT 
            transaction_date as date,
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
            COALESCE(SUM(CASE WHEN type = 'expense' OR category_id IN (18, 19) THEN amount ELSE 0 END), 0) as expenses,
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END), 0) as net
            FROM transactions 
            WHERE transaction_date BETWEEN :start AND :end
            GROUP BY transaction_date
            ORDER BY transaction_date");
        
        $dailyStmt->execute(['start' => $monthStart, 'end' => $monthEnd]);
        $data['daily_stats'] = $dailyStmt->fetchAll(PDO::FETCH_ASSOC);

        // Get previous month's daily stats
        $prevMonthStart = date('Y-m-01', strtotime('-1 month'));
        $prevMonthEnd = date('Y-m-t', strtotime('-1 month'));

        $prevDailyStmt = $conn->prepare("SELECT 
            transaction_date as date,
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
            COALESCE(SUM(CASE WHEN type = 'expense' OR category_id IN (18, 19) THEN amount ELSE 0 END), 0) as expenses,
            COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END), 0) as net
            FROM transactions 
            WHERE transaction_date BETWEEN :start AND :end
            GROUP BY transaction_date
            ORDER BY transaction_date");
        
        $prevDailyStmt->execute(['start' => $prevMonthStart, 'end' => $prevMonthEnd]);
        $data['prev_daily_stats'] = $prevDailyStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($data);
    
} catch (PDOException $e) {
    echo json_encode([
        'income' => 0, 
        'expenses' => 0, 
        'error' => $e->getMessage(),
        'daily_stats' => [],
        'prev_daily_stats' => []
    ]);
}
?>
