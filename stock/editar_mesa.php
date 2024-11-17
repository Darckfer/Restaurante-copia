<?php
session_start();
include_once '../procesos/conexion.php';

// Comprobamos si se ha pasado el id de la mesa en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idMesa = intval($_GET['id']);

    // Recuperamos los datos de la mesa
    $sql = "SELECT m.*, s.nombre as estado_nombre
            FROM mesa m 
            INNER JOIN estados s ON m.id_estado = s.id_estado
            WHERE m.id_mesa = $idMesa";

    $result = mysqli_query($conn, $sql);

    $mesa = mysqli_fetch_assoc($result);
}

// Comprobamos si se ha enviado el formulario para actualizar la mesa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMesa = $_POST['id_mesa'];
    $estado = $_POST['estado'];
    $numSillas = $_POST['num_sillas'];

    $updateSql = "UPDATE mesa SET id_estado = $estado, num_sillas = $numSillas WHERE id_mesa = $idMesa";

    if (mysqli_query($conn, $updateSql)) {
        header('location:index.php');
    }
}

$estados = mysqli_query($conn, "SELECT * FROM estados");

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Editar Mesa <?php echo $mesa['id_mesa']; ?></h2>
        <form action="editar_mesa.php" method="POST">
            <input type="hidden" name="id_mesa" value="<?php echo $mesa['id_mesa']; ?>">

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <?php while ($estado = mysqli_fetch_assoc($estados)) { ?>
                        <option value="<?php echo $estado['id_estado']; ?>" <?php if ($mesa['id_estado'] == $estado['id_estado']) echo 'selected'; ?>>
                            <?php echo $estado['nombre']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="num_sillas" class="form-label">Número de Sillas</label>
                <input type="number" name="num_sillas" id="num_sillas" class="form-control" value="<?php echo $mesa['num_sillas']; ?>" required>
            </div>

            <div class="mb-3 text-end">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>

<?php
// Cerrar la conexión
mysqli_close($conn);
?>