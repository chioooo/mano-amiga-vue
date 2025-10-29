<?php
global $pdo;
// --- 🔹 Encabezados CORS ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

// --- 🔹 Manejo de solicitud preflight (OPTIONS) ---
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}
include "../db_connection.php";

$sql = "SELECT * FROM recursos ORDER BY id DESC";
$stmt = $pdo->query($sql);

$recursos = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $recursos[] = $row;
}

echo json_encode($recursos);