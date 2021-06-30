<?php
//CRIAR OBJETO Pedido_Produto
//SE COOKIE NAO EXISTIR
//CRIAR COOKIE
//CASO CONTRARIO
//JSON_DECODE NO VETOR
//ADICIONAR OBJETO NO VETOR
//JSON_ENCODE NO VETOR
//ADICIONAR VETOR COOKIE
//REDIRECIONAR AO CATALOGO

$docroot = $_SERVER['DOCUMENT_ROOT'];
require_once "{$docroot}/PI_2021.1/lib/crypto.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once '../model/M_connection.php';
    require_once '../model/M_cartProduct.php';

    $dbConn = new Connection();
    $conn = $dbConn->connect();

    //CRIANDO COOKIE DE CART SE NÃO EXISTE
    if (!isset($_COOKIE['cart'])) {
        $cart = [];
    } else {
        //DECODIFICANDO O CART PARA MANIPULAR
        $cart = json_decode($_COOKIE['cart']);
    }

    //RECOLHENDO DADOS DO FORMULARIO DE PRODUTO    
    $produto = new CartProduct();
    $produto->product = $_POST['idProduto'];
    $produto->cor = $_POST['cor'];
    $produto->tamanho = $_POST['tamanho'];
    $produto->quantidade = $_POST['quantidade'];
    $produto->costura = $_POST['costura'];

    $indices_servicos = $_POST['servicos']; //checkbox hidden

    foreach ($indices_servicos as $num) {
        array_push(
            $produto->servicos,
            array(
                "servico"   => $_POST["$num-select-servico"],
                "posicao"   => $_POST["$num-select-posicao"],
                "tamanho"   => $_POST["$num-select-tamanho"]
            )
        );
    }

    array_push($cart, $produto);
    $cart = json_encode($cart);
    setcookie('cart', $cart, time() + 3600 * 24 * 3, "/");

    $msg = "Produto adicionado ao carrinho com sucesso!";
    header("location: ../catalogo.php?msg={$msg}");
}
