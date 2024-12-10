<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <nav class="menu">
        <ul>
            <li><a href="registrar_alumno.php">Registrar Alumnos</a></li>
            <li><a href="registrar_asistencia.php">Registrar Asistencia</a></li>
            <li><a href="registrar_calificaciones.php">Registrar Calificaciones</a></li>
            <li><a href="consultar_reportes.php">Consultar Reportes</a></li>
            <li><a href="registrar_grupo.php">Registrar Grupo</a></li>
            <li><a href="gestionar_materias.php">Registrar Materias</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</body>
</html>
