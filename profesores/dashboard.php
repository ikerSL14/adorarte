<?php
session_start();
include '../conexion_be.php';

if (!isset($_SESSION['id_profesor'])) {
    header("Location: ../index.php");
    exit();
}

$id_profesor = $_SESSION['id_profesor'];
$consulta = mysqli_query($conexion, "SELECT * FROM profesores WHERE id_profesor = $id_profesor");
$profesor = mysqli_fetch_assoc($consulta);

$foto_profesor = $profesor['foto'] ?? 'default.png';
$foto_path = "../imgs/profesores/$id_profesor/$foto_profesor";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Profesor - Adorarte</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        margin: 0;
        font-family: 'Roboto', sans-serif;
        background-color: #f0f4f1;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 220px;
        height: 100%;
        background-color: #ffffff;
        border-right: 1px solid #D5DBE7;
        padding-top: 0;
    }

    .sidebar-header {
        background-color: #b58900; /* mostaza oscuro */
        color: white;
        text-align: center;
        padding: 20px 10px;
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .sidebar-header i {
        font-size: 1.5rem;
    }

    .sidebar ul {
        list-style: none;
        padding: 10px 10px;
        margin: 0;
    }

    /* Sidebar ul li */
    .sidebar ul li {
        padding: 12px 20px; /* un poco más de espacio arriba/abajo */
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500; /* más suave que bold */
        color: #333;
        border-radius: 8px; /* para rectángulo suave */
        transition: background-color 0.3s, color 0.3s; /* hover suave */
        margin-bottom: 0.8rem;
    }

    /* Icono del sidebar */
    .sidebar ul li i {
        width: 20px;
        text-align: center;
        transition: color 0.3s;
    }

    /* Hover */
    .sidebar ul li:hover {
        background-color: #f7e8b085; /* mostaza bajito */
        color: #b58900; /* texto mostaza */
    }

    .sidebar ul li:hover i {
        color: #b58900; /* ícono también mostaza */
    }

    /* Activa */
    .sidebar ul li.active {
        background-color: #b58900; /* mostaza oscuro */
        color: #ffffff; /* letra blanca */
    }

    .sidebar ul li.active i {
        color: #ffffff; /* ícono blanco */
    }

    /* Eliminamos el borde vertical al lado activo o hover */
    .sidebar ul li::before {
        content: none;
    }


    /* Navbar */
    .navbar-custom {
        
        height: 69px;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 0 20px;
        border-bottom: 1px solid #D5DBE7;
        position: fixed;
        top: 0;
        left: 220px;
        right: 0;
        z-index: 1000;
    }

    .navbar-custom img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #b58900;
    }

    .navbar-custom .logout {
        cursor: pointer;
        color: #dc3545;
        font-size: 20px;
        transition: color 0.3s;
    }

    .navbar-custom .logout:hover {
        color: #a71d2a;
    }

    /* Contenido */
    .content {
        margin-left: 220px;
        margin-top: 60px;
        padding: 25px;
        min-height: calc(100vh - 60px);
        background-color: #f0f4f1;
    }

    .content h1 {
        font-weight: 700;
        color: #333;
    }

    .content p {
        color: #555;
    }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-chalkboard-teacher"></i> Adorarte Profesor
    </div>
    <ul id="menu">
        <li class="active" data-tab="inicio"><i class="fas fa-home"></i> Inicio</li>
        <li data-tab="curso"><i class="fas fa-book"></i> Curso</li>
        <li data-tab="alumnos"><i class="fas fa-user-graduate"></i> Alumnos</li>
        <li data-tab="perfil"><i class="fas fa-user"></i> Perfil</li>
    </ul>
</div>


<!-- Navbar -->
<div class="navbar-custom">
    <img src="<?= $foto_path ?>" alt="Foto Profesor">
    <i class="fas fa-sign-out-alt logout" title="Cerrar sesión" onclick="cerrarSesion()"></i>
</div>

<!-- Contenido -->
<div class="content" id="content">
    <h1>Bienvenido, <?= htmlspecialchars($profesor['nombre']) ?>!</h1>
    <p>Selecciona una opción del menú para empezar.</p>
</div>

<script>

const tabs = document.querySelectorAll('#menu li');
const content = document.getElementById('content');

// -------------------------------
//  Cambiar pestañas
// -------------------------------
tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const tabName = tab.dataset.tab;

        fetch(`partials/${tabName}.php`)
            .then(res => res.text())
            .then(html => {
                content.innerHTML = html;
                if(tabName === 'alumnos') initAlumnos();
                if (tabName === 'perfil') initPerfil();  // ← IMPORTANTE
            })
            .catch(err => {
                content.innerHTML = `<p>Error al cargar la pestaña.</p>`;
                console.error(err);
            });
    });
});

// -------------------------------
//  Inicializa funcionalidad alumnos
// -------------------------------
function initAlumnos() {

    const btnTerminar = document.querySelector('.btn-terminar');
    if(!btnTerminar) return;

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
                text: 'Todos los alumnos deben tener calificación válida (0-10).'
            });
            return;
        }

        Swal.fire({
            title: '¿Terminar curso?',
            text: "Esto marcará el curso como terminado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, terminar curso'
        }).then((result) => {

            if(result.isConfirmed){

                const cursoId = btnTerminar.dataset.cursoId;

                const calificaciones = Array.from(inputs).map(input => ({
                    id_hijo: input.dataset.idHijo,
                    calificacion: input.value
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
                            title: 'Curso terminado'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(err => {
                    Swal.fire('Error en la solicitud');
                    console.error(err);
                });
            }
        });
    });
}

// -------------------------------
//  Cargar pestaña por defecto
// -------------------------------
window.addEventListener('DOMContentLoaded', () => {
    const tab = document.querySelector('#menu li.active');
    if(tab){
        fetch(`partials/${tab.dataset.tab}.php`)
            .then(res => res.text())
            .then(html => {
                content.innerHTML = html;
                if(tab.dataset.tab === 'alumnos') initAlumnos();
                if(tab.dataset.tab === 'perfil') initPerfil();  // ← IMPORTANTE
            });
    }
});

// -------------------------------
function cerrarSesion() {
    fetch('../cerrar_sesion.php')
        .then(() => window.location.href = '../index.php');
}

// -------------------------------
//  Inicializa pestaña perfil
// -------------------------------
function initPerfil() {

    // --- PREVIEW DE FOTO ---
    const inputFoto = document.getElementById("input-foto");
    if (inputFoto) {
        inputFoto.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (!file) return;
    
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById("img-preview").src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    // --- GUARDAR DATOS ---
    const btnGuardarDatos = document.getElementById("guardar-datos");
    if (btnGuardarDatos) {
        btnGuardarDatos.addEventListener("click", function() {

            const nombre = document.querySelector("input[type='text']").value;
            const email = document.querySelector("input[type='email']").value;
            const foto = document.getElementById("input-foto").files[0];

            let formData = new FormData();
            formData.append("nombre", nombre);
            formData.append("email", email);
            if (foto) formData.append("foto", foto);

            fetch("partials/actualizar_perfil.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.success ? "success" : "error",
                    title: data.message,
                    timer: 1800,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    if (data.success) location.reload();
                });
            });
        });
    }
        // OJITOS PARA VER CONTRASEÑA
    function togglePassInput(id, icon) {
        let input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
            icon.textContent = "🙈";
        } else {
            input.type = "password";
            icon.textContent = "👁";
        }
    }

    document.querySelectorAll(".toggle-pass").forEach(btn => {
        btn.addEventListener("click", function () {
            togglePassInput(this.previousElementSibling.id, this);
        });
    });

        const btnGuardarPass = document.getElementById("guardar-pass");
    if (btnGuardarPass) {

        btnGuardarPass.addEventListener("click", function () {

            const actual = document.getElementById("pass-actual").value.trim();
            const nueva = document.getElementById("pass-nueva").value.trim();
            const confirmar = document.getElementById("pass-confirmar").value.trim();

            if (!actual || !nueva || !confirmar) {
                Swal.fire("Error", "Todos los campos son obligatorios.", "error");
                return;
            }

            if (nueva !== confirmar) {
                Swal.fire("Error", "Las nuevas contraseñas no coinciden.", "error");
                return;
            }

            let formData = new FormData();
            formData.append("actual", actual);
            formData.append("nueva", nueva);

            fetch("partials/actualizar_password.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    Swal.fire({
                        icon: "success",
                        title: "IMPORTANTE",
                        html: `
                            Guarda tu nueva contraseña.<br>
                            <b>Solo podrás verla esta vez.</b><br><br>
                            <strong style="font-size:20px;">${nueva}</strong>
                        `,
                        confirmButtonText: "Entendido",
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Limpia inputs
                        document.getElementById("pass-actual").value = "";
                        document.getElementById("pass-nueva").value = "";
                        document.getElementById("pass-confirmar").value = "";
                    });

                } else {
                    Swal.fire("Error", data.message, "error");
                }
            });
        });
    }


}

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
