<?php
session_start();
require_once '../config/db.php';

$product_id = $_POST['product_id'] ?? $_GET['id'] ?? null;
if (!$product_id) exit("No product ID");

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();
if (!$product) exit("Product not found");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = (int) ($_POST['quantity'] ?? 1);
    if ($quantity <= 0) $quantity = 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }

    header("Location: ./index.php");
    exit;
}
?>

<h2>Add to Cart: <?= htmlspecialchars($product['name']) ?></h2>
<form method="post">
    <label>Quantity:</label><br>
    <input type="number" name="quantity" value="1" min="1" required><br><br>
    <button type="submit">Add to Cart</button>
</form>
