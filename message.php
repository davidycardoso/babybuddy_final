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

// Verifica o tipo de usuário e determina o perfil
if ($user_type == 'guardian') {
    $receiver_id = $_GET['babysitter_id']; // ID da babá para quem enviar a mensagem
} elseif ($user_type == 'babysitter') {
    $receiver_id = $_GET['guardian_id']; // ID do responsável
}

// Enviar mensagem
if (isset($_POST['send_message'])) {
    $message = $_POST['message'];
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $receiver_id, $message);
    $stmt->execute();
    header("Location: message.php?babysitter_id=$receiver_id"); // Redireciona após o envio
    exit();
}

// Recuperar mensagens
$sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)";
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
</head>
<body>
    <h2>Mensagens</h2>
    <div>
        <?php while ($message = $result->fetch_assoc()): ?>
            <p><strong><?php echo ($message['sender_id'] == $user_id) ? 'Você' : 'Usuário'; ?>:</strong> <?php echo $message['message']; ?></p>
        <?php endwhile; ?>
    </div>
    <form method="POST">
        <textarea name="message" placeholder="Digite sua mensagem" required></textarea>
        <button type="submit" name="send_message">Enviar</button>
    </form>
</body>
</html>
