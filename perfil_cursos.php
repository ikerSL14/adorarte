<?php
    session_start();//
    if (!isset($_SESSION['idUsuario'])) {
    header("Location: index.php");
    exit();
}
include 'conexion_be.php';
$idUsuario = $_SESSION['idUsuario'];
$query = "SELECT nombre_com, correo_elec, fecha_registro, id_usuario, foto_perfil FROM usuarios WHERE id_usuario = $idUsuario LIMIT 1";
$result = mysqli_query($conexion, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $usuario = mysqli_fetch_assoc($result);
} else {
    // Si no encuentra usuario, cerrar sesión y redirigir
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="imgs/adorate.png">
	<title>Adorate</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
	<link rel="stylesheet" href="adorate.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Great+Vibes&family=Dancing+Script&family=Lobster&family=Alex+Brush&family=Satisfy&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Great+Vibes&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  
	<script src="https://kit.fontawesome.com/46e8cbfbf8.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
	
	<style>
        body {
  padding-right: 0 !important;
  
}

.logo-fondo {
  position: fixed;
  top: 50%;
  left: 43%;
  transform: translate(-50%, -50%);
  opacity: 0.05;
  z-index: 0;
  pointer-events: none;
  width: 300px;
  height: auto;
}

.contenido-principal {
  position: relative;
  z-index: 3;
}
.perfil {
  background: linear-gradient(to right, #C7AA2B, #7A6F1F);
  padding: 2rem 5%;
  color: #fff;
  margin-top:4rem;
}

.perfil-contenedor {
  display: flex;
  align-items: center;
  max-width: 900px;
  margin: 0 auto;
  gap: 2rem;
  flex-wrap: wrap; /* para que sea responsive */
}

.perfil-imagen .imagen-circulo {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #fff;
  flex-shrink: 0;
  background-color: #fff; /* fondo blanco para mejor contraste */
}

.perfil-imagen img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.perfil-info {
  flex: 1;
  min-width: 200px;
}

.perfil-nombre-editar {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.perfil-nombre-editar h2 {
  margin: 0;
  font-size: 2rem;
  font-weight: 700;
}

.btn-editar {
  background: transparent;
  border: none;
  color: #fff;
  cursor: pointer;
  font-size: 1.8rem;
  transition: color 0.3s ease;
}

.btn-editar:hover {
  color: #F0E68C; /* un dorado más claro al pasar el mouse */
}

.perfil-correo {
  margin-top: 0.5rem;
  font-size: 1.1rem;
  opacity: 0.9;
  word-break: break-word;
}
/* Sección Hijos */
.hijos {
    padding: 2rem 5%;
}

.hijos h1 {
    text-align: left;
    color: #3C1E06;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    padding-left: 1rem;
}

.agregar-hijo {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    margin-left: 1rem;
    margin-bottom: 2rem;
    border: 2px dashed #C7AA2B;
    border-radius: 25px;
    color: #C7AA2B;
    cursor: pointer;
    transition: all 0.3s ease;
}

.agregar-hijo:hover {
    background-color: rgba(199, 170, 43, 0.1);
    transform: scale(1.05);
}

.agregar-hijo i {
    font-size: 1.2rem;
}

.hijos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
    padding: 0 1rem;
}

.hijo-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.hijo-card:hover {
    transform: translateY(-5px);
}

.hijo-header {
    position: relative;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.hijo-imagen {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #C7AA2B;
    flex-shrink: 0;
}

.hijo-imagen img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Contenedor de botones para alinear */
.hijo-acciones {
    margin-left: auto;
    display: flex;
    gap: 0.5rem;
}

/* Estilos comunes para botones */
.hijo-acciones button {
    background: rgba(255,255,255,0.8);
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #3C1E06;
    font-size: 1.3rem;
}

/* Hover editar */
.btn-editar-hijo:hover {
    background: #C7AA2B;
    color: white;
}

/* Hover borrar con círculo rojo */
.btn-borrar-hijo:hover {
    background: #e74c3c;
    color: white;
}

.hijo-info {
    padding: 1rem;
    background:rgba(241, 241, 241, 0.87)
}

.hijo-info h3 {
    margin: 0;
    color: #3C1E06;
    font-size: 1.2rem;
}

.hijo-info p {
    margin: 0.5rem 0;
    color: #666;
    font-size: 0.9rem;
}

.curso {
    color: #C7AA2B !important;
    font-weight: 500;
}

.no-inscrito {
    color: #999 !important;
    font-style: italic;
}
/* Modal base */
.modal {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background-color: rgba(0,0,0,0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  overflow-y: auto;
  padding: 20px;
}

.modal.hidden {
  display: none;
}

.modal-content {
  background: #fff;
  border-radius: 12px;
  padding: 2rem;
  margin-top:10rem;
  max-width: 500px;
  width: 100%;
  position: relative;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.close-modal {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 1.8rem;
  cursor: pointer;
  color: #333;
}

.modal-content h2 {
  margin-top: 0;
  margin-bottom: 1rem;
  color: #3C1E06;
}

/* Formulario */
form label {
  display: block;
  margin-top: 1rem;
  font-weight: 600;
  color: #3C1E06;
}

form input[type="text"],
form input[type="number"],
form input[type="email"],
form input[type="password"],
form select {
  width: 100%;
  padding: 0.5rem;
  margin-top: 0.3rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.form-row {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.imagen-circulo {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid #C7AA2B;
  flex-shrink: 0;
}

.imagen-circulo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* Lista de cursos */
.cursos-lista {
  margin-top: 0.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.7rem;
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 0.5rem;
}

.curso-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  padding: 0.3rem 0.5rem;
  border-radius: 6px;
  transition: background-color 0.2s ease;
}

.curso-item:hover {
  background-color: #f0e68c33;
}

.curso-item input[type="radio"] {
  cursor: pointer;
}

.curso-icono {
  font-size: 1.5rem;
  color: #C7AA2B;
  width: 30px;
  text-align: center;
}

.curso-nombre {
  font-weight: 600;
  color: #3C1E06;
  flex: 1;
}

.curso-horario {
  font-size: 0.85rem;
  color: #666;
}

/* Botón */
.btn-inscribir {
  margin-top: 1.5rem;
  background-color: #C7AA2B;
  border: none;
  color: white;
  padding: 0.8rem 1.5rem;
  font-weight: 700;
  border-radius: 8px;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.3s ease;
}

.btn-inscribir:hover {
  background-color: #a68a1a;
}
fieldset {
  border: 2px solid #C7AA2B;
  border-radius: 12px;
  padding: 1rem 1.5rem 1.5rem 1.5rem;
  margin-top: 1rem;
  position: relative;
  background: #fff;
}

legend {
  padding: 0 10px;
  font-weight: 600;
  color: #3C1E06;
  border-radius: 8px;
  background: #fff;
  border: 2px solid #C7AA2B;
  position: relative;
  top: 0rem;
  left: 0rem;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.cursos {
  padding: 2rem 5%;
  max-width: 1200px;
  margin: 0 auto;
}

.titulo-cursos {
  font-size: 2.5rem;
  font-weight: 700;
  color: #3C1E06;
  margin-bottom: 2rem;
  text-align: center;
}

.cursos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
}

.curso-card {
  display: flex;
  flex-direction: column;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 15px rgba(0,0,0,0.15);
  background: #fff;
  transition: transform 0.3s ease;
  height: 100%; /* <-- Asegura que el card pueda crecer completo */
}

.curso-card:hover {
  transform: scale(1.03);
}

.curso-imagen img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.curso-info-blanco {
  background: #fff;
  padding: 1rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex-grow: 1;
}

.curso-icono-nombre {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.curso-icono-nombre .icono {
  font-size: 2rem;
  color: #C7AA2B;
  flex-shrink: 0;
}

.curso-icono-nombre h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #3C1E06;
}

.precio {
  font-weight: 700;
  color: #C7AA2B;
  font-size: 1.2rem;
}

.curso-info-gris {
  background: #f0f0f0;
  padding: 1rem 0.5rem;
  border-top: 1px solid #ddd;
  flex-shrink: 0; /* <-- Que no se encoja */
  min-height: 120px; /* <-- Opcional: garantiza una altura mínima consistente */
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* Para que el contenido quede bien distribuido */
}

.curso-info-gris h3 {
  margin: 0 0 0.8rem 0;
  margin-left:1rem;
  color: #555;
  font-weight: 600;
}

.tabla-horarios {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  text-align: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  color: #333;
}

.col-titulo {
  font-weight: 700;
  color: #777;
}

.col-dato {
  font-weight: 500;
}

/* Responsive */

@media (max-width: 600px) {
  .curso-info-gris .tabla-horarios {
    grid-template-columns: 1fr 1fr;
    grid-auto-rows: auto;
  }
  .col-titulo:nth-child(3),
  .col-dato:nth-child(3){
    display: none;
  }
}
/* Estilo para curso actual */
.curso-actual {
  opacity: 0.6;
  background-color: #f8f8f8;
  cursor: not-allowed;
}

.curso-actual input[type="radio"] {
  cursor: not-allowed;
}
.historial {
  max-width: 1100px;
  margin: 2rem auto;
  padding: 0 5%;
  color: #3C1E06;
}

.historial h2 {
  font-size: 2rem;
  margin-bottom: 1.5rem;
  font-weight: 700;
  text-align: center;
}

.compra-item {
  border: 1px solid #ccc;
  border-radius: 12px;
  margin-bottom: 1rem;
  overflow: hidden;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.compra-resumen {
  background-color: #f0f0f0;
  padding: 1rem 1.5rem;
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  align-items: center;
  cursor: pointer;
  justify-content: space-between;
  transition: background-color 0.3s ease;
}
.compra-resumen:hover{
  background-color:rgb(226, 226, 226);
}

.compra-resumen span {
  flex: 1 1 150px;
  font-weight: 600;
}

.btn-toggle-detalles {
  background: none;
  border: none;
  font-weight: 700;
  color: #C7AA2B;
  cursor: pointer;
  padding: 0.3rem 0.6rem;
  border-radius: 6px;
  transition: background-color 0.3s ease;
}

.btn-toggle-detalles:hover {
  background-color: #a68a1a;
  color: white;
}

.compra-detalles {
  background-color: #fff;
  padding: 1rem 2rem;
  border-top: 1px solid #ccc;
}

.compra-detalles.hidden {
  display: none;
}

.compra-detalles ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.compra-detalles li {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #eee;
}

.compra-detalles li:last-child {
  border-bottom: none;
}

.compra-detalles .titulo {
  flex: 2;
}

.compra-detalles .precio,
.compra-detalles .cantidad {
  flex: 1;
  text-align: right;
}

/* Responsive */
@media (max-width: 600px) {
  .compra-resumen {
    flex-direction: column;
    gap: 0.5rem;
  }
  .compra-resumen span {
    flex: 1 1 100%;
  }
  .compra-detalles li {
    flex-direction: column;
    align-items: flex-start;
  }
  .compra-detalles .precio,
  .compra-detalles .cantidad {
    text-align: left;
  }
}
.swal2-container {
  z-index: 20000 !important;
}
html{
  overflow-y:scroll;
}
.curso-lleno {
  opacity: 0.6;
  background-color: #f8f8f8;
  color: #990000 !important;
  cursor: not-allowed;
}

.curso-lleno input[type="radio"] {
  cursor: not-allowed;
}
    </style>
    
</head>
<body>
<div class="contenido-principal">
	<!-- header section -->
	<header>
		
		<a href="#" class="logo"><img src="imgs/adorate.png" width="55" alt="logo"></a>
    <div id="menu-icon" class="bx bx-menu"></div> <!-- Icono hamburguesa -->
		<ul class="navbar">
      
            <li><a href="index.php" style="color:#C7AA2B;">Inicio</a></li>
            <?php 
            if (isset($_SESSION['nombre'])) {
                // Obtener el nombre completo
                $nombreCompleto = $_SESSION['nombre'];

                // Separar por espacios y tomar la primera palabra
                $partesNombre = explode(' ', trim($nombreCompleto));
                $primerNombre = ucfirst($partesNombre[0]); // Primera palabra con primera letra mayúscula

                echo '<li><a href="perfil.php" style="color:#C7AA2B;" id="userLink">
                        <i class="bx bxs-user" style="margin-right:0.7rem;"></i>' . htmlspecialchars($primerNombre) . '
                      </a></li>';
            }
            ?>
            <li><a href="perfil_cursos.php">Cursos</a></li>
            <li><a href="perfil_historial.php">Historial</a></li>
            <li><a href="https://drive.google.com/file/d/1GpO4hfCu0mNDoaR4s7zPHs4a9xIVX5yH/view" target="_blank" rel="noopener noreferrer">Programas</a></li>
			<li><a href="cerrar_sesion.php" id="cerrarLink" style="color:#C7AA2B;">Cerrar Sesión</a><li>
		</ul>
         <a href="#carrito" style="visibility:hidden;">
		<div class="h-icons">
			
			<i class='bx bx-cart' ></i>
			
		</div>
        </a>
        </header>
        
  <script>
    const menuIcon = document.getElementById('menu-icon');
const navbar = document.querySelector('.navbar');

menuIcon.addEventListener('click', () => {
  navbar.classList.toggle('open');
  menuIcon.classList.toggle('bx-x'); // Cambia icono a "X" al abrir
});
document.querySelectorAll('.navbar a').forEach(link => {
  link.addEventListener('click', () => {
    if(navbar.classList.contains('open')){
      navbar.classList.remove('open');
      menuIcon.classList.remove('bx-x');
    }
  });
});
  </script>
  </div>
  <!-- Sección: Perfil -->
<section class="perfil">
  <div class="perfil-contenedor">
    <div class="perfil-imagen">
      <div class="imagen-circulo">
        <img 
          src="<?php 
            echo !empty($usuario['foto_perfil']) 
              ? 'imgs/perfil/' . $usuario['id_usuario'] . '/' . htmlspecialchars($usuario['foto_perfil']) 
              : 'imgs/usuario-placeholder.png'; 
          ?>" 
          alt="Imagen de perfil"
        >
      </div>
    </div>
    <div class="perfil-info">
      <div class="perfil-nombre-editar">
        <h2><?php echo htmlspecialchars($usuario['nombre_com']); ?></h2>
        <button class="btn-editar" title="Editar perfil">
          <i class='bx bx-edit-alt'></i>
        </button>
      </div>
      <p class="perfil-correo"><?php echo htmlspecialchars($usuario['correo_elec']); ?></p>
    </div>
  </div>
  <!-- Modal Editar Perfil -->
<div id="modalEditarPerfil" class="modal hidden">
  <div class="modal-content" style="margin-top:2rem;">
    <span class="close-modal" title="Cerrar">&times;</span>
    <h2>Editar Perfil</h2>
    <form id="formEditarPerfil" method="POST" action="editar_perfil.php" enctype="multipart/form-data">
      <div class="form-row">
        <div class="imagen-circulo">
          <img src="<?php 
            echo !empty($usuario['foto_perfil']) 
              ? 'imgs/perfil/' . $usuario['id_usuario'] . '/' . htmlspecialchars($usuario['foto_perfil']) 
              : 'imgs/usuario-placeholder.png'; 
          ?>" alt="Foto de perfil" id="perfilPreviewImagen">
        </div>
        <div>
          <label for="imagenPerfil">Seleccionar imagen</label>
          <input type="file" id="imagenPerfil" name="imagenPerfil" accept="image/*">
          <!-- Imagen aún no disponible -->
        </div>
      </div>

      <label for="nombrePerfil">Nombre completo</label>
      <input type="text" id="nombrePerfil" name="nombre_completo" required value="<?php echo htmlspecialchars($usuario['nombre_com']); ?>">

      <label for="correoPerfil">Correo electrónico</label>
      <input type="email" id="correoPerfil" name="correo_elec" required value="<?php echo htmlspecialchars($usuario['correo_elec']); ?>">

      <label for="contrasenaPerfil">Contraseña</label>
      <input type="password" id="contrasenaPerfil" name="contrasena" placeholder="Nueva contraseña (dejar vacío para no cambiar)">

      <label>Fecha de registro</label>
      <p style="color:rgb(51, 51, 51);"><?php echo date("d/m/Y H:i", strtotime($usuario['fecha_registro'])); ?></p>

      <button type="submit" class="btn-inscribir">Guardar datos</button>
    </form>
  </div>
</div>

</section>

<!-- Sección: Hijos -->
<section class="hijos" id="hijos" style="display: none;">
    <h1>Hijos</h1>
    
    <div class="agregar-hijo" id="agregarHijo">
        <i class='bx bx-plus'></i>
        <span>Inscribir hijo</span>
    </div>

    <!-- Modal Agregar Hijo -->
<div id="modalAgregarHijo" class="modal hidden">
  <div class="modal-content">
    <span class="close-modal" title="Cerrar">&times;</span>
    <h2>Inscribir hijo</h2>
    <form id="formAgregarHijo" method="POST" action="agregar_hijo.php" enctype="multipart/form-data">
      <div class="form-row">
        <div class="imagen-circulo">
          <img src="imgs/usuario-placeholder.png" alt="Foto del hijo" id="previewImagen">
        </div>
        <div>
          <label for="imagenHijo">Seleccionar imagen</label>
          <input type="file" id="imagenHijo" name="imagenHijo" accept="image/*">
          <!-- Por ahora deshabilitado -->
        </div>
      </div>
      
      <label for="nombreHijo">Nombre completo</label>
      <input type="text" id="nombreHijo" name="nombre_completo" required>

      <label for="edadHijo">Edad</label>
      <input type="number" id="edadHijo" name="edad" min="1" max="23" required>

      <label for="generoHijo">Género</label>
      <select id="generoHijo" name="genero" required>
        <option value="" disabled selected>Selecciona género</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
        <option value="Otro">Otro</option>
      </select>

      <fieldset>
        <legend>Selecciona un curso</legend>
        <div class="cursos-lista">
          <!-- Aquí se cargarán los cursos dinámicamente -->
          <?php
          // Consulta cursos para listar
          $query_cursos = "
          SELECT c.*, COUNT(i.id_inscripcion) AS alumnos_inscritos
          FROM cursos c
          LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
          GROUP BY c.id_curso
          ORDER BY c.nombre_curso ASC
        ";
        $result_cursos = mysqli_query($conexion, $query_cursos);

        while($curso = mysqli_fetch_assoc($result_cursos)):
          $lleno = ($curso['alumnos_inscritos'] >= 3);
          ?>
          <label class="curso-item" title="<?php echo $lleno ? 'Curso lleno' : ''; ?>">
            <input type="radio" name="curso_id" value="<?php echo $curso['id_curso']; ?>" required <?php echo $lleno ? 'disabled' : ''; ?>>
            <span class="curso-icono">
              <?php
              // Iconos según nombre del curso (puedes personalizar)
              switch(strtolower($curso['nombre_curso'])){
                case 'guitarra': echo "<i class='fa-solid fa-guitar'></i>"; break;
                case 'canto': echo "<i class='fa-solid fa-microphone'></i>"; break;
                case 'piano': echo "<i class='fa-solid fa-music'></i>"; break;
                case 'batería': echo "<i class='fa-solid fa-drum'></i>"; break;
                case 'bajo': echo "<i class='fa-solid fa-headphones'></i>"; break;
                default: echo "<i class='f-solid fa-music'></i>"; break;
              }
              ?>
            </span>
            <span class="curso-nombre <?php echo $lleno ? 'curso-lleno' : ''; ?>">
              <?php echo htmlspecialchars($curso['nombre_curso']); ?>
            </span>
            <span class="curso-horario"><?php echo htmlspecialchars($curso['dia_hora']); ?></span>
          </label>
          <?php endwhile; ?>
        </div>
      </fieldset>

      <button type="submit" class="btn-inscribir">Inscribir</button>
    </form>
  </div>
</div>

    <div class="hijos-grid">
        <?php
        // Consultar hijos del usuario
        $query_hijos = "SELECT h.*, c.nombre_curso, i.id_curso, c.dia_hora
               FROM hijos h 
               LEFT JOIN inscripciones i ON h.id_hijo = i.id_hijo 
               LEFT JOIN cursos c ON i.id_curso = c.id_curso 
               WHERE h.id_usuario = $idUsuario";
        $result_hijos = mysqli_query($conexion, $query_hijos);
        
        if(mysqli_num_rows($result_hijos) > 0):
            while($hijo = mysqli_fetch_assoc($result_hijos)):
        ?>
        <div class="hijo-card"data-id="<?php echo $hijo['id_hijo']; ?>" data-genero="<?php echo $hijo['genero']; ?>" data-curso-id="<?php echo isset($hijo['id_curso']) ? $hijo['id_curso'] : '';  ?>" data-foto-perfil="<?php echo htmlspecialchars($hijo['foto_perfil'] ?? ''); ?>">  <!-- NUEVO -->
            <div class="hijo-header">
                <div class="hijo-imagen">
                    <img src="<?php 
                        echo (!empty($hijo['foto_perfil'])) 
                            ? 'imgs/perfil/' . $idUsuario . '/hijos/' . $hijo['id_hijo'] . '/' . htmlspecialchars($hijo['foto_perfil'])
                            : 'imgs/usuario-placeholder.png'; 
                    ?>" alt="Foto del niño">
                </div>
                <div class="hijo-acciones">
                    <button class="btn-editar-hijo" title="Editar hijo">
                        <i class='bx bx-edit-alt'></i>
                    </button>
                    <button class="btn-borrar-hijo" title="Borrar hijo" data-id="<?php echo $hijo['id_hijo']; ?>">
                      <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
            <div class="hijo-info">
                <h3><?php echo htmlspecialchars($hijo['nombre_completo']); ?></h3>
                <p>Edad: <?php echo $hijo['edad']; ?> años</p>
                <?php if($hijo['nombre_curso']): ?>
                    <p class="curso">Curso: <?php echo htmlspecialchars($hijo['nombre_curso']); ?></p>
                    <p class="curso">Horario: <?php echo htmlspecialchars($hijo['dia_hora']); ?></p>
                <?php else: ?>
                    <p class="curso no-inscrito">Sin curso asignado</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
            endwhile;
        endif;
        ?>
    </div>
    <!-- Modal Editar Hijo -->
<div id="modalEditarHijo" class="modal hidden">
  <div class="modal-content">
    <span class="close-modal" title="Cerrar">&times;</span>
    <h2>Editar Hijo</h2>
    <form id="formEditarHijo" method="POST" action="editar_hijo.php" enctype="multipart/form-data">
      <input type="hidden" name="id_hijo" id="editarIdHijo">

      <div class="form-row">
        <div class="imagen-circulo">
         <img src="imgs/usuario-placeholder.png" alt="Foto del hijo" id="editarPreviewImagen" 
     data-foto-actual="<?php echo $hijo['foto_perfil'] ?? ''; ?>">
        </div>
        <div>
          <label for="editarImagenHijo">Seleccionar imagen</label>
          <input type="file" id="editarImagenHijo" name="imagenHijo" accept="image/*">
        </div>
      </div>

      <label for="editarNombreHijo">Nombre completo</label>
      <input type="text" id="editarNombreHijo" name="nombre_completo" required>

      <label for="editarEdadHijo">Edad</label>
      <input type="number" id="editarEdadHijo" name="edad" min="1" max="23" required>

      <label for="editarGeneroHijo">Género</label>
      <select id="editarGeneroHijo" name="genero" required>
        <option value="" disabled>Selecciona género</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
        <option value="Otro">Otro</option>
      </select>

      <fieldset>
        <legend>Selecciona un curso</legend>
        <div class="cursos-lista" id="editarCursosLista">
          <!-- Cursos cargados dinámicamente -->
        </div>
      </fieldset>

      <button type="submit" class="btn-inscribir">Guardar Cambios</button>
    </form>
  </div>
</div>

</section>

<!-- Sección: cursos -->
<section class="cursos" id="cursos">
  <h1 class="titulo-cursos">Cursos</h1>
  <div class="cursos-grid">
    <?php
    // Consulta cursos con conteo de alumnos inscritos
    $query = "
      SELECT c.*, COUNT(i.id_inscripcion) AS alumnos_inscritos
      FROM cursos c
      LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
      GROUP BY c.id_curso
      ORDER BY c.nombre_curso ASC
    ";
    $result = mysqli_query($conexion, $query);

    // Función para mostrar iconos (usando Boxicons)
    function iconoCurso($nombre) {
      switch(strtolower($nombre)) {
        case 'guitarra': return "<i class='fa-solid fa-guitar'></i>";
        case 'canto': return "<i class='bx bx-microphone'></i>";
        case 'piano': return "<i class='fa-solid fa-music'></i>";
        case 'batería': return "<i class='fa-solid fa-drum'></i>";
        case 'bajo': return "<i class='fa-solid fa-headphones'></i>";
        default: return "<i class='bx bx-music'></i>";
      }
    }

    while($curso = mysqli_fetch_assoc($result)):
    ?>
    <div class="curso-card">
      <div class="curso-imagen">
        <?php
          // Ruta base (ajústala si estás en admin/dashboard/)
          $basePath = "imgs/cursos/";

          // Verificar si tiene foto
          if (!empty($curso['foto']) && trim($curso['foto']) !== "") {
              $rutaImagen = $basePath . $curso['id_curso'] . "/" . $curso['foto'];
              if (!file_exists($rutaImagen)) {
                  // Si no existe el archivo en disco
                  $rutaImagen = "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=870";
              }
          } else {
              // Si no tiene foto registrada
              $rutaImagen = "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=870";
          }
        ?>
        <img src="<?php echo htmlspecialchars($rutaImagen); ?>" alt="<?php echo htmlspecialchars($curso['nombre_curso']); ?>">
      </div>

      <div class="curso-info-blanco">
        <div class="curso-icono-nombre">
          <span class="icono"><?php echo iconoCurso($curso['nombre_curso']); ?></span>
          <h2 class="<?php echo ($curso['alumnos_inscritos'] >= 3) ? 'curso-lleno' : ''; ?>">
            <?php echo htmlspecialchars($curso['nombre_curso']); ?>
          </h2>
        </div>
        <p class="precio">$<?php echo number_format($curso['precio'], 2); ?> MXN</p>
      </div>
      <div class="curso-info-gris">
        <h3>Horarios:</h3>
        <div class="tabla-horarios">
          <div class="col-titulo">Grupo</div>
          <div class="col-titulo">Día y horario</div>
          <div class="col-titulo">Alumnos</div>

          <div class="col-dato">
            <?php echo htmlspecialchars($curso['grupo']); ?>
          </div>
          <div class="col-dato">
            <?php echo htmlspecialchars($curso['dia_hora']); ?>
          </div>
          <div class="col-dato <?php echo ($curso['alumnos_inscritos'] >= 3) ? 'curso-lleno' : ''; ?>">
            <?php echo $curso['alumnos_inscritos']; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</section>
	<!-- contact section -->

		<section class="contact" id="contact">
			<div class="main-contact">
				<div class="contact-content">
					<h4>Servicios</h4>
					<li><a href="perfil.php">Hijos</a></li>
					<li><a href="#cursos">Cursos</a></li>
          <li><a href="perfil_historial.php">Historial</a></li>
				</div>
				<div class="contact-content">
					<h4>Siguenos</h4>
					<li><a href="#">TikTok</a></li>
					<li><a href="#">Facebook</a></li>
				</div>
			</div>
		</section>

		<div class="last-text">
			<p>© Adorate 2025</p>
		</div>

	<!-- scroll top -->
	<a href="#home" class="scroll-top">
		<i class='bx bx-up-arrow-alt' ></i>
	</a>

    <script>
      document.getElementById('imagenPerfil').addEventListener('change', function(e) {
  const [file] = this.files;
  if (file) {
    document.getElementById('perfilPreviewImagen').src = URL.createObjectURL(file);
  }
});
const btnEditarPerfil = document.querySelector('.btn-editar');
const modalEditarPerfil = document.getElementById('modalEditarPerfil');
const closeModalPerfil = modalEditarPerfil.querySelector('.close-modal');

btnEditarPerfil.addEventListener('click', () => {
  modalEditarPerfil.classList.remove('hidden');
});

closeModalPerfil.addEventListener('click', () => {
  modalEditarPerfil.classList.add('hidden');
});

modalEditarPerfil.addEventListener('click', e => {
  if (e.target === modalEditarPerfil) {
    modalEditarPerfil.classList.add('hidden');
  }
});
// Para agregar hijo
document.getElementById('imagenHijo').addEventListener('change', function(e) {
    const [file] = this.files;
    if (file) {
        document.getElementById('previewImagen').src = URL.createObjectURL(file);
    }
});

// Para editar hijo
document.getElementById('editarImagenHijo').addEventListener('change', function(e) {
    const [file] = this.files;
    const preview = document.getElementById('editarPreviewImagen');
    
    if (file) {
        preview.src = URL.createObjectURL(file);
    } else {
        // Restaurar imagen actual si existe
        const fotoActual = preview.dataset.fotoActual;
        if (fotoActual) {
            preview.src = `imgs/perfil/${<?php echo $idUsuario; ?>}/hijos/${preview.dataset.id}/${fotoActual}`;
        }
    }
});
        const btnAgregarHijo = document.getElementById('agregarHijo');
const modalAgregarHijo = document.getElementById('modalAgregarHijo');
const closeModalBtn = modalAgregarHijo.querySelector('.close-modal');

btnAgregarHijo.addEventListener('click', () => {
  modalAgregarHijo.classList.remove('hidden');
});

closeModalBtn.addEventListener('click', () => {
  modalAgregarHijo.classList.add('hidden');
});

// Cerrar modal al hacer click fuera del contenido
modalAgregarHijo.addEventListener('click', (e) => {
  if(e.target === modalAgregarHijo) {
    modalAgregarHijo.classList.add('hidden');
  }
});
const modalEditar = document.getElementById('modalEditarHijo');
const closeEditar = modalEditar.querySelector('.close-modal');

document.querySelectorAll('.btn-editar-hijo').forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.hijo-card');
    const idHijo = card.dataset.id;
    const nombre = card.querySelector('h3').textContent;
    const edadText = card.querySelector('p').textContent;
    const edad = edadText.match(/\d+/)[0];
    const genero = card.dataset.genero; // Debes agregar este atributo en PHP
    const cursoId = card.dataset.cursoId; // Debes agregar este atributo en PHP
    const fotoPerfil = card.dataset.fotoPerfil; // <---- NUEVO

    // Rellenar formulario
    document.getElementById('editarIdHijo').value = idHijo;
    document.getElementById('editarNombreHijo').value = nombre;
    document.getElementById('editarEdadHijo').value = edad;
    document.getElementById('editarGeneroHijo').value = genero;

     // Cargar foto de perfil
    const editarPreviewImagen = document.getElementById('editarPreviewImagen');
    editarPreviewImagen.src = fotoPerfil
      ? `imgs/perfil/${<?php echo $idUsuario; ?>}/hijos/${idHijo}/${fotoPerfil}`
      : 'imgs/usuario-placeholder.png';

    // Cargar cursos y marcar el curso actual (deshabilitarlo)
    fetch('obtener_cursos.php')
      .then(res => res.json())
      .then(cursos => {
        const lista = document.getElementById('editarCursosLista');
        lista.innerHTML = '';
        const cursoIdNum = Number(cursoId); // convertir a número
        cursos.forEach(curso => {
          const cursoIdCursoNum = Number(curso.id_curso);
          const isCurrent = cursoIdCursoNum === cursoIdNum; // para editar hijo
          const lleno = curso.alumnos_inscritos >= 3;

          // Deshabilitar solo si está lleno y NO es el curso actual (en editar)
          const disabled = (lleno && !isCurrent) ? 'disabled' : '';
          const claseCursoLleno = (lleno && !isCurrent) ? 'curso-lleno' : '';

          lista.innerHTML += `
            <label class="curso-item ${claseCursoLleno}">
              <input type="radio" name="curso_id" value="${curso.id_curso}" ${isCurrent ? 'checked disabled' : ''} ${disabled} required>
              <span class="curso-icono">${obtenerIcono(curso.nombre_curso)}</span>
              <span class="curso-nombre">${curso.nombre_curso}</span>
              <span class="curso-horario">${curso.dia_hora}</span>
            </label>
          `;
        });
      });

    modalEditar.classList.remove('hidden');
  });
});

closeEditar.addEventListener('click', () => {
  modalEditar.classList.add('hidden');
});

modalEditar.addEventListener('click', e => {
  if (e.target === modalEditar) {
    modalEditar.classList.add('hidden');
  }
});

function obtenerIcono(nombreCurso) {
  switch(nombreCurso.toLowerCase()) {
    case 'guitarra': return "<i class='fa-solid fa-guitar'></i>";
    case 'canto': return "<i class='fa-solid fa-microphone'></i>";
    case 'piano': return "<i class='fa-solid fa-music'></i>";
    case 'batería': return "<i class='fa-solid fa-drum'></i>";
    case 'bajo': return "<i class='fa-solid fa-headphones'></i>";
    default: return "<i class='f-solid fa-music'></i>";
  }
}

document.querySelectorAll('.compra-resumen').forEach(resumen => {
  resumen.addEventListener('click', () => {
    const detallesId = resumen.getAttribute('aria-controls');
    const detalles = document.getElementById(detallesId);
    const expanded = resumen.getAttribute('aria-expanded') === 'true';

    if (detalles) {
      if (expanded) {
        detalles.classList.add('hidden');
        resumen.setAttribute('aria-expanded', 'false');
        resumen.querySelector('.toggle-indicador').textContent = '▼';
      } else {
        detalles.classList.remove('hidden');
        resumen.setAttribute('aria-expanded', 'true');
        resumen.querySelector('.toggle-indicador').textContent = '▲';
      }
    }
  });
});

document.getElementById('formEditarPerfil').addEventListener('submit', async function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData
    });
    const data = await response.json();

    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: data.message,
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      }).then(() => {
        // Opcional: recarga la página para reflejar cambios
        window.location.reload();
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        timer: 3000,
        showConfirmButton: true
      });
    }
  } catch (error) {
    console.error('Error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error inesperado.',
      timer: 3000,
      showConfirmButton: true
    });
  }
});
document.getElementById('formAgregarHijo').addEventListener('submit', async function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData
    });
    const data = await response.json();

    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: data.message,
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      }).then(() => {
        window.location.reload(); // refresca para mostrar el hijo agregado
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        showConfirmButton: true
      });
    }
  } catch (error) {
    console.error('Error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error inesperado.',
      showConfirmButton: true
    });
  }
});
document.getElementById('formEditarHijo').addEventListener('submit', async function(e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData
    });
    const data = await response.json();

    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: data.message,
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      }).then(() => {
        window.location.reload(); // O actualiza la UI según tu flujo
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        showConfirmButton: true
      });
    }
  } catch (error) {
    console.error('Error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error inesperado.',
      showConfirmButton: true
    });
  }
});
document.querySelectorAll('.btn-borrar-hijo').forEach(button => {
  button.addEventListener('click', () => {
    const idHijo = button.getAttribute('data-id');

    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esta acción!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3C1E06',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, borrar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch(`borrar_hijo.php?id=${idHijo}`)
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: '¡Borrado!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
              });
              // Opcional: eliminar el card del hijo del DOM
              const card = button.closest('.hijo-card');
              if (card) card.remove();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                showConfirmButton: true
              });
            }
          })
          .catch(() => {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error al comunicarse con el servidor',
              showConfirmButton: true
            });
          });
      }
    });
  });
});

    </script>
	<!-- custom scrollreveal link -->
	<script src="https://unpkg.com/scrollreveal"></script>
	
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>
</body>
</html>