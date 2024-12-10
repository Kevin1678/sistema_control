<?php
include 'db_config.php';
include 'menu.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = $_POST['id_materia'];
    $id_grupo = $_POST['id_grupo'];
    $calificaciones = $_POST['calificacion'];

    foreach ($calificaciones as $id_alumno => $unidades) {
        $unidad1 = $unidades['Unidad1'];
        $unidad2 = $unidades['Unidad2'];
        $unidad3 = $unidades['Unidad3'];
        $unidad4 = $unidades['Unidad4'];

        $sql = "INSERT INTO Calificaciones (id_alumno, id_materia, Unidad1, Unidad2, Unidad3, Unidad4, fecha) 
                VALUES (?, ?, ?, ?, ?, ?, NOW()) 
                ON DUPLICATE KEY UPDATE
                Unidad1 = VALUES(Unidad1),
                Unidad2 = VALUES(Unidad2),
                Unidad3 = VALUES(Unidad3),
                Unidad4 = VALUES(Unidad4)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidddd", $id_alumno, $id_materia, $unidad1, $unidad2, $unidad3, $unidad4);
        $stmt->execute();
    }

    echo "<p>Calificaciones registradas o actualizadas con éxito.</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Calificaciones</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Registrar Calificaciones</h1>
    <form method="post">
        <label for="id_grupo">Seleccionar Grupo:</label>
        <select id="id_grupo" name="id_grupo" required>
            <option value="">-- Seleccionar un grupo --</option>
            <?php
            $sql_grupos = "SELECT * FROM Grupos";
            $result_grupos = $conn->query($sql_grupos);
            while ($row = $result_grupos->fetch_assoc()) {
                echo "<option value='{$row['id_grupo']}'>{$row['nombre_grupo']}</option>";
            }
            ?>
        </select>

        <label for="id_materia">Seleccionar Materia:</label>
        <select id="id_materia" name="id_materia" required>
            <?php
            $sql_materias = "SELECT * FROM Materias";
            $result_materias = $conn->query($sql_materias);
            while ($row = $result_materias->fetch_assoc()) {
                echo "<option value='{$row['id_materia']}'>{$row['nombre_materia']}</option>";
            }
            ?>
        </select>
        <br>

        <h2>Lista de Alumnos</h2>
        <table border="1" id="tabla_alumnos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Unidad 1</th>
                    <th>Unidad 2</th>
                    <th>Unidad 3</th>
                    <th>Unidad 4</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los alumnos se llenarán aquí con AJAX -->
            </tbody>
        </table>

        <button type="submit">Guardar Calificaciones</button>
    </form>

    <script>
        // Función para cargar los alumnos del grupo seleccionado
        $('#id_grupo').change(function() {
            var id_grupo = $(this).val();
            if (id_grupo != "") {
                $.ajax({
                    url: 'get_alumnos.php', // Archivo PHP que devuelve los alumnos
                    method: 'POST',
                    data: { id_grupo: id_grupo },
                    dataType: 'json',
                    success: function(data) {
                        var alumnosHTML = '';
                        // Generar las filas para cada alumno
                        data.forEach(function(alumno) {
                            alumnosHTML += '<tr>';
                            alumnosHTML += '<td>' + alumno.Nombre + ' ' + alumno.Apellido + '</td>';
                            alumnosHTML += '<td><input type="number" name="calificacion[' + alumno.id + '][Unidad1]" step="0.01" min="0" max="100" required></td>';
                            alumnosHTML += '<td><input type="number" name="calificacion[' + alumno.id + '][Unidad2]" step="0.01" min="0" max="100" required></td>';
                            alumnosHTML += '<td><input type="number" name="calificacion[' + alumno.id + '][Unidad3]" step="0.01" min="0" max="100" required></td>';
                            alumnosHTML += '<td><input type="number" name="calificacion[' + alumno.id + '][Unidad4]" step="0.01" min="0" max="100" required></td>';
                            alumnosHTML += '</tr>';
                        });
                        $('#tabla_alumnos tbody').html(alumnosHTML);
                    }
                });
            } else {
                $('#tabla_alumnos tbody').html('');
            }
        });
    </script>
</body>
</html>







