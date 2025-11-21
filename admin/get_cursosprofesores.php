<?php
include '../conexion_be.php';

// solo cursos sin profesor asignado
$sql = "SELECT id_curso, nombre_curso, grupo 
        FROM cursos 
        WHERE id_profesor IS NULL OR id_profesor = 0";

$result = $conexion->query($sql);

$cursos = [];

while ($row = $result->fetch_assoc()) {
    $cursos[] = $row;
}

echo json_encode($cursos);
?>
