<?php

session_start();


//SECURITY CHECK
if (!isset($_SESSION['loggedAdmin'])) {
    $erro = "Acesso negado";
    header("Location: ../index.php?erro={$erro}");
}
if (!$_SESSION['loggedAdmin']) {
    $erro = "Acesso negado";
    header("Location: ../index.php?erro={$erro}");
}

require_once '../model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

require_once '../model/M_pedido.php';
$order = new Pedido();

//SETTING VALUES
$date = date("Y-m-d");

$order->setId($_POST['id_pedido']);
$order->setCusto_Orcado($_POST['custo_orcado']);
$order->setComment($_POST['comment']);
$order->setDateOrcamento($date);

// var_dump($order);

$order->aferirOrcamento($conn);

$msg = "Orçamento aferido com sucesso";
header("Location: ../admin.php?msg={$msg}");
