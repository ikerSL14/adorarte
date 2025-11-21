<?php
session_start();
include 'conexion_be.php';

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$idHijo = (int)($_POST['id_hijo'] ?? 0);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre_completo'] ?? '');
$edad = (int)($_POST['edad'] ?? 0);
$genero = mysqli_real_escape_string($conexion, $_POST['genero'] ?? '');
$cursoId = (int)($_POST['curso_id'] ?? 0);

// Verificar que el hijo pertenece al usuario
$query = "SELECT * FROM hijos WHERE id_hijo = $idHijo AND id_usuario = $idUsuario";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit();
}

// Obtener foto actual
$fotoActual = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT foto_perfil FROM hijos WHERE id_hijo = $idHijo"))['foto_perfil'];

// Manejo de imagen
if (isset($_FILES['imagenHijo']) && $_FILES['imagenHijo']['error'] === UPLOAD_ERR_OK) {

    $archivoTmp = $_FILES['imagenHijo']['tmp_name'];
    $nombreArchivo = basename($_FILES['imagenHijo']['name']);
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    $extPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extension, $extPermitidas)) {

        $carpetaHijo = "imgs/perfil/$idUsuario/hijos/$idHijo";
        if (!is_dir($carpetaHijo)) {
            mkdir($carpetaHijo, 0755, true);
        }

        // Borrar foto anterior
        if ($fotoActual && file_exists("$carpetaHijo/$fotoActual")) {
            unlink("$carpetaHijo/$fotoActual");
        }

        $nuevoNombreArchivo = uniqid() . '.' . $extension;
        $rutaDestino = "$carpetaHijo/$nuevoNombreArchivo";

        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            mysqli_query($conexion, "UPDATE hijos SET foto_perfil = '$nuevoNombreArchivo' WHERE id_hijo = $idHijo");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
            exit();
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido.']);
        exit();
    }
}

// Actualizar datos del hijo
$updateHijo = "UPDATE hijos SET nombre_completo = '$nombre', edad = $edad, genero = '$genero' WHERE id_hijo = $idHijo";

if (!mysqli_query($conexion, $updateHijo)) {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar datos del hijo.']);
    exit();
}


// ---------------------------------------------------------
//      ACTUALIZAR INSCRIPCIÓN Y MATRÍCULA
// ---------------------------------------------------------

// Obtener curso actual
$queryCursoActual = "SELECT id_curso FROM inscripciones WHERE id_hijo = $idHijo";
$resCursoActual = mysqli_query($conexion, $queryCursoActual);
$cursoActual = mysqli_fetch_assoc($resCursoActual)['id_curso'] ?? null;

if ($cursoId && $cursoId != $cursoActual) {

    // Actualizar inscripción
    mysqli_query($conexion, "DELETE FROM inscripciones WHERE id_hijo = $idHijo");
    $insertInscripcion = mysqli_query($conexion, "INSERT INTO inscripciones (id_hijo, id_curso) VALUES ($idHijo, $cursoId)");

    if (!$insertInscripcion) {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la inscripción.']);
        exit();
    }

    // Obtener matrícula actual
    $resMat = mysqli_query($conexion, "SELECT matricula FROM hijos WHERE id_hijo = $idHijo");
    $matriculaActual = mysqli_fetch_assoc($resMat)['matricula'];

    // Separar matrícula → AA + idCurso + Grupo + idHijo
    $anio = substr($matriculaActual, 0, 2);      // Ej: "25"
    $idMatHijo = substr($matriculaActual, 4);    // Ej: "20"

    // Obtener grupo del nuevo curso
    $resCurso = mysqli_query($conexion, "SELECT grupo FROM cursos WHERE id_curso = $cursoId");
    $grupoNuevo = strtoupper(mysqli_fetch_assoc($resCurso)['grupo']);

    // Nueva matrícula
    $nuevaMatricula = $anio . $cursoId . $grupoNuevo . $idMatHijo;

    // Guardar nueva matrícula
    mysqli_query($conexion, "
        UPDATE hijos 
        SET matricula = '$nuevaMatricula'
        WHERE id_hijo = $idHijo
    ");
}


echo json_encode(['status' => 'success', 'message' => 'Datos del hijo actualizados correctamente.']);
mysqli_close($conexion);
?>
