<?php
include_once '../procesos/conexion.php';

// Verificar si se ha proporcionado un ID válido
$idCamarero = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM camarero WHERE id_camarero = $idCamarero";
$result = mysqli_query($conn, $sql);
mysqli_num_rows($result);
$camarero = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password_real = $_POST['password_real'];

    // Validar los datos
    if (!password_verify($_POST['password'], $password_real)) {
        $password_real = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }

    // Usar prepared statements para actualizar los datos
    $sqlUpdate = "UPDATE camarero SET nombre = ?, usuario = ?, password = ? WHERE id_camarero = ?";
    $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);

    if ($stmtUpdate) {
        mysqli_stmt_bind_param($stmtUpdate, "sssi", $nombre, $usuario, $password_real, $idCamarero);
        if (mysqli_stmt_execute($stmtUpdate)) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error al actualizar el camarero: " . mysqli_error($conn);
        }
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conn);
    }
}
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Camarero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Editar Camarero</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($camarero['nombre']); ?>">
            </div>
            <div class="mb-3">
                <label for="usuario" class="form-label">usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($camarero['usuario']); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">password</label>
                <input type="password" class="form-control" id="password" name="password">
                <input type="hidden" class="form-control" id="password_real" name="password_real" value="<?php echo htmlspecialchars($camarero['password']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="index_camareros.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>