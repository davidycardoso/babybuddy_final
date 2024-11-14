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
            echo "Email ou senha incorretos!";
        }
    }
}
?>

<form method="POST" action="login.php">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit" name="login">Entrar</button>
</form>
