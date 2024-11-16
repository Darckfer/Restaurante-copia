<?php
session_start();
if (isset($_SESSION['success']) && $_SESSION['success']) {
    unset($_SESSION['success']);
    $user = htmlspecialchars($_SESSION['usuario']);
    echo "<script>let loginSuccess = true; let user='$user';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="body2">
    <?php include './header.php' ?>
    <div class="container">
        <br>
        <br>
        <p>Que quieres hacer?</p>
        <br>
        <button><a href="./ocupar">Ocupar una mesa</a></button>
        <br>
        <button><a href="./reservar">Reservar</a></button>
        <br>
        <button><a href="./camareros">Etidar camareros</a></button>
        <br>
        <button><a href="./mesas">Etidar mesas</a></button>
        <br>
        <button><a href="./stock">Editar stock de sillas</a></button>
    </div>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
<script>
    if (typeof loginSuccess !== 'undefined' && loginSuccess) {
        swal.fire({
            title: 'Sesion iniciada',
            text: "Bienvenido " + user + "!",
            icon: 'success'
        })
    }
</script>