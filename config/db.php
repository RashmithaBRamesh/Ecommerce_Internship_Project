<?php
$host = "sql305.infinityfree.com";
$dbname = "if0_38778201_ecommerce_db";
$username = "if0_38778201"; // or your DB username
$password = "Thennow21";     // or your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
