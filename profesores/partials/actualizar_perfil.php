<?php
session_start();
include '../../conexion_be.php';

$id_profesor = $_SESSION['id_profesor'];

$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);

$response = ["success" => false, "message" => ""];

// -----------------------------------------------------
// Ruta correcta usando "../" (NUNCA realpath aquí)
// -----------------------------------------------------
$carpeta = __DIR__ . "/../../imgs/profesores/$id_profesor/";

// Crear carpeta si no existe
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

// Foto actual
$consulta = mysqli_query($conexion, "SELECT foto FROM profesores WHERE id_profesor = $id_profesor");
$prof = mysqli_fetch_assoc($consulta);
$fotoActual = $prof['foto'];

$nombreNuevaFoto = $fotoActual;

// ¿Hay nueva foto?
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

    // --------------------------------------
    // BORRAR FOTO ANTERIOR (ya funciona)
    // --------------------------------------
    if ($fotoActual && file_exists($carpeta . $fotoActual)) {
        unlink($carpeta . $fotoActual);
    }

    // Guardar nueva foto
    $nombreNuevaFoto = time() . "_" . basename($_FILES["foto"]["name"]);
    $rutaDestino = $carpeta . $nombreNuevaFoto;

    move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaDestino);
}

// Actualizar BD
$update = mysqli_query($conexion,
    "UPDATE profesores SET
        nombre = '$nombre',
        email = '$email',
        foto = '$nombreNuevaFoto'
     WHERE id_profesor = $id_profesor"
);

if ($update) {
    $response["success"] = true;
    $response["message"] = "Datos guardados correctamente";
} else {
    $response["message"] = "Error al actualizar";
}

echo json_encode($response);
?>
