<?php
// Conexão com o banco de dados (ajuste conforme necessário)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agendamento_db";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificando se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação e limpeza dos dados recebidos
    $data = isset($_POST['data']) ? mysqli_real_escape_string($conn, $_POST['data']) : '';
    $hora = isset($_POST['hora']) ? mysqli_real_escape_string($conn, $_POST['hora']) : '';

    // Verificar se os campos de data e hora não estão vazios
    if (!empty($data) && !empty($hora)) {
        // Usando prepared statement para evitar injeção de SQL
        $stmt = $conn->prepare("INSERT INTO agendamentos (data, hora) VALUES (?, ?)");
        $stmt->bind_param("ss", $data, $hora);

        // Executando a consulta
        if ($stmt->execute()) {
            echo "Agendamento realizado com sucesso!";

            // Envio de e-mail
            $to = "alissoncorreia31@gmail.com";  // E-mail para o qual os dados serão enviados
            $subject = "Novo Agendamento Recebido";  // Assunto do e-mail
            $message = "Você recebeu um novo agendamento:\n\n";
            $message .= "Data: " . $data . "\n";
            $message .= "Hora: " . $hora . "\n";
            $headers = "From: no-reply@seudominio.com";  // Remetente do e-mail
            $headers .= "\r\nReply-To: no-reply@seudominio.com";  // Resposta para o remetente

            // Função mail para enviar o e-mail
            if (mail($to, $subject, $message, $headers)) {
                echo " E-mail enviado com sucesso!";
            } else {
                echo " Falha ao enviar o e-mail.";
            }
        } else {
            echo "Erro: " . $stmt->error;
        }

        // Fechar o statement e a conexão com o banco de dados
        $stmt->close();
    } else {
        echo "Por favor, preencha todos os campos corretamente.";
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
}
?>


