<?php
// profile_guardian.php
include 'conexao.php';
session_start();

// Verifica se o usuário é um responsável e está logado
if ($_SESSION['type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

$guardian_id = $_SESSION['user_id'];
$sql = "SELECT name, email FROM guardians WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();

?>
<h2>Perfil do Responsável</h2>
<p>Nome: <?= $guardian['name'] ?></p>
<p>Email: <?= $guardian['email'] ?></p>
<a href="edit_profile_guardian.php">Editar Perfil</a>
