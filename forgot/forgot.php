<?php
    session_start();//
	include '../conexion_be.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../imgs/adorate.png">
	<title>Adorarte</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
	<link rel="stylesheet" href="../adorate.css">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Great+Vibes&family=Dancing+Script&family=Lobster&family=Alex+Brush&family=Satisfy&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Great+Vibes&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  
	<script src="https://kit.fontawesome.com/46e8cbfbf8.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
	<script src="../app.js" async></script>
	<style>
    body {
  padding-right: 0 !important;
  margin: 0;
    background: url('https://images.unsplash.com/photo-1555117391-2f4b3598a4fa?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
    background-size: cover; /* Hace que se adapte a todo el body */
    background-color: #000; /* Por si la imagen tarda en cargar */
}
        
        .modal {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex; justify-content: center; align-items: center;
  z-index: 999;
  overflow-y: auto; /* Permite scroll si el contenido es más alto que el modal */
  padding: 20px; /* Da un pequeño espacio arriba/abajo en pantallas pequeñas */
}
.hidden {
  display: none;
}

.modal .close {
  position: absolute;
  top: 10px; right: 15px;
  cursor: pointer;
  font-size: 1.5rem;
}

.link {
  color: #007BFF;
  cursor: pointer;
  text-decoration: underline;
}

html {
  overflow-y: scroll;
}
.contenido-principal {
  position: relative;
  z-index: 3;
}
html{
  overflow-y:scroll;
}

.forgot-container {
    width: 90%;
    max-width: 420px;
    margin: 6rem auto;
    padding: 2rem;
    margin-top:11rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.forgot-container h2 {
    text-align: center;
    margin-bottom: 1.5rem;
}

.forgot-container input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-bottom: 1rem;
    margin-top:1rem;
}

.forgot-container button {
    width: 100%;
    padding: 12px;
    background: #C7AA2B;
    border: none;
    color: white;
    font-weight: bold;
    cursor: pointer;
    border-radius: 8px;
    transition: .3s;
}

.forgot-container button:hover {
    background: #8d791c;
}

.success, .error {
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
}

.success { background:#d4edda; color:#155724; }
.error { background:#f8d7da; color:#721c24; }
    </style>
</head>
<body>
<div class="contenido-principal">
	<!-- header section -->
	<header>
		
		<a href="#" class="logo"><img src="../imgs/adorate.png" width="55" alt="logo"></a>
    <div id="menu-icon" class="bx bx-menu"></div> <!-- Icono hamburguesa -->
		<ul class="navbar">
        <?php 
            if (isset($_SESSION['nombre'])) {
                // Obtener el nombre completo
                $nombreCompleto = $_SESSION['nombre'];

                // Separar por espacios y tomar la primera palabra
                $partesNombre = explode(' ', trim($nombreCompleto));
                $primerNombre = ucfirst($partesNombre[0]); // Primera palabra con primera letra mayúscula

                echo '<li><a href="#" style="color:#C7AA2B;" id="userLink">
                        <i class="bx bxs-user" style="margin-right:0.7rem;"></i>' . htmlspecialchars($primerNombre) . '
                      </a></li>';
            }
            ?>
        <?php
          if(isset($_SESSION['idUsuario'])){
            echo '<li><a href="../perfil.php" style="color:#C7AA2B;">Perfil</a></li>';
          }
        ?>
			<li><a href="../index.php#about">Nosotros</a></li>
			<li><a href="../index.php#servicios">Servicios</a></li>
            <li><a href="../index.php#blog">Blog</a></li>
            <li><a href="https://drive.google.com/file/d/1GpO4hfCu0mNDoaR4s7zPHs4a9xIVX5yH/view" target="_blank" rel="noopener noreferrer">Programas</a></li>
			<li><a href="../cerrar_sesion.php" id="cerrarLink" style="color:#C7AA2B;">Regresar a inicio</a><li>
		</ul>
        <a href="#carrito" style="visibility:hidden">
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
  
<div class="forgot-container">

    <h2>Recuperar contraseña</h2>

    <?php 
    if (isset($_SESSION['forgot_message'])) {
        echo $_SESSION['forgot_message'];
        unset($_SESSION['forgot_message']);
    }
    ?>

    <form action="forgot_process.php" method="POST">
        <label>Ingresa tu correo registrado</label>
        <input type="email" name="email" required placeholder="tuCorreo@mail.com">

        <button type="submit">Enviar enlace de recuperación</button>
    </form>
</div>
	<!-- custom js link -->
	<script type="text/javascript" src="app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>
</body>
</html>

