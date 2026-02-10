<?php
// Define que a resposta será um JSON (formato lido pelo JS)
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$response = array('status' => '', 'message' => '');

if (isset($_POST['acao']) && $_POST['acao'] == 'enviar') {

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $emailCliente = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

    $mail = new PHPMailer(true);

    try {
        // Configurações do Servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'seuemail@gmail.com'; // COLOQUE SEU EMAIL
        $mail->Password   = 'sua_senha_de_app';   // COLOQUE SUA SENHA DE APP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Destinatários
        $mail->setFrom('seuemail@gmail.com', 'VivaVerba Site');
        $mail->addAddress('admin@vivaverba.com'); 
        $mail->addReplyTo($emailCliente, $nome);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = "Contato: $assunto";
        $mail->Body    = "<h2>Novo contato de $nome</h2><p>$mensagem</p>";
        $mail->AltBody = "De: $nome \nMensagem: $mensagem";

        $mail->send();
        
        // Sucesso
        $response['status'] = 'success';
        $response['message'] = 'Email enviado!';

    } catch (Exception $e) {
        // Erro
        $response['status'] = 'error';
        $response['message'] = $mail->ErrorInfo;
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Acesso inválido';
}

// Devolve a resposta para o JavaScript
echo json_encode($response);
?>