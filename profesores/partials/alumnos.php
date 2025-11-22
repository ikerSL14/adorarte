<?php
session_start();
include '../../conexion_be.php';

$id_profesor = $_SESSION['id_profesor'];

// Obtener curso asignado
$curso_query = mysqli_query($conexion, "SELECT * FROM cursos WHERE id_profesor = $id_profesor LIMIT 1");
$curso = mysqli_fetch_assoc($curso_query);

if (!$curso) {
    echo '<div class="sin-curso">Aún no tienes un curso asignado 😊</div>';
    exit;
}

// Determinar imagen del curso
$portada = $curso['foto'] && trim($curso['foto']) !== ""
    ? "../imgs/cursos/{$curso['id_curso']}/{$curso['foto']}"
    : "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=100";

// Contar alumnos según estado del curso
if ($curso['estado'] === 'activo') {
    $alumnos_query = mysqli_query($conexion, "
        SELECT h.*, u.nombre_com as nombre_padre 
        FROM hijos h
        JOIN inscripciones i ON h.id_hijo = i.id_hijo
        JOIN usuarios u ON h.id_usuario = u.id_usuario
        WHERE i.id_curso = {$curso['id_curso']} AND h.estado = 'inscrito'
    ");
    $total_alumnos = mysqli_num_rows($alumnos_query);
} else {
    // Curso terminado
    $alumnos_query = mysqli_query($conexion, "
        SELECT h.*, u.nombre_com as nombre_padre, hi.calificacion
        FROM hijos h
        JOIN historial hi ON h.id_hijo = hi.id_hijo
        JOIN usuarios u ON h.id_usuario = u.id_usuario
        WHERE hi.id_curso = {$curso['id_curso']}
    ");
    $total_alumnos = mysqli_num_rows($alumnos_query);
}
?>

<style>
/* Contenedor superior del curso */
.curso-superior {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.curso-superior-left {
    display: flex;
    align-items: center;
    gap: 15px;
}
.curso-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}
.curso-info {
    display: flex;
    flex-direction: column;
}
.curso-info span {
    font-weight: bold;
    font-size: 1rem;
}
.curso-superior-right {
    display: flex;
    align-items: center;
    gap: 20px;
}
.btn-terminar {
    padding: 8px 15px;
    background-color: #b58900;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

/* Lista de alumnos */
.alumno-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.alumno-left {
    display: flex;
    align-items: center;
    gap: 15px;
}
.alumno-foto {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}
.alumno-info {
    display: flex;
    flex-direction: column;
}
.alumno-info span {
    font-weight: bold;
}
.alumno-detalles {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}
.calificacion-input {
    width: 80px;
    padding: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
h2.alumnos-separador {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin: 30px 0 15px 0;
    border-bottom: 3px solid #b58900; /* línea mostaza */
    padding-bottom: 5px;
}
</style>

<div class="curso-superior">
    <div class="curso-superior-left">
        <img src="<?= $portada ?>" class="curso-img" alt="Curso">
        <div class="curso-info">
            <span><?= htmlspecialchars($curso['nombre_curso']) ?> - Grupo <?= htmlspecialchars($curso['grupo']) ?></span>
            <span>ID: <?= htmlspecialchars($curso['id_curso']) ?></span>
        </div>
    </div>
    <div class="curso-superior-right">
        <span>Total alumnos: <?= $total_alumnos ?></span>
        <?php if($curso['estado'] === 'activo'): ?>
            <button class="btn-terminar" data-curso-id="<?= $curso['id_curso'] ?>">Terminar curso</button>
        <?php endif; ?>
    </div>
</div>
<h2 class="alumnos-separador">Alumnos </h2>

<?php if ($total_alumnos > 0): ?>
    <?php while ($alumno = mysqli_fetch_assoc($alumnos_query)): ?>
        <?php 
            $foto_alumno = $alumno['foto_perfil'] && trim($alumno['foto_perfil']) !== ""
                ? "../imgs/perfil/{$alumno['id_usuario']}/hijos/{$alumno['id_hijo']}/{$alumno['foto_perfil']}"
                : "https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_1280.png";
        ?>
        <div class="alumno-item">
            <div class="alumno-left">
                <img src="<?= $foto_alumno ?>" class="alumno-foto" alt="<?= htmlspecialchars($alumno['nombre_completo']) ?>">
                <div class="alumno-info">
                    <span><?= htmlspecialchars($alumno['nombre_completo']) ?></span>
                    <small>Edad: <?= htmlspecialchars($alumno['edad']) ?> años | 
                        <i class="fa-solid fa-<?= $alumno['genero']==='F'?'venus':'mars' ?>"></i></small>
                    <small>Padre: <?= htmlspecialchars($alumno['nombre_padre']) ?></small>
                </div>
            </div>
            <div class="alumno-detalles">
                <?php if($curso['estado'] === 'activo'): ?>
                    <input type="text" class="calificacion-input" placeholder="Calificación" data-id-hijo="<?= $alumno['id_hijo'] ?>">
                <?php else: ?>
                    <span>Calificación: <?= htmlspecialchars($alumno['calificacion']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No hay alumnos inscritos.</p>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnTerminar = document.querySelector('.btn-terminar');
    if(!btnTerminar) return; // si curso ya terminado, no hay botón
    

    btnTerminar.addEventListener('click', () => {
        const inputs = document.querySelectorAll('.calificacion-input');
        let todasValidas = true;

        inputs.forEach(input => {
            const val = parseFloat(input.value);
            if(isNaN(val) || val < 0 || val > 10){
                todasValidas = false;
            }
        });

        if(!todasValidas){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Todos los alumnos deben tener calificación válida (0-10) antes de terminar el curso.'
            });
            return;
        }

        Swal.fire({
            title: '¿Terminar curso?',
            text: "Esta acción actualizará el estado del curso y de los alumnos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, terminar curso'
        }).then((result) => {
            if(result.isConfirmed){
                const cursoId = <?= $curso['id_curso'] ?>;
                const calificaciones = Array.from(inputs).map(input => ({
    id_hijo: parseInt(input.dataset.idHijo),
    calificacion: parseFloat(input.value)
}));


                fetch('partials/terminar_curso.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({cursoId, calificaciones})
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Curso terminado',
                            text: 'El curso se ha marcado como terminado.'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Ocurrió un error al terminar el curso.'
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error en la solicitud.'
                    });
                });
            }
        });
    });
});
</script>

