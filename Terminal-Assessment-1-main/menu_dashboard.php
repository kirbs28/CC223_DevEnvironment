<!--
    This file is used as the menu dashboard for the admin.
    It is used by admin to update, edit, or delete a menu item.
    It is also used to add a new menu item via addMenu.php.
-->

<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Dashboard - Kainderia</title>
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
                <h5 class="card-title">Menu Management</h5>
                <div>
                    <input type="text" class="search-box" id="menuSearch" placeholder="Search menu items...">
                    <select class="filter-select" id="menuCategoryFilter">
                        <option value="">All Categories</option>
                        <!-- Fetch all categories from the database and display them in the dropdown -->
                        <?php
                        $categories = $conn->query("SELECT DISTINCT category FROM menu_items");
                        while ($category = $categories->fetch_assoc()) {
                            echo "<option value='{$category['category']}'>{$category['category']}</option>";
                        }
                        ?>
                    </select>
                    <a href="addMenu.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Menu Item
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="menuTableBody">
                            <!-- Fetch all menu items from the database and display them in the table -->
                            <?php
                            $menuItems = $conn->query("SELECT * FROM menu_items ORDER BY category, name");
                            while ($item = $menuItems->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$item['name']}</td>";
                                echo "<td>{$item['description']}</td>";
                                echo "<td>₱" . number_format($item['price'], 2) . "</td>";
                                echo "<td>{$item['category']}</td>";
                                echo "<td>
                                        <button class='btn btn-warning btn-sm' onclick='editItem({$item['id']})'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button class='btn btn-danger btn-sm' onclick='deleteItem({$item['id']})'>
                                            <i class='fas fa-trash'></i>
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
        // Menu Search and Filter
        document.getElementById('menuSearch').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const categoryFilter = document.getElementById('menuCategoryFilter').value;
            filterMenuTable(searchText, categoryFilter);
        });

        document.getElementById('menuCategoryFilter').addEventListener('change', function(e) {
            const searchText = document.getElementById('menuSearch').value.toLowerCase();
            filterMenuTable(searchText, e.target.value);
        });

        // Filter the menu table based on the search text and category
        function filterMenuTable(searchText, category) {
            const rows = document.querySelectorAll('#menuTableBody tr');
            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const description = row.cells[1].textContent.toLowerCase();
                const rowCategory = row.cells[3].textContent;
                
                const matchesSearch = name.includes(searchText) || description.includes(searchText);
                const matchesCategory = !category || rowCategory === category;
                
                row.style.display = matchesSearch && matchesCategory ? '' : 'none';
            });
        }

        // Edit Menu Item
        function editItem(id) {
            fetch(`get_menu_item.php?id=${id}`)
                .then(response => response.json())
                .then(item => {
                    Swal.fire({
                        title: 'Edit Menu Item',
                        html: `
                            <form id="editForm">
                                <input type="hidden" name="id" value="${id}">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="${item.name}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" required>${item.description}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price" value="${item.price}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category" required>
                                        <option value="Appetizer" ${item.category === 'Appetizer' ? 'selected' : ''}>Appetizer</option>
                                        <option value="Main Course" ${item.category === 'Main Course' ? 'selected' : ''}>Main Course</option>
                                        <option value="Dessert" ${item.category === 'Dessert' ? 'selected' : ''}>Dessert</option>
                                        <option value="Beverage" ${item.category === 'Beverage' ? 'selected' : ''}>Beverage</option>
                                    </select>
                                </div>
                            </form>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#333',
                        cancelButtonColor: '#666',
                        confirmButtonText: 'Update',
                        preConfirm: () => {
                            const form = document.getElementById('editForm');
                            const formData = new FormData(form);
                            return fetch('edit.php', {
                                method: 'POST',
                                body: formData
                            }).then(response => {
                                if (!response.ok) {
                                    throw new Error(response.statusText);
                                }
                                return response.json();
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Updated!', 'The menu item has been updated.', 'success').then(() => {
                                location.reload();
                            });
                        }
                    });
                });
        }

        // Delete Menu Item
        function deleteItem(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#333',
                cancelButtonColor: '#666',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`delete_menu.php?id=${id}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', 'The menu item has been deleted.', 'success')
                                    .then(() => {
                                        location.reload();
                                    });
                            } else {
                                Swal.fire('Error!', data.error || 'Failed to delete the item.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'There was an error deleting the item.', 'error');
                        });
                }
            });
        }
    </script>
</body>
</html> 