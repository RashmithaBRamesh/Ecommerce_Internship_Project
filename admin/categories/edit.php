<?php
require_once '../../includes/auth.php';
require_once '../../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit('No ID');

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) exit('Category not found');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->execute([$name, $id]);
    header("Location: index.php");
    exit;
}
?>

<h2>Edit Category</h2>
<form method="post">
    <label>Category Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required><br><br>
    <button type="submit">Update</button>
</form>
