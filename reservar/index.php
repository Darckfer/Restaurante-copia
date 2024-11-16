<?php
session_start();
include './procesos/conexion.php';

// Verificamos si el usuario ya está autenticado
if (isset($_SESSION['id_camarero'])) {
    // Consulta para obtener el nombre del usuario autenticado
    $query = "SELECT * FROM tipo_sala";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bienvenido <?php echo $_SESSION['nombre']; ?>!</title>
        <link rel="stylesheet" href="../css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <body class="body2">
        <header>
            <nav>
                <div class="sesionIniciada">
                    <p>Usuario: <?php echo $_SESSION['nombre'] ?></p>
                </div>
                <div class="cerrarSesion">
                    <a id="bug" href="./reservas.php">
                        <button type="submit" class="btn btn-light" id="cerrarSesion">reservas</button>
                    </a>
                    <a id="bug" href="./filtros.php">
                        <button type="submit" class="btn btn-light" id="cerrarSesion">Filtrar</button>
                    </a>
                    <a href="./procesos/logout.php">
                        <button type="submit" class="btn btn-dark" id="cerrarSesion">Cerrar Sesión</button>
                    </a>
                </div>
            </nav>
        </header>
        <div class="container">
            <br>
            <h3>Selecciona una sala</h3>
            <div class="row">
                <?php
                foreach ($result as $fila) {
                    echo "<div class='col-md-4 mb-4'>";
                    echo "<div class= 'container_img grow'>";
                    switch ($fila['id_tipoSala']) {
                        case '1':
                ?>
                            <form class="formImg " action="tipoSala.php" method="post">
                                <input type="hidden" name="id_tipoSala" value="<?php echo $fila['id_tipoSala'] ?>">
                                <button class="botonImg " type="submit"><img src="../img/terraza 1.webp" alt=""></button>
                            </form>
                        <?php
                            break;
                        case '2':
                        ?>
                            <form class="formImg" action="tipoSala.php" method="post">
                                <input type="hidden" name="id_tipoSala" value="<?php echo $fila['id_tipoSala'] ?>">
                                <button class="botonImg" type="submit"><img src="../img/comedor1.webp" alt=""></button>
                            </form>
                        <?php
                            break;
                        case '3':
                        ?>
                            <form class="formImg" action="tipoSala.php" method="post">
                                <input type="hidden" name="id_tipoSala" value="<?php echo $fila['id_tipoSala'] ?>">
                                <button class="botonImg" type="submit"><img src="../img/salapriv.png" alt=""></button>
                            </form>
                        <?php
                            break;
                        default:
                        ?>
                            <form class="formImg" action="tipoSala.php" method="post">
                                <input type="hidden" name="id_tipoSala" value="<?php echo $fila['id_tipoSala'] ?>">
                                <button class="botonImg" type="submit"><img src="../img/comedor1.webp" alt=""></button>
                            </form>
                <?php
                            break;
                    }
                    echo "</div>";
                    echo "<label class=labelTipo>" . $fila['tipo_sala'] . "</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </body>

    </html>

<?php
} else {
    // Usuario no autenticado - Redirigir al inicio de sesión
    header("Location: index.php");
    exit; // Siempre es buena práctica usar exit después de redirigir
}
