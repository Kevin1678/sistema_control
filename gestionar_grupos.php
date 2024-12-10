<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include 'db_config.php';
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_grupo = $_POST['nombre_grupo'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO Grupos (nombre_grupo, descripcion) VALUES ('$nombre_grupo', '$descripcion')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Grupo creado con éxito');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Grupos</title>
</head>
<body>
    <h1>Gestionar Grupos</h1>
    <form action="gestionar_grupos.php" method="post">
        <label for="nombre_grupo">Nombre del Grupo:</label>
        <input type="text" id="nombre_grupo" name="nombre_grupo" required>
        
        <label for="descripcion">Descripción (opcional):</label>
        <input type="text" id="descripcion" name="descripcion">
        
        <button type="submit">Crear Grupo</button>
    </form>

    <h2>Lista de Grupos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Grupo</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM Grupos";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_grupo']}</td>
                            <td>{$row['nombre_grupo']}</td>
                            <td>{$row['descripcion']}</td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</body>
</html>
