<?php
require_once 'config/db.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No debt ID provided']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM debts WHERE id = :id");
    $result = $stmt->execute(['id' => $_POST['id']]);
    
    echo json_encode(['success' => $result]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
