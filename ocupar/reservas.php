<?php
session_start();
include_once '../procesos/conexion.php';

// Obtener los filtros de la solicitud
$fechaFiltro = isset($_GET['fecha']) ? mysqli_real_escape_string($conn, $_GET['fecha']) : '';
$salaFiltro = isset($_GET['sala']) ? intval($_GET['sala']) : '';
$mesaFiltro = isset($_GET['mesa']) ? intval($_GET['mesa']) : '';
$camareroFiltro = isset($_GET['camarero']) ? intval($_GET['camarero']) : '';

// Construir la consulta con filtros
$sql = "SELECT r.id_reserva, m.id_mesa, s.nombre_sala AS sala, e.nombre AS estado, r.fecha_inicio, r.fecha_fin, c.nombre AS camarero
        FROM reserva r
        JOIN mesa m ON r.id_mesa = m.id_mesa
        JOIN sala s ON m.id_sala = s.id_sala
        JOIN estados e ON m.id_estado = e.id_estado
        JOIN camarero c ON r.id_camarero = c.id_camarero
        WHERE 1=1";

if ($fechaFiltro) {
    $sql .= " AND DATE(r.fecha_inicio) = '$fechaFiltro'";
}
if ($salaFiltro) {
    $sql .= " AND m.id_sala = $salaFiltro";
}
if ($mesaFiltro) {
    $sql .= " AND r.id_mesa = $mesaFiltro";
}
if ($camareroFiltro) {
    $sql .= " AND r.id_camarero = $camareroFiltro";
}

$sql .= " ORDER BY r.fecha_inicio ASC";

$result = mysqli_query($conn, $sql);

// Obtener listas para filtros
$salas = mysqli_query($conn, "SELECT * FROM sala");
$camareros = mysqli_query($conn, "SELECT * FROM camarero");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Lista de Reservas</h2>
        <form action="" method="GET" class="row g-3 mb-4">

            <div class="col-md-3">
                <select name="camarero" id="camarero" class="form-select">
                    <option value="">Seleccionar Camarero</option>
                    <?php while ($row = mysqli_fetch_assoc($camareros)) { ?>
                        <option value="<?php echo $row['id_camarero']; ?>" <?php if ($camareroFiltro == $row['id_camarero']) echo 'selected'; ?>>
                            <?php echo $row['nombre']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="sala" id="sala" class="form-select">
                    <option value="">Seleccionar Sala</option>
                    <?php while ($row = mysqli_fetch_assoc($salas)) { ?>
                        <option value="<?php echo $row['id_sala']; ?>" <?php if ($salaFiltro == $row['id_sala']) echo 'selected'; ?>>
                            <?php echo $row['nombre_sala']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="mesa" id="mesa" class="form-select">
                    <option value="">Seleccionar Mesa</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" name="fecha" class="form-control" value="<?php echo $fechaFiltro; ?>" placeholder="Fecha">
            </div>

            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="./reservas.php" class="btn btn-secondary">Limpiar Filtros</a>
                <a href="./index.php" style="color:white; text-decoration:none;" class="btn btn-danger">volver</a>
            </div>

        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Mesa</th>
                    <th>Sala</th>
                    <th>Estado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id_mesa']; ?></td>
                            <td><?php echo $row['sala']; ?></td>
                            <td><?php echo $row['estado']; ?></td>
                            <td><?php echo $row['fecha_inicio']; ?></td>
                            <td><?php echo $row['fecha_fin']; ?></td>
                            <td>
                                <a href="editar_reserva.php?id=<?php echo $row['id_reserva']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="eliminar_reserva.php?id=<?php echo $row['id_reserva']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta reserva?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron reservas</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const salaSelect = document.getElementById('sala');
        const mesaSelect = document.getElementById('mesa');
        const mesaFiltro = "<?php echo $mesaFiltro; ?>";

        // Función para cargar las mesas según la sala seleccionada
        function cargarMesas(salaId, mesaSeleccionada) {
            mesaSelect.innerHTML = '<option value="">Seleccionar Mesa</option>';

            if (salaId) {
                fetch(`obtener_mesas.php?sala=${salaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(mesa => {
                            const option = document.createElement('option');
                            option.value = mesa.id_mesa;
                            option.textContent = `Mesa ${mesa.id_mesa}`;
                            if (mesaSeleccionada && mesaSeleccionada == mesa.id_mesa) {
                                option.selected = true;
                            }
                            mesaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error al obtener las mesas:', error));
            }
        }

        // Cargar mesas al cambiar la sala seleccionada
        salaSelect.addEventListener('change', () => {
            cargarMesas(salaSelect.value, null);
        });

        // Cargar mesas al cargar la página si hay una sala seleccionada
        if (salaSelect.value) {
            cargarMesas(salaSelect.value, mesaFiltro);
        }
    </script>
</body>

</html>
<?php
mysqli_close($conn);
?>