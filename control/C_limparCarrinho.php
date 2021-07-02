<?php

setcookie('cart', "", time(), "/");

$msg = "O carrinho foi limpo!";
header("Location: ../catalogo.php?msg={$msg}");
