<?php
session_start();
unset($_SESSION["loggedUser"]);
unset($_SESSION["idUser"]);
unset($_SESSION["nameUser"]);
session_destroy();
header("Location: ../index.php");
