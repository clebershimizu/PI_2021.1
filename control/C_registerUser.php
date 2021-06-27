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
        if ($result) {
            $msg = "Cadastro realizado com sucesso";
            header("Location: ../userLogin.php?success={$msg}");
        } else {
            $msg = "Já existe uma conta com este E-mail e/ou CPF/CNPJ";
            header("Location: ../userRegister.php?erro={$msg}");
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        header("Location: ../userRegister.php?erro={$msg}");
    }
}
