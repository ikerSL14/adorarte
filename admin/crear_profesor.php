<?php
// crear_profesor.php
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

$nombre = trim($_POST['nombre'] ?? '');
$email  = trim($_POST['email'] ?? '');
// seguir recibiendo genero en front (pero lo ignoramos para BD)
$genero = trim($_POST['genero'] ?? '');
$id_curso = $_POST["id_curso"] ?? null;

if ($nombre === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Nombre y email válidos son obligatorios.']);
    exit;
}

// evitar duplicados: otro profesor
$stmt = $conexion->prepare("SELECT id_profesor FROM profesores WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    echo json_encode(['status' => 'error', 'message' => 'Ya existe un profesor con ese email.']);
    exit;
}
$stmt->close();

// evitar que el email exista en usuarios
$stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE correo_elec = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    echo json_encode(['status' => 'error', 'message' => 'Este correo ya está registrado como usuario.']);
    exit;
}
$stmt->close();

// Generar contraseña aleatoria (ejemplo: 10 chars)
function generar_password($len = 10) {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
    $max = strlen($chars) - 1;
    $str = '';
    for ($i = 0; $i < $len; $i++) $str .= $chars[random_int(0, $max)];
    return $str;
}

$plainPassword = generar_password(10);
$hashPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Insertar profesor (guardar hash)
$stmt = $conexion->prepare("INSERT INTO profesores (nombre, email, contrasena) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error de BD: ' . $conexion->error]);
    exit;
}
$stmt->bind_param("sss", $nombre, $email, $hashPassword);
$ok = $stmt->execute();
if (!$ok) {
    $stmt->close();
    echo json_encode(['status' => 'error', 'message' => 'Error al crear profesor: ' . $stmt->error]);
    exit;
}
$profesorId = $conexion->insert_id;
$stmt->close();

if (!empty($id_curso)) {
    $stmtCur = $conexion->prepare("UPDATE cursos SET id_profesor = ? WHERE id_curso = ?");
    $stmtCur->bind_param("ii", $profesorId, $id_curso);
    $stmtCur->execute();
    $stmtCur->close();
}

// Manejo de foto (opcional)
$fotoNombreFinal = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $f = $_FILES['foto'];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        // eliminar registro creado
        $conexion->query("DELETE FROM profesores WHERE id_profesor = $profesorId");
        echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen. Código: ' . $f['error']]);
        exit;
    }
    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        $conexion->query("DELETE FROM profesores WHERE id_profesor = $profesorId");
        echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido.']);
        exit;
    }

    $baseDir = __DIR__ . '/../imgs/profesores';
    if (!is_dir($baseDir)) mkdir($baseDir, 0755, true);
    $profDir = $baseDir . '/' . $profesorId;
    if (!is_dir($profDir)) mkdir($profDir, 0755, true);

    $fotoNombreFinal = uniqid('pf_') . '.' . $ext;
    $dest = $profDir . '/' . $fotoNombreFinal;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        $conexion->query("DELETE FROM profesores WHERE id_profesor = $profesorId");
        echo json_encode(['status' => 'error', 'message' => 'No se pudo mover el archivo al destino.']);
        exit;
    }

    // actualizar foto en BD
    $stmt = $conexion->prepare("UPDATE profesores SET foto = ? WHERE id_profesor = ?");
    $stmt->bind_param("si", $fotoNombreFinal, $profesorId);
    $stmt->execute();
    $stmt->close();
}

// Enviar correo SMTP con la contraseña en texto plano (una sola vez)
try {
    $mail = new PHPMailer(true);
    $mail->SMTPOptions = ['ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    ]];

    // --- CONFIGURA ESTO con tus datos reales ---
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'teecreator123@gmail.com'; // reemplaza
    $mail->Password = 'szhi ipir cobo rtpj';     // reemplaza
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    // ------------------------------------------

    $mail->setFrom('teecreator123@gmail.com', 'Equipo de Adorarte'); // reemplaza
    $mail->addAddress($email, $nombre);

    $mail->Subject = "Cuenta creada: acceso a panel de profesores";
    $body = "Hola $nombre,\n\n";
    $body .= "Tu cuenta de profesor ha sido creada en la plataforma.\n\n";
    $body .= "Email: $email\n";
    $body .= "Contraseña provisional: $plainPassword\n\n";
    $body .= "Por seguridad, cambia tu contraseña en tu primer inicio de sesión.\n\n";
    $body .= "Equipo de Adorarte";

    $mail->Body = $body;
    $mail->send();

} catch (Exception $e) {
    // opcional: eliminar al profesor si quieres garantizar envío
    // $conexion->query("DELETE FROM profesores WHERE id_profesor = $profesorId");
    // echo json_encode(['status' => 'error', 'message' => 'Error al enviar correo: ' . $mail->ErrorInfo]);
    // exit;

    // Si no quieres borrar el registro por fallo de correo, avisamos:
    echo json_encode([
        'status' => 'warning',
        'message' => 'Profesor creado pero falló el envío del correo: ' . $mail->ErrorInfo,
        'profesor' => ['id_profesor' => $profesorId, 'nombre' => $nombre, 'email' => $email],
        // mostramos la contraseña para que admin la copie aun si falla el correo
        'password' => $plainPassword
    ]);
    exit;
}

// éxito
echo json_encode([
    'status' => 'success',
    'message' => 'Profesor creado y correo enviado.',
    'profesor' => ['id_profesor' => $profesorId, 'nombre' => $nombre, 'email' => $email, 'foto' => $fotoNombreFinal],
    'password' => $plainPassword
]);
exit;
