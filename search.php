<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';

// Build the SQL query with search and category filtering
$query = "SELECT * FROM products WHERE name LIKE ?";
$params = ["%$searchTerm%"];

if ($categoryFilter) {
    $query .= " AND category_id = ?";
    $params[] = $categoryFilter;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<h2>Search Results</h2>
<form method="get">
    <input type="text" name="search" placeholder="Search for products..." value="<?= htmlspecialchars($searchTerm) ?>">
    <select name="category">
        <option value="">All Categories</option>
        <?php
        // Get categories to filter
        $categoryStmt = $pdo->query("SELECT * FROM categories");
        $categories = $categoryStmt->fetchAll();
        foreach ($categories as $category) {
            echo "<option value='{$category['id']}' " . ($category['id'] == $categoryFilter ? 'selected' : '') . ">{$category['name']}</option>";
        }
        ?>
    </select>
    <button type="submit">Search</button>
</form>

<div style="display:flex; flex-wrap:wrap;">
    <?php foreach ($products as $p): ?>
        <div style="width:200px; margin:10px; padding:10px; border:1px solid #ccc;">
            <img src="uploads/<?= $p['image'] ?>" width="180"><br>
            <strong><?= htmlspecialchars($p['name']) ?></strong><br>
            $<?= $p['price'] ?><br>
            <a href="product.php?id=<?= $p['id'] ?>">View Details</a>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
