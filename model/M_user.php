<?php

require_once "../lib/crypto.php";

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
        require_once '../lib/crypto.php';
        $query = 'SELECT * FROM user WHERE email LIKE ? AND user_password LIKE ? LIMIT 1';
        $stmt = $conn->prepare($query);
        @$stmt->bind_param("ss", $this->getEmail(), $this->getPassword());
        $stmt->execute();
        $search = $stmt->get_result();
        return $search;
    }
}
