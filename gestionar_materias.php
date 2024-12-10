<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include 'db_config.php';
include 'menu.php';

// Agregar materia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = $_POST['nombre_materia'];
    $descripcion = $_POST['descripcion'];
    $clave = $_POST['clave_materia'];

    $stmt = $conn->prepare("INSERT INTO Materias (nombre_materia, descripcion, clave_materia) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $descripcion, $clave);
    $stmt->execute();
    $stmt->close();
}

// Editar materia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id_materia = $_POST['id_materia'];
    $nombre = $_POST['nombre_materia'];
    $descripcion = $_POST['descripcion'];
    $clave = $_POST['clave_materia'];

    $stmt = $conn->prepare("UPDATE Materias SET nombre_materia = ?, descripcion = ?, clave_materia = ? WHERE id_materia = ?");
    $stmt->bind_param("sssi", $nombre, $descripcion, $clave, $id_materia);
    $stmt->execute();
    $stmt->close();
}

// Eliminar materia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id_materia = $_POST['id_materia'];

    $stmt = $conn->prepare("DELETE FROM Materias WHERE id_materia = ?");
    $stmt->bind_param("i", $id_materia);
    $stmt->execute();
    $stmt->close();
}

// Obtener todas las materias
$materias = $conn->query("SELECT * FROM Materias");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Gestionar Materias</title>
</head>
<body>
    <h1>Gestión de Materias</h1>

    <!-- Formulario para agregar materia -->
    <h2>Agregar Nueva Materia</h2>
    <form method="POST" action="gestionar_materias.php">
        <input type="hidden" name="accion" value="agregar">
        <label for="nombre_materia">Nombre de la Materia:</label>
        <input type="text" id="nombre_materia" name="nombre_materia" required>
        <br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
        <br>
        <label for="clave_materia">Clave de la Materia:</label>
        <input type="text" id="clave_materia" name="clave_materia" required>
        <br>
        <button type="submit">Agregar Materia</button>
    </form>

    <!-- Tabla para listar materias -->
    <h2>Materias Registradas</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Clave</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $materias->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_materia']; ?></td>
                <td><?= htmlspecialchars($row['nombre_materia']); ?></td>
                <td><?= htmlspecialchars($row['descripcion']); ?></td>
                <td><?= htmlspecialchars($row['clave_materia']); ?></td>
                <td>
                    <!-- Formulario para editar materia -->
                    <form method="POST" action="gestionar_materias.php" style="display:inline;">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id_materia" value="<?= $row['id_materia']; ?>">
                        <input type="text" name="nombre_materia" value="<?= htmlspecialchars($row['nombre_materia']); ?>" required>
                        <textarea name="descripcion" required><?= htmlspecialchars($row['descripcion']); ?></textarea>
                        <input type="text" name="clave_materia" value="<?= htmlspecialchars($row['clave_materia']); ?>" required>
                        <button type="submit">Editar</button>
                    </form>

                    <!-- Formulario para eliminar materia -->
                    <form method="POST" action="gestionar_materias.php" style="display:inline;">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_materia" value="<?= $row['id_materia']; ?>">
                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta materia?');">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
