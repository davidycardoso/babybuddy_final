<?php
// edit_profile_babysitter.php
include 'conexao.php';
session_start();

if ($_SESSION['type'] !== 'babysitter') {
    header("Location: login.php");
    exit();
}

$babysitter_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hourly_rate = $_POST['hourly_rate'];
    $qualifications = $_POST['qualifications'];
    $experience = $_POST['experience'];

    $sql = "UPDATE babysitters SET hourly_rate = ?, qualifications = ?, experience = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $hourly_rate, $qualifications, $experience, $babysitter_id);
    $stmt->execute();

    echo "Perfil atualizado com sucesso!";
}

$sql = "SELECT hourly_rate, qualifications, experience FROM babysitters WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();
$babysitter = $result->fetch_assoc();

?>
<form method="POST">
    <label>Valor por Hora:</label>
    <input type="text" name="hourly_rate" value="<?= $babysitter['hourly_rate'] ?>">
    <label>Qualificações:</label>
    <textarea name="qualifications"><?= $babysitter['qualifications'] ?></textarea>
    <label>Experiência:</label>
    <textarea name="experience"><?= $babysitter['experience'] ?></textarea>
    <button type="submit">Salvar Alterações</button>
</form>
