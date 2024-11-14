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
    die('Proposta não encontrada.');
}

$proposal_id = $_GET['id'];

// Consulta para pegar os detalhes da proposta
$sql = "
    SELECT p.id, g.name AS guardian_name, p.status, p.babysitter_id, p.guardian_id
    FROM proposals p
    JOIN guardians g ON p.guardian_id = g.id
    WHERE p.id = ? AND p.babysitter_id = ?
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar a consulta SQL para pegar os detalhes da proposta: " . $conn->error);
}

$stmt->bind_param("ii", $proposal_id, $_SESSION['user_id']);
if ($stmt->execute() === false) {
    die("Erro ao executar a consulta: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Proposta não encontrada.");
}

$proposal = $result->fetch_assoc();

// Lógica para aceitar a proposta
if (isset($_GET['action']) && $_GET['action'] == 'accept') {
    $update_sql = "UPDATE proposals SET status = 'accepted' WHERE id = ? AND babysitter_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt === false) {
        die("Erro ao preparar a consulta SQL para aceitar a proposta: " . $conn->error);
    }
    $update_stmt->bind_param("ii", $proposal_id, $_SESSION['user_id']);
    if ($update_stmt->execute()) {
        header("Location: view_proposal.php?id=" . $proposal_id);
        exit();
    } else {
        die("Erro ao aceitar a proposta: " . $update_stmt->error);
    }
}

// Lógica para rejeitar a proposta
if (isset($_GET['action']) && $_GET['action'] == 'reject') {
    $update_sql = "UPDATE proposals SET status = 'rejected' WHERE id = ? AND babysitter_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt === false) {
        die("Erro ao preparar a consulta SQL para rejeitar a proposta: " . $conn->error);
    }
    $update_stmt->bind_param("ii", $proposal_id, $_SESSION['user_id']);
    if ($update_stmt->execute()) {
        header("Location: view_proposal.php?id=" . $proposal_id);
        exit();
    } else {
        die("Erro ao rejeitar a proposta: " . $update_stmt->error);
    }
}

// Lógica para enviar uma mensagem
if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $sender_id = $_SESSION['user_id'];
    $recipient_id = ($proposal['babysitter_id'] == $sender_id) ? $proposal['guardian_id'] : $proposal['babysitter_id'];

    // Insere a mensagem no banco de dados
    $message_sql = "INSERT INTO messages (proposal_id, sender_id, recipient_id, message) VALUES (?, ?, ?, ?)";
    $message_stmt = $conn->prepare($message_sql);
    if ($message_stmt === false) {
        die("Erro ao preparar a consulta SQL para enviar a mensagem: " . $conn->error);
    }
    $message_stmt->bind_param("iiis", $proposal_id, $sender_id, $recipient_id, $message);
    if ($message_stmt->execute()) {
        header("Location: view_proposal.php?id=" . $proposal_id);
        exit();
    } else {
        die("Erro ao enviar a mensagem: " . $message_stmt->error);
    }
}


// Consulta as mensagens da proposta
$message_sql = "SELECT m.message, 
                CASE
                    WHEN m.sender_id = p.babysitter_id THEN bs.name
                    ELSE g.name
                END AS sender_name,
                m.created_at
                FROM messages m
                LEFT JOIN babysitters bs ON m.sender_id = bs.id
                LEFT JOIN guardians g ON m.sender_id = g.id
                LEFT JOIN proposals p ON m.proposal_id = p.id
                WHERE m.proposal_id = ?
                ORDER BY m.created_at ASC";
$message_stmt = $conn->prepare($message_sql);
if ($message_stmt === false) {
    die("Erro ao preparar a consulta SQL para pegar as mensagens: " . $conn->error);
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="proposal-container">
    <h2>Detalhes da Proposta</h2>
    
    <p><strong>Responsável:</strong> <?php echo $proposal['guardian_name']; ?></p>
    <p><strong>Status:</strong> <?php echo ucfirst($proposal['status']); ?></p>

    <!-- Botões para aceitar ou rejeitar -->
    <?php if ($proposal['status'] == 'sent'): ?>
        <a href="view_proposal.php?id=<?php echo $proposal['id']; ?>&action=accept" class="btn">Aceitar</a>
        <a href="view_proposal.php?id=<?php echo $proposal['id']; ?>&action=reject" class="btn">Rejeitar</a>
    <?php else: ?>
        <p>Os botões de resposta não estão disponíveis porque a proposta já foi respondida ou está com outro status.</p>
    <?php endif; ?>

    <!-- Exibição de mensagens -->
    <h3>Mensagens:</h3>
    <div class="messages">
        <?php while ($message = $message_result->fetch_assoc()): ?>
            <p><strong><?php echo $message['sender_name']; ?>:</strong> <?php echo $message['message']; ?></p>
        <?php endwhile; ?>
    </div>

    <!-- Formulário para enviar uma nova mensagem -->
    <form action="view_proposal.php?id=<?php echo $proposal['id']; ?>" method="post">
        <textarea name="message" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit" class="btn">Enviar Mensagem</button>
    </form>
</div>

</body>
</html>
