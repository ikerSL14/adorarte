<?php
include '../conexion_be.php';

$result = $conexion->query("SELECT id_profesor, nombre FROM profesores");

$profesores = [];

while ($row = $result->fetch_assoc()) {
    $profesores[] = $row;
}

echo json_encode($profesores);
?>
