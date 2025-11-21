<?php
require_once '../conexion_be.php';

$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_usuario <= 0) {
  echo json_encode(['error' => 'ID inválido']);
  exit;
}

// 🔹 Obtener los datos del usuario
$sqlUsuario = "
  SELECT 
    u.id_usuario,
    u.nombre_com,
    u.correo_elec,
    u.foto_perfil,
    DATE_FORMAT(u.fecha_registro, '%d/%m/%Y') AS fecha_registro,
    COUNT(h.id_hijo) AS total_hijos
  FROM usuarios u
  LEFT JOIN hijos h ON u.id_usuario = h.id_usuario
  WHERE u.id_usuario = $id_usuario
  GROUP BY u.id_usuario
";
$resUsuario = mysqli_query($conexion, $sqlUsuario);
$usuario = mysqli_fetch_assoc($resUsuario);

if (!$usuario) {
  echo json_encode(['error' => 'Usuario no encontrado']);
  exit;
}

// 🔹 Obtener los hijos del usuario (con curso)
$sqlHijos = "
  SELECT 
    h.id_hijo,
    h.nombre_completo AS nombre,
    h.edad,
    h.genero,
    h.foto_perfil AS foto,
    c.id_curso,
    c.nombre_curso,
    c.grupo
  FROM hijos h
  LEFT JOIN inscripciones i ON i.id_hijo = h.id_hijo
  LEFT JOIN cursos c ON i.id_curso = c.id_curso
  WHERE h.id_usuario = $id_usuario
";
$resHijos = mysqli_query($conexion, $sqlHijos);

$hijos = [];
while ($row = mysqli_fetch_assoc($resHijos)) {
  $hijos[] = $row;
}

// 🔹 Enviar respuesta
echo json_encode([
  'usuario' => $usuario,
  'hijos' => $hijos
]);
?>
