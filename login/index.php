<?php
session_start();
include("includes/mysql-connection.php");
header('Content-Type: application/json');
use \JWT\JWT;
use \JWT\Key;

$input = json_decode(file_get_contents('php://input'), true);
if (isset($input['email'], $input['passwd'])) {
    $email = $input['email'];
    $passwd = $input['passwd'];

    if ($stmt = $mysqlWrapper->prepareStatement("SELECT * FROM Usuarios WHERE email = ? LIMIT 1")) {
        $mysqlWrapper->bindParams($stmt, "s", $email);
        $result = $mysqlWrapper->executeStatement($stmt);
        $usuario = $mysqlWrapper->fetchAssoc($result);

        if ($usuario && password_verify($passwd, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];

            //Generación del token del usuario
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

  
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
      const popup = document.getElementById('forgotPasswordPopup');
      document.getElementById('forgotPasswordBtn').addEventListener("click",function () {
        popup.style.display = 'flex';
      });
      window.addEventListener("click", (event) => { //Si se hace click fuera del popup cerrarlo
        if (event.target === popup) {
            popup.style.display = "none";
        }
      });
    $(document).ready(function() {
        $('#resetPasswordForm').on('submit', function(event) {
            event.preventDefault(); 
            const emailReset = $('#emailReset').val();
            
            if (!emailReset) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, ingresa un correo electrónico.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                return; 
            }

            $.ajax({
                url: 'https://campanias.roymo.info/api/usuarios/forgotPassword/index.php', 
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ emailReset: emailReset }), 
                beforeSend: function() {
                    document.getElementById('spinner').style.display = 'block';
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Éxito',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar',
                        });
                        document.getElementById('spinner').style.display = 'none';
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                        document.getElementById('spinner').style.display = 'none';
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: "Hubo un problema al procesar tu solicitud.",
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    document.getElementById('spinner').style.display = 'none';
                }
            });
        });
    });
    $(document).ready(function() {
    $('#login_form').on('submit', function(event) {
        event.preventDefault();
    
        const email = $('#email').val();  
        const passwd = $('#passwd').val(); 
        if (!email || !passwd) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            return; 
        }

        $.ajax({
            url: 'https://campanias.roymo.info/api/usuarios/login/', 
            type: 'POST',
            contentType: 'application/json', 
            data: JSON.stringify({ email: email, passwd: passwd }),
            success: function(response) {
                if (response.success) {
                        setTimeout(() => {
                            console.log("Redirigiendo a /usuarios");
                            window.location.href = '/usuarios'; 
                        }, 500); 
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: "Hubo un problema al procesar tu solicitud.",
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});
</script>

