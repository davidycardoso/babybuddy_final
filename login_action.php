<?php
session_start();
include('conexao.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica se é babá
    $sql = "SELECT * FROM babysitters WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $babysitter = $result->fetch_assoc();

    if ($babysitter && password_verify($password, $babysitter['password'])) {
        $_SESSION['user_id'] = $babysitter['id'];
        $_SESSION['user_type'] = 'babysitter';

        // Redireciona para o dashboard da babá
        header("Location: babysitter_dashboard.php");
        exit();
    } else {
        // Verifica se é responsável
        $sql = "SELECT * FROM guardians WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $guardian = $result->fetch_assoc();

        if ($guardian && password_verify($password, $guardian['password'])) {
            $_SESSION['user_id'] = $guardian['id'];
            $_SESSION['user_type'] = 'guardian';

            // Redireciona para a lista de babás
            header("Location: babysitter_list.php");
            exit();
        } else {
            echo "Email ou senha incorretos!";
        }
    }
}
?>
