<?php

session_start();
if (!isset($_SESSION['loggedUser'])) {
    $msg = "Você só pode visitar o carrinho se estiver logado!";
    header("Location: userLogin.php?erro={$msg}");
    exit();
}

require_once 'model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

if (isset($_COOKIE['cart'])) {
    $cart = json_decode($_COOKIE['cart']);

    //var_dump($cart);
} else {
    $msg = "Você não possui produtos no carrinho! Comece pelo catálogo!";
    header("Location: catalogo.php?erro={$msg}");
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

    <!-- CSS para ícones -->
    <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /*diminui o tamanho dos textos do card, de maneira responsiva*/
        .card-body p {
            font-size: 0.8em;
        }

        /*crucial para as imagens ficarem certas*/
        .card-img-top {
            width: 100%;
            height: 24.5vh;
            object-fit: scale-down;
        }
    </style>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">

    <?php include "view/header.php"; ?>

    <main class="container">
        <div class="row justify-content-between mt-5">
            <div class="col-auto me-auto">
                <h1>Seu pedido</h1>
            </div>
            <div class="col-auto ms-auto">
                <a class="btn btn-primary" href="catalogo.php">Adicionar mais produtos</a>
                <a class="btn btn-success" href="control/C_registerOrder.php">Solicitar Orçamento</a>
            </div>
        </div>

        <hr>

        <br>
        <h2>Produtos adicionados</h2>
        <br>

        <!--ROW = CONTAINER DE COLUNAS... AQUI DENTRO ELAS SE AJEITAM SOZINHAS, SEGUNDO OS PARAMETROS DE TELA (rows-cols-screensize-qtde por linha)-->
        <div class="row m-auto row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-2 row-cols-xxl-3">

            <!--LOOP EM PHP-->
            <?php
            foreach ($cart as $prod) {

                //var_dump($prod);

                /*TRANSFORMAR OS IDS DO CART EM TEXTO...
                INFELIZMENTE, O CART NAO TEM CADASTRADO NO BANCO, ENTAO NAO DA PARA UTILIZAR A CLASSE PEDIDO_PRODUTO
                MAS É BASICAMENTE ISSO, TRAZER OS VALORES DE STRING, COM BASE NOS IDS NO CART.*/

                //DEFINIR O PRODUTO E O TECIDO
                $query = 'SELECT tipo_peca, tecido, image_url FROM produto WHERE id = ?';
                $stmt = $conn->prepare($query);
                @$stmt->bind_param("i", $prod->product);
                $stmt->execute();
                $search = $stmt->get_result();
                $search = $search->fetch_assoc();

                $produto = $search['tipo_peca'];
                $tecido = $search['tecido'];
                $imagem = $search['image_url'];

                //DEFINIR TAMANHO
                $query = 'SELECT t.desc FROM tamanho t WHERE id = ?';
                $stmt = $conn->prepare($query);
                @$stmt->bind_param("i", $prod->tamanho);
                $stmt->execute();
                $search = $stmt->get_result();
                $search = $search->fetch_assoc();

                $tamanho = $search['desc'];

                //DEFINIR COR
                $query = 'SELECT c.desc FROM cor c WHERE id = ?';
                $stmt = $conn->prepare($query);
                @$stmt->bind_param("i", $prod->cor);
                $stmt->execute();
                $search = $stmt->get_result();
                $search = $search->fetch_assoc();

                $cor = $search['desc'];

                //DEFINIR COSTURA
                $query = 'SELECT c.desc FROM costura c WHERE id = ?';
                $stmt = $conn->prepare($query);
                @$stmt->bind_param("i", $prod->costura);
                $stmt->execute();
                $search = $stmt->get_result();
                $search = $search->fetch_assoc();

                $costura = $search['desc'];

                //DEFINIR A QUANTIDADE ... só traduz pra uma variavel mais legal
                $quantidade = $prod->quantidade;


            ?>

                <!--ESTRUTURA DE UM CARD-->
                <div class="col mb-4">
                    <div class="card shadow-sm h-100">

                        <!--CORPO DO CARD-->
                        <div class="card-body row row-cols-2 align-items-center">
                            <!-- COLUNA DA IMAGEM -->
                            <div class="col">
                                <img src="img/<?= $imagem ?>" alt="placeholder" class="card-img-top">
                            </div>
                            <!-- COLUNA DAS INFORMAÇÕES DO PRODUTO -->
                            <div class="col">
                                <!-- TITULO -->
                                <h6 class="text-info"><?= mb_strtoupper($produto, 'UTF-8') ?></h6>

                                <!-- INFOS -->
                                <p class="card-text">Tecido: <?= mb_strtoupper($tecido, 'UTF-8') ?><br>
                                    Tamanho: <?= mb_strtoupper($tamanho, 'UTF-8') ?><br>
                                    Cor: <?= mb_strtoupper($cor, 'UTF-8') ?><br>
                                    Costura: <?= mb_strtoupper($costura, 'UTF-8') ?><br>
                                    Quantidade: <?= mb_strtoupper($quantidade, 'UTF-8') ?></p>

                                <!-- SERVICOS -->
                                <select class="form-control form-control-sm">
                                    <option selected>Serviços</option>

                                    <?php foreach ($prod->servicos as $serv) {
                                        //RECOLHER OS VALORES DOS IDS

                                        //BUSCAR TAMANHO, DESC TAMANHO E NOME DO SERVICO
                                        $query =   'SELECT stp.desc_tamanho, stp.tamanho, s.desc FROM servico_tamanho_preco stp 
                                        JOIN servico s ON s.id = stp.fk_servico_id WHERE stp.id = ?';
                                        $stmt = $conn->prepare($query);
                                        @$stmt->bind_param("i", $serv->tamanho);
                                        $stmt->execute();
                                        $search = $stmt->get_result();
                                        $search = $search->fetch_assoc();

                                        $servico = $search['desc'];
                                        $tamanho = $search['tamanho'];
                                        $desc_tamanho = $search['desc_tamanho'];

                                        //BUSCAR POSICAO
                                        $query =   'SELECT descricao FROM posicao WHERE id = ?';
                                        $stmt = $conn->prepare($query);
                                        @$stmt->bind_param("i", $serv->posicao);
                                        $stmt->execute();
                                        $search = $stmt->get_result();
                                        $search = $search->fetch_assoc();

                                        $posicao = $search['descricao']; ?>

                                        <option disabled><?= $servico ?> - <?= $posicao ?> - <?= $tamanho ?>(<?= $desc_tamanho ?>)</option>

                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <!--FIM DO LOOP-->
            <?php } ?>

        </div>

        <div>


        </div>

    </main>

    <?php include "view/footer.php"; ?>
    <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>