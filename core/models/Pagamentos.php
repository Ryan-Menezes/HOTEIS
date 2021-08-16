<?php

namespace core\models;

use core\classes\Store;
use core\classes\Database;

class Pagamentos{
	// MÉTODO QUE ADICIONA UM PAGAMENTO A UMA RESERVA

	public function addPagamento($payment_id, $valor, $status, int $id_res) : bool{
		return Database::EXECUTE_NON_QUERY('CALL add_pagamento(:pay_id, :valor, :status, :id)', [':pay_id' => $payment_id, ':valor' => $valor, ':status' => $status, ':id' => $id_res]);
	}

	// MÉTODO QUE EDITA O STATUS DE UM PAGAMENTO

	public function editStatusPagamento($payment_id, $status) : bool{
		return Database::EXECUTE_NON_QUERY('CALL edit_status_pagamento(:pay_id, :status)', [':pay_id' => $payment_id, ':status' => $status]);
	}

	// MÉTODO QUE RETORNA OS DADOS DE UM PAGAMENTO

	public function buscaPagamento($payment_id) : ?object{
		$dados = Database::EXECUTE_QUERY('SELECT * FROM pagamentos WHERE payment_id = :pay_id LIMIT 1', [':pay_id' => $payment_id]);

		if(empty($dados)) return null;

		return $dados[0];
	}
}