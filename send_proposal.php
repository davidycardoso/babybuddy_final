<?php
session_start();
include 'conexao.php';

// Verifica se o responsável está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php");  // Se não for responsável, redireciona para o login
    exit;
}

if (isset($_POST['babysitter_id']) && isset($_POST['proposal_message'])) {
    $babysitter_id = $_POST['babysitter_id'];
    $proposal_message = $_POST['proposal_message'];
    $guardian_id = $_SESSION['user_id']; // ID do responsável

    // Insere a proposta no banco de dados
    $sql = "INSERT INTO proposals (babysitter_id, guardian_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $babysitter_id, $guardian_id, $proposal_message);

    if ($stmt->execute()) {
        echo "Proposta enviada com sucesso!";
    } else {
        echo "Erro ao enviar a proposta. Tente novamente.";
    }
} else {
    echo "Dados da proposta não foram enviados corretamente.";
}

$conn->close();
?>
