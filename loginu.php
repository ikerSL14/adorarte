<?php
session_start();
include 'conexion_be.php';
header('Content-Type: application/json');

$correo = mysqli_real_escape_string($conexion, $_POST['Correo_elec'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

// ======================================================================
// 1️⃣ PRIMER CHECK → ¿ES PROFESOR?
// ======================================================================
$consultaProfesor = mysqli_query(
    $conexion,
    "SELECT * FROM profesores WHERE email = '$correo' LIMIT 1"
);

if (mysqli_num_rows($consultaProfesor) > 0) {
    $prof = mysqli_fetch_assoc($consultaProfesor);

    // Las contraseñas de profesores también deben estar hasheadas
    if (password_verify($contrasena, $prof['contrasena'])) {

        // Crear sesión especial para profesor
        $_SESSION['id_profesor'] = $prof['id_profesor'];
        $_SESSION['nombre_profesor'] = $prof['nombre'];
        $_SESSION['email_profesor'] = $prof['email'];
        $_SESSION['rol'] = 'profesor';

        echo json_encode([
            'status' => 'success',
            'message' => 'Inicio de sesión como profesor.',
            'redirect' => 'profesores/dashboard.php'
        ]);
        exit();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Contraseña incorrecta.'
        ]);
        exit();
    }
}

// ======================================================================
// 2️⃣ SEGUNDO CHECK → USUARIO NORMAL (EL QUE YA TENÍAS)
// ======================================================================
$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_elec='$correo'");

if (mysqli_num_rows($validar_login) > 0) {
    $row = mysqli_fetch_assoc($validar_login);

    if (password_verify($contrasena, $row['contrasena'])) {

        $_SESSION['idUsuario'] = $row['id_usuario'];
        $_SESSION['nombre'] = $row['nombre_com'];
        $_SESSION['correo'] = $row['correo_elec'];
        $_SESSION['rol'] = $row['rol'];

        echo json_encode([
            'status' => 'success',
            'message' => 'Inicio de sesión exitoso.',
            'rol' => $row['rol'],
            'redirect' => ($row['rol'] === 'admin' ? 'admin/dashboard.php' : 'perfil.php')
        ]);
        exit();

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        exit();
    }
}

// ======================================================================
// 3️⃣ NO ES PROFESOR NI USUARIO
// ======================================================================
echo json_encode([
    'status' => 'error',
    'message' => 'El correo no está registrado.'
]);
exit();

?>
