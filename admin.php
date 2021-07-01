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
    <title>Pedidos e Orçamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS para ícones -->
    <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <style>
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
            <h1 class="py-5">Gestão de Pedidos e Orçamentos</h1>
        </div>
        <hr>
        <br>
        <div class="pendentes p-3">
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
            $pedidos = Pedido::getPedidos($conn, 0);

            //LOOP DAS COTAÇÕES
            if ($pedidos) {

                foreach ($pedidos as $pedido) {

                    $idPedido = $pedido->getId();
                    $cliente = mb_strtoupper(aes_256("decrypt", ($pedido->getUser())->getName()), 'UTF-8');
                    $cnpj_cpf = aes_256("decrypt", ($pedido->getUser())->getCnpj_cpf());
                    $precoAuto = number_format($pedido->computarTotal($conn), 2, ',', '.');
            ?>

                    <div id="pedido-<?= $idPedido ?>">

                        <div class="d-flex flex-row">
                            <h3>Pedido # <?= $idPedido ?></h3>
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pedido-collapsible-<?= $idPedido ?>" aria-expanded="false" aria-controls="pedido-collapsible-<?= $idPedido ?>">
                                Expandir
                            </button>
                        </div>

                        <div id="pedido-collapsible-<?= $idPedido ?>" class="collapse">
                            <div class="pedido-info mb-3">

                                <p>Cliente: <?= $cliente ?><br>
                                    CNPJ_CPF: <?= $cnpj_cpf ?><br><br>
                                    Custo Automático: R$ <?= $precoAuto ?></p>

                                <form action="control/C_aferirOrcamento.php" method="POST">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
                                            <div class="col-5 mb-2">
                                                Custo Orçado: <input required class="form-control form-control-sm" type="number" name="custo_orcado" step="0.01" require>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="exampleFormControlTextarea1">Comentários Sobre Orçamento:</label>
                                                <textarea required name="comment" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div>
                                                <input class="btn btn-success btn-sm" type="submit" name="submit" value="Aferir Orçamento">
                                            </div>
                                        </div>
                                    </div>
                                    <input required type="text" name="id_pedido" value="<?= $idPedido ?>" hidden>
                                </form>

                            </div>

                            <div class="d-flex flex-row">
                                <h3>Pedido # <?= $idPedido ?></h3>
                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#pedido-collapsible-<?= $idPedido ?>" aria-expanded="false" aria-controls="pedido-collapsible-<?= $idPedido ?>">
                                    Expandir
                                </button>
                            </div>

                            <div id="produto-collapsible-<?= $idPedido ?>" class="collapse">
                                <div id="produtos-<?= $pedido->getId() ?>">
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
                            </div>
                        </div>
                    </div>

                    <hr>

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

            require_once 'model/M_pedido.php';

            //ESTA É A FORMA DE CHAMAR UM MÉTODO ESTÁTICO
            $pedidos = Pedido::getPedidos($conn, 1);


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
    <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>