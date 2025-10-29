<?php
// --- 🔹 Encabezados CORS ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

// --- 🔹 Manejo de preflight (solicitud OPTIONS) ---
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}
global $pdo;
include "../db_connection.php";

$location  = $_POST['location'];
$level     = $_POST['level'];
$date_time = $_POST['date_time'];
$resources = $_POST['resources'];
$active    = $_POST['active'];

$sql = "INSERT INTO siniestros (location, level, date_time, resources, active) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

if ($stmt->execute([$location, $level, $date_time, $resources, $active])) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->errorInfo()[2]
    ]);
}
