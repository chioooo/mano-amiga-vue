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

// Datos enviados por POST

$name = $_POST['name'] ?? null;
$description = $_POST['description'] ?? null;
$category = $_POST['category'] ?? null;
$quantity = $_POST['quantity'] ?? null;
$siniestro_id = $_POST['siniestro_id'] ?? null;
$usuario_id = $_POST['usuario_id'] ?? null;

try {
    $sql = "INSERT INTO recursos (name, description, category, quantity, usuario_id, siniestro_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$name, $description, $category, $quantity, $usuario_id, $siniestro_id])) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $stmt->errorInfo()[2]
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}