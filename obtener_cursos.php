<?php
include 'conexion_be.php';

$query = "
  SELECT c.*, COUNT(i.id_inscripcion) AS alumnos_inscritos
  FROM cursos c
  LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
  WHERE c.estado = 'activo'
  GROUP BY c.id_curso
  ORDER BY c.nombre_curso ASC
";

$result = mysqli_query($conexion, $query);

$cursos = [];
while ($row = mysqli_fetch_assoc($result)) {
  $cursos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($cursos);
?>
