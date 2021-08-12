<?php

namespace core\models;

use core\classes\Store;
use core\classes\Email;
use core\classes\Database;

class Usuarios{
	// MÉTODO QUE RETORNA OS USUÁRIOS

	public function buscaUsuarios($pesquisa = '', $status = 'T', $acesso = 'T', $conta = 'T', int $min = 0) : array{
		$sql = ($acesso !== 'T') ? ' AND acesso = "' . $acesso . '"' : '';
		$sql .= ($status !== 'T') ? ' AND status_user = "' . $status . '"' : '';
		$sql .= ($conta !== 'T' && is_numeric($conta)) ? ' AND ativo = ' . $conta : '';

		$pesquisa = trim($pesquisa);

		if(!empty($pesquisa)):
			$pesquisa = str_ireplace('@', "\"@\"", $pesquisa);

			return Database::EXECUTE_QUERY('SELECT * FROM usuarios WHERE MATCH (cpf, email, nome, sobrenome) AGAINST (\'' . $pesquisa . '*\' IN BOOLEAN MODE)' . $sql . ' LIMIT ' . $min . ', 10');
		else:
			return Database::EXECUTE_QUERY('SELECT * FROM usuarios WHERE 1=1' . $sql . ' LIMIT ' . $min . ', 10', [':pesq' => $pesquisa]);
		endif;
	}

	// MÉTODO QUE BUSCA UM USUÁRIO PELO E-MAIL

	public function buscaUsuarioPorEmail(string $email) : ?object{
		$usuario = Database::EXECUTE_QUERY('SELECT * FROM usuarios WHERE email = :email LIMIT 1', [':email' => $email]);

		return (count($usuario) > 0) ? $usuario[0] : null;
	}

	// MÉTODO QUE BUSCA UM USUÁRIO PELO E-MAIL

	public function buscaUsuarioPorCurl(string $curl) : ?object{
		$usuario = Database::EXECUTE_QUERY('SELECT * FROM usuarios WHERE curl = :curl LIMIT 1', [':curl' => $curl]);

		return (count($usuario) > 0) ? $usuario[0] : null;
	}

	// METODO QUE VERIFICA SE O USUÁRIO TEM ALGUMAS RESERVAS NO HOTEL

	public function existeReservaUsuario($cpf) : bool{
		$res = Database::EXECUTE_QUERY('SELECT COUNT(*) AS existe WHERE status_reserva != "C" AND cpf_usuario = :cpf LIMIT 1', [':cpf' => $cpf]);

		if(count($res) > 0) return (bool) $res[0]->existe;
		else return false;
	}

	// MÉTODO QUE VERIFICA SE JÁ EXISTE UM USUÁRIO CADASTRADO COM O E-MAIL PASSADO

	public function emailExiste($email) : bool{
		$res = Database::EXECUTE_QUERY('CALL usuario_email_existe(:email)', [':email' => $email]);

		if(count($res) > 0) return (bool) $res[0]->existe;
		else return false;
	}

	// MÉTODO QUE VERIFICA SE JÁ EXISTE UM USUÁRIO CADASTRADO COM O CPF PASSADO

	public function CPFExiste($cpf) : bool{
		$res = Database::EXECUTE_QUERY('CALL usuario_cpf_existe(:cpf)', [':cpf' => $cpf]);

		if(count($res) > 0) return (bool) $res[0]->existe;
		else return false;
	}

	// MÉTODO QUE VERIFICA SE JÁ EXISTE UM USUÁRIO CADASTRADO COM O MESMO CURL

	public function curlExiste($curl) : bool{
		$res = Database::EXECUTE_QUERY('CALL usuario_curl_existe(:curl)', [':curl' => $curl]);

		if(count($res) > 0) return (bool) $res[0]->existe;
		else return false;
	}

	// MÉTODO QUE ENVIARÁ UM E-MAIL DE VALIDAÇÃO PARA O USUÁRIO

	public function enviaEmailValidacao(string $email, string $nome, string $curl) : bool{
		$assunto = 'Validação de E-Mail e ativação de sua conta em nosso site';
		$mensagem = '<header style="font-family: arial; 
								   padding: 10px;
								   border-bottom: 1px solid #e0e0e0;
								   color: #212121;">
						<img src="' . URL_HOME . 'assets/IMGS/' . LOGO . '" width="40" style="filter: drop-shadow(0px 0px 1px #4f0800); display: inline-block">
						<h3 style="display: inline-block">' . APP_NAME . '</h3>
					</header>';
		$mensagem .= '<h4 style="margin-top: 20px; font-family: arial;">Validação de E-Mail e ativação de sua conta em nosso site</h4>';
		$mensagem .= '<p style="margin-top: 20px;">Para prosseguir com a validação de sua conta, por favor click no link a seguir:</p>';
		$mensagem .= '<a style="margin-top: 20px;" href="' . URL_HOME . '?a=validar_conta&c=' . $curl . '">Validar Conta</a>';

		return Email::EnviarEmail($email, $nome, $assunto, $mensagem);
	}

	// MÉTODO QUE ENVIARÁ UM E-MAIL DE RECUPERAÇÃO DE SENHA AO USUÁRIO

	public function enviaEmailRecupera(string $email, string $nome, string $curl, string $senha) : bool{
		$assunto = 'Recuperação de Senha';
		$mensagem = '<header style="font-family: arial; 
								   padding: 10px;
								   border-bottom: 1px solid #e0e0e0;
								   color: #212121;">
						<img src="' . URL_HOME . 'assets/IMGS/' . LOGO . '" width="40" style="filter: drop-shadow(0px 0px 1px #4f0800); display: inline-block">
						<h3 style="display: inline-block">' . APP_NAME . '</h3>
					</header>';
		$mensagem .= '<h4 style="margin-top: 20px; font-family: arial;">Recuperação de Senha</h4>';
		$mensagem .= '<p style="margin-top: 20px;">Para recuperar sua senha em nosso site, por favor click no link a seguir:</p>';
		$mensagem .= '<a style="margin-top: 20px;" href="' . URL_HOME . '?a=recupera_senha&c=' . $curl . '&h=' . md5($curl . $senha) . '">Recuperar Senha</a>';

		return Email::EnviarEmail($email, $nome, $assunto, $mensagem);
	}

	// MÉTODO QUE IRÁ VALIDAR A CONTA DE UM USUÁRIO POR COMPLETO

	public function validarConta(string $curl) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_usuario_ativa(:curl)', [':curl' => $curl]);
	}

	// MÉTODO QUE IRÁ CADASTRAR UM NOVO USUÁRIO NO SISTEMA

	public function cadastrar($cpf, $nome, $sobrenome, $email, $senha, $curl) : bool{
		return Database::EXECUTE_NON_QUERY('CALL add_usuario(:cpf, :nome, :sobrenome, :email, :senha, :curl, :img)', [
			':cpf' => $cpf, 
			':nome' => $nome, 
			':sobrenome' => $sobrenome, 
			':email' => $email, 
			':senha' => password_hash($senha, PASSWORD_DEFAULT),
			':curl' => $curl,
			':img' => file_get_contents(IMG_PERFIL_DEFAULT)
		]);
	}

	// MÉTODO PARA VALIDAR O LOGIN DE ALGUM USUÁRIO

	public function validarLogin($email, $senha) : array{
		$dados = Database::EXECUTE_QUERY('SELECT * FROM usuarios WHERE email = :email AND deleted_at IS NULL LIMIT 1', [':email' => $email]);

		if(!empty($dados)):
			if($dados[0]->status_user == 'B') return ['RES' => false, 'MSG' => 'Não foi possível finalizar o login!, Esta conta está bloqueada pelo sistema!'];

			if(!$dados[0]->ativo) return ['RES' => false, 'MSG' => 'Não foi possível finalizar o login!, Esta conta ainda não foi verificada!'];

			if(password_verify($senha, $dados[0]->senha)) return ['RES' => true, 'DADOS' => $dados[0]];
		endif;

		return ['RES' => false, 'MSG' => 'E-Mail ou Senha Inválidos!'];
	}

	// MÉTODO PARA CADASTRAR UM USUÁRIO

	public function editarUsuario($cpf_antigo, $cpf_novo, $nome, $sobrenome, $email, $status, $acesso, bool $ativo) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_usuario(:cpf_a, :cpf_n, :nome, :sobrenome, :email, :status, :acesso, :ativo)', [
			':cpf_a' => $cpf_antigo,
			':cpf_n' => $cpf_novo,
			':nome' => $nome, 
			':sobrenome' => $sobrenome, 
			':email' => $email, 
			':status' => $status,
			':acesso' => $acesso,
			':ativo' => $ativo
		]);
	}

	// MÉTODO QUE ALTERA A SENHA DO USUÁRIO

	public function alterarSenha($cpf, $senha) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_usuario_senha(:cpf, :senha)', [
			':cpf' => $cpf,
			':senha' => $senha
		]);
	}

	// MÉTODO QUE ALTERA A IMAGEM DE PERFIL DO USUÁRIO

	public function alterarImagem($cpf, $img) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_usuario_img(:cpf, :img)', [
			':cpf' => $cpf,
			':img' => $img
		]);
	}

	// MÉTODO PARA DELETAR UM USUÁRIO

	public function deletaUsuario($cpf) : bool{
		return Database::EXECUTE_NON_QUERY('CALL del_usuario(:cpf)', [':cpf' => $cpf]);
	}

	// MÉTODO PARA RECUPERAR UM USUÁRIO DELETADO

	public function recuperaUsuario($cpf) : bool{
		return Database::EXECUTE_NON_QUERY('CALL rec_usuario(:cpf)', [':cpf' => $cpf]);
	}

	// MÉTODO QUE RETORNA O TOTAL DE USUÁRIOS DE ACORDO COM SEU ACESSO

	public function totalUsuariosAcesso() : array{
		$dados =  Database::EXECUTE_QUERY('SELECT COUNT(*) AS total, acesso FROM usuarios GROUP BY acesso');

		$valores = ['A' => 0, 'C' => 0];

		foreach($dados AS $dado):
			$valores[$dado->acesso] = $dado->total;
		endforeach;

		return $valores;
	}
}