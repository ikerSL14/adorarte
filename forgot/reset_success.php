<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../imgs/adorate.png">
    <title>Contraseña Actualizada</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?q=80&w=871&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .success-box {
            background: rgba(255, 255, 255, 0.9);
            width: 90%;
            max-width: 430px;
            padding: 2.5rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.8s ease-in-out;
        }

        .success-box h2 {
            font-size: 1.6rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .success-box p {
            color: #444;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .success-box button {
            width: 100%;
            padding: 12px;
            background: #c7aa2b;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .success-box button:hover {
            background: #3C1E06;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

</head>
<body>

    <div class="success-box">
        <h2>¡Contraseña actualizada!</h2>
        <p>Tu contraseña ha sido restablecida exitosamente.  
        Ya puedes iniciar sesión nuevamente.</p>

        <a href="../index.php">
            <button>Ir al inicio</button>
        </a>
    </div>

</body>
</html>
