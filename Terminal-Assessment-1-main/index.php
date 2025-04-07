<!--
    This file is the main dashboard for the admin.
    It is used to manage the menu, orders, and statistics.
    Can navigate to the menu_dashboard.php and order_dashboard.php.
-->

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kainderia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .dashboard-tabs {
            background: #fff;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .dashboard-tabs .nav-link {
            color: #666;
            border: none;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
            border-radius: 5px;
        }

        .dashboard-tabs .nav-link.active {
            background: #333;
            color: #fff;
        }

        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e0dcd0;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .dashboard-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .dashboard-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .dashboard-card p {
            color: #666;
            margin: 0;
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

        .btn-danger {
            background: #dc3545;
            border: none;
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

        .dashboard-content {
            display: none;
        }

        .dashboard-content.active {
            display: block;
        }

        .chart-container {
            background: #fff;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e0dcd0;
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
                        <a class="nav-link" href="mainPage.php">Back to Main</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="menu_dashboard.php" class="text-decoration-none">
                    <div class="dashboard-card">
                        <i class="fas fa-utensils"></i>
                        <h3>Menu Dashboard</h3>
                        <p>Manage your menu items, categories, and prices</p>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="order_dashboard.php" class="text-decoration-none">
                    <div class="dashboard-card">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Order Dashboard</h3>
                        <p>View and manage customer orders</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fas fa-utensils"></i>
                    <h3>
                        <?php
                        $menuCount = $conn->query("SELECT COUNT(*) as count FROM menu_items")->fetch_assoc()['count'];
                        echo $menuCount;
                        ?>
                    </h3>
                    <p>Menu Items</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>
                        <?php
                        $orderCount = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
                        echo $orderCount;
                        ?>
                    </h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fas fa-clock"></i>
                    <h3>
                        <?php
                        $pendingCount = $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'")->fetch_assoc()['count'];
                        echo $pendingCount;
                        ?>
                    </h3>
                    <p>Pending Orders</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <i class="fas fa-money-bill-wave"></i>
                    <h3>
                        <?php
                        $totalRevenue = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE order_status = 'completed'")->fetch_assoc()['total'];
                        echo "₱" . number_format($totalRevenue, 2);
                        ?>
                    </h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Orders by Status</h5>
                    <canvas id="ordersByStatusChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="mb-3">Revenue by Month</h5>
                    <canvas id="revenueByMonthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Charts
        const ordersByStatusCtx = document.getElementById('ordersByStatusChart').getContext('2d');
        const revenueByMonthCtx = document.getElementById('revenueByMonthChart').getContext('2d');

        // Orders by Status Chart
        new Chart(ordersByStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'")->fetch_assoc()['count']; ?>,
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'completed'")->fetch_assoc()['count']; ?>,
                        <?php echo $conn->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'cancelled'")->fetch_assoc()['count']; ?>
                    ],
                    backgroundColor: ['#ffc107', '#28a745', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Revenue by Month Chart
        new Chart(revenueByMonthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [
                        <?php
                        $revenueData = [];
                        for ($i = 1; $i <= 12; $i++) {
                            $month = str_pad($i, 2, '0', STR_PAD_LEFT);
                            $revenue = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE order_status = 'completed' AND DATE_FORMAT(created_at, '%m') = '$month'")->fetch_assoc()['total'];
                            $revenueData[] = $revenue ? $revenue : 0;
                        }
                        echo implode(',', $revenueData);
                        ?>
                    ],
                    borderColor: '#333',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>

