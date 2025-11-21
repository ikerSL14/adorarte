<?php
session_start();
include '../conexion_be.php';

$token = $_POST['token'] ?? '';
$pass1 = $_POST['pass1'] ?? '';
$pass2 = $_POST['pass2'] ?? '';

if ($pass1 !== $pass2) {
    die("Las contraseñas no coinciden.");
}

$query = mysqli_query($conexion, "SELECT * FROM password_resets WHERE token='$token' LIMIT 1");

if (mysqli_num_rows($query) == 0) {
    die("Token inválido.");
}

$row = mysqli_fetch_assoc($query);
$email = $row['email'];

// Hashear nueva contraseña
$newPass = password_hash($pass1, PASSWORD_DEFAULT);

// Update
mysqli_query($conexion, "UPDATE usuarios SET contrasena='$newPass' WHERE correo_elec='$email'");

// Borrar token
mysqli_query($conexion, "DELETE FROM password_resets WHERE token='$token'");
header("Location: reset_success.php");
exit();
