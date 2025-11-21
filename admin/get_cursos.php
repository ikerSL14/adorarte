<?php
include '../conexion_be.php';
header('Content-Type: application/json');

$query = "
  SELECT 
    c.id_curso,
    c.nombre_curso,
    COALESCE(NULLIF(c.grupo, ''), 'A') AS grupo,
    c.precio,
    c.dia_hora,
    c.foto,

    -- 🔥 PROFESOR
    c.id_profesor,
    p.nombre AS nombre_profesor,

    -- 🔥 TOTAL ALUMNOS
    COUNT(i.id_hijo) AS total_alumnos

  FROM cursos c
  LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
  LEFT JOIN profesores p ON c.id_profesor = p.id_profesor

  GROUP BY c.id_curso
  ORDER BY c.nombre_curso ASC
";


$result = mysqli_query($conexion, $query);
$cursos = [];

while ($row = mysqli_fetch_assoc($result)) {
  $cursos[] = $row;
}

echo json_encode($cursos);
?>
