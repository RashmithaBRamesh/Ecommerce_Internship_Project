<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please <a href='auth/login.php'>login</a> to view your order history.</p>";
    include 'includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$orderStmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$orderStmt->execute([$user_id]);
$orders = $orderStmt->fetchAll();
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container mt-4">
    <?php if (!$orders): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <h2 class="mb-4">Your Order History</h2>
        <?php foreach ($orders as $order): ?>
            <div class="order-card mb-4 p-4 rounded shadow-sm">
                <h4>Order ID: <?= $order['id'] ?></h4>
                <p><strong>Date:</strong> <?= $order['created_at'] ?></p>
                <p><strong>Status:</strong> <?= $order['status'] ?></p>
                <p><strong>Total:</strong> $<?= $order['total'] ?></p>

                <?php
                $itemStmt = $pdo->prepare("
                    SELECT oi.quantity, oi.price, p.name 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?
                ");
                $itemStmt->execute([$order['id']]);
                $items = $itemStmt->fetchAll();
                ?>

                <h5>Items:</h5>
                <ul>
                    <?php foreach ($items as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> - <?= $item['quantity'] ?> x $<?= $item['price'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <!-- Back to Home Button -->
        <a href="index.php" class="btn btn-outline-secondary mb-5">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<style>
    body {
        background-color: #f4f8fb;
    }
    .order-card {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .order-card h4 {
        color: #007bff;
    }
    .order-card p {
        font-size: 16px;
        color: #333;
    }
    .order-card ul {
        list-style-type: none;
        padding: 0;
    }
    .order-card ul li {
        font-size: 15px;
        color: #555;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
