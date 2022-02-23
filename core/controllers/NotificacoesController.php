<?php

namespace core\controllers;

use core\classes\Store;
use core\classes\Email;
use core\models\Notificacoes;

class NotificacoesController{
	private $notificacoes;

	public function __construct(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$this->notificacoes = new Notificacoes();
	}

	public function notificacoes(){
		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$html = '';

		if(!is_numeric($min)) $min = 0;

		$dados = Store::dadosUsuarioLogado();

		$notificacoes = $this->notificacoes->buscaNotificacoes($dados['CPF'], $min);

		if(!empty($notificacoes)):
			foreach($notificacoes as $notificacao):
				$dados = ['ID' => $notificacao->id_notificacao, 'TITULO' => $notificacao->titulo, 'MENSAGEM' => $notificacao->mensagem, 'DATA' => date('d/m/Y H:i:s', strtotime($notificacao->created_at))];

				$html .= '<li onclick=\'abreNotificacao(`' . json_encode($dados) . '`)\'>
							<h4>' . $dados['TITULO'] . '</h4>
							<p>' . substr($dados['MENSAGEM'], 0, 50)  . '...</p>
							<span>' . $dados['DATA'] . '</span>
						  </li>';
			endforeach;

			if(count($notificacoes) >= 10):
				$html .= '<form method="POST" action="?a=notificacoes" id="formCarregaNotificacao" class="formularioCarrega" onsubmit="executaFormCarrega(carregaNotificacoes, loadingNotificacoes, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						  </form>';
			endif;
		elseif($min == 0):
			$html = '<li style="border: none; margin: 0;"><h4 style="margin: 0;">Sua caixa de mensagem está vázia!</h4><li>';
		endif;

		echo $html;
	}

	public function visualiza_notificacao(){
		$dadosUsuario = Store::dadosUsuarioLogado();

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$id_not = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) ?? '');
		
		if($this->notificacoes->existeNotificacao($dadosUsuario['CPF'], $id_not) && !$this->notificacoes->notificacaoVisualizada($id_not)):
			echo (int)$this->notificacoes->visualizaNotificacao($dadosUsuario['CPF'], $id_not);
		else:
			echo 0;
		endif;
	}

	public function deleta_notificacao(){
		$dadosUsuario = Store::dadosUsuarioLogado();

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível deletar esta mensagem, ';

		// Validando os dados do POST

		$id_not = trim(filter_input(INPUT_POST, 'id_not', FILTER_SANITIZE_NUMBER_INT) ?? '');
		
		if($this->notificacoes->existeNotificacao($dadosUsuario['CPF'], $id_not)):
			if($this->notificacoes->deletaNotificacao($dadosUsuario['CPF'], $id_not)):
				echo json_encode(['RES' => true, 'MSG' => 'Mensagem deletada com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de exclusão!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro no processo de exclusão!']);
		endif;
	}
}