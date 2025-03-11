<?php
session_start();
include("includes/mysql-connection.php");
header('Content-Type: application/json');
require_once 'jwt/JWT.php';
require_once 'jwt/Key.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['login-email'], $input['login-password'])) {
    $email = $input['login-email'];
    $passwd = $input['login-password'];

    if ($stmt = $mysqlWrapper->prepareStatement("SELECT * FROM Usuarios WHERE email = ? LIMIT 1")) {
        $mysqlWrapper->bindParams($stmt, "s", $email);
        $result = $mysqlWrapper->executeStatement($stmt);
        $usuario = $mysqlWrapper->fetchAssoc($result);

        if ($usuario && password_verify($passwd, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];

            $key = "1234"; 
            $time = time(); //Momento en el que se genera
            $expirationTime = $time + 3600;  // Expiración del token. 1 hora.
            $payload = array(
                "iat" => $time,
                "exp" => $expirationTime,
                "userId" => $usuario['id_usuario']
            );
            $jwt = JWT::encode($payload, $key, 'HS256'); //Aquí se genera el token. El tercer argumento es por defecto
            setcookie("user_cookie", $jwt, $expirationTime, "/", "", false, true);  //Y aquí lo usamos
            //(nombre,valor,tiempoexpiración,rutaAccesible(todoeldominio), envio por http y https, no accesible x cliente, httpOnly)

            echo json_encode([
                'success' => true,
                'message' => 'Inicio de sesión correcto.',
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario o contraseña incorrectos.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error en la consulta de la base de datos.'
        ]);
    }
}    
?>
