<?php
$servidor = "127.0.0.1"; 
$usuario = "root"; 
$senha = "usbw";   // se usa XAMPP deixe vazio, no USBWebserver é "usbw"
$banco = "viajemais";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("❌ Falha na conexão: " . $conn->connect_error);
}
?>
