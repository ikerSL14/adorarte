<?php
require '../conexion_be.php';

$sql = "
  SELECT u.id_usuario, u.nombre_com, u.correo_elec, u.foto_perfil,
         DATE_FORMAT(u.fecha_registro, '%d/%m/%Y') AS fecha_registro,
         COUNT(h.id_hijo) AS total_hijos
  FROM usuarios u
  LEFT JOIN hijos h ON u.id_usuario = h.id_usuario
  WHERE u.rol = 'usuario'
  GROUP BY u.id_usuario
  ORDER BY u.fecha_registro DESC
";

$result = mysqli_query($conexion, $sql);
$usuarios = [];

while ($row = mysqli_fetch_assoc($result)) {
  $usuarios[] = $row;
}

header('Content-Type: application/json');
echo json_encode($usuarios);
