<?php
//include('/var/www/vhosts/campanias.roymo.info/httpdocs/includes/mysql-connection.php');
header('Content-Type: application/json');

/**Accedemos a los valores del formulario para hacer las consultas */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(["error" => "Por favor, rellene todos los campos."]);
        exit();
    } else if ($newPassword !== $confirmPassword) {
        echo json_encode(["coincidir" => "Las contraseñas deben coincidir."]);
        exit();
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE Usuarios SET password = ? WHERE id_usuario = ?";
        $updateStatement = $mysqlWrapper->prepareStatement($sql);
        $mysqlWrapper->bindParams($updateStatement, "si", $hashedPassword, $id_usuario);
        $result = $mysqlWrapper->executeStatement($updateStatement);
        echo json_encode(["success" => "Contraseña actualizada con éxito."]);
    }
}