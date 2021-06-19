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
    <title>Dashboard</title>

    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet"> -->
    <style>
        /* body {
            font-family: 'Nunito', sans-serif;
            padding: 8rem 8rem 8rem 8rem;
        } */

        .andamento {
            background: rgba(76, 175, 80, 0.1);
        }

        .pendentes {
            background: rgba(235, 127, 94, 0.1);
        }

        .logo {
            padding: 2rem 0 2rem 0;
            display: flex;
            max-width: 100%;
            justify-content: flex-end;
        }
    </style>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">
    <!-- <div class="logo">
        <img src="img/gmsLogo.jpeg" alt="gms Logo" width=100>
    </div> -->

    <?php include "view/header.php"; ?>

    <main class="container">
        <div>
            <h1 class="py-5">Gestão de orçamentos e pedidos</h1>
        </div>
        <hr>
        <br>
        <div class="pendentes">
            <h2>Solicitações de orçamento aguardando validação:</h2>
            <br>

            <?php

            require_once 'model/M_connection.php';
            $dbConn = new Connection();
            $conn = $dbConn->connect();

            require_once 'model/M_admin.php';
            require_once 'model/M_pedido.php';
            $admin = new Admin();

            //ESTA É A FORMA DE CHAMAR UM MÉTODO ESTÁTICO
            $pedidos = Pedido::getPedidosNaoOrcados($conn);


            //LOOP DAS COTAÇÕES
            if ($pedidos) {

                foreach ($pedidos as $pedido) {

                    $idPedido = $pedido->getId();
                    $cliente = mb_strtoupper(aes_256("decrypt", ($pedido->getUser())->getName()), 'UTF-8');
                    $cnpj_cpf = aes_256("decrypt", ($pedido->getUser())->getCnpj_cpf());
                    $precoAuto = number_format($pedido->computarTotal($conn), 2, ',', '.');
            ?>

                    <div class="cotacoes">
                        <h3>Cotação # <?= $idPedido ?></h3>
                        <p>Cliente: <?= $cliente ?><br>
                            CNPJ_CPF: <?= $cnpj_cpf ?><br><br>
                            Preço Automático: R$ <?= $precoAuto ?> <br>
                            PREÇO ORÇADO: Cotação ainda não orçada.
                        </p>
                    </div>

                    <div id="produtos-<?= $pedido->getId() ?>" style="margin-left:20px;">
                        <h3>Produtos</h3>

                        <?php

                        //LOOP DOS PRODUTOS

                        $produtos = $pedido->getProdutos($conn);
                        //Aqui são PEDIDO_PRODUTOS... extensão de apenas PRODUTOS. (conferir M_product.php)

                        foreach ($produtos as $prod) {

                            $peca = $prod->getTipoPeca();
                            $tecido = mb_strtoupper($prod->getTecido(), 'UTF-8');
                            $tamanho = $prod->getTamanho();
                            $mod_t = $prod->getModTamanho();
                            $cor = $prod->getCor();
                            $costura = $prod->getCostura();
                            $mod_c = $prod->getModCostura();
                            $qtde = $prod->getQtdeProdutos();
                            $base_cost = $prod->getBaseCost();
                        ?>
                            <p><b><?= $peca ?></b><br>
                                Tecido: <?= $tecido ?><br>
                                Tamanho: <?= $tamanho ?> <small>(x<?= $mod_t ?>)</small><br>
                                Cor: <?= $cor ?><br>
                                Costura: <?= $costura ?> <small>(x<?= $mod_c ?>)</small><br>
                                Quantidade: <?= $qtde ?><br>
                                Preço Base: R$ <?= $base_cost ?>
                            </p>

                            <?php
                            //LOOP DOS SERVIÇOS

                            $servicos = $prod->getServicos($conn);
                            while ($servico = $servicos->fetch_assoc()) {
                            ?>
                                <div id="prod-<?= $prod->getIdPedidoProduto() ?>-serv-<?= $servico['id'] ?>" style="margin-left:20px;">
                                    <p><b>Serviço: <?= $servico['desc'] ?></b><br>
                                        Tamanho: <?= $servico['tamanho'] ?> (<?= $servico['desc_tamanho'] ?>) <br>
                                        Custo: R$ <?= $servico['preco'] ?> <br>
                                        Posição: <?= $servico['posicao'] ?> <br>
                                        Comentários: <?= $servico['comment'] ?>
                                    </p>
                                </div>

                            <?php } // FIM DO LOOP DOS SERVIÇOS 
                            ?>

                        <?php } // FIM DO LOOP DOS PRODUTOS 
                        ?>

                    </div>


            <?php } //FIM DO LOOP DAS COTAÇÕES 

            } // FIM DA VERIFICACAO SE HÁ COTACOES
            ?>
        </div>

        <div class="andamento">
            <br>
            <h2>Pedidos Orçados:</h2>
            <br>

            <?php
            require_once 'model/M_connection.php';
            $dbConn = new Connection();
            $conn = $dbConn->connect();

            require_once 'model/M_admin.php';
            require_once 'model/M_pedido.php';
            $admin = new Admin();

            //ESTA É A FORMA DE CHAMAR UM MÉTODO ESTÁTICO
            $pedidos = Pedido::getPedidosOrcados($conn);


            //LOOP DAS COTAÇÕES
            if ($pedidos) {

                foreach ($pedidos as $pedido) {

                    $idPedido = $pedido->getId();
                    $cliente = mb_strtoupper(aes_256("decrypt", ($pedido->getUser())->getName()), 'UTF-8');
                    $cnpj_cpf = aes_256("decrypt", ($pedido->getUser())->getCnpj_cpf());
                    $precoAuto = number_format($pedido->computarTotal($conn), 2, ',', '.');
            ?>

                    <div class="cotacoes">
                        <h3>Cotação # <?= $idPedido ?></h3>
                        <p>Cliente: <?= $cliente ?><br>
                            CNPJ_CPF: <?= $cnpj_cpf ?><br><br>
                            Preço Automático: R$ <?= $precoAuto ?> <br>
                            PREÇO ORÇADO: Cotação ainda não orçada.
                        </p>
                    </div>

                    <div id="produtos-<?= $pedido->getId() ?>" style="margin-left:20px;">
                        <h3>Produtos</h3>

                        <?php

                        //LOOP DOS PRODUTOS

                        $produtos = $pedido->getProdutos($conn);
                        //Aqui são PEDIDO_PRODUTOS... extensão de apenas PRODUTOS. (conferir M_product.php)

                        foreach ($produtos as $prod) {

                            $peca = $prod->getTipoPeca();
                            $tecido = mb_strtoupper($prod->getTecido(), 'UTF-8');
                            $tamanho = $prod->getTamanho();
                            $mod_t = $prod->getModTamanho();
                            $cor = $prod->getCor();
                            $costura = $prod->getCostura();
                            $mod_c = $prod->getModCostura();
                            $qtde = $prod->getQtdeProdutos();
                            $base_cost = $prod->getBaseCost();
                        ?>
                            <p><b><?= $peca ?></b><br>
                                Tecido: <?= $tecido ?><br>
                                Tamanho: <?= $tamanho ?> <small>(x<?= $mod_t ?>)</small><br>
                                Cor: <?= $cor ?><br>
                                Costura: <?= $costura ?> <small>(x<?= $mod_c ?>)</small><br>
                                Quantidade: <?= $qtde ?><br>
                                Preço Base: R$ <?= $base_cost ?>
                            </p>

                            <?php
                            //LOOP DOS SERVIÇOS

                            $servicos = $prod->getServicos($conn);
                            while ($servico = $servicos->fetch_assoc()) {
                            ?>
                                <div id="prod-<?= $prod->getIdPedidoProduto() ?>-serv-<?= $servico['id'] ?>" style="margin-left:20px;">
                                    <p><b>Serviço: <?= $servico['desc'] ?></b><br>
                                        Tamanho: <?= $servico['tamanho'] ?> (<?= $servico['desc_tamanho'] ?>) <br>
                                        Custo: R$ <?= $servico['preco'] ?> <br>
                                        Posição: <?= $servico['posicao'] ?> <br>
                                        Comentários: <?= $servico['comment'] ?>
                                    </p>
                                </div>

                            <?php } // FIM DO LOOP DOS SERVIÇOS 
                            ?>

                        <?php } // FIM DO LOOP DOS PRODUTOS 
                        ?>

                    </div>


            <?php } //FIM DO LOOP DAS COTAÇÕES 

            } // FIM DA VERIFICACAO SE HÁ COTACOES
            ?>
        </div>
    </main>
</body>

</html>