<?php
include '../conexion_be.php';

$sql = "
    SELECT p.id_profesor, p.nombre
    FROM profesores p
    WHERE p.id_profesor NOT IN (
        SELECT id_profesor 
        FROM cursos 
        WHERE id_profesor IS NOT NULL AND id_profesor <> 0
    )
    ORDER BY p.nombre ASC
";

$result = $conexion->query($sql);

$profesores = [];

while ($row = $result->fetch_assoc()) {
    $profesores[] = $row;
}

echo json_encode($profesores);
?>
