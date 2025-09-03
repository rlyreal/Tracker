<?php
require_once 'config/db.php';

header('Content-Type: application/json');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    try {
        // First get the debt info
        $stmt = $conn->prepare("SELECT amount FROM debts WHERE id = ?");
        $stmt->execute([$id]);
        $debt = $stmt->fetch();
        
        if ($debt) {
            // Update both status columns and set paid date
            $updateStmt = $conn->prepare("UPDATE debts SET status = 'paid', paid_status = 'paid', paid_date = NOW() WHERE id = ?");
            $result = $updateStmt->execute([$id]);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Debt marked as paid',
                    'amount' => $debt['amount']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update debt status'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Debt not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Missing debt ID'
    ]);
}
?>
