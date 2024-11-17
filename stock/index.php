<?php
session_start();
include_once '../procesos/conexion.php';

$sql = "SELECT * FROM stock";

$result = mysqli_query($conn, $sql);

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

        <a href="../preinicio.php" style="color:white; text-decoration:none;" class="btn btn-danger">volver</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nº sillas</th>
                    <th>accion</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td><?php echo $row['sillas_stock']; ?></td>
                            <td>
                                <a href="editar_mesa.php?id=<?php echo $row['idStock']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <!-- <a href="eliminar_mesa.php?id=<?php echo $row['idStock']; ?>" class="btn btn-sm btn-danger">Eliminar</a> -->
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

</body>

</html>
<?php
mysqli_close($conn);
?>