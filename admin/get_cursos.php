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
    COALESCE(c.estado, 'activo') AS estado,

    -- PROFESOR
    c.id_profesor,
    p.nombre AS nombre_profesor,

    -- 🎯 TOTAL ALUMNOS SEGÚN ESTADO
    CASE 
      WHEN COALESCE(c.estado, 'activo') = 'terminado' THEN
        (SELECT COUNT(*) FROM historial h WHERE h.id_curso = c.id_curso)
      ELSE
        (SELECT COUNT(*) FROM inscripciones i WHERE i.id_curso = c.id_curso)
    END AS total_alumnos

  FROM cursos c
  LEFT JOIN profesores p ON c.id_profesor = p.id_profesor

  ORDER BY 
    (COALESCE(c.estado, 'activo') = 'terminado') ASC,
    c.nombre_curso ASC
";


$result = mysqli_query($conexion, $query);
$cursos = [];

while ($row = mysqli_fetch_assoc($result)) {
  $cursos[] = $row;
}

echo json_encode($cursos);
?>
