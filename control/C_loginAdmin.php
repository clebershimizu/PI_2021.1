<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_Admin.php';
    $admin = new Admin();
    $admin->setUsername($_POST["username"]);
    $admin->setPassword($_POST["adminPassword"]);

    $result = $admin->searchLogin($conn);

    if ($result->num_rows > 0) {
        //LOGADO
        $user = $result->fetch_assoc();

        session_start();

        $_SESSION["loggedAdmin"] = True;
        $_SESSION["idAdmin"]     = $user["id"];

        header("Location: ../admin.php");
        exit();
    } else {

        //CREDENCIAIS INCORRETAS
        $msg = "Dados incorretos. Tente Novamente";
        header("Location: ../adminLogin.php?erro={$msg}");
        exit();
    }
} else {

    $msg = "Acesso Negado.";
    header("Location: ../adminLogin.php?erro={$msg}");
    exit();
}
