<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .logo {
            padding: 2rem 0 2rem 0;
            display: flex;
            max-width: 100%;
            justify-content: flex-end;
        }
    </style>

    <title>Login - Admin</title>
</head>

<body>

    <?php include "view/header.php" ?>

    <div class='container'>

        <h2 class="py-5">Bem vindo. Por favor, faça seu login.</h2><br>

        <form action="control/C_loginAdmin.php" method="POST">
            <label for="username">Nome de Usuário</label><br>
            <input type="text" name="username"><br>
            <br>

            <label for="userPassword">Senha</label><br>
            <input type="password" name="adminPassword"><br>
            <br>
            <input type="submit" value="ENVIAR" />
        </form>

    </div>
</body>

</html>