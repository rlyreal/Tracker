<?php
require_once 'config/db.php';

header('Content-Type: application/json');

// Get filter parameters
$type = $_GET['type'] ?? 'all';
$dateFilter = $_GET['dateFilter'] ?? 'all';
$startDate = $_GET['startDate'] ?? '';
$endDate = $_GET['endDate'] ?? '';
$minAmount = $_GET['minAmount'] ?? '';
$maxAmount = $_GET['maxAmount'] ?? '';
$category = $_GET['category'] ?? 'all';

try {
    $params = [];
    $conditions = [];

    // Base query
    $sql = "SELECT t.*, 
            CASE 
                WHEN t.type = 'income' THEN ic.name 
                ELSE ec.name 
            END as category_name 
            FROM transactions t 
            LEFT JOIN income_categories ic ON t.category_id = ic.id AND t.type = 'income'
            LEFT JOIN expense_categories ec ON t.category_id = ec.id AND t.type = 'expense'
            WHERE 1=1";

    // Type filter
    if ($type !== 'all') {
        $conditions[] = "t.type = :type";
        $params['type'] = $type;
    }

    // Date filter
    switch ($dateFilter) {
        case 'today':
            $conditions[] = "DATE(t.transaction_date) = CURRENT_DATE()";
            break;
        case 'week':
            $conditions[] = "t.transaction_date >= DATE_SUB(CURRENT_DATE(), INTERVAL WEEKDAY(CURRENT_DATE()) DAY)";
            break;
        case 'month':
            $conditions[] = "YEAR(t.transaction_date) = YEAR(CURRENT_DATE()) AND MONTH(t.transaction_date) = MONTH(CURRENT_DATE())";
            break;
        case 'custom':
            if ($startDate) {
                $conditions[] = "DATE(t.transaction_date) >= :start_date";
                $params['start_date'] = $startDate;
            }
            if ($endDate) {
                $conditions[] = "DATE(t.transaction_date) <= :end_date";
                $params['end_date'] = $endDate;
            }
            break;
    }

    // Amount filter
    if ($minAmount !== '') {
        $conditions[] = "t.amount >= :min_amount";
        $params['min_amount'] = $minAmount;
    }
    if ($maxAmount !== '') {
        $conditions[] = "t.amount <= :max_amount";
        $params['max_amount'] = $maxAmount;
    }

    // Category filter
    if ($category === 'income') {
        $conditions[] = "t.type = 'income'";
    } elseif ($category === 'expense') {
        $conditions[] = "t.type = 'expense'";
    }

    // Add conditions to query
    if (!empty($conditions)) {
        $sql .= " AND " . implode(" AND ", $conditions);
    }

    // Add order by
    $sql .= " ORDER BY t.transaction_date DESC, t.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    
    $transactions = $stmt->fetchAll();
    echo json_encode(['success' => true, 'transactions' => $transactions]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
