<?php

require_once "../lib/crypto.php";

class Admin
{
    private $id;
    private $username;
    private $password;

    //GETS E SETS
    //ID
    function getId()
    {
        return $this->id;
    }
    function setId($id)
    {
        $this->id = $id;
    }

    //NAME
    function getUsername()
    {
        return $this->username;
    }
    function setUsername($username)
    {
        $this->username = sha3_256($username);
    }


    //SENHA
    function getPassword()
    {
        return $this->password;
    }
    function setPassword($password)
    {
        $this->password = sha3_256($password);
    }

    //DEMAIS MÉTODOS DO ADMIN

    //FUNÇÃO PARA RECOLHER COTACOES
    function getCotacoes($conn)
    {
    }

    //FUNÇÃO PARA RECOLHER PRODUTOS DE UMA COTAÇÃO
    function getProdutosDeCotacao($conn, $pedido_id)
    {
    }

    //FUNÇÃO PARA RECOLHER SERVIÇOS DE PRODUTOS DE UM PEDIDO
    function getServicosDeProduto($conn, $produto_id)
    {
    }

    function computarTotalDePedido($con, $pedido_id)
    {
    }

    //FUNÇÃO PARA VERIFICAR LOGIN
    function searchLogin($conn)
    {
        $query = 'SELECT * FROM admin WHERE admin_username LIKE ? AND admin_password LIKE ? LIMIT 1';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("ss", $this->getUsername(), $this->getPassword());
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }
}
