<?php
session_start();
if ($_SESSION["loggedUser"] = True) {
    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();
    require_once '../model/M_user.php';
    $user = new User();
    $user->setId($_SESSION["idUser"]);
    $user->deleteUserData($conn);

    header('Location: logoutUser.php');
    exit();
} else {
    header("Location: ../index.php");
}
