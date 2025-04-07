<!--
    This file is used to add a menu item to the database.
-->

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item - Kainderia</title>
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
            background: #fff;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e0dcd0;
        }

        .card-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border: 1px solid #e0dcd0;
            border-radius: 5px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #333;
            box-shadow: 0 0 0 0.2rem rgba(51, 51, 51, 0.25);
        }

        .btn {
            font-weight: 500;
            padding: 0.75rem 1.5rem;
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

        .btn-secondary {
            background: #666;
            border: none;
        }

        .btn-secondary:hover {
            background: #555;
        }

        .image-preview {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            display: none;
            margin-top: 1rem;
            border: 1px solid #e0dcd0;
        }

        .preview-container {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Kainderia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="menu_dashboard.php">Back to Menu Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mainPage.php">Back to Main</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add Menu Item Form -->
    <div class="container mt-4">
        <div class="card">
            <h2 class="card-title">Add Menu Item</h2>
            <form action="store.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a brief description of the menu item"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="Appetizer">Appetizer</option>
                                <option value="Main Course">Main Course</option>
                                <option value="Dessert">Dessert</option>
                                <option value="Beverage">Beverage</option>
                            </select>
                        </div>
                    </div>
                    <!-- Menu Item Image -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Menu Item Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="preview-container">
                                <img id="imagePreview" class="image-preview" alt="Preview">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Menu Item
                    </button>
                    <a href="menu_dashboard.php" class="btn btn-secondary ms-2">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });

        // Form submission with SWAL
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Add Menu Item?',
                text: "Are you sure you want to add this menu item?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#666',
                confirmButtonText: 'Yes, add it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(this);
                    fetch('store.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonColor: '#333'
                            }).then(() => {
                                window.location.href = 'menu_dashboard.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Something went wrong.',
                                confirmButtonColor: '#333'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to submit the form.',
                            confirmButtonColor: '#333'
                        });
                    });
                }
            });
        });
    </script>
</body>
</html>