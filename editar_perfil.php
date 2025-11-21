<?php
session_start();
include 'conexion_be.php';

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
  echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
  exit();
}

$idUsuario = $_SESSION['idUsuario'];
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
$correo = mysqli_real_escape_string($conexion, $_POST['correo_elec']);
$contrasena = $_POST['contrasena'] ?? '';

// Validar correo único
$queryCorreo = "SELECT id_usuario FROM usuarios WHERE correo_elec = '$correo' AND id_usuario != $idUsuario";
$resultCorreo = mysqli_query($conexion, $queryCorreo);
if (mysqli_num_rows($resultCorreo) > 0) {
  echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está en uso.']);
  exit();
}

// Obtener foto actual para borrar si se cambia
$queryFoto = "SELECT foto_perfil FROM usuarios WHERE id_usuario = $idUsuario";
$resFoto = mysqli_query($conexion, $queryFoto);
$fotoActual = null;
if ($resFoto && mysqli_num_rows($resFoto) > 0) {
  $fotoActual = mysqli_fetch_assoc($resFoto)['foto_perfil'];
}

// Manejo de imagen subida
$nombreArchivoFoto = $fotoActual; // por defecto mantiene la actual

if (isset($_FILES['imagenPerfil']) && $_FILES['imagenPerfil']['error'] === UPLOAD_ERR_OK) {
  $archivoTmp = $_FILES['imagenPerfil']['tmp_name'];
  $nombreArchivo = basename($_FILES['imagenPerfil']['name']);
  $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
  $extPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

  if (!in_array($extension, $extPermitidas)) {
    echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido. Solo jpg, jpeg, png, gif.']);
    exit();
  }

  // Crear carpeta si no existe
  $carpetaUsuario = "imgs/perfil/$idUsuario";
  if (!is_dir($carpetaUsuario)) {
    mkdir($carpetaUsuario, 0755, true);
  }

  // Nombre único para evitar conflictos (puedes usar timestamp)
  $nuevoNombreArchivo = uniqid() . '.' . $extension;
  $rutaDestino = "$carpetaUsuario/$nuevoNombreArchivo";

  // Mover archivo
  if (move_uploaded_file($archivoTmp, $rutaDestino)) {
    // Borrar foto anterior si existe y es diferente
    if ($fotoActual && file_exists("$carpetaUsuario/$fotoActual")) {
      unlink("$carpetaUsuario/$fotoActual");
    }
    $nombreArchivoFoto = $nuevoNombreArchivo;
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
    exit();
  }
}

// Actualizar datos
if (!empty($contrasena)) {
  $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
  $queryUpdate = "UPDATE usuarios SET 
                    nombre_com = '$nombre', 
                    correo_elec = '$correo', 
                    contrasena = '$contrasena_hash', 
                    foto_perfil = '$nombreArchivoFoto' 
                  WHERE id_usuario = $idUsuario";
} else {
  $queryUpdate = "UPDATE usuarios SET 
                    nombre_com = '$nombre', 
                    correo_elec = '$correo', 
                    foto_perfil = '$nombreArchivoFoto' 
                  WHERE id_usuario = $idUsuario";
}

if (mysqli_query($conexion, $queryUpdate)) {
  echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente.']);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Error al actualizar datos: ' . mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>