<?php

namespace core\models;

use core\classes\Store;
use core\classes\Database;

class Quartos{
	// MÉTODO QUE RETORNA TODOS OS QUARTOS

	public function buscaQuartos($numero, $status = 'T', $tipo = 'T', int $min = 0) : array{
		$sql = (!empty($numero)) ? ' AND q.numero_quarto = ' . $numero : '';
		$sql .= ($status !== 'T') ? ' AND q.status_quarto = "' . $status . '"' : '';
		$sql .= ($tipo !== 'T') ? ' AND q.id_tipo_quarto = ' . $tipo : '';

		return Database::EXECUTE_QUERY('SELECT * FROM quartos AS q INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE 1=1 ' . $sql . ' ORDER BY numero_quarto LIMIT ' . $min . ', 10');
	}

	// MÉTODO QUE RETORNA TODOS OS QUARTOS

	public function buscaQuartosDisponiveis($cpf, $tipo, int $min = 0) : array{
		if($tipo === 'T')
			return Database::EXECUTE_QUERY('SELECT * FROM quartos AS q INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE NOT EXISTS(SELECT * FROM pedidos_reserva AS p WHERE p.cpf_usuario = :cpf AND p.numero_quarto = q.numero_quarto AND p.status_pedido = "P") AND q.status_quarto = "D" ORDER BY numero_quarto LIMIT ' . $min . ', 10', [':cpf' => $cpf]);
		else
			return Database::EXECUTE_QUERY('SELECT * FROM quartos AS q INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE NOT EXISTS(SELECT * FROM pedidos_reserva AS p WHERE p.cpf_usuario = :cpf AND p.numero_quarto = q.numero_quarto AND p.status_pedido = "P") AND q.status_quarto = "D" AND q.id_tipo_quarto = :tipo ORDER BY numero_quarto LIMIT ' . $min . ', 10', [':cpf' => $cpf, ':tipo' => $tipo]);
	}

	// METODO QUE VERIFICA SE O QUARTO ESTÁ ENVOLVIDO EM ALGUMAS RESERVAS NO HOTEL

	public function existeReservaQuarto($num) : bool{
		$res = Database::EXECUTE_QUERY('SELECT COUNT(*) AS existe WHERE status_reserva != "C" AND numero_quarto = :num LIMIT 1', [':num' => $num]);

		if(count($res) > 0) return (bool) $res[0]->existe;
		else return false;
	}

	// MÉTODO QUE RETORNA TODOS OS TIPOS DE QUARTO CADASTRADOS NO BANCO DE DADOS

	public function buscaTiposQuarto() : array{
		return Database::EXECUTE_QUERY('SELECT * FROM tipos_quarto');
	}

	// MÉTODO QUE RETORNA OS DADOS DE UM QUARTO

	public function buscaQuarto(int $numero) : ?object{
		$quarto = Database::EXECUTE_QUERY('SELECT * FROM quartos WHERE numero_quarto = :num LIMIT 1', [':num' => $numero]);

		return (count($quarto) > 0) ? $quarto[0] : null;
	}

	// MÉTODO QUE VERIFICA SE JÁ EXISTE UM QUARTO CADASTRADO COM O NÚMERO ESPECIFICADO

	public function numeroExiste(int $numero) : bool{
		return (bool) Database::EXECUTE_QUERY('CALL quarto_numero_existe(:num)', [':num' => $numero])[0]->existe;
	}

	// MÉTODO QUE CADASTRA UM NOVO QUARTO

	public function cadastrarQuarto(int $numero, int $andar, float $preco, int $tipo) : bool{
		return Database::EXECUTE_NON_QUERY('CALL add_quarto(:num, :andar, :preco, :tipo)', [
			':num' => $numero,
			':andar' => $andar,
			':preco' => $preco,
			':tipo' => $tipo
		]);
	}

	// MÉTODO QUE IRÁ EDITAR UM QUARTO

	public function editarQuarto(int $numero_atual, int $numero_editado, int $andar, float $preco, $status, int $tipo) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_quarto(:num_a, :num_e, :andar, :preco, :status, :tipo)', [
			':num_a' => $numero_atual,
			':num_e' => $numero_editado,
			':andar' => $andar,
			':preco' => $preco,
			':status' => $status,
			':tipo' => $tipo
		]);
	}

	// MÉTODO QUE DELETA UM QUARTO

	public function deletaQuarto(int $numero) : bool{
		return Database::EXECUTE_NON_QUERY('CALL del_quarto(:num)', [':num' => $numero]);
	}
}