<?php
session_start();
include('conexao.php');

// Verificando se a babá está logada
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'babysitter') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['proposal_id']) && isset($_POST['response_message'])) {
    $proposal_id = $_POST['proposal_id'];
    $response_message = $_POST['response_message'];

    // Inserir resposta no banco de dados
    $sql = "INSERT INTO responses (proposal_id, babysitter_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $proposal_id, $_SESSION['user_id'], $response_message);
    
    if ($stmt->execute()) {
        header("Location: proposal.php"); // Redireciona para a página de propostas
        exit();
    } else {
        echo "Erro ao responder a proposta.";
    }
}
?>
