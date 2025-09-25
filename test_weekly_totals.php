<?php
require_once 'config/db.php';

$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));

$sql = "SELECT 
    c.name as category,
    SUM(t.amount) as total
FROM transactions t
JOIN categories c ON t.category_id = c.id
WHERE DATE(t.transaction_date) BETWEEN :start AND :end
AND t.type = 'expense'
AND c.name IN ('Breakfast', 'Lunch', 'Dinner', 'Bank Fee', 'Move it', 'Data Load')
GROUP BY c.name
ORDER BY c.name";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'start' => $weekStart,
    'end' => $weekEnd
]);

echo "Testing Weekly Totals for {$weekStart} to {$weekEnd}\n\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['category']}: ₱" . number_format($row['total'], 2) . "\n";
}
?>