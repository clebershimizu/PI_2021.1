<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_product.php';
    $prod = new Product();

    $prod->setIdProduto($_GET['id']);

    $prod->deactivateProduct($conn);
    $msg="Produto Excluído com Sucesso!";
    header("Location: ../adminProduto.php?alert={$msg}");

}
