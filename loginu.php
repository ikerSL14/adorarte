<?php
session_start();
include 'conexion_be.php';
header('Content-Type: application/json');

$correo = mysqli_real_escape_string($conexion, $_POST['Correo_elec'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_elec='$correo'");

if (mysqli_num_rows($validar_login) > 0) {
    $row = mysqli_fetch_assoc($validar_login);
    if (password_verify($contrasena, $row['contrasena'])) {

        $_SESSION['idUsuario'] = $row['id_usuario'];
        $_SESSION['nombre'] = $row['nombre_com'];
        $_SESSION['correo'] = $row['correo_elec'];
        $_SESSION['rol'] = $row['rol']; // 👈 Guardamos el rol

        echo json_encode([
        'status' => 'success',
        'message' => 'Inicio de sesión exitoso.',
        'rol' => $row['rol']
        ]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'El correo no está registrado.']);
    exit();
}
?>
