<?php
session_start();

if (!isset($_SESSION['loggedAdmin'])) {
    $msg = "Acesso Negado.";
    header("Location: adminLogin.php?erro={$msg}");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <title>Carrinho</title>
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

</head>

<body>
    <div class="logo">
        <img src="img/gmsLogo.jpeg" alt="gms Logo" width=100>
    </div>

    <h1>Seu orçamento</h1>
    <hr>

    <h2>Produtos adicionados</h2>
    
    <table style="width:100%">
        <tr>
            <th>Item/SKU</th>
            <th>Descrição</th>
            <th>Tecido</th>
            <th>Custo Base</th>
            <th>Foto</th>
            <th>Editar</th>
            <th>Remover</th>
        </tr>
        <tr>
            <td id="id"></td>
            <td id="tipo_peca"></td>
            <td id="tecido"></td>
            <td id="base_cost"></td>
            <td id="image_url"></td>
            <td><button class="w-50 btn btn-md btn-primary" type="submit" >Editar</button></td>
            <td><button class="w-50 btn btn-md btn-danger" type="submit" >Remover</button></td>
        </tr>
        
    </table>

    <div>
    <a href="/product.php">Adicionar produtos ao orçamento</a>
       
    </div>

    <!-- SOME PHP WITCHCRAFT ADN STUFF -->

    

</body>

</html>