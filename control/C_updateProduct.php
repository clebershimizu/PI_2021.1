<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    require_once '../model/M_connection.php';
    $dbConn = new Connection();
    $conn = $dbConn->connect();

    require_once '../model/M_product.php';
    $prod = new Product();

    $id = $_GET['id'];
    

    try {
        //TRY CATCH, pois a sanitização dos SETS pode retornar exceptions
        $prod->setIdProduto($id);
        $prod->setTipoPeca($_POST['description']);
        $prod->setTecido($_POST['tecido']);
        $prod->setBaseCost($_POST['base_cost']);
        $prod->setImgUrl($_POST['picture']);
    } catch (Exception $e) {
        $msg = $e->getMessage();
        header("Location: ../adminProduto.php?erro={$msg}");
    }

    //MONTAR ARRAY DE POSICOES, SANITIZANDO
    $inputs_posicoes = $_POST['pos'];  
    $str_pos = [];
    foreach($inputs_posicoes as $input){

        $pos = $_POST[$input];

        $s_pos = filter_var($pos, FILTER_SANITIZE_STRING);
        if (!$s_pos) {
            $msg="Valor para Posicao Inválida!";
            header("Location: ../adminProduto.php?erro={$msg}");
            exit();
        }else{
            array_push($str_pos, $_POST[$input]);
        } 
    }

    $prod->updateProduct($conn, $str_pos);
    $msg="Produto Editado com Sucesso!";
    header("Location: ../adminProduto.php?alert={$msg}");

}
