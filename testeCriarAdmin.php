<?php

require_once "lib/crypto.php";
require_once 'model/M_connection.php';
$dbConn = new Connection();
$conn = $dbConn->connect();

$admin = sha3_256($_POST['username']);
$senha = sha3_256($_POST['adminPassword']);
$query = "INSERT INTO admin (admin_username, admin_password) VALUES (? , ?)";
$stmt = $conn->prepare($query);
@$stmt->bind_param("ss", $admin, $senha);
$stmt->execute();


echo sha3_256("admin") . "<br>";
echo sha3_256("1234");
