<?php
require_once '../../includes/auth.php';
require_once '../../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) exit("Invalid ID");

$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$id]);
$product = $product->fetch();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category_id = $_POST['category_id'] ?? 0;

    $image = $product['image'];
    if ($_FILES['image']['name']) {
        $image = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../../uploads/" . $image);
    }

    $stmt = $pdo->prepare("UPDATE products 
        SET name=?, description=?, price=?, image=?, category_id=? WHERE id=?");
    $stmt->execute([$name, $description, $price, $image, $category_id, $id]);

    header("Location: index.php");
    exit;
}
?>

<h2>Edit Product</h2>
<form method="post" enctype="multipart/form-data">
    <label>Name:</label><br><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>
    <label>Description:</label><br><textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br><br>
    <label>Price:</label><br><input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br><br>
    <label>Category:</label><br>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>
    <label>Image:</label><br>
    <img src="../../uploads/<?= $product['image'] ?>" width="80"><br>
    <input type="file" name="image"><br><br>
    <button type="submit">Update Product</button>
</form>
