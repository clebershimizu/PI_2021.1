<?php
session_start();
if ($_SESSION["loggedUser"] = True) {
    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_user.php';
    $user = new User();
    $user->setId($_SESSION["idUser"]);

    if (!$user->haveOrders($conn)) {
        $user->deleteUserData($conn);
        header('Location: C_logoutUser.php');
        exit();
    } else {
        $msg = "Nao e possivel excluir a conta enquanto tiver pedidos pagos em andamento";
        header("Location: ../userAccount.php?erro={$msg}");
    }
} else {
    header("Location: ../index.php");
}
