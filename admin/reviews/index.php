<?php
require_once '../../includes/auth.php';
require_once '../../config/db.php';

// Fetch all reviews with product details
$stmt = $pdo->prepare("SELECT r.*, p.name AS product_name, u.name AS user_name 
                       FROM reviews r
                       JOIN products p ON r.product_id = p.id
                       JOIN users u ON r.user_id = u.id
                       ORDER BY r.created_at DESC");
$stmt->execute();
$reviews = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .review-text {
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Product Reviews</h2>
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>User Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['product_name']) ?></td>
                        <td><?= htmlspecialchars($review['user_name']) ?></td>
                        <td><?= $review['rating'] ?> / 5</td>
                        <td class="review-text"><?= htmlspecialchars($review['comment']) ?></td>
                        <td><?= $review['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
