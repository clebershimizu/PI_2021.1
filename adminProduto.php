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
        var count = 2
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
            newPositionCb.checked= "checked"
            newPositionCb.hidden = true
            d.getElementById('checkbox_hidden').appendChild(newPositionCb)

            count++
        }

        function deletePosition() {
            lastPosition = d.getElementById('campos_posicoes').lastElementChild
            lastPositionCb = d.getElementById('checkbox_hidden').lastElementChild
            d.getElementById('campos_posicoes').removeChild(lastPosition)
            d.getElementById('checkbox_hidden').removeChild(lastPositionCb)

            if (count > 2) {
                count--
            }
        }
    </script>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">

    <?php include "view/header.php"; ?>

    <main class="container py-4">
        <h1>Gestão de produtos</h1>
        <hr>

        <h2>Produtos cadastrados</h2>
        <br>
        <table style="width:100%">
            <tr>
                <th>Item/SKU</th>
                <th>Descrição</th>
                <th>Tecido</th>
                <th>Custo Base</th>
                <th>Imagem</th>
                <th>Posicoes</th>
                <th>Editar</th>
                <th>Remover</th>
            </tr>

            <?php

            require_once 'Model/M_connection.php';
            $dbConn = new Connection();
            $conn = $dbConn->connect();

            require_once "model/M_product.php";
            $produtos = Product::getProdutos($conn);

            foreach ($produtos as $prod) {

                $id = $prod->getIdProduto();
                $tipo = $prod->getTipoPeca();
                $tecido = $prod->getTecido();
                $cost = number_format($prod->getBaseCost(), 2, ",", '.');
                $url = $prod->getImgUrl();
            ?>

                <tr>
                    <td id="<?= $id ?>-id"><?= $id ?></td>
                    <td id="<?= $id ?>-tipo_peca"><?= $tipo ?></td>
                    <td id="<?= $id ?>-tecido"><?= $tecido ?></td>
                    <td id="<?= $id ?>-base_cost"><?= $cost ?></td>
                    <td id="<?= $id ?>-image_url"><?= $url ?></td>
                    <td id="<?= $id ?>-posicoes" class="pe-3">
                        <select id="select" class="form-select form-select-sm">
                            <option value="" selected disabled>Visualizar</option>
                            <?php
                            $posicoes = $prod->getPosicoes($conn);
                            while ($pos = $posicoes->fetch_assoc()) { ?>
                                <option value="" disabled><?= $pos['descricao'] ?></option>
                            <?php    }
                            ?>
                        </select>
                    </td>
                    <td><a class="btn btn-md btn-primary" href="adminProdutoEdit.php?id=<?= $id ?>">Editar</a></td>
                    <td>
                        <form action="control/C_deleteProduct.php?id=<?= $id ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                            <input type="submit" class="btn btn-md btn-danger" value="Excluir">
                        </form>
                    </td>
                </tr>

            <?php } ?>

        </table>

        <hr>
        <br>

        <form action="control/C_registerProduct.php" method="POST">
            <div class="col-10 col-xs-10 col-sm-8 col-md-7 col-lg-7 col-xl-6">
                <h2 class="h2 mb-3 fw-normal">Cadastrar Novo Produto</h2>

                <label for="description">Descrição</label> <br>
                <input type="text" name="description" class="form-control">
                <br>
                <label for="tecido">Tecido</label> <br>
                <input type="text" name="tecido" class="form-control">
                <br>
                <label for="base_cost">Custo Base</label> <br>
                <input type="number" name="base_cost" class="form-control">
                <br>
                <label for="picture">Imagem</label> <br>
                <input type="text" name="picture" class="form-control">
                <br>

                <div id="posicoes">
                    <hr>

                    <h3 class="h3 mb-3 fw-normal">Posições para Serviços</h3>

                    <!-- INPUTS DE POSICOES: 1º FICA FORA DA DIV PARA NAO SER AFETADO PELO JAVASCRIPT -->
                    <input type="text" class="form-control mb-2" id="i-p-1" name="p-1" placeholder="Ex: Manga Direita" required>
                    <div id="campos_posicoes">

                    </div>

                    <!-- ARRAY DE CHECKBOX ESCONDIDOS, QUE CARREGA O NAME DE TODOS OS INPUTS DINAMICOS EM SEUS VALUES: O PRIMEIRO FICA FORA PQ É OBRIGATORIO -->
                    <input type="checkbox" name="pos[]" id="c-p-1" value="p-1" checked="checked" hidden >
                    <div id="checkbox_hidden" hidden>

                    </div>

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
                <input class="w-50 btn btn-lg btn-primary" type="submit" value="Cadastrar">
            </div>
        </form>
    </main>


</body>

</html>