<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dbefff, #c0e0ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            max-width: 450px;
            margin: 80px auto;
            background: #fff;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 28px;
            font-weight: 600;
            color: #0077b6;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-control:focus {
            border-color: #0077b6;
            box-shadow: 0 0 0 0.2rem rgba(0, 119, 182, 0.25);
        }

        .btn-primary {
            background-color: #0077b6;
            border-color: #0077b6;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #005f91;
            border-color: #005f91;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            text-decoration: none;
            color: #0077b6;
            font-weight: 500;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .alert {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-title">Login to Your Account</div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <div class="link mt-3">
        <a href="register.php">Don't have an account? Register</a>
    </div>
</div>

<!-- JavaScript for password toggle -->
<script>
    function togglePassword() {
        const pass = document.getElementById("password");
        pass.type = pass.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>
