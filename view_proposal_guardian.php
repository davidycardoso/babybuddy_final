<?php
session_start();
include('conexao.php');

<<<<<<< HEAD
// Verifica se a conexão foi bem-sucedida
if ($conn === false) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

=======
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
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
<<<<<<< HEAD

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
=======
$stmt->bind_param("ii", $proposal_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$proposal = $result->fetch_assoc();

if (!$proposal) {
    die('Proposta não encontrada.');
}

// Envio de mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];
    $sender_id = $_SESSION['user_id'];
    $recipient_id = ($proposal['guardian_id'] === $sender_id) ? $proposal['babysitter_id'] : $proposal['guardian_id'];

    // Insere a mensagem no banco de dados
    $message_sql = "INSERT INTO messages (proposal_id, sender_id, recipient_id, message) VALUES (?, ?, ?, ?)";
    $message_stmt = $conn->prepare($message_sql);
    $message_stmt->bind_param("iiis", $proposal_id, $sender_id, $recipient_id, $message);
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
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
<<<<<<< HEAD
                           WHEN m.sender_id = p.guardian_id THEN g.name
=======
                           ELSE g.name
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
                       END AS sender_name,
                       m.created_at
                FROM messages m
                LEFT JOIN babysitters bs ON m.sender_id = bs.id
                LEFT JOIN guardians g ON m.sender_id = g.id
                LEFT JOIN proposals p ON m.proposal_id = p.id
                WHERE m.proposal_id = ?
                ORDER BY m.created_at ASC";
$message_stmt = $conn->prepare($message_sql);
<<<<<<< HEAD

// Verifica se houve erro na preparação da consulta para mensagens
if ($message_stmt === false) {
    die("Erro ao preparar a consulta SQL para obter mensagens: " . $conn->error);
}

$message_stmt->bind_param("i", $proposal_id);
$message_stmt->execute();
$message_result = $message_stmt->get_result();
=======
$message_stmt->bind_param("i", $proposal_id);
$message_stmt->execute();
$message_result = $message_stmt->get_result();

// Avaliação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Verifica se a proposta foi concluída
    if ($proposal['status'] === 'concluida') {
        $sql_review = "INSERT INTO reviews (babysitter_id, guardian_id, rating, comment) 
                       VALUES (?, ?, ?, ?)";
        $stmt_review = $conn->prepare($sql_review);
        $stmt_review->bind_param("iiis", $proposal['babysitter_id'], $_SESSION['user_id'], $rating, $comment);
        
        if ($stmt_review->execute()) {
            echo "Avaliação enviada com sucesso!";
            header("Location: view_proposal_guardian.php?id=" . $proposal_id);
            exit();
        } else {
            echo "Erro ao enviar avaliação: " . $stmt_review->error;
        }
    } else {
        echo "A proposta não foi concluída, portanto não é possível avaliar.";
    }
}

// Consulta as avaliações da babá
$sql_reviews = "SELECT r.rating, r.comment, r.created_at, g.name AS guardian_name 
                FROM reviews r 
                JOIN guardians g ON r.guardian_id = g.id 
                WHERE r.babysitter_id = ?";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("i", $proposal['babysitter_id']);
$stmt_reviews->execute();
$reviews_result = $stmt_reviews->get_result();
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Proposta</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="css/styles.css">
=======
    <link rel="stylesheet" href="styles.css">
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
</head>
<body>

<div class="proposal-container">
    <h2>Detalhes da Proposta</h2>
    
<<<<<<< HEAD
    <p><strong>Babá:</strong> <?php echo htmlspecialchars($proposal['babysitter_name']); ?></p>
=======
    <p><strong>Babá:</strong> <?php echo $proposal['babysitter_name']; ?></p>
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
    <p><strong>Status:</strong> <?php echo ucfirst($proposal['status']); ?></p>

    <!-- Exibição de mensagens -->
    <h3>Mensagens:</h3>
    <div class="messages">
        <?php while ($message = $message_result->fetch_assoc()): ?>
<<<<<<< HEAD
            <p><strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
=======
            <p><strong><?php echo $message['sender_name']; ?>:</strong> <?php echo $message['message']; ?></p>
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
        <?php endwhile; ?>
    </div>

    <!-- Formulário para enviar uma nova mensagem -->
    <form action="view_proposal_guardian.php?id=<?php echo $proposal['id']; ?>" method="post">
        <textarea name="message" placeholder="Digite sua mensagem..." required></textarea>
        <button type="submit" class="btn">Enviar Mensagem</button>
    </form>

<<<<<<< HEAD
=======
    <!-- Se a proposta estiver concluída, mostrar o formulário de avaliação -->
    <?php if ($proposal['status'] === 'concluida'): ?>
        <h3>Avaliação da Babá</h3>
        <form action="view_proposal_guardian.php?id=<?php echo $proposal['id']; ?>" method="post">
            <label for="rating">Avaliação:</label>
            <select name="rating" required>
                <option value="1">1 - Péssimo</option>
                <option value="2">2 - Ruim</option>
                <option value="3">3 - Regular</option>
                <option value="4">4 - Bom</option>
                <option value="5">5 - Excelente</option>
            </select>

            <label for="comment">Comentário:</label>
            <textarea name="comment" placeholder="Deixe um comentário..." required></textarea>

            <button type="submit" class="btn">Enviar Avaliação</button>
        </form>
    <?php endif; ?>

    <!-- Exibição das avaliações feitas para a babá -->
    <h3>Avaliações Recebidas:</h3>
    <div class="reviews">
        <?php while ($review = $reviews_result->fetch_assoc()): ?>
            <p><strong><?php echo $review['guardian_name']; ?>:</strong> <?php echo $review['rating']; ?> estrelas</p>
            <p><em><?php echo $review['comment']; ?></em></p>
            <p><small>Data: <?php echo $review['created_at']; ?></small></p>
        <?php endwhile; ?>
    </div>
>>>>>>> 2c5834b71f4051517d4af26e3ace3280b31c7b97
</div>

</body>
</html>
