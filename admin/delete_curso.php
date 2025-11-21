<?php
header("Content-Type: application/json; charset=UTF-8");

session_start();
require '../conexion_be.php';

$response = ["success" => false, "message" => ""];

// Solo POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
    exit;
}


if (!isset($_POST["id_curso"]) || empty($_POST["id_curso"])) {
    echo json_encode(["success" => false, "message" => "Falta el ID del curso."]);
    exit;
}

$id_curso = intval($_POST["id_curso"]);

// Iniciar transacción
$conexion->begin_transaction();

try {

    // 1️⃣ Obtener hijos inscritos
    $sql_hijos = "SELECT id_hijo FROM inscripciones WHERE id_curso = ?";
    $stmt = $conexion->prepare($sql_hijos);
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();

    $hijosAfectados = [];
    while ($row = $result->fetch_assoc()) {
        $hijosAfectados[] = $row["id_hijo"];
    }
    $stmt->close();

    // 2️⃣ Cambiar estado de cada hijo a "no_inscrito"
    if (!empty($hijosAfectados)) {

        $sql_update = "UPDATE hijos SET estado = 'no_inscrito' WHERE id_hijo = ?";
        $stmt = $conexion->prepare($sql_update);

        foreach ($hijosAfectados as $id_hijo) {
            $stmt->bind_param("i", $id_hijo);
            $stmt->execute();
        }

        $stmt->close();
    }

    // 3️⃣ Borrar inscripciones del curso
    $sql_del_ins = "DELETE FROM inscripciones WHERE id_curso = ?";
    $stmt = $conexion->prepare($sql_del_ins);
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $stmt->close();

    // 4️⃣ Borrar curso
    $sql_del_curso = "DELETE FROM cursos WHERE id_curso = ?";
    $stmt = $conexion->prepare($sql_del_curso);
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $stmt->close();

    // 5️⃣ Confirmar cambios
    $conexion->commit();

    echo json_encode([
        "success" => true,
        "message" => "Curso y registros eliminados correctamente."
    ]);
    exit;

} catch (Exception $e) {

    // Revertir todos los cambios
    $conexion->rollback();

    echo json_encode([
        "success" => false,
        "message" => "Error al eliminar el curso: " . $e->getMessage()
    ]);
    exit;
}

?>
