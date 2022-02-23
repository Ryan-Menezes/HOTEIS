<?php

namespace core\controllers;

use core\classes\Store;
use core\models\Configuracoes as Config;
use core\classes\Email;

class MainController{
	// MÉTODO QUE IRÁ CARREGAR AS VIEWS DA PÁGINA INICIAL

	public function inicio(){
		$config = new Config();

		$dados = [
			'titulo' => 'Início',
			'logado' => Store::logado(),
			'imgs' => $config->getConfig()->site->imagens,
			'phone' => Store::mask('(##)#####-####', ADDRESS_PHONE),
			'postal_code' => Store::mask('#####-###', ADDRESS_POSTAL_CODE)
		];

		Store::layout([
			'layout/html_header',
			'layout/header',
			'inicio',
			'layout/footer',
			'layout/html_footer'
		], $dados);
	}

	public function contato(){
		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$camposErrados = array();
		$msg = 'Não foi possível enviar esta mensagem, ';

		// Buscando os dados passados pelo form

		$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
		$assunto = trim(filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$mensagem = trim(filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		if(empty($nome)) array_push($camposErrados, 'NOME');
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($camposErrados, 'E-MAIL');
		if(empty($assunto)) array_push($camposErrados, 'ASSUNTO');
		if(empty($mensagem)) array_push($camposErrados, 'MENSAGEM');

		if(empty($camposErrados)):
			if(Email::EnviarEmailContato($email, $nome, $assunto, $mensagem)):
				echo json_encode(['RES' => true, 'MSG' => 'Mensagem enviada com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de enviar a menagem!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
		endif;
	}
}