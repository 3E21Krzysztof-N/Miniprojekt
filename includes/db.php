<?php
$host = 'localhost';
$dbname = 'playlistdb';
$username = 'root'; // Your DB username
$password = '';     // Your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In a production environment, log this error and show a user-friendly message.
    die("Database connection failed: " . $e->getMessage());
}
?>