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
    <meta charset="utf-8">
    <meta name="copyright" content="Copyright 1999-2024. WebPros International GmbH. All rights reserved.">
    <!-- Aquí incluir lo que va a ser común en todas las páginas
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <style>
        body {
            font-family: "Montserrat";
            height: 100vh;
            background-color: #f4f4f4;
        }
        * {
            box-sizing: border-box;
}
    </style>-->
</head>
<body>

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