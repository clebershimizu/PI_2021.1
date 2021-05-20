<?php

class Connection
{
    public function connect()
    {
        $servidor = "localhost";
        $usuario = "root";
        $senha = "";
        $basededados = "gms_banco";
        $porta = "3306";

        // Conexão com banco de dados
        $conn = new mysqli($servidor, $usuario, $senha, $basededados, $porta);

        return $conn;
    }
}
