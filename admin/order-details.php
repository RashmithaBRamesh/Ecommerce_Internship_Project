<?php
session_start();
require_once '../config/db.php';

// Admin authentication check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = $_GET['id'] ?? null;
if (!$order_id) exit("Invalid order ID");

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) exit("Order not found");

// Fetch order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    $updateStmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $updateStmt->execute([$status, $order_id]);
    header("Location: order-details.php?id=$order_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            margin-top: 40px;
        }
        .order-header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .btn-update {
            background-color: #198754;
            color: white;
        }
        .btn-update:hover {
            background-color: #146c43;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="order-header">
            <h3>Order #<?= $order['id'] ?> - Details</h3>
        </div>
        <div class="card-body">
            <p><strong>User:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
            <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

            <h5 class="mt-4">Order Items:</h5>
            <ul class="list-group mb-4">
                <?php foreach ($order_items as $item): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($item['product_name']) ?> - $<?= number_format($item['price'], 2) ?> x <?= $item['quantity'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h5>Update Order Status:</h5>
            <form method="post" class="d-flex gap-2">
                <select name="status" class="form-select w-auto" required>
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                </select>
                <button type="submit" class="btn btn-update">Update Status</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
