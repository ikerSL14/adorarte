<?php
include '../conexion_be.php';

$id_profesor = $_GET['id_profesor'] ?? 0;
$id_profesor = intval($id_profesor);

$sql = "
    SELECT 
        c.id_curso,
        c.nombre_curso,
        COALESCE(NULLIF(c.grupo, ''), 'A') AS grupo,
        c.id_profesor
    FROM cursos c
    WHERE c.id_profesor IS NULL 
       OR c.id_profesor = 0
       OR c.id_profesor = $id_profesor
    ORDER BY c.nombre_curso ASC
";

$result = $conexion->query($sql);

$cursos = [];

while ($row = $result->fetch_assoc()) {
    $cursos[] = $row;
}

echo json_encode($cursos);
?>
