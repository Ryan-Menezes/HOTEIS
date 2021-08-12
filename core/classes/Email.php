<?php

namespace core\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email{
	public static function EnviarEmail(string $email, string $nome, string $assunto, string $mensagem, array $arquivos = []) : bool{
		try{
			$mail = new PHPMailer(true);

		    // Configurações do servidor

		    $mail->SMTPDebug = SMTP::DEBUG_OFF;
		    $mail->isSMTP();
		    $mail->CharSet = 'UTF-8';
		    $mail->Host = EMAIL_SERVER;
		    $mail->SMTPAuth = true;
		    $mail->Username = EMAIL_FROM;
		    $mail->Password = EMAIL_PASS;
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		    $mail->Port = EMAIL_PORT;

		    // Recipientes

	    	$mail->setFrom(EMAIL_FROM, APP_NAME);
	    	$mail->addAddress($email, $nome);
	    	// $mail->addBcc(EMAIL_FROM);

		    // Conteúdo

		    $mail->isHTML(true);
		    $mail->Subject = $assunto;
		    $mail->Body = $mensagem;
		    $mail->AltBody = $mensagem;

		    // Arquivos

		    if(is_array($arquivos) && !empty($arquivos)):
		    	foreach($arquivos as $arquivo):
		    		if(file_exists($arquivo)) $mail->addAttachment($arquivo);
		    	endforeach;
		    endif;

		    return $mail->send();
		}catch(Exception $e){
		    return false;
		}
	}

	public static function EnviarEmailContato(string $email, string $nome, string $assunto, string $mensagem) : bool{
		try{
			$mail = new PHPMailer(true);

		    // Configurações do servidor

		    $mail->SMTPDebug = SMTP::DEBUG_OFF;
		    $mail->isSMTP();
		    $mail->CharSet = 'UTF-8';
		    $mail->Host = EMAIL_SERVER;
		    $mail->SMTPAuth = true;
		    $mail->Username = EMAIL_FROM;
		    $mail->Password = EMAIL_PASS;
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		    $mail->Port = EMAIL_PORT;

		    // Recipientes

		    $mail->Sender = $email;
	    	$mail->setFrom($email, $nome);
	    	$mail->addAddress(EMAIL_FROM, APP_NAME);
	    	// $mail->addBcc(EMAIL_FROM);

		    // Conteúdo

		    $mail->isHTML(true);
		    $mail->Subject = $assunto;
		    $mail->Body = $mensagem;
		    $mail->AltBody = $mensagem;

		    return $mail->send();
		}catch(Exception $e){
		    return false;
		}
	}
}