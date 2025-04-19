<?php
require_once '../../includes/auth.php';
require_once '../../config/db.php';

$stmt = $pdo->query("SELECT products.*, categories.name AS category 
                     FROM products 
                     JOIN categories ON products.category_id = categories.id 
                     ORDER BY products.id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table img {
            max-width: 60px;
            border-radius: 5px;
        }
        /* Sticky Header Style */
        thead {
            position: sticky;
            top: 0;
            background-color: #ffffff;
            z-index: 1;
        }
        /* Add some spacing between table rows */
        tbody tr {
            transition: background-color 0.3s ease;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Product List</h2>
    <a href="add.php" class="btn btn-custom mb-3">Add New Product</a>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['category']) ?></td>
                        <td><img src="../../uploads/<?= $product['image'] ?>" alt="Product Image"></td>
                        <td>
                            <a href="edit.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS (optional for navbar collapse) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
