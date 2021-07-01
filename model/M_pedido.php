<?php


class Pedido
{
    private $id;
    private $user;
    private $date;
    private $description = "Sem descricao";
    private $status;
    private $date_orcamento;
    private $custo_orcado;
    private $comment;

    function preencher($conn, $id)
    {
        //semelhante á um  construtor... constroi vazio e chama essa funcao passando um ID.
        //Buscar infos do Pedido e do Usuario
        $query =   'SELECT * FROM pedido WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedido = $result->fetch_assoc();

        //Setar informações do Pedido, Instanciando um Usuário

        $this->id = $pedido['id'];

        require_once "M_user.php";
        $usuario = new User();
        $usuario->preencher($conn, $pedido['fk_user_id']);
        $this->user = $usuario;
        $this->date = $pedido['date'];
        $this->description = $pedido['description'];
        $this->status = $pedido['status'];
        $this->date_orcamento = $pedido['date_orcamento'];
        $this->custo_orcado = $pedido['custo_orcado'];
        $this->comment = $pedido['comment'];
    }

    // =-=-= MÉTODOS ESTÁTICOS =-=-=

    public static function getPedidos($conn, $status)
    {

        //Pegar todos os pedidos, dependendo do status

        $query = 'SELECT id FROM pedido WHERE status = ? order by date';
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $status);
        $stmt->execute();
        $ids = $stmt->get_result();

        if ($ids->num_rows > 0) {

            // PARA CADA PEDIDO NÃO ORÇADO:
            // 1. Construir um Objeto de Pedido
            // 2. Preencher este Objeto com infos de um ID já existente;
            // 3. Anexar em Array, que será retornado

            $array = [];

            while ($id = $ids->fetch_assoc()) {
                $pedido = new Pedido();
                $pedido->preencher($conn, $id['id']);
                array_push($array, $pedido);
            }

            return $array;
        } else {
            return 0;
        }
    }

    // =-=-= GETS E SETS

    //ID
    function getId()
    {
        return $this->id;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    //User
    function getUser()
    {
        return $this->user;
    }

    function setUser($conn, $id)
    {
        require_once "M_user.php";
        $user = new User();
        $user->preencher($conn, $id);
        $this->user = $user;
    }

    //DATE
    function getDate()
    {
        return $this->date;
    }

    function setDate($date)
    {
        $this->date = $date;
    }

    //DESCRIPTION
    function getDescription()
    {
        return $this->description;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    //STATUS
    function getStatus()
    {
        return $this->status;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    //DATE ORCAMENTO
    function getDateOrcamento()
    {
        return $this->date_orcamento;
    }

    function setDateOrcamento($x)
    {
        $this->date_orcamento = $x;
    }

    //CUSTO ORCADO
    function getCusto_Orcado()
    {
        return $this->custo_orcado;
    }

    function setCusto_Orcado($x)
    {
        $this->custo_orcado = $x;
    }

    //COMMENT
    function getComment()
    {
        return $this->comment;
    }

    function setComment($x)
    {
        $this->comment = $x;
    }


    // =-=-= DEMAIS MÉTODOS ---- 


    function getProdutos($conn)
    {
        //1. BUSCAR CADA PRODUTO DO PEDIDO (APENAS O ID)
        //2. COM CADA ID, montar um objeto de PEDIDO_PRODUTO (Carrega infos do produto + dados específicos ao pedido)
        //3. ADICIONAR À UM ARRAY
        //4. RETORNAR O ARRAY.

        $query =   'SELECT id FROM pedido_produto  
                        WHERE fk_pedido_id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getId());
        $stmt->execute();
        $ids = $stmt->get_result();

        require_once "M_product.php";

        $array = [];

        while ($id = $ids->fetch_assoc()) {

            $pedido_produto = new Pedido_Produto();
            $pedido_produto->preencherPedidoProduto($conn, $id['id']);
            array_push($array, $pedido_produto);
        }

        return $array;
    }

    function computarTotal($conn)
    {
        //MÉTODO PARA CALCULAR O TOTAL DE UMA COTAÇÃO...

        //PARA CADA PRODUTO
        //Multiplicar o "mod Tamanho" e o "mod da Costura" no preco base
        //PARA CADA SERVICO DO PRODUTO
        //Adicionar custos dos serviços
        //Multiplicar tudo pela quantidade de produtos
        //Somar quantidade anterior

        $total = 0;

        $produtos = $this->getProdutos($conn);

        foreach ($produtos as $produto) {

            $custo  = $produto->getBaseCost();
            $custo *= $produto->getModTamanho();
            $custo *= $produto->getModCostura();

            $servicos = $produto->getServicos($conn);
            while ($servico = $servicos->fetch_assoc()) {
                $custo += $servico['preco'];
            }

            $custo *= $produto->getQtdeProdutos();
            $total += $custo;
        }

        return $total;
    }

    function criarPedido($conn)
    {

        $query =   'INSERT into pedido (fk_user_id,
                                        date,
                                        description,
                                        status)
                                VALUES (?,?,?,?)';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("issi", ($this->getUser())->getId(), $this->getDate(), $this->getDescription(), $this->getStatus());
        $stmt->execute();

        //PEGAR O ULTIMO PEDIDO CADASTRADO E ATRIBUIR À ESTE OBJETO DE PEDIDO

        $query =   'SELECT id FROM pedido WHERE fk_user_id = ? order by id desc limit 1';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", ($this->getUser())->getId());
        $stmt->execute();
        $search = $stmt->get_result();
        $pedido = $search->fetch_assoc();

        $this->setId($pedido['id']);
    }

    function cadastrarProduto($conn, $prod)
    {

        /* CADASTRA NA TABELA PEDIDO PRODUTO, E DEPOIS OS SERVICOS ADEQUADAMENTE

        TABELA PEDIDO_PRODUTO
        id - (não é inserido)
	    fk_pedido_id 
	    fk_produto_id
	    qtde_produtos
	    fk_costura_id
	    fk_cor_id
	    fk_tamanho_id 
        */
        $query =   'INSERT into pedido_produto (fk_pedido_id,
                                                fk_produto_id,
                                                qtde_produtos,
                                                fk_costura_id,
                                                fk_cor_id,
                                                fk_tamanho_id)
                                VALUES (?,?,?,?,?,?)';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("iiiiii", $this->getId(), $prod->product, $prod->quantidade, $prod->costura, $prod->cor, $prod->tamanho);
        $stmt->execute();

        //PEGAR O ULTIMO PEDIDO_PRODUTO CADASTRADO

        $query =   'SELECT id FROM pedido_produto WHERE fk_pedido_id = ? order by id desc limit 1';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getId());
        $stmt->execute();
        $search = $stmt->get_result();
        $pedido_produto = $search->fetch_assoc();
        $produto = $pedido_produto['id'];

        /*
        CAMPOS DA TABELA PEDIDO_PRODUTO_SERVICO
            id
            fk_servico_id
            fk_pedido_produto_id
            fk_posicao_id
            comment
        */

        foreach ($prod->servicos as $servico) {

            $comment = "Sem comentários";

            $query = 'INSERT INTO pedido_produto_servico   (fk_servico_id,
                                                            fk_pedido_produto_id,
                                                            fk_posicao_id,
                                                            comment)
                                                            VALUES ( ? , ? , ? , ? )';
            $stmt = $conn->prepare($query);
            @$stmt->bind_param("iiis", $servico->tamanho, $produto, $servico->posicao, $comment);
            $stmt->execute();
        }
    }
    function aferirOrcamento($conn)
    {
        $status = 1;
        $query =    'UPDATE pedido SET date_orcamento = ?, custo_orcado = ?, comment = ?, status = ? WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("sdsii", $this->getDateOrcamento(), $this->getCusto_Orcado(), $this->getComment(), $status, $this->getId());
        $stmt->execute();
    }
}
