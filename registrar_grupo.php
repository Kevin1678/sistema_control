<!-- registrar_grupo.php -->
<?php
    include 'menu.php';  // Incluye el menú
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Grupo</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <h2>Registrar Nuevo Grupo</h2>

    <form action="guardar_grupo.php" method="POST">
        <label for="nombre_grupo">Nombre del Grupo:</label>
        <input type="text" id="nombre_grupo" name="nombre_grupo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <button type="submit">Registrar Grupo</button>
    </form>

</body>
</html>

