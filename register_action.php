<?php
// register_action.php
include 'conexao.php';

// Verifica se o campo 'type' está presente no POST
if (!isset($_POST['type'])) {
    die("Erro: Tipo de usuário não selecionado.");
}

// Recebe os dados do formulário
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$type = $_POST['type'];

// Sanitizar e validar os dados recebidos
$name = mysqli_real_escape_string($conn, $name);
$email = mysqli_real_escape_string($conn, $email);
$password = mysqli_real_escape_string($conn, $password);

// Hash da senha
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Processa as informações adicionais para a babá
$photo = null; // Aqui o nome correto da variável é $photo
$hourly_rate = null;
$qualifications = null;
$experience = null;

// Verifica se a foto foi enviada
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    // Verifica o nome do arquivo e cria um nome único para evitar duplicação
    $photo_name = basename($_FILES['photo']['name']);
    $photo = 'uploads/' . $photo_name; // Salva o caminho da foto na variável $photo
    
    // Verifica a extensão da imagem (para garantir que seja uma imagem válida)
    $imageFileType = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
    $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($imageFileType, $valid_extensions)) {
        // Move o arquivo para o diretório uploads
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    } else {
        echo "Apenas imagens JPG, JPEG, PNG e GIF são permitidas.";
        exit;
    }
}

// Recebe as informações adicionais para a babá
if ($type == 'babysitter') {
    $hourly_rate = $_POST['hourly_rate'];
    $qualifications = mysqli_real_escape_string($conn, $_POST['qualifications']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
}

// Verifica o tipo de usuário e insere no banco de dados
if ($type == 'babysitter') {
    // SQL para babá
    $sql = "INSERT INTO babysitters (name, email, password, photo, hourly_rate, qualifications, experience) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    // Vincula os parâmetros
    $stmt->bind_param("sssssss", $name, $email, $hashed_password, $photo, $hourly_rate, $qualifications, $experience);
} else {
    // SQL para responsável
    $sql = "INSERT INTO guardians (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    // Vincula os parâmetros
    $stmt->bind_param("sss", $name, $email, $hashed_password);
}

// Executa a consulta
if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
