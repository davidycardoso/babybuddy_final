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

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $hourly_rate = $_POST['hourly_rate'];
    $qualifications = $_POST['qualifications'];
    $experience = $_POST['experience'];

    // Verifica se foi enviada uma nova foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['photo']['name'];
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_size = $_FILES['photo']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Define o diretório de upload e o novo nome da imagem
        $upload_dir = 'uploads/';
        $new_file_name = 'profile_' . $babysitter_id . '.' . $file_ext;

        // Verifica o tipo da imagem
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_ext, $allowed_ext) && $file_size < 2000000) { // Tamanho máximo 2MB
            // Move a imagem para o diretório de uploads
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                // Atualiza o banco de dados com o novo nome da foto
                $sql_update_photo = "UPDATE babysitters SET photo = ? WHERE id = ?";
                $stmt_update_photo = $conn->prepare($sql_update_photo);
                $stmt_update_photo->bind_param("si", $new_file_name, $babysitter_id);
                $stmt_update_photo->execute();
            } else {
                $error_message = "Erro ao fazer upload da imagem.";
            }
        } else {
            $error_message = "Formato de arquivo inválido ou o tamanho excede 2MB.";
        }
    }

    // Atualiza as informações restantes
    $sql_update = "UPDATE babysitters SET name = ?, hourly_rate = ?, qualifications = ?, experience = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $name, $hourly_rate, $qualifications, $experience, $babysitter_id);
    $stmt_update->execute();

    // Redireciona de volta para o perfil
    header("Location: babysitter_dashboard.php");
    exit();
}

// Consulta para pegar as informações da babá
$sql = "SELECT * FROM babysitters WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();
$babysitter = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="edit-profile-container">
    <h2>Editar Perfil</h2>

    <?php if (isset($error_message)) { ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php } ?>

    <form action="edit-profile.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo $babysitter['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="hourly_rate">Valor por Hora:</label>
            <input type="text" id="hourly_rate" name="hourly_rate" value="<?php echo $babysitter['hourly_rate']; ?>" required>
        </div>
        <div class="form-group">
            <label for="qualifications">Formações:</label>
            <textarea id="qualifications" name="qualifications" required><?php echo $babysitter['qualifications']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="experience">Experiência:</label>
            <textarea id="experience" name="experience" required><?php echo $babysitter['experience']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="photo">Foto:</label>
            <input type="file" id="photo" name="photo">
            <p>Foto atual: <img src="uploads/<?php echo $babysitter['photo'] ?: 'default-avatar.jpg'; ?>" alt="Foto da Babá" width="100"></p>
        </div>
        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="babysitter_dashboard.php">Voltar ao Dashboard</a>
</div>

</body>
</html>
