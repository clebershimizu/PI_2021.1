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

        if (!$user->haveOrders($conn)) {
            $msg = "Informações alteradas com sucesso";
        } else {
            $msg = "Não é possível alterar o seu CNPJ / CPF enquanto existir um pedido pago em andamento. As outras informações foram alteradas com sucesso";
        }

        $user->updateUserData($conn);

        header("Location: ../userAccount.php?msg={$msg}");
    } catch (Exception $e) {
        $msg = $e->getMessage();
        header("Location: ../userAccount.php?erro={$msg}");
    }
}
