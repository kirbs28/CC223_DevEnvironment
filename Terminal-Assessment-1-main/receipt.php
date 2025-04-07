<!--
    This file is used to get the order details from the database and display them as a receipt.
    Can download the receipt as a pdf.
    Can navigate back to the mainPage.php.
-->

<?php
include 'config.php';
session_start();

// Get order details from URL parameters
$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : null;

    if (!$orderId) {
        header('Location: menu.php');
        exit;
    }

    // Fetch order details
    $orderQuery = "SELECT * FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $orderQuery);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $orderResult = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($orderResult);

    if (!$order) {
        header('Location: menu.php');
        exit;
    }

    // Fetch order items
    $itemsQuery = "SELECT oi.*, m.name, m.price 
                FROM order_items oi 
                JOIN menu_items m ON oi.menu_item_id = m.id 
                WHERE oi.order_id = ?";
    $stmt = mysqli_prepare($conn, $itemsQuery);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $itemsResult = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($itemsResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Kainderia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Montserrat', sans-serif;
        }

        .receipt-container {
            max-width: 400px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #dee2e6;
        }

        .receipt-header h1 {
            font-family: 'Imperial Script', cursive;
            font-size: 3rem;
            margin-bottom: 5px;
            color: #333;
        }

        .receipt-header p {
            color: #666;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .receipt-body {
            margin-bottom: 30px;
        }

        .receipt-info {
            margin-bottom: 20px;
        }

        .receipt-info p {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #666;
        }

        .items-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .items-table th {
            text-align: left;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
            color: #666;
        }

        .items-table td {
            padding: 8px 0;
            color: #333;
        }

        .total-section {
            border-top: 2px dashed #dee2e6;
            padding-top: 20px;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .grand-total {
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 10px;
            color: #333;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #dee2e6;
            color: #666;
            font-size: 0.8rem;
        }

        .download-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #28a745;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: #218838;
            color: white;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            color: #333;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid #ddd;
            text-decoration: none;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
        }

        @media print {
            body {
                background: white;
            }
            .receipt-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
            }
            .download-btn, .back-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <a href="mainPage.php" class="back-btn" title="Back to Main Page">
        <i class="fas fa-times"></i>
    </a>

    <div class="receipt-container" id="receipt">
        <div class="receipt-header">
            <h1>Kainderia</h1>
            <p>Authentic Filipino Cuisine</p>
            <p>1700 San Dionisio, Paranaque City</p>
            <p>Tel: (222) 943-2319</p>
        </div>

        <div class="receipt-body">
            <div class="receipt-info">
                <p><strong>Order #:</strong> <?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                <p><strong>Date:</strong> <?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></p>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>Type:</strong> <?php echo ucfirst($order['order_type']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($order['order_status']); ?></p>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td style="text-align: right;">₱<?php echo number_format($item['price'], 2); ?></td>
                        <td style="text-align: right;">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-line">
                    <span>Subtotal:</span>
                    <span>₱<?php echo number_format($order['total_price'], 2); ?></span>
                </div>
                <div class="total-line grand-total">
                    <span>Total:</span>
                    <span>₱<?php echo number_format($order['total_price'], 2); ?></span>
                </div>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Thank you for dining with us!</p>
            <p>Please come again</p>
            <p>--- Kainderia ---</p>
        </div>
    </div>

    <a href="#" class="download-btn" id="downloadBtn" title="Download Receipt">
        <i class="fas fa-download"></i>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById('downloadBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            const receipt = document.getElementById('receipt');
            const options = {
                margin: 0.5,
                filename: 'Kainderia-Receipt-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    useCORS: true,
                    logging: true
                },
                jsPDF: { 
                    unit: 'in', 
                    format: 'letter', 
                    orientation: 'portrait'
                }
            };

            // Show loading state
            const btn = document.getElementById('downloadBtn');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.style.pointerEvents = 'none';

            html2pdf().from(receipt).set(options).save()
                .then(() => {
                    // Restore button state
                    btn.innerHTML = originalContent;
                    btn.style.pointerEvents = 'auto';
                })
                .catch(err => {
                    console.error('PDF generation failed:', err);
                    // Restore button state
                    btn.innerHTML = originalContent;
                    btn.style.pointerEvents = 'auto';
                    alert('Failed to generate PDF. Please try again.');
                });
        });
    </script>
</body>
</html> 