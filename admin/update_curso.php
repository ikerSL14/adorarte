<?php
include '../conexion_be.php';
header('Content-Type: application/json');

if (!isset($_POST['id_curso'])) {
  echo json_encode(['success' => false, 'message' => 'ID de curso no proporcionado']);
  exit;
}

$id = intval($_POST['id_curso']);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre_curso']);
$precio = floatval($_POST['precio']);
$dia_hora = mysqli_real_escape_string($conexion, $_POST['dia_hora']);
$grupo = mysqli_real_escape_string($conexion, $_POST['grupo']);
$id_profesor = intval($_POST['id_profesor']);

// Verificar si el curso existe
$res = mysqli_query($conexion, "SELECT foto FROM cursos WHERE id_curso = $id");
if (!$res || mysqli_num_rows($res) == 0) {
  echo json_encode(['success' => false, 'message' => 'Curso no encontrado']);
  exit;
}

$row = mysqli_fetch_assoc($res);
$oldFoto = $row['foto'];

// Procesar imagen (si se sube una nueva)
$newFoto = $oldFoto;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
  $folderPath = "../imgs/cursos/$id/";
  if (!file_exists($folderPath)) {
    mkdir($folderPath, 0777, true);
  }

  // Borrar imagen anterior si existe
  if (!empty($oldFoto) && file_exists($folderPath . $oldFoto)) {
    unlink($folderPath . $oldFoto);
  }

  // Guardar nueva imagen
  $filename = basename($_FILES['foto']['name']);
  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  $newFoto = "curso_" . time() . "." . $ext;
  move_uploaded_file($_FILES['foto']['tmp_name'], $folderPath . $newFoto);
}

// Actualizar curso
$update = mysqli_query($conexion, "
  UPDATE cursos SET 
    nombre_curso = '$nombre',
    precio = $precio,
    dia_hora = '$dia_hora',
    grupo = '$grupo',
    id_profesor = $id_profesor,
    foto = " . ($newFoto ? "'$newFoto'" : "NULL") . "
  WHERE id_curso = $id
");

if ($update) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Error al actualizar curso.']);
}
?>
