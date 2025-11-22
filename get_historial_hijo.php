<?php
include 'conexion_be.php';
header("Content-Type: application/json");

$id_hijo = intval($_GET['id'] ?? 0);

$query = "
  SELECT 
    h.id_historial,
    h.id_curso,
    c.nombre_curso,
    c.grupo,
    h.calificacion,
    h.fecha_terminacion
  FROM historial h
  INNER JOIN cursos c ON h.id_curso = c.id_curso
  WHERE h.id_hijo = $id_hijo
  ORDER BY h.fecha_terminacion DESC
";

$result = mysqli_query($conexion, $query);

$historial = [];
while ($row = mysqli_fetch_assoc($result)) {
    $historial[] = $row;
}

echo json_encode($historial);
