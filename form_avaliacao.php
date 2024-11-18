<?php
session_start();
include('conexao.php');

// Verifica se o responsável está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['babysitter_id'])) {
    $babysitter_id = $_GET['babysitter_id'];
} else {
    // Se não houver ID da babá, redireciona para a página de erro
    header("Location: error_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Avaliar Babá</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="form-container">
        <h2>Avaliar a Babá</h2>
        <form action="submit_review.php" method="POST">
            <label for="rating">Avaliação (1 a 5):</label>
            <select name="rating" id="rating">
                <option value="1">1 - Péssimo</option>
                <option value="2">2 - Ruim</option>
                <option value="3">3 - Regular</option>
                <option value="4">4 - Bom</option>
                <option value="5">5 - Excelente</option>
            </select>

            <label for="review">Comentário:</label>
            <textarea name="review" id="review" rows="4" placeholder="Deixe um comentário"></textarea>

            <input type="hidden" name="babysitter_id" value="<?php echo $babysitter_id; ?>">

            <button type="submit">Enviar Avaliação</button>
        </form>
    </div>

</body>
</html>
