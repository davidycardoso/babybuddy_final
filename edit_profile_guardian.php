<?php
// edit_profile_guardian.php
include 'conexao.php';
session_start();

if ($_SESSION['type'] !== 'guardian') {
    header("Location: login.php");
    exit();
}

$guardian_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE guardians SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $email, $guardian_id);
    $stmt->execute();

    echo "Perfil atualizado com sucesso!";
}

$sql = "SELECT name, email FROM guardians WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();
$guardian = $result->fetch_assoc();

?>
<form method="POST">
    <label>Nome:</label>
    <input type="text" name="name" value="<?= $guardian['name'] ?>">
    <label>Email:</label>
    <input type="email" name="email" value="<?= $guardian['email'] ?>">
    <button type="submit">Salvar Alterações</button>
</form>
