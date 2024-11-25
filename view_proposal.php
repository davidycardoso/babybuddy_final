<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado e é uma babá
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'babysitter') {
    header("Location: login.php");
    exit();
}

// Verifica se o ID da proposta foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Proposta não encontrada.');
}

$proposal_id = intval($_GET['id']);

// Consulta os detalhes da proposta
$sql = "
    SELECT p.id, g.name AS guardian_name, p.status, p.babysitter_id, p.guardian_id, p.message
    FROM proposals p
    JOIN guardians g ON p.guardian_id = g.id
    WHERE p.id = ? AND p.babysitter_id = ?
";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar a consulta SQL: " . $conn->error);
}
$stmt->bind_param("ii", $proposal_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Proposta não encontrada ou não autorizada.");
}

$proposal = $result->fetch_assoc();

// Ações: Aceitar, Rejeitar ou Concluir
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    // Aceitar proposta
    if ($action === 'accept') {
        $status = 'em_andamento';
    }
    // Rejeitar proposta
    elseif ($action === 'reject') {
        $status = 'rejeitada';
    }
    // Concluir proposta (apenas se a proposta estiver em andamento)
    elseif ($action === 'complete' && $proposal['status'] === 'em_andamento') {
        $status = 'concluida';
    } else {
        $status = null;
    }

    if ($status) {
        $update_sql = "UPDATE proposals SET status = ? WHERE id = ? AND babysitter_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt === false) {
            die("Erro ao preparar a atualização: " . $conn->error);
        }
        $update_stmt->bind_param("sii", $status, $proposal_id, $_SESSION['user_id']);
        if ($update_stmt->execute()) {
            header("Location: view_proposal.php?id=" . $proposal_id);
            exit();
        } else {
            die("Erro ao atualizar a proposta: " . $update_stmt->error);
        }
    }
}

// Enviar mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $sender_id = $_SESSION['user_id'];
<<<<<<< HEAD
        $receiver_id = $proposal['guardian_id'];

        $message_sql = "
            INSERT INTO messages (proposal_id, sender_id, receiver_id, message)
=======
        $recipient_id = $proposal['guardian_id'];

        $message_sql = "
            INSERT INTO messages (proposal_id, sender_id, recipient_id, message)
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
            VALUES (?, ?, ?, ?)
        ";
        $message_stmt = $conn->prepare($message_sql);
        if ($message_stmt === false) {
            die("Erro ao preparar a consulta para mensagens: " . $conn->error);
        }
<<<<<<< HEAD
        $message_stmt->bind_param("iiis", $proposal_id, $sender_id, $receiver_id, $message);
=======
        $message_stmt->bind_param("iiis", $proposal_id, $sender_id, $recipient_id, $message);
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
        if ($message_stmt->execute()) {
            header("Location: view_proposal.php?id=" . $proposal_id);
            exit();
        } else {
            die("Erro ao enviar a mensagem: " . $message_stmt->error);
        }
    }
}

// Consultar mensagens
$message_sql = "
    SELECT m.message, 
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
    ORDER BY m.created_at ASC
";
$message_stmt = $conn->prepare($message_sql);
if ($message_stmt === false) {
    die("Erro ao preparar a consulta para mensagens: " . $conn->error);
}
$message_stmt->bind_param("i", $proposal_id);
$message_stmt->execute();
$message_result = $message_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Proposta</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="proposal-container">
    <h2>Detalhes da Proposta</h2>
    <p><strong>Responsável:</strong> <?php echo htmlspecialchars($proposal['guardian_name']); ?></p>
    <p><strong>Proposta:</strong> <?php echo htmlspecialchars($proposal['message']); ?></p>
    <p><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($proposal['status'])); ?></p>

    <?php if ($proposal['status'] === 'pendente'): ?>
        <div class="action-buttons">
            <a href="view_proposal.php?id=<?php echo $proposal['id']; ?>&action=accept" class="btn btn-accept">Aceitar</a>
            <a href="view_proposal.php?id=<?php echo $proposal['id']; ?>&action=reject" class="btn btn-reject">Rejeitar</a>
        </div>
    <?php elseif ($proposal['status'] === 'em_andamento'): ?>
        <div class="action-buttons">
            <a href="view_proposal.php?id=<?php echo $proposal['id']; ?>&action=complete" class="btn btn-complete">Concluir</a>
        </div>
    <?php else: ?>
        <p>Resposta já registrada. Não é possível alterar o status.</p>
    <?php endif; ?>

    <h3>Mensagens</h3>
    <div class="messages">
        <?php while ($message = $message_result->fetch_assoc()): ?>
            <div class="message">
                <p><strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <form action="view_proposal.php?id=<?php echo $proposal['id']; ?>" method="post" class="message-form">
        <textarea name="message" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit" class="btn btn-send">Enviar Mensagem</button>
    </form>
</div>
</body>
</html>
