<?php
session_start();
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

// --- 🔹 Conexión a la base de datos ---
require "../db_connection.php";

// --- 🔹 Lógica de inicio de sesión ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Intentar obtener los datos desde JSON o desde POST (por compatibilidad)
    $data = json_decode(file_get_contents("php://input"), true);
    $username = trim($data["username"] ?? $_POST["username"] ?? '');
    $password = trim($data["password"] ?? $_POST["password"] ?? '');

    if (!empty($username) && !empty($password)) {

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];

            echo json_encode([
                "status" => "success",
                "message" => "Inicio de sesión exitoso",
                "user" => [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "is_admin" => $user['is_admin']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Usuario o contraseña incorrectos"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Debes llenar todos los campos"]);
    }
    exit;
}
