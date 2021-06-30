<?php 

require_once 'model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

$query = 'SELECT * FROM produto';
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <meta name="generator" content="Hugo 0.83.1">
    <title>PIBES - GMS</title>

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS para ícones -->
    <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="lib/bootstrap/dist/css/carousel.css" rel="stylesheet">

    <!-- CSS para ícones -->
    <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">


    <!-- CSS temporário para os placeholders (imagens em cinza) -->
    <style>
        /*diminui o tamanho dos textos do card, de maneira responsiva*/
        .card-body p {
            font-size: 0.7em;
        }

        /*crucial para as imagens ficarem certas*/
        .card-img-top {
            width: 100%;
            height: 30.5vh;
            object-fit: scale-down;
        }
    </style>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">

    <?php include "view/header.php"; ?>

    <main>

        <div class="container-fluid">

            <div class="py-5 text-center">
                <h2>Produtos</h2>
            </div>

            <!--ROW = CONTAINER DE COLUNAS... AQUI DENTRO ELAS SE AJEITAM SOZINHAS, SEGUNDO OS PARAMETROS DE TELA (rows-cols-screensize-qtde por linha)-->
            <div class="row m-auto row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-6">

                <!--LOOP MOSTRANDO TODOS OS PRODUTOS REGISTRADOS NO BANCO COM NOME E IMAGEM-->
                <?php while ($produto = $result->fetch_assoc()) { ?>

                    <!--ESTRUTURA DE UM CARD-->
                    <div class="col mb-4">
                        <div class="card shadow-sm h-100">

                            <!--IMAGEM DO CARD-->
                            <!-- Lá na pasta, tem fotos de produtos "prod0" até "prod4". Aí utilizo a própria variavel do loop ($i) para gerar um número,
                        que decide qual imagem chamar. Quando tiver tudo integrado, só puxa da banco mesmo 
                        (lá vai estar armazenado um link com o repositorio de imagens, um imgur da vida)-->

                            <img src="img/<?= $produto['image_url'] ?>" alt="placeholder" class="card-img-top w-100">

                            <!--CORPO DO CARD-->
                            <div class="card-body d-flex flex-column">
                                <h5 class="text-info"> <?php echo $produto['tipo_peca'] ?></h5>
                                <div class="mt-auto">
                                    <a href="product.php?id=<?=$produto['id']?>" class="btn btn-sm w-100 btn-info" role="button">Adicionar ao Carrinho</a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!--FIM DO LOOP-->
                <?php } ?>

            </div>
        </div> <!-- /.Container Catálogo -->


    </main>

    <!-- FOOTER -->
    <?php include "view/footer.php"; ?>

    <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>