<?php
session_start();
require_once './config/db.php';
include './includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please <a href='login.php'>login</a> to edit your profile.</p>";
    include './includes/footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? $user['name'];
    $email = $_POST['email'] ?? $user['email'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Update name and email
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $user_id]);

    // Check if password needs to be updated
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        // Verify current password
        if (password_verify($current_password, $user['password'])) {
            // Check if new password matches the confirm password
            if ($new_password === $confirm_password) {
                // Hash new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password in database
                $updatePasswordStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $updatePasswordStmt->execute([$hashed_password, $user_id]);

                echo "<p>Your password has been updated successfully!</p>";
            } else {
                echo "<p>The new password and confirmation do not match.</p>";
            }
        } else {
            echo "<p>Current password is incorrect.</p>";
        }
    } else {
        echo "<p>Your profile has been updated successfully!</p>";
    }
}

// Show current data in the form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .navbar-custom {
            background-color: #0077b6;
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: #fff;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #0077b6;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #005f8c;
        }
        .footer {
            background-color: #0077b6;
            color: white;
            padding: 20px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2 class="text-center mb-4">Edit Profile</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <h3 class="mt-4">Change Password</h3>
            <div class="mb-3">
                <label class="form-label">Current Password:</label>
                <input type="password" name="current_password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">New Password:</label>
                <input type="password" name="new_password" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password:</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>

            <button type="submit" class="btn btn-custom w-100">Update Profile</button>
        </form>
    </div>
</div>
<a href="index.php" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Back to Home
</a>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> MyShop. All rights reserved.
</div>

<!-- Bootstrap JS (optional for navbar collapse) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include './includes/footer.php'; ?>
