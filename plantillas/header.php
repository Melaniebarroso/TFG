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
        src: url('../resources/fonts/Nunito-VariableFont_wght.woff');
    }
    @font-face {
        font-family: 'Title';
        src: url('../resources/fonts/Humane-Regular.otf') format('woff'),
            url('../resources/fonts/Humane-Regular.woff2') format('woff2');
    }
    @font-face {
        font-family: 'Cursiva';
        src: url('../resources/fonts/ButterflyKids-Regular.woff') format('woff'),
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
        color: var(--text-color);
    }

    h1 {
        font-family: "Title", "Principal";
        font-size: 90px;
        text-transform: uppercase;
        font-weight: 400;
        letter-spacing: 4px;
        color: white;
        padding: 0px;
        transition: all 0.4s ease-in-out;
    }
    p {
        color: var(--text-color);
        transition: all 0.4s ease-in-out;
    }
    #nav-list {
        display: flex;           
        justify-content: center;  
        gap: 20px;    
        padding: 0;
        list-style: none;      
    }
    #nav-list ul {
        list-style: none;
        padding: 0;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    #nav-list li{
        color: white;
        cursor: pointer;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }

    #nav-list li a {
        text-decoration: none;
        color: white;
    }
    #nav-list li a:visited {
        text-decoration: none;
        color: white;
    }

    #nav-list li a:hover {
        color: red;
    }

    #nav-list li:nth-child(1) {
        transition-delay: 0.1s;
    }

    #nav-list li:nth-child(2) {
        transition-delay: 0.2s;
    }

    #nav-list li:nth-child(3) {
        transition-delay: 0.3s;
    }

    #nav-list li:nth-child(4) {
        transition-delay: 0.4s;
    }

    #nav-list li:nth-child(5) {
        transition-delay: 0.5s;
    }

    #nav-list li:nth-child(6) {
        transition-delay: 0.6s;
    }

    #nav-list li:nth-child(7) {
        transition-delay: 0.7s;
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
        outline: none;
    }
    .theme.toggle:focus {
        outline: none;
    }

    #login-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.5);
    }

    #login-popup .popup-container {
        display: none;
        width: 100%;
        max-width: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
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
</style>

<body>
    <header>
        <img src="../resources/img/logo.png" class="logo">
        <nav>
            <ul id="nav-list">
                <li><a href="/TFG/inicio/">Inicio</a></li>
                <li><a href="/TFG/latara/">La Tará</a></li>
                <li><a href="/TFG/educacion/">Educación</a></li>
                <li><a href="/TFG/espectaculos/">Espectáculos</a></li>
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
                <img src="../resources/img/logo.png" class="logo">
                <input placeholder="Correo electrónico" id="login-email" class="input-login" type="password" required>
                <input placeholder="Contraseña" id="login-password" class="input-login" type="email" required>
                <button type="submit">Enviar</button>
                <button type="button" id="forgotPasswordBtn">¿Has olvidado tu contraseña?</button>
                <div id="login-message"></div>
            </form>
        </div>
    </div>
    <div id="particles-js"></div>
</body>
</html>
<script>
    fetch('../particlesjs-config.json')
            .then(response => response.json())
            .then(config => {
                particlesJS('particles-js', config);
            })
            .catch(error => console.error("Error al cargar el archivo JSON:", error));

    window.addEventListener("load", () => {
        document.getElementById("nav-list").classList.add("nav-list-show");
    });
    //Añadido manualmente este estilo en javascript ya que había confusión entre archivos.
    window.addEventListener("load", () => {
    document.querySelectorAll("#nav-list li").forEach(li => {
        li.style.opacity = "1";
        li.style.transform = "translateY(0)";
        });
    });

    const toggleButton = document.getElementById("theme-toggle");
    const body = document.body;
    toggleButton.addEventListener("click", () => {
        body.classList.toggle("dark-mode");
        if (body.classList.contains("dark-mode")) {
            localStorage.setItem("theme", "dark");
            toggleButton.textContent = "☀️";
            fetch('../particlesjs-dark-config.json')
            .then(response => response.json())
            .then(config => {
                particlesJS('particles-js', config);
            })
            .catch(error => console.error("Error al cargar el archivo JSON:", error));
        } else {
            localStorage.setItem("theme", "light");
            toggleButton.textContent = "🌙";
            fetch('../particlesjs-config.json')
            .then(response => response.json())
            .then(config => {
                particlesJS('particles-js', config);
            })
            .catch(error => console.error("Error al cargar el archivo JSON:", error));
        }
    });

    const loginButton = document.getElementById('login-button');
    const loginPopup = document.getElementById('login-popup');

    console.log(loginPopup.style);
    loginButton.addEventListener("click", function () {
        console.log("boton pulsado");
        loginPopup.style.display = 'flex'; 
        loginPopup.style.zIndex ="999";
    });

    window.addEventListener("click", (event) => {
        if (event.target === loginPopup) {
            loginPopup.style.display = "none";
        }
    });

</script>