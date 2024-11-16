<?php
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location:../../index.php');
        exit;
    } else {
        $user = htmlspecialchars(trim($_POST['user']));
        $pwd = htmlspecialchars(trim($_POST['pwd']));
        $errores = "";
        require_once '../../functions/validaCampo.php';
        if(validaCampo($user)){
            $errores .= ($errores === "") ? '?userVacio=true' : '&userVacio=true';
        } else if(!ctype_alpha($user)){
            $errores .= ($errores === "") ? '?userMal=true' : '&userMal=true';
        }
        if(validaCampo($pwd)){
            $errores .= ($errores === "") ? '?pwdVacio=true' : '&pwdVacio=true';
        } else if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/', $pwd)){
            $errores .= ($errores === "") ? '?pwdMal=true' : '&pwdMal=true';
        }
        
        if($errores !== ''){
            $datosRecibidos = array(
                'user' => $user,
                'pwd' => $pwd
            );
            $datosDevolver = http_build_query($datosRecibidos);
            header('Location:../../index.php'.$errores.'&error=1&'.$datosDevolver);
            exit();
        } else {
            echo "<form method='POST' action='../../procesos/login.php' id='comprobacionCheck'>";
                echo "<input type='hidden' id='user' name='user' value='$user'>";
                echo "<input type='hidden' id='pwd' name='pwd' value='$pwd'>";
            echo "</form>";
            echo "<script>document.getElementById('comprobacionCheck').submit();</script>";
        }
    }