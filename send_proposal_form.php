<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';
session_start();

// Verifica se o responsável está logado, caso contrário, redireciona
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'responsavel') {
    header("Location: login.php");  // Redireciona para a página de login se não estiver logado
    exit;
}

// Recebe o ID da babá da URL (ou de outra parte da página)
$babysitter_id = isset($_GET['babysitter_id']) ? $_GET['babysitter_id'] : null;

if (!$babysitter_id) {
    echo "Erro: ID da babá não fornecido.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Proposta</title>
</head>
<body>

<h2>Enviar Proposta para a Babá</h2>

<form action="send_proposal.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="babysitter_id" value="<?php echo $babysitter_id; ?>">

    <label for="hourly_rate">Proposta de valor por hora:</label>
    <input type="number" name="hourly_rate" step="0.01" required>
    
    <label for="message">Mensagem para a Babá (opcional):</label>
    <textarea name="message" rows="4" cols="50"></textarea>
    
    <button type="submit">Enviar Proposta</button>
</form>

</body>
</html>
