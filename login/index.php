<style>
@font-face {
    font-family: "Principal";
    src: url('../fonts/Nunito-VariableFont_wght.woff');
}
@font-face {
    font-family: 'Title';
    src: url('../fonts/Humane-Regular.otf') format('woff'),
         url('../fonts/Humane-Regular.woff2') format('woff2');
}
@font-face {
    font-family: 'Cursiva';
    src: url('../fonts/ButterflyKids-Regular.woff') format('woff'),
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
}
h1 {
    font-family: "Title", "Principal";
    font-size: 90px;
    text-transform: uppercase;
    font-weight: 400;
    letter-spacing: 4px;
    color: white;
    padding: 0px;
   
}
nav ul {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 20px;
}
nav li {
    color: white;
    cursor: pointer;
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}
nav li:hover {
    color: yellow;
}
nav li:nth-child(1) { transition-delay: 0.1s; }
nav li:nth-child(2) { transition-delay: 0.2s; }
nav li:nth-child(3) { transition-delay: 0.3s; }
nav li:nth-child(4) { transition-delay: 0.4s; }
nav li:nth-child(5) { transition-delay: 0.5s; }
nav li:nth-child(6) { transition-delay: 0.6s; }
nav li:nth-child(7) { transition-delay: 0.7s; }
.nav-list-show li {
    opacity: 1;
    transform: translateY(0);
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
    background-color: none;
    border: none;
}
</style>
    <div id="particles-js"></div>
    <div class="container">
        <form class="login_form" id="login_form" method="POST" action="./login.php">
            <img src="../resources/logoRommel.png" class="logo"> 
            <label for="email">Correo electrónico</label>
            <input name="email" type="email" id="email" placeholder="Introduzca su correo electrónico" >
            <label for="passwd">Contraseña</label>
            <input name="passwd" type="password" id="passwd" placeholder="Introduzca su contraseña">
            <button type="submit">Enviar</button>
            <button type="button" id="forgotPasswordBtn">¿Has olvidado tu contraseña?</button>
        </form>
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
                <button type="submit">Enviar</button>
                <div id="message"></div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    fetch('particlesjs-config.json')
      .then(response => response.json())
      .then(config => {
        particlesJS('particles-js', config);
      })
      .catch(error => console.error("Error al cargar el archivo JSON:", error));
      
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

