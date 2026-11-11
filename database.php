<?php
// Activity 2 uses the SAME database as Activity 1.
$host = "localhost";
$dbname = "signin_register";
$dbuser = "root";
$dbpass = ""; // Change this if your MySQL root account has a password.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Database connection failed. Check database.php settings and make sure MySQL is running.");
}
