<style>
    body {
        font-family: 'Montserrat';
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f4f4f4;
    }
    #particles-js {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    .container {
        width: 100%;
        max-width: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login_form {
        width: 100%;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: left;
        padding: 50px;
    
    }
    .login_form .logo {
        align-self: center;
        max-width: 140px;
        margin-bottom: 40px;
    }
    .login_form label {
        color: rgb(68, 68, 68);
        text-align: left;
        width: 100%;
    }
    .login_form input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        margin-bottom: 25px;
    }

    .login_form button {
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

    .login_form button:hover {
        background-color: gray;
        color: black;
    }
    .popup {
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
    .popup-container {
        width: 100%;
        max-width: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex
        z-index: 999;
    }
    .popup-container form {
        width: 100%;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: left;
        padding: 50px 50px 50px 50px;
    }
    .popup-container form .logo {
        align-self: center;
        max-width: 140px;
        margin-bottom: 40px;
    }
    .popup-container form label {
        color: gray;
        text-align: left;
        width: 100%;
    }
    .popup-container form input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        margin-bottom: 25px;
    }
    .popup-container form button {
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

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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

