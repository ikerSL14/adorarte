<?php
session_start();

// --- Validar sesión ---
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header("Location: ../index.php");
  exit();
}

include '../conexion_be.php';

// --- Consultas estadísticas ---
$totalCursos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM cursos"))['total'];
$totalUsuarios = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM usuarios"))['total'];
$totalHijos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM hijos"))['total'];

// --- Datos para la gráfica ---
$graficoData = [];
$res = mysqli_query($conexion, "
  SELECT c.nombre_curso, COUNT(i.id_hijo) AS total_hijos
  FROM cursos c
  LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
  GROUP BY c.id_curso
");
while ($row = mysqli_fetch_assoc($res)) $graficoData[] = $row;

// --- Últimos 5 usuarios ---
$ultimosUsuarios = mysqli_query($conexion, "
  SELECT nombre_com, correo_elec, fecha_registro 
  FROM usuarios WHERE rol = 'usuario'
  ORDER BY fecha_registro DESC 
  LIMIT 5
");

// --- Últimos 5 hijos inscritos ---
$ultimosHijos = mysqli_query($conexion, "
  SELECT h.nombre_completo, c.nombre_curso, i.fecha_inscripcion 
  FROM inscripciones i
  INNER JOIN hijos h ON h.id_hijo = i.id_hijo
  INNER JOIN cursos c ON c.id_curso = i.id_curso
  ORDER BY i.fecha_inscripcion DESC 
  LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrador | Adorarte</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }

    body {
      display: flex;
      height: 100vh;
      background: #f5f6fa;
      overflow: hidden;
    }
    

    /* SIDEBAR */
    .sidebar {
      width: 250px;
      background: linear-gradient(to bottom, #000000, #C7AA2B);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding-top: 1rem;
      position: fixed;
      height: 100vh;
    }

    .sidebar h2 {
      text-align: center;
      font-size: 1.6rem;
      margin-bottom: 1rem;
      letter-spacing: 1px;
    }

    .sidebar hr {
      border: none;
      border-top: 1px solid rgba(255, 255, 255, 0.3);
      margin: 0.5rem 1rem 1rem 1rem;
    }

    .nav-links { display: flex; flex-direction: column; gap: 0.5rem; padding: 0 1rem; }
    .nav-links button {
      background: none; border: none; color: #fff; text-align: left;
      font-size: 1rem; padding: 0.8rem 1rem; border-radius: 8px;
      display: flex; align-items: center; gap: 0.8rem; cursor: pointer;
      transition: background 0.3s; width: 100%;
    }
    .nav-links button:hover { background: rgba(255, 255, 255, 0.15); }
    .nav-links button.active { background: rgba(255, 255, 255, 0.25); font-weight: 600; }

    /* MAIN AREA */
    .main-content {
      margin-left: 250px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow:hidden;
    }

    /* NAVBAR */
    .navbar {
      flex-shrink: 0;
      height: 70px;
      background: #fff;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 0 2rem;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      z-index: 10;
    }

    .navbar a {
      text-decoration: none;
      color: #C7AA2B;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .navbar a:hover { text-decoration: underline; }

    /* CONTENT */
    .content { flex-grow: 1; padding: 2rem; background: #f5f6fa; overflow-y: auto; height: calc(100vh - 70px); /* <-- se ajusta al alto total menos la navbar */}
    .content h1 { font-size: 2rem; color: #333; margin-bottom: 1rem; }

    /* Tarjetas */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 2rem;
    }

    .card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      display: flex;
      align-items: center;
      gap: 15px;
      transition: transform 0.2s;
    }

    .card:hover { transform: translateY(-5px); }

    .card i {
      font-size: 2.2rem;
      color: #C7AA2B;
      background: #00000010;
      padding: 15px;
      border-radius: 50%;
    }

    .card-info h3 {
      font-size: 1rem;
      color: #555;
    }

    .card-info p {
      font-size: 1.8rem;
      font-weight: bold;
      color: #000;
    }

    /* Tablas */
    .tables-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 25px;
      margin-top: 2rem;
    }

    .table-container {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .table-container h3 {
      margin-bottom: 15px;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      overflow: hidden;
    }

    th, td {
      padding: 10px;
      text-align: left;
      font-size: 0.95rem;
      color: #444;
    }

    th {
      background: #f1f1f1;
      color: #000;
    }

    tr:nth-child(even) { background: #fafafa; }

    @media (max-width: 900px) {
      .tables-grid { grid-template-columns: 1fr; }
    }
/* === SECCIÓN CURSOS === */
.cursos-section {
  padding: 1rem 2rem;
  text-align: center;
}

.search-bar {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin: 0rem auto 2rem;
}
#searchUsuarios{
  width: 60%;
  padding: 0.7rem 1rem;
  border: 1px solid #ccc;
  border-radius: 2rem;
  font-size: 1rem;
  outline: none;
  transition: all 0.3s ease;
}
#searchUsuarios:focus {
  border-color: #C7AA2B;
  box-shadow: 0 0 5px #c7aa2b80;
}

/* === BOTÓN CREAR CURSO (Profesional) === */
.btn-crear-curso {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.8rem 1.6rem;
  font-size: 1rem;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #C7AA2B, #b69820);
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(199, 170, 43, 0.3);
}

.btn-crear-curso i {
  font-size: 1.1rem;
}

.btn-crear-curso:hover {
  transform: translateY(-2px);
  background: linear-gradient(135deg, #e0c13a, #C7AA2B);
  box-shadow: 0 6px 15px rgba(199, 170, 43, 0.45);
}

/* === CONTENEDOR DE BÚSQUEDA Y BOTÓN === */
.search-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin: 0rem auto 2rem;
}

#searchInput {
  width: 60%;
  padding: 0.7rem 1rem;
  border: 1px solid #ccc;
  border-radius: 2rem;
  font-size: 1rem;
  outline: none;
  transition: all 0.3s ease;
}

#searchInput:focus {
  border-color: #C7AA2B;
  box-shadow: 0 0 5px #c7aa2b80;
}
.cursos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.curso-card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 1rem 1.2rem;
  text-align: left;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.2s ease;
  cursor: pointer;
  position: relative;
}
.curso-id {
  position: absolute;
  top: 20px;
  right: 20px;
  font-size: 0.9rem;
  color: #777;
  font-weight: 500;
}
.curso-card:hover {
  transform: translateY(-5px);
}

.curso-header {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-weight: 600;
  font-size: 1.1rem;
  color: #222;
}
.curso-header i {
  color: #C7AA2B;
  font-size: 1.2rem;
}

.curso-sub {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
  margin-top: 0.4rem;
  color: #555;
}

.curso-precio {
  margin-top: 0.8rem;
  font-size: 1.1rem;
  font-weight: bold;
  color: #C7AA2B;
}

.curso-horario {
  font-size: 0.9rem;
  color: #777;
  margin-top: 0.4rem;
}

.curso-actions {
  margin-top: 1rem;
  display: flex;
  justify-content: flex-end;
  gap: 0.7rem;
}
.badge-terminado {
    background-color: #1E90FF; /* azul */
    color: white;
    padding: 2px 8px;
    font-size: 0.75rem;
    border-radius: 8px;
    display: inline-block;
    font-weight: 600;
    margin-bottom:0.4rem;
}


.btn-icon {
  background: transparent;
  border: none;
  cursor: pointer;
  color: #666;
  transition: color 0.3s;
}
.btn-icon:hover {
  color: #000;
}
/* === DETALLE DE CURSO === */
.curso-detalle {
  padding: 1.5rem 2rem;
}

.curso-portada {
  position: relative;
  height: 250px;
  border-radius: 15px;
  overflow: hidden;
  margin-bottom: 1.5rem;
  margin-bottom: -70px; /* para que se una con el contenedor blanco */
  z-index: 1;
}

.curso-portada img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.8);
}

.btn-regresar {
  position: absolute;
  top: 15px;
  left: 15px;
  background: rgba(0, 0, 0, 0.6);
  color: #fff;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
}

.btn-regresar:hover {
  background: #C7AA2B;
  color: #000;
}

/* Contenedor principal del curso */
.curso-info {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  margin: 0 auto 2rem;
  max-width: 800px;
  position: relative;
  z-index: 2;
  text-align: center;
}
.badge-curso-terminado-detalle {
  display: inline-block;
  background: #1E90FF; /* rojo elegante */
  color: #fff;
  padding: 6px 14px;
  font-size: 0.85rem;
  border-radius: 20px;
  font-weight: 600;
  margin: 6px 0 10px;
  box-shadow: 0px 2px 4px rgba(0,0,0,0.15);
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
  margin-bottom: 1rem;
}

.curso-info .precio {
  font-size: 1.6rem;
  color: #C7AA2B;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.curso-info .horario {
  color: #555;
  margin-bottom: 0.5rem;
}

.curso-info .inscritos {
  color: #333;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  font-size: 1.05rem;
  margin-top: 0.5rem;
}

/* Lista de alumnos */
.alumnos-lista {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: 2rem;
}

.alumno-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border-radius: 12px;
  padding: 0.8rem 1rem;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s;
}

.alumno-item:hover {
  transform: translateY(-3px);
}

.alumno-foto {
  width: 55px;
  height: 55px;
  border-radius: 50%;
  object-fit: cover;
}

.alumno-info {
  flex: 1;
  margin-left: 1rem;
}
/* --- Extra de alumno: calificación y fecha --- */
.alumno-extra {
  margin-top: 0.3rem;
  padding: 0.5rem 0.7rem;
  width: 40%;
  background: #f7f7f7;
  border-radius: 8px;
  font-size: 0.9rem;
  color: #444;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.06);
}

/* Líneas dentro del extra */
.alumno-extra p {
  margin: 0.2rem 0;
}

/* --- Padre --- */
.alumno-info .padre {
  margin-top: 0.5rem;
  font-size: 0.9rem;
  color: #555;
  display: flex;
  align-items: center;
  gap: 0.3rem;
}

.alumno-info .padre i {
  font-size: 0.85rem;
  color: #888;
}

/* Calificación destacada */
.calificacion strong {
  color: #C7AA2B;
}

/* Finalizado */
.fecha-fin {
  color: #2a7d2a;
  font-weight: 600;
}

.alumno-info .nombre {
  font-weight: 600;
}

.alumno-detalles {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #555;
}

.sin-alumnos {
  color: #777;
  font-style: italic;
  text-align: center;
  margin-top: 1rem;
}

.curso-alumnos i{
    transition: color 0.3s ease-in-out;
}
.curso-alumnos i:hover{
    color: #C7AA2B;
}
/* ===== Modal Crear Curso ===== */
.modal-overlay {
  display: none;
  position: absolute;
  top: 0;
  left: 250px; /* ajusta según el ancho del sidebar */
  width: calc(100% - 250px);
  height: 100%;
  background: rgba(0, 0, 0, 0.55);
  z-index: 50;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 2rem 2.5rem;
  border-radius: 20px;
  width: 650px; /* más ancho */
  max-width: 90%;
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
  animation: showModal 0.25s ease-out;
}

@keyframes showModal {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

.modal-content h2 {
  margin-bottom: 1.5rem;
  text-align: center;
  color: #222;
  font-weight: 700;
}

/* --- Form layout --- */
.form-row {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-group {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.form-group.small {
  flex: 0.3;
}

.form-group label {
  font-weight: 600;
  color: #444;
  margin-bottom: 5px;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="file"],
.form-group input[type="email"],
.form-group select{
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 15px;
  transition: border 0.2s;
}

.form-group input:focus {
  outline: none;
  border-color: #d8a806;
}

/* --- Imagen + preview --- */
.imagen-group {
  margin-top: 1.5rem;
}

.imagen-input-preview {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.preview-box {
  width: 130px;
  height: 100px;
  background: #f8f8f8;
  border-radius: 10px;
  border: 2px dashed #d8a806;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
}

.preview-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* --- Botón --- */
.btn-modal-crear {
  background: linear-gradient(135deg, #f4c542, #d8a806);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px 24px;
  cursor: pointer;
  font-weight: 600;
  font-size: 16px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-modal-crear:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 12px rgba(212, 165, 16, 0.4);
}

.form-actions {
  display: flex;
  justify-content: center;
  margin-top: 1.5rem;
}
.buscador-profesor-group {
    margin-top: 15px;
}

.buscador-profesor {
    position: relative;
}

.buscador-profesor input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #bfbfbf;
    border-radius: 6px;
    font-size: 14px;
}

.lista-profesores {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 180px;
    background: #fff;
    border: 1px solid #d3d3d3;
    border-radius: 6px;
    overflow-y: auto;
    z-index: 99;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
}

.lista-profesores div {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.lista-profesores div:hover {
    background: #f2f2f2;
}

.oculto {
    display: none;
}

/* === SECCIÓN USUARIOS === */
.usuarios-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.usuario-card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 1rem 1.2rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
  transition: transform 0.2s ease;
  cursor: pointer;
}

.usuario-card:hover {
  transform: translateY(-5px);
}

.usuario-top {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.usuario-foto {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #C7AA2B;
  box-shadow: 0 0 8px rgba(199,170,43,0.3);
}

.usuario-info h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #222;
}

.usuario-info .correo {
  font-size: 0.9rem;
  color: #666;
}

.usuario-extra {
  margin-top: 0.8rem;
  font-size: 0.9rem;
  color: #555;
  line-height: 1.4;
}

.usuario-extra i {
  color: #C7AA2B;
  margin-right: 0.4rem;
}

.usuario-card .btn-icon.borrar-usuario {
  position: absolute;
  bottom: 12px;
  right: 15px;
  color: #888;
}

.usuario-card .btn-icon.borrar-usuario:hover {
  color: #000;
}
/* === Detalle del usuario === */
.usuario-detalle {
  padding: 2rem;
}

.btn-volver {
  background: none;
  border: none;
  color: #C7AA2B;
  font-size: 1rem;
  cursor: pointer;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-weight: 600;
  transition: color 0.3s;
}
.btn-volver:hover {
  color: #a68f22;
}

.usuario-header {
  display: flex;
  align-items: center;
  gap: 2rem;
  background: #fff;
  padding: 1.5rem;
  border-radius: 16px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.08);
}

.usuario-foto-grande {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #C7AA2B;
}

.usuario-info-detalle h2 {
  margin: 0;
  font-size: 1.5rem;
  color: #333;
}

.usuario-info-detalle p {
  margin: 0.3rem 0;
  color: #555;
}

/* === Lista de hijos === */
.hijos-lista {
  margin-top: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.hijo-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border-radius: 12px;
  padding: 0.8rem 1rem;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s;
}
.hijo-item:hover {
  transform: translateY(-3px);
}

.hijo-foto {
  width: 55px;
  height: 55px;
  border-radius: 50%;
  object-fit: cover;
}

.hijo-info {
  flex: 1;
  margin-left: 1rem;
}
.hijo-info .nombre {
  font-weight: 600;
  color: #333;
}

.hijo-detalles {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #555;
}

.hijo-detalles .curso {
  color: #C7AA2B;
  font-weight: 600;
}

.hijo-detalles .separador {
  width: 1px;
  height: 18px;
  background: #ccc;
}

.sin-alumnos {
  color: #777;
  font-style: italic;
  text-align: center;
  margin-top: 1rem;
}

/* ========================== */

/* === SECCIÓN PROFESORES === */

/* ========================== */
.prof-container {
  padding: 1rem 2rem;
}

.prof-btn-crear {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.8rem 1.6rem;
  font-size: 1rem;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #C7AA2B, #b69820);
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(199, 170, 43, 0.3);
}

.prof-btn-crear:hover {
  transform: translateY(-2px);
  background: linear-gradient(135deg, #e0c13a, #C7AA2B);
}
.prof-search-container {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin: 0rem auto 2rem;
}

#profSearchInput {
  width: 60%;
  padding: 0.7rem 1rem;
  border: 1px solid #ccc;
  border-radius: 2rem;
  font-size: 1rem;
  outline: none;
  transition: all 0.3s ease;
}

#profSearchInput:focus {
  border-color: #C7AA2B;
  box-shadow: 0 0 5px #c7aa2b80;
}
.prof-card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  padding: 1rem 1.2rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
}


.prof-top {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.prof-foto {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #C7AA2B;
  box-shadow: 0 0 8px rgba(199,170,43,0.3);
}

.prof-info h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #222;
}

.prof-info .email {
  font-size: 0.9rem;
  color: #666;
}

.prof-extra {
  margin-top: 0.8rem;
  font-size: 0.9rem;
  color: #555;
}
.badge-terminadoProfes {
  background-color: #007bff;  /* azul */
  color: white;
  padding: 3px 7px;
  border-radius: 6px;
  font-size: 0.7rem;
  font-weight: bold;
  margin-left: 6px;
  display: inline-block;
}

/* CONTENEDOR DE BOTONES */
.prof-card .prof-actions {
  position: absolute;
  bottom: 10px;
  right: 15px;
  display: flex;
  gap: 12px;
}

/* BOTÓN RESET */
.prof-card .prof-reset {
  color: #5a5a5a;
  cursor: pointer;
  transition: 0.25s ease;
  font-size: 18px;
}

.prof-card .prof-reset:hover {
  color: #be9304ff; /* azul */
  transform: scale(1.05);
}

/* BOTÓN EDITAR */
.prof-card .prof-edit {
  color: #5a5a5a;
  cursor: pointer;
  transition: 0.25s ease;
  font-size: 18px;
}

.prof-card .prof-edit:hover {
  color: #1d74ff; /* azul */
  transform: scale(1.05);
}

/* BOTÓN ELIMINAR */
.prof-card .prof-delete {
  color: #888;
  cursor: pointer;
  transition: 0.25s ease;
  font-size: 18px;
}

.prof-card .prof-delete:hover {
  color: #ff2d2d; /* rojo */
  transform: scale(1.05);
}

/* === ACTIVAR MODAL === */
.modal-overlay.active {
  display: flex !important;
}
#profList {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
  gap: 1.5rem;
  padding: 1rem 0;
}
.buscador-curso {
    position: relative;
    margin-top: 15px;
}

.buscador-curso input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #bfbfbf;
    border-radius: 6px;
    font-size: 14px;
}

.lista-cursos {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 180px;
    background: #fff;
    border: 1px solid #d3d3d3;
    border-radius: 6px;
    overflow-y: auto;
    z-index: 99;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.2);
}

.lista-cursos div {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.lista-cursos div:hover {
    background: #f2f2f2;
}
.curso-asignado {
  padding: 8px 12px;
  border-radius: 8px;
  margin-top: 5px;
}

.curso-nombre {
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 5px;
  color: #333;
}

.curso-detalles {
  display: flex;
  gap: 10px;
}

.badge-grupo, .badge-id {
  background: #a3831aff;
  color: #fff8dfff;
  padding: 3px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: bold;
}

  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>Adorarte</h2>
    <hr>
    <div class="nav-links">
      <button class="tab-btn active" data-tab="inicio"><i class="fa-solid fa-house"></i> Inicio</button>
      <button class="tab-btn" data-tab="cursos"><i class="fa-solid fa-guitar"></i> Cursos</button>
      <button class="tab-btn" data-tab="usuarios"><i class="fa-solid fa-users"></i> Usuarios</button>
      <!--<button class="tab-btn" data-tab="compras"><i class="fa-solid fa-cart-shopping"></i> Compras</button>-->
      <button class="tab-btn" data-tab="profesores"><i class="fa-solid fa-chalkboard-teacher"></i> Profesores</button>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main-content">
    <div class="navbar">
      <a href="../cerrar_sesion.php"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
    <div class="content" id="content-area"></div>
  </div>

  <script>
    const buttons = document.querySelectorAll('.tab-btn');
    const contentArea = document.getElementById('content-area');

    const sections = {
      inicio: `
        <div class="stats-grid">
          <div class="card">
            <i class="fa-solid fa-guitar"></i>
            <div class="card-info">
              <h3>Total de Cursos</h3>
              <p><?= $totalCursos ?></p>
            </div>
          </div>

          <div class="card">
            <i class="fa-solid fa-user-group"></i>
            <div class="card-info">
              <h3>Total de Usuarios</h3>
              <p><?= $totalUsuarios ?></p>
            </div>
          </div>

          <div class="card">
            <i class="fa-solid fa-child"></i>
            <div class="card-info">
              <h3>Total de Alumnos</h3>
              <p><?= $totalHijos ?></p>
            </div>
          </div>
        </div>

        <canvas id="graficoCursos" height="100"></canvas>

        <div class="tables-grid">
          <div class="table-container">
            <h3><i class="fa-solid fa-user-plus"></i> Últimos 5 Padres Registrados</h3>
            <table>
              <tr><th>Nombre</th><th>Correo</th><th>Fecha</th></tr>
              <?php while($u = mysqli_fetch_assoc($ultimosUsuarios)): ?>
              <tr>
                <td><?= htmlspecialchars($u['nombre_com']) ?></td>
                <td><?= htmlspecialchars($u['correo_elec']) ?></td>
                <td><?= date('d/m/Y', strtotime($u['fecha_registro'])) ?></td>
              </tr>
              <?php endwhile; ?>
            </table>
          </div>

          <div class="table-container">
            <h3><i class="fa-solid fa-music"></i> Últimos 5 Alumnos Inscritos</h3>
            <table>
              <tr><th>Alumno</th><th>Curso</th><th>Fecha</th></tr>
              <?php while($h = mysqli_fetch_assoc($ultimosHijos)): ?>
              <tr>
                <td><?= htmlspecialchars($h['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($h['nombre_curso']) ?></td>
                <td><?= date('d/m/Y', strtotime($h['fecha_inscripcion'])) ?></td>
              </tr>
              <?php endwhile; ?>
            </table>
          </div>
        </div>
      `,
      cursos: `
  <section class="cursos-section">
    <!-- Barra de búsqueda -->
    <div class="search-container">
        <button id="crearCurso" class="btn-crear-curso">
            <i class="fa-solid fa-plus"></i> Crear curso
        </button>
        <input type="text" id="searchInput" placeholder="Buscar curso o grupo..." />
    </div>

    <!-- Contenedor de cursos -->
    <div id="cursosContainer" class="cursos-grid">
      <p class="loading-msg">Cargando cursos...</p>
    </div>
  </section>

<div id="modalCrearCurso" class="modal-overlay">
  <div class="modal-content">
    <h2>Crear curso</h2>

    <form id="formCrearCurso" enctype="multipart/form-data">
      <div class="form-row">
        <div class="form-group">
          <label>Nombre del curso</label>
          <input type="text" name="nombre_curso" required>
        </div>

        <div class="form-group">
          <label>Precio</label>
          <input type="number" step="0.01" name="precio" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Día y hora</label>
          <input type="text" name="dia_hora" placeholder="Ej: Lunes 4:00 PM" required>
        </div>

        <div class="form-group">
          <label>Grupo</label>
          <input type="text" name="grupo" maxlength="1" required>
        </div>
      </div>

      <div class="form-group buscador-profesor-group">
        <label>Profesor</label>
        <div class="buscador-profesor">
          <input type="text" id="inputBuscarProfesor" placeholder="Buscar profesor..." autocomplete="off">
          <input type="hidden" name="id_profesor" id="idProfesorSeleccionado">

          <div id="listaProfesores" class="lista-profesores oculto"></div>
        </div>
      </div>

      <div class="form-group imagen-group">
        <label>Imagen del curso</label>
        <div class="imagen-input-preview">
          <input type="file" name="foto" id="fotoInput" accept="image/*" required>
          <div class="preview-box">
            <img id="previewImg" src="" alt="Vista previa" />
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-modal-crear">Crear curso</button>
      </div>
    </form>
  </div>
</div>
<!-- ===== Modal Editar Curso ===== -->
<div id="modalEditarCurso" class="modal-overlay">
  <div class="modal-content">
    <h2>Editar curso</h2>

    <form id="formEditarCurso" enctype="multipart/form-data">
      <input type="hidden" name="id_curso" id="edit_id_curso">

      <div class="form-row">
        <div class="form-group">
          <label>Nombre del curso</label>
          <input type="text" name="nombre_curso" id="edit_nombre_curso" required>
        </div>

        <div class="form-group">
          <label>Precio</label>
          <input type="number" step="0.01" name="precio" id="edit_precio" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Día y hora</label>
          <input type="text" name="dia_hora" id="edit_dia_hora" placeholder="Ej: Lunes 4:00 PM" required>
        </div>

        <div class="form-group">
          <label>Grupo</label>
          <input type="text" name="grupo" id="edit_grupo" maxlength="1" required>
        </div>
      </div>

      <div class="form-group buscador-profesor">
        <label>Profesor asignado</label>
        <input type="text" id="edit_inputBuscarProfesor" placeholder="Buscar profesor..." autocomplete="off">

        <input type="hidden" id="edit_idProfesorSeleccionado" name="id_profesor">

        <div id="edit_listaProfesores" class="lista-profesores oculto"></div>
      </div>

      <div class="form-group imagen-group">
        <label>Imagen del curso</label>
        <div class="imagen-input-preview">
          <input type="file" name="foto" id="edit_fotoInput" accept="image/*">
          <div class="preview-box">
            <img id="edit_previewImg" src="" alt="Vista previa" />
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-modal-crear">Guardar</button>
      </div>
    </form>
  </div>
</div>


`,

      usuarios: `
      <section class="usuarios-section">
        <!-- 🔍 Barra de búsqueda centrada -->
        <div class="search-bar">
        
          <input type="text" id="searchUsuarios" placeholder="Buscar padre o correo...">
        
        </div>

        <!-- 🧑‍🤝‍🧑 Contenedor de cards -->
        <div id="usuariosContainer" class="usuarios-grid">
          <p class="loading-msg">Cargando usuarios...</p>
        </div>
      </section>
    `,

      // compras: "<h1>Compras</h1><p>Historial de compras y registros de pago.</p>",
      profesores: `
  <section class="prof-container">

    <!-- Barra de búsqueda y botón -->
    <div class="prof-search-container">
      <button id="btnCrearProfesor" class="prof-btn-crear">
        <i class="fa-solid fa-plus"></i> Crear cuenta
      </button>

      <input type="text" id="profSearchInput" placeholder="Buscar profesor o curso...">
    </div>

    <!-- Contenedor donde se imprimen los profesores -->
    <div id="profList" class="prof-list"></div>

    <!-- ===========================
          MODAL CREAR PROFESOR
    =========================== -->
    <div id="modalCrearProfesor" class="modal-overlay">
      <div class="modal-content">

        <h2>Registrar Profesor</h2>

        <form id="formCrearProfesor" enctype="multipart/form-data">

          <div class="form-group" style="margin-bottom:1rem;">
            <label>Nombre completo</label>
            <input type="text" name="nombre" required>
          </div>

          <div class="form-group" style="margin-bottom:1rem;">
            <label>Correo electrónico</label>
            <input type="email" name="email" required>
          </div>

          <!-- BUSCADOR DE CURSOS (OPCIONAL) -->
          <div class="form-group buscador-curso">
            <label>Asignar curso (opcional)</label>

            <!-- Input visible -->
            <input type="text" id="crear_inputBuscarCurso" placeholder="Buscar curso..." autocomplete="off">

            <!-- Input oculto donde se almacena el id del curso -->
            <input type="hidden" id="crear_idCursoSeleccionado" name="id_curso">

            <!-- Lista de resultados -->
            <div id="crear_listaCursos" class="lista-cursos oculto"></div>
          </div>


          <!-- Imagen -->
          <div class="imagen-group">
            <label style="font-weight:bold;">Foto (opcional)</label>
            <div class="imagen-input-preview">
              <input type="file" name="foto" id="profFotoInput" accept="image/*">

              <div class="preview-box" style="margin-left: auto; margin-right:2rem;">
                <img id="profFotoPreview" src="" alt="">
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn-modal-crear">Guardar profesor</button>
          </div>

        </form>

      </div>
    </div>

<!-- ===========================
      MODAL EDITAR PROFESOR
=========================== -->
<div id="modalEditarProfesor" class="modal-overlay">
  <div class="modal-content">

    <h2>Editar Profesor</h2>

    <form id="formEditarProfesor" enctype="multipart/form-data">

      <input type="hidden" name="id_profesor" id="edit_id_profesor">

      <div class="form-group" style="margin-bottom:1rem;">
        <label>Nombre completo</label>
        <input type="text" name="nombre" id="edit_nombre" required>
      </div>

      <div class="form-group" style="margin-bottom:1rem;">
        <label>Correo electrónico</label>
        <input type="email" name="email" id="edit_email" required>
      </div>

      <!-- BUSCADOR DE CURSOS PARA EDITAR -->
      <div class="form-group buscador-curso">
        <label>Asignar curso</label>

        <!-- Input visible -->
        <input type="text" id="edit_inputBuscarCurso" placeholder="Buscar curso..." autocomplete="off">

        <!-- Input oculto donde se almacena el id del curso -->
        <input type="hidden" id="edit_idCursoSeleccionado" name="id_curso">

        <!-- Lista de resultados -->
        <div id="edit_listaCursos" class="lista-cursos oculto"></div>
      </div>



      <!-- Imagen -->
      <div class="imagen-group">
        <label style="font-weight:bold;">Foto (opcional)</label>
        <div class="imagen-input-preview">
          <input type="file" name="foto" id="edit_foto" accept="image/*">

          <div class="preview-box" style="margin-left: auto; margin-right: 2rem;">
            <img id="edit_foto_preview" src="" alt="">
          </div>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-modal-crear">Guardar cambios</button>
      </div>

    </form>

  </div>
</div>
  </section>
`
    };

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const tab = btn.dataset.tab;
        contentArea.innerHTML = sections[tab];
        if (tab === "inicio") inicializarGrafico();
        if (tab === "cursos") cargarCursos();
        if (tab === "usuarios") cargarUsuarios();
        if (tab === "profesores") cargarProfesores();

      });
    });

    contentArea.innerHTML = sections["inicio"];
    inicializarGrafico();

    function inicializarGrafico() {
      const canvas = document.getElementById('graficoCursos');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?= json_encode(array_column($graficoData, 'nombre_curso')) ?>,
          datasets: [{
            label: 'Alumnos por curso',
            data: <?= json_encode(array_column($graficoData, 'total_hijos')) ?>,
            backgroundColor: '#C7AA2B',
            borderColor: '#000',
            borderWidth: 1
          }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
      });
    }

    // ===============================
    // 🎸 Cargar cursos dinámicamente
    // ===============================
    function cargarCursos() {
        fetch('get_cursos.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('cursosContainer');
            container.innerHTML = '';
            if (data.length === 0) {
            container.innerHTML = '<p>No hay cursos registrados.</p>';
            return;
            }
            data.forEach(curso => {

    // Normalizamos el estado
    const estado = (curso.estado || "").trim().toLowerCase();
    // BADGE
    const badge = (estado === "terminado")
        ? `<span class="badge-terminado">Terminado</span>`
        : "";

    container.innerHTML += `
        <div class="curso-card" data-id="${curso.id_curso}" 
            data-nombre="${curso.nombre_curso}" 
            data-precio="${curso.precio}" 
            data-dia-hora="${curso.dia_hora}" 
            data-grupo="${curso.grupo}" 
            data-foto="${curso.foto || ''}"
            data-id-profesor="${curso.id_profesor || ''}"
            data-nombre-profesor="${curso.nombre_profesor || ''}">
            
            <div class="curso-header">
                <i class="fa-solid fa-music"></i>
                <span class="curso-nombre">${curso.nombre_curso}</span>
                ${badge}
            </div>

            <span class="curso-id">#${curso.id_curso}</span>

            <div class="curso-sub">
                <span>Grupo ${curso.grupo}</span>
                <span class="curso-alumnos"><i class="fa-solid fa-users"></i> ${curso.total_alumnos}</span>
            </div>

            <div class="curso-precio">$${curso.precio}</div>
            <div class="curso-horario" style="font-weight: 600;">Profesor: ${curso.nombre_profesor || 'No asignado'}</div>
            <div class="curso-horario">${curso.dia_hora}</div>

            <div class="curso-actions">
                <button class="btn-icon ver" title="Ver"><i class="fa-solid fa-eye"></i></button>
                <button class="btn-icon editar" title="Editar"><i class="fa-solid fa-pen"></i></button>
                <button class="btn-icon borrar" title="Borrar"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    `;
});

       // Asignar eventos de clic a cada tarjeta o botón "ver"
document.querySelectorAll('.curso-card, .btn-icon.ver').forEach(el => {
  el.addEventListener('click', e => {
    if (e.target.closest('.btn-icon.editar') || e.target.closest('.btn-icon.borrar')) return;

    e.stopPropagation();
    const card = e.currentTarget.closest('.curso-card');
    const cursoId = card.dataset.id; // 🔥 usar el ID directamente
    mostrarDetalleCurso(cursoId);
  });
});

            // Filtro de búsqueda
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', () => {
            const search = searchInput.value.toLowerCase();
            document.querySelectorAll('.curso-card').forEach(card => {
                const nombre = card.querySelector('.curso-nombre').textContent.toLowerCase();
                const grupo = card.querySelector('.curso-sub span').textContent.toLowerCase();
                card.style.display = (nombre.includes(search) || grupo.includes(search)) ? 'block' : 'none';
            });
            });
            // Abrir modal
            document.getElementById('crearCurso').addEventListener('click', () => {
            document.getElementById('modalCrearCurso').style.display = 'flex';
            // Inicializar buscador solo cuando exista el modal
            inicializarBuscadorProfesores();
            });

            // Cerrar modal al hacer click fuera del contenido
            document.getElementById('modalCrearCurso').addEventListener('click', e => {
            if (e.target.id === 'modalCrearCurso') {
                e.currentTarget.style.display = 'none';
            }
            });

            // Vista previa de imagen
            document.getElementById('fotoInput').addEventListener('change', e => {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImg');
            if (file) {
                const reader = new FileReader();
                reader.onload = () => preview.src = reader.result;
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
            });
            document.getElementById('formCrearCurso').addEventListener('submit', e => {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch('crear_curso.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                Swal.fire({
                    icon: 'success',
                    title: 'Curso creado',
                    text: 'El curso se ha registrado correctamente',
                    confirmButtonColor: '#f4c542'
                }).then(() => {
                    document.getElementById('modalCrearCurso').style.display = 'none';
                    cargarCursos(); // recargar cursos
                });
                } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msg || 'No se pudo crear el curso'
                });
                }
            })
            .catch(err => {
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un problema con el servidor'
                });
                console.error(err);
            });
            });

        })
        .catch(err => {
            document.getElementById('cursosContainer').innerHTML = '<p>Error al cargar los cursos.</p>';
            console.error(err);
        });
        
// ===============================
// 🖼️ Vista previa al cambiar imagen
// ===============================
document.getElementById("edit_fotoInput").addEventListener("change", e => {
  const file = e.target.files[0];
  const preview = document.getElementById("edit_previewImg");
  if (file) preview.src = URL.createObjectURL(file);
});

// ===============================
// 💾 Enviar formulario de edición
// ===============================
document.getElementById("formEditarCurso").addEventListener("submit", e => {
  e.preventDefault();

  const formData = new FormData(e.target);

  fetch("update_curso.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(resp => {
     if (resp.success) {
      Swal.fire({
        icon: "success",
        title: "Curso actualizado",
        text: "Los cambios se guardaron correctamente.",
        confirmButtonColor: "#f4c542"
      }).then(() => {
        document.getElementById("modalEditarCurso").style.display = "none";
        cargarCursos(); // 🔁 recargar lista de cursos
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Error al actualizar",
        text: resp.message || "Ocurrió un problema al guardar los cambios.",
        confirmButtonColor: "#f4c542"
      });
    }
  })
  .catch(err => {
    console.error("Error al actualizar curso:", err);
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text: "No se pudo conectar con el servidor.",
      confirmButtonColor: "#f4c542"
    });
  });
});

// ===============================
// ❌ Cerrar modal al hacer click fuera
// ===============================
document.getElementById("modalEditarCurso").addEventListener("click", e => {
  if (e.target.classList.contains("modal-overlay")) {
    e.currentTarget.style.display = "none";
  }
});

    }
// ===============================
// ✏️ Abrir modal de edición
// ===============================
document.addEventListener("click", e => {
  if (e.target.closest(".btn-icon.editar")) {
    const card = e.target.closest(".curso-card");
    if (!card) return;

    // Obtener datos desde atributos data-*
    const curso = {
      id_curso: card.dataset.id,
      nombre_curso: card.dataset.nombre,
      precio: card.dataset.precio,
      dia_hora: card.dataset.diaHora,
      grupo: card.dataset.grupo,
      foto: card.dataset.foto,
      id_profesor: card.dataset.idProfesor,
      nombre_profesor: card.dataset.nombreProfesor
    };

    // Rellenar los inputs
    document.getElementById("edit_id_curso").value = curso.id_curso;
    document.getElementById("edit_nombre_curso").value = curso.nombre_curso;
    document.getElementById("edit_precio").value = curso.precio;
    document.getElementById("edit_dia_hora").value = curso.dia_hora;
    document.getElementById("edit_grupo").value = curso.grupo;

    // Profesor
    document.getElementById("edit_inputBuscarProfesor").value = curso.nombre_profesor || "";
    document.getElementById("edit_idProfesorSeleccionado").value = curso.id_profesor || "";

    // Imagen preview
    const previewImg = document.getElementById("edit_previewImg");
    if (curso.foto && curso.foto.trim() !== "") {
      previewImg.src = `../imgs/cursos/${curso.id_curso}/${curso.foto}`;
    } else {
      previewImg.src = "https://via.placeholder.com/120x90?text=Sin+imagen";
    }

    // Mostrar modal
    document.getElementById("modalEditarCurso").style.display = "flex";
    // Inicializar buscador
    inicializarBuscadorProfesoresEditar();
  }
});
// ===============================
// 🗑️ Borrar curso
// ===============================
document.addEventListener("click", e => {
  if (e.target.closest(".btn-icon.borrar")) {

    const card = e.target.closest(".curso-card");
    const cursoId = card.dataset.id;
    const nombre = card.dataset.nombre;

    console.log("ID A BORRAR:", cursoId); // 👈 DEBUG

    Swal.fire({
      title: "¿Eliminar curso?",
      html: `Se eliminará <b>${nombre}</b>, sus inscripciones y se marcarán sus alumnos como <b>no_inscrito</b>.`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, borrar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6"
    }).then(result => {
      if (result.isConfirmed) {

        fetch("delete_curso.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded" // 👈 NECESARIO
          },
          body: "id_curso=" + encodeURIComponent(cursoId)
        })
        .then(res => res.json())
        .then(resp => {
          console.log("RESPUESTA BACKEND:", resp); // 👈 DEBUG

          if (resp.success) {
            Swal.fire("Eliminado", resp.message, "success");
            cargarCursos();
          } else {
            Swal.fire("Error", resp.message, "error");
          }
        })
        .catch(err => {
          console.error("FETCH ERROR:", err);
          Swal.fire("Error", "No se pudo conectar con el servidor", "error");
        });

      }
    });
  }
});



    // ===============================
    // 🎵 Mostrar detalle de un curso
    // ===============================
    // 🎵 Mostrar detalle de un curso
// ===============================
function mostrarDetalleCurso(cursoId) {
  // Obtener los datos del curso y sus alumnos desde el servidor
  fetch(`get_detalle_curso.php?id=${cursoId}`)
    .then(res => res.json())
    .then(data => {
      const curso = data.curso || {};
      const alumnos = Array.isArray(data.alumnos) ? data.alumnos : [];

      // Determinar la imagen del curso
      const portada = curso.foto && curso.foto.trim() !== ""
        ? `../imgs/cursos/${curso.id_curso}/${curso.foto}`
        : "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=870";

      const alumnosHtml = alumnos.length > 0
  ? alumnos.map(a => {

      const nombreAlumno = a.nombre || "Alumno";

      const foto = a.foto && a.foto.trim() !== ""
        ? `../imgs/perfil/${a.id_usuario}/hijos/${a.id_hijo}/${a.foto}`
        : "https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_1280.png";

      const edad = a.edad ?? "N/D";
      const iconGenero = a.genero === "F" ? "venus" : "mars";
      const nombrePadre = a.nombre_padre || "Padre no registrado";

      const extra =
        (a.calificacion || a.fecha_terminacion)
          ? `
            <div class="alumno-extra">
              ${a.calificacion ? `<p class="calificacion">Calificación: <strong>${escapeHtml(a.calificacion)}</strong></p>` : ""}
              ${a.fecha_terminacion ? `<p class="fecha-fin">Finalizado: ${escapeHtml(a.fecha_terminacion)}</p>` : ""}
            </div>
          `
          : "";

      return `
        <div class="alumno-item">
          <img src="${foto}" class="alumno-foto" alt="Foto de ${escapeHtml(nombreAlumno)}">

          <div class="alumno-info">
            <p class="nombre">${escapeHtml(nombreAlumno)}</p>

            <p class="padre">
              <i class="fa-solid fa-user"></i>
              Tutor: 
              <strong>${escapeHtml(nombrePadre)}</strong>
            </p>

            ${extra}
          </div>

          <div class="alumno-detalles">
            <span>${escapeHtml(String(edad))} años</span>
            <i class="fa-solid fa-${iconGenero}"></i>
          </div>
        </div>
      `;
    }).join('')
  : '<p class="sin-alumnos">No hay alumnos inscritos en este curso.</p>';


        // Badge de estado
        const badgeEstado = curso.estado === "terminado"
          ? `<span class="badge-curso-terminado-detalle">Terminado</span><br>`
          : "";
      // Insertar todo en el DOM
      contentArea.innerHTML = `
        <section class="curso-detalle">
          <!-- Portada -->
          <div class="curso-portada">
            <img src="${portada}" alt="Portada del curso">
            <button class="btn-regresar" id="btnRegresar">
              <i class="fa-solid fa-arrow-left"></i> Regresar
            </button>
          </div>

          <!-- Información principal -->
          <div class="curso-info">
            <h2>${escapeHtml(curso.nombre_curso || 'Curso')}</h2>
            ${badgeEstado}
            <p class="grupo">Grupo ${escapeHtml(curso.grupo || '')}</p>
            <p class="precio">$${escapeHtml(String(curso.precio || '0'))}</p>
            <p class="horario">${escapeHtml(curso.dia_hora || '')}</p>
            <p class="inscritos" style="color: #b89d26ff;"><i class="fa-solid fa-chalkboard-user"></i>Profesor: <span style="color: #333;"> ${escapeHtml(curso.nombre_profesor || 'No asignado')}</span></p>
            
            <p class="inscritos"><i class="fa-solid fa-users"></i> Alumnos inscritos: ${alumnos.length}</p>
          </div>

          <!-- Lista de alumnos -->
          <div class="alumnos-lista">
            ${alumnosHtml}
          </div>
        </section>
      `;

      // Asignar evento al botón regresar (aseguramos que exista)
      const btnRegresar = document.getElementById('btnRegresar');
      if (btnRegresar) {
        btnRegresar.addEventListener('click', () => {
          contentArea.innerHTML = sections["cursos"];
          cargarCursos();
        });
      }
    })
    .catch(err => {
      console.error('Error al obtener el detalle del curso:', err);
      contentArea.innerHTML = `<p>Error al cargar el curso.</p>`;
    });
}

// Pequeña función para escapar HTML (evita problemas si algún nombre trae caracteres)
function escapeHtml(str) {
  if (typeof str !== 'string') return str;
  return str.replace(/[&<>"'`=\/]/g, function(s) {
    return ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;',
      '`': '&#x60;',
      '=': '&#x3D;'
    })[s];
  });
}

// ===============================
// 👨‍👩‍👧 Cargar Usuarios (Padres)
// ===============================
function cargarUsuarios() {
  // Aseguramos que la pestaña usuarios esté colocada en el contentArea
  if (!document.getElementById('usuariosContainer')) {
    contentArea.innerHTML = sections["usuarios"];
  }

  const container = document.getElementById('usuariosContainer');
  container.innerHTML = '<p class="loading-msg">Cargando usuarios...</p>';

  fetch('get_usuarios.php')
    .then(res => res.json())
    .then(data => {
      container.innerHTML = '';

      if (!data || data.length === 0) {
        container.innerHTML = '<p>No hay usuarios registrados.</p>';
        return;
      }

      data.forEach(u => {
        const fotoPerfil = u.foto_perfil && u.foto_perfil.trim() !== ''
          ? `../imgs/perfil/${u.id_usuario}/${u.foto_perfil}`
          : 'https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_1280.png';

        container.innerHTML += `
          <div class="usuario-card" data-id="${u.id_usuario}" data-nombre="${u.nombre_com}" data-correo="${u.correo_elec}">
            <div class="usuario-top">
              <img src="${fotoPerfil}" alt="${u.nombre_com}" class="usuario-foto">
              <div class="usuario-info">
                <h3>${u.nombre_com}</h3>
                <p class="correo">${u.correo_elec}</p>
              </div>
            </div>
            <div class="usuario-extra">
              <p><i class="fa-solid fa-child"></i> ${u.total_hijos} hijos</p>
              <p><i class="fa-regular fa-calendar"></i> ${u.fecha_registro}</p>
            </div>
            <button class="btn-icon borrar-usuario" title="Borrar">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        `;
      });

      // Filtrar mientras se escribe
      const searchInput = document.getElementById('searchUsuarios');
      if (searchInput) {
        searchInput.addEventListener('input', () => {
          const search = searchInput.value.toLowerCase();
          document.querySelectorAll('.usuario-card').forEach(card => {
            const nombre = card.dataset.nombre.toLowerCase();
            const correo = card.dataset.correo.toLowerCase();
            card.style.display = (nombre.includes(search) || correo.includes(search)) ? 'flex' : 'none';
          });
        });
      }

      // Eliminar usuario
      document.querySelectorAll('.borrar-usuario').forEach(btn => {
        btn.addEventListener('click', e => {
          e.stopPropagation();
          const card = e.target.closest('.usuario-card');
          const id = card.dataset.id;

          Swal.fire({
            title: '¿Eliminar usuario?',
            text: 'Se eliminará también el acceso y los hijos asociados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c72b2bff',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Sí, borrar'
          }).then(result => {
            if (result.isConfirmed) {
              fetch(`delete_usuario.php?id=${id}`)
                .then(res => res.json())
                .then(resp => {
                  if (resp.success) {
                    Swal.fire('Eliminado', 'El usuario fue borrado correctamente.', 'success');
                    cargarUsuarios();
                  } else {
                    Swal.fire('Error', resp.message || 'No se pudo eliminar.', 'error');
                  }
                })
                .catch(err => {
                  console.error(err);
                  Swal.fire('Error', 'No se pudo conectar al servidor.', 'error');
                });
            }
          });
        });
      });

      // Abrir detalle de usuario (una vez que ya están las cards)
      document.querySelectorAll('.usuario-card').forEach(card => {
        card.addEventListener('click', e => {
          const id = card.dataset.id;
          cargarDetalleUsuario(id);
        });
      });

    })
    .catch(err => {
      console.error(err);
      // comprobar que el elemento siga existiendo antes de escribir en él
      const cont = document.getElementById('usuariosContainer');
      if (cont) cont.innerHTML = '<p>Error al cargar los usuarios.</p>';
    });
}
function cargarDetalleUsuario(id) {
  fetch(`get_usuario_detalle.php?id=${id}`)
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        Swal.fire('Error', data.error, 'error');
        return;
      }

      const u = data.usuario;
      const hijos = data.hijos;

      const fotoPerfil = u.foto_perfil && u.foto_perfil.trim() !== ''
        ? `../imgs/perfil/${u.id_usuario}/${u.foto_perfil}`
        : 'https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_1280.png';

      const contenido = `
      <section class="usuario-detalle">
        <button class="btn-volver" id="btnVolverUsuarios">
          <i class="fa-solid fa-arrow-left"></i> Volver
        </button>

        <div class="usuario-header">
          <img src="${fotoPerfil}" class="usuario-foto-grande" alt="${u.nombre_com}">
          <div class="usuario-info-detalle">
            <h2>${u.nombre_com}</h2>
            <p><i class="fa-regular fa-envelope"></i> ${u.correo_elec}</p>
            <p><i class="fa-regular fa-calendar"></i> Registrado el ${u.fecha_registro}</p>
            <p><strong>Hijos inscritos:</strong> ${u.total_hijos}</p>
          </div>
        </div>

        <div class="hijos-lista">
          ${hijos.length > 0 ? hijos.map(h => `
            <div class="hijo-item">
              <img 
                src="${h.foto ? `../imgs/perfil/${u.id_usuario}/hijos/${h.id_hijo}/${h.foto}` : 'https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_1280.png'}" 
                class="hijo-foto" 
                alt="${h.nombre}"
              >
              <div class="hijo-info">
                <p class="nombre">${h.nombre}</p>
              </div>
              <div class="hijo-detalles">
                <span class="curso">
                  ${h.nombre_curso ? `${h.nombre_curso}` : 'Sin curso'}
                  ${h.grupo ? ` - Grupo ${h.grupo}` : ''}
                  ${h.id_curso ? ` - Curso #${h.id_curso}` : ''}
                </span>
                <div class="separador"></div>
                <span>${h.edad} años</span>
                <i class="fa-solid fa-${h.genero === 'F' ? 'venus' : 'mars'}"></i>
              </div>

            </div>
          `).join('') : '<p class="sin-alumnos">No hay hijos registrados.</p>'}
        </div>
      </section>
      `;

      // Insertar la vista en el contentArea (no reemplazamos sidebar/nav)
      contentArea.innerHTML = contenido;

      // Asignar evento al botón Volver — restauramos la pestaña y recargamos
      const btnVolver = document.getElementById('btnVolverUsuarios');
      if (btnVolver) {
        btnVolver.addEventListener('click', () => {
          contentArea.innerHTML = sections["usuarios"];
          cargarUsuarios();
        });
      }

    })
    .catch(err => {
      console.error(err);
      Swal.fire('Error', 'No se pudo cargar el usuario.', 'error');
    });
}
/* ============================
      PROFESORES – COMPLETO
============================ */

function cargarProfesores() {
  const profList = document.getElementById("profList");

  fetch("get_profesores.php")
    .then(res => res.json())
    .then(data => {
      if (!Array.isArray(data) || data.length === 0) {
        profList.innerHTML = "<p>No hay profesores registrados.</p>";
        return;
      }

      profList.innerHTML = data.map(p => {
        const foto = p.foto && p.foto.trim() !== ""
          ? `../imgs/profesores/${p.id_profesor}/${p.foto}`
          : "https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_1280.png";

        return `
        <div class="prof-card" 
          data-id-profesor="${p.id_profesor}" 
          data-nombre="${p.nombre}"
          data-email="${p.email}"
          data-genero="${p.genero}"
          data-foto="${p.foto || ''}"
          data-id-curso="${p.id_curso || ''}"
          data-nombre-curso="${p.nombre_curso || ''}"
          data-grupo="${p.grupo || ''}"
          data-estado="${p.estado || 'activo'}">

          <div class="prof-top">
            <img src="${foto}" class="prof-foto">
            <div class="prof-info">
              <h3>${p.nombre}</h3>
              <p class="email">${p.email}</p>
            </div>
          </div>

          

          <div class="prof-extra">
          ${
            p.id_curso
              ? `
                <div class="curso-asignado">
                  <div class="curso-nombre">
                    <i class="fa-solid fa-book"></i>
                    ${p.nombre_curso}
                  </div>
                  <div class="curso-detalles">
                    <span class="badge-grupo">Grupo: ${p.grupo}</span>
                    <span class="badge-id">ID: ${p.id_curso}</span>
                    ${p.estado === "terminado" ? `<span class="badge-terminadoProfes">Terminado</span>` : ""}
                  </div>
                </div>
              `
              : `<p><i class="fa-solid fa-book"></i> Curso: Sin curso asignado</p>`
          }
        </div>


          <!-- BOTONES -->
          <div class="prof-actions">
            <i class="fa-solid fa-arrow-rotate-left prof-reset" title="Reiniciar contraseña"></i>
            <i class="fa-solid fa-pen prof-edit" title="Editar"></i>
            <i class="fa-solid fa-trash prof-delete" title="Eliminar"></i>
          </div>
        </div>
      `;
      }).join("");
    });
}


/* ================================
      BUSCADOR EN TIEMPO REAL
================================ */

document.addEventListener("input", e => {
  if (e.target.id === "profSearchInput") {
    const filtro = e.target.value.toLowerCase();

    document.querySelectorAll(".prof-card").forEach(card => {
      const nombre = card.dataset.nombre.toLowerCase();
      const curso = (card.dataset.curso || "").toLowerCase();

      card.style.display =
        nombre.includes(filtro) || curso.includes(filtro)
          ? "flex"
          : "none";
    });
  }
});


/* ================================
      ABRIR MODAL CREAR PROFESOR
================================ */

document.addEventListener("click", e => {
  if (e.target.id === "btnCrearProfesor") {
    document.getElementById("modalCrearProfesor").classList.add("active");
    // Reiniciar buscador
    document.getElementById("crear_inputBuscarCurso").value = "";
    document.getElementById("crear_idCursoSeleccionado").value = "";

    inicializarBuscadorCursosCrear();
  }
});


/* ====================================
      CERRAR MODAL (CLICK FUERA)
==================================== */

document.addEventListener("click", e => {
  if (e.target.id === "modalCrearProfesor") {
    e.target.classList.remove("active");
  }
});


/* ====================================
      PREVIEW DE FOTO
==================================== */

document.addEventListener("change", e => {
  if (e.target.id === "profFotoInput") {
    const file = e.target.files[0];
    if (file) {
      document.getElementById("profFotoPreview").src =
        URL.createObjectURL(file);
    }
  }
});

/* ====================================
      GUARDAR PROFESOR (SUBMIT)
==================================== */

document.addEventListener("submit", e => {
  if (e.target.id === "formCrearProfesor") {
    e.preventDefault();

    // --- VALIDACIÓN DE CURSO ---
    const cursoInput = document.getElementById("crear_inputBuscarCurso"); // el input
    const idCursoHidden = document.getElementById("id_curso_hidden"); // tu hidden real

    // Si escriben texto pero NO han seleccionado una opción válida
    if (cursoInput.value !== "" && (idCursoHidden.value === "" || idCursoHidden.value === "0")) {
      Swal.fire({
        icon: "warning",
        title: "Curso no válido",
        text: "Debes seleccionar un curso del listado. No se permite texto manual.",
      });
      return;
    }

    const formData = new FormData(e.target);

    fetch("crear_profesor.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {

      if (data.status === "error") {
        Swal.fire({ icon: "error", title: "Error", text: data.message });
        return;
      }

      // Si viene warning (creado pero fallo correo) mostrar aviso y contraseña
      if (data.status === "warning") {
        Swal.fire({
          icon: "warning",
          title: "Profesor creado (correo NO enviado)",
          html: `La cuenta se creó correctamente.<br><b>Contraseña:</b> <code>${data.password}</code><br>Envía manualmente o intenta reenviar.`,
          confirmButtonText: "OK"
        });
      } else {
        // Éxito y correo enviado
        Swal.fire({
          icon: "success",
          title: "Profesor creado",
          html: `Cuenta creada y correo enviado.<br><b>Contraseña:</b> <code>${data.password}</code>`,
          confirmButtonText: "OK"
        });
      }

      // cerrar modal
      document.getElementById("modalCrearProfesor").classList.remove("active");
      e.target.reset();
      document.getElementById("profFotoPreview").src = "";
      cargarProfesores();
    })
    .catch(err => {
      Swal.fire({ icon: "error", title: "Error inesperado", text: err });
    });
  }
});


/* ====================================
      EDITAR PROFESOR (SUBMIT EDIT)
==================================== */

document.addEventListener("click", async e => {
  if (e.target.classList.contains("prof-edit")) {

    const card = e.target.closest(".prof-card");

    // Datos del profesor
    const id = card.dataset.idProfesor;
    const nombre = card.dataset.nombre;
    const email = card.dataset.email;
    const genero = card.dataset.genero;
    const foto = card.dataset.foto;
    const cursoActual = card.dataset.nombreCurso;
    const id_curso_actual = card.dataset.idCurso;
    const grupoCursoActual = card.dataset.grupo;


    document.getElementById("edit_inputBuscarCurso").value = "";
document.getElementById("edit_idCursoSeleccionado").value = "";
document.getElementById("edit_listaCursos").innerHTML = "";

    inicializarBuscadorCursosEditar(
    cursoActual, 
    id_curso_actual, 
    grupoCursoActual,
    id   // ← AQUÍ MANDAMOS EL ID DEL PROFESOR
    );

    // Setear valores en el modal
    document.getElementById("edit_id_profesor").value = id;
    document.getElementById("edit_nombre").value = nombre;
    document.getElementById("edit_email").value = email;

    // Foto previa desde la BD
    if (foto) {
      document.getElementById("edit_foto_preview").src =
        `../imgs/profesores/${id}/${foto}`;
    } else {
      document.getElementById("edit_foto_preview").src = "";
    }

    
    // Abrir modal
    document.getElementById("modalEditarProfesor").classList.add("active");
  }
});

document.addEventListener("click", e => {
  if (e.target.id === "modalEditarProfesor") {
    e.target.classList.remove("active");
  }
});
document.addEventListener("change", e => {
  if (e.target.id === "edit_foto") {
    const file = e.target.files[0];
    if (file) {
      document.getElementById("edit_foto_preview").src =
        URL.createObjectURL(file);
    }
  }
});
document.addEventListener("submit", e => {
  if (e.target.id === "formEditarProfesor") {
    e.preventDefault();

    const cursoInput = document.getElementById("edit_inputBuscarCurso");
    const idCursoHidden = document.getElementById("edit_idCursoSeleccionado");

    if (cursoInput.value !== "" && (idCursoHidden.value === "" || idCursoHidden.value === "0")) {
      Swal.fire({
        icon: "warning",
        title: "Curso no válido",
        text: "Debes seleccionar un curso del listado.",
      });
      return;
    }

    const formData = new FormData(e.target);

    fetch("editar_profesor.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {

      if (data.status !== "success") {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.message
        });
        return;
      }

      Swal.fire({
        icon: "success",
        title: "Actualizado",
        text: "El profesor se actualizó correctamente.",
        timer: 1500,
        showConfirmButton: false
      });

      // Cerrar modal
      document.getElementById("modalEditarProfesor").classList.remove("active");

      // Recargar lista
      cargarProfesores();
    })
    .catch(err => {
      Swal.fire({
        icon: "error",
        title: "Error inesperado",
        text: err
      });
    });
  }
});

document.addEventListener("click", async (e) => {
  if (e.target.classList.contains("prof-reset")) {
    const card = e.target.closest(".prof-card");
    const id = card.dataset.idProfesor || card.getAttribute('data-id-profesor');

    const ok = await Swal.fire({
      title: "Reiniciar contraseña",
      text: "Se generará una nueva contraseña y se enviará por correo. ¿Continuar?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, reiniciar"
    });

    if (!ok.isConfirmed) return;

    try {
      const formData = new FormData();
      formData.append('id_profesor', id);

      const resp = await fetch('reset_profesor_password.php', {
        method: 'POST',
        body: formData
      });
      const j = await resp.json();

      if (j.status === 'error') {
        Swal.fire({ icon: 'error', title: 'Error', text: j.message });
        return;
      }

      // Mostrar la contraseña generada (ya la devuelve en j.password)
      if (j.status === 'warning') {
        Swal.fire({
          icon: 'warning',
          title: 'Contraseña generada (correo NO enviado)',
          html: `Nueva contraseña: <code>${j.password}</code>`,
          confirmButtonText: 'OK'
        });
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Contraseña restablecida',
          html: `Nueva contraseña: <code>${j.password}</code><br>Correo enviado.`,
          confirmButtonText: 'OK'
        });
      }

    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar al servidor.' });
    }
  }
});


/* ====================================
      ELIMINAR PROFESOR
==================================== */

document.addEventListener("click", e => {
  if (e.target.classList.contains("prof-delete")) {
    
    const card = e.target.closest(".prof-card");
    const id = card.dataset.idProfesor;

    // 🔥 Confirmación con SweetAlert2
    Swal.fire({
      title: "¿Eliminar profesor?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar"
    }).then(result => {

      if (!result.isConfirmed) return;

      const formData = new FormData();
      formData.append("id_profesor", id);

      fetch("delete_profesor.php", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {

        if (data.status !== "success") {
          Swal.fire({
            icon: "error",
            title: "Error al eliminar",
            text: data.message
          });
          return;
        }

        Swal.fire({
          icon: "success",
          title: "Eliminado",
          text: "Profesor eliminado correctamente.",
          timer: 1500,
          showConfirmButton: false
        });

        cargarProfesores();
      })
      .catch(err => {
        Swal.fire({
          icon: "error",
          title: "Error inesperado",
          text: err
        });
      });

    });
  }
});
function inicializarBuscadorProfesores() {
    const inputBuscar = document.getElementById("inputBuscarProfesor");
    const listaProfes = document.getElementById("listaProfesores");
    const inputIdProfesor = document.getElementById("idProfesorSeleccionado");

    if (!inputBuscar || !listaProfes) {
        console.warn("Buscador de profesores no encontrado.");
        return;
    }

    let profesoresData = [];

    // Obtener profesores
    fetch("get_profesorescursos.php")
        .then(res => res.json())
        .then(data => {
            profesoresData = data;
            mostrarListaProfesores(data);
        });

    function mostrarListaProfesores(lista) {
        if (!listaProfes) return;
        listaProfes.innerHTML = "";

        lista.forEach(prof => {
            const div = document.createElement("div");
            div.textContent = prof.nombre;
            div.dataset.id = prof.id_profesor;

            div.addEventListener("click", () => {
                inputBuscar.value = prof.nombre;
                inputIdProfesor.value = prof.id_profesor;
                listaProfes.classList.add("oculto");
            });

            listaProfes.appendChild(div);
        });
    }

    inputBuscar.addEventListener("input", () => {
        const texto = inputBuscar.value.toLowerCase();

        const filtrados = profesoresData.filter(p =>
            p.nombre.toLowerCase().includes(texto)
        );

        mostrarListaProfesores(filtrados);
        listaProfes.classList.remove("oculto");
    });

    inputBuscar.addEventListener("focus", () => {
        listaProfes.classList.remove("oculto");
    });

    document.addEventListener("click", (e) => {
        if (!e.target.closest(".buscador-profesor")) {
            listaProfes.classList.add("oculto");
        }
    });
}

function inicializarBuscadorProfesoresEditar() {
    const inputBuscar = document.getElementById("edit_inputBuscarProfesor");
    const listaProfes = document.getElementById("edit_listaProfesores");
    const inputIdProfesor = document.getElementById("edit_idProfesorSeleccionado");

    if (!inputBuscar || !listaProfes) {
        console.warn("Buscador de profesores (editar curso) no encontrado.");
        return;
    }

    let profesoresData = [];

    // Obtener profesores
    fetch("get_profesorescursos.php")
        .then(res => res.json())
        .then(data => {
            profesoresData = data;
            mostrarListaProfesores(data);
        });

    function mostrarListaProfesores(lista) {
        listaProfes.innerHTML = "";

        lista.forEach(prof => {
            const div = document.createElement("div");
            div.textContent = prof.nombre;
            div.dataset.id = prof.id_profesor;

            div.addEventListener("click", () => {
                inputBuscar.value = prof.nombre;
                inputIdProfesor.value = prof.id_profesor;
                listaProfes.classList.add("oculto");
            });

            listaProfes.appendChild(div);
        });
    }

    // Filtrar al escribir
    inputBuscar.addEventListener("input", () => {
        const texto = inputBuscar.value.toLowerCase();

        const filtrados = profesoresData.filter(p =>
            p.nombre.toLowerCase().includes(texto)
        );

        mostrarListaProfesores(filtrados);
        listaProfes.classList.remove("oculto");
    });

    // Mostrar siempre al hacer clic
    inputBuscar.addEventListener("focus", () => {
        listaProfes.classList.remove("oculto");
    });

    // Cerrar si se hace clic fuera
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".buscador-profesor")) {
            listaProfes.classList.add("oculto");
        }
    });
}
function inicializarBuscadorCursosCrear() {
    const inputBuscar = document.getElementById("crear_inputBuscarCurso");
    const listaCursos = document.getElementById("crear_listaCursos");
    const inputIdCurso = document.getElementById("crear_idCursoSeleccionado");

    if (!inputBuscar || !listaCursos) return;

    let cursosData = [];

    // Cargar cursos
    fetch("get_cursosprofesores.php")
        .then(res => res.json())
        .then(data => {
            cursosData = data;
            mostrarListaCursos(data);
        });

    function mostrarListaCursos(lista) {
        listaCursos.innerHTML = "";

        lista.forEach(curso => {
            const div = document.createElement("div");
            div.textContent = `${curso.nombre_curso} "${curso.grupo}" ID: ${curso.id_curso}`;
            div.dataset.id = curso.id_curso;

            div.addEventListener("click", () => {
                inputBuscar.value = curso.nombre_curso;
                inputIdCurso.value = curso.id_curso;
                listaCursos.classList.add("oculto");
            });

            listaCursos.appendChild(div);
        });
    }

    inputBuscar.addEventListener("input", () => {
        const texto = inputBuscar.value.toLowerCase();

        const filtrados = cursosData.filter(c =>
            c.nombre_curso.toLowerCase().includes(texto)
        );

        mostrarListaCursos(filtrados);
        listaCursos.classList.remove("oculto");
    });

    inputBuscar.addEventListener("focus", () => {
        listaCursos.classList.remove("oculto");
    });

    document.addEventListener("click", e => {
        if (!e.target.closest(".buscador-curso")) {
            listaCursos.classList.add("oculto");
        }
    });
}
function inicializarBuscadorCursosEditar(cursoActualNombre = "", cursoActualId = "", cursoActualGrupo = "", idProfesor = 0) {
    const inputBuscar = document.getElementById("edit_inputBuscarCurso");
    const listaCursos = document.getElementById("edit_listaCursos");
    const inputIdCurso = document.getElementById("edit_idCursoSeleccionado");

    if (!inputBuscar || !listaCursos) return;

    let cursosData = [];

    // Cargar cursos
    fetch(`get_cursos_para_editar.php?id_profesor=${idProfesor}`)
        .then(res => res.json())
        .then(data => {
            cursosData = data;

            // Mostrar lista completa
            mostrarListaCursos(data);

            // Prellenar curso actual si viene desde el card
            if (cursoActualId) {
                inputBuscar.value = `${cursoActualNombre} "${cursoActualGrupo}" ID: ${cursoActualId}`;
                inputIdCurso.value = cursoActualId;
            }
        });

    function mostrarListaCursos(lista) {
        listaCursos.innerHTML = "";

        lista.forEach(curso => {
            const div = document.createElement("div");
            div.textContent = `${curso.nombre_curso} "${curso.grupo}" ID: ${curso.id_curso}`;
            div.dataset.id = curso.id_curso;

            div.addEventListener("click", () => {
                inputBuscar.value = `${curso.nombre_curso} "${curso.grupo}" ID: ${curso.id_curso}`;
                inputIdCurso.value = curso.id_curso;
                listaCursos.classList.add("oculto");
            });

            listaCursos.appendChild(div);
        });
    }

    // Filtrar
    inputBuscar.addEventListener("input", () => {
        const texto = inputBuscar.value.toLowerCase();

        const filtrados = cursosData.filter(c =>
            c.nombre_curso.toLowerCase().includes(texto)
        );

        mostrarListaCursos(filtrados);
        listaCursos.classList.remove("oculto");
    });

    // Abrir lista al hacer focus
    inputBuscar.addEventListener("focus", () => {
        listaCursos.classList.remove("oculto");
    });

    // Cerrar lista al hacer clic fuera
    document.addEventListener("click", e => {
        if (!e.target.closest(".buscador-curso")) {
            listaCursos.classList.add("oculto");
        }
    });
}



  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
