<?php
session_start();
include '../../conexion_be.php';

$id_profesor = $_SESSION['id_profesor'];

$consulta = mysqli_query($conexion, "SELECT * FROM profesores WHERE id_profesor = $id_profesor");
$prof = mysqli_fetch_assoc($consulta);

$foto = ($prof['foto'] && trim($prof['foto']) !== "")
    ? "../imgs/profesores/{$id_profesor}/{$prof['foto']}"
    : "https://cdn-icons-png.flaticon.com/512/149/149071.png";
?>

<style>
/* ----------- CONTENEDOR GENERAL ----------- */
.perfil-contenedor {
    max-width: 1000px;
    margin: 0 auto;
}

/* ----------- TARJETA SUPERIOR (perfil) ----------- */
.perfil-header {
    background: white;
    padding: 25px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.perfil-foto {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    overflow: hidden;
}

.perfil-foto img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.perfil-info h2 {
    margin: 0;
    font-size: 1.6rem;
    font-weight: 700;
}

.perfil-info p {
    margin: 6px 0;
    color: #444;
}

.perfil-info i {
    margin-right: 6px;
    color: #b58900;
}

/* ----------- TARJETA MIS DATOS ----------- */
.card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    --bs-card-border-color: none !important;
}


.card h3 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 700;
}

.card p.descripcion {
    color: #777;
    margin-top: 5px;
}

.separador {
    width: 100%;
    height: 1px;
    background: #e2e2e2;
    margin: 15px 0;
}

/* ----------- INPUTS ----------- */
.form-row {
    display: flex;
    gap: 20px;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 6px;
    font-weight: 600;
}

.form-group input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.input-pass {
    position: relative;
    display: flex;
    align-items: center;
}

.input-pass input {
    width: 100%;
    padding-right: 40px;
}

.toggle-pass {
    position: absolute;
    right: 12px;
    cursor: pointer;
    font-size: 18px;
    user-select: none;
}

/* ----------- FOTO DE PERFIL (mis datos) ----------- */
.foto-row {
    margin-top: 25px;
    display: flex;
    align-items: center;
    gap: 25px;
}

.foto-preview {
    width: 130px;
    height: 110px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #b58900;
}

.foto-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.foto-input {
    border: 2px dashed #b58900;
    padding: 25px;
    width: 100%;
    text-align: center;
    border-radius: 12px;
    cursor: pointer;
    color: #555;
    transition: 0.3s;
}

.foto-input:hover {
    background: #fff7d6;
}

.btn-guardar {
    margin-top: 20px;
    padding: 12px 22px;
    background: #b58900;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    float: right;
}

/* ----------- TARJETA CONTRASEÑA ----------- */
</style>

<div class="perfil-contenedor">

    <!-- ------------------ HEADER PERFIL ------------------ -->
    <div class="perfil-header">
        <div class="perfil-foto">
            <img src="<?= $foto ?>" alt="Foto perfil">
        </div>

        <div class="perfil-info">
            <h2><?= htmlspecialchars($prof['nombre']) ?></h2>

            <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($prof['email']) ?></p>

            <p>
                <i class="fas fa-calendar"></i> 
                Fecha de registro: <?= htmlspecialchars($prof['fecha_registro']) ?>
            </p>
        </div>
    </div>

    <!-- ------------------ MIS DATOS ------------------ -->
    <div class="card">
        <h3>Mis datos</h3>
        <p class="descripcion">Gestiona tus datos en la plataforma</p>

        <div class="separador"></div>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" value="<?= htmlspecialchars($prof['nombre']) ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?= htmlspecialchars($prof['email']) ?>">
            </div>
        </div>

        <h4 style="margin-top:25px;">Tu foto</h4>

        <div class="foto-row">
            <div class="foto-preview" id="preview-foto">
                <img src="<?= $foto ?>" id="img-preview">
            </div>

            <label class="foto-input">
                Click o arrastra para seleccionar imagen
                <input type="file" id="input-foto" accept="image/*" style="display:none;">
            </label>
        </div>

        <button id="guardar-datos" class="btn-guardar">Guardar cambios</button>
    </div>

    <!-- ------------------ CONTRASEÑA ------------------ -->
    <div class="card">
        <h3>Contraseña</h3>
        <p class="descripcion">Aquí puedes cambiar tu contraseña en el sistema.</p>

        <div class="separador"></div>

        <!-- Contraseña actual -->
        <div class="form-group">
            <label>Contraseña actual</label>
            <div class="input-pass">
                <input type="password" id="pass-actual">
                <span class="toggle-pass" onclick="togglePass('pass-actual', this)">👁</span>
            </div>
        </div>

        <div class="form-row" style="margin-top:15px;">

            <!-- Nueva -->
            <div class="form-group">
                <label>Contraseña nueva</label>
                <div class="input-pass">
                    <input type="password" id="pass-nueva">
                    <span class="toggle-pass" onclick="togglePass('pass-nueva', this)">👁</span>
                </div>
            </div>

            <!-- Confirmación -->
            <div class="form-group">
                <label>Confirma nueva contraseña</label>
                <div class="input-pass">
                    <input type="password" id="pass-confirmar">
                    <span class="toggle-pass" onclick="togglePass('pass-confirmar', this)">👁</span>
                </div>
            </div>
        </div>

        <button id="guardar-pass" class="btn-guardar">Guardar contraseña</button>
    </div>


</div>