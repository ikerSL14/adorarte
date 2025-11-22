<?php
session_start();
include '../../conexion_be.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$cursoId = $data['cursoId'] ?? 0;
$calificaciones = $data['calificaciones'] ?? [];

if(!$cursoId || empty($calificaciones)){
    echo json_encode(['success'=>false, 'message'=>'Datos inválidos']);
    exit;
}

mysqli_begin_transaction($conexion);

try {
    // Insertar en historial
    $stmt = $conexion->prepare("INSERT INTO historial (id_curso, id_hijo, calificacion, fecha_terminacion) VALUES (?, ?, ?, NOW())");
    foreach($calificaciones as $cal){
        $stmt->bind_param("iid", $cursoId, $cal['id_hijo'], $cal['calificacion']);
        $stmt->execute();
    }

    // Actualizar hijos a no_inscrito
    $ids_hijos = array_column($calificaciones, 'id_hijo');
    $ids_hijos_str = implode(',', array_map('intval', $ids_hijos));
    $conexion->query("UPDATE hijos SET estado='no_inscrito' WHERE id_hijo IN ($ids_hijos_str)");

    // Borrar inscripciones
    $conexion->query("DELETE FROM inscripciones WHERE id_curso=$cursoId");

    // Actualizar curso a terminado
    $conexion->query("UPDATE cursos SET estado='terminado' WHERE id_curso=$cursoId");

    mysqli_commit($conexion);
    echo json_encode(['success'=>true]);

} catch(Exception $e){
    mysqli_rollback($conexion);
    echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
}
?>
