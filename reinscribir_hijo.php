<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include 'php/Exception.php';
include 'php/PHPMailer.php';
include 'php/SMTP.php';
include 'conexion_be.php';

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$correoPadre = $_SESSION['correo'];
$nombrePadre = $_SESSION['nombre'];

$id_hijo = (int)($_POST['id_hijo'] ?? 0);
$curso_id = (int)($_POST['curso_id'] ?? 0);

if ($id_hijo <= 0 || $curso_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
    exit();
}

/* =======================================================
    1. Obtener datos del hijo
======================================================= */
$query_hijo = mysqli_query($conexion, "SELECT nombre_completo FROM hijos WHERE id_hijo = $id_hijo LIMIT 1");

if (!$query_hijo || mysqli_num_rows($query_hijo) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Hijo no encontrado.']);
    exit();
}

$hijo = mysqli_fetch_assoc($query_hijo);
$nombre_completo = $hijo['nombre_completo'];

/* =======================================================
    2. Eliminar inscripción pasada
======================================================= */
mysqli_query($conexion, "DELETE FROM inscripciones WHERE id_hijo = $id_hijo");

/* =======================================================
    3. Insertar nueva inscripción
======================================================= */
if (!mysqli_query($conexion, "INSERT INTO inscripciones (id_hijo, id_curso) VALUES ($id_hijo, $curso_id)")) {
    echo json_encode(['status' => 'error', 'message' => 'Error al reinscribir.']);
    exit();
}

/* =======================================================
    4. Obtener información del curso
======================================================= */
$query_curso = mysqli_query($conexion, "
    SELECT nombre_curso, precio, grupo
    FROM cursos
    WHERE id_curso = $curso_id
    LIMIT 1
");

if (!$query_curso || mysqli_num_rows($query_curso) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Curso no encontrado.']);
    exit();
}

$curso = mysqli_fetch_assoc($query_curso);

$nombreCurso = $curso['nombre_curso'];
$precioCurso = $curso['precio'];
$grupoCurso  = strtoupper($curso['grupo']);

/* =======================================================
    5. Generar matrícula nueva
======================================================= */
$anio = date("y");
$matricula = $anio . $curso_id . $grupoCurso . $id_hijo;

mysqli_query($conexion, "UPDATE hijos SET matricula='$matricula', estado='inscrito' WHERE id_hijo=$id_hijo");

/* =======================================================
    6. Registrar compra
======================================================= */
$query_compra = "
    INSERT INTO compras (id_usuario, fecha, total, direccion_envio, metodo_pago)
    VALUES ($idUsuario, NOW(), $precioCurso, 'Escuela Adorarte', 'Tarjeta')
";

if (!mysqli_query($conexion, $query_compra)) {
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar compra.']);
    exit();
}

$idCompra = mysqli_insert_id($conexion);

/* =======================================================
    7. Registrar detalle de la compra
======================================================= */
$tituloRegistro = "Reinscripción al curso: $nombreCurso";

$query_registro = "
    INSERT INTO registro (id_compra, titulo, precio, cantidad)
    VALUES ($idCompra, '$tituloRegistro', $precioCurso, 1)
";

if (!mysqli_query($conexion, $query_registro)) {
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar detalle.']);
    exit();
}

/* =======================================================
    8. Enviar correo de confirmación
======================================================= */

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
    $mail->Username = 'teecreator123@gmail.com';
    $mail->Password = 'szhi ipir cobo rtpj';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('teecreator123@gmail.com', 'Equipo de Adorarte');
    $mail->addAddress($correoPadre, $nombrePadre);

    $mail->Subject = "Reinscripción de $nombre_completo";

    $mail->Body =
        "Hola $nombrePadre,\n\n" .
        "Tu hijo(a) $nombre_completo ha sido REINSCRITO al curso: $nombreCurso.\n" .
        "Monto pagado: $precioCurso pesos.\n" .
        "Nueva matrícula asignada: $matricula\n\n" .
        "Gracias por continuar con nosotros.\n\n" .
        "Equipo de Adorarte";

    $mail->send();

} catch (Exception $e) {
    error_log("Error al enviar correo: " . $mail->ErrorInfo);
}

echo json_encode([
    'status' => 'success',
    'message' => 'Reinscripción completa'
]);
exit();

?>
