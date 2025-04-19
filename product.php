<?php
require_once 'config/db.php';
include 'includes/header.php';
session_start();

$id = $_GET['id'] ?? null;
if (!$id) exit("No product ID");

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) exit("Product not found");

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $rating = $_POST['rating'] ?? 5;
    $comment = $_POST['comment'] ?? '';
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $user_id, $rating, $comment]);
    header("Location: product.php?id=$id");
    exit;
}

// Fetch reviews
$reviewStmt = $pdo->prepare("SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE product_id = ? ORDER BY r.id DESC");
$reviewStmt->execute([$id]);
$reviews = $reviewStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> | Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e8f4fc;
        }
        .product-img {
            max-width: 100%;
            border-radius: 10px;
        }
        .review-box {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <img src="uploads/<?= $product['image'] ?>" class="product-img">
        </div>
        <div class="col-md-7">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="fs-5 text-success fw-semibold">Price: $<?= $product['price'] ?></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="cart/add.php" method="post" class="mt-4">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Quantity:</label>
                        <input type="number" class="form-control" name="quantity" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <hr class="my-5">

    <h3 class="text-primary">Reviews</h3>
    <?php if (count($reviews)): ?>
        <?php foreach ($reviews as $r): ?>
            <div class="review-box">
                <strong><?= htmlspecialchars($r['name']) ?></strong> (<?= $r['rating'] ?>/5)
                <p><?= htmlspecialchars($r['comment']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mt-4">
            <h4>Leave a Review</h4>
            <form method="post" class="border p-4 rounded bg-white">
                <div class="mb-3">
                    <label class="form-label">Rating (1-5):</label>
                    <select name="rating" class="form-select" required>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comment:</label>
                    <textarea name="comment" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit Review</button>
            </form>
        </div>
    <?php else: ?>
        <p class="mt-3"><a href="./auth/login.php">Login</a> to leave a review.</p>
    <?php endif; ?>
</div>
<a href="index.php" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Back to Home
</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'includes/footer.php'; ?>
