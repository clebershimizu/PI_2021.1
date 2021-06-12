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
