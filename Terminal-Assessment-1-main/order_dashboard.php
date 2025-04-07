<!--
    This file is used as the order dashboard for the admin.
    It is used by admin view the order details of the customer.
    It is also used to update the order status of the customer.
-->

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dashboard - Kainderia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f5f5f0;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }

        .navbar {
            background: #f5f5f0;
            border-bottom: 1px solid #e0dcd0;
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-family: 'Imperial Script', cursive;
            font-size: 2.5rem;
            color: #333;
        }

        .nav-link {
            color: #333;
            font-weight: 500;
            margin: 0 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #666;
        }

        .card {
            border: 1px solid #e0dcd0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            background: #fff;
        }

        .card-header {
            background: #f5f5f0;
            border-bottom: 1px solid #e0dcd0;
            padding: 1rem;
        }

        .card-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0dcd0;
        }

        .table td {
            color: #666;
            vertical-align: middle;
        }

        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #333;
            border: none;
        }

        .btn-primary:hover {
            background: #222;
        }

        .btn-warning {
            background: #ffc107;
            border: none;
            color: #333;
        }

        .search-box {
            background: #fff;
            border: 1px solid #e0dcd0;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
        }

        .filter-select {
            background: #fff;
            border: 1px solid #e0dcd0;
            border-radius: 5px;
            padding: 0.5rem;
            margin-right: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Kainderia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Back to Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mainPage.php">Back to Main</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Order Management</h5>
                <div>
                    <input type="text" class="search-box" id="orderSearch" placeholder="Search orders...">
                    <select class="filter-select" id="orderStatusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select class="filter-select" id="orderTypeFilter">
                        <option value="">All Types</option>
                        <option value="dine_in">Dine In</option>
                        <option value="take_out">Take Out</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Type</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            <?php
                            $orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
                            while ($order = $orders->fetch_assoc()) {
                                $statusClass = "status-" . $order['order_status'];
                                echo "<tr>";
                                echo "<td>#{$order['id']}</td>";
                                echo "<td>{$order['customer_name']}</td>";
                                echo "<td>₱" . number_format($order['total_price'], 2) . "</td>";
                                echo "<td>" . ucfirst(str_replace('_', ' ', $order['order_type'])) . "</td>";
                                echo "<td>" . ucfirst($order['payment_method']) . "</td>";
                                echo "<td><span class='status-badge {$statusClass}'>" . ucfirst($order['order_status']) . "</span></td>";
                                echo "<td>" . date('M d, Y h:i A', strtotime($order['created_at'])) . "</td>";
                                echo "<td>
                                        <button class='btn btn-primary btn-sm' onclick='viewOrder({$order['id']})'>
                                            <i class='fas fa-eye'></i>
                                        </button>
                                        <button class='btn btn-warning btn-sm' onclick='updateStatus({$order['id']})'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Order Search and Filter
        document.getElementById('orderSearch').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const statusFilter = document.getElementById('orderStatusFilter').value;
            const typeFilter = document.getElementById('orderTypeFilter').value;
            filterOrderTable(searchText, statusFilter, typeFilter);
        });

        document.getElementById('orderStatusFilter').addEventListener('change', function(e) {
            const searchText = document.getElementById('orderSearch').value.toLowerCase();
            const typeFilter = document.getElementById('orderTypeFilter').value;
            filterOrderTable(searchText, e.target.value, typeFilter);
        });

        document.getElementById('orderTypeFilter').addEventListener('change', function(e) {
            const searchText = document.getElementById('orderSearch').value.toLowerCase();
            const statusFilter = document.getElementById('orderStatusFilter').value;
            filterOrderTable(searchText, statusFilter, e.target.value);
        });

        function filterOrderTable(searchText, status, type) {
            const rows = document.querySelectorAll('#orderTableBody tr');
            rows.forEach(row => {
                const customer = row.cells[1].textContent.toLowerCase();
                const orderStatus = row.cells[5].textContent.toLowerCase();
                const orderType = row.cells[3].textContent.toLowerCase();
                
                const matchesSearch = customer.includes(searchText);
                const matchesStatus = !status || orderStatus.includes(status);
                const matchesType = !type || orderType.includes(type);
                
                row.style.display = matchesSearch && matchesStatus && matchesType ? '' : 'none';
            });
        }

        // View Order Details
        function viewOrder(id) {
            fetch(`get_order_details.php?id=${id}`)
                .then(response => response.json())
                .then(order => {
                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>₱${parseFloat(item.price).toFixed(2)}</td>
                                <td>₱${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    Swal.fire({
                        title: 'Order Details',
                        html: `
                            <div class="receipt">
                                <h4 class="text-center mb-4">Kainderia</h4>
                                <p class="text-center">Order #${order.id}</p>
                                <p class="text-center">${new Date(order.created_at).toLocaleString()}</p>
                                <hr>
                                <p><strong>Customer:</strong> ${order.customer_name}</p>
                                <p><strong>Order Type:</strong> ${order.order_type.replace('_', ' ')}</p>
                                <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                                <hr>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${itemsHtml}
                                    </tbody>
                                </table>
                                <hr>
                                <div class="text-end">
                                    <h5>Total: ₱${parseFloat(order.total_price).toFixed(2)}</h5>
                                </div>
                            </div>
                        `,
                        width: '600px',
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                });
        }

        // Update Order Status
        function updateStatus(id) {
            Swal.fire({
                title: 'Update Order Status',
                input: 'select',
                inputOptions: {
                    'pending': 'Pending',
                    'completed': 'Completed',
                    'cancelled': 'Cancelled'
                },
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#666',
                confirmButtonText: 'Update'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('update_order_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: id,
                            status: result.value
                        })
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    }).then(() => {
                        Swal.fire('Updated!', 'Order status has been updated.', 'success').then(() => {
                            location.reload();
                        });
                    }).catch(error => {
                        Swal.fire('Error!', 'There was an error updating the status.', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html> 