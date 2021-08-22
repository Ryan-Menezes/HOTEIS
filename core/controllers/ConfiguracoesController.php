<?php

namespace core\controllers;

use core\classes\Store;
use core\classes\Email;
use core\models\Config;
use core\models\Notificacoes;
use Exception;

class ConfiguracoesController{
	private $config;

	public function __construct(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$this->config = new Config();
	}

	public function configuracoes(){
		// Carregando os dados da página

		$dadosUser = Store::dadosUsuarioLogado();
		$notificacao = new Notificacoes();

		$dados = [
			'titulo' => 'Configurações',
			'dadosUser' => $dadosUser,
			'config' => $this->config->getConfig(),
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/configuracoes',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}

	public function alterar_redes_sociais(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível alterar suas redes sociais, ';
		$camposErrados = array();

		$facebook = trim(addslashes(filter_input(INPUT_POST, 'facebook', FILTER_SANITIZE_URL)));
		$instagram = trim(addslashes(filter_input(INPUT_POST, 'instagram', FILTER_SANITIZE_URL)));
		$twitter = trim(addslashes(filter_input(INPUT_POST, 'twitter', FILTER_SANITIZE_URL)));

		if(!filter_var($facebook, FILTER_VALIDATE_URL)) array_push($camposErrados, 'FACEBOOK');
		if(!filter_var($instagram, FILTER_VALIDATE_URL)) array_push($camposErrados, 'INSTAGRAM');
		if(!filter_var($twitter, FILTER_VALIDATE_URL)) array_push($camposErrados, 'TWITTER');

		try{
			if(empty($camposErrados)):
				$config = $this->config->getConfig();

				$config->social->facebook = $facebook;
				$config->social->instagram = $instagram;
				$config->social->twitter = $twitter;

				if($this->config->setConfig($config)):
					echo json_encode(['RES' => true, 'MSG' => 'Redes sociais alterardas com sucesso!']);
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
			endif;
		}catch(Exception $e){
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
		}
	}

	public function alterar_nome_sistema(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível alterar o nome do sistema, ';
		$camposErrados = array();

		$nome = trim(addslashes(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS)));

		try{
			$config = $this->config->getConfig();

			$config->name = $nome;

			if($this->config->setConfig($config)):
				echo json_encode(['RES' => true, 'MSG' => 'Nome do sistema alterado com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
			endif;
		}catch(Exception $e){
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
		}
	}

	public function alterar_logo_sistema(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível alterar a logo do sistema, ';
		$camposErrados = array();

		// Array de extensões permitidas para a imagem

		$extensoes = [
			'image/jpeg',
			'image/png',
			'image/gif'
		];

		// Verificando se o arquivo foi passado

		if(!isset($_FILES['imagem'])):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
			return;
		endif;

		// Buscando os dados passados pelo form

		$imagem = $_FILES['imagem'];

		// Verificando se o arquivo NÃO últrapassou o limite de tamanho

		if($imagem['error'] === UPLOAD_ERR_FORM_SIZE || $imagem['size'] > MAX_FILE_SIZE):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'A imagem passada últrapassa o limite de tamanho de 1MB!']);
			return;
		endif;

		// Validando a imagem passada

		if($imagem['error'] !== UPLOAD_ERR_OK):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'A imagem passada é inválida!']);
			return;
		endif;

		// Varificando se a imagem passada é uma imagem png, jpeg ou gif

		if(!in_array($imagem['type'], $extensoes)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'A extensão da imagem é inválida!, só é permitida imagens das extensões: PNG, JPEG ou GIF!']);
			return;
		endif;

		// Movendo a imagem para a pasta com imagens

		$logo = explode('.', LOGO)[0] . '.' . pathinfo($imagem['name'], PATHINFO_EXTENSION);

		if(move_uploaded_file($imagem['tmp_name'], '../../public/assets/IMGS/' . $logo)):
			// Deletando a antiga logo

			if($logo !== LOGO) unlink('../../public/assets/IMGS/' . LOGO);

			// Alterarndo o arquivo de configurações

			try{
				$config = $this->config->getConfig();

				$config->logo = $logo;

				if($this->config->setConfig($config)):
					echo json_encode(['RES' => true, 'MSG' => 'Logo do sistema alterada com sucessso!']);
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
				endif;
			}catch(Exception $e){
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
			}
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
		endif;
	}

	public function alterar_carrossel_sistema(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Pasta onde será armazenado as imagens

		$pasta = '../../public/assets/IMGS/CARROSSEL/';

		$msg = 'Não foi possível alterar os dados do carrossel, ';
		$camposErrados = array();

		// Array de extensões permitidas para a imagem

		$extensoes = [
			'image/jpeg',
			'image/png'
		];

		// Verificando se os dados foram passados

		if(!isset($_FILES['imgs']) || !isset($_POST['descricao']) || !isset($_POST['removidos'])):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
			return;
		endif;

		$img = $_FILES['imgs'];
		$config = $this->config->getConfig();

		// Deletando os dados reovidos do carrossel

		$removidos = explode(',', $_POST['removidos']);

		foreach($removidos as $index):
			if(array_key_exists($index, $config->site->carrossel->imgs)):
				if(!empty($config->site->carrossel->imgs[$index]->img) && is_file($pasta . $config->site->carrossel->imgs[$index]->img) && file_exists($pasta . $config->site->carrossel->imgs[$index]->img)):
					unlink($pasta . $config->site->carrossel->imgs[$index]->img);
				endif;

				array_splice($config->site->carrossel->imgs, $index, 1);
			endif;
		endforeach;

		// Alterarndo as imagens do carrossel

		for($i = 0; $i < count($img['name']); $i++):
			// Verificando se o arquivo NÃO últrapassou o limite de tamanho

			if($img['error'][$i] === UPLOAD_ERR_FORM_SIZE || $img['size'][$i] > MAX_FILE_SIZE) continue;

			// Validando a imagem passada

			if($img['error'][$i] !== UPLOAD_ERR_OK) continue;

			// Verificando se a imagem passada é uma imagem png ou jpeg

			if(!in_array($img['type'][$i], $extensoes)) continue;

			// Verificando se nome da imagem não é vazio

			if(empty($img['name'][$i])) continue;

			// Movendo a imagem para a pasta com imagens

			do{
				$fileName = md5(time() . rand(0, 1000))  . '.' . pathinfo($img['name'][$i], PATHINFO_EXTENSION);
			}while(file_exists($pasta . $fileName));
			
			if(move_uploaded_file($img['tmp_name'][$i], $pasta . $fileName)):
				// Verificando se o valor no array de imagens é um objeto

				if(!array_key_exists($i, $config->site->carrossel->imgs)) 
					$config->site->carrossel->imgs[] = json_decode(json_encode(['img' => '', 'text' => '']));

				// Deletando a imagem antiga

				try{
					if(!empty($config->site->carrossel->imgs[$i]->img) && is_file($pasta . $config->site->carrossel->imgs[$i]->img) && file_exists($pasta . $config->site->carrossel->imgs[$i]->img)):
						unlink($pasta . $config->site->carrossel->imgs[$i]->img);
					endif;

					$config->site->carrossel->imgs[$i]->img = $fileName;
				}catch(Exception $e){
					unlink($pasta . $fileName);
				}
			endif;
		endfor;

		// Alterarndo os textos do carrossel

		for($i = 0; $i < count($_POST['descricao']); $i++):
			// Verificando se o valor no array de imagens é um objeto

			if(!array_key_exists($i, $config->site->carrossel->imgs)) 
				$config->site->carrossel->imgs[] = json_decode(json_encode(['img' => '', 'text' => '']));

			if(!empty($_POST['descricao'][$i])) 
				$config->site->carrossel->imgs[$i]->text = trim(htmlspecialchars($_POST['descricao'][$i]));
		endfor;

		// Deletando dados vázios do carrossel

		for($i = 0; $i < count($config->site->carrossel->imgs); $i++):
			if(empty($config->site->carrossel->imgs[$i]->img) || empty($config->site->carrossel->imgs[$i]->text)):
				if(!empty($config->site->carrossel->imgs[$i]->img) && is_file($pasta . $config->site->carrossel->imgs[$i]->img) && file_exists($config->site->carrossel->imgs[$i]->img))
					unlink($pasta . $config->site->carrossel->imgs[$i]->img);

				array_splice($config->site->carrossel->imgs, $i);
			endif;
		endfor;

		// Alterando o arquivo de configurações

		try{
			if($this->config->setConfig($config)):
				echo json_encode(['RES' => true, 'MSG' => 'Carrossel alterado com sucessso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
			endif;
		}catch(Exception $e){
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
		}
	}

	public function alterar_imgs_destaque_sistema(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Pasta onde será armazenado as imagens

		$pasta = '../../public/assets/IMGS/DESTAQUE/';

		$msg = 'Não foi possível alterar as imagens de destaque do sistema, ';
		$camposErrados = array();

		// Array de extensões permitidas para a imagem

		$extensoes = [
			'image/jpeg',
			'image/png'
		];

		// Verificando se o arquivo foi passado

		if(!isset($_FILES['imgs'])):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
			return;
		endif;

		$img = $_FILES['imgs'];
		$config = $this->config->getConfig();

		// Buscando os dados passados pelo form

		for($i = 0; $i < count($img['name']); $i++):
			// Verificando se o arquivo NÃO últrapassou o limite de tamanho

			if($img['error'][$i] === UPLOAD_ERR_FORM_SIZE || $img['size'][$i] > MAX_FILE_SIZE) continue;

			// Validando a imagem passada

			if($img['error'][$i] !== UPLOAD_ERR_OK) continue;

			// Verificando se a imagem passada é uma imagem png ou jpeg

			if(!in_array($img['type'][$i], $extensoes)) continue;

			// Verificando se nome da imagem não é vazio

			if(empty($img['name'][$i])) continue;

			// Movendo a imagem para a pasta com imagens

			do{
				$fileName = md5(time() . rand(0, 1000))  . '.' . pathinfo($img['name'][$i], PATHINFO_EXTENSION);
			}while(file_exists($pasta . $fileName));

			if(move_uploaded_file($img['tmp_name'][$i], $pasta . $fileName)):
				// Deletando a imagem antiga

				try{
					if(file_exists($pasta . $config->site->imagens[$i])):
						unlink($pasta . $config->site->imagens[$i]);
					endif;

					$config->site->imagens[$i] = $fileName;
				}catch(Exception $e){
					unlink($pasta . $fileName);
				}
			endif;
		endfor;

		// Alterando o arquivo de configurações

		try{
			if($this->config->setConfig($config)):
				echo json_encode(['RES' => true, 'MSG' => 'Imagens de destaque do sistema alteradas com sucessso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
			endif;
		}catch(Exception $e){
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
		}
	}

	public function alterar_endereco(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível alterar o endereço, ';
		$camposErrados = array();

		$cep = trim(addslashes(filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS)));
		$logradouro = trim(addslashes(filter_input(INPUT_POST, 'logradouro', FILTER_SANITIZE_SPECIAL_CHARS)));
		$numero = strtoupper(trim(addslashes(filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_SPECIAL_CHARS))));
		$bairro = trim(addslashes(filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_SPECIAL_CHARS)));
		$cidade = trim(addslashes(filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS)));
		$estado = explode('-', trim(addslashes(filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS))));

		if(strlen($cep) !== 8) array_push($camposErrados, 'CEP');
		if(empty($logradouro)) array_push($camposErrados, 'LOGRADOURO');
		if(strlen($numero) == 0) array_push($camposErrados, 'NÚMERO');
		if(empty($bairro)) array_push($camposErrados, 'BAIRRO');
		if(empty($cidade)) array_push($camposErrados, 'CIDADE');
		if(empty($estado) || count($estado) !== 2) array_push($camposErrados, 'ESTADO');

		try{
			if(empty($camposErrados)):
				$config = $this->config->getConfig();

				$config->address->street = $logradouro;
				$config->address->number = $numero;
				$config->address->district = $bairro;
				$config->address->city = $cidade;
				$config->address->postal_code = $cep;
				$config->address->state->name = $estado[0];
				$config->address->state->sigla = $estado[1];

				if($this->config->setConfig($config)):
					echo json_encode(['RES' => true, 'MSG' => 'Endereço alterado com sucesso!']);
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
			endif;
		}catch(Exception $e){
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro na tentativa de alteração!']);
		}
	}

	public function get_payment_config(){
		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$config = $this->config->getConfig();

		$config->payment->mode = PAYMENT_MODE;
		
		echo json_encode($config->payment);	
	}
}