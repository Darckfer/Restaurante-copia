<?php
session_start();
include_once '../procesos/conexion.php';

$fechaFiltro = isset($_GET['fecha']) ? mysqli_real_escape_string($conn, $_GET['fecha']) : '';
$salaFiltro = isset($_GET['sala']) ? intval($_GET['sala']) : '';
$mesaFiltro = isset($_GET['mesa']) ? intval($_GET['mesa']) : '';

$sql = "SELECT m.*, s.*
        FROM mesa m inner join estados s on s.id_estado = m.id_estado
        WHERE 1=1";

if ($salaFiltro) {
    $sql .= " AND id_sala = $salaFiltro";
}
if ($mesaFiltro) {
    $sql .= " AND id_mesa = $mesaFiltro";
}

$sql .= " ORDER BY id_mesa ASC";

$result = mysqli_query($conn, $sql);

$salas = mysqli_query($conn, "SELECT * FROM sala");
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
                <select name="sala" id="sala" class="form-select">
                    <option value="">Seleccionar Sala</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($salas)) {
                    ?>
                        <option value="<?php echo $row['id_sala']; ?>" <?php if ($salaFiltro == $row['id_sala']) echo 'selected'; ?>>
                            <?php echo $row['nombre_sala']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="mesa" id="mesa" class="form-select">
                    <option value="">Seleccionar Mesa</option>
                </select>
            </div>

            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="./index.php" class="btn btn-secondary">Limpiar Filtros</a>
                <a href="../preinicio.php" style="color:white; text-decoration:none;" class="btn btn-danger">volver</a>
            </div>

        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Estado</th>
                    <th>Nº sillas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td><?php echo $row['id_mesa']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['num_sillas']; ?></td>
                            <td>
                                <a href="editar_mesa.php?id=<?php echo $row['id_mesa']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <!-- <a href="eliminar_mesa.php?id=<?php echo $row['id_mesa']; ?>" class="btn btn-sm btn-danger">Eliminar</a> -->
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron reservas</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        salaSelect = document.getElementById('sala');
        mesaSelect = document.getElementById('mesa');
        mesaFiltro = "<?php echo $mesaFiltro; ?>";

        function cargarMesas(salaId, mesaSeleccionada) {
            mesaSelect.innerHTML = '<option value="">Seleccionar Mesa</option>';

            if (salaId) {
                fetch(`./obtener_mesas.php?sala=${salaId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(mesa => {
                            option = document.createElement('option');
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

        salaSelect.addEventListener('change', () => {
            cargarMesas(salaSelect.value, null);
        });

        if (salaSelect.value) {
            cargarMesas(salaSelect.value, mesaFiltro);
        }
    </script>
</body>

</html>
<?php
mysqli_close($conn);
?>