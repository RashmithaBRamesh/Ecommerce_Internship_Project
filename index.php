<?php
session_start();
require_once 'config/db.php';
include 'includes/header.php';

$categoryFilter = $_GET['category'] ?? '';
$searchTerm = $_GET['search'] ?? '';

// Fetch categories for filter dropdown
$categoryStmt = $pdo->query("SELECT * FROM categories");
$categories = $categoryStmt->fetchAll();

// Build query to filter by category and search term
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
     body {
            background: #e8f4fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: #0077b6;
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff;
            font-weight: 500;
        }

        .product-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            transition: box-shadow 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            max-height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }

        .footer {
            margin-top: 60px;
            padding: 20px 0;
            background: #0077b6;
            color: white;
            text-align: center;
        }

/* Banner container */
.hero-banner {
    position: relative;
    width: 100vw;
    height: 100vh; /* Full viewport height */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* Full image without cropping */
.hero-banner img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Show full image */
    object-position: center center;
    display: block;
}

/* Text and button at the bottom center */
.hero-text {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    text-align: center;
    z-index: 10;
}

.hero-text h1 {
    font-size: 3rem;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
    margin-bottom: 20px;
}

.hero-text .btn {
    font-size: 1.1rem;
    padding: 10px 28px;
    border-radius: 30px;
    background-color: #0077b6;
    color: white;
    text-transform: uppercase;
    border: none;
    transition: 0.3s ease-in-out;
}

.hero-text .btn:hover {
    background-color: #023e8a;
    transform: scale(1.05);
}
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="index.php">🛍 MyShop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon text-white"></span>
        </button>
        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="cart/index.php">🛒 Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="order_history.php">📜 Order History</a></li>
                    <li class="nav-item"><a class="nav-link" href="edit_profile.php">✏️ Edit Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="auth/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>



<!-- Search and Category Filter -->
<div class="container mt-4">
    <h2>Browse Our Products</h2>
    <form method="get" class="d-flex justify-content-between align-items-center flex-wrap">
        <input type="text" name="search" class="form-control w-50 mb-2" placeholder="Search by product name" value="<?= htmlspecialchars($searchTerm) ?>">
        <select name="category" class="form-select w-25 ms-2 mb-2">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= ($category['id'] == $categoryFilter) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary ms-2 mb-2">Filter</button>
    </form>
</div>

<!-- Hero Banner -->
<div class="hero-banner">
    <img src="img/banner.jpg" alt="Banner">
    <div class="hero-text">
        <h1>Discover Trendy Products</h1>
        <a href="#products-section" class="btn">Shop Now</a>
    </div>
</div>

<!-- Product Listing -->
<div class="container mt-5" id="products-section">
    <h2 class="mb-4 text-primary">🆕 Latest Products</h2>
    <div class="row">
        <?php foreach ($products as $p): ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="product-card h-100 text-center">
                    <img src="uploads/<?= $p['image'] ?>" class="img-fluid product-img mb-3" alt="<?= htmlspecialchars($p['name']) ?>">
                    <h5><?= htmlspecialchars($p['name']) ?></h5>
                    <p class="text-success fw-bold">$<?= $p['price'] ?></p>
                    <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include 'includes/footer.php'; ?>
