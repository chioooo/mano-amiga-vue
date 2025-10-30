<?php
global $pdo;
session_start();

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

require "../db_connection.php";

$usuario_id   = $_POST['usuario_id'] ?? null;
$siniestro_id = $_POST['siniestro_id'] ?? null;

if (!$usuario_id || !$siniestro_id) {
    echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
    exit;
}

try {
    $sql = "UPDATE usuarios SET siniestro_id = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$siniestro_id, $usuario_id])) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
