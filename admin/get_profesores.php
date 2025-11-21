<?php
require '../conexion_be.php';
header('Content-Type: application/json');

// ================================================
//  OBTENER LISTA DE PROFESORES CON SU CURSO
// ================================================
$sql = "
    SELECT 
        p.id_profesor,
        p.nombre,
        p.email,
        p.foto,

        c.id_curso,
        c.nombre_curso,
        c.grupo

    FROM profesores p
    LEFT JOIN cursos c ON p.id_profesor = c.id_profesor
    ORDER BY p.nombre ASC
";

$result = $conexion->query($sql);

$profesores = [];

while ($row = $result->fetch_assoc()) {

    unset($row['contrasena']); // Nunca devolver contraseña

    if (!$row['foto'] || trim($row['foto']) === "") {
        $row['foto'] = "";
    }

    $profesores[] = $row;
}

echo json_encode($profesores);
exit;
?>
