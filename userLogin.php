<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="lib/bootstrap/exemplos-do-bootstrap/sign-in/signin.css" /> -->

</head>

<body>

    <?php include "view/header.php" ?>

    <main class="form-signin row justify-content-center py-5">
        <div class="col-10 col-xs-10 col-sm-7 col-md-6 col-lg-5 col-xl-4 py-5">
            <form action="control/C_loginUser.php" method="POST">

                <h1 class="h1 mb-3 fw-normal">Faça seu login</h1>
                <a href="index.php">Voltar</a>

                <div class="form-floating">
                    <input type="email" name="userEmail" class="form-control" id="floatingInput" placeholder="nome@examplo.com">
                    <label for="floatingInput">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="userPassword" class="form-control" id="floatingPassword" placeholder="Senha">
                    <label for="floatingPassword">Senha</label>
                </div>

                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me"> Manter conectado
                    </label>
                </div>
                <input class="w-100 btn btn-lg btn-primary" type="submit" value="Entrar">
            </form>
        </div>
    </main>
</body>

</html>