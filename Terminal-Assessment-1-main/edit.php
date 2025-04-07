<!--
    This file is used to edit a menu item in the database.
    It is used along with get_menu_item.php to edit a menu item.
-->

<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Update the menu item in the database
    $sql = "UPDATE menu_items SET name = ?, description = ?, price = ?, category = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $name, $description, $price, $category, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Menu item updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating menu item: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>