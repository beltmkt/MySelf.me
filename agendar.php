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
  $data = $_POST['data'];
  $hora = $_POST['hora'];
  
  // Insira os dados no banco de dados
  $sql = "INSERT INTO agendamentos (data, hora) VALUES ('$data', '$hora')";
  
  if ($conn->query($sql) === TRUE) {
    echo "Agendamento realizado com sucesso!";
    
    // Envio de e-mail
    $to = "alissoncorreia31@gmail.com";  // E-mail para o qual os dados serão enviados
    $subject = "Novo Agendamento Recebido";  // Assunto do e-mail
    $message = "Você recebeu um novo agendamento:\n\n";
    $message .= "Data: " . $data . "\n";
    $message .= "Hora: " . $hora . "\n";
    $headers = "From: no-reply@seudominio.com";  // Remetente do e-mail

    // Função mail para enviar o e-mail
    if (mail($to, $subject, $message, $headers)) {
        echo " E-mail enviado com sucesso!";
    } else {
        echo " Falha ao enviar o e-mail.";
    }
  } else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
  }

  // Fechar a conexão com o banco de dados
  $conn->close();
}
?>

