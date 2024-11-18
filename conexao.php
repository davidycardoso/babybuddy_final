<?php
$servername = "localhost"; // O servidor MySQL (geralmente localhost)
$username = "root";        // O usuário do banco de dados (padrão é 'root')
$password = "root";            // A senha (se tiver uma senha, coloque-a aqui)
$dbname = "babybuddy";     // O nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("A conexão falhou: " . $conn->connect_error);
}
?>
