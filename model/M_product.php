<?php

//ESTE ARQUIVO POSSUI DUAS CLASSES, PRODUTO E SUA EXTENSÃO, PEDIDO_PRODUTO.

class Product
{
    private $id_produto;
    private $base_cost;
    private $img_url;
    private $tecido;
    private $tipo_peca;

    //DEMAIS MÉTODOS

    function preencherProduto($conn, $id_p)
    {
        $query =   'SELECT * FROM produto 
                    WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $id_p);
        $stmt->execute();
        $search = $stmt->get_result();
        $prod = $search->fetch_assoc();

        $this->setIdProduto($prod['id']);
        $this->setBaseCost($prod['base_cost']);
        $this->setImgUrl($prod['image_url']);
        $this->setTecido($prod['tecido']);
        $this->setTipoPeca($prod['tipo_peca']);
    }

    public static function getProdutos($conn)
    {

        //Pegar todos os produtos cadastrados

        $query = 'SELECT id FROM produto';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $produtos = $stmt->get_result();

        if ($produtos->num_rows > 0) {

            // PARA CADA PEDIDO NÃO ORÇADO:
            // 1. Construir um Objeto de Pedido
            // 2. Preencher este Objeto com infos de um ID já existente;
            // 3. Anexar em Array, que será retornado

            $array = [];

            while ($produto = $produtos->fetch_assoc()) {
                $prod = new Product();
                $prod->preencherProduto($conn, $produto['id']);
                array_push($array, $prod);
            }

            return $array;
        } else {
            return 0;
        }
    }

    // GETS E SETS

    //ID Produto
    function setIdProduto($id)
    {
        $this->id_produto = $id;
    }
    function getIdProduto()
    {
        return $this->id_produto;
    }

    //Base Cost
    function setBaseCost($bc)
    {
        $this->base_cost = $bc;
    }
    function getBaseCost()
    {
        return $this->base_cost;
    }

    //IMG URL
    function setImgUrl($url)
    {
        $this->img_url = $url;
    }
    function getImgUrl()
    {
        return $this->img_url;
    }

    //Tecido
    function setTecido($tecido)
    {
        $this->tecido = $tecido;
    }
    function getTecido()
    {
        return $this->tecido;
    }

    //Tipo Peca
    function setTipoPeca($tp)
    {
        $this->tipo_peca = $tp;
    }
    function getTipoPeca()
    {
        return $this->tipo_peca;
    }

    //Posicoes
    function getPosicoes($conn)
    {
        $query =   'SELECT * FROM posicao 
                    WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->id_produto);
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }
}

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// EXTENSÃO DA CLASSE - PEDIDO_PRODUTO
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

class Pedido_Produto extends Product
{
    private $id_pedido_produto;
    private $id_pedido;
    private $qtde_produtos;

    private $id_costura;
    private $costura;
    private $mod_costura;

    private $id_cor;
    private $cor;

    private $id_tamanho;
    private $tamanho;
    private $mod_tamanho;

    //GETS E SETS

    //PedidoProduto
    function getIdPedidoProduto()
    {
        return $this->id_pedido_produto;
    }
    function setIdPedidoProduto($x)
    {
        $this->id_pedido_produto = $x;
    }

    //Pedido
    function getIdPedido()
    {
        return $this->id_pedido;
    }
    function setIdPedido($x)
    {
        $this->id_pedido = $x;
    }

    //QTDE
    function getQtdeProdutos()
    {
        return $this->qtde_produtos;
    }
    function setQtdeProdutos($x)
    {
        $this->qtde_produtos = $x;
    }

    //Costura
    function setCostura($conn, $x)
    {
        $this->id_costura = $x;

        $query =   'SELECT c.desc, c.mod_preco FROM costura c
                    JOIN pedido_produto pp ON pp.fk_costura_id = c.id
                    WHERE pp.id = ?';

        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->id_pedido_produto);
        $stmt->execute();
        $search = $stmt->get_result();
        $row = $search->fetch_assoc();

        $this->costura = $row['desc'];
        $this->mod_costura = $row['mod_preco'];
    }
    function getIdCostura()
    {
        return $this->id_costura;
    }
    function getCostura()
    {
        return $this->costura;
    }
    function getModCostura()
    {
        return $this->mod_costura;
    }


    //Cor
    function setCor($conn, $x)
    {
        $this->id_cor = $x;

        $query =   'SELECT c.desc FROM cor c
                    JOIN pedido_produto pp ON pp.fk_cor_id = c.id
                    WHERE pp.id = ?';

        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->id_pedido_produto);
        $stmt->execute();
        $search = $stmt->get_result();
        $row = $search->fetch_assoc();

        $this->cor = $row['desc'];
    }
    function getIdCor()
    {
        return $this->id_cor;
    }
    function getCor()
    {
        return $this->cor;
    }

    //Tamanho
    function setTamanho($conn, $x)
    {
        $this->id_tamanho = $x;

        $query =   'SELECT t.desc, t.mod_preco FROM tamanho t
                    JOIN pedido_produto pp ON pp.fk_tamanho_id = t.id
                    WHERE pp.id = ?';

        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->id_pedido_produto);
        $stmt->execute();
        $search = $stmt->get_result();
        $row = $search->fetch_assoc();

        $this->tamanho = $row['desc'];
        $this->mod_tamanho = $row['mod_preco'];
    }
    function getIdTamanho()
    {
        return $this->id_tamanho;
    }
    function getTamanho()
    {
        return $this->tamanho;
    }
    function getModTamanho()
    {
        return $this->mod_tamanho;
    }

    //=-=-=-=-=- DEMAIS MÉTODOS =-=-=-=-=-=

    function preencherPedidoProduto($conn, $id_pp)
    {

        //Buscar tudo do parâmetro
        $query =   'SELECT * FROM pedido_produto WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $id_pp);
        $stmt->execute();
        $search = $stmt->get_result();
        $prod = $search->fetch_assoc();

        //PREENCHER dados de PRODUTO (catálogo) utilizando "construtor" da CLASSE MÃE.
        $this->preencherProduto($conn, $prod['fk_produto_id']);

        //PREENCHER os dados restantes
        $this->id_pedido_produto = $prod['id'];
        $this->id_pedido = $prod['fk_pedido_id'];
        $this->qtde_produtos = $prod['qtde_produtos'];

        //USAR os SETS de tamanho, costura e cor (lá ele monta ID, o nome certinho e o modificador. O set já faz isso, entao só aproveita)
        $this->setTamanho($conn, $prod['fk_tamanho_id']);
        $this->setCostura($conn, $prod['fk_costura_id']);
        $this->setCor($conn, $prod['fk_cor_id']);
    }

    function getServicos($conn)
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
                                pos.descricao as posicao,
                                pps.comment
                        FROM pedido_produto_servico as pps
                        JOIN servico_tamanho_preco stp  ON pps.fk_servico_id = stp.id
                        JOIN servico s                  ON s.id = stp.fk_servico_id
                        JOIN posicao pos                ON pos.id = pps.fk_posicao_id
                        WHERE pps.fk_pedido_produto_id = ?';

        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getIdPedidoProduto());
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }
}
