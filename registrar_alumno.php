<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include 'db_config.php';
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $matricula = $_POST['matricula'];
    $id_grupo = $_POST['id_grupo'];

    $sql = "INSERT INTO Alumnos (Nombre, Apellido, Matricula, id_grupo) VALUES ('$nombre', '$apellido', '$matricula', $id_grupo)";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Alumno registrado con éxito');</script>";
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
    <title>Registrar Alumnos</title>
</head>
<body>
    <h1>Registrar Alumnos</h1>
    <form action="registrar_alumno.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required>
        
        <label for="id_grupo">Grupo:</label>
        <select id="id_grupo" name="id_grupo" required>
            <?php
            $sql = "SELECT * FROM Grupos";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id_grupo']}'>{$row['nombre_grupo']}</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Registrar</button>
    </form>
</body>
</html>

