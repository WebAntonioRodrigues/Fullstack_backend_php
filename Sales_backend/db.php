<?php

$host = 'localhost';
$dbname = 'sales_db';
$user = 'root';
$password = '';
$charset = "utf8mb4";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(500);
    echo json_encode(['error' => 'Error on conection: ' . $e->getMessage()]);
    exit;
}