<?php
session_start();
include('conexao.php');

// Verifica se o responsável está logado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating']; // Avaliação de 1 a 5
    $review = $_POST['review']; // Comentário do responsável
    $babysitter_id = $_POST['babysitter_id']; // ID da babá a ser avaliada
    $guardian_id = $_SESSION['user_id']; // ID do responsável logado

    // Verifica se a avaliação é válida
    if ($rating >= 1 && $rating <= 5) {
        // Insere a avaliação no banco
        $sql = "INSERT INTO reviews (babysitter_id, guardian_id, rating, review) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $babysitter_id, $guardian_id, $rating, $review);
        
        if ($stmt->execute()) {
            // Redireciona para o perfil da babá após o envio da avaliação
            header("Location: babysitter_profile.php?id=" . $babysitter_id);
            exit();
        } else {
            echo "Erro ao enviar avaliação. Tente novamente.";
        }
    } else {
        echo "A avaliação deve ser entre 1 e 5 estrelas.";
    }
}
?>
