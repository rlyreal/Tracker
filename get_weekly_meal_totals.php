<?php
require_once 'config/db.php';

// Get the current week's start and end dates
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));

// Prepare SQL query to get weekly totals for specific categories
$sql = "SELECT 
            c.name as category,
            COALESCE(SUM(CASE WHEN t.type = 'expense' AND DATE(t.transaction_date) BETWEEN :start AND :end THEN t.amount ELSE 0 END), 0) as total_amount
        FROM categories c
        LEFT JOIN transactions t ON t.category_id = c.id 
        WHERE c.name IN ('Breakfast', 'Lunch', 'Dinner', 'Bank Fee', 'Move it', 'Moveit', 'Data Load')
        GROUP BY c.name";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'start' => $weekStart,
        'end' => $weekEnd
    ]);

    // Initialize result array
    $result = [
        'breakfast_total' => 0,
        'lunch_total' => 0,
        'dinner_total' => 0,
        'bank_fee_total' => 0,
        'moveit_total' => 0,
        'data_load_total' => 0
    ];

    // Fill in actual values
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $category = $row['category'];
        $amount = (float)$row['total_amount'];
        
        // Handle possible variations in Move it category name
        if ($category === 'Move it' || $category === 'Moveit') {
            $result['moveit_total'] += $amount;
        } else {
            $category_key = strtolower(str_replace(' ', '_', $category)) . '_total';
            $result[$category_key] = $amount;
        }
    }

    // Calculate the grand total
    $values_to_sum = [
        $result['breakfast_total'],
        $result['lunch_total'],
        $result['dinner_total'],
        $result['bank_fee_total'],
        $result['moveit_total'],
        $result['data_load_total']
    ];
    
    $result['total'] = array_sum($values_to_sum);

    // Add week start and end dates
    $result['week_start'] = $weekStart;
    $result['week_end'] = $weekEnd;
    
    // Add calculation debug info
    $result['debug']['calculation'] = [
        'breakfast' => $result['breakfast_total'],
        'lunch' => $result['lunch_total'],
        'dinner' => $result['dinner_total'],
        'bank_fee' => $result['bank_fee_total'],
        'moveit' => $result['moveit_total'],
        'data_load' => $result['data_load_total'],
        'sum' => $result['total']
    ];

    // Add debug information
    $result['debug'] = [
        'success' => true,
        'week_start' => $weekStart,
        'week_end' => $weekEnd,
        'query' => $sql,
        'params' => ['start' => $weekStart, 'end' => $weekEnd],
        'raw_data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ];

} catch (PDOException $e) {
    http_response_code(500);
    $result = [
        'error' => 'Database error occurred',
        'debug' => [
            'success' => false,
            'message' => $e->getMessage(),
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'query' => $sql
        ]
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($result);
?>
