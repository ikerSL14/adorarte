<?php
session_start();
include '../../conexion_be.php';

$id_profesor = $_SESSION['id_profesor'];
$consulta = mysqli_query($conexion, "SELECT * FROM profesores WHERE id_profesor = $id_profesor");
$profesor = mysqli_fetch_assoc($consulta);

$nombre_corto = explode(' ', $profesor['nombre'])[0];

// Obtener curso asignado
$curso_query = mysqli_query($conexion, "SELECT * FROM cursos WHERE id_profesor = $id_profesor LIMIT 1");
$curso = mysqli_fetch_assoc($curso_query);

// Determinar cantidad de alumnos
$alumnos_count = 0;

if ($curso) {
    $id_curso = $curso['id_curso'];

    if ($curso['estado'] === 'terminado') {
        // Contar desde historial
        $count_query = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM historial WHERE id_curso = $id_curso");
    } else {
        // Contar desde inscripciones
        $count_query = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM inscripciones WHERE id_curso = $id_curso");
    }

    $alumnos_count = mysqli_fetch_assoc($count_query)['total'];
}
?>

<style>
.inicio-header {
    position: relative;
    background: url('https://images.unsplash.com/photo-1656196075693-c62431afd0be?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center;
    background-size: cover;
    border-radius: 12px;
    color: white;
    padding: 60px 20px;
    margin-bottom: 20px;
}
.inicio-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.59);
    border-radius: 12px;
}
.inicio-header h2, .inicio-header p {
    position: relative;
    margin: 0;
}
.inicio-header h2 {
    font-size: 2rem;
    text-align: left;
}
.inicio-header p {
    text-align: left;
    font-size: 1rem;
    margin-top:1rem;
    color: white;
}

/* Curso asignado */
.badge-curso {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    background-color: #b58900;
    color: white;
    border-radius: 8px;
    font-weight: 500;
}

/* Contenedor blanco alentador */
.contenedor-alento {
    background-color: white;
    padding: 20px;
    border-radius: 12px;
    color: #b58900;
    font-weight: 500;
    text-align: center;
}

.cuadrados-container {
    display: flex;
    gap: 20px;
    margin-top: 15px;
}

.cuadrado {
    background-color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.cuadrado:hover {
    transform: translateY(-3px);
}

.cuadrado-icono {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #b58900;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.cuadrado-texto {
    display: flex;
    flex-direction: column;
}

.cuadrado-texto span:first-child {
    font-weight: bold;
    font-size: 1rem;
}

.cuadrado-texto span:last-child {
    font-weight: 700;
    font-size: 1.5rem;
    margin-top: 10px;
}
.badge-terminado-azul {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background-color: #007BFF;
    color: white;
    border-radius: 8px;
    font-weight: 600;
    margin-left: 10px;
}

</style>

<!-- Cabecera de bienvenida -->
<div class="inicio-header">
    <h2>Bienvenido, <?= htmlspecialchars($nombre_corto) ?>!</h2>

    <?php if ($curso && $curso['estado'] === 'terminado'): ?>
        <p>Has concluído la impartición de un curso. ¡Pronto vendrán más!</p>
    <?php else: ?>
        <p>Siéntete cómodo para impartir tu enseñanza con ayuda del panel.</p>
    <?php endif; ?>
</div>

<!-- Curso asignado o mensaje -->
<?php if ($curso): ?>
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom:2rem;">
        <div class="badge-curso">
            <i class="fas fa-book"></i> 
            <?= htmlspecialchars($curso['nombre_curso']) ?> 
            - <?= htmlspecialchars($curso['grupo']) ?> 
            (ID: <?= $curso['id_curso'] ?>)
        </div>

        <?php if ($curso['estado'] === 'terminado'): ?>
            <div class="badge-terminado-azul">
                <i class="fas fa-check-circle"></i> Terminado
            </div>
        <?php endif; ?>
    </div>

    <!-- Tarjetas -->
    <div class="cuadrados-container" style="display:flex; gap:20px; margin-top:15px;">
        <div class="cuadrado" style="flex:1; height:150px;">
            <div class="cuadrado-icono">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="cuadrado-texto">
                <span>Alumnos inscritos</span>
                <span><?= $alumnos_count ?></span>
            </div>
        </div>

        <?php
// CUADRO DERECHO — dinámico según estado
?>

<div class="cuadrado" style="flex:1; height:150px;">

    <?php if ($curso['estado'] !== 'terminado'): ?>
        <!-- CURSO ACTIVO -->
        <div class="cuadrado-icono">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="cuadrado-texto">
            <span>Horario</span>
            <span><?= htmlspecialchars($curso['dia_hora']) ?></span>
        </div>

    <?php else: ?>
        <!-- CURSO TERMINADO -->
        <?php
        // Sacar promedio grupal desde historial
        $promedio_query = mysqli_query($conexion, 
            "SELECT AVG(calificacion) AS promedio 
             FROM historial 
             WHERE id_curso = {$curso['id_curso']}"
        );
        $promedio_result = mysqli_fetch_assoc($promedio_query);
        $promedio_grupal = $promedio_result['promedio'] ? round($promedio_result['promedio'], 1) : '—';
        ?>

        <div class="cuadrado-icono">
            <i class="fas fa-star-half-alt"></i>
        </div>
        <div class="cuadrado-texto">
            <span>Promedio grupal</span>
            <span><?= $promedio_grupal ?></span>
        </div>

    <?php endif; ?>

</div>

    </div>

<?php else: ?>
    <div class="contenedor-alento">
        Familiarízate con el sistema y explora las funcionalidades mientras esperas tus cursos y alumnos.
    </div>
<?php endif; ?>
