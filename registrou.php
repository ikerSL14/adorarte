<?php
session_start();
include 'conexion_be.php';

header('Content-Type: application/json');

$Nombre_completo = mysqli_real_escape_string($conexion, $_POST['Nombre_com'] ?? '');
$correo = mysqli_real_escape_string($conexion, $_POST['Correo_elec'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';
$contrasena_confirm = $_POST['contrasena_confirm'] ?? '';


// 1️⃣ Verificar que las contraseñas coincidan
if ($contrasena !== $contrasena_confirm) {
    echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
    exit();
}

// 2️⃣ Verificar si el correo ya existe en usuarios
$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_elec='$correo'");
if (mysqli_num_rows($verificar_correo) != 0) {
    echo json_encode(['status' => 'error', 'message' => 'Este correo ya está registrado.']);
    exit();
}

// 3️⃣ Verificar si el correo ya existe en profesores
$verificar_profesor = mysqli_query($conexion, "SELECT * FROM profesores WHERE email='$correo'");
if (mysqli_num_rows($verificar_profesor) != 0) {
    // Mismo mensaje que para usuarios
    echo json_encode(['status' => 'error', 'message' => 'Este correo ya está registrado.']);
    exit();
}

// 4️⃣ Hashear la contraseña
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

// 5️⃣ Insertar el usuario
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