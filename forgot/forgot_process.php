<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include '../php/Exception.php';
include '../php/PHPMailer.php';
include '../php/SMTP.php';
include '../conexion_be.php';

$email = mysqli_real_escape_string($conexion, $_POST['email'] ?? '');

if (empty($email)) {
    $_SESSION['forgot_message'] = "<div class='error'>Debes escribir un correo.</div>";
    header("Location: forgot.php");
    exit();
}

// Verificar si existe
$check = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo_elec = '$email' LIMIT 1");

if (mysqli_num_rows($check) == 0) {
    $_SESSION['forgot_message'] = "<div class='error'>Ese correo no está registrado.</div>";
    header("Location: forgot.php");
    exit();
}

// Crear token
$token = bin2hex(random_bytes(32));
$fecha = date("Y-m-d H:i:s");

// Guardar token
mysqli_query($conexion, "INSERT INTO password_resets (email, token, created_at) 
                         VALUES ('$email', '$token', '$fecha')");

// Enviar correo
$link = "http://localhost/adorate/forgot/reset_password.php?token=$token";

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'teecreator123@gmail.com';
    $mail->Password = 'szhi ipir cobo rtpj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('teecreator123@gmail.com', 'Adorarte Soporte');
    $mail->addAddress($email);

    $mail->Subject = "Recuperación de contraseña";
    $mail->Body = "Has solicitado recuperar tu contraseña.\n\n
    Da clic aquí para crear una nueva:\n$link\n\n
    Este enlace es válido por 1 hora.";

    $mail->send();

    $_SESSION['forgot_message'] = "<div class='success'>Te hemos enviado un enlace a tu correo.</div>";

} catch (Exception $e) {
    $_SESSION['forgot_message'] = "<div class='error'>No se pudo enviar el correo.</div>";
}

header("Location: forgot.php");
exit();
