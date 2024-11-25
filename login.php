<?php
// Conexão com o banco de dados
include('conexao.php');
session_start();

if (isset($_POST['login'])) {
    // Recebe dados do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica no banco de dados se o e-mail existe
    $sql = "SELECT * FROM babysitters WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = 'babysitter';
        header("Location: babysitter_dashboard.php");
        exit();
    } else {
        // Verifica no banco de dados se o responsável existe
        $sql = "SELECT * FROM guardians WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = 'guardian';
            header("Location: guardian_dashboard.php");
            exit();
        } else {
            echo "<p class='error'>Email ou senha incorretos!</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BabyBuddy</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="login-container">
        <i class="fa fa-baby icon"></i>  <!-- Ícone de bebê -->
        <h1>BabyBuddy</h1>
        <h2>Entre com sua conta</h2>
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit" name="login" class="btn">Entrar</button>
        </form>
        <div class="options">
            <p><a href="register.php">Não tem uma conta? Cadastre-se</a></p>
            <p><a href="forgot_password.php">Esqueceu sua senha?</a></p>
        </div>
    </div>
</body>
</html>


