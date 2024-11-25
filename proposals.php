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

// Consulta para pegar as propostas
$sql = "
    SELECT p.id, g.name AS guardian_name, p.status
    FROM proposals p
    JOIN guardians g ON p.guardian_id = g.id
    WHERE p.babysitter_id = ?
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar a consulta SQL: " . $conn->error);
}

$stmt->bind_param("i", $babysitter_id);
if ($stmt->execute() === false) {
    die("Erro ao executar a consulta: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result === false) {
    die("Erro ao obter resultados da consulta: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Propostas Detalhadas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="proposals-container">
    <h2>Propostas Detalhadas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Responsável</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifica se há resultados e exibe as propostas
            if ($result->num_rows > 0) {
                while ($proposal = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $proposal['id'] . "</td>";
                    echo "<td>" . $proposal['guardian_name'] . "</td>";
                    echo "<td>" . $proposal['status'] . "</td>";
                    echo "<td>";
                    // Exibe o link para visualizar os detalhes da proposta
                    echo '<a href="view_proposal.php?id=' . $proposal['id'] . '">Ver Detalhes</a>';
                    
                    // Verifica o status da proposta e exibe os botões de aceitar ou rejeitar
                    if ($proposal['status'] == 'sent') {
                        // Mostrar botões de ação se a proposta estiver no status 'sent'
                        echo ' <a href="accept_proposal.php?id=' . $proposal['id'] . '">Aceitar</a>';
                        echo ' <a href="reject_proposal.php?id=' . $proposal['id'] . '">Rejeitar</a>';
                    }
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhuma proposta encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
