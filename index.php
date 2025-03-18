<?php
require_once 'jwt/JWT.php';
require_once 'jwt/Key.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

//Si la ruta no es login ni restablecer contraseña verificamos el token
if ($route != '' && !str_contains($route, "resetPassword") && !str_contains($route, "api")) {
    $jwt = $_COOKIE['user_cookie'] ?? null;
    if ($jwt) {
        try {
            $secret_key = "1234";
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            $admin = $decoded->userId;
        } catch (Exception $e) {
            header("Location: /"); //Aquí si hay algún fallo con el token te redirige a la página de login directamente y cuando pase x tiempo
            error_log($e);
            exit; //Obligatorio
        }
    } else {
        header("Location: /"); // Aquí lo mismo
        error_log("Error");
        exit;
    }
}
// Aquí tendremos que definir las rutas restringidas
$restricted_dirs = ['assets', 'uploads', 'css', 'js'];

// Evitar acceso a carpetas restringidas
if (in_array(explode('/', $route)[0], $restricted_dirs)) {
    http_response_code(403);
    die("Acceso denegado.");
}

// Manejo de rutas
if ($route === '') {
    $content = 'home.php'; //Aquí incluir la página de login
} elseif (strpos($route, 'api/') === 0) {
    // Si es una llamada a la API, procesarla sin cargar HTML
    $api_route = str_replace('api/', '', $route); // Quitamos "api/" pensar que / y archivo tiene que ser lo mismo
    if (is_dir("api/$api_route") && file_exists("api/$api_route/index.php")) {
        include "api/$api_route/index.php";
    } elseif (file_exists("api/$api_route.php")) {
        include "api/$api_route.php";
    } else {
        http_response_code(404);
        echo "Error 404: API endpoint no encontrado.";
    }
    exit; // Detener la ejecución aquí para evitar la carga del HTML
} elseif (is_dir($route) && file_exists("$route/index.php")) {
    $content = "$route/index.php";
} elseif (file_exists("$route.php")) {
    $content = "$route.php";
} else {
    http_response_code(404);
    $content = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de inicio</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script> <!-- Librería JWT -->
    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script> <!--Librería particles -->
</head>
<style>
    @font-face {
        font-family: "Principal";
        src: url('./resources/fonts/Nunito-VariableFont_wght.woff');
    }

    @font-face {
        font-family: 'Title';
        src: url('./resources/fonts/Humane-Regular.otf') format('woff'),
            url('./resources/fonts/Humane-Regular.woff2') format('woff2');
    }

    @font-face {
        font-family: 'Cursiva';
        src: url('./resources/fonts/ButterflyKids-Regular.woff') format('woff'),
    }

    body {
        font-family: "Principal", sans-serif;
        margin: 0;
        padding: 0;
        background-color: var(--bg-color);
        color: var(--text-color);
        transition: background-color 0.3s, color 0.3s;
    }

    :root {
        --bg-color: #ffffff;
        --text-color: #000000;
    }

    .dark-mode {
        --bg-color: #1a1a1a;
        --text-color: #ffffff;
        transition: all 1s ease-out;
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #222;
        padding: 15px 0;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-family: "Title", "Principal";
        font-size: 90px;
        text-transform: uppercase;
        font-weight: 400;
        letter-spacing: 4px;
        var(--text-color);
        padding: 0px;

    }
     p {
        color: var(--text-color);
    }
    nav ul {
        list-style: none;
        padding: 0;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    nav li{
        color: white;
        cursor: pointer;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }
    nav li a {
        text-decoration: none;
        color: white;
    }
    nav li a:visited {
        text-decoration: none;
        color: white;
    }

    nav li a:hover {
        color: yellow;
    }

    nav li:nth-child(1) {
        transition-delay: 0.1s;
    }

    nav li:nth-child(2) {
        transition-delay: 0.2s;
    }

    nav li:nth-child(3) {
        transition-delay: 0.3s;
    }

    nav li:nth-child(4) {
        transition-delay: 0.4s;
    }

    nav li:nth-child(5) {
        transition-delay: 0.5s;
    }

    nav li:nth-child(6) {
        transition-delay: 0.6s;
    }

    nav li:nth-child(7) {
        transition-delay: 0.7s;
    }

    .nav-list-show li {
        opacity: 1;
        transform: translateY(0);
    }

    .logo {
        max-width: 90px;
    }

    .login {
        background: yellow;
        color: black;
        border: none;
        padding: 10px 20px;
        font-size: 18px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .login:hover {
        background: orange;
    }

    .theme-toggle {
        padding: 0;
        background-color: transparent;
        border: none; 
        font-size: 24px;
        cursor: pointer;
    }
    #forgotPasswordPopup {
        display: none;
    }
    #login-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    #login-popup .popup-container {
        width: 100%;
        max-width: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    #login-popup .popup-container form {
        width: 100%;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: left;
        padding: 40px;
    }

    #login-popup .popup-container form .logo {
        align-self: center;
        max-width: 80px;
        margin-bottom: 20px;
    }

    #login-popup .popup-container form label {
        color: rgb(68, 68, 68);
        text-align: left;
        width: 100%;
    }

    #login-popup .popup-container form input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        margin-bottom: 25px;
    }

    #login-popup .popup-container form button {
        width: 100%;
        padding: 10px;
        background-color: black;
        border: none;
        margin: 0px;
        border-radius: 5px;
        color: white;
        margin-top: 10px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    #particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        z-index: 1000;
        position: absolute;
        top:50%;
        left:50%;
    }
</style>

<body>
    <header>
        <img src="resources/img/logo.png" class="logo">
        <nav>
            <ul id="nav-list">
                <li><a href="/TFG/inicio/">Inicio</a></li>
                <li><a href="/TFG/latara/">La Tará</a></li>
                <li><a href="/TFG/educacion/">Educación</a></li>
                <li><a href="/TFG/espectacuos/">Espectáculos</a></li>
                <li><a href="/TFG/tienda/">Tienda</a></li>
                <li><a href="/TFG/blog/">Blog</a></li>
                <li><a href="/TFG/contacto/">Contacto</a></li>
            </ul>
        </nav>
        <button class="login" id="login-button">Acceso</button>
        <button id="theme-toggle" class="theme-toggle">🌙</button>
    </header>
    <div class="login-popup" id="login-popup">
        <div class="popup-container">
            <form>
                <img src="resources/img/logo.png" class="logo">
                <input placeholder="Correo electrónico" id="login-email" name="login-email" class="input-login" type="email" required>
                <input placeholder="Contraseña" id="login-password" name="login-password" class="input-login" type="password" required>
                <button type="submit">Enviar</button>
                <button type="button" id="forgotPassword">¿Has olvidado tu contraseña?</button>
                <div id="login-message"></div>
            </form>
        </div>
    </div>
    <div id="spinner" style="display: none;">
        <div class="spinner"></div>
    </div>
    <div id="forgotPasswordPopup" class="popup">
        <div class="popup-container">
            <form id="resetPasswordForm" action="/api/usuarios/forgotPassword/index.php" method="POST">
                <img src="../resources/logoRommel.png" class="logo">
                <label>Correo electrónico:</label>
                <input name="emailReset" type="email" id="emailReset" placeholder="Introduzca su correo electrónico" required>
                <button type="submit" id="forgotPasswordBtn">Enviar</button>
                <div id="message"></div>
            </form>
        </div>
    </div>
    <div id="particles-js"></div>
</body>

</html>
<script>
    fetch('particlesjs-config.json')
            .then(response => response.json())
            .then(config => {
                particlesJS('particles-js', config);
            })
            .catch(error => console.error("Error al cargar el archivo JSON:", error));
    window.addEventListener("load", () => {
        document.getElementById("nav-list").classList.add("nav-list-show");
    });

    const toggleButton = document.getElementById("theme-toggle");
    const body = document.body;
    toggleButton.addEventListener("click", () => {
        body.classList.toggle("dark-mode");
        if (body.classList.contains("dark-mode")) {
            localStorage.setItem("theme", "dark");
            toggleButton.textContent = "☀️";
            fetch('particlesjs-dark-config.json')
            .then(response => response.json())
            .then(config => {
                particlesJS('particles-js', config);
            })
            .catch(error => console.error("Error al cargar el archivo JSON:", error));
        } else {
            localStorage.setItem("theme", "light");
            toggleButton.textContent = "🌙";
            fetch('particlesjs-config.json')
            .then(response => response.json())
            .then(config => {
                particlesJS('particles-js', config);
            })
            .catch(error => console.error("Error al cargar el archivo JSON:", error));
        }
    });
    const popup = document.getElementById('forgotPasswordPopup');
      document.getElementById('forgotPasswordBtn').addEventListener("click",function () {
        popup.style.display = 'flex';
      });
    
    const loginButton = document.getElementById('login-button');
    const loginPopup = document.getElementById('login-popup');
    loginButton.addEventListener("click", function () {
        loginPopup.style.display = 'block';
    })
    window.addEventListener("click", (event) => {
        if (event.target === loginPopup) {
            loginPopup.style.display = "none";
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.getElementById("login_form");

    loginForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const passwd = document.getElementById("passwd").value;

        if (!email || !passwd) { return;}

        fetch("https://campanias.roymo.info/api/usuarios/login/", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email: email, passwd: passwd })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setTimeout(() => {
                    console.log("Redirigiendo a /usuarios");
                    window.location.href = "/usuarios";
                }, 500);
            } else {
               console.log("Credenciales incorresctAAAS");
            }
        })
        .catch(error => {
            console.log("Error en la solicitud:", error);
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const resetPasswordForm = document.getElementById("resetPasswordForm");
    resetPasswordForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const emailReset = document.getElementById("emailReset").value;
        const spinner = document.getElementById("spinner");

        if (!emailReset) { return;}

        spinner.style.display = "block";

        fetch("https://dominiooo/api/usuarios/forgotPassword/index.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ emailReset: emailReset })
        })
        .then(response => response.json())
        .then(data => {
            spinner.style.display = "none"; 
            console.log("Enviadoooo");
        })
        .catch(error => {
            spinner.style.display = "none";
            console.log("Error al procesar la solicitud");
        });
    });
});

</script>

<?php
// Incluir el contenido si existe
if ($content) {
    include $content;
} else {
    include '404.php'; 
    //header("Location: 404.php");
}
?>

</body>
</html>
