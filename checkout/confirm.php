<?php
session_start();
require_once '../config/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user data from form
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $total = $_POST['total'] ?? 0;

    $user_id = $_SESSION['user_id'] ?? null;
    $cartItems = $_SESSION['cart'] ?? [];

    if (!$user_id || empty($cartItems)) {
        echo "<p>Error: Missing user or cart info.</p>";
        include '../includes/footer.php';
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insert into orders table
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

        foreach ($cartItems as $product_id => $item) {
            $itemStmt->execute([
                $order_id,
                $product_id,
                $item['quantity'],
                $item['price']
            ]);
        }

        $pdo->commit();
        unset($_SESSION['cart']); // Clear cart

        // Confirmation
        echo "<div class='container order-confirmation'>
                <h2 class='text-success'>Order Confirmed!</h2>
                <p>Thank you, $name! Your order has been placed successfully.</p>
                <p><strong>Total Amount:</strong> $$total</p>
                <p><strong>Shipping Address:</strong> $address</p>
                <p><strong>Phone Number:</strong> $phone</p>
                <p>You will receive a confirmation email shortly.</p>
                <a href='../index.php' class='btn btn-primary'>Return to Shopping</a>
              </div>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p class='text-danger'>Error saving order: " . $e->getMessage() . "</p>";
    }

} else {
    echo "<p>Error: Invalid access.</p>";
}

include '../includes/footer.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f8fb;
        }
        .order-confirmation {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .order-confirmation h2 {
            color: #28a745;
            font-size: 28px;
        }
        .order-confirmation p {
            font-size: 18px;
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
