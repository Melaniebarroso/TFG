<?php
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
    $content = 'home.html'; //Aquí incluir la página de login
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
</head>
<body>
    <header>
        <img width="100px" src="resources/logo.png" class="logo">
        <nav>
            <ul id="nav-list">
                <li>Inicio</li>
                <li>La Tará</li>
                <li>Educación</li>
                <li>Espectáculos</li>
                <li>Tienda</li>
                <li>Blog</li>
                <li>Contacto</li>
            </ul>
        </nav>
        <button class="login">Acceso</button>
        <button id="theme-toggle" class="theme-toggle">🌙</button>
    </header>
</body>
</html>
<script>
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
  } else {
    localStorage.setItem("theme", "light");
    toggleButton.textContent = "🌙";
  }
});

</script>

<?php
// Incluir el contenido si existe
if ($content) {
    include $content;
} else {
    //Aquí tendremos que incluir una página de no encontrado
    echo "<h1>Error 404: Página no encontrada.</h1>";
    //header('Location: '.$nuevaURL.php);
}
?>

</body>
</html>