<?php
session_start();
include '../conexion_be.php';

$token = $_GET['token'] ?? '';

// Ver si existe
$query = mysqli_query($conexion, "SELECT * FROM password_resets WHERE token='$token' LIMIT 1");

if (mysqli_num_rows($query) == 0) {
    die("Token inválido o expirado");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="icon" type="image/x-icon" href="../imgs/adorate.png">
<title>Restablecer contraseña</title>
<style>
*,
*::before,
*::after {
    box-sizing: border-box;
}
body { font-family: 'Raleway', sans-serif;
    background: url('https://images.unsplash.com/photo-1615406839587-0276084b72bb?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
    background-size: cover; /* Hace que se adapte a todo el body */
    background-color:#f0f0f0; }
.form {
    width:450px; 
    margin:6rem auto; 
    margin-top: 8rem;
    padding:2rem;
    background:white; border-radius:12px;
    box-shadow:0 4px 20px rgba(0,0,0,0.1);
}
.form input, .form button {
    width:100%; padding:12px; margin-bottom:1rem;
    border-radius:8px; border:1px solid #ccc;
}
.form button{
    background:#C7AA2B; border:none; color:white;
    font-weight:bold; cursor:pointer; transition: background 0.3s ease-in-out;
}
.form button:hover {
    background: #8d791c;
}
</style>
</head>

<body>

<div class="form">
    <h2>Crear nueva contraseña</h2>
    <form action="reset_process.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <input type="password" name="pass1" placeholder="Nueva contraseña" required>
        <input type="password" name="pass2" placeholder="Confirmar contraseña" required>

        <button type="submit">Guardar contraseña</button>
    </form>
</div>

</body>
</html>
