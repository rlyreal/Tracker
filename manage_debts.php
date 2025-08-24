<?php
require_once 'config/db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Log the request method and data
error_log('Request Method: ' . $_SERVER['REQUEST_METHOD']);
error_log('POST Data: ' . print_r($_POST, true));

// Test database connection
try {
    $testQuery = $conn->query("SELECT 1 FROM debts LIMIT 1");
    error_log("Database connection test: successful");
} catch(PDOException $e) {
    error_log("Database connection test failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Handle GET request to fetch all debts
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->query("SELECT * FROM debts ORDER BY created_at DESC");
        $debts = $stmt->fetchAll();
        echo json_encode(['success' => true, 'debts' => $debts]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch debts', 'error' => $e->getMessage()]);
    }
}

// Handle POST requests for adding, updating, or deleting debts
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            try {
                // Log the incoming data
                error_log("Adding new debt: " . print_r($_POST, true));
                
                // Validate required fields
                if (empty($_POST['debtor_name']) || !isset($_POST['amount'])) {
                    echo json_encode(['success' => false, 'message' => 'Debtor name and amount are required']);
                    return;
                }

                // Prepare the data
                $debtor_name = trim($_POST['debtor_name']);
                $amount = floatval($_POST['amount']);
                $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
                $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

                // Insert the debt record
                $stmt = $conn->prepare("INSERT INTO debts (debtor_name, amount, description, due_date) VALUES (:debtor_name, :amount, :description, :due_date)");
                $result = $stmt->execute([
                    'debtor_name' => $debtor_name,
                    'amount' => $amount,
                    'description' => $description,
                    'due_date' => $due_date
                ]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Debt added successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add debt']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error', 'error' => $e->getMessage()]);
            }
            break;
            
        case 'mark_paid':
            try {
                $stmt = $conn->prepare("UPDATE debts SET status = 'paid' WHERE id = :id");
                $result = $stmt->execute(['id' => $_POST['id']]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Debt marked as paid']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update debt']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error', 'error' => $e->getMessage()]);
            }
            break;
            
        case 'delete':
            try {
                $stmt = $conn->prepare("DELETE FROM debts WHERE id = :id");
                $result = $stmt->execute(['id' => $_POST['id']]);
                
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Debt deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete debt']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Database error', 'error' => $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}
