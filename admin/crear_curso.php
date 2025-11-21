<?php
include '../conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_curso'];
    $precio = $_POST['precio'];
    $dia_hora = $_POST['dia_hora'];
    $grupo = $_POST['grupo'];
    $id_profesor = $_POST['id_profesor'];

    // Insertar curso sin imagen primero
    $stmt = $conexion->prepare("INSERT INTO cursos (nombre_curso, precio, dia_hora, grupo, id_profesor, foto) 
                            VALUES (?, ?, ?, ?, ?, '')");
    $stmt->bind_param("sdssi", $nombre, $precio, $dia_hora, $grupo, $id_profesor);
    
    if ($stmt->execute()) {
        $idCurso = $stmt->insert_id;
        $foto = $_FILES['foto'];

        $rutaBase = "../imgs/cursos";
        if (!is_dir($rutaBase)) {
            mkdir($rutaBase, 0777, true);
        }

        $rutaCurso = "$rutaBase/$idCurso";
        if (!is_dir($rutaCurso)) {
            mkdir($rutaCurso, 0777, true);
        }

        $nombreArchivo = basename($foto['name']);
        $rutaFinal = "$rutaCurso/$nombreArchivo";

        if (move_uploaded_file($foto['tmp_name'], $rutaFinal)) {
            $conexion->query("UPDATE cursos SET foto='$nombreArchivo' WHERE id_curso=$idCurso");
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Error al subir la imagen']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Error al crear curso']);
    }
}
?>
