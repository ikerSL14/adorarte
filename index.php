<?php
    session_start();//
	include 'conexion_be.php';
    
  //$query = "SELECT * FROM productos WHERE activo = 1";
//$result = mysqli_query($conexion, $query);
	//if(!isset($_SESSION['idUsuario'])){
		//echo'
			//<script>
				//alert("por favor debes iniciar sesión");
				//window.location = "../index.php";
				//</script>
		//';
		//session_destroy();
		//die();
	//}

    
//$idUsuario = $_SESSION['idUsuario'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="imgs/adorate.png">
	<title>Adorarte</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
	<link rel="stylesheet" href="adorate.css">
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
	<script src="app.js" async></script>
	<style>
    body {
  padding-right: 0 !important;
  
}
        .trans-exito {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(40, 167, 69, 0); /* Opacidad inicial */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            z-index: 1000;
            transition: background-color 2s ease; /* Transición suave para el cambio de opacidad */
        }
        .trans-exito.show {
            background-color: rgba(40, 167, 69, 0.9);
        }
        /*body.modal-open {
  overflow-y: hidden;
}*/
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

.contenedor__todo{
    width: 100%;
    max-width: 800px;
    margin: auto;
    position: relative;
    margin-top: 8rem;
}

.caja__trasera{
    width: 100%;
    padding: 10px 20px;
    display: flex;
    justify-content: center;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    background-color: rgba(136, 140, 150, 0.5);
    border-radius: 20px;
}

.caja__trasera div{
    margin: 100px 40px;
    color: white;
    transition: all 500ms;
}


.caja__trasera div p,
.caja__trasera button{
    margin-top: 30px;
}

.caja__trasera div h3{
    font-weight: 400;
    font-size: 26px;
}

.caja__trasera div p{
    font-size: 16px;
    font-weight: 300;
}

.caja__trasera button{
    padding: 10px 40px;
    border: 2px solid #fff;
    font-size: 14px;
    background: transparent;
    font-weight: 600;
    cursor: pointer;
    color: white;
    outline: none;
    transition: all 300ms;
}

.caja__trasera button:hover{
    background: #fff;
    color: #3C1E06;
}

/*Formularios*/

.contenedor__login-register{
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 380px;
    position: relative;
    top: -185px;
    left: 10px;

    /*La transicion va despues del codigo JS*/
    transition: left 500ms cubic-bezier(0.175, 0.885, 0.320, 1.275);
}

.contenedor__login-register form{
    width: 100%;
    padding: 80px 20px;
    background: white;
    position: absolute;
    border-radius: 20px;
}

.contenedor__login-register form h2{
    font-size: 30px;
    text-align: center;
    margin-bottom: 20px;
    color: #C7AA2B;
}

.contenedor__login-register form input{
    width: 100%;
    margin-top: 20px;
    padding: 10px;
    border: none;
    background: #F2F2F2;
    font-size: 16px;
    outline: none;
}

.contenedor__login-register form button{
    padding: 10px 40px;
    margin-top: 40px;
    border: none;
    font-size: 14px;
    background: #3C1E06;
    font-weight: 600;
    cursor: pointer;
    color: white;
    outline: none;
}

.formulario__login{
    opacity: 1;
    display: block;
}
.formulario__register{
    display: none;
}



@media screen and (max-width: 850px){

    main{
        margin-top: 50px;
    }

    .caja__trasera{
        max-width: 350px;
        height: 300px;
        flex-direction: column;
        margin: auto;
    }

    .caja__trasera div{
        margin: 0px;
        position: absolute;
    }


    /*Formularios*/

    .contenedor__login-register{
        top: -10px;
        left: -5px;
        margin: auto;
    }

    .contenedor__login-register form{
        position: relative;
    }
}html {
  overflow-y: scroll;
}
.modal-pagar {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0; top: 0;
  width: 100%; height: 100%;
  overflow-y: hidden;
  background-color: rgba(0,0,0,0.4);
}

.modal-pagar-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  border-radius: 20px;
  width: 80%;
  max-width: 600px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  position: relative;
   min-height: 200px; /* o el valor que prefieras */
  max-height: 90vh; /* para que no supere el 90% de la altura de la ventana */
  overflow-y: auto; /* agrega scroll vertical si el contenido excede la altura */
  transition: margin 0.3s ease;
}

.modal-pagar-content.margin-reducido {
  margin: 5.5% auto;
}
.close-pagar {
  color: #aaa;
  position: absolute;
  top: 10px; right: 10px;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close-pagar:hover,
.close-pagar:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* Estilos para las secciones desplegables */
.seccion-desplegable {
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
  overflow: hidden; /* Para ocultar el contenido inicialmente */
}

.titulo-seccion {
  background-color: #f0f0f0;
  padding: 10px;
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: bold;
}

.contenido-seccion {
  padding: 10px;
  display: none; /* Oculto inicialmente */
}

.icono-desplegar {
  transition: transform 0.3s ease;
}

.seccion-desplegable.activa .icono-desplegar {
  transform: rotate(90deg); /* Rotar el icono cuando la sección está activa */
}

.seccion-desplegable.activa .contenido-seccion {
  display: block; /* Mostrar el contenido cuando la sección está activa */
}

/* Estilos para el botón "Pagar" */
#btnPagarModal {
  background-color: #3C1E06;
  color: white;
  padding: 12px 50px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  display: block; /* Para centrarlo */
  margin: 20px auto 0; /* Centrar horizontalmente */
  transition: background-color 0.3s ease;
}

#btnPagarModal:hover {
  background-color: #000;
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
html{
  overflow-y:scroll;
}
.contacto {
  padding: 2rem 0;
  background-color: #f9f9f9; /* Fondo suave para destacar la sección */
  text-align: center;
}

.contacto.servicios2-titulo {
  color: #3C1E06;
  font-weight: 700;
  margin-bottom: 1.5rem;
  font-size: 2rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Estilo para el contenedor del mapa */
#map {
  width: 80vw;
  max-width: 900px; /* Limita el ancho máximo para pantallas grandes */
  height: 400px;
  margin: 0 auto;
  border: 4px solid #C7AA2B; /* Borde dorado que combina con tu paleta */
  border-radius: 12px; /* Bordes redondeados para suavizar */
  box-shadow: 0 4px 15px rgba(60, 30, 6, 0.3); /* Sombra sutil para dar profundidad */
  transition: box-shadow 0.3s ease;
  z-index: 2;
}

/* Efecto hover para darle interactividad */
#map:hover {
  box-shadow: 0 8px 25px rgba(60, 30, 6, 0.5);
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
            echo '<li><a href="perfil.php" style="color:#C7AA2B;">Perfil</a></li>';
          }
        ?>
			<li><a href="#about">Nosotros</a></li>
			<li><a href="#servicios">Servicios</a></li>
      <li><a href="#blog">Blog</a></li>
      <li><a href="https://drive.google.com/file/d/1GpO4hfCu0mNDoaR4s7zPHs4a9xIVX5yH/view" target="_blank" rel="noopener noreferrer">Programas</a></li>
			<li><?php 
            if(!isset($_SESSION['idUsuario'])){
                echo '<a href="#" id="loginLink" style="color:#C7AA2B;">Iniciar sesión</a></li>';
            } else {
                echo '<a href="cerrar_sesion.php" id="cerrarLink" style="color:#C7AA2B;">Cerrar Sesión</a><li>';
            }
            ?>
		</ul>
        <a href="#carrito">
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
<div id="authModal" class="modal hidden">
  <div class="contenedor__todo">
                <div class="caja__trasera">
                    <div class="caja__trasera-login">
                        <h3>¿Ya tienes una cuenta?</h3>
                        <p>Inicia sesión para entrar en la página</p>
                        <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                    </div>
                    <div class="caja__trasera-register">
                        <h3>¿Aún no tienes una cuenta?</h3>
                        <p>Regístrate para que puedas iniciar sesión</p>
                        <button id="btn__registrarse">Regístrarse</button>
                    </div>
                </div>

                <!--Formulario de Login y registro-->
                <div class="contenedor__login-register">
                    <!--Login-->
                    <form id="formLogin" action="loginu.php" method="POST" class="formulario__login">
                        <h2>Iniciar Sesión</h2>
                        <span class="close">&times;</span>
                        <input type="text" placeholder="Correo Electronico" name="Correo_elec">
                        <input type="password" placeholder="Contraseña" name="contrasena">
                        <button>Entrar</button>
                        <a id="fplink" href="forgot/forgot.php">Olvidé mi contraseña</a>
                    </form>

                    <!--Register-->
                    <form id="formRegistro" action="registrou.php" method="POST" class="formulario__register">
                      <h2>Regístrarse</h2>
                      <span class="close">&times;</span>
                      <input type="text" placeholder="Nombre completo" name="Nombre_com" required>
                      <input type="email" placeholder="Correo Electrónico" name="Correo_elec" required>
                      <input type="password" placeholder="Contraseña" name="contrasena" id="contrasena" required>
                      <input type="password" placeholder="Confirmar Contraseña" name="contrasena_confirm" id="contrasena_confirm" required>
                      <button style="margin-bottom:-2rem;">Regístrarse</button>
                  </form>
                </div>
            </div>

        
  </div>
</div>
<script>
  const formRegister = document.querySelector('.formulario__register');
  formRegister.addEventListener('submit', function(e) {
    const pass = document.getElementById('contrasena').value;
    const passConfirm = document.getElementById('contrasena_confirm').value;
    if(pass !== passConfirm) {
      e.preventDefault();
      alert('Las contraseñas no coinciden.');
    }
  });
  const modal = document.getElementById('authModal');
  const loginLink = document.getElementById('loginLink');
  const closeModal = document.querySelector('.close');
  loginLink.addEventListener('click', () => {
    modal.classList.remove('hidden');
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
  });

  closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
  });


  // Opcional: cerrar modal si se hace clic fuera del contenido
  window.addEventListener('click', (e) => {
    if (e.target == modal) {
      modal.classList.add('hidden');
    }
  });
  loginLink.addEventListener('click', () => {
  modal.classList.remove('hidden');
});

closeModal.addEventListener('click', () => {
  modal.classList.add('hidden');
});

window.addEventListener('click', (e) => {
  if (e.target == modal) {
    modal.classList.add('hidden');
  }
});
modal.addEventListener('click', (e) => {
  // Si se hace clic directo en el fondo del modal (no en el contenido)
  if (e.target === modal) {
    modal.classList.add('hidden');
  }
});
let scrollPos = 0;

loginLink.addEventListener('click', () => {
  // Guardar posición de scroll antes de mostrar modal
  scrollPos = window.scrollY || window.pageYOffset;
  
  modal.classList.remove('hidden');

  // Fijar posición del body para evitar scroll jump
  document.body.style.position = 'fixed';
  document.body.style.top = `-${scrollPos}px`;
  document.body.style.left = '0';
  document.body.style.right = '0';
});

const closeAllModals = () => {
  modal.classList.add('hidden');

  // Restaurar posición scroll
  document.body.style.position = '';
  document.body.style.top = '';
  document.body.style.left = '';
  document.body.style.right = '';
  window.scrollTo({ top: scrollPos, behavior: 'instant' });
};

document.querySelectorAll('.close').forEach(closeBtn => {
  closeBtn.addEventListener('click', closeAllModals);
});

window.addEventListener('click', (e) => {
  if (e.target === modal) {
    closeAllModals();
  }
});
</script></p>
<!-- Logo fijo en el fondo -->
  <div class="logo-fondo">
    <img src="imgs/adorate.png" alt="Logo fondo">
  </div>
	<!-- home section -->
		<section class="home" id="home">
			<div class="home-text">
				<h1><span style="font-family: 'Lato', sans-serif; font-style: italic; color: #C7AA2B;">Adorarte</span><br><br><span style="color: white">La música enciende en los niños la chispa del aprendizaje y el amor por la vida.</span></h1>
        <button id="inscribete">Inscríbelos!</button>
			</div>

			<div class="home-img">
				
			</div>
		</section>
<script>
   function mostrarElementos() {
        const elementos = document.querySelectorAll('.fade-in, .from-left, .from-right, .about-img, .about-text');
        elementos.forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 100) {
                el.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', mostrarElementos);
    window.addEventListener('load', mostrarElementos);
</script>
<br><br><br>
  <!-- Sobre nosotros section -->
		<section class="about" id="about">
			<div class="about-img">
				<img src="imgs/about.jpeg" alt="">
			</div>
			<div class="about-text" style="margin-top:3rem;">
				<h2>Sobre nosotros<br></h2>
				<p>La Academia de Música Adorarte surgió en 2020 como un proyecto durante la pandemia de COVID-19, con el objetivo de mitigar los efectos psicológicos <span>provocados por la emergencia sanitaria.</span><br>Tras el éxito de los cursos en línea, se decidió expandir el proyecto a un formato presencial, ofreciendo enseñanza práctica y personalizada en la ejecución de instrumentos musicales.<span style="color: #C7AA2B;">¡Les van a encantar!</span></p>
			</div>
		</section>
    <section class="about2">
       <div class="card card-left">
        <div class="icon-circle">
          <!-- Aquí puedes usar un ícono SVG o una fuente de iconos como FontAwesome o Boxicons -->
          <i class='bx bxs-book-reader'></i> <!-- Ejemplo con Boxicons para "filosofía" -->
        </div>
        <h3>Filosofía educativa</h3>
        <hr>
        <p>
          Nuestra filosofía se basa en fomentar la creatividad y el amor por la música desde la infancia, respetando el ritmo y las capacidades de cada niño para que aprenda disfrutando.
        </p>
      </div>

      <div class="card card-right">
        <div class="icon-circle">
          <i class='bx bxs-brain'></i> <!-- Icono para "metodología" -->
        </div>
        <h3>Metodología de enseñanza</h3>
        <hr>
        <p>
          Utilizamos métodos activos y participativos que combinan teoría y práctica, adaptándonos a las necesidades individuales para lograr un aprendizaje efectivo y divertido.
        </p>
      </div>
    </section>

    <div class="leyenda-centro">
      Conoce nuestro equipo docente y nuestro espacio físico
    </div>

    <section class="about3">
      <div class="image-card" data-img="imgs/m1.jpeg" tabindex="0" role="button" aria-label="Abrir imagen 1">
    <img src="imgs/m1.jpeg" alt="Imagen 1">
  </div>
  <div class="image-card" data-img="imgs/m2.jpeg" tabindex="0" role="button" aria-label="Abrir imagen 2">
    <img src="imgs/m2.jpeg" alt="Imagen 2">
  </div>
  <div class="image-card" data-img="imgs/m3.jpeg" tabindex="0" role="button" aria-label="Abrir imagen 3">
    <img src="imgs/m3.jpeg" alt="Imagen 3">
  </div>
    </section>
    <!-- Modal personalizado -->
<div id="imageModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
  <div class="modal-content">
    <span class="close" aria-label="Cerrar">&times;</span>
    <img src="" alt="" id="modalImage" />
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImage');
  const closeBtn = modal.querySelector('.close');
  const imageCards = document.querySelectorAll('.about3 .image-card');

  imageCards.forEach(card => {
    card.addEventListener('click', () => {
      const imgSrc = card.getAttribute('data-img');
      const altText = card.querySelector('img').alt || '';
      modalImg.src = imgSrc;
      modalImg.alt = altText;
      modal.classList.remove('hidden');

      // Bloquear scroll en body
      document.body.style.overflow = 'hidden';
    });

    // Para accesibilidad: abrir modal con Enter o espacio
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });

  closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
    modalImg.src = '';
    modalImg.alt = '';

    // Restaurar scroll
    document.body.style.overflow = '';
  });

  // Cerrar modal al hacer click fuera de la imagen
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeBtn.click();
    }
  });

  // Cerrar modal con tecla Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      closeBtn.click();
    }
  });
});
</script>

<!-- Sección: Servicios -->
<!--<section class="servicios" id="servicios">
  <h2 class="servicios-titulo">Servicios</h2>
  <div class="servicios-grid">
    <div class="servicio-card">
      <img src="imgs/instrumentos.jpg" alt="Clases de instrumentos">
      <div class="servicio-nombre">Clases de instrumentos</div>
    </div>
    <div class="servicio-card">
      <img src="imgs/talleres.jpg" alt="Talleres de canto y composición">
      <div class="servicio-nombre">Talleres de canto y composición</div>
    </div>
    <div class="servicio-card">
      <img src="imgs/presentaciones.jpg" alt="Presentaciones y recitales">
      <div class="servicio-nombre">Presentaciones y recitales</div>
    </div>
    <div class="servicio-card">
      <img src="imgs/teoria.jpg" alt="Cursos de teoría musical">
      <div class="servicio-nombre">Cursos de teoría musical</div>
    </div>
  </div>
</section>-->
<section class="servicios2" id="servicios">
  <h2 class="servicios2-titulo">Servicios</h2>
  <div class="servicios2-grid">
    <div class="servicio2-card">
      <div class="servicio2-img-container">
        <img src="imgs/instrumentos.jpg" alt="Clases de instrumentos" class="servicio2-img">
        </div>
        <div class="servicio2-icon-box">
          <i class="fas fa-guitar"></i>
        </div>
      <div class="servicio2-content">
        <div class="servicio2-nombre">Clases de instrumentos</div>
        <div class="servicio2-desc">Aprende a tocar tu instrumento favorito con expertos.</div>
        
      </div>
    </div>
    <div class="servicio2-card">
      <div class="servicio2-img-container">
        <img src="imgs/talleres.jpg" alt="Talleres de canto y composición" class="servicio2-img">
        </div>
        <div class="servicio2-icon-box">
          <i class="fas fa-microphone-alt"></i>
        </div>
      <div class="servicio2-content">
        <div class="servicio2-nombre">Talleres de canto y composición</div>
        <div class="servicio2-desc">Desarrolla tus habilidades vocales y creativas.</div>
        
      </div>
    </div>
    <div class="servicio2-card">
      <div class="servicio2-img-container">
        <img src="imgs/presentaciones.jpg" alt="Presentaciones y recitales" class="servicio2-img">
        </div>
        <div class="servicio2-icon-box">
          <i class="fas fa-music"></i>
        </div>
      <div class="servicio2-content">
        <div class="servicio2-nombre">Presentaciones y recitales</div>
        <div class="servicio2-desc">Participa en eventos y muestra tu talento.</div>
        <!--<a href="#" class="servicio2-link">Leer más &rarr;</a>-->
      </div>
    </div>
    <div class="servicio2-card">
      <div class="servicio2-img-container">
        <img src="imgs/teoria.jpg" alt="Cursos de teoría musical" class="servicio2-img">
        </div>
        <div class="servicio2-icon-box">
          <i class="fas fa-book"></i>
        </div>
      
      <div class="servicio2-content">
        <div class="servicio2-nombre">Cursos de teoría musical</div>
        <div class="servicio2-desc">Comprende la base de la música desde cero.</div>
        
      </div>
    </div>
  </div>
</section>



	<!-- menu section -->
<section class="productos" id="productos">
<div class="main-text" style="text-align: center;">
				<h2>Productos</h2>
				<p>Instrumentos, materiales, merchandising... ¡Todo a tu disposición!</p>
	</div>
	<section class="contenedor">
    
        <div class="contenedor-items">
          <div class="item">
            <span class="titulo-item">Guitarra</span>
            <img src="imgs/p1.png" alt="" class="img-item">
            <span class="precio-item">$500</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Audífonos</span>
            <img src="imgs/p2.png" alt="" class="img-item">
            <span class="precio-item">$200</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Playera Unitalla</span>
            <img src="imgs/p3.png" alt="" class="img-item">
            <span class="precio-item">$100</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Flauta</span>
            <img src="imgs/p4.png" alt="" class="img-item">
            <span class="precio-item">$100</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Teclado</span>
            <img src="imgs/p5.png" alt="" class="img-item">
            <span class="precio-item">$500</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Tambor</span>
            <img src="imgs/p6.png" alt="" class="img-item">
            <span class="precio-item">$100</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Maracas</span>
            <img src="imgs/p7.png" alt="" class="img-item">
            <span class="precio-item">$100</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
          <div class="item">
            <span class="titulo-item">Guitarra eléctrica</span>
            <img src="imgs/p8.png" alt="" class="img-item">
            <span class="precio-item">$700</span>
            <button class="boton-item">Agregar al Carrito</button>
          </div>
			</div>
	</section></section>

<section class="#carrito" id="carrito">
	
<div class="carrito" id="carrito">
	<div class="header-carrito">
		<h2>Tu Carrito</h2>
	</div>

	<div class="carrito-items">                
	</div>
	<div class="carrito-total">
		<div class="fila">
			<strong style="color: #3c3c3c">Tu  Total</strong>
			<span class="carrito-precio-total">
				$0
				
				
			</span>
		
			
	</div>
    <?php if (isset($_SESSION['idUsuario'])): ?>
    <button class="btn-pagar" onclick="mostrarTransicionExito()">Enviar <i class="fa-solid fa-bag-shopping"></i></button>
<?php else: ?>
    <button class="btn-login-required" id="btnLoginRequired">Inicia sesión para comprar</button>
<?php endif; ?>
	<script>
    const usuarioLogueado = <?php echo isset($_SESSION['idUsuario']) ? 'true' : 'false'; ?>;
        document.addEventListener('DOMContentLoaded', () => {
            // Restaurar la posición de la página después de iniciar sesión
  const scrollPosition = sessionStorage.getItem('scrollPosition');
  if (scrollPosition) {
    window.scrollTo({
      top: scrollPosition,
      behavior: 'instant' // O 'smooth' para una transición suave
    });
    // Eliminar la posición guardada para que no se restaure en futuras cargas
    sessionStorage.removeItem('scrollPosition');
  }
    const loginBtn = document.getElementById('btnLoginRequired');
    if (loginBtn) {
      loginBtn.addEventListener('click', () => {
        sessionStorage.setItem('scrollPosition', window.scrollY || window.pageYOffset);
        guardarCarrito(); // ← Guardar estado actual
        document.getElementById('authModal').classList.remove('hidden');
        document.querySelector('.formulario__login').classList.remove('hidden');
        document.querySelector('.formulario__register').classList.add('hidden');
        scrollPos = window.scrollY || window.pageYOffset;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollPos}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
      });
    }
    const inscribete = document.getElementById('inscribete');
    if (inscribete) {
      inscribete.addEventListener('click', () => {
        if (usuarioLogueado) {
          // Usuario logueado: redirigir a perfil.php
          window.location.href = 'perfil.php';
        } else {
          // Usuario NO logueado: abrir modal login
          sessionStorage.setItem('scrollPosition', window.scrollY || window.pageYOffset);
          
          document.getElementById('authModal').classList.remove('hidden');
          document.querySelector('.formulario__login').classList.remove('hidden');
          document.querySelector('.formulario__register').classList.add('hidden');
          const scrollPos = window.scrollY || window.pageYOffset;
          document.body.style.position = 'fixed';
          document.body.style.top = `-${scrollPos}px`;
          document.body.style.left = '0';
          document.body.style.right = '0';
        }
      });
    }
  });
        function mostrarTransicionExito() {
            const mensajeExito = document.createElement('div');
                        mensajeExito.classList.add('trans-exito');
                        mensajeExito.innerText = "Correo enviado correctamente";
                        document.body.appendChild(mensajeExito);

                        // Mostrar la animación gradualmente
                        setTimeout(function() {
                            mensajeExito.classList.add('show');
                        }, 10);

                        // Ocultar la animación después de un tiempo
                        setTimeout(function() {
                            mensajeExito.classList.remove('show');
                            setTimeout(function() {
                                document.body.removeChild(mensajeExito);
                            }, 2000);
							window.location.href = 'index.php';
                        }, 2000);
        }

        function ocultarTransicionExito() {
            var transExito = document.getElementById('transExito');
            transExito.classList.remove('show');
            transExito.style.display = 'none'; // Asegurar que esté oculto
        }
		</script>
	</div>
</div>
<div id="modalPagar" class="modal-pagar">
  <div class="modal-pagar-content">
    <span class="close-pagar">&times;</span>
    <h2 style="margin-bottom: 2rem;">Comprar</h2>

    <div class="seccion-desplegable" id="seccionEnvio">
      <div class="titulo-seccion">
        Envío <span class="icono-desplegar">&gt;</span>
      </div>
      <div class="contenido-seccion">
        <label for="codigoPostal">Código postal:</label>
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
      <input type="text" id="codigoPostal" name="codigoPostal" style="flex: 1;">
      <button id="btnGeolocalizacion" type="button" title="Obtener código postal y dirección desde ubicación">
        <i class="fas fa-map-marker-alt"></i>
      </button>
    </div>

        <div style="display: flex; gap: 1rem;">
      <div style="flex: 1; display: flex; flex-direction: column;">
        <label for="direccionEnvio">Dirección de envío:</label>
        <textarea id="direccionEnvio" name="direccionEnvio" rows="3" cols="30" style="resize: vertical;"></textarea>
      </div>

      <div style="flex: 1; display: flex; flex-direction: column;">
        <label for="instruccionesEntrega">Instrucciones de entrega:</label>
        <textarea id="instruccionesEntrega" name="instruccionesEntrega" rows="3" cols="30" style="resize: vertical;"></textarea>
      </div>
    </div>
  </div>
</div>

    <div class="seccion-desplegable" id="seccionPago">
  <div class="titulo-seccion">
    Método de pago <span class="icono-desplegar">&gt;</span>
  </div>
  <div class="contenido-seccion">
    <div style="display: flex; gap: 1rem;">
      <!-- Columna izquierda -->
      <div style="flex: 1; display: flex; flex-direction: column;">
        <label for="numeroTarjeta">Número de tarjeta:</label>
        <input type="text" id="numeroTarjeta" name="numeroTarjeta" placeholder="1234 5678 9012 3456" maxlength="19" style="margin-bottom: 1rem;">

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4">
      </div>

      <!-- Columna derecha -->
      <div style="flex: 1; display: flex; flex-direction: column;">
        <label for="fechaExpiracion">Fecha de expiración (MM/AA):</label>
        <input type="text" id="fechaExpiracion" name="fechaExpiracion" placeholder="MM/AA" maxlength="5" style="margin-bottom: 1rem;">

        <label for="nombreTarjeta">Nombre en la tarjeta:</label>
        <input type="text" id="nombreTarjeta" name="nombreTarjeta" placeholder="Como aparece en la tarjeta">
      </div>
    </div>
  </div>
</div>
<div id="totalCarrito" style="font-weight: bold; font-size: 1.2rem; margin: 1rem 0; text-align: center;">
  Total: $0.00
</div>
    <button id="btnPagarModal">Pagar</button>
  </div>
</div>
</section>
<section class="blog" id="blog" style="background-color:rgba(199, 170, 43, 0.73); padding: 3rem 5%;">
  <h2 class="servicios2-titulo" style="text-align:center; color: #3C1E06; margin-bottom: 2rem;">Blog</h2>
  <div class="blog-container">
    <!-- Artículo 1 -->
    <article class="blog-item">
      <img src="imgs/artad.jpeg" alt="Imagen artículo 1" class="blog-img">
      <div class="blog-meta">
        <span class="blog-categoria">ARTICULO</span>
        <span class="blog-date">01 / 06 / 2025</span>
      </div>
      <h3 class="blog-title">Historia de la academia de música Adorarte</h3>
      <hr class="blog-separator">
      <p class="blog-text">La Academia de Música Adorarte surgió en 2020 como un proyecto durante la pandemia de COVID-19, con el objetivo de mitigar los efectos psicológicos provocados por la emergencia sanitaria.</p>
      <button class="btn-leer-mas" onclick="window.open('https://docs.google.com/document/d/1uyQVy3QAwqp1q3eSfvC8NU4x0JZHww2g/edit?usp=sharing&ouid=113320511184194431816&rtpof=true&sd=true', '_blank')">Leer más</button>
    </article>

    <!-- Artículo 2 -->
    <article class="blog-item">
      <img src="imgs/artad2.png" alt="Imagen artículo 2" class="blog-img">
      <div class="blog-meta">
        <span class="blog-categoria">ARTICULO</span>
        <span class="blog-date">22 / 01 / 2025</span>
      </div>
      <h3 class="blog-title">¿Qué le espera a la industria de la música en 2025?</h3>
      <hr class="blog-separator">
      <p class="blog-text">A medida que la tecnología, la economía global, y las preferencias de los consumidores evolucionan, los músicos, productores, y sellos discográficos se enfrentan a nuevas oportunidades y desafíos.</p>
      <button class="btn-leer-mas" onclick="window.open('https://subterranica.com/2025/01/22/que-le-espera-a-la-industria-de-la-musica-en-2025-los-cambios-y-tendencias-que-marcaran-el-futuro/', '_blank')">Leer más</button>
    </article>

    <!-- Artículo 3 -->
    <article class="blog-item">
      <img src="imgs/artad3.jpg" alt="Imagen artículo 3" class="blog-img">
      <div class="blog-meta">
        <span class="blog-categoria">ARTICULO</span>
        <span class="blog-date">14 / 12 / 2024</span>
      </div>
      <h3 class="blog-title">Instrumentos y Tecnologías que revolucionan la música</h3>
      <hr class="blog-separator">
      <p class="blog-text">Adéntrate en un viaje a través de la historia de la música junto al cine, analizando también como ambos funcionan en conjunto y llegaron a ser lo que son hoy.</p>
      <button class="btn-leer-mas" onclick="window.open('https://musicalfuste.com/tendencias-musicales-2025/?srsltid=AfmBOoovxSHVk_YcPTWRp9PN8nCVpWgTA7_NTb5NKeE0IytIGrRqgotk', '_blank')">Leer más</button>
    </article>
  </div>
</section>
<section class="blog" style="background-color:rgba(199, 170, 43, 0.73); padding: 3rem 5%;">
  <div class="blog-container">
    <!-- Artículo 1 -->
    <article class="blog-item">
      <img src="imgs/art1.png" alt="Imagen artículo 1" class="blog-img">
      <div class="blog-meta">
        <span class="blog-categoria">Noticias</span>
        <span class="blog-date">13 / 10 / 2024</span>
      </div>
      <h3 class="blog-title">Música, IA y Derechos de Autor: 'Heart On My Sleeve'</h3>
      <hr class="blog-separator">
      <p class="blog-text">El caso de «Heart on My Sleeve» plantea preguntas urgentes sobre la interacción entre la inteligencia artificial (IA) y los derechos de propiedad intelectual en la industria musical.</p>
      <button class="btn-leer-mas" onclick="window.open('https://vaventura.com/cultura/musica/musica-inteligencia-artificial-derechos-autor-caso-heart-on-my-sleeve', '_blank')">Leer más</button>
    </article>

    <!-- Artículo 2 -->
    <article class="blog-item">
      <img src="imgs/art2.jpeg" alt="Imagen artículo 2" class="blog-img">
      <div class="blog-meta">
        <span class="blog-categoria">HISTORIA</span>
        <span class="blog-date">07 / 04 / 2024</span>
      </div>
      <h3 class="blog-title">1966, un año clave para la música</h3>
      <hr class="blog-separator">
      <p class="blog-text">La segunda mitad de los sesenta fue un periodo de gran producción musical, una etapa en la que comenzaron a desarrollarse nuevos géneros.</p>
      <button class="btn-leer-mas" onclick="window.open('https://vaventura.com/cultura/musica/1966-ano-clave-musica', '_blank')">Leer más</button>
    </article>

    <!-- Artículo 3 -->
    <article class="blog-item">
      <img src="imgs/art3.png" alt="Imagen artículo 3" class="blog-img">
      <div class="blog-meta">
        <span class="blog-categoria">HISTORIA</span>
        <span class="blog-date">24 / 03 / 2024</span>
      </div>
      <h3 class="blog-title">Breve repaso a la historia de la música y el cine</h3>
      <hr class="blog-separator">
      <p class="blog-text">Adéntrate en un viaje a través de la historia de la música junto al cine, analizando también como ambos funcionan en conjunto y llegaron a ser lo que son hoy.</p>
      <button class="btn-leer-mas" onclick="window.open('https://vaventura.com/cultura/cine/historia-musica-cine', '_blank')">Leer más</button>
    </article>
  </div>
</section>
<section class="contacto">
  <h2 class="servicios2-titulo">Ubicación</h2>
  <div id="map"></div>
</section>

	<!-- contact section -->

		<section class="contact" id="contact">
			<div class="main-contact">
				<div class="contact-content">
					<h4>Servicios</h4>
					<li><a href="#productos">Productos</a></li>
					<li><a href="#blog">Blog</a></li>
				</div>
				<div class="contact-content">
					<h4>Siguenos</h4>
					<li><a href="#">TikTok</a></li>
					<li><a href="#">Facebook</a></li>
				</div>
			</div>
		</section>

		<div class="last-text">
			<p>© Adorarte 2025</p>
		</div>

	<!-- scroll top -->
	<a href="#home" class="scroll-top">
		<i class='bx bx-up-arrow-alt' ></i>
	</a>
<script>
  document.getElementById('formLogin').addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(e.target);

  try {
    const response = await fetch('loginu.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.status === 'success') {
  Swal.fire({
    icon: 'success',
    title: '¡Bienvenido!',
    text: data.message,
    timer: 1000,
    showConfirmButton: false,
    timerProgressBar: true,
    didClose: () => {
      if (data.redirect) {
        window.location.href = data.redirect;
      } else {
        window.location.reload();
      }
    }
  });
}
 else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      });
    }
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Ocurrió un error inesperado.',
      timer: 2000,
      showConfirmButton: false,
      timerProgressBar: true
    });
  }
});

document.getElementById('formRegistro').addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(e.target);

  try {
    const response = await fetch('registrou.php', {
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
        window.location.reload(); // o redirige a otra página
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message,
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true
      });
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Error inesperado, intenta de nuevo.',
      timer: 2000,
      showConfirmButton: false,
      timerProgressBar: true
    });
  }
});

document.addEventListener('DOMContentLoaded', () => {
    // Coordenadas del marcador (lat, lng)
    const lat = 17.5487761;
    const lng = -91.9853788;

    // Crear mapa centrado en las coordenadas
    const map = L.map('map').setView([lat, lng], 16);

    // Agregar capa de mapa (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Agregar marcador en la ubicación
    L.marker([lat, lng]).addTo(map)
      .bindPopup('<b>EL TABERNACULO (IDMECAR)</b><br>Dirección: Carretera, Catazaja - Palenque km 25 col. Pakalna son Palenque, Chiapas..')
      .openPopup();
  });
</script>
	<!-- custom scrollreveal link -->

	<script src="https://unpkg.com/scrollreveal"></script>
	
	<!-- custom js link -->
	<script type="text/javascript" src="app.js"></script>
    <script src="accest/js-login/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>
</body>
</html>

