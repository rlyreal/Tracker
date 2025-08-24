<?php
require_once 'config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Transaction ID is required']);
        exit;
    }
    
    try {
        $stmt = $conn->prepare("DELETE FROM transactions WHERE id = :id");
        $success = $stmt->execute(['id' => $id]);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Transaction deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting transaction']);
        }
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
