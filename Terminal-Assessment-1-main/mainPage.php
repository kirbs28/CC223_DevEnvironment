<!--
    This file is the main landing page for the customer.
    It is used to login the admin, and for dine in or take out the customer.
    Navigates to the menu.php for the customer to view the menu.
-->

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kainderia - Local Filipino Eatery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Imperial+Script&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
        }

        .main-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .logo-section {
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            font-family: 'Imperial Script', cursive;
            font-size: 6rem;
            font-weight: 400;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .logo-section .cutline {
            font-family: 'Monsterrat';
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .est-year {
            font-size: 1rem;
            margin-bottom: 1rem;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            top: 20px;
            z-index: 1100;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            color: #333;
        }

        #loginToggle {
            left: 20px;
            color: #333;
        }

        #loginToggle:hover {
            background: #ffffff;
            transform: scale(1.05);
        }

        #loginToggle i {
            font-size: 1.2rem;
        }

        .form-label {
            color: black;
        }

        .dine-button {
            background-color: transparent;
            border: 2px solid white;
            color: white;
            padding: 15px 60px;
            font-size: 1.5rem;
            letter-spacing: 3px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .dine-button:hover {
            background-color: white;
            color: black;
        }

        .modal-content {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 0;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-title {
            color: #000;
            font-weight: 600;
        }

        .modal-footer {
            border-top: none;
        }

        .order-type-btn {
            width: 200px;
            margin: 10px;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 0;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    
    <button class="toggle-btn" id="loginToggle">
        <i class="fas fa-user"></i>
    </button>

    <div class="main-container">
        <div class="logo-section">
            <div class="est-year">EST. 2025</div>
            <h1>Kainderia</h1>
            <p class="cutline">Authentic Filipino Cuisine</p>
        </div>
        <button class="btn dine-button" id="dineButton">DINE</button>
    </div>
    

    <!-- Order Type Modal -->
    <div class="modal fade" id="orderTypeModal" tabindex="-1" aria-labelledby="orderTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center w-100" id="orderTypeModalLabel">Choose Your Dining Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <button class="btn btn-dark order-type-btn" id="dineInBtn">Dine In</button>
                    <button class="btn btn-dark order-type-btn" id="takeOutBtn">Take Out</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Admin Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="adminLoginForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Bootstrap Modal
        const orderTypeModal = new bootstrap.Modal(document.getElementById('orderTypeModal'));
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        
        // Show login modal when login button is clicked
        document.getElementById('loginToggle').addEventListener('click', function() {
            loginModal.show();
        });

        // Handle admin login form submission
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // Send login request to check_admin.php
            fetch('check_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Login Successful',
                        text: 'Redirecting to dashboard...',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        didClose: () => {
                            document.body.style.transition = 'opacity 0.5s ease-out';
                            document.body.style.opacity = '0';
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 500);
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Login Failed',
                        text: 'Invalid username or password',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred during login',
                    icon: 'error'
                });
            });
        });
        
        // Show modal when DINE button is clicked
        document.getElementById('dineButton').addEventListener('click', () => {
            orderTypeModal.show();
        });

        // Function to handle order type selection
        function handleOrderType(type) {
            sessionStorage.setItem('orderType', type);
            
            orderTypeModal.hide();
            
            Swal.fire({
                title: type === 'dine_in' ? 'Dine In' : 'Take Out',
                text: `You selected ${type === 'dine_in' ? 'Dine In' : 'Take Out'}`,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                timerProgressBar: true,
                didClose: () => {
                    // Add fade out effect to the body
                    document.body.style.transition = 'opacity 0.5s ease-out';
                    document.body.style.opacity = '0';
                    
                    // Redirect after fade out animation
                    setTimeout(() => {
                        window.location.href = 'menu.php';
                    }, 500);
                }
            });
        }

        // Event listeners
        document.getElementById('dineInBtn').addEventListener('click', () => handleOrderType('dine_in'));
        document.getElementById('takeOutBtn').addEventListener('click', () => handleOrderType('take_out'));
    </script>
</body>
</html>