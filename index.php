<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = md5($_POST['contrasena']);

    $sql = "SELECT * FROM Usuarios WHERE Usuario = '$usuario' AND Contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $usuario;
        header("Location: registrar_alumno.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form action="index.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>
        
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
