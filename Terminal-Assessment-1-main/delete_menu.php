<!--
    This file is used to delete a menu item from the database.
-->

<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // First, check if the menu item exists in any orders
    $checkQuery = "SELECT COUNT(*) as count FROM order_items WHERE menu_item_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    
    if ($count > 0) {
        // If the item exists in orders, return an error
        http_response_code(400);
        echo json_encode(['error' => 'Cannot delete menu item as it exists in orders']);
        exit;
    }
    
    // If the item is not in any orders, proceed with deletion
    $deleteQuery = "DELETE FROM menu_items WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete menu item']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?> 