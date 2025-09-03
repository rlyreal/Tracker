<?php
require_once 'config/db.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $conn->prepare("INSERT INTO debts (debtor_name, amount, due_date, notes, status, created_date) 
                           VALUES (:debtor_name, :amount, :due_date, :notes, 'pending', NOW())");
    
    $result = $stmt->execute([
        'debtor_name' => $data['debtor_name'],
        'amount' => $data['amount'],
        'due_date' => $data['due_date'],
        'notes' => $data['notes']
    ]);
    
    echo json_encode(['success' => $result]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
