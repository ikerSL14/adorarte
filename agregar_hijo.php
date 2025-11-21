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

$nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo'] ?? '');
$edad = (int)($_POST['edad'] ?? 0);
$genero = mysqli_real_escape_string($conexion, $_POST['genero'] ?? '');
$curso_id = (int)($_POST['curso_id'] ?? 0);

// Validar datos
if (empty($nombre_completo) || $edad < 1 || $edad > 23 || empty($genero) || $curso_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o inválidos.']);
    exit();
}

// Manejo de imagen
$nombreArchivoFoto = null;
if (isset($_FILES['imagenHijo']) && $_FILES['imagenHijo']['error'] === UPLOAD_ERR_OK) {
    $archivoTmp = $_FILES['imagenHijo']['tmp_name'];
    $nombreArchivo = basename($_FILES['imagenHijo']['name']);
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    $extPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extension, $extPermitidas)) {
        echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido.']);
        exit();
    }
}

// 1. Insertar hijo
$query_insert_hijo = "INSERT INTO hijos (id_usuario, nombre_completo, edad, genero) 
                      VALUES ($idUsuario, '$nombre_completo', $edad, '$genero')";

if (mysqli_query($conexion, $query_insert_hijo)) {

    $idHijo = mysqli_insert_id($conexion);

    // Subir imagen si existe
    if (isset($_FILES['imagenHijo']) && $_FILES['imagenHijo']['error'] === UPLOAD_ERR_OK) {
        $carpetaHijo = "imgs/perfil/$idUsuario/hijos/$idHijo";
        if (!is_dir($carpetaHijo)) {
            mkdir($carpetaHijo, 0755, true);
        }

        $nuevoNombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = "$carpetaHijo/$nuevoNombreArchivo";

        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            mysqli_query($conexion, "UPDATE hijos SET foto_perfil = '$nuevoNombreArchivo' 
                                     WHERE id_hijo = $idHijo");
        }
    }

    // 2. Inscribir hijo al curso
    $query_inscribir = "INSERT INTO inscripciones (id_hijo, id_curso) 
                        VALUES ($idHijo, $curso_id)";

    if (mysqli_query($conexion, $query_inscribir)) {

        // 3. Obtener nombre, precio y GRUPO del curso
        $query_curso = "SELECT nombre_curso, precio, grupo 
                        FROM cursos 
                        WHERE id_curso = $curso_id LIMIT 1";

        $result_curso = mysqli_query($conexion, $query_curso);

        if ($result_curso && mysqli_num_rows($result_curso) > 0) {
            $curso = mysqli_fetch_assoc($result_curso);
            $nombreCurso = $curso['nombre_curso'];
            $precioCurso = $curso['precio'];
            $grupoCurso = strtoupper($curso['grupo']);
        } else {
            $nombreCurso = "Curso desconocido";
            $precioCurso = 500.00;
            $grupoCurso = "X";
        }

        /*
        ============================================================================
            4. GENERAR MATRÍCULA AUTOMÁTICA
            Formato: Año (2 díg) + id_curso + grupo + id_hijo
            Ejemplo: 25 3 B 12  => 253B12
        ============================================================================
        */
        $anio = date("y");
        $matricula = $anio . $curso_id . $grupoCurso . $idHijo;

        mysqli_query($conexion, 
            "UPDATE hijos SET matricula='$matricula' WHERE id_hijo=$idHijo"
        );

        // 5. Registrar compra
        $totalCompra = $precioCurso;
        $direccionEnvio = "Escuela Adorate";
        $metodoPago = "Tarjeta";

        $query_insert_compra = "INSERT INTO compras (id_usuario, fecha, total, direccion_envio, metodo_pago) 
                                VALUES ($idUsuario, NOW(), $totalCompra, '$direccionEnvio', '$metodoPago')";

        if (mysqli_query($conexion, $query_insert_compra)) {

            $idCompra = mysqli_insert_id($conexion);

            // 6. Insertar detalle de compra
            $tituloRegistro = "Curso de: $nombreCurso";
            $cantidad = 1;

            $query_insert_registro = "INSERT INTO registro (id_compra, titulo, precio, cantidad) 
                                      VALUES ($idCompra, '$tituloRegistro', $precioCurso, $cantidad)";

            if (mysqli_query($conexion, $query_insert_registro)) {

                // 7. Enviar correo
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

                    $mail->Subject = "Inscripción de $nombre_completo al curso";

                    $mail->Body =
                        "Hola $nombrePadre,\n\n" .
                        "Tu hijo(a) $nombre_completo ha sido inscrito exitosamente al curso: $nombreCurso.\n" .
                        "Horario: Sábados de 9:00 A.M. a 1:00 P.M.\n" .
                        "Monto pagado: $precioCurso pesos.\n" .
                        "Matrícula asignada: $matricula\n\n" .
                        "Gracias por confiar en nosotros.\n\n" .
                        "Atentamente,\nEquipo de Adorarte";

                    $mail->send();

                } catch (Exception $e) {
                    error_log("Error al enviar correo: " . $mail->ErrorInfo);
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Hijo inscrito, matrícula generada, compra registrada y correo enviado correctamente.'
                ]);
                exit();
            }

            echo json_encode(['status' => 'error', 'message' => 'Error al registrar detalle: ' . mysqli_error($conexion)]);
            exit();
        }

        echo json_encode(['status' => 'error', 'message' => 'Error en compra: ' . mysqli_error($conexion)]);
        exit();
    }

    echo json_encode(['status' => 'error', 'message' => 'Error al inscribir al hijo: ' . mysqli_error($conexion)]);
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Error al agregar al hijo: ' . mysqli_error($conexion)]);
mysqli_close($conexion);
?>
