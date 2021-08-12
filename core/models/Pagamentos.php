<?php

namespace core\models;

use core\classes\Store;
use core\classes\Database;

class Pagamentos{
	// MÉTODO QUE ADICIONA UM PAGAMENTO A UMA RESERVA

	public function addPagamento($valor, $status, int $id_res) : bool{
		return Database::EXECUTE_QUERY('CALL add_pagamento(:valor, :status, :id)', [':valor' => $valor, ':status' => $status, ':id' => $id_res]);
	}

	// MÉTODO QUE EDITA O STATUS DE UM PAGAMENTO

	public function editStatusPagamento($id, $status) : bool{
		return Database::EXECUTE_QUERY('CALL edit_status_pagamento(:id, :status)', [':id' => $id, ':status' => $status]);
	}
}