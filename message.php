<?php
session_start();
include('conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Identificar o destinatário
if (isset($_GET['conversation_id'])) {
    $receiver_id = $_GET['conversation_id'];
} else {
    die("ID da conversa não especificado.");
}

// Enviar mensagem
if (isset($_POST['send_message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $receiver_id, $message);
        $stmt->execute();
        header("Location: message.php?conversation_id=$receiver_id"); // Redireciona após o envio
        exit();
    }
}

// Recuperar mensagens
$sql = "SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?)
        ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $receiver_id, $receiver_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mensagens</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .message { margin-bottom: 15px; }
        .message strong { color: #007BFF; }
        .message.you { text-align: right; }
        form { margin-top: 20px; }
        textarea { width: 100%; height: 100px; margin-bottom: 10px; }
        button { padding: 10px 15px; background-color: #007BFF; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Mensagens</h2>
    <div>
        <?php while ($message = $result->fetch_assoc()): ?>
            <div class="message <?php echo ($message['sender_id'] == $user_id) ? 'you' : ''; ?>">
                <p>
                    <strong><?php echo ($message['sender_id'] == $user_id) ? 'Você' : 'Outro Usuário'; ?>:</strong>
                    <?php echo htmlspecialchars($message['message']); ?>
                </p>
                <small><?php echo $message['timestamp']; ?></small>
            </div>
        <?php endwhile; ?>
    </div>
    <form method="POST">
        <textarea name="message" placeholder="Digite sua mensagem" required></textarea>
        <button type="submit" name="send_message">Enviar</button>
    </form>
</body>
</html>
