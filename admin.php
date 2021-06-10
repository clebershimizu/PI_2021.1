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

    <?php

    require_once 'model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once 'model/M_admin.php';
    $admin = new Admin();
    $cotacoes = $admin->getCotacoes($conn);


    //LOOP DAS COTAÇÕES
    if ($cotacoes->num_rows > 0) {

        while ($cotacao = $cotacoes->fetch_assoc()) { ?>

            <hr>

            <h2>COTAÇÃO # <?=$cotacao['id']?></h2>
            <p>Cliente: <?=strtoupper(aes_256("decrypt", $cotacao['name']))?><br>
               CNPJ_CPF: <?=aes_256("decrypt", $cotacao['cnpj_cpf'])?><br>
               <br>
               Preço Automático: R$ xxxx,xx <br>    
               PREÇO ORÇADO: Cotação ainda não orçada.
            </p>
            
            </h4>


            <div id="produtos-<?=$cotacao['id']?>" style="margin-left:20px;">
                <h3>PRODUTOS</h3>

                <?php

                //LOOP DOS PRODUTOS

                $produtos = $admin->getProdutosDeCotacao($conn, $cotacao['id']);
                while ($produto = $produtos->fetch_assoc()) {
                ?>

                    <p><b><?=$produto['tipo_peca']?></b><br>
                        Tamanho: <?=$produto['tamanho']?><br>
                        Cor: <?=$produto['cor']?><br>
                        Costura: <?=$produto['costura']?><br>
                        Quantidade: <?=$produto['qtde_produtos']?><br>
                        Preço Base: R$ <?=$produto['base_cost']?>
                    </p>

                    <?php

                    //LOOP DOS SERVIÇOS
                    
                    $servicos = $admin->getServicosDeProduto($conn, $produto['id']);
                    while ($servico = $servicos->fetch_assoc()) {
                    ?>
                        <div id="prod-<?=$produto['id']?>-serv-<?=$servico['id']?>" style="margin-left:20px;">
                            <p><b>Serviço: <?=$servico['desc']?></b><br>
                                Tamanho: <?=$servico['tamanho']?> (<?=$servico['desc_tamanho']?>) <br>
                                Custo: R$ <?=$servico['preco']?> <br>
                                Posição: <?=$servico['posicao']?> <br>
                                Comentários: <?=$servico['comment']?>
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

</body>

</html>