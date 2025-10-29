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

// --- 🔹 Conexión a la base de datos ---
require "../db_connection.php";

global $pdo;

// --- 🔹 Lógica del registro ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Si Vue envía JSON (lo más común con axios)
    $data = json_decode(file_get_contents("php://input"), true);

    // Soporte también si usa FormData (por compatibilidad)
    $fullname = trim($data["fullname"] ?? $_POST["fullname"] ?? '');
    $username = trim($data["username"] ?? $_POST["username"] ?? '');
    $password = trim($data["password"] ?? $_POST["password"] ?? '');

    if (!empty($fullname) && !empty($username) && !empty($password)) {

        $check = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "El nombre de usuario ya existe"]);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (full_name, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $username, $hashedPassword]);

        echo json_encode(["status" => "success", "message" => "Usuario registrado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios"]);
    }

    exit;
}
