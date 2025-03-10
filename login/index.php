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

