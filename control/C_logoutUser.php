<?php
session_start();
if (isset($_SESSION["loggedUser"])) {
    unset($_SESSION["loggedUser"]);
    unset($_SESSION["idUser"]);
    unset($_SESSION["nameUser"]);
}

if (isset($_SESSION["loggedAdmin"])) {
    unset($_SESSION["loggedAdmin"]);
    unset($_SESSION["idAdmin"]);
}

setcookie("id", "", time(), "/");
setcookie('cart', "", time(), "/");
setcookie('aceite', "", time(), "/");

session_destroy();
header("Location: ../index.php");
