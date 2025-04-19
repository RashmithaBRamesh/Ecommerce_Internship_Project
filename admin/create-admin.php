<?php
require_once '../config/db.php';

// Only run once!
$password = password_hash('admin123', PASSWORD_DEFAULT);
$username = 'admin';

// Check if admin already exists
$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);

if ($stmt->rowCount() === 0) {
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "Admin user created successfully!";
} else {
    echo "Admin already exists!";
}
?>
