<?php
session_start();
include '../../conexion_be.php';

if (!isset($_SESSION['id_profesor'])) {
    echo json_encode(["success" => false, "message" => "Sesión no válida"]);
    exit;
}

$id_profesor = $_SESSION['id_profesor'];

$actual = $_POST['actual'] ?? '';
$nueva = $_POST['nueva'] ?? '';

$response = ["success" => false, "message" => ""];

// Obtener la contraseña actual (hasheada)
$q = mysqli_query($conexion, "SELECT contrasena FROM profesores WHERE id_profesor = $id_profesor");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    $response["message"] = "Profesor no encontrado.";
    echo json_encode($response);
    exit;
}

$hashActual = $data["contrasena"];

// Verificar contraseña actual
if (!password_verify($actual, $hashActual)) {
    $response["message"] = "La contraseña actual es incorrecta.";
    echo json_encode($response);
    exit;
}

// Crear hash de la nueva contraseña
$nuevoHash = password_hash($nueva, PASSWORD_DEFAULT);

// Actualizar en BD
$update = mysqli_query(
    $conexion,
    "UPDATE profesores SET contrasena='$nuevoHash' WHERE id_profesor = $id_profesor"
);

if ($update) {
    $response["success"] = true;
    $response["message"] = "Contraseña actualizada.";
} else {
    $response["message"] = "Error al actualizar.";
}

echo json_encode($response);
