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
    <title>ADMIN - Visualizar Cotações</title>

</head>

<body>

    <h1>VISUALIZAR COTAÇÕES</h1>
    <hr>


    <h2>COTAÇÃO # xxxxxxxxx</h2>
    <p>Cliente: xxxxxx</p>

    <h3>PRODUTOS</h3>

    <h4>Camiseta XXXXX</h4>
    <p>Tamanho: G<br>
        Cor: PRETO<br>
        Costura: SIMPLES<br>
        Quantidade: 25 br <br>
        Preço Base: xx Reais
    </p>

    <h5>SERVIÇOS</h5>

    <p>{Nome do Serviço}<br>
        Tamanho: G (40cm) <br>
        Custo: xx Reais <br>
        Posição: Manga Direita <br>
    </p>

    <h4>PREÇO TOTAL: Preço Dinamico RS</h4>

    <hr>

    <!--  -->

    <?php

    require_once 'model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once 'model/M_admin.php';
    $admin = new Admin();
    $cotacoes = $admin->getCotacoes($conn);

    if ($cotacoes->num_rows > 0) {

        while ($cotacao = $cotacoes->fetch_assoc()) { ?>


            <h2>COTAÇÃO # xxxxxxxxx</h2>
            <p>Cliente: xxxxxx</p>

            <h3>PRODUTOS</h3>

            <?php
            $produtos = $admin->getProdutosDeCotacao($conn, $cotacao['id']);
            while ($cotacao = $cotacoes->fetch_assoc()) {
            ?>

                <h4>Camiseta XXXXX</h4>
                <p>Tamanho: G<br>
                    Cor: PRETO<br>
                    Costura: SIMPLES<br>
                    Quantidade: 25 br <br>
                    Preço Base: xx Reais
                </p>

                <h5>SERVIÇOS</h5>

                <p>{Nome do Serviço}<br>
                    Tamanho: G (40cm) <br>
                    Custo: xx Reais <br>
                    Posição: Manga Direita <br>
                </p>

            <?php } // FIM DO LOOP DOS PRODUTOS 
            ?>

            <h4>PREÇO TOTAL: Preço Dinamico RS</h4>

    <?php } //FIM DO LOOP DAS COTAÇÕES 

    } // FIM DA VERIFICACAO SE HÁ COTACOES
    ?>

</body>

</html>