<?php
require_once '../../includes/auth.php';
require_once '../../config/db.php';

// Fetch the order ID from the URL
$order_id = $_GET['id'] ?? 0;

// Fetch the order details
$stmt = $pdo->prepare("SELECT o.*, u.name AS user_name 
                       FROM orders o
                       JOIN users u ON o.user_id = u.id
                       WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<p>Order not found.</p>";
    exit;
}

// Fetch the products in this order
$itemStmt = $pdo->prepare("SELECT oi.quantity, oi.price, p.name 
                           FROM order_items oi 
                           JOIN products p ON oi.product_id = p.id 
                           WHERE oi.order_id = ?");
$itemStmt->execute([$order_id]);
$order_items = $itemStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    // Update the order status to the new status
    $updateStmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $updateStmt->execute([$status, $order_id]);

    echo "<p>Order status updated to: " . htmlspecialchars($status) . ".</p>";
    // Optionally, redirect after update
    // header("Location: index.php");
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Order Details</h2>
    
    <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
    <p><strong>User Name:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
    <p><strong>Total Amount:</strong> $<?= number_format($order['total'], 2) ?></p>
    <p><strong>Order Date:</strong> <?= $order['created_at'] ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

    <h4>Products in this Order</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Update Order Status</h4>
    <form method="post">
        <div class="mb-3">
            <label for="status" class="form-label">Order Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Pending" <?= ($order['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="Shipped" <?= ($order['status'] == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                <option value="Delivered" <?= ($order['status'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                <option value="Cancelled" <?= ($order['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Confirm Order</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
