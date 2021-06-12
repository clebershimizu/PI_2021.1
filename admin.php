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

    <h1>COTAÇÕES - ORÇAMENTOS PENDENTES</h1>

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

        foreach($pedidos as $pedido) { 

            $idPedido = $pedido->getId();
            $cliente = mb_strtoupper(aes_256("decrypt", ($pedido->getUser())->getName()), 'UTF-8');
            $cnpj_cpf = aes_256("decrypt", ($pedido->getUser())->getCnpj_cpf());
            $precoAuto = number_format($pedido->computarTotal($conn), 2, ',', '.');
            ?>

            <hr>

            <h2>COTAÇÃO # <?=$idPedido?></h2>
            <p>Cliente: <?=$cliente?><br>
               CNPJ_CPF: <?=$cnpj_cpf?><br>
               <br>
               Preço Automático: R$ <?=$precoAuto?> <br>    
               PREÇO ORÇADO: Cotação ainda não orçada.
            </p>
            
            </h4>


            <div id="produtos-<?=$pedido->getId()?>" style="margin-left:20px;">
                <h3>PRODUTOS</h3>

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
                    <p><b><?=$peca?></b><br>
                        Tecido: <?=$tecido?><br>
                        Tamanho: <?=$tamanho?> <small>(x<?=$mod_t?>)</small><br>
                        Cor: <?=$cor?><br>
                        Costura: <?=$costura?> <small>(x<?=$mod_c?>)</small><br>
                        Quantidade: <?=$qtde?><br>
                        Preço Base: R$ <?=$base_cost?>
                    </p>

                    <?php
                    //LOOP DOS SERVIÇOS
                    
                    $servicos = $prod->getServicos($conn);
                    while ($servico = $servicos->fetch_assoc()) {
                    ?>
                        <div id="prod-<?=$prod->getIdPedidoProduto()?>-serv-<?=$servico['id']?>" style="margin-left:20px;">
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