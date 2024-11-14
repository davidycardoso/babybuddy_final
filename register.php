<?php
// Conexão com o banco de dados
include('conexao.php');

if (isset($_POST['register'])) {
    // Recebe dados do formulário
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // 'babysitter' ou 'guardian'

    // Latitude e Longitude
    $latitude = $_POST['latitude']; // Recebe a latitude capturada pelo JavaScript
    $longitude = $_POST['longitude']; // Recebe a longitude capturada pelo JavaScript

    // Criptografa a senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insere os dados no banco
    if ($user_type == 'babysitter') {
        $sql = "INSERT INTO babysitters (name, email, password, latitude, longitude) VALUES (?, ?, ?, ?, ?)";
    } else {
        $sql = "INSERT INTO guardians (name, email, password) VALUES (?, ?, ?)";
    }

    $stmt = $conn->prepare($sql);
    if ($user_type == 'babysitter') {
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $latitude, $longitude);
    } else {
        $stmt->bind_param("sss", $name, $email, $hashed_password);
    }

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar!";
    }
}
?>

<form action="register.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Nome" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Senha" required>

    <!-- Selecione o tipo de usuário -->
    <label for="babysitter">Babá</label>
    <input type="radio" name="user_type" value="babysitter" required>
    
    <label for="guardian">Responsável</label>
    <input type="radio" name="user_type" value="guardian" required>

    <!-- Campos adicionais para a babá -->
    <div id="babysitter_fields" style="display: none;">
        <input type="file" name="photo" accept="image/*">
        <input type="number" name="hourly_rate" placeholder="Taxa por hora" required>
        <textarea name="qualifications" placeholder="Qualificações" required></textarea>
        <textarea name="experience" placeholder="Experiência anterior" required></textarea>

        <!-- Latitude e Longitude serão preenchidos automaticamente -->
        <input type="text" id="latitude" name="latitude" placeholder="Latitude" readonly>
        <input type="text" id="longitude" name="longitude" placeholder="Longitude" readonly>
        
        <!-- Botão para capturar a localização -->
        <button type="button" onclick="getLocation()">Obter Localização</button>
    </div>

    <button type="submit" name="register">Cadastrar</button>
</form>

<script>
    // Exibe os campos adicionais para babá quando a opção for escolhida
    document.querySelectorAll('input[name="user_type"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            const babysitterFields = document.getElementById('babysitter_fields');
            if (this.value === 'babysitter') {
                babysitterFields.style.display = 'block';
            } else {
                babysitterFields.style.display = 'none';
            }
        });
    });

    // Função para obter a localização do navegador
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocalização não é suportada por este navegador.");
        }
    }

    // Função para exibir a posição
    function showPosition(position) {
        document.getElementById("latitude").value = position.coords.latitude;
        document.getElementById("longitude").value = position.coords.longitude;
    }

    // Função para lidar com erros
    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("Usuário negou a solicitação de geolocalização.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("A localização não está disponível.");
                break;
            case error.TIMEOUT:
                alert("A solicitação para obter a localização expirou.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Ocorreu um erro desconhecido.");
                break;
        }
    }
</script>
