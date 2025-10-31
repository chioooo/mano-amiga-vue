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

$id = intval($_POST["id"] ?? 0);
$location = $_POST["location"] ?? "";
$level = intval($_POST["level"] ?? 0);
$date_time = $_POST["date_time"] ?? "";
$resources = $_POST["resources"] ?? "";
$active = intval($_POST["active"] ?? 0);

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID inválido"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE siniestros 
    SET location = ?, level = ?, date_time = ?, resources = ?, active = ? 
    WHERE id = ?");

if ($stmt->execute([$location, $level, $date_time, $resources, $active, $id])) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->errorInfo()]);
}
