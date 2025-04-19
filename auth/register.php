<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        $error = "Email already exists!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: ../index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #43cea2, #185a9d); /* greenish blue gradient */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .form-container {
            max-width: 500px;
            margin: 80px auto;
            background: #fff;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .form-title {
            font-size: 28px;
            font-weight: bold;
            color: #157347;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-control:focus {
            border-color: #157347;
            box-shadow: 0 0 0 0.2rem rgba(21, 115, 71, 0.25);
        }

        .btn-primary {
            background-color: #157347;
            border-color: #157347;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #14532d;
            border-color: #14532d;
        }

        .btn-outline-secondary {
            border-color: #157347;
            color: #157347;
        }

        .btn-outline-secondary:hover {
            background-color: #157347;
            color: white;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            text-decoration: none;
            color: #157347;
            font-weight: 500;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-title">Create Your Account</div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="password" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <div class="link">
        <a href="login.php">Already have an account? Login</a>
    </div>
</div>

<!-- Bootstrap JS + Toggle Password -->
<script>
    function togglePassword() {
        const pass = document.getElementById("password");
        pass.type = pass.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>
