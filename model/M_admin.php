<?php

    $docroot = $_SERVER['DOCUMENT_ROOT'];
    require_once "{$docroot}/PI_2021.1/lib/crypto.php";


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

        //retornar ID da cotação
        //retornar data da solicitação
        //retornar Nome do cliente
        //retornar CNPJ/CPF do cliente
        $query = 'SELECT p.id, p.date, u.name, u.cnpj_cpf FROM pedido p
                    JOIN user u ON p.fk_user_id = u.id
                    WHERE p.status = 0';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    //FUNÇÃO PARA RECOLHER PRODUTOS DE UMA COTAÇÃO
    function getProdutosDeCotacao($conn, $pedido_id)
    {
        $query =   'SELECT  pp.id,
                            p.tipo_peca,
                            p.base_cost,
                            pp.qtde_produtos,
                            t.desc as tamanho,
                            cor.desc as cor,
                            c.desc as costura
                    FROM pedido_produto pp
                    JOIN produto p  ON pp.fk_produto_id = p.id
                    JOIN cor        ON pp.fk_cor_id  =  cor.id
                    JOIN tamanho t  ON pp.fk_tamanho_id = t.id
                    JOIN costura c  ON pp.fk_costura_id = c.id
                    WHERE pp.fk_pedido_id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    //FUNÇÃO PARA RECOLHER SERVIÇOS DE PRODUTOS DE UM PEDIDO
    function getServicosDeProduto($conn, $produto_id)
    {
        //retorna o nome do serviço
        //retorna o preço adicional
        //retorna o tamanho
        //retorna a descricao do tamanho
        //retorna a posição
        //retorna comentarios
        $query =   'SELECT  s.id,
                            s.desc,
                            stp.preco,
                            stp.tamanho,
                            stp.desc_tamanho,
                            pps.posicao,
                            pps.comment
                    FROM pedido_produto_servico as pps
                    JOIN servico_tamanho_preco stp  ON pps.fk_servico_id = stp.id
                    JOIN servico s                  ON s.id = stp.fk_servico_id 
                    WHERE pps.fk_pedido_produto_id = ?';

        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $produto_id);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    function computarTotalDePedido($con, $pedido_id)
    {
            //essa aqui é o monstro
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
