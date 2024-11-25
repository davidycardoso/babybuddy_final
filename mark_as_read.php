<?php
session_start();
include('conexao.php');

if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];

    // Atualiza o status da notificação para 'read'
    $sql = "UPDATE notifications SET status = 'read' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
}

header("Location: dashboard_babysitter.php");
exit();
