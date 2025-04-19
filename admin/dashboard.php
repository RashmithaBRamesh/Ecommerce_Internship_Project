<?php
require_once '../includes/auth.php'; // Checks if admin is logged in
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .nav-links {
            display: flex;
            flex-direction: column;
            max-width: 300px;
        }
        .nav-links a {
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
            background-color: #f2f2f2;
            border-radius: 5px;
            color: #333;
        }
        .nav-links a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Welcome to Admin Dashboard</h2>

    <div class="nav-links">
        <a href="products/add.php">➕ Add Product</a>
        <a href="products/index.php">📦 View / Edit / Delete Products</a>
        <a href="reviews/index.php">📝 Manage Reviews</a>
        <a href="orders/index.php">📜 View Orders</a>
        <a href="logout.php">🚪 Logout</a>
        <a href="categories/index.php">📂 Manage Categories</a>

    </div>
</body>
</html>
