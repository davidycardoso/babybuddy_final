<?php
session_start();
include('conexao.php');

// Verifica se o responsável está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

// Consulta para obter as propostas enviadas pelo responsável
$sql = "
    SELECT p.id, bs.name AS babysitter_name, p.status, p.created_at
    FROM proposals p
    JOIN babysitters bs ON p.babysitter_id = bs.id
    WHERE p.guardian_id = ?
    ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar a consulta SQL: " . $conn->error);
}

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minhas Propostas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="dashboard-container">
    <h2>Minhas Propostas</h2>

    <table>
        <thead>
            <tr>
                <th>Babá</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($proposal = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $proposal['babysitter_name']; ?></td>
                    <td><?php echo ucfirst($proposal['status']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($proposal['created_at'])); ?></td>
                    <td>
                        <a href="view_proposal_guardian.php?id=<?php echo $proposal['id']; ?>" class="btn">Ver Detalhes</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
