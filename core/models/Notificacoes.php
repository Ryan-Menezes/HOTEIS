<?php

namespace core\models;

use core\classes\Store;
use core\classes\Database;

class Notificacoes{
	// MÉTODO QUE RETORNA TODOS AS NOTIFICAÇÕES DE UM USUÁRIO

	public function buscaNotificacoes($cpf, int $min) : array{
		return Database::EXECUTE_QUERY('SELECT * FROM notificacoes WHERE cpf_usuario = :cpf ORDER BY created_at DESC LIMIT ' . $min . ', 10', [':cpf' => $cpf]);
	}

	// MÉTODO QUE VERIFICA SE UMA NOTIFICAÇÃO PERTENCE A UM CERTO USUÁRIO

	public function existeNotificacao($cpf, int $id) : bool{
		return (bool) Database::EXECUTE_QUERY('SELECT COUNT(*) FROM notificacoes WHERE cpf_usuario = :cpf AND id_notificacao = :id LIMIT 1', [':cpf' => $cpf, ':id' => $id]);
	}

	// MÉTODO QUE VERIFICA SE UMA NOTIFICAÇÃO FOI VISUALIZADA

	public function notificacaoVisualizada(int $id) : bool{
		$res = Database::EXECUTE_QUERY('SELECT visualizado FROM notificacoes WHERE id_notificacao = :id LIMIT 1', [':id' => $id]);

		if(count($res) == 0) return false;

		return (bool)$res[0]->visualizado;
	}

	// MÉTODO QUE DELETA UMA NOTIFICACAO

	public function deletaNotificacao($cpf, int $id) : bool{
		return Database::EXECUTE_NON_QUERY('DELETE FROM notificacoes WHERE cpf_usuario = :cpf AND id_notificacao = :id LIMIT 1', [':cpf' => $cpf, ':id' => $id]);
	}

	// MÉTODO QUE RETORNA O TOTAL DE NOTIFICAÇÕES NÃO LIDAS DE UM USUÁRIO

	public static function totalNotificacoes($cpf) : int{
		return Database::EXECUTE_QUERY('SELECT COUNT(*) AS total FROM notificacoes WHERE visualizado = FALSE AND cpf_usuario = :cpf', [':cpf' => $cpf])[0]->total;
	}

	// MÉTODO QUE VISUALIZA UMA NOTIFICAÇÃO

	public function visualizaNotificacao($cpf, int $id) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_notificacao_visualizacao(:id, :cpf, TRUE)', [':id' => $id, ':cpf' => $cpf]);
	}
}