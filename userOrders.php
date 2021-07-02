<?php
session_start();

if (!isset($_SESSION['loggedUser'])) {
    $msg = "Acesso Negado.";
    header("Location: userLogin.php?erro={$msg}");
    exit();
}

require_once 'model/M_connection.php';
require_once 'model/M_user.php';
require_once 'model/M_pedido.php'; //UTILIZADO DENTRO DO MODEL USER

$dbConn = new Connection();
$conn = $dbConn->connect();

$user = new User();
$user->preencher($conn, $_SESSION['idUser']);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <title>Meus Pedidos e Orçamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS para ícones -->
    <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <style>
        /* ESTILOS DO ACCORDION */
        /* ESTILOS PARA OS PEDIDOS PENDENTES (VERMELHO) */
        .pendentes {
            background: rgba(220, 53, 69, 0.06);
        }

        .pendentes .accordion-button:focus {
            border-color: rgb(220, 53, 69);
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.2);
        }

        .pendentes .accordion-button[aria-expanded="true"] {
            background-color: rgb(220, 53, 69, 0.2);
        }


        /* ESTILOS PARA OS PEDIDOS ORÇADOS (AMARELO) */
        .orcados {
            background: rgba(255, 193, 7, 0.1);
        }

        .orcados .accordion-button:focus {
            border-color: rgb(255, 193, 7);
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.2);
        }

        .orcados .accordion-button[aria-expanded="true"] {
            background-color: rgb(255, 193, 7, 0.2);
        }


        /* ESTILOS PARA OS PEDIDOS PAGOS (VERDE) */
        .pagos {
            background: rgba(40, 167, 69, 0.1);
        }

        .pagos .accordion-button:focus {
            border-color: rgb(40, 167, 69);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.2);
        }

        .pagos .accordion-button[aria-expanded="true"] {
            background-color: rgb(40, 167, 69, 0.2);
        }
    </style>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">

    <?php include "view/header.php"; ?>

    <main class="container">
        <div>
            <h1 class="py-5">Meus Pedidos e Orçamentos</h1>
        </div>
        <hr>
        <br>

        <?php for ($status = 0; $status <= 2; $status++) { //LOOP PARA CADA TIPO DE PEDIDO DE ACORDO COM STATUS 

            switch ($status) {
                case 0:
                    $titulo = "Pedidos aguardado Orçamento:";
                    $class = "pendentes";
                    break;
                case 1:
                    $titulo = "Pedidos Orçados:";
                    $class = "orcados";
                    break;
                case 2:
                    $titulo = "Pedidos Pagos:";
                    $class = "pagos";
                    break;
            }
        ?>

            <div class="<?= $class ?> p-3 rounded">
                <h2> <?= $titulo ?> </h2>
                <br>

                <?php

                //ESTA É A FORMA DE CHAMAR UM MÉTODO ESTÁTICO
                $pedidos = $user->getPedidos($conn, $status);

                //LOOP DAS COTAÇÕES
                if ($pedidos) { ?>

                    <div id="accordion-pedidos" class="accordion">

                        <?php foreach ($pedidos as $pedido) {

                            $idPedido = $pedido->getId();
                            $cliente = mb_strtoupper(aes_256("decrypt", ($pedido->getUser())->getName()), 'UTF-8');
                            $cnpj_cpf = aes_256("decrypt", ($pedido->getUser())->getCnpj_cpf());
                            $precoAuto = number_format($pedido->computarTotal($conn), 2, ',', '.');

                            $aux = strtotime($pedido->getDate());
                            $dataSolic = date("d/m/Y", $aux);

                            if ($status > 0) {
                                $custo_orcado = number_format($pedido->getCusto_Orcado(), 2, ",", ".");
                                $comment = $pedido->getComment();

                                $aux = strtotime($pedido->getDateOrcamento());
                                $dataOrc = date("d/m/Y", $aux);
                            }


                        ?>

                            <div id="pedido-<?= $idPedido ?>" class="accordion-item">

                                <h3 class="accordion-header" id="pedido-header-<?= $idPedido ?>">
                                    <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#pedido-collapsible-<?= $idPedido ?>" aria-expanded="false" aria-controls="pedido-collapsible-<?= $idPedido ?>">
                                        <span> <b>PEDIDO #<?= $idPedido ?></b><br>
                                            <small>
                                                CNPJ_CPF: <?= $cnpj_cpf ?><br>
                                                Data da Solicitação: <?= $dataSolic ?></small></span>
                                    </button>
                                </h3>

                                <div id="pedido-collapsible-<?= $idPedido ?>" class="accordion-collapse collapse accordion-body py-3" data-bs-parent="#pedido-collapsible-<?= $idPedido ?>">
                                    <div class="pedido-info mb-3">

                                        <p>Cliente: <b><?= $cliente ?></b><br>
                                            CNPJ_CPF: <b><?= $cnpj_cpf ?></b><br>
                                            Data da Solicitação: <b><?= $dataSolic ?></b>
                                        <p>

                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#produto-collapsible-<?= $idPedido ?>" aria-expanded="false" aria-controls="produto-collapsible-<?= $idPedido ?>">
                                                Exibir Produtos
                                            </button>

                                        <div id="produto-collapsible-<?= $idPedido ?>" class="collapse mt-3">
                                            <div id="produtos-<?= $pedido->getId() ?>">

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
                                                        Tamanho: <?= $tamanho ?><br>
                                                        Cor: <?= $cor ?><br>
                                                        Costura: <?= $costura ?><br>
                                                        Quantidade: <?= $qtde ?><br>
                                                    </p>

                                                    <?php
                                                    //LOOP DOS SERVIÇOS

                                                    $servicos = $prod->getServicos($conn);
                                                    while ($servico = $servicos->fetch_assoc()) {
                                                    ?>
                                                        <div id="prod-<?= $prod->getIdPedidoProduto() ?>-serv-<?= $servico['id'] ?>" class="ps-4 border-start border-secondary">
                                                            <p><b>Serviço: <?= $servico['desc'] ?></b><br>
                                                                Imagem: <?= $servico['image_url'] ?><br>
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

                                        <hr>
                                        <?php if ($status == 0) { ?>
                                            <p>Custo Automático: R$ <?= $precoAuto ?></p>
                                            <p class="text-dark fw-bold"><small>Observação: O preço a ser orçado pode variar pouco ou drásticamente do custo automático.</small></p>
                                        <?php } else { ?>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
                                                    <div class="col-5 mb-2">
                                                        Custo Orçado:
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text" id="basic-addon1">R$</span>
                                                            <input disabled class="form-control" type="text" name="custo_orcado" value="<?= $custo_orcado ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <label for="exampleFormControlTextarea1">Comentários Sobre Orçamento:</label>
                                                        <textarea disabled name="comment" class="form-control"><?= $comment ?></textarea>
                                                    </div>

                                                    <p>Data do Orçamento: <b><?= $dataOrc ?></b></p>
                                                <?php } ?>

                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>

                        <?php } //FIM DO LOOP DAS COTAÇÕES 
                        ?>

                    </div>
                    <!--DIV DO ACCORDION DOS PEDIDOS.-->

                <?php } else { ?>
                    <h6>Não há pedidos nesta categoria.</h6>

                <?php } // FIM DA VERIFICACAO SE HÁ COTACOES 
                ?>

            </div>

            <hr>

        <?php } //FIM DO LOOP DE DIFERENTES STATUS 
        ?>

    </main>
    <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>