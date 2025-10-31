<?php
global $pdo;

header("Access-Control-Allow-Origin: *"); // ajusta a tu dominio
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}
include "../db_connection.php";

$sql = "SELECT * FROM usuarios ORDER BY id DESC";
$stmt = $pdo->query($sql);

$usuarios = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $usuarios[] = $row;
}

echo json_encode($usuarios);
