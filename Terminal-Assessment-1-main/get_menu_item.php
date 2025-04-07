<!--
    This file is used to get a menu item from the database.
    It is used to get the menu item details for the edit page.
-->
<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM menu_items WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the item exists / row is returned from query
    if ($item = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode($item);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Menu item not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID parameter is required']);
}
?> 