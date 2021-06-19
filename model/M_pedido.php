<?php


class Pedido
{
    private $id;
    private $user;
    private $date;
    private $description;
    private $status;
    private $custo_orcado;

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
        $this->custo_orcado = $pedido['custo_orcado'];
    }

    // =-=-= MÉTODOS ESTÁTICOS =-=-=

    public static function getPedidosNaoOrcados($conn)
    {

        //Pegar todos os pedidos sem orçamento

        $query = 'SELECT id FROM pedido WHERE status = 0';
        $stmt = $conn->prepare($query);
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
                $pedido->preencher($conn, $id);
                array_push($array, $pedido);
            }

            return $array;
        } else {
            return 0;
        }
    }

    public static function getPedidosOrcados($conn)
    {

        //Pegar todos os pedidos sem orçamento

        $query = 'SELECT id FROM pedido WHERE status = 1';
        $stmt = $conn->prepare($query);
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
                $pedido->preencher($conn, $id);
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
        $this->user = new User($conn, $id);
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

    //STATUS
    function getCusto_Orcado()
    {
        return $this->custo_orcado;
    }

    function setCusto_Orcado($x)
    {
        $this->custo_orcado = $x;
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
}
