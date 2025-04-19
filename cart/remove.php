<?php
session_start();

$product_id = $_GET['id'] ?? null;
if (!$product_id || !isset($_SESSION['cart'][$product_id])) exit("Invalid product");

unset($_SESSION['cart'][$product_id]);  // Remove from cart
header("Location: /cart/index.php");    // Redirect to cart page
exit;
<?php
session_start();

$product_id = $_GET['id'] ?? null;
if (!$product_id || !isset($_SESSION['cart'][$product_id])) exit("Invalid product");

unset($_SESSION['cart'][$product_id]);  // Remove from cart
header("Location: /cart/index.php");    // Redirect to cart page
exit;
