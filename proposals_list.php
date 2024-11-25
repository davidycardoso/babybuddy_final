<?php
include 'conexao.php';
session_start();

$babysitter_id = $_SESSION['user_id'];  // ID da babá (assumindo que está logada)

// Consulta as propostas recebidas
$sql = "SELECT proposals.id, guardians.name AS guardian_name, proposals.hourly_rate, proposals.message, proposals.status 
        FROM proposals
        JOIN guardians ON proposals.guardian_id = guardians.id
        WHERE proposals.babysitter_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $babysitter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p><strong>Responsável:</strong> " . htmlspecialchars($row['guardian_name']) . "</p>";
        echo "<p><strong>Valor proposto por hora:</strong> R$ " . number_format($row['hourly_rate'], 2, ',', '.') . "</p>";
        echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($row['message']) . "</p>";
        echo "<p><strong>Status:</strong> " . ucfirst($row['status']) . "</p>";
        
        if ($row['status'] == 'pendente') {
            echo "<form action='accept_reject_proposal.php' method='POST'>
                    <input type='hidden' name='proposal_id' value='" . $row['id'] . "'>
                    <button type='submit' name='action' value='accept'>Aceitar</button>
                    <button type='submit' name='action' value='reject'>Rejeitar</button>
                  </form>";
        }
        
        echo "</div>";
    }
} else {
    echo "Você não tem propostas pendentes.";
}

$stmt->close();
$conn->close();
?>
