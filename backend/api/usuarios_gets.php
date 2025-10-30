<?php
session_start();

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

$usuario_id = intval($_SESSION['user_id'] ?? 0);

if ($usuario_id === 0) {
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

try {
    $sql = "SELECT u.id, u.full_name, u.username, u.is_admin, u.siniestro_id, u.fecha_registro,
                   s.level AS siniestro_level, s.location AS siniestro_location, s.date_time AS siniestro_date
            FROM usuarios u
            LEFT JOIN siniestros s ON u.siniestro_id = s.id
            WHERE u.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($user ?: ["error" => "Usuario no encontrado."]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

header("Content-Type: application/json");
echo json_encode($user);
exit;
