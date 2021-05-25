<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>

<body>
    <form action="control/C_registerUser" method="POST">
        <label for="name">Nome</label> <br>
        <input type="text" name="name"><br>
        <br>

        <label for="email">Email</label><br>
        <input type="email" name="email"><br>
        <br>

        <label for="password">Senha</label><br>
        <input type="password" name="password"><br>
        <br>

        <label for="cpf_cnpj">CNPJ_CPF</label><br>
        <input type="text" name="cnpj_cpf"><br>
        <br>

        <label for="cep">CEP</label><br>
        <input type="text" name="cep"><br>
        <br>

        <label for="number">Numero</label><br>
        <input type="text" name="number"><br>
        <br>

        <label for="complement">Complemento</label><br>
        <input type="text" name="complemento"><br>
        <br>

        <input type="submit" value="Cadastrar!">
    </form>
</body>

</html>