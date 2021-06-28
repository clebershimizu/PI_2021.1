<?php

//arquivo acionado via ajax

$idServico =  $_GET['id'];

require_once '../model/M_connection.php';

$dbConn = new Connection();
$conn = $dbConn->connect();

$query = 'SELECT * FROM servico_tamanho_preco WHERE fk_servico_id = ?';
$stmt = $conn->prepare($query);
@$stmt->bind_param("i", $idServico);
$stmt->execute();
$search = $stmt->get_result();

$result = array();
while ($tamanho = $search->fetch_object()) {
    array_push($result, $tamanho);
}

echo json_encode($result);

$conn->close();
