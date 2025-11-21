<?php
// reset_profesor_password.php
header('Content-Type: application/json; charset=UTF-8');
require '../conexion_be.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../php/Exception.php';
include '../php/PHPMailer.php';
include '../php/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

$id = intval($_POST['id_profesor'] ?? 0);
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID inválido.']);
    exit;
}

// obtener profesor
$stmt = $conexion->prepare("SELECT nombre, email FROM profesores WHERE id_profesor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Profesor no encontrado.']);
    exit;
}
$row = $res->fetch_assoc();
$nombre = $row['nombre'];
$email  = $row['email'];
$stmt->close();

// generar nueva password
function generar_password($len = 10) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
    $max = strlen($chars) - 1;
    $str = '';
    for ($i = 0; $i < $len; $i++) $str .= $chars[random_int(0, $max)];
    return $str;
}
$plainPassword = generar_password(10);
$hashPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// actualizar BD
$stmt = $conexion->prepare("UPDATE profesores SET contrasena = ? WHERE id_profesor = ?");
$stmt->bind_param("si", $hashPassword, $id);
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar contraseña.']);
    exit;
}
$stmt->close();

// enviar correo
try {
    $mail = new PHPMailer(true);
    $mail->SMTPOptions = ['ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]];

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'teecreator123@gmail.com'; // cambiar
    $mail->Password = 'szhi ipir cobo rtpj';     // cambiar
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('teecreator123@gmail.com', 'Equipo de Adorarte');
    $mail->addAddress($email, $nombre);

    $mail->Subject = "Contraseña restablecida - Acceso profesores";
    $mail->Body = "Hola $nombre,\n\nTu contraseña ha sido restablecida por el administrador.\n\nEmail: $email\nContraseña provisional: $plainPassword\n\nCámbiala al iniciar sesión.\n\nEquipo de Adorarte";

    $mail->send();

} catch (Exception $e) {
    echo json_encode(['status' => 'warning', 'message' => 'Contraseña actualizada, pero fallo el envío por correo: ' . $mail->ErrorInfo, 'password' => $plainPassword]);
    exit;
}

echo json_encode(['status' => 'success', 'message' => 'Contraseña restablecida y enviada por correo.', 'password' => $plainPassword]);
exit;
