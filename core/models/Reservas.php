<?php

namespace core\models;

use core\classes\Store;
use core\classes\Database;

class Reservas{
	// MÉTODO QUE SOLICITA UMA RESERVA PARA A ADMINISTRAÇÃO

	public function solicitaReserva(int $numero, $preco, $data_reserva, $data_encerrar, $cpf) : bool{
		return Database::EXECUTE_NON_QUERY('CALL add_pedido_reserva(:preco, :reserva, :encerrar, :numero, :cpf)', [
			':preco' => $preco,
			':reserva' => $data_reserva,
			':encerrar' => $data_encerrar,
			':numero' => $numero,
			':cpf' => $cpf
		]);
	}

	// MÉTODO QUE BUSCAS AS RESERVAS DO SISTEMA

	public function buscaReservas($num, $status, $data_reserva, $data_encerrar, int $min){
		$sql = (!empty($num)) ? ' AND r.numero_quarto = ' . $num : '';
		$sql .= (!empty($data_reserva)) ? ' AND r.data_reserva >= "' . $data_reserva . '"' : '';
		$sql .= (!empty($data_encerrar)) ? ' AND r.data_encerrar <= "' . $data_encerrar . '"' : '';
		$sql .= ($status !== 'T') ? ' AND r.status_reserva = "' . $status . '"' : '';

		return Database::EXECUTE_QUERY('SELECT r.id_reserva, r.preco_hora, r.data_reserva, r.data_encerrar, r.status_reserva, r.created_at, r.updated_at, q.numero_quarto, q.andar, t.nome_tipo, u.cpf, u.nome, u.sobrenome, u.email, u.img_perfil FROM reservas AS r INNER JOIN quartos AS q ON q.numero_quarto = r.numero_quarto INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto INNER JOIN usuarios AS u ON r.cpf_usuario = u.cpf WHERE 1=1 ' . $sql . ' ORDER BY r.numero_quarto LIMIT ' . $min . ', 10');
	}

	// MÉTODO QUE BUSCA RESERVAS DE UM DETERMINADO USUÁRIO

	public function buscaReservasUsuario($cpf, int $min){
		return Database::EXECUTE_QUERY('SELECT r.id_reserva, r.preco_hora, r.data_reserva, r.data_encerrar, r.status_reserva, r.created_at, r.updated_at, q.numero_quarto, q.andar, t.nome_tipo FROM reservas AS r INNER JOIN quartos AS q ON q.numero_quarto = r.numero_quarto INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE r.cpf_usuario = :cpf LIMIT ' . $min . ', 10', [':cpf' => $cpf]);
	}

	// MÉTODO QUE EDITA UMA RESERVA

	public function editaReserva(int $id, int $numero, $preco, $data_reserva, $data_encerrar, $status, $cpf) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_reserva(:id, :preco, :reserva, :encerrar, :status, :numero, :cpf)', [
			':id' => $id,
			':preco' => $preco,
			':reserva' => $data_reserva,
			':encerrar' => $data_encerrar,
			':status' => $status,
			':numero' => $numero,
			':cpf' => $cpf
		]);
	}

	// MÉTODO QUE EDITA O STATUS DE UMA RESERVA

	public function editaStatusReserva(int $id, $status) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_status_reserva(:id, :status)', [
			':id' => $id,
			':status' => $status
		]);
	}

	// MÉTODO QUE VERIFICA SE HÁ UMA SOLICITAÇÃO DE RESERVA FEITA PARA UM USUÁRIO

	public function solicitaReservaExiste(int $num, $cpf) : bool{
		return (bool) Database::EXECUTE_QUERY('CALL pedido_reserva_existe(:num, :cpf)', [':num' => $num, ':cpf' => $cpf])[0]->existe;
	}

	// MÉTODO QUE IRÁ BUSCAR AS SOLICITAÇÕES DE UM USUÁRIO

	public function buscaSolicitacoesUsuario($cpf, int $min = 0) : array{
		return Database::EXECUTE_QUERY('SELECT p.id_pedido_reserva, t.nome_tipo, p.numero_quarto, q.andar, p.preco_hora, p.data_reserva, p.data_encerrar, CASE p.status_pedido WHEN "P" THEN "PENDENTE" WHEN "N" THEN "NEGADO" ELSE "ACEITO" END AS status FROM pedidos_reserva AS p INNER JOIN quartos AS q ON p.numero_quarto = q.numero_quarto INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE p.cpf_usuario = :cpf ORDER BY p.id_pedido_reserva DESC LIMIT ' . $min . ', 10', [':cpf' => $cpf]);
	}

	// MÉTODO QUE BUSCA TODAS AS SOLICITAÇÕES DE RESERVAS FEITAS

	public function buscaSolicitacoesReservas(int $min) : array{
		return Database::EXECUTE_QUERY('SELECT p.id_pedido_reserva, t.nome_tipo, p.numero_quarto, q.andar, p.preco_hora, p.data_reserva, p.data_encerrar, CASE p.status_pedido WHEN "P" THEN "PENDENTE" WHEN "N" THEN "NEGADO" ELSE "ACEITO" END AS status, u.cpf, CONCAT(u.nome, " ", u.sobrenome) AS nome_completo, u.email, u.img_perfil FROM pedidos_reserva AS p INNER JOIN usuarios AS u ON u.cpf = p.cpf_usuario INNER JOIN quartos AS q ON p.numero_quarto = q.numero_quarto INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE p.status_pedido = "P" ORDER BY p.id_pedido_reserva DESC LIMIT ' . $min . ', 10');
	}

	// MÉTODO QUE IRÁ VERIFICAR SE UMA SOLICITAÇÃO É DE UM USUÁRIO ESPECÍFICO

	public function solicitacaoReservaUsuarioExiste(int $id, $cpf) : bool{
		return (bool) Database::EXECUTE_QUERY('SELECT COUNT(*) FROM pedidos_reserva AS p INNER JOIN quartos AS q ON p.numero_quarto = q.numero_quarto INNER JOIN tipos_quarto AS t ON q.id_tipo_quarto = t.id_tipo_quarto WHERE p.cpf_usuario = :cpf AND p.id_pedido_reserva = :id AND p.status_pedido = "P" LIMIT 1', [':cpf' => $cpf, ':id' => $id]);
	}

	// MÉTODO QUE IRÁ CANCELAR/APAGAR UMA SOLCITAÇÃO DE RESERVA

	public function cancelaPedidoReserva(int $id) : bool{
		return Database::EXECUTE_NON_QUERY('CALL del_pedido_reserva(:id)', [':id' => $id]);
	}

	// MÉTODO QUE IRÁ FINALIZAR UM PEDIDO DE RESERVA

	public function finalizaPedidoReserva(int $id, $status) : bool{
		return Database::EXECUTE_NON_QUERY('CALL finaliza_pedido_reserva(:id, :status)', [':id' => $id, ':status' => $status]);
	}

	// MÉTODO QUE IRÁ FINALIZAR UMA RESERVA

	public function finalizaReserva($id) : bool{
		return Database::EXECUTE_NON_QUERY('CALL finaliza_reserva(:id)', [':id' => $id]);
	}

	// MÉTODO QUE IRÁ VERIFICAR SE UMA RESERVA PERTENCE A UM DETERMINADO USUÁRIO

	public function reservaUsuarioExiste(int $id, $cpf) : bool{
		return (bool) Database::EXECUTE_QUERY('SELECT COUNT(*) FROM reservas WHERE cpf_usuario = :cpf AND id_reserva = :id LIMIT 1', [':cpf' => $cpf, ':id' => $id]);
	}

	// MÉTODO QUE RETORNA O TOTAL DE RESERVAS DE ACORDO COM SEU STATUS

	public function totalReservasStatus() : array{
		$dados = Database::EXECUTE_QUERY('SELECT COUNT(*) AS total, status_reserva FROM reservas GROUP BY status_reserva');

		$valores = ['R' => 0, 'P' => 0, 'C' => 0];

		foreach($dados AS $dado):
			$valores[$dado->status_reserva] = $dado->total;
		endforeach;

		return $valores;
	}

	// MÉTODO QUE BUSCA O TOTAL DE RESERVAS DA ÚLTIMA SEMANA

	public function totalReservasSemana() : array{
		$dados = Database::EXECUTE_QUERY('SELECT COUNT(*) AS total, WEEKDAY(created_at) AS dia_semana FROM reservas WHERE WEEK(CURRENT_TIMESTAMP) = WEEK(created_at) GROUP BY WEEKDAY(created_at)');

		$semanas = ['Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado', 'Domingo'];
		$valores = [];

		for($i = 0; $i < (int)date('w'); $i++) $valores[$semanas[$i]] = 0;

		foreach($dados AS $dado) $valores[$semanas[$dado->dia_semana]] = $dado->total;

		return $valores;
	}

	// MÉTODO QUE BUSCA O TOTAL DE RESERVAS DOS ÚLTIMOS MESES DO ANO

	public function totalReservasMeses() : array{
		$dados = Database::EXECUTE_QUERY('SELECT COUNT(*) AS total, MONTH(created_at) AS mes FROM reservas WHERE YEAR(CURRENT_TIMESTAMP) = YEAR(created_at) GROUP BY MONTH(created_at)');

		$meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
		
		$valores = [];

		for($i = 0; $i < (int)date('m'); $i++) $valores[$meses[$i]] = 0;

		foreach($dados AS $dado) $valores[$meses[$dado->mes - 1]] = $dado->total;

		return $valores;
	}

	// MÉTODO QUE BUSCA O TOTAL DE RESERVAS DOS ÚLTIMOS ANOS

	public function totalReservasAnos() : array{
		$dados = Database::EXECUTE_QUERY('SELECT COUNT(*) AS total, YEAR(created_at) AS ano FROM reservas WHERE YEAR(created_at) BETWEEN YEAR(CURRENT_TIMESTAMP) - 20 AND YEAR(CURRENT_TIMESTAMP) GROUP BY YEAR(created_at)');

		$valores = [];
		$ano = (int)date('Y');

		for($i = $ano - 20; $i <= $ano; $i++) $valores[$i] = 0;
		foreach($dados AS $dado) $valores[$dado->ano] = $dado->total;

		return $valores;
	} 

	// MÉTODO QUE BUSCA O TOTAL A PAGAR DE UMA RESERVA

	public function totalPagar(int $id){
		$res = Database::EXECUTE_QUERY('CALL total_reserva(:id)', [':id' => $id]);

		if(empty($res)) return 0;

		return $res[0]->total;
	}
}