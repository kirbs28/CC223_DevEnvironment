<!--
    This file is the main menu page for the customer.
    It is used by the customer to view the menu and order food.
    Has a sidebar for categories of food and order cart to view the order.
    Proceeds to receipt.php after checkout.
-->

<?php
include 'config.php';

    // Get order type from session
    $orderType = isset($_GET['type']) ? $_GET['type'] : (isset($_SESSION['orderType']) ? $_SESSION['orderType'] : 'dine_in');
    $_SESSION['orderType'] = $orderType;

    // Get all categories
    $categoriesQuery = "SELECT DISTINCT category FROM menu_items";
    $categoriesResult = mysqli_query($conn, $categoriesQuery);
    $categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);

    // Get selected category (default to all)
    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';

    // Fetch menu items
    $query = ($selectedCategory === 'all') 
        ? "SELECT * FROM menu_items" 
        : "SELECT * FROM menu_items WHERE category = '$selectedCategory'";
    $result = mysqli_query($conn, $query);
    $menu_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Imperial+Script&family=Montserrat:wght@300;400;500;600&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: #f5f5f0;
            color: #333;
            font-family: 'Montserrat', sans-serif;
        }

        h1 {
            font-family: 'Imperial Script', cursive;
            font-size: 4rem;
            margin-bottom: 2rem;
            text-align: center;
            letter-spacing: 2px;
            color: #333;
        }
        
        /* Toggle Buttons */
        .toggle-btn {
            position: fixed;
            top: 20px;
            z-index: 1100;
            background: #f5f5f0;
            border: 1px solid #e0dcd0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            color: #333;
        }
        
        #sidebarToggle {
            left: 20px;
        }
        
        #cartToggle {
            right: 20px;
        }

        .toggle-btn:hover {
            background: #e0dcd0;
        }

        /* Cart Badge */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: #f5f5f0;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            position: fixed;
            left: -250px;
            top: 0;
            bottom: 0;
            background: #f5f5f0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            padding: 80px 25px 25px;
            border-right: 1px solid #e0dcd0;
        }
        
        .sidebar.active {
            left: 0;
        }

        .sidebar h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            padding-left: 10px;
        }

        .list-group-item {
            border: none;
            background: transparent;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 5px;
            padding: 12px 15px;
            margin-bottom: 5px;
        }

        .list-group-item:hover {
            background: #e0dcd0;
        }

        .list-group-item.active {
            background: #333;
            color: #f5f5f0;
        }
        
        /* Cart */
        .cart {
            width: 300px;
            max-height: 80vh;
            position: fixed;
            right: -300px;
            top: 70px;
            background: #f5f5f0;
            z-index: 1000;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 25px;
            overflow-y: auto;
            border: 1px solid #e0dcd0;
        }
        
        .cart.active {
            right: 20px;
        }
        
        .cart h5 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
            padding-left: 10px;
        }
        
        /* Menu Card Styles */
        .menu-card {
            border: 1px solid #e0dcd0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: all 0.3s;
            background: #fff;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #333;
            color: #f5f5f0;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .card-text {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            color: #666;
        }
        
        .price-tag {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }

        .quantity-input {
            background: #f5f5f0;
            border: 1px solid #e0dcd0;
            color: #333;
        }

        .add-to-order {
            background: #333;
            border: none;
            color: #f5f5f0;
            transition: all 0.3s ease;
        }

        .add-to-order:hover {
            background: #222;
        }

        /* Cart Items */
        .list-group-item {
            background: transparent;
            border: none;
            border-bottom: 1px solid #e0dcd0;
            padding: 15px 10px;
            margin-bottom: 0;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .remove-item {
            background: transparent;
            border: none;
            color: #dc3545;
            transition: all 0.3s ease;
            padding: 5px;
            margin-left: 10px;
        }

        .remove-item:hover {
            color: #c82333;
        }

        /* Order Total */
        .d-flex.justify-content-between.align-items-center.mt-2 {
            padding: 15px 10px;
            border-top: 1px solid #e0dcd0;
            margin-top: 15px;
        }

        /* Checkout Button */
        #checkoutBtn {
            margin-top: 20px;
            width: calc(100% - 20px);
            margin-left: 10px;
        }

        /* Main Content */
        .main-content {
            transition: all 0.3s ease;
            padding: 20px;
            background: #f5f5f0;
        }
        
        .main-content.sidebar-active {
            margin-left: 250px;
        }
        
        .main-content.cart-active {
            margin-right: 320px;
        }
        
        @media (max-width: 768px) {
            .main-content.sidebar-active, 
            .main-content.cart-active {
                margin-left: 0;
                margin-right: 0;
            }
        }

        /* Logout Button */
        .btn-logout {
            background: #333;
            border: none;
            color: #f5f5f0;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #666;
        }

    </style>
</head>
<body>
    <!-- Toggle Buttons -->
    <button class="toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <button class="toggle-btn" id="cartToggle">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-badge" id="cartBadge" style="display: none;">0</span>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 class="mb-3">Categories</h4>
        <div class="list-group">
            <a href="menu.php?category=all&type=<?= $orderType ?>" 
            class="list-group-item list-group-item-action <?= $selectedCategory === 'all' ? 'active' : '' ?>">
                <i class="fas fa-utensils me-2"></i> All Items
            </a>
            <!-- Fetch all categories from the database and display them in the sidebar -->
            <?php foreach ($categories as $category): ?>
            <a href="menu.php?category=<?= urlencode($category['category']) ?>&type=<?= $orderType ?>" 
            class="list-group-item list-group-item-action <?= $selectedCategory === $category['category'] ? 'active' : '' ?>">
                <i class="fas <?= 
                    $category['category'] === 'Main Course' ? 'fa-drumstick-bite' : 
                    ($category['category'] === 'Beverage' ? 'fa-glass-whiskey' : 
                    'fa-utensils') 
                ?> me-2"></i>
                <?= htmlspecialchars($category['category']) ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Logout Button at Bottom -->
        <div class="mt-auto pt-3">
            <a href="mainPage.php" class="btn btn-logout w-100 mt-3" id="logoutBtn">
                <i class="fas fa-sign-out-alt me-2"></i> Log Out
            </a>
        </div>
    </div>

    <!-- Order Cart -->
    <div class="cart" id="cart">
        <h5 class="d-flex justify-content-between align-items-center">
            <span>Your Order</span>
            <span class="badge bg-primary rounded-pill" id="cartCount">0</span>
        </h5>
        <div id="order-items-container">
            <div class="alert alert-info empty-cart">Your order cart is empty.</div>
            <ul id="order-items" class="list-group mb-3" style="display: none;"></ul>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <strong>Total:</strong>
            <strong id="order-total">₱0.00</strong>
        </div>
        <button class="btn btn-success w-100 mt-3" id="checkoutBtn">
            <i class="fas fa-shopping-cart me-2"></i> Checkout
        </button>
    </div>

    <!-- Main Menu -->
    <div class="main-content" id="mainContent">
        <h1 class="text-center mb-4">Our Menu</h1>
        <div class="row">
            <!-- Fetch all menu items from the database and display them in the main menu -->
            <?php if (empty($menu_items)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">No items found in this category.</div>
                </div>
            <?php else: ?>
                <?php foreach ($menu_items as $item): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card menu-card h-100">
                        <?php if ($item['image_path']): ?>
                            <img src="<?= $item['image_path'] ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No image">
                        <?php endif; ?>
                        <div class="category-badge"><?= $item['category'] ?></div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">₱<?= number_format($item['price'], 2) ?></span>
                                <div class="input-group" style="width: 120px;">
                                    <input type="number" class="form-control quantity-input" min="1" value="1" data-id="<?= $item['id'] ?>">
                                    <button class="btn btn-primary add-to-order" 
                                            data-id="<?= $item['id'] ?>" 
                                            data-name="<?= htmlspecialchars($item['name']) ?>" 
                                            data-price="<?= $item['price'] ?>">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle Sidebar
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-active');
            
            // Change icon based on state
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Logout Confirmation
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const logoutUrl = this.href;
            
            Swal.fire({
                title: 'Log Out?',
                text: 'Are you sure you want to return to the main page?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#666',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = logoutUrl;
                }
            });
        });
        
        // Toggle Order Cart
        const cartToggle = document.getElementById('cartToggle');
        const cart = document.getElementById('cart');
        
        cartToggle.addEventListener('click', function() {
            cart.classList.toggle('active');
            mainContent.classList.toggle('cart-active');
            
            // Change icon based on state
            const icon = this.querySelector('i');
            if (cart.classList.contains('active')) {
                icon.classList.remove('fa-shopping-cart');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-shopping-cart');
            }
        });
        
        // Order Cart functionality
        let order = [];
        
        document.querySelectorAll('.add-to-order').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = parseFloat(this.dataset.price);
                const quantity = parseInt(document.querySelector(`.quantity-input[data-id="${id}"]`).value);
                
                const existingItem = order.find(item => item.id == id);
                if (existingItem) {
                    existingItem.quantity += quantity;
                } else {
                    order.push({ id, name, price, quantity });
                }
                
                updateCart();
            });
        });
        
        // Update Order Cart
        function updateCart() {
            const orderItems = document.getElementById('order-items');
            const emptyCart = document.querySelector('.empty-cart');
            const orderTotal = document.getElementById('order-total');
            const cartCount = document.getElementById('cartCount');
            const cartBadge = document.getElementById('cartBadge');
            
            orderItems.innerHTML = '';
            let total = 0;
            let itemCount = 0;
            
            order.forEach(item => {
                total += item.price * item.quantity;
                itemCount += item.quantity;
                
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.innerHTML = `
                    <div>
                        <strong>${item.name}</strong>
                        <div class="text-muted">${item.quantity} × ₱${item.price.toFixed(2)}</div>
                    </div>
                    <button class="btn btn-sm btn-danger remove-item" data-id="${item.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                orderItems.appendChild(li);
            });
            
            // Show/hide empty cart message
            if (order.length > 0) {
                emptyCart.style.display = 'none';
                orderItems.style.display = 'block';
                cartCount.textContent = itemCount;
                cartBadge.textContent = itemCount;
                cartBadge.style.display = 'flex';
            } else {
                emptyCart.style.display = 'block';
                orderItems.style.display = 'none';
                cartCount.textContent = '0';
                cartBadge.style.display = 'none';
            }
            
            // Update total
            orderTotal.textContent = `₱${total.toFixed(2)}`;
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    order = order.filter(item => item.id != id);
                    updateCart();
                });
            });
        }

        
        // Checkout functionality
        document.getElementById('checkoutBtn').addEventListener('click', function() {
            if (order.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Order',
                    text: 'Your order is empty. Please add items to your order before checking out.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            const orderType = sessionStorage.getItem('orderType') || 'dine_in';
            
            // Customer Name Input
            Swal.fire({
                title: 'Enter Customer Name',
                input: 'text',
                inputPlaceholder: 'Customer Name',
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#666',
                confirmButtonText: 'Next',
                preConfirm: (customerName) => {
                    if (!customerName) {
                        Swal.showValidationMessage('Customer name is required');
                    }
                    return customerName;
                }
            }).then(result => {
                if (result.isConfirmed) {
                    const customerName = result.value;

                    // Payment Method Input 
                    Swal.fire({
                        title: 'Select Payment Method',
                        input: 'radio',
                        showCancelButton: true,
                        confirmButtonColor: '#333',
                        cancelButtonColor: '#666',
                        inputOptions: {
                            cash: 'Cash',
                            qr: 'QR Code'
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to select a payment method!';
                            }
                        }
                    }).then(paymentResult => {
                        if (paymentResult.isConfirmed) {
                            // Process Order via processOrder.php
                            const paymentMethod = paymentResult.value;
                            const totalPrice = order.reduce((sum, item) => sum + (item.price * item.quantity), 0);

                            fetch('processOrder.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ 
                                    customerName, 
                                    totalPrice, 
                                    order, 
                                    paymentMethod,
                                    orderType
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Order Placed',
                                        text: 'Please go to the counter to pay.',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        timerProgressBar: true,
                                        didClose: () => {
                                            document.body.style.transition = 'opacity 0.5s ease-out';
                                            document.body.style.opacity = '0';
                                            setTimeout(() => {
                                                window.location.href = `receipt.php?order_id=${data.order_id}`;
                                            }, 500);
                                        }
                                    });
                                } else {
                                    Swal.fire('Error', data.message || 'Failed to place the order.', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Error', 'Failed to process the order.', 'error');
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>