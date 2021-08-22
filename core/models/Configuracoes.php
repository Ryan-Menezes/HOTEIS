<?php

namespace core\models;

use core\classes\Store;
use core\classes\Email;
use core\classes\Database;
use Exception;

class Configuracoes{
	// MÉTODO QUE RETORNA AS CONFIGURAÇÕES DO SISTEMA

	public function getConfig() : object{
		return json_decode(file_get_contents(URL . CONFIGJSON));
	}

	// MÉTODO ALTERAR O ARQUIVO DE CONFIGURAÇÕES DO SISTEMA

	public function setConfig(object $json) : bool{
		if(is_object($json)):
			try{
				$arq = fopen('../../' . CONFIGJSON, 'w');
				fwrite($arq, json_encode($json));
				fclose($arq);

				return true;
			}catch(Exception $e){
				return false;
			}
		endif;

		return false;
	}
}