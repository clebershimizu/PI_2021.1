<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    $id = $_SESSION["idUser"];

    require_once '../model/M_user.php';

    try {
        $user = new User();
        $user->setId($id);
        $user->setName($_POST["name"]);
        $user->setEmail($_POST["email"]);
        // $user->setPassword($_POST["password"]);
        $user->setCNPJ_CPF($_POST["cnpj_cpf"]);
        $user->setCEP($_POST["cep"]);
        $user->setNumber($_POST["number"]);
        $user->setComplement($_POST["complemento"]);

        $user->updateUserData($conn);

        header("Location: ../userAccount.php");
    } catch (Exception $e) {
        $msg = $e->getMessage();
        header("Location: ../userAccount.php?erro={$msg}");
    }
}
