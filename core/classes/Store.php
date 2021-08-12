<?php

namespace core\classes;

use core\classes\Database;

class Store{
	// MÉTODO QUE CARREGA AS VIEWS

	public static function layout(array $rotas, array $dados, int $tipo = HOME){
		extract($dados);

		foreach($rotas as $rota):
			require_once (($tipo === HOME) ? '../core/views/' : '../../core/views/') . $rota . '.php';
		endforeach;
	}

	// MÉTODO QUE REDIRECIONA O USUÁRIO

	public static function redirect(array $dados, int $tipo = HOME){
		header('location: ' . (($tipo === HOME) ? URL_HOME : URL_PAINEL) . '?' . http_build_query($dados));
		exit();
	}

	// MÉTODO QUE APLICA UMA MASCARA ESPECÍFICA NUMA STRING E A RETORNA

	public static function mask($mask, $string) : string{
		$i = $j = 0;

		while($i < strlen($mask)):
			if($mask[$i] === '#'):
				$mask[$i] = $string[$j];
				$j++;
			endif;

			$i++;
		endwhile;

		return $mask;
	}

	// MÉTODO QUE GERA O CURL DO USUÁRIO

	public static function curl(int $size) : string{
		$hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ01234567899876543210';

		return substr(str_shuffle($hash), 0, $size);
	}

	// VERIFICAR SE EXISTE ALGUM USUÁRIO LOGADO

	public static function logado() : bool{
		return (isset($_SESSION['SESSAO']) && isset($_SESSION[$_SESSION['SESSAO']]));
	}

	// GERAR SESSÃO DE CONEXÃO APÓS O LOGIN

	public static function iniciarSessao($cpf, $curl, $nome, $sobrenome, $email, $senha, $status, $acesso, $ativo, $img){
		$_SESSION['SESSAO'] = md5($curl);

		$_SESSION[$_SESSION['SESSAO']] = [
			'CPF' => $cpf,
			'CURL' => $curl,
			'NOME' => $nome,
			'SOBRENOME' => $sobrenome,
			'EMAIL' => $email,
			'SENHA' => $senha,
			'STATUS' => $status,
			'ACESSO' => $acesso,
			'ATIVO' => $ativo,
			'IMG' => base64_encode($img)
		];
	}

	// FECHAR SESSÃO DE CONEXÃO APÓS O LOGOUT

	public static function fecharSessao(){
		if(self::logado()):
			unset($_SESSION[$_SESSION['SESSAO']]);
			unset($_SESSION['SESSAO']);
		endif;
	}

	// MÉTODO QUE RETORNA OS DADOS DO USUÁRIO LOGADO

	public static function dadosUsuarioLogado() : array{
		if(self::logado()) return $_SESSION[$_SESSION['SESSAO']];

		return array();
	}

	// MÉTODO QUE ALTERA A SESSÃO COM OS DADOPS DO USUÁRIO

	public static function editDadosUsuarioLogado(array $dados){
		if(self::logado()) $_SESSION[$_SESSION['SESSAO']] = $dados;
	}

	// MÉTODO QUE RETORNA O TOTAL DE DADOS CADASTRADOS EM CADAS TABELA DO DB

	public static function totais() : array{
		return [
			'USUARIOS' => Database::EXECUTE_QUERY('SELECT COUNT(cpf) AS total FROM usuarios')[0]->total,
			'QUARTOS' => Database::EXECUTE_QUERY('SELECT COUNT(numero_quarto) AS total FROM quartos')[0]->total,
			'RESERVAS' => Database::EXECUTE_QUERY('SELECT COUNT(id_reserva) AS total FROM reservas')[0]->total,
			'PEDIDOS' => Database::EXECUTE_QUERY('SELECT COUNT(id_pedido_reserva) AS total FROM pedidos_reserva WHERE status_pedido = "P"')[0]->total
		]; 
	}
}