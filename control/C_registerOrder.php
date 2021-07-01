<?php

session_start();

if ((!isset($_COOKIE['cart'])) || (!isset($_SESSION['loggedUser']))) {
    $erro = "Acesso negado.";
    header("Location: ../index.php?erro={$erro}");
    exit;
}

$cart = json_decode($_COOKIE['cart']);

require_once '../model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

require_once '../model/M_pedido.php';
$orderProduct = new Pedido();

try {
    $date = date("Y-m-d");
    $orderProduct->setUser($conn, $_SESSION['idUser']);
    $orderProduct->setStatus(0);
    $orderProduct->setDate($date);
    $orderProduct->criarPedido($conn);

    foreach ($cart as $produto) {
        $orderProduct->cadastrarProduto($conn, $produto);
    }

    setcookie('cart', $_COOKIE['cart'], time() - 10, "/");
} catch (Exception $e) {
    $erro = $e->getMessage();
    header("Location: ../catalogo.php?erro={$erro}");
    exit();
}
$msg = ("Orçamento solicitado com sucesso");
header("Location: ../catalogo.php?msg={$msg}");
exit();
