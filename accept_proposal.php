<?php
session_start();
include('conexao.php');

// Verifica se a babá está logada
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'babysitter') {
    header("Location: login.php");
    exit();
}

// Verifica se o ID da proposta foi passado
if (!isset($_GET['id'])) {
    die("ID da proposta não fornecido.");
}

// Pega o ID da proposta e da babá
$proposal_id = $_GET['id'];
$babysitter_id = $_SESSION['user_id'];

// Atualiza o status da proposta para 'aceita'
$sql = "UPDATE proposals SET status = 'accepted' WHERE id = ? AND babysitter_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $proposal_id, $babysitter_id);

if ($stmt->execute()) {
    echo "Proposta aceita com sucesso.";
    header("Location: proposals.php");  // Redireciona para a página de propostas
    exit();
} else {
    echo "Erro ao aceitar proposta: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
