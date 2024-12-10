<?php
include 'db_config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

include 'menu.php';

// Determinar si se ha seleccionado un grupo
$id_grupo = isset($_POST['id_grupo']) ? (int)$_POST['id_grupo'] : null;

// Obtener los grupos para el menú desplegable
$stmtGrupos = $conn->prepare("SELECT * FROM Grupos");
$stmtGrupos->execute();
$grupos = $stmtGrupos->get_result();

// Obtener los alumnos del grupo seleccionado
$alumnos = null;
if ($id_grupo) {
    $stmtAlumnos = $conn->prepare("
        SELECT A.id AS IdAlumno, A.Nombre, A.Apellido, A.Matricula, 
            G.nombre_grupo AS Grupo
        FROM Alumnos A
        INNER JOIN Grupos G ON A.id_grupo = G.id_grupo
        WHERE A.id_grupo = ?
    ");
    $stmtAlumnos->bind_param("i", $id_grupo);
    $stmtAlumnos->execute();
    $alumnos = $stmtAlumnos->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Consultar Reportes</title>
</head>
<body>
    <h1>Consultar Reportes por Grupo</h1>

    <!-- Selección de grupo -->
    <form method="POST" action="consultar_reportes.php">
        <label for="id_grupo">Seleccionar Grupo:</label>
        <select name="id_grupo" id="id_grupo" required>
            <option value="">-- Seleccione un grupo --</option>
            <?php while ($row = $grupos->fetch_assoc()): ?>
                <option value="<?= $row['id_grupo']; ?>" <?= $id_grupo == $row['id_grupo'] ? 'selected' : ''; ?>>
                    <?= $row['nombre_grupo']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <?php if ($id_grupo && $alumnos): ?>
        <h2>Reportes del Grupo Seleccionado</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Matrícula</th>
                    <th>Grupo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $alumnos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nombre'] . " " . $row['Apellido']); ?></td>
                    <td><?= htmlspecialchars($row['Matricula']); ?></td>
                    <td><?= htmlspecialchars($row['Grupo']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Reporte de Unidades por Materias -->
        <h2>Calificaciones por Materia y Unidades</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Alumno</th>
                    <th>Materia</th>
                    <th>Unidad 1</th>
                    <th>Unidad 2</th>
                    <th>Unidad 3</th>
                    <th>Unidad 4</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener calificaciones por unidades
                $stmtUnidades = $conn->prepare("
                    SELECT A.Nombre, A.Apellido, M.nombre_materia, 
                        C.unidad1, C.unidad2, C.unidad3, C.unidad4
                    FROM Calificaciones C
                    INNER JOIN Alumnos A ON C.id_alumno = A.id
                    INNER JOIN Materias M ON C.id_materia = M.id_materia
                    WHERE A.id_grupo = ?
                ");
                $stmtUnidades->bind_param("i", $id_grupo);
                $stmtUnidades->execute();
                $unidades = $stmtUnidades->get_result();

                // Mostrar las calificaciones por alumno
                while ($row = $unidades->fetch_assoc()):
                    // Calcular el promedio
                    $promedio = ($row['unidad1'] + $row['unidad2'] + $row['unidad3'] + $row['unidad4']) / 4;
                    // Determinar el estatus
                    $estatus = ($promedio >= 60) ? 'Aprobado' : 'Reprobado';
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nombre'] . " " . $row['Apellido']); ?></td>
                    <td><?= htmlspecialchars($row['nombre_materia']); ?></td>
                    <td><?= number_format($row['unidad1'] ?? 0, 2); ?></td>
                    <td><?= number_format($row['unidad2'] ?? 0, 2); ?></td>
                    <td><?= number_format($row['unidad3'] ?? 0, 2); ?></td>
                    <td><?= number_format($row['unidad4'] ?? 0, 2); ?></td>
                    <td><?= htmlspecialchars($estatus); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Botón para descargar el reporte Excel -->
        <form method="POST" action="descargar_reporte.php">
            <input type="hidden" name="id_grupo" value="<?= $id_grupo; ?>">
            <button type="submit">Descargar Reporte Excel</button>
        </form>

    <?php elseif ($id_grupo): ?>
        <p>No se encontraron datos para este grupo.</p>
    <?php endif; ?>
</body>
</html>





