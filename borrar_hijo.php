<?php
session_start();
include 'conexion_be.php';

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$idHijo = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idHijo <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID de hijo inválido']);
    exit();
}

// Verificar que el hijo pertenece al usuario
$query = "SELECT * FROM hijos WHERE id_hijo = $idHijo AND id_usuario = $idUsuario";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado o hijo no existe']);
    exit();
}

// Borrar inscripciones del hijo
mysqli_query($conexion, "DELETE FROM inscripciones WHERE id_hijo = $idHijo");

// Borrar el hijo
if (mysqli_query($conexion, "DELETE FROM hijos WHERE id_hijo = $idHijo")) {
    echo json_encode(['status' => 'success', 'message' => 'Hijo borrado correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al borrar el hijo']);
}

mysqli_close($conexion);
?>