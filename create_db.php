<?php

// Database creation script
$host = '127.0.0.1';
$root = 'root';
$password = '';
$dbname = 'school-business';

try {
    // Connect without database
    $pdo = new PDO("mysql:host=$host", $root, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    echo "Database '$dbname' created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
