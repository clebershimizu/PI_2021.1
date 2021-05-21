<?php
function sha3_256($value)
{
    $salt = ',z@&M<e1G3azMfh!Oe|sjRZ9>9m_/mU8J1rWS^P|KX!|g5:KG|GpH*)Hf#%t Pgy';
    return hash("sha3-256", $salt . $value);
}


function aes_256($action, $string)
{
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = '/B?E(G+KbPeShVmYq3t6w9z$C&F)J@Mc';   //chave 256-bit, 32 bytes
    $secret_iv = 'v9y$B&E)H@McQfTj';                    //chave 128-bit, 16 bytes

    // hash
    $key = hash('sha3-256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes 
    $iv = substr(hash('sha3-256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
