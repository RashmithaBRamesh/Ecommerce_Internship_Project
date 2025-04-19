<?php
session_start();
include '../includes/header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f4fc;
        }
        .cart-item {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .cart-item img {
            max-width: 50px;
            border-radius: 5px;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
        .proceed-btn {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 15px;
            text-decoration: none;
        }
        .proceed-btn:hover {
            background-color: #0056b3;
        }
        .remove-btn {
            color: red;
            text-decoration: none;
        }
        .remove-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <?php
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        echo "<p class='alert alert-warning'>Your cart is empty.</p>";
    } else {
        echo "<h2 class='mb-4'>Your Cart</h2><ul class='list-unstyled'>";
        foreach ($_SESSION['cart'] as $id => $item) {
            echo "<li class='cart-item d-flex justify-content-between align-items-center'>
                    <div class='d-flex align-items-center'>
                        <img src='../uploads/{$item['image']}' alt='{$item['name']}'>
                        <span class='ms-3'>{$item['name']} - $ {$item['price']} x {$item['quantity']}</span>
                    </div>
                    <a href='remove.php?id=$id' class='remove-btn'>Remove</a>
                  </li>";
        }
        echo "</ul>";
        echo "<hr><div class='total'>Total: $" . array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $_SESSION['cart'])) . "</div><br><br>";
        echo "<a href='../checkout/index.php' class='proceed-btn'>Proceed to Checkout</a>";
    }
    ?>
</div>
<a href="../index.php" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Back to Home
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include '../includes/footer.php'; ?>
