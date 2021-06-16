<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding: 8rem 8rem 8rem 8rem;
        }
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
        <div class="logo">
            <img src="img/gmsLogo.jpeg" alt="gms Logo" width=100>
        </div>
        
    <h2>Bem vindo. Por favor, faça seu login.</h2><br>

    <form action="control/C_loginAdmin.php" method="POST">
        <label for="username">Nome de Usuário</label><br>
        <input type="text" name="username"><br>
        <br>

        <label for="userPassword">Senha</label><br>
        <input type="password" name="adminPassword"><br>
        <br>
        <input type="submit" value="ENVIAR" />
    </form>
</body>

</html>