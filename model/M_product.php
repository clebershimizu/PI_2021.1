<?php

// =-= ESTE ARQUIVO POSSUI DUAS CLASSES, PRODUTO E SUA EXTENSÃO, PEDIDO_PRODUTO.

class Product
{
    private $id_produto;
    private $base_cost;
    private $img_url;
    private $tecido;
    private $tipo_peca;



    // GETS E SETS

    //ID Produto
    function setIdProduto($id)
    {
        $s_id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if (!$s_id) throw new Exception('Valor para ID inválido.');
        $this->id_produto = $s_id;
    }
    function getIdProduto()
    {
        return $this->id_produto;
    }

    //Base Cost
    function setBaseCost($bc)
    {
        $s_bc = filter_var($bc, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        if (!$s_bc) throw new Exception('Valor para Custo Base inválido.');
        $this->base_cost = $s_bc;
    }
    function getBaseCost()
    {
        return $this->base_cost;
    }

    //IMG URL
    function setImgUrl($url)
    {
        $s_url = filter_var($url, FILTER_SANITIZE_STRING, );
        if (!$s_url) throw new Exception('Valor para URL da Imagem inválido.');
        $this->img_url = $s_url;
    }
    function getImgUrl()
    {
        return $this->img_url;
    }

    //Tecido
    function setTecido($tecido)
    {
        $s_tec = filter_var($tecido, FILTER_SANITIZE_STRING);
        if (!$s_tec) throw new Exception('Valor para Tecido inválido.');
        $this->tecido = $s_tec;
    }
    function getTecido()
    {
        return $this->tecido;
    }

    //Tipo Peca
    function setTipoPeca($tp)
    {
        $s_tp = filter_var($tp, FILTER_SANITIZE_STRING);
        if (!$s_tp) throw new Exception('Valor para Tipo Peca inválido.');
        $this->tipo_peca = $s_tp;
    }
    function getTipoPeca()
    {
        return $this->tipo_peca;
    }

    //Posicoes
    function getPosicoes($conn)
    {
        $query =   'SELECT * FROM posicao 
                    WHERE fk_produto_id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getIdProduto());
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    //DEMAIS MÉTODOS

    function registerProduct($conn, $array_str_pos)
    {

        //INSERIR O NOVO PRODUTO, E PEGAR O ID NO BANCO
        $query =   'INSERT INTO produto (
                    base_cost,
                    image_url,
                    tecido,
                    tipo_peca,
                    available)
                    VALUES ( ? , ? , ? , ? , 1 );';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param(
            "dsss",
            $this->getBaseCost(),
            $this->getImgUrl(),
            $this->getTecido(),
            $this->getTipoPeca()
        );
        $stmt->execute();

        $query =   ' SELECT id FROM `produto` ORDER BY id DESC LIMIT 1';
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $search = $stmt->get_result();
        $id = $search->fetch_assoc();
        $id = $id['id'];

        //INSERIR AS POSICOES DO PRODUTO

        $query =   'INSERT INTO posicao (descricao, fk_produto_id) VALUES ( ? , ? )';
        $stmt = $conn->prepare($query);

        foreach ($array_str_pos as $descricao) {
            @$stmt->bind_param("si",  $descricao, $id);
            $stmt->execute();
        }
    }

    function updateProduct($conn, $array_str_pos)
    {

        //UPDATE DADOS DO PRODUTO, E PEGAR O ID NO BANCO
        $query =   'UPDATE produto SET
                    base_cost = ?,
                    image_url = ?,
                    tecido = ?,
                    tipo_peca = ?
                    WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param(
            "dsssi",
            $this->getBaseCost(),
            $this->getImgUrl(),
            $this->getTecido(),
            $this->getTipoPeca(),
            $this->getIdProduto()
        );
        $stmt->execute();


        try {

            //DELETAR POSIÇÕES ANTIGAS (ISSO VAI DAR PROBLEMA UMA HORA.)
            //POIS SE DELETAR POSIÇÕES QUE JÁ FORAM REFERENCIADAS O DELETE NÃO FUNCIONARÁ.
            $query =   'DELETE FROM posicao WHERE fk_produto_id = ? ';
            $stmt = $conn->prepare($query);
            @$stmt->bind_param("i", $this->getIdProduto());
            $stmt->execute();

            $query =   'SELECT id FROM posicao WHERE fk_produto_id = ? ';
            $stmt = $conn->prepare($query);
            @$stmt->bind_param("i", $this->getIdProduto());
            $stmt->execute();
            $search = $stmt->get_result();

            if ($search->num_rows == 0) {

                //INSERIR AS NOVAS POSICOES DO PRODUTO

                $query =   'INSERT INTO posicao (descricao, fk_produto_id) VALUES ( ? , ? )';
                $stmt = $conn->prepare($query);

                foreach ($array_str_pos as $descricao) {
                    @$stmt->bind_param("si",  $descricao, $this->getIdProduto() );
                    $stmt->execute();
                }
            }
        } catch (Exception $e) {
            //só pra nao quebrar violentamente
        }
    }

    function deactivateProduct($conn)
    {
        $query =   'UPDATE produto SET available = 0 
                    WHERE fk_produto_id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getIdProduto());
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    //MÉTODOS ESTÁTICOS

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

        //Pegar todos os produtos cadastrados (DISPONÍVEIS, available = 1)

        $query = 'SELECT id FROM produto WHERE available = 1';
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
