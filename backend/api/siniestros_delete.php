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

$id = intval($_POST["id"] ?? 0);

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID inválido"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM siniestros WHERE id = ?");

if ($stmt->execute([$id])) {
    echo json_encode(["status" => "success"]);
} else {
    $error = $stmt->errorInfo();
    echo json_encode(["status" => "error", "message" => $error[2]]);
}
