
<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_user.php';
    $user = new User();
    $user->setEmail($_POST["email"]);
    $user->setPassword($_POST["password"]);

    $result = $user->searchLogin($conn);

    if ($result->num_rows > 0) {

        //LOGADO

        session_start();

        $_SESSION["loggedUser"] = True;
        $_SESSION["idUser"]     = $result["id"];
        $_SESSION["nameUser"]   = $result["name"];

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
