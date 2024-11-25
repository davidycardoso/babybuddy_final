<?php
// Inclui a conexão com o banco de dados
include 'conexao.php';
session_start();

// Verifica se o usuário está logado e se é responsável
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php"); // Redireciona para o login
    exit;
}

// Verifica se o ID da babá foi passado na URL
if (isset($_GET['babysitter_id'])) {
    $babysitter_id = intval($_GET['babysitter_id']);

    // Consulta para buscar os detalhes da babá
    $sql = "SELECT * FROM babysitters WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $babysitter_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $photo = htmlspecialchars($row['photo']);
        $hourly_rate = is_numeric($row['hourly_rate']) ? floatval($row['hourly_rate']) : 0.00; // Converte ou define valor padrão
        $qualifications = htmlspecialchars($row['qualifications']);
        $experience = htmlspecialchars($row['experience']);
    } else {
        echo "Babá não encontrada.";
        exit;
    }
} else {
    echo "ID da babá não fornecido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Babá</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <h2>Perfil de <?php echo $name; ?></h2>

    <div class="babysitter-profile">
        <!-- Exibe a foto da babá -->
        <?php if (!empty($photo) && file_exists("uploads/$photo")) { ?>
            <img src="uploads/<?php echo $photo; ?>" alt="<?php echo $name; ?>" width="200" height="200">
        <?php } else { ?>
            <img src="default-avatar.jpg" alt="Imagem de <?php echo $name; ?>" width="200" height="200">
        <?php } ?>

        <!-- Exibe as informações detalhadas da babá -->
        <h3>Nome: <?php echo $name; ?></h3>
        <p><strong>Taxa por hora:</strong> R$ <?php echo number_format($hourly_rate, 2, ',', '.'); ?></p>
        <p><strong>Qualificações:</strong> <?php echo nl2br($qualifications); ?></p>
        <p><strong>Experiência:</strong> <?php echo nl2br($experience); ?></p>

        <!-- Formulário para enviar proposta -->
        <form action="send_proposal.php" method="POST">
            <input type="hidden" name="babysitter_id" value="<?php echo $babysitter_id; ?>">
            <textarea name="proposal_message" placeholder="Escreva sua proposta..." required></textarea><br>
            <button type="submit">Enviar Proposta</button>
        </form>
    </div>

    <a href="babysitter_list.php">Voltar para a lista de babás</a>
</body>
</html>

<?php
// Fecha a conexão
$conn->close();
?>
