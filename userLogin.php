<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form action="control/C_loginUser.php" method="POST">
        <label for="userEmail">Email</label><br>
        <input type="email" name="userEmail"><br>
        <br>

        <label for="userPassword">Senha</label><br>
        <input type="password" name="userPassword"><br>
        <br>
        <input type="submit" value="ENVIAR" />
    </form>
</body>

</html>