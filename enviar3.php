<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include 'php/Exception.php';
include 'php/PHPMailer.php';
include 'php/SMTP.php';

// Conexión a la base de datos
include 'conexion_be.php';


$idUsuario = $_SESSION['idUsuario'];
$correo = $_SESSION['correo'];
$nombre = $_SESSION['nombre'];
if (isset($_GET['totalCarrito'])) {
    $totalCarrito = $_GET['totalCarrito'];
  } else {
    $totalCarrito = 0; // Valor predeterminado si no se recibe el parámetro
  }
        // Configuración del correo
        $mail = new PHPMailer();

        try {
            // Configuración del servidor SMTP
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->SMTPDebug = 0; // 0 para no mostrar, 2 para modo completo de depuración
$mail->Debugoutput = 'html'; // Mostrar en formato HTML en la página
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Ejemplo: smtp.gmail.com
            $mail->SMTPAuth = true;
            $mail->Username = 'teecreator123@gmail.com';
            $mail->Password = 'szhi ipir cobo rtpj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('teecreator123@gmail.com', 'TeeCreator');
            $mail->addAddress($correo); // Correo de prueba
            $mail->Subject = 'Compra de $' . $totalCarrito . ' en TeeCreator';
            $mail->Body = "Hola $nombre,\n\n"
            . "Te enviamos un resumen de tu compra:\n"
            . "Total del carrito: $totalCarrito\n\n"
            . "Gracias por tu compra!\n\n"
            . "Atentamente,\n"
            . "Equipo de TeeCreator";

            // Enviar el correo
            $mail->send();
            // Redirigir al index después de enviar el correo
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            // En caso de error, puedes redirigir con mensaje o mostrar error
            // Por simplicidad, redirigimos con un parámetro de error
            header("Location: index.php?error=correo");
            exit;
        }
    

?>