<?php 
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../../vendor/autoload.php");
require_once(__DIR__."/../../inc/includes.php");

$config = $settings->get(1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendEmail($fields) {
	global $config;
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->SMTPDebug = 1; // Desativa a saída de depuração
        $mail->SMTPOptions = array(
            'ssl' => array(
		        'verify_peer' => $config->smtp_verify_peer == 1 ? true : false,
		        'verify_peer_name' => $config->smtp_verify_peer_name == 1 ? true : false,
		        'allow_self_signed' => $config->smtp_allow_self_signed == 1 ? true : false
            )
        );      
        if (!empty($config->smtp_secure)) {
            $mail->SMTPSecure = $config->smtp_secure;
        }
        $mail->CharSet = $config->smtp_charset;  
        $mail->isSMTP();
        $mail->Host = $config->smtp_host;
        $mail->SMTPAuth = ($config->smtp_auth == 1) ? true : false;
        $mail->Username = $config->smtp_username; 
        $mail->Password = $config->smtp_password;
        $mail->Port = $config->smtp_port;

        // Remetente e destinatário
        $mail->setFrom($config->smtp_from, $config->smtp_from_name); 
        $mail->addAddress($fields['email'], $fields['recipient_name']);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = $fields['subject'];
        $mail->Body = $fields['content'];
        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}



$fields = array(
    'subject' => $_POST['subject'],
    'name' => $_SESSION['admin_name'],
    'email' => $_POST['email'],
    'content' => $_POST['content'],
    'recipient_name' => $_POST['recipient_name']
);

$emailSent = sendEmail($fields);