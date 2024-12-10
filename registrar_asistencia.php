<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include 'db_config.php';
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_grupo = isset($_POST['id_grupo']) ? intval($_POST['id_grupo']) : null;
} else {
    $id_grupo = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia</title>
</head>
<body>
    <h1>Registrar Asistencia</h1>
    <form action="registrar_asistencia.php" method="post">
        <label for="id_grupo">Seleccionar Grupo:</label>
        <select id="id_grupo" name="id_grupo" required>
            <?php
            $sql = "SELECT * FROM Grupos";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $selected = ($row['id_grupo'] == $id_grupo) ? "selected" : "";
                echo "<option value='{$row['id_grupo']}' $selected>{$row['nombre_grupo']}</option>";
            }
            ?>
        </select>
        <button type="submit">Seleccionar</button>
    </form>

    <?php if ($id_grupo): ?>
        <h2>Lista de Alumnos</h2>
        <form action="guardar_asistencia.php" method="post">
            <input type="hidden" name="id_grupo" value="<?= $id_grupo; ?>">
            <table border="1">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM Alumnos WHERE id_grupo = $id_grupo";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['Nombre']} {$row['Apellido']}</td>
                                    <td>
                                        <input type='checkbox' name='asistencia[{$row['id']}]' value='1'>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No hay alumnos registrados en este grupo.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit">Guardar Asistencia</button>
        </form>
    <?php endif; ?>
</body>
</html>

