<?php
include '../conexion_be.php';

// Solo cursos sin profesor y que estén activos
$sql = "SELECT id_curso, nombre_curso, grupo 
        FROM cursos 
        WHERE (id_profesor IS NULL OR id_profesor = 0)
        AND estado = 'activo'";

$result = $conexion->query($sql);

$cursos = [];

while ($row = $result->fetch_assoc()) {
    $cursos[] = $row;
}

echo json_encode($cursos);
?>
