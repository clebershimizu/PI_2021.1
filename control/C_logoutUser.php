<?php
session_start();
unset($_SESSION["loggedUser"]);
unset($_SESSION["idUser"]);
unset($_SESSION["nameUser"]);

unset($_SESSION["loggedAdmin"]);
unset($_SESSION["idAdmin"]);

session_destroy();
header("Location: ../index.php");
