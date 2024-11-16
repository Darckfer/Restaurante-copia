<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" integrity="sha256-qWVM38RAVYHA4W8TAlDdszO1hRaAq0ME7y2e9aab354=" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100..900;1,100..900&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


</head>
<body>

    <div id="contenedor">
    <form action="validations/php/verifLogin.php" method="post">

        <div id="pequeño">
            <img src="img/logoRestaurante.png" alt="Logo" id="logo">
        </div>
        <div id="grande">
  <div class="form-group">
    <label for="user">Usuario:</label>
    <input type="text" id="user" name="user" class="form-control" placeholder="Introduce el usuario..." value="julio">
    <span id="errorUser" class="error"></span><br>
    <?php if(isset($_GET['userVacio'])){ echo "<span class='error'>Campo vacio.</span>"; } ?>
    <?php if(isset($_GET['userMal'])){ echo "<span class='error'>Campo mal introducido</span>"; } ?>
  </div>
  <div class="form-group">
    <label for="pwd">Contraseña</label>
    <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Introduce la contraseña..." value="asdASD123">
    <span id="errorPwd" class="error"></span><br>
    <?php if(isset($_GET['pwdVacio'])){ echo "<span class='error'>Campo vacio.</span>"; } ?>
    <?php if(isset($_GET['pwdMal'])){ echo "<span class='error'>Campo mal introducido.</span>"; } ?>
  </div>
  <button type="submit" class="btn btn-primary">Siguiente</button>
  </div>

</form>
</div>


<script type="text/javascript" src="validations/js/validaLogin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
<script>
  const urlParams = new URLSearchParams(window.location.search);
  if(urlParams.get('error') == '5'){
    swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Datos incorrectos!'
    })
  }
</script>
</body>
</html>