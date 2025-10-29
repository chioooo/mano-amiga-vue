<?php
global $pdo;
require "../db_connection.php";

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
