<?php
// delete_profesor.php
header('Content-Type: application/json; charset=UTF-8');
require '../conexion_be.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
    exit;
}

$id = intval($_POST['id_profesor'] ?? 0);
if ($id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID de profesor inválido.']);
    exit;
}

// función para borrar carpetas recursivamente
function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $objects = scandir($dir);
    foreach ($objects as $object) {
        if ($object === '.' || $object === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $object;
        if (is_dir($path)) {
            rrmdir($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}

// iniciamos transacción para coherencia
$conexion->begin_transaction();

try {
    // 1) Obtener nombre de foto (para borrar carpeta después)
    $stmt = $conexion->prepare("SELECT foto FROM profesores WHERE id_profesor = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $foto = null;
    if ($row = $res->fetch_assoc()) {
        $foto = $row['foto'];
    }
    $stmt->close();

    // 2) Desasignar profesor en cursos (si tienes FK con ON DELETE SET NULL esta query no es estrictamente necesaria,
    //    pero la dejamos para asegurar consistencia si no se configura la FK)
    $stmt = $conexion->prepare("UPDATE cursos SET id_profesor = NULL WHERE id_profesor = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 3) Borrar profesor
    $stmt = $conexion->prepare("DELETE FROM profesores WHERE id_profesor = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($conexion->affected_rows === 0) {
        // posiblemente no existía
        $conexion->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Profesor no encontrado o ya eliminado.']);
        exit;
    }
    $stmt->close();

    // 4) Borrar carpeta de imágenes (si existe)
    $profDir = __DIR__ . '/../imgs/profesores/' . $id;
    if (is_dir($profDir)) {
        rrmdir($profDir);
    }

    $conexion->commit();
    echo json_encode(['status' => 'success', 'message' => 'Profesor eliminado correctamente.']);
    exit;

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()]);
    exit;
}
