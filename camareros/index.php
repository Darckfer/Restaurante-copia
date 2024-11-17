<?php
include_once '../procesos/conexion.php';

$sql = "SELECT * FROM camarero";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Camareros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Lista de Camareros</h2>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <tr>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['usuario']; ?></td>
                            <td>
                                <a href="editar_camarero.php?id=<?php echo $row['id_camarero']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="eliminar_camarero.php?id=<?php echo $row['id_camarero']; ?>"
                                    class="btn btn-sm btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron camareros
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <a href="../preinicio.php" class="btn btn-secondary">Volver</a>
    </div>
</body>

</html>
<?php
mysqli_close($conn);
?>