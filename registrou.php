<?php
session_start();
include 'conexion_be.php';

header('Content-Type: application/json');

$Nombre_completo = mysqli_real_escape_string($conexion, $_POST['Nombre_com'] ?? '');
$correo = mysqli_real_escape_string($conexion, $_POST['Correo_elec'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';
$contrasena_confirm = $_POST['contrasena_confirm'] ?? '';

if ($contrasena !== $contrasena_confirm) {
    echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
    exit();
}

$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_elec='$correo'");
if (mysqli_num_rows($verificar_correo) != 0) {
    echo json_encode(['status' => 'error', 'message' => 'Este correo ya está registrado.']);
    exit();
}

$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

$insertar = mysqli_query($conexion, "INSERT INTO usuarios (nombre_com, correo_elec, contrasena) VALUES ('$Nombre_completo', '$correo', '$contrasena_hash')");

if ($insertar) {
    $ejecutar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_elec = '$correo'");
    $row = mysqli_fetch_assoc($ejecutar);
    $_SESSION['idUsuario'] = $row['id_usuario'];
    $_SESSION['nombre'] = $row['nombre_com'];
    $_SESSION['correo'] = $row['correo_elec'];

    echo json_encode(['status' => 'success', 'message' => 'Usuario registrado correctamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar usuario.']);
}

mysqli_close($conexion);
?>