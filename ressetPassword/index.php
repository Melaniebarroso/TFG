<?php
//include('/var/www/vhosts/campanias.roymo.info/httpdocs/includes/mysql-connection.php');
/** Accedemos con el GET a la reset key  para poder incluirla en la tabla de Password_reset y par acceder a la id del user */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['reset_key'])) {
    $reset_key = $_GET['reset_key'];
    $sql = "SELECT id_usuario FROM PasswordReset WHERE reset_key = '$reset_key'";
    $result = $mysqlWrapper->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_usuario = $row['id_user'];
    }
}
?>
<div class='container'>
            <form method='POST' id='resetPasswordForm' action="../api/usuarios/resetPassword/index.php">
                <img src='../../../resources/logoRommel.png' class='logo'>
                <input type='hidden' name='id_usuario' id='id_usuario' value='<?php echo $id_usuario?>'>
                <label for='newPassword'>Nueva contraseña:</label>
                <input type='password' name='newPassword' id='newPassword' required>
                <label for='confirmPassword'>Confirmar contraseña:</label>
                <input type='password' name='confirmPassword' id='confirmPassword' required>
                <button type='submit'>Restablecer contraseña</button>
            </form>
        </div>
        <div id="particles-js"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    fetch('../particlesjs-config.json')
      .then(response => response.json())
      .then(config => {
        particlesJS('particles-js', config);
      })
      .catch(error => console.error("Error al cargar el archivo JSON:", error));

    
      document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    fetch('../api/usuarios/resetPassword/index.php', {
        method: 'POST',
        body: formData 
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setTimeout(() => {
                window.location.href = 'https://campanias.roymo.info'; 
            }, 2000);
            Swal.fire({
                title: 'Éxito',
                text: 'Contraseña actualizada con éxito',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        } else if (data.coincidir) {
            Swal.fire({
                title: 'Error',
                text: 'Las contraseñas no coinciden',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        } else if (data.errorActualizar) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, rellene todos los campos.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error',
            text: 'Hubo un error al procesar la solicitud.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    });
});

</script>