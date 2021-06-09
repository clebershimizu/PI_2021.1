<?php

class Catalog
{
    function getProdutos($conn)
    {
        $query = 'SELECT * FROM produto';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    function getServicos($conn)
    {
        $query = 'SELECT * FROM servico_tamanho_preco';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }
}
