<?php
session_start();

if (!isset($_SESSION['loggedAdmin'])) {
    $msg = "Acesso Negado.";
    header("Location: adminLogin.php?erro={$msg}");
    exit();
}

require_once 'Model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

require_once "model/M_product.php";
$produtos = Product::getProdutos($conn);

$id = $_GET['id'];

$prod = new Product();
$prod->preencherProduto($conn, $id);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="estilo.css" />
    <title>ADMIN - Cadastro de produtos</title>

    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .logo {
            padding: 2rem 0 2rem 0;
            display: flex;
            max-width: 100%;
            justify-content: flex-end;
        }
    </style>

    <script>
        <?php

        $posicoes = $prod->getPosicoes($conn);

        if ($posicoes->num_rows == 0) {
            $jsCount = 2;
        } else {
            $jsCount = $posicoes->num_rows + 1;
        }

        ?>

        var count = <?= $jsCount ?>

        var d = document

        function createPosition() {
            var d = document
            var newPosition = d.createElement('input')
            newPosition.type = "text"
            newPosition.classList = "form-control mb-2"
            newPosition.id = "i-p-" + count
            newPosition.name = "p-" + count
            newPosition.placeholder = "Ex: Manga Direita"
            newPosition.required = true
            d.getElementById('campos_posicoes').appendChild(newPosition)

            var newPositionCb = d.createElement('input')
            newPositionCb.type = "checkbox"
            newPositionCb.name = "pos[]"
            newPositionCb.id = "c-p-" + count
            newPositionCb.value = "p-" + count
            newPositionCb.hidden = true
            d.getElementById('checkbox_hidden').appendChild(newPositionCb)

            count++
        }

        function deletePosition() {

            console.log("Count Após Click: " + count)

            lastPosition = d.getElementById('campos_posicoes').lastElementChild
            lastPositionCb = d.getElementById('checkbox_hidden').lastElementChild
            d.getElementById('campos_posicoes').removeChild(lastPosition)
            d.getElementById('checkbox_hidden').removeChild(lastPositionCb)

            if (count > 2) {
                count--
            }

            console.log("Count Após Deletar: " + count)
        }
    </script>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">

    <?php include "view/header.php"; ?>

    <main class="container py-4">
        <h1>Editar Produto</h1>
        <hr>

        <div class="col-10 col-xs-10 col-sm-8 col-md-7 col-lg-7 col-xl-6">
            <label for="description">Descrição</label> <br>
            <input type="text" name="description" class="form-control" value="<?= $prod->getTipoPeca() ?>">
            <br>
            <label for="tecido">Tecido</label> <br>
            <input type="text" name="tecido" class="form-control" value="<?= $prod->getTecido() ?>">
            <br>
            <label for="base_cost">Custo Base</label> <br>
            <input type="number" name="base_cost" class="form-control" value="<?= $prod->getBaseCost() ?>">
            <br>
            <label for="picture">Imagem</label> <br>
            <input type="text" name="picture" class="form-control" value="<?= $prod->getImgUrl() ?>">
            <br>

            <div id="posicoes">
                <hr>

                <h3 class="h3 mb-3 fw-normal">Posições para Serviços</h3>

                <?php

                // CÓDIGO PRA CAMPOS DINAMICOS

                $posicoes = $prod->getPosicoes($conn);

                if ($posicoes->num_rows == 0) { ?>
                    <input type="text" class="form-control mb-2" id="i-p-1" name="p-1" placeholder="Ex: Manga Direita" required>
                    <div id="campos_posicoes">

                    </div>
                    <?php } else {

                    $count = 0;
                    while ($pos = $posicoes->fetch_assoc()) {
                        $count++;

                        if ($count == 1) { ?>
                            <input type="text" class="form-control mb-2" id="i-p-<?= $count ?>" name="p-<?= $count ?>" placeholder="Ex: Manga Direita" value="<?= $pos['descricao'] ?>" required>
                            <div id="campos_posicoes">
                            <?php } else { ?>
                                <input type="text" class="form-control mb-2" id="i-p-<?= $count ?>" name="p-<?= $count ?>" placeholder="Ex: Manga Direita" value="<?= $pos['descricao'] ?>" required>
                        <?php }
                    } ?>
                            </div>
                        <?php }

                    // CÓDIGO PARA HIDDEN CHECKBOXES DINAMICOS

                    if ($count == 0) { ?>
                            <input type="checkbox" name="pos[]" id="c-p-1" value="p-1" hidden>
                            <div id="checkbox_hidden" hidden>

                            </div>

                            <?php } else {

                            for ($i = 0; $i < $count; $i++) {

                                if ($i == 0) { ?>
                                    <input type="checkbox" name="pos[]" id="c-p-<?= ($i + 1) ?>" value="p-<?= ($i + 1) ?>" hidden>
                                    <div id="checkbox_hidden" hidden>
                                    <?php } else { ?>
                                        <input type="checkbox" name="pos[]" id="c-p-<?= ($i + 1) ?>" value="p-<?= ($i + 1) ?>" hidden>
                                    <?php } ?>

                                <?php } ?>
                                    </div>
                                <?php } ?>

                                <!-- ATUALIZAR FUNCAO DE ADICIONAR POSICAO -->
                                <div class="py-2">
                                    <input type="button" value="Adicionar Posição +" class="btn btn-success" id="add_pos" onclick="createPosition();">
                                </div>
                                <div>
                                    <input type="button" value="Remover Última Posição Adicionada -" class="btn btn-danger btn-sm" id="rem_pos" onclick="deletePosition();">
                                </div>


                                <br>
            </div>

            <hr>
            <button class="w-50 btn btn-lg btn-primary" type="submit">Adicionar</button>
        </div>
    </main>


</body>

</html>