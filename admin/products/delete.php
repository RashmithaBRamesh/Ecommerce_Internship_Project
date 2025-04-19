<?php
require_once '../../includes/auth.php';
require_once '../../config/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: index.php");
exit;
