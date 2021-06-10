<?php

class Catalog {
    function getProducts($conn){
        $query = 'SELECT * FROM produto';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    function getServices($conn){
        $query = 'SELECT * FROM servico';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }
}