<?php
include('/var/www/vhosts/campanias.roymo.info/httpdocs/includes/mysql-connection.php');
require '/var/www/vhosts/campanias.roymo.info/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);
    if (isset($inputData['emailReset'])) {
        $emailReset = $inputData['emailReset']; 
        $sql = "SELECT id_usuario FROM Usuarios WHERE email = '$emailReset'";
        $result = $mysqlWrapper->query($sql);
        $user = $mysqlWrapper->fetchAssoc($result);
    }
    if ($user) {
        $key = bin2hex(random_bytes(16));  
        $resetLink = "https://campanias.roymo.info/resetPassword/?reset_key=" . $key; //CAMBIAR RESETLINK dominio
        $hashedPassword = password_hash($key, PASSWORD_BCRYPT); 
        $sql = "INSERT INTO Password_reset (id_user, reset_key) VALUES ('" . $user['id_usuario'] . "', '$key')";

        $html = file_get_contents('forgotPassword.html'); //Haremos un html a partir de un mjml para el envío de emails
        $html = str_replace('{{resetLink}}', $resetLink, $html);
        $html = str_replace('{{icono}}', $icon, $html);


        if ($mysqlWrapper->query($sql)) {
            $mail = new PHPMailer();
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
                $mail->SMTPAuth = true;
                $mail->Username = 'melanibarroso13@gmail.com';  // Tu cuenta de Gmail
                $mail->Password = 'halq sltv wexf ulhr';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Seguridad TLS
                $mail->CharSet = 'UTF-8'; //Para no tener problema ocn caracteres especiales
                $mail->Port = 587;

                $mail->setFrom('melanibarroso13@gmail.com', 'Melanie');
                $mail->addAddress($emailReset); 
                $mail->isHTML(true);
                $mail->Subject = 'Nueva contraseña generada';
                $mail->Body = $html; //AQUÍ haremos el archivo mjml
                if ($mail->send()) {
                    echo json_encode([
                        'success' => true,
                        'message' => "Te hemos enviado tu nueva contraseña al correo: $emailReset."
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => "Problema al enviar el correo."
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => "No se pudo enviar el mensaje. Error: {$mail->ErrorInfo}"
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => "Error al actualizar la contraseña."
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Usuario no encontrado."
        ]);
    }
}
?>
