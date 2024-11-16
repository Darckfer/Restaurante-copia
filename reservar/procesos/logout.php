<?php
session_start(); // Iniciar la sesión

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea, también se puede destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio o a la página de inicio de sesión
header("Location: ../index.php");
exit();
?>