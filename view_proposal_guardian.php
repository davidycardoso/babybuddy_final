<?php
session_start();
include('conexao.php');

// Verifica se a conexão foi bem-sucedida
if ($conn === false) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o responsável está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

$proposal_id = $_GET['id'] ?? null;
if (!$proposal_id) {
    die('Proposta não encontrada.');
}

// Consulta os detalhes da proposta
$sql = "SELECT p.id, bs.name AS babysitter_name, p.status, p.babysitter_id, p.guardian_id
        FROM proposals p
        JOIN babysitters bs ON p.babysitter_id = bs.id
        WHERE p.id = ? AND p.guardian_id = ?";
$stmt = $conn->prepare($sql);

// Verifica se houve erro na preparação da consulta
if ($stmt === false) {
    die("Erro ao preparar a consulta SQL: " . $conn->error);
}

$stmt->bind_param("ii", $proposal_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se há resultados
if ($result->num_rows === 0) {
    die('Proposta não encontrada ou você não tem permissão para visualizar essa proposta.');
}

// Recupera a proposta
$proposal = $result->fetch_assoc();

// Envio de mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $sender_id = $_SESSION['user_id']; // O responsável é quem envia a mensagem
    $receiver_id = $proposal['babysitter_id']; // A babá é quem recebe a mensagem

    // Insere a mensagem no banco de dados
    $message_sql = "INSERT INTO messages (proposal_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)";
    $message_stmt = $conn->prepare($message_sql);

    // Verifica se houve erro na preparação da consulta para inserir mensagem
    if ($message_stmt === false) {
        die("Erro ao preparar a consulta SQL para inserir a mensagem: " . $conn->error);
    }

    $message_stmt->bind_param("iiis", $proposal_id, $sender_id, $receiver_id, $message);
    if ($message_stmt->execute()) {
        header("Location: view_proposal_guardian.php?id=" . $proposal_id);
        exit();
    } else {
        die("Erro ao enviar a mensagem: " . $message_stmt->error);
    }
}

// Consulta as mensagens da proposta
$message_sql = "SELECT m.message, 
                       CASE
                           WHEN m.sender_id = p.babysitter_id THEN bs.name
                           WHEN m.sender_id = p.guardian_id THEN g.name
                       END AS sender_name,
                       m.created_at
                FROM messages m
                LEFT JOIN babysitters bs ON m.sender_id = bs.id
                LEFT JOIN guardians g ON m.sender_id = g.id
                LEFT JOIN proposals p ON m.proposal_id = p.id
                WHERE m.proposal_id = ?
                ORDER BY m.created_at ASC";
$message_stmt = $conn->prepare($message_sql);

// Verifica se houve erro na preparação da consulta para mensagens
if ($message_stmt === false) {
    die("Erro ao preparar a consulta SQL para obter mensagens: " . $conn->error);
}

$message_stmt->bind_param("i", $proposal_id);
$message_stmt->execute();
$message_result = $message_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Proposta</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="proposal-container">
    <h2>Detalhes da Proposta</h2>
    
    <p><strong>Babá:</strong> <?php echo htmlspecialchars($proposal['babysitter_name']); ?></p>
    <p><strong>Status:</strong> <?php echo ucfirst($proposal['status']); ?></p>

    <!-- Exibição de mensagens -->
    <h3>Mensagens:</h3>
    <div class="messages">
        <?php while ($message = $message_result->fetch_assoc()): ?>
            <p><strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
        <?php endwhile; ?>
    </div>

    <!-- Formulário para enviar uma nova mensagem -->
    <form action="view_proposal_guardian.php?id=<?php echo $proposal['id']; ?>" method="post">
        <textarea name="message" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit" class="btn">Enviar Mensagem</button>
    </form>

</div>

</body>
</html>
