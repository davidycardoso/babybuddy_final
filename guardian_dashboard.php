<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM guardians WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Responsável</title>
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Bem-vindo, <?php echo htmlspecialchars($guardian['name']); ?></h2>
        
        <div class="mt-3">
            <a href="babysitter_list.php?latitude=123.456&longitude=789.012" class="btn btn-primary">Ver Babás Próximas</a>
            <a href="sent_proposals.php" class="btn btn-secondary">Minhas Propostas</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>
</body>
</html>

