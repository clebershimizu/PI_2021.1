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

    $id_p = $_POST['id_p'];

    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();
    require_once '../model/M_product.php';
    $produto = new Pedido_Produto();
    $produto->preencherProduto($conn, $id_p);

    if (!isset($_COOKIE['cart'])) {
        $products = array();
        $products = json_encode($products);
        setcookie('cart', $products, time() + 3600 * 24 * 3, "/");
    }

    $products = json_decode($_COOKIE['cart']);


    $produto->setCor($conn, $_POST['cor']);
    $produto->setCostura($conn, $_POST['costura']);
    $produto->setTamanho($conn, $_POST['tamanho']);

    array_push($products, $produto);
    setcookie('cart', $products, time() + 3600 * 24 * 3, "/");
}
