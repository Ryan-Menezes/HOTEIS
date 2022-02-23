<?php

namespace core\controllers;

use core\classes\Store;
use core\classes\Email;
use core\models\Usuarios;
use core\models\Notificacoes;

class UsuariosController{
	private $usuarios;

	public function __construct(){
		$this->usuarios = new Usuarios();
	}

	public function usuarios(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$notificacao = new Notificacoes();

		// Carregando os dados da página

		$dados = [
			'titulo' => 'Usuários',
			'dadosUser' => $dadosUser,
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/usuarios',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}

	// MÉTODO QUE EXECUTARÁ O CADASTRO DO USUÁRIO E RETORNARÁ UM JSON COM O RESULTADO

	public function cadastro(){
		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$camposErrados = array();
		$msg = 'Não foi possível finalizar o cadastro, ';

		// Buscando os dados passados pelo form

		$cpf = trim(filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$sobrenome = trim(filter_input(INPUT_POST, 'sobrenome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
		$senha = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$rsenha = trim(filter_input(INPUT_POST, 'rsenha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		// Validando os dados passados

		if(empty($cpf) || !is_numeric($cpf) || strlen($cpf) != 11) array_push($camposErrados, 'CPF');
		if(empty($nome)) array_push($camposErrados, 'NOME');
		if(empty($sobrenome)) array_push($camposErrados, 'SOBRENOME');
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($camposErrados, 'E-MAIL');
		if(mb_strlen($senha) < 8) array_push($camposErrados, 'SENHA(no mínimo 8 caracteres)');

		if(empty($camposErrados)):
			// Validando a senha

			if($senha === $rsenha):
				// Validando o CPF

				if(!$this->usuarios->CPFExiste($cpf)):
					// Validando o E-MAIL

					if(!$this->usuarios->emailExiste($email)):
						// Gerando um curl único para o usuário

						do{
							$curl = Store::curl(20);
						}while($this->usuarios->curlExiste($curl));
						
						// Finalizando o cadastro e enviando um E-Mail de validação da conta

						if($this->usuarios->cadastrar($cpf, $nome, $sobrenome, $email, $senha, $curl)):
							if($this->usuarios->enviaEmailValidacao($email, $nome, $curl)):
								echo json_encode(['RES' => true, 'MSG' => 'Usuário cadastrado com sucesso!, Enviamos um link de ativação para seu E-Mail, por favor vefique sua caixa de E-Mail ou spans!']);
							else:
								echo json_encode(['RES' => true, 'MSG' => 'Usuário cadastrado com sucesso!, Porém NÃO foi possível enviarmos um link de ativação para seu E-Mail!']);
							endif;
						else:
							echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de cadastro!']);
						endif;
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um usuário cadastrado com este E-Mail!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um usuário cadastrado com este CPF!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'As senhas informadas não se coincidem!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
		endif;
	}

	// MÉTODO QUE IRÁ VALIDAR A CONTA DO USUÁRIO

	public function validar_conta(){
		// Verificando se o curl foi passado pela URL

		if(!isset($_GET['c'])):
			Store::redirect(['a' => 'inicio']);
		endif;

		$curl = trim(filter_input(INPUT_GET, 'c', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		if($this->usuarios->curlExiste($curl)):
			$this->usuarios = new Usuarios();
			if($this->usuarios->validarConta($curl)) $mensagem = 'Conta validada com sucesso!';
			else $mensagem = 'Conta NÃO validada!, Infelizmente deve ter ocorrido um erro no processo de validação!';
		else:
			$mensagem = 'A conta que você está tentando validar ou já foi validada ou o link acessado está quebrado!';
		endif;

		// Apresentando a mensagem final

		$dados = [
			'titulo' => 'Validação de conta',
			'mensagem' => $mensagem
		];

		Store::layout([
			'layout/html_header',
			'mensagem',
			'layout/footer',
			'layout/html_footer'
		], $dados);
	}

	// MÉTODO PARA VALIDAR O LOGIN DO USUÁRIO

	public function login(){
		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
		$senha = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		// Validando o login

		$login = $this->usuarios->validarLogin($email, $senha);

		if($login['RES']):
			Store::iniciarSessao($login['DADOS']->cpf, $login['DADOS']->curl, $login['DADOS']->nome, $login['DADOS']->sobrenome, $login['DADOS']->email, $login['DADOS']->senha, $login['DADOS']->status_user, $login['DADOS']->acesso, $login['DADOS']->ativo, $login['DADOS']->img_perfil);

			Store::redirect(['a' => 'inicio'], PAINEL);
		else:
			$_SESSION['msg'] = $login['MSG'];
			Store::redirect(['a' => 'inicio']);
		endif;
	}

	public function pesquisa_usuarios(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um cliente

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$conta = trim(filter_input(INPUT_POST, 'conta', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$acesso = trim(filter_input(INPUT_POST, 'acesso', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$pesquisa = trim(filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT) ?? '');

		if(empty($acesso)) $acesso = 'T';
		if(empty($status)) $status = 'T';
		if(!is_numeric($min)) $min = 0;

		// Formatando uma apresentação e retornando-a

		$html = '';

		$usuarios = $this->usuarios->buscaUsuarios($pesquisa, $status, $acesso, $conta, $min);

		if(!empty($usuarios)):
			foreach($usuarios as $usuario):
				$dadosUsuario = ['IMG' => base64_encode($usuario->img_perfil), 'CPF' => $usuario->cpf, 'CPFMASK' => Store::mask('###.###.###-##', $usuario->cpf), 'NOME' => $usuario->nome,  'SOBRENOME' => $usuario->sobrenome, 'EMAIL' => $usuario->email, 'ATIVO' => $usuario->ativo, 'STATUS' => (($usuario->status_user == 'B') ? 'Bloqueado' : 'Desbloqueado'), 'ACESSO' => (($usuario->acesso == 'A') ? 'Administrador' : 'Cliente')];

				// Setando o botão de deltar conforme as situações

				$btnConfig = '<button class="bg-vermelho" title="Deletar" onclick=\'deletarUsuarioModal(`' . $dadosUsuario['CPF'] . '`)\'><i class="fas fa-trash-alt"></i></button> <button class="bg-azul" title="Editar" onclick=\'editarUsuario(`' . json_encode($dadosUsuario) . '`)\'><i class="fas fa-pencil-alt"></i></button>';

				if(!empty($usuario->deleted_at) || !is_null($usuario->deleted_at)):
					$btnConfig = '<button class="bg-vermelho" title="Recuperar" onclick=\'recuperaUsuarioModal(`' . $dadosUsuario['CPF'] . '`)\'><i class="fas fa-history"></i></button> <button class="bg-azul" title="Editar" onclick=\'editarUsuario(`' . json_encode($dadosUsuario) . '`)\'><i class="fas fa-pencil-alt"></i></button>';
				endif;

				if($dadosUser['CPF'] === $dadosUsuario['CPF']) $btnConfig = '';

				$html .= '<tr>
							<td><img src="data:image/*;base64,' . $dadosUsuario['IMG'] . '"></td>
							<td>' . $dadosUsuario['CPFMASK'] . '</td>
							<td>' . $dadosUsuario['NOME'] . ' ' . $dadosUsuario['SOBRENOME'] . '</td>
							<td>' . $dadosUsuario['ACESSO'] . '</td>
							<td>' . $dadosUsuario['STATUS'] . '</td>
							<td>' . date('d/m/Y H:i:s', strtotime($usuario->created_at)) . '</td>
							<td>' . date('d/m/Y H:i:s', strtotime($usuario->updated_at)) . '</td>
							<td>' . ((empty($usuario->deleted_at) || is_null($usuario->deleted_at)) ? '<i class="fas fa-times-circle vermelho">' : '<i class="fas fa-check-circle verde"></i>')  . '</i></td>
							<td>
								<button class="bg-verde" title="Mais Informações" onclick=\'visualizarUsuario(`' . json_encode($dadosUsuario) . '`)\'><i class="fas fa-info-circle"></i></button>
								' . $btnConfig . '</td>					
						</tr>';
			endforeach;

			if(count($usuarios) >= 10):
				$html .= '<tr><td colspan="9">';
				$html .= '<form method="POST" action="?a=pesquisa_usuarios" class="formularioCarrega" onsubmit="executaFormCarrega(carregaUsuarios, loadingCarregaUsuarios, null)">
							<input type="hidden" name="min" value="' . ($min ) . '">
							<input type="hidden" name="conta" value="' . $conta . '">
							<input type="hidden" name="acesso" value="' . $acesso . '">
							<input type="hidden" name="status" value="' . $status . '">
							<input type="hidden" name="pesquisa" value="' . $pesquisa . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
				$html .= '</td></tr>';
			endif;
		else:
			if($min == 0):
				if(empty($pesquisa)) $html = '<tr><td colspan="9"><h4 style="grid-column: 1/5">Não há usuários cadastrados no sistema</h4></td></tr>';
				else $html = '<tr><td colspan="9"><h4 style="grid-column: 1/5">Não foi possível localizar um usuário com os dados parecidos com "' . $pesquisa . '"</h4></td></tr>';
			endif;
		endif;

		echo $html;
	}

	public function editar_usuario(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Valida os dados passados por POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$camposErrados = array();
		$msg = 'Não foi possível finalizar a edição, ';

		// Buscando os dados passados pelo form

		$cpf = trim(filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$cpf_antigo = trim(filter_input(INPUT_POST, 'cpf_antigo', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$sobrenome = trim(filter_input(INPUT_POST, 'sobrenome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
		$email_antigo = trim(filter_input(INPUT_POST, 'email_antigo', FILTER_SANITIZE_EMAIL) ?? '');
		$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$acesso = trim(filter_input(INPUT_POST, 'acesso', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$ativo = trim(filter_input(INPUT_POST, 'ativo', FILTER_SANITIZE_NUMBER_INT) ?? '');

		// Validando os dados passados

		if(empty($cpf) || !is_numeric($cpf) || strlen($cpf) != 11) array_push($camposErrados, 'CPF');
		if(empty($nome)) array_push($camposErrados, 'NOME');
		if(empty($sobrenome)) array_push($camposErrados, 'SOBRENOME');
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($camposErrados, 'E-MAIL');
		if(empty($status)) array_push($camposErrados, 'STATUS DO USUÁRIO');
		if(empty($acesso)) array_push($camposErrados, 'ACESSO');
		if(!is_numeric($ativo)) array_push($camposErrados, 'VERIFICAÇÃO DA CONTA');

		// Impedindo que o usuário logado edite seus dados

		if($cpf === $dadosUser['CPF']):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de edição!']);
			return;
		endif;

		if(empty($camposErrados)):
			// Validando o CPF

			if(!$this->usuarios->CPFExiste($cpf) || $cpf == $cpf_antigo):
				// Validando o E-MAIL

				if(!$this->usuarios->emailExiste($email) || $email == $email_antigo):						
					// Finalizando a edição

					if($this->usuarios->editarUsuario($cpf, $cpf_antigo, $nome, $sobrenome, $email, $status, $acesso, (bool)$ativo)):
						echo json_encode(['RES' => true, 'MSG' => 'Usuário editado com sucesso!']);
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de edição!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um usuário cadastrado com este E-Mail!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um usuário cadastrado com este CPF!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'o seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
		endif;
	}

	public function editar_dados_pessoais(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$dadosUsuario = Store::dadosUsuarioLogado();

		// Valida os dados passados por POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$camposErrados = array();
		$msg = 'Não foi possível alterar seus dados pessoais, ';

		// Buscando os dados passados pelo form

		$cpf = trim(filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$sobrenome = trim(filter_input(INPUT_POST, 'sobrenome', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');

		// Validando os dados passados

		if(empty($cpf) || !is_numeric($cpf) || strlen($cpf) != 11) array_push($camposErrados, 'CPF');
		if(empty($nome)) array_push($camposErrados, 'NOME');
		if(empty($sobrenome)) array_push($camposErrados, 'SOBRENOME');
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($camposErrados, 'E-MAIL');

		if(empty($camposErrados)):
			// Validando o CPF

			if(!$this->usuarios->CPFExiste($cpf) || $cpf == $dadosUsuario['CPF']):
				// Validando o E-MAIL

				if(!$this->usuarios->emailExiste($email) || $email == $dadosUsuario['EMAIL']):						
					// Finalizando a edição

					if($this->usuarios->editarUsuario($cpf, $dadosUsuario['CPF'], $nome, $sobrenome, $email, $dadosUsuario['STATUS'], $dadosUsuario['ACESSO'], (bool)$dadosUsuario['ATIVO'])):
						// Alterando os dados do usuário na sessão

						$dadosUsuario['CPF'] = $cpf;
						$dadosUsuario['NOME'] = $nome;
						$dadosUsuario['SOBRENOME'] = $sobrenome;
						$dadosUsuario['EMAIL'] = $email;

						Store::editDadosUsuarioLogado($dadosUsuario);

						echo json_encode(['RES' => true, 'MSG' => 'Dados pessoais alterados com sucesso!']);
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um usuário cadastrado com este E-Mail!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um usuário cadastrado com este CPF!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'o seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
		endif;
	}

	public function alterar_senha(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um administrador

		$dadosUsuario = Store::dadosUsuarioLogado();

		// Valida os dados passados por POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$msg = 'Não foi possível alterar sua senha, ';

		// Buscando os dados passados pelo form

		$senha = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$nsenha = trim(filter_input(INPUT_POST, 'nsenha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$rnsenha = trim(filter_input(INPUT_POST, 'rnsenha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		// Validando a senha atual

		if(password_verify($senha, $dadosUsuario['SENHA'])):
			// Validando a nova senha

			if(mb_strlen($nsenha) >= 8):
				if($nsenha === $rnsenha):						
					// Encriptando a nova senha

					$nsenha = password_hash($nsenha, PASSWORD_DEFAULT);

					if($this->usuarios->alterarSenha($dadosUsuario['CPF'], $nsenha)):
						// Alterando a senha do usuário na sessão

						$dadosUsuario['SENHA'] = $nsenha;

						Store::editDadosUsuarioLogado($dadosUsuario);

						echo json_encode(['RES' => true, 'MSG' => 'Senha alterada com sucessso!']);
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'A senha repetida e a nova senha não se coincidem!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Sua nova senha deve conter no mínimo 8 caracteres!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'A senha digitada é inválida!']);
		endif;
	}

	public function alterar_imagem_perfil(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$dadosUsuario = Store::dadosUsuarioLogado();

		// Valida os dados passados por POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$msg = 'Não foi possível alterar sua imagem de perfil, ';

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

		// Criando um objeto da classe Usuarios

		$img = file_get_contents($imagem['tmp_name']);

		if($this->usuarios->alterarImagem($dadosUsuario['CPF'], $img)):
			// Alterando a imagem do usuário na sessão

			$dadosUsuario['IMG'] = base64_encode($img);

			Store::editDadosUsuarioLogado($dadosUsuario);

			echo json_encode(['RES' => true, 'MSG' => 'Imagem de perfil alterada com sucessso!']);
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
		endif;
	}

	public function deleta_usuario(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível deletar este usuário, ';

		// Validando os dados do POST

		$cpf = trim(filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT) ?? '');

		// Impedindo que o usuário logado edite seus dados

		if($cpf === $dadosUser['CPF']):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao deletar este usuário!']);
			return;
		endif;

		// Verificando se o cpf é válido

		if(!is_numeric($cpf) || empty($cpf)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao deletar este usuário!']);
			return;
		endif;

		// Verificando se o cpf indicado é o mesmo do usuário logado

		if($dadosUser['CPF'] == $cpf):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Não é possível deletar a sua conta por aqui, Isso somente é possível fazer nas configurações!']);
			return;
		endif;

		// Verificando sde o usuário tem alguma reserva NÃO concluida no hotel

		if(!$this->usuarios->existeReservaUsuario($cpf)):
			if($this->usuarios->deletaUsuario($cpf)):
				echo json_encode(['RES' => true, 'MSG' => 'Usuário deletado com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao deletar este usuário!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Este usuário possui algumas reservas NÃO concluidas em nosso hotel, Só será possível deletá-lo após a conclusão das mesmas!']);
		endif;
	}

	public function recupera_usuario(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível recuperar este usuário, ';

		// Validando os dados do POST

		$cpf = trim(filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT) ?? '');

		// Verificando se o cpf é válido

		if(!is_numeric($cpf) || empty($cpf)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao recuperar este usuário!']);
			return;
		endif;

		// Verificando se o cpf indicado é o mesmo do usuário logado

		if($dadosUser['CPF'] == $cpf):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Não é possível recuperar a sua conta por aqui!, Isso somente pode ser feito por outro usuário administrador!']);
			return;
		endif;

		if($this->usuarios->recuperaUsuario($cpf)):
			echo json_encode(['RES' => true, 'MSG' => 'Usuário recuperado com sucesso!']);
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao recuperar este usuário!']);
		endif;
	}

	public function deletar_conta(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		// Verificando se o usuário logado é um administrador

		$dadosUsuario = Store::dadosUsuarioLogado();

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível deletar sua conta, ';

		// Validando os dados do POST

		$senha = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		// Validando a senha e deletando a conta

		if(password_verify($senha, $dadosUsuario['SENHA'])):
			// Verificando se o usuário tem alguma reserva NÃO concluida no hotel

			if(!$this->usuarios->existeReservaUsuario($dadosUsuario['CPF'])):
				if($this->usuarios->deletaUsuario($dadosUsuario['CPF'])):
					Store::fecharSessao(); // Fazendo logout na conta

					$msg = 'Conta deletada com sucesso, caso você queira recuperar esta conta, você deve entrar em contato com nossa equipe dentro de um mês, caso contrário sua conta não terá mais salvação!';
				else:
					$msg .= 'Ocorreu um erro ao deletar sua conta!';
				endif;
			else:
				$msg .= 'Você possui algumas reservas NÃO concluidas em nosso hotel, Só será possível deletar sua conta após a conclusão das mesmas!';
			endif;
		else:
			$msg .= 'A senha informada é inválida!';
		endif;

		// Redirecionando para uma tela de mensagens

		$dados = [
			'titulo' => 'Exclusão de Conta',
			'mensagem' => $msg,
			'painel' => true
		];

		Store::layout([
			'layout/html_header',
			'mensagem',
			'layout/footer',
			'layout/html_footer'
		], $dados, PAINEL);
	}

	public function envia_recupera_senha(){
		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$msg = 'Não foi possível enviar um E-Mail de recuperação de senha, ';

		// Validando os dados do POST

		$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');

		// Verificando se o E-Mail foi preenchido

		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Preencha o campo E-Mail corretamente!']);
			return;
		endif;

		// Verificando se o email existe

		if($this->usuarios->emailExiste($email)):
			$usuario = $this->usuarios->buscaUsuarioPorEmail($email);

			if(!is_null($usuario) && $this->usuarios->enviaEmailRecupera($email, $usuario->nome, $usuario->curl, $usuario->senha)):
				echo json_encode(['RES' => true, 'MSG' => 'Foi enviado um link de recuperação para o E-Mail informado!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar enviar o E-Mail de recuperação!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'O E-Mail informado não está relacionado a algum usuário em nosso hotel!']);
		endif;
	}

	public function recupera_senha(){
		// Verificando se houve uma requisição GET

		if($_SERVER['REQUEST_METHOD'] !== 'GET'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$curl = trim(filter_input(INPUT_GET, 'c', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$hash = trim(filter_input(INPUT_GET, 'h', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		if(!empty($curl) && !empty($hash)):
			$usuario = $this->usuarios->buscaUsuarioPorCurl($curl);

			if(!is_null($usuario)):
				if(md5($curl . $usuario->senha) === $hash):
					// Redirecionando para uma tela de recuperação de senha

					$dados = [
						'titulo' => 'Gerar nova senha',
						'curl' => $curl,
						'hash' => $hash,
						'phone' => Store::mask('(##)#####-####', ADDRESS_PHONE),
						'postal_code' => Store::mask('#####-###', ADDRESS_POSTAL_CODE)
					];

					Store::layout([
						'layout/html_header',
						'recupera_senha',
						'layout/footer',
						'layout/html_footer'
					], $dados);
				else:
					Store::redirect(['a' => 'inicio']);
				endif;
			else:
				Store::redirect(['a' => 'inicio']);
			endif;
		else:
			Store::redirect(['a' => 'inicio']);
		endif;
	}

	public function alterar_senha_recupera(){
		// Verificando se o usuário logado é um administrador

		$dadosUsuario = Store::dadosUsuarioLogado();

		// Valida os dados passados por POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio']);
		endif;

		$msg = 'Não foi possível alterar sua senha, ';

		// Buscando os dados passados pelo form

		$nsenha = trim(filter_input(INPUT_POST, 'nsenha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$rnsenha = trim(filter_input(INPUT_POST, 'rnsenha', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		$curl = trim(filter_input(INPUT_POST, 'c', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$hash = trim(filter_input(INPUT_POST, 'h', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		if(!empty($curl) && !empty($hash)):
			$usuario = $this->usuarios->buscaUsuarioPorCurl($curl);

			if(!is_null($usuario)):
				if(md5($curl . $usuario->senha) === $hash):
					// Validando a nova senha

					if(mb_strlen($nsenha) >= 8):
						if($nsenha === $rnsenha):						
							// Encriptando a nova senha

							$nsenha = password_hash($nsenha, PASSWORD_DEFAULT);

							if($this->usuarios->alterarSenha($usuario->cpf, $nsenha)):
								echo json_encode(['RES' => true, 'MSG' => 'Senha alterada com sucessso!']);
							else:
								echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
							endif;
						else:
							echo json_encode(['RES' => false, 'MSG' => $msg . 'A senha repetida e a nova senha não se coincidem!']);
						endif;
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Sua nova senha deve conter no mínimo 8 caracteres!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de alteração!']);
		endif;
	}
}