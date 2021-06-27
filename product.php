<?php

session_start();
if (!isset($_SESSION['loggedUser'])) {
    $msg = "Você só pode adicionar um item ao carrinho se estiver logado";
    header("Location: catalogo.php?erro={$msg}");
}

require_once 'model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

if (isset($_GET['id'])) {
    $idProduto = $_GET['id'];
    $idProduto = filter_var($idProduto, FILTER_SANITIZE_NUMBER_INT);

    if (!$idProduto) {
        $msg = "Código de produto inválido. Acesse apenas via o catálogo de produtos.";
        //header("Location: catalogo.php?erro={$msg}");
        exit();
    }
} else {
    $msg = "Código de produto ausente. Acesse apenas via o catálogo de produtos.";
    //header("Location: catalogo.php?erro={$msg}");
    exit();
}


$result = $conn->query("SELECT tipo_peca, tecido FROM produto WHERE id = {$idProduto}");
$produto = $result->fetch_assoc();

$cores =    $conn->query("SELECT * FROM cor");
$tamanhos = $conn->query("SELECT * FROM tamanho");
$costuras = $conn->query("SELECT * FROM costura");
$servicos = $conn->query("SELECT * FROM servico");
$posicoes = $conn->query("SELECT * FROM posicao");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>

    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .logo {
            padding: 2rem 0 2rem 0;
            display: flex;
            max-width: 100%;
            justify-content: flex-end;
        }

        #servicosBottom {
            display: none;
        }

        .img-format {
            width: 100%;
            height: 30.5vh;
            object-fit: scale-down;
        }
    </style>

    <script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="script.js"></script>


</head>

<body class="mt-0 mb-0 pt-0 pb-0">

    <?php include "view/header.php"; ?>

    <main class="container">
        <h1 class="pt-5">Adicionar ao Carrinho</h1>
        <hr>

        <form action="control/C_addToCart.php" method="POST">
            <div id="product" class="col-10 col-xs-10 col-sm-8 col-md-7 col-lg-7 col-xl-6">

                <div id="product-mandatory">
                    <br>

                    <!-- NOME DO PRODUTO -->

                    <h4><?= mb_strtoupper($produto['tipo_peca'], 'UTF-8') ?></h4>

                    <!-- IMAGEM DO PRODUTO, AINDA NAO DINAMICO -->

                    <div class="w-50">
                        <img src="img/prod0.jpg" alt="Imagem do produto" class="img-format">
                    </div>

                    <!-- COR DO PRODUTO -->
                    <br>
                    <h4>Escolha a cor</h4>
                    <select class="form-select" aria-label="prodColor" id="descricao">
                        <option value="" selected disabled>Selecione</option>

                        <?php while ($cor = $cores->fetch_assoc()) { ?>
                            <option value="<?= $cor['id'] ?>"><?= $cor['desc'] ?></option>
                        <?php } ?>

                    </select>
                    <br>

                    <!-- TAMANHO DO PRODUTO -->

                    <h4>Escolha o tamanho</h4>

                    <?php while ($tamanho = $tamanhos->fetch_assoc()) { ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="inlineCheckbox2" value="<?= $tamanho['id'] ?>" name="tamanho">
                            <label class="form-check-label" for="inlineCheckbox2"><?= $tamanho['desc'] ?></label>
                        </div>
                    <?php } ?>

                    <br>

                    <!-- COSTURA DO PRODUTO -->

                    <h4>Selecione o tipo de costura</h4>
                    <div class="costura">
                        <select class="form-select" aria-label="costura" id="costura" name="costura">
                            <option value="" selected disabled>Selecione</option>

                            <?php while ($costura = $costuras->fetch_assoc()) { ?>
                                <option value="<?= $costura['id'] ?>"><?= $costura['desc'] ?></option>
                            <?php } ?>

                        </select>
                        <br>
                    </div>

                    <!-- QUANTIDADE DO PRODUTO -->

                    <h4>Informe a quantidade desejada</h4>
                    <input type="text" name="number" class="form-control"><br>

                    <hr>
                </div>


                <!-- ========================= -->
                <!-- SERVIÇOS OPCIONAIS -->
                <!-- ========================= -->

                <h2>Serviços Opcionais</h2>

                <div id="product-optional">

                    <!-- LOOP TEMPORARIO PARA TESTAR CSS -->
                    <?php for ($i = 0; $i < 2; $i++) { ?>

                        <div id="service-1">
                            <hr class="mb-1 mt-5">
                            <div class='row'>
                                <!-- CAMPO DO SERVICO -->
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <span>Serviço</span>
                                    <select class="form-select" aria-label="bordadoTop" id="bordadoTop">
                                        <option value="" selected disabled>Selecione</option>

                                        <?php while ($servico = $servicos->fetch_assoc()) { ?>
                                            <option value="<?= $servico['id'] ?>"><?= $servico['desc'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- CAMPO DO POSICIONAMENTO -->
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <span>Posicionamento</span>
                                    <select class="form-select" aria-label="bordadoTop" id="bordadoTop">
                                        <option value="" selected disabled>Selecione</option>

                                        <?php while ($posicao = $posicoes->fetch_assoc()) { ?>
                                            <option value="<?= $posicao['id'] ?>"><?= $posicao['descricao'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- CAMPO DO TAMANHO: OPTIONS SÃO DINAMICAS, AJAX PARA PREENCHER, POIS DEPENDE DO CAMPO DE SERVICO -->
                                <!-- AINDA INCOMPLETO -->
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                    <span>Tamanho</span>
                                    <select class="form-select" aria-label="bordadoTopSize" id="bordadoTopSize">
                                        <option selected>P</option>
                                        <option value="1">M</option>
                                        <option value="2">G</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                    <!--FIM DA DIV DO SERVICO 2-->

                </div>
                <!--FIM DA DIV DE SERVICOS (PRODUCT-OPTIONAL)-->
            </div>
            <!--FIM DA DIV PRODUCT-->

            <div class="py-2 mt-5">
                <input type="button" value="Adicionar Serviço +" class="btn btn-success" id="add_pos" onclick="">
            </div>
            <div class="py-2">
                <input type="button" value="Remover Último Serviço Adicionado -" class="btn btn-danger btn-sm" id="rem_pos" onclick="">
            </div>
            <hr>
            <div class="py-5">
                <button class="w-50 btn btn-lg btn-primary" type="submit">Adicionar ao Carrinho</button>
            </div>

        </form>

    </main>



    <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        document.getElementById('descricao').onchange = function() {

            var produto = document.getElementById('descricao').value;

            if (produto == 'calca') {
                document.getElementById("servicosTop").style.display = "none";
                document.getElementById('servicosBottom').style.display = "initial";
                document.getElementById("servicosTop2").style.display = "none";
                document.getElementById('servicosBottom2').style.display = "initial";
            } else {
                document.getElementById("servicosTop").style.display = "initial";
                document.getElementById('servicosBottom').style.display = "none";
                document.getElementById("servicosTop2").style.display = "initial";
                document.getElementById('servicosBottom2').style.display = "none";
            }
        }
    </script>
</body>


</html>