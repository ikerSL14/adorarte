<?php
require '../conexion_be.php';
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
  // Borrar hijos asociados
  mysqli_query($conexion, "DELETE FROM hijos WHERE id_usuario = $id");
  // Borrar usuario
  $ok = mysqli_query($conexion, "DELETE FROM usuarios WHERE id_usuario = $id");
  
  echo json_encode(['success' => $ok]);
} else {
  echo json_encode(['success' => false, 'message' => 'ID inválido']);
}
