<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_user.php';

    try {
        $user = new User();
        $user->setName($_POST["name"]);
        $user->setEmail($_POST["email"]);
        $user->setPassword($_POST["password"]);
        $user->setCNPJ_CPF($_POST["cnpj_cpf"]);
        $user->setCEP($_POST["cep"]);
        $user->setNumber($_POST["number"]);
        $user->setComplement($_POST["complemento"]);

        $result = $user->registerUser($conn);
        header("Location: ../userLogin.php");
    } catch (Exception $e) {
        $msg = $e->getMessage();
        header("Location: ../userRegister.php?erro={$msg}");
    }
}
