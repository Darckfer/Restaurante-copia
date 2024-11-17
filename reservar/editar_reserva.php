<?php
include_once '../procesos/conexion.php';

$idReserva = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fechaInicio = $_POST['fecha_inicio'];
    $fechaFin = $_POST['fecha_fin'];
    $mesaId = $_POST['mesa'];

    $sqlUpdate = "UPDATE reserva SET fecha_inicio='$fechaInicio', fecha_fin='$fechaFin', id_mesa=$mesaId WHERE id_reserva=$idReserva";
    if (mysqli_query($conn, $sqlUpdate)) {
        header("Location: reservas.php?msg=Reserva actualizada");
    } else {
        echo "Error al actualizar la reserva: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM reserva WHERE id_reserva = $idReserva";
$result = mysqli_query($conn, $sql);
$reserva = mysqli_fetch_assoc($result);

$mesas = mysqli_query($conn, "SELECT * FROM mesa");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Editar Reserva</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="mesa" class="form-label">Mesa</label>
                <select name="mesa" class="form-select">
                    <?php while ($row = mysqli_fetch_assoc($mesas)) { ?>
                        <option value="<?php echo $row['id_mesa']; ?>" <?php if ($reserva['id_mesa'] == $row['id_mesa']) echo 'selected'; ?>>
                            Mesa <?php echo $row['id_mesa']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($reserva['fecha_inicio'])); ?>">
            </div>
            <div class="mb-3">
                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                <input type="datetime-local" name="fecha_fin" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($reserva['fecha_fin'])); ?>">
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="index_reservas.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>

</html>
<?php
mysqli_close($conn);
?>