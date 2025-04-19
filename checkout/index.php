<?php
session_start();
include '../includes/header.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Total amount
$total = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $_SESSION['cart']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f8fb;
        }
        .checkout-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .checkout-header {
            color: #007bff;
            font-size: 24px;
            font-weight: bold;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .form-label {
            font-weight: bold;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            text-align: center;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .cart-summary {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="checkout-header">Checkout</h2>

    <h3>Cart Summary</h3>
    <ul class="list-group cart-summary">
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <li class="list-group-item d-flex justify-content-between">
                <?= htmlspecialchars($item['name']) ?> - $<?= $item['price'] ?> x <?= $item['quantity'] ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Total: <span class="total-amount">$<?= $total ?></span></h3>

    <h3>Shipping Information</h3>
    <form method="post" action="confirm.php">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Shipping Address:</label>
            <textarea id="address" name="address" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number:</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>

        <input type="hidden" name="total" value="<?= $total ?>">

        <button type="submit" class="submit-btn w-100">Proceed to Confirm</button>
    </form>
</div>
<a href="../index.php" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Back to Home
</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
