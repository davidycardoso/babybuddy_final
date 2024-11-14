<?php
session_start();
include('conexao.php');

// Verifica se a babá está logada
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'babysitter') {
    header("Location: login.php");
    exit();
}

// Pega o ID da babá logada
$babysitter_id = $_SESSION['user_id'];

// Consulta para pegar as informações da babá
$sql = "SELECT * FROM babysitters WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Erro na preparação da consulta de babá: ' . $conn->error);
}
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();
$babysitter = $result->fetch_assoc();

// Consulta para pegar o número de propostas pendentes
$sql_proposals = "SELECT COUNT(*) as pending_count FROM proposals WHERE babysitter_id = ? AND status = 'sent'";
$stmt_proposals = $conn->prepare($sql_proposals);
if ($stmt_proposals === false) {
    die('Erro na preparação da consulta de propostas pendentes: ' . $conn->error);
}
$stmt_proposals->bind_param("i", $babysitter_id);
$stmt_proposals->execute();
$result_proposals = $stmt_proposals->get_result();
$pending_proposals = $result_proposals->fetch_assoc();

// Consulta para pegar o número total de propostas (todas as status)
$sql_all_proposals = "SELECT COUNT(*) as total_count FROM proposals WHERE babysitter_id = ?";
$stmt_all_proposals = $conn->prepare($sql_all_proposals);
if ($stmt_all_proposals === false) {
    die('Erro na preparação da consulta de propostas totais: ' . $conn->error);
}
$stmt_all_proposals->bind_param("i", $babysitter_id);
$stmt_all_proposals->execute();
$result_all_proposals = $stmt_all_proposals->get_result();
$total_proposals = $result_all_proposals->fetch_assoc();

// Consulta para pegar as notificações da babá
// Consulta para pegar as notificações da babá
$sql_notifications = "SELECT COUNT(*) as notification_count FROM notifications WHERE babysitter_id = ? AND status = 'unread'";
$stmt_notifications = $conn->prepare($sql_notifications);
if ($stmt_notifications === false) {
    die('Erro na preparação da consulta de notificações: ' . $conn->error);
}
$stmt_notifications->bind_param("i", $babysitter_id);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();
$notification_count = $result_notifications->fetch_assoc();


// Consulta para pegar as avaliações da babá
$sql_reviews = "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews FROM reviews WHERE babysitter_id = ?";
$stmt_reviews = $conn->prepare($sql_reviews);
if ($stmt_reviews === false) {
    die('Erro na preparação da consulta de avaliações: ' . $conn->error);
}
$stmt_reviews->bind_param("i", $babysitter_id);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();
$reviews = $result_reviews->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard da Babá</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="dashboard-container">
    <!-- Informações da Babá -->
    <div class="babysitter-info">
        <img src="<?php echo $babysitter['photo'] ?: 'default-avatar.jpg'; ?>" alt="Foto da Babá" class="profile-photo">
        <h2><?php echo $babysitter['name']; ?></h2>
        <p><strong>Propostas Pendentes:</strong> <?php echo $pending_proposals['pending_count']; ?></p>
        <p><strong>Total de Propostas:</strong> <?php echo $total_proposals['total_count']; ?></p>
    </div>

    <!-- Resumo Rápido -->
    <div class="summary">
        <h3>Resumo das Propostas</h3>
        <ul>
            <li><strong>Pendentes:</strong> <?php echo $pending_proposals['pending_count']; ?></li>
            <li><strong>Total de Propostas:</strong> <?php echo $total_proposals['total_count']; ?></li>
        </ul>
    </div>

    <!-- Notificações -->
    <div class="notifications">
        <h3>Notificações</h3>
        <p>Você tem <strong><?php echo $notification_count['notification_count']; ?></strong> nova(s) notificação(ões).</p>
        <a href="notifications.php">Ver Notificações</a>
    </div>

    <!-- Avaliações -->
    <div class="reviews">
        <h3>Avaliações</h3>
        <p>Média de Avaliações: <?php echo number_format($reviews['average_rating'], 1); ?> / 5</p>
        <p>Total de Avaliações: <?php echo $reviews['total_reviews']; ?></p>
        <a href="reviews.php">Ver Avaliações</a>
    </div>

    <!-- Botão para ir para a página de Propostas Detalhadas -->
    <div class="proposals-link">
        <a href="proposals.php">Ver Propostas Detalhadas</a>
    </div>

</div>

</body>
</html>
