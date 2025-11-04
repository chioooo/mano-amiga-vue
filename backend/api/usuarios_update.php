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
$full_name = $_POST["full_name"] ?? "";
$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";
$is_admin = intval($_POST["is_admin"] ?? 0);

if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID inválido"]);
    exit;
}

if (!empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
} else {
    // Obtener la contraseña actual si no se proporciona una nueva
    $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $password = $row['password'];
    } else {
        echo json_encode(["status" => "error", "message" => "Usuario no encontrado"]);
        exit;
    }
}


$stmt = $pdo->prepare("UPDATE usuarios 
    SET full_name = ?, username = ?, password = ?, is_admin = ? 
    WHERE id = ?");

if ($stmt->execute([$full_name, $username, $password, $is_admin, $id])) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->errorInfo()]);
}
