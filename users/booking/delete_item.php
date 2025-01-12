<?php
include('../../sessioncheck.php');
include('../../connection.php');

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST and the body contains JSON data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['ids']) && is_array($data['ids'])) {
        $cart_ids = $data['ids'];

        // Ensure cart_ids is an array and not empty
        if (count($cart_ids) > 0) {
            // Use placeholders for prepared statements
            $placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
            $query = "DELETE FROM cart WHERE id IN ($placeholders) AND user_id = ?";

            // Prepare the SQL statement
            $stmt = $conn->prepare($query);
            if ($stmt) {
                // Bind parameters dynamically
                $types = str_repeat('i', count($cart_ids)) . 'i'; // 'i' for integers
                $params = array_merge($cart_ids, [$_SESSION['id']]);
                $stmt->bind_param($types, ...$params);

                // Execute the statement
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
                }

                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL statement: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid cart_ids array.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
