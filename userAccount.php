<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$docroot = $_SERVER['DOCUMENT_ROOT'];
require_once "{$docroot}/PI_2021.1/lib/crypto.php";

if ($_SESSION["loggedUser"]) {

    require_once 'Model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once 'Model/M_user.php';
    $user = new User();
    $user->preencher($conn, $_SESSION["idUser"]);
} else {
    $msg = "Acesso Negado.";
    header("Location: userLogin.php?erro={$msg}");
    exit();
}

$query =    "SELECT * FROM pedido WHERE fk_user_id = ? AND status > 1";
$stmt = $conn->prepare($query);
@$stmt->bind_param("i", $_SESSION['idUser']);
$stmt->execute();
$ordersCheck = $stmt->get_result();

//REGRA DE NEGÓCIO = se há pedido
$temPedidoPago = false;
if ($ordersCheck->num_rows > 0) {
    $temPedidoPago = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="lib/bootstrap/exemplos-do-bootstrap/sign-in/signin.css" /> -->
    <!-- CSS para ícones -->
    <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <title>Conta</title>
    <script>
        var d = document
        function confirmDeletion() {
            if(window.confirm("Tem certeza que deseja deletar a sua conta?")) {
                d.getElementById('accDelete').submit()
            }
        }
    </script>
    <style>
        .strength0 {
            height: 20px;
            width: 40px;
            background-color: #F55;
        }

        .strength1 {
            height: 20px;
            width: 80px;
            background-color: #DF7401;
        }

        .strength2 {
            height: 20px;
            width: 120px;
            background-color: #FFFF00;
        }

        .strength3 {
            height: 20px;
            width: 160px;
            background-color: #9AFE2E;
        }

        .strength4 {
            height: 20px;
            width: 200px;
            background-color: #0B610B;
        }

        .strength5 {
            height: 20px;
            width: 240px;
            background-color: #0B610F;
        }


        body {
            height: 100%;
        }

        body {

            align-content: center;
            flex-wrap: wrap;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .header-new {
            align-content: center;
        }

        .form-signin {
            width: 100%;
            max-width: 630px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>


</head>

<body class="mt-0 mb-0 pt-0 pb-0">
    <?php include "view/header.php";  ?>

    <div class="form-signin">
        <form action="control/C_updateUserData.php" method="POST">

            <!-- Buscar pela id -->

            <h1 class="h1 mb-3 fw-normal">Olá <?= $_SESSION["nameUser"] ?>! Seja bem-vindo.</h1>
            <a href="index.php">Voltar</a>
            <br>
            <label for="name">Nome</label> <br>
            <input type="text" name="name" class="form-control" value="<?= aes_256("decrypt", $user->getName()) ?>"><br>
            <br>

            <label for="email">Email</label><br>
            <input type="email" name="email" class="form-control" value="<?= aes_256("decrypt", $user->getEmail()) ?>"><br>
            <br>

            <!-- <label for="password">Senha</label><br>
            <input type="password" name="password" onkeyup="passwordStrength(this.value)" maxlength="30" minlength="8" class="form-control">
            <span class="small">No mímino 8 caracteres. Procure usar maiúsculas, minúsculas, números e símbolos.</span>
            <br>
            <div id="passwordDescription">Nenhuma senha digitada</div>
            <div id="passwordStrength" class="strength0"></div><br>

            <label for="password-confirm">Repita a senha</label><br>
            <input type="password" name="password-confirm" onkeyup="passwordMatch(this.value)" maxlength="30" minlength="8" class="form-control"><br>
            <br> -->



            <label for="cpf_cnpj">CNPJ_CPF</label><br>
            <input type="text" name="cnpj_cpf" class="form-control" value="<?= aes_256("decrypt", $user->getCNPJ_CPF()) ?>" <?= ($temPedidoPago) ? "disabled" : "" ?>><br>
            <br>

            <label for="cep">CEP</label><br>
            <input type="text" name="cep" class="form-control" value="<?= aes_256("decrypt", $user->getCEP()) ?>"><br>
            <br>

            <!-- <label for="address">Endereço</label><br>
            <input type="text" name="address" class="form-control"><br>
            <br> -->

            <label for="number">Número</label><br>
            <input type="text" name="number" class="form-control" value="<?= aes_256("decrypt", $user->getNumber()) ?>"><br>
            <br>

            <label for="complement">Complemento</label><br>
            <input type="text" name="complemento" class="form-control" value="<?= aes_256("decrypt", $user->getComplement()) ?>"><br>
            <br>

            <!-- <label for="city">Cidade</label><br>
            <input type="text" name="city" class="form-control"><br>
            <br>

            <label for="state">Estado</label><br>
            <input type="text" name="state" class="form-control"><br>
            <br> -->

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="terms-accept" required> Declaro estar ciente dos <a href="privacy.html">Termos de privacidade</a> e concordar com o uso dos meus dados.
                </label>
            </div>

            <!-- <button class="w-100 btn btn-lg btn-primary" type="submit">Atualizar cadastro</button> -->
            <input class="w-100 btn btn-lg btn-primary" type="submit" value="Atualizar cadastro">
            <hr>
            
        </form>
        <form id="accDelete" name="accDelete" action="control/C_deleteUserData.php" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta?');">
            <input type="submit" class="w-100 btn btn-lg btn-primary" value="Deletar Conta">
        </form>
    </div>


    <script>
        function passwordStrength(password) {
            var desc = new Array();
            desc[0] = "Muito Fraca";
            desc[1] = "Muito Fraca";
            desc[2] = "Fraca";
            desc[3] = "Médio";
            desc[4] = "Forte";
            desc[5] = "Muito Forte";
            var score = 0;
            //if password bigger than 8 give 1 point
            if (password.length > 8) score++;
            //if password has both lower and uppercase characters give 1 point  
            if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) score++;
            //if password has at least one number give 1 point
            if (password.match(/\d+/)) score++;
            //if password has at least one special caracther give 1 point
            if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) score++;
            //if password bigger than 12 give another 1 point
            if (password.length > 12) score++;
            document.getElementById("passwordDescription").innerHTML = desc[score];
            document.getElementById("passwordStrength").className = "strength" + score;
        }
    </script>
<?php include "view/footer.php"; ?>
</body>



</html>