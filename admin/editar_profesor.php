<?php
header('Content-Type: application/json');
include '../conexion_be.php';

$response = ["status" => "error", "message" => ""];

// VALIDAR CAMPOS
$id = intval($_POST['id_profesor'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
// seguimos aceptando genero en front pero lo ignoramos para BD
//$genero = trim($_POST['genero'] ?? '');

if (!$id || !$nombre || !$email) {
    $response['message'] = "Faltan datos obligatorios.";
    echo json_encode($response);
    exit;
}

// Validar correo repetido en otros profesores
$sql_check_prof = "SELECT id_profesor FROM profesores WHERE email = ? AND id_profesor != ?";
$stmt_check_prof = $conexion->prepare($sql_check_prof);
$stmt_check_prof->bind_param("si", $email, $id);
$stmt_check_prof->execute();
$res_prof = $stmt_check_prof->get_result();
if ($res_prof->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El correo ya pertenece a otro profesor."]);
    exit;
}
$stmt_check_prof->close();

// Validar correo en tabla usuarios
$sql_check_user = "SELECT id_usuario FROM usuarios WHERE correo_elec = ? LIMIT 1";
$stmt_check_user = $conexion->prepare($sql_check_user);
$stmt_check_user->bind_param("s", $email);
$stmt_check_user->execute();
$res_user = $stmt_check_user->get_result();
if ($res_user->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El correo ya está registrado como usuario."]);
    exit;
}
$stmt_check_user->close();

// Obtener foto actual
$sql_foto = "SELECT foto FROM profesores WHERE id_profesor = ?";
$stmt_foto = $conexion->prepare($sql_foto);
$stmt_foto->bind_param("i", $id);
$stmt_foto->execute();
$result_foto = $stmt_foto->get_result();
$row = $result_foto->fetch_assoc();
$foto_actual = $row['foto'] ?? '';
$stmt_foto->close();

// Procesar foto nueva
$nueva_foto_nombre = $foto_actual;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $nueva_foto_nombre = "foto_" . time() . "." . $ext;
    $carpeta = "../imgs/profesores/$id/";
    if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
    if ($foto_actual && file_exists($carpeta . $foto_actual)) unlink($carpeta . $foto_actual);
    move_uploaded_file($_FILES['foto']['tmp_name'], $carpeta . $nueva_foto_nombre);
}

// Actualizar datos (NO tocamos contrasena aquí)
$sql_update = "UPDATE profesores SET nombre = ?, email = ?, foto = ? WHERE id_profesor = ?";
$stmt_upd = $conexion->prepare($sql_update);
$stmt_upd->bind_param("sssi", $nombre, $email, $nueva_foto_nombre, $id);
// =====================================
// ACTUALIZAR CURSO ASIGNADO AL PROFESOR
// =====================================
$id_curso = intval($_POST['id_curso'] ?? 0);

// 1. Quitar asignación antigua
$conexion->query("UPDATE cursos SET id_profesor = NULL WHERE id_profesor = $id");

// 2. Si hay nuevo curso asignado
if ($id_curso > 0) {
    $conexion->query("UPDATE cursos SET id_profesor = $id WHERE id_curso = $id_curso");
}

if ($stmt_upd->execute()) {
    $response["status"] = "success";
    $response["message"] = "Profesor actualizado correctamente.";
} else {
    $response["message"] = "Error al actualizar: " . $stmt_upd->error;
}

$stmt_upd->close();
echo json_encode($response);
