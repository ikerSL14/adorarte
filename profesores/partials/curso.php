<?php
session_start();
include '../../conexion_be.php';

$id_profesor = $_SESSION['id_profesor'];

// Obtener curso asignado
$curso_query = mysqli_query($conexion, "SELECT * FROM cursos WHERE id_profesor = $id_profesor LIMIT 1");
$curso = mysqli_fetch_assoc($curso_query);
?>
<style>
/* Imagen de portada */
.curso-portada {
    position: relative;
    height: 250px;
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: -90px; /* superposición con el contenedor blanco */
    z-index: 1;
}

.curso-portada img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.8);
}

/* Contenedor principal del curso */
.curso-info {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    padding: 2rem;
    margin: 0 auto 2rem;
    max-width: 800px;
    position: relative;
    z-index: 2;
    text-align: center;
}

.curso-info h2 {
    font-size: 2rem;
    color: #333;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.curso-info p {
    margin: 0.3rem 0;
    font-size: 1.1rem;
}

.curso-info .grupo {
    display: inline-block;
    background: #000;
    color: #fff;
    padding: 0.3rem 0.9rem;
    border-radius: 12px;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.curso-info .id-curso {
    font-size: 1rem;
    color: #555;
    margin-bottom: 0.2rem;
}

.curso-info .precio {
    font-size: 1.6rem;
    color: #C7AA2B;
    font-weight: bold;
    margin-bottom: 0.2rem;
}

.curso-info .horario {
    color: #555;
    margin-bottom: 0.2rem;
}

/* Contenedor cuando no hay curso */
.sin-curso {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 60vh;
    font-size: 2rem;
    font-weight: 600;
    color: #b58900;
    text-align: center;
}
.badge-terminado {
    display: inline-block;
    background: #007BFF;
    color: #fff;
    padding: 0.35rem 0.9rem;
    border-radius: 12px;
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    font-weight: 600;
}

</style>

<?php if ($curso): 
    // Determinar imagen del curso
    $portada = $curso['foto'] && trim($curso['foto']) !== ""
        ? "../imgs/cursos/{$curso['id_curso']}/{$curso['foto']}"
        : "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=870";
?>
    <!-- Portada -->
    <div class="curso-portada">
        <img src="<?= $portada ?>" alt="Portada del curso">
    </div>

    <!-- Información principal -->
    <div class="curso-info">
        <h2><?= htmlspecialchars($curso['nombre_curso']) ?></h2>
        <?php if ($curso['estado'] === 'terminado'): ?>
            <span class="badge-terminado">Terminado</span>
        <?php endif; ?><br>
        <p class="grupo">Grupo <?= htmlspecialchars($curso['grupo']) ?></p>
        <p class="id-curso">ID del curso: <?= htmlspecialchars($curso['id_curso']) ?></p>
        <p class="precio">$<?= htmlspecialchars($curso['precio']) ?></p>
        <p class="horario"><?= htmlspecialchars($curso['dia_hora']) ?></p>
    </div>

<?php else: ?>
    <div class="sin-curso">
        Aún no tienes un curso asignado 😊
    </div>
<?php endif; ?>
