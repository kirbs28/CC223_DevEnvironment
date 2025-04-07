<!--
    This file is used to process the order of the customer.
    It is used by menu.php to process the order of the customer.
    If process order returns success, it will navigate to the receipt.php.
-->

<?php
include 'config.php';
session_start();

header('Content-Type: application/json');

    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    // Validate required fields
    $requiredFields = ['customerName', 'totalPrice', 'order', 'paymentMethod', 'orderType'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            exit;
        }
    }

    $customerName = trim($data['customerName']);
    $totalPrice = floatval($data['totalPrice']);
    $orderItems = $data['order'];
    $paymentMethod = in_array(strtolower($data['paymentMethod']), ['cash', 'qr']) 
        ? strtolower($data['paymentMethod'])
        : 'cash'; // Default to cash if invalid
    $orderType = strtolower($data['orderType']);

    try {
        // Start transaction
        $conn->begin_transaction();

        // Insert into orders table
        $orderSql = "INSERT INTO orders (customer_name, total_price, order_status, payment_method, order_type, created_at) 
                    VALUES (?, ?, 'Pending', ?, ?, NOW())";
        $orderStmt = $conn->prepare($orderSql);
        
        if (!$orderStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $orderStmt->bind_param("sdss", $customerName, $totalPrice, $paymentMethod, $orderType);
        
        if (!$orderStmt->execute()) {
            throw new Exception("Order creation failed: " . $orderStmt->error);
        }
        
        $orderId = $conn->insert_id;

        // Insert into order_items table
        $itemSql = "INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES (?, ?, ?)";
        $itemStmt = $conn->prepare($itemSql);
        
        if (!$itemStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        foreach ($orderItems as $item) {
            $menuItemId = intval($item['id']);
            $quantity = intval($item['quantity']);
            
            if ($menuItemId <= 0 || $quantity <= 0) {
                continue; // Skip invalid items
            }
            
            $itemStmt->bind_param("iii", $orderId, $menuItemId, $quantity);
            
            if (!$itemStmt->execute()) {
                throw new Exception("Failed to add order item: " . $itemStmt->error);
            }
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true,
            'order_id' => $orderId
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Order processing failed: ' . $e->getMessage()]);
    } finally {
        // Close statements
        if (isset($orderStmt)) $orderStmt->close();
        if (isset($itemStmt)) $itemStmt->close();
        $conn->close();
    }
?>