<!--
    This file is used to update the order status of the customer.
    It is used by order_dashboard.php to update the order status of the customer.
    Can update the order status to pending, completed, or cancelled.
    Changes on order status is reflected on index.php.
-->

<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['order_id']) && isset($data['status'])) {
        $order_id = $data['order_id'];
        $status = $data['status'];
        
        // Validate status
        $validStatuses = ['pending', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status']);
            exit;
        }
        
        $query = "UPDATE orders SET order_status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $order_id);
        
        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => 'Order status updated successfully',
                'new_status' => $status
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update order status']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Order ID and status are required']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?> 