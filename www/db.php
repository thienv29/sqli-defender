<?php
/**
 * Database connection utilities for SQL injection demo
 */

function getMySQLiConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli('db', 'demo', 'demopass', 'demo');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

function getPDOConnection() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=db;dbname=demo;charset=utf8mb4';
        $pdo = new PDO($dsn, 'demo', 'demopass', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    return $pdo;
}

function closeMySQLiConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
        $conn = null;
    }
}
?>
