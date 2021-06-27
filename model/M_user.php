<?php

$docroot = $_SERVER['DOCUMENT_ROOT'];
require_once "{$docroot}/PI_2021.1/lib/crypto.php";

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $cnpj_cpf;
    private $cep;
    private $number;
    private $complement;

    //TIPO UM CONSTRUTOR, QUE RECEBE UM ID, já monta um usuário
    //PORQUE NAO USAR CONSTRUTOR? Php não aceita overload... ótimo. Comentario do Leandro: KKKKKKKKKKKKKKKKKKKKKKKKK
    function preencher($conn, $id)
    {
        //BUSCA O USUARIO DO BANCO
        $query =   'SELECT * FROM user WHERE id = ?';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            //PREENCHE O OBJETO - tudo vem criptografado
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->password = $user['user_password'];
            $this->cnpj_cpf = $user['cnpj_cpf'];
            $this->cep = $user['cep'];
            $this->number = $user['house_number'];
            $this->complement = $user['complement'];
        } else {
            return 0;
        }
    }

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
    function getName()
    {
        return $this->name;
    }
    function setName($name)
    {
        $s_name = filter_var($name, FILTER_SANITIZE_STRING);
        if (!$s_name) throw new Exception('Valor para Nome inválido.');
        $this->name = aes_256("encrypt", $s_name);
    }

    //EMAIL
    function getEmail()
    {
        return $this->email;
    }
    function setEmail($email)
    {
        $s_email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!$s_email) throw new Exception('Email Inválido.');
        $this->email = aes_256("encrypt", $s_email);
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

    //CPF_CNPJ
    function getCNPJ_CPF()
    {
        return $this->cnpj_cpf;
    }
    function setCNPJ_CPF($cnpj_cpf)
    {
        $s_cnpj_cpf = filter_var($cnpj_cpf, FILTER_SANITIZE_NUMBER_INT);
        if (!$s_cnpj_cpf) throw new Exception('CNPJ/CPF inválido.');
        $this->cnpj_cpf = aes_256("encrypt", $s_cnpj_cpf);
    }

    //CEP
    function getCEP()
    {
        return $this->cep;
    }
    function setCEP($cep)
    {
        $s_cep = filter_var($cep, FILTER_SANITIZE_NUMBER_INT);
        if (!$s_cep) throw new Exception('CEP inválido.');
        $this->cep = aes_256("encrypt", $s_cep);
    }

    //NUMBER
    function getNumber()
    {
        return $this->number;
    }
    function setNumber($number)
    {
        $s_number = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
        if (!$s_number) throw new Exception('Valor para Número inválido.');
        $this->number = aes_256("encrypt", $s_number);
    }

    //COMPLEMENT
    function getComplement()
    {
        return $this->complement;
    }
    function setComplement($complement)
    {
        $s_complement = filter_var($complement, FILTER_SANITIZE_STRING);
        if (!$s_complement) throw new Exception('Valor para Complemento inválido.');
        $this->complement = aes_256("encrypt", $s_complement);
    }

    //DEMAIS MÉTODOS DO USUÁRIO

    //CADASTRO DE USUÁRIO
    function registerUser($conn)
    {
        $query = "INSERT INTO user (name, email, user_password, cnpj_cpf, cep, house_number, complement)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        @$stmt->bind_param("sssssss", $this->getName(), $this->getEmail(), $this->getPassword(), $this->getCNPJ_CPF(), $this->getCEP(), $this->getNumber(), $this->getComplement());
        $stmt->execute();
    }

    //FUNÇÃO PARA VERIFICAR LOGIN
    function searchLogin($conn)
    {
        $query = 'SELECT * FROM user WHERE email LIKE ? AND user_password LIKE ? LIMIT 1';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("ss", $this->getEmail(), $this->getPassword());
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }

    function updateUserData($conn)
    {
        $query =    "SELECT * FROM pedido WHERE fk_user_id = ? AND status > 1";
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getId());
        $stmt->execute();
        $ordersCheck = $stmt->get_result();

        if ($ordersCheck->num_rows > 0) {
            $query =    "SELECT cnpj_cpf FROM user WHERE id = ?";
            $stmt = $conn->prepare($query);
            @$stmt->bind_param("i", $this->getId());
            $stmt->execute();
            $cnpj_cpfCheck = $stmt->get_result();
            $cnpj_cpfCheck = $cnpj_cpfCheck->fetch_assoc();

            $this->setCNPJ_CPF(aes_256("decrypt", $cnpj_cpfCheck["cnpj_cpf"]));

        }
        $query =    "UPDATE user SET name = ? , email = ? , cnpj_cpf = ?, cep = ?  ,house_number = ? , complement = ? 
                    WHERE id = ?";
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("ssssssi", $this->getName(), $this->getEmail(), $this->getCNPJ_CPF(), $this->getCEP(), $this->getNumber(), $this->getComplement(), $this->getId());
        $stmt->execute();
    }
    function deleteUserData($conn)
    {
        $query =    "DELETE FROM user WHERE id = ?";
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getId());
        $stmt->execute();
    }
    function haveOrders($conn) {
        $query =    "SELECT * FROM pedido WHERE fk_user_id = ? AND status > 1";
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("i", $this->getId());
        $stmt->execute();
        $ordersCheck = $stmt->get_result();

        if ($ordersCheck->num_rows > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
