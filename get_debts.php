<?php
require_once 'config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT * FROM debts WHERE status = 'pending' ORDER BY due_date ASC");
    $stmt->execute();
    $debts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($debts);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
