<?php
include("../plantillas/header.php")
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tienda Online</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/scripts.js" defer></script>
</head>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
}

header {
    background-color: #333;
    color: white;
    padding: 10px;
    text-align: center;
}

header a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
}

footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    position: fixed;
    width: 100%;
    bottom: 0;
}

.contenedor {
    margin: 20px;
}

.productos {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.producto {
    background-color: white;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 200px;
    text-align: center;
}

.producto img {
    max-width: 100%;
    border-radius: 8px;
}

.producto h2 {
    font-size: 18px;
    margin: 10px 0;
}

.producto p {
    font-size: 16px;
    color: #555;
}

.precio {
    font-size: 18px;
    font-weight: bold;
    color: #e74c3c;
}

.btn {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 10px;
    width: 100%;
    border-radius: 8px;
    cursor: pointer;
}

.btn:hover {
    background-color: #27ae60;
}

</style>
<body>

    <?php include 'includes/header.php'; ?>
    
    <div class="contenedor">
        <h1>Bienvenido a nuestra tienda</h1>
        
        <div class="productos">
            <!-- Ejemplo de producto -->
            <div class="producto">
                <img src="img/productos/producto1.jpg" alt="Producto 1">
                <h2>Producto 1</h2>
                <p>Descripción del producto 1.</p>
                <p class="precio">$19.99</p>
                <button class="btn">Agregar al carrito</button>
            </div>

            <div class="producto">
                <img src="img/productos/producto2.jpg" alt="Producto 2">
                <h2>Producto 2</h2>
                <p>Descripción del producto 2.</p>
                <p class="precio">$29.99</p>
                <button class="btn">Agregar al carrito</button>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            alert("Producto agregado al carrito!");
        });
    });
});

</script>

<!--
 // Productos (puedes obtener estos datos desde una base de datos)
$productos = [
    ['id' => 1, 'nombre' => 'Producto 1', 'descripcion' => 'Descripción del producto 1', 'precio' => 19.99, 'imagen' => 'img/productos/producto1.jpg'],
    ['id' => 2, 'nombre' => 'Producto 2', 'descripcion' => 'Descripción del producto 2', 'precio' => 29.99, 'imagen' => 'img/productos/producto2.jpg']
];

foreach ($productos as $producto) {
    echo '<div class="producto">';
    echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
    echo '<h2>' . $producto['nombre'] . '</h2>';
    echo '<p>' . $producto['descripcion'] . '</p>';
    echo '<p class="precio">$' . number_format($producto['precio'], 2) . '</p>';
    echo '<button class="btn" data-id="' . $producto['id'] . '">Agregar al carrito</button>';
    echo '</div>';
}

-->

<?php
include("../plantillas/footer.php")
?>