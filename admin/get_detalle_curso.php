<?php
include '../conexion_be.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo json_encode(['error' => 'ID de curso inválido']);
  exit;
}

$id = intval($_GET['id']);

// ==============================
// ⭐ Obtener curso + nombre profesor
// ==============================
$cursoQuery = mysqli_query($conexion, "
  SELECT 
    c.*, 
    p.nombre AS nombre_profesor
  FROM cursos c
  LEFT JOIN profesores p ON p.id_profesor = c.id_profesor
  WHERE c.id_curso = $id
");

if (!$cursoQuery || mysqli_num_rows($cursoQuery) == 0) {
  echo json_encode(['error' => 'Curso no encontrado']);
  exit;
}

$curso = mysqli_fetch_assoc($cursoQuery);

// ==============================
// 👦 Obtener alumnos inscritos
// ==============================
$alumnosQuery = mysqli_query($conexion, "
  SELECT 
    h.id_hijo,
    h.id_usuario,
    h.nombre_completo AS nombre,
    h.foto_perfil AS foto,
    h.edad,
    h.genero
  FROM inscripciones i
  INNER JOIN hijos h ON h.id_hijo = i.id_hijo
  WHERE i.id_curso = $id
");

$alumnos = [];
if ($alumnosQuery) {
  while ($a = mysqli_fetch_assoc($alumnosQuery)) {
    $alumnos[] = $a;
  }
}

echo json_encode([
  'curso' => $curso,
  'alumnos' => $alumnos
], JSON_UNESCAPED_UNICODE);
