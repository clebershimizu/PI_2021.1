<?php

require_once "../lib/crypto.php";
require_once "../lib/sanitize.php";
class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $cpf_cnpj;
    public $cep;
    public $number;
    public $complement;

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
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $this->name = $name;
    }

    //EMAIL
    function getEmail()
    {
        return $this->email;
    }
    function setEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $this->email = crypto($email);
    }

    //SENHA
    function getPassword()
    {
        return $this->password;
    }
    function setPassword($password)
    {
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $this->password = crypto($password);
    }

    //CPF_CNPJ
    function getCPF_CNPJ()
    {
        return $this->cpf_cnpj;
    }
    function setCPF_CNPJ($cpf_cnpj)
    {
        $cpf_cnpj = filter_var($cpf_cnpj, FILTER_SANITIZE_STRING);
        $this->cpf_cnpj = crypto($cpf_cnpj);
    }

    //CEP
    function getCEP()
    {
        return $this->cep;
    }
    function setCEP($cep)
    {
        $cep = filter_var($cep, FILTER_SANITIZE_STRING);
        $this->cep = crypto($cep);
    }

    //NUMBER
    function getNumber()
    {
        return $this->number;
    }
    function setNumber($number)
    {
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        $this->number = crypto($number);
    }

    //COMPLEMENT
    function getComplement()
    {
        return $this->complement;
    }
    function setComplement($complement)
    {
        $complement = filter_var($complement, FILTER_SANITIZE_STRING);
        $this->complement = crypto($complement);
    }

    //DEMAIS MÉTODOS DO USUÁRIO

    //CADASTRO DE USUÁRIO
    function registerUser($conn)
    {
        $query = "INSERT INTO user (name, email, user_password, cnpj_cpf, cep, house_number, complement)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $this->getName(), $this->getEmail(), $this->getPassword(), $this->getCPF_CNPJ(), $this->getCEP(), $this->getNumber(), $this->getComplement());
        $stmt->execute();
    }
    //FUNÇÃO PARA VERIFICAR LOGIN
    function searchLogin($conn)
    {
        require_once '../lib/crypto.php';
        $h_Email = crypto($this->getEmail());
        $h_Password = crypto($this->getPassword());
        $query = 'SELECT * FROM user WHERE email LIKE ? AND passcode LIKE ? LIMIT 1';
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $$h_Email, $h_Password);
        $stmt->execute();
        $search = $stmt->get_result();
        $user = $search->fetch_assoc();
        return $user;
    }
}
