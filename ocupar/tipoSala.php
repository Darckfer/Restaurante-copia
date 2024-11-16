<?php
session_start();
include_once '../procesos/conexion.php';
if (!isset($_SESSION['id_camarero'])) {
    header('Location: ../index.php');
    exit();
}
if (!isset($_POST['id_tipoSala'])) {
    header('Location: ./index.php');
    exit();
} else {
    $id = mysqli_real_escape_string($conn, $_POST['id_tipoSala']);
    $query = "SELECT * FROM sala WHERE id_tipoSala = ?";
    $stmtq = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmtq, $query);
    mysqli_stmt_bind_param($stmtq, "i", $id);
    mysqli_stmt_execute($stmtq);
    $result = mysqli_stmt_get_result($stmtq);
    $user = mysqli_fetch_assoc($result);
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Selección de sala</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css"> <!-- Asegúrate de que la ruta es correcta -->
    </head>


    <body class="body2">
        <?php require_once '../header.php';?>
        <a href="./index.php">
            <button class="btn btn-danger">Volver</button>
        </a>
        <div class="container">
            <h1 id="ubicacion">Selecciona una ubicación!</h1>
            <div class="row"> <!-- Agregado para crear un nuevo row de Bootstrap -->

<?php
                $numero = mysqli_num_rows($result);
                $nuevoNumero = 4;
            switch($numero) {
                case 1: $nuevoNumero = 6;
                break;
                case 2: $nuevoNumero = 4;
                break;
                case 3: $nuevoNumero = 4;
                break;
                case 4: $nuevoNumero = 3;
                break;
                case 5: $nuevoNumero = 2;
                break;
                case 6: $nuevoNumero = 1;
                break;
            }
        foreach ($result as $fila) {
            echo "<div class='col-md-$nuevoNumero mb-4'>"; // Clase Bootstrap para cuatro columnas
            echo "<div class='container_img grow'>";
            // echo "<a href='mesa.php?id=" . $fila['id_tipoSala'] . "'><img src='../img/" . $fila['nombre_sala'] . ".jpg' alt=''></a>";
            ?>
                    <form class="formImg" action="mesa.php" method="post">
                        <input type="hidden" name="id_sala"  value="<?php echo $fila['id_sala'] ?>">
                        <input type="hidden" name="id_tipoSala"  value="<?php echo $fila['id_tipoSala'] ?>">
                        <button class="botonImg" type="submit"><img src="../img/<?php echo $fila['nombre_sala'] ?>.jpg" alt=""></button>
                        <!-- <input type="submit" value="Enviar"> -->
                    </form>
                    <?php
            echo "</div>";
            echo "<label class=labelTipo>" . $fila['nombre_sala'] . "</label>";
            echo "</div>";
        }
?>
            </div> <!-- Cierre del row -->
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <footer></footer>
    </body>

    </html>

<?php
    mysqli_stmt_close($stmtq);
    mysqli_close($conn);
}
?>