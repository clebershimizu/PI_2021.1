<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_user.php';
    $user = new User();
    $user->setEmail($_POST["userEmail"]);
    $user->setPassword($_POST["userPassword"]);

    $result = $user->searchLogin($conn);

    if ($result->num_rows > 0) {
        //LOGADO
        $user = $result->fetch_assoc();

        session_start();

        $_SESSION["loggedUser"] = True;
        $_SESSION["idUser"]     = $user["id"];
        $_SESSION["nameUser"]   = aes_256("decrypt", $user["name"]);

        header("Location: ../index.php");
        exit();
    } else {

        //CREDENCIAIS INCORRETAS
        $msg = "Dados incorretos. Tente Novamente";
        header("Location: ../userLogin.php?erro={$msg}");
        exit();
    }
} else {

    $msg = "Acesso Negado.";
    header("Location: ../userLogin.php?erro={$msg}");
    exit();
}
