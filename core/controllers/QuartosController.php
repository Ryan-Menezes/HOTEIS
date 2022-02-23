<?php

namespace core\controllers;

use core\classes\Store;
use core\classes\Email;
use core\models\Quartos;
use core\models\Notificacoes;

class QuartosController{
	private $quartos;

	public function __construct(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$this->quartos = new Quartos();
	}

	public function quartos(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$notificacao = new Notificacoes();

		// Carregando os dados da página

		$dados = [
			'titulo' => 'Quartos',
			'tipos_quarto' => $this->quartos->buscaTiposQuarto(),
			'dadosUser' => $dadosUser,
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/quartos',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}
	
	public function quartos_disponiveis(){
		// Verificando se o usuário logado é um cliente

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'C'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS));
		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT));

		if(empty($tipo)) $tipo = 'T';
		if(!is_numeric($min)) $min = 0;

		// Formatando uma apresentação e retornando-a

		$html = '';

		$quartos = $this->quartos->buscaQuartosDisponiveis($dadosUser['CPF'], $tipo, $min);

		if(!empty($quartos)):
			foreach($quartos as $quarto):
				$html .= '<div class="cardQuarto">
							<div class="headerCardQuarto">Quarto - ' . $quarto->numero_quarto . '</div>
							<div class="conteudoCardQuarto">
								<p><span>Tipo: </span>' . $quarto->nome_tipo . '</p>
								<p><span>Andar: </span>' . $quarto->andar . '</p>
								<p><span>Preço por hora: </span>R$' . number_format($quarto->preco_hora, 2, ',', '.') . '</p>
							</div>
							<div class="footerCardQuarto">
								<button onclick=\'solicitarReserva(`' . json_encode(['NUM' => $quarto->numero_quarto, 'TIPO' => $quarto->nome_tipo, 'ANDAR' => $quarto->andar, 'PRECO' => $quarto->preco_hora]) . '`)\'>Solicitar Reserva</button>	
							</div>
						</div>';
			endforeach;

			if(count($quartos) >= 10):
				$html .= '<form method="POST" action="?a=quartos_disponiveis" class="formularioCarrega" onsubmit="executaFormCarrega(carregaMinhasReservas, loadingCarregaMinhasReservas, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<input type="hidden" name="tipo" value="' . $tipo . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
			endif;
		else:
			if($min == 0) $html = '<h5 style="grid-column: 1/5">Infelizmente NÃO há quartos disponíveis para reserva!</h5>';
		endif;

		echo $html;
	}

	public function pesquisa_quartos(){
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

		$tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$numero = trim(filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT) ?? '');

		if(empty($tipo)) $tipo = 'T';
		if(empty($status)) $status = 'T';
		if(!is_numeric($min)) $min = 0;

		// Formatando uma apresentação e retornando-a

		$html = '';

		$quartos = $this->quartos->buscaQuartos($numero, $status, $tipo, $min);

		if(!empty($quartos)):
			foreach($quartos as $quarto):
				$html .= '<div class="cardQuarto">
							<div class="headerCardQuarto">
								<p>Quarto - ' . $quarto->numero_quarto . '</p>

								<div>
									<button class="bg-vermelho" onclick="deletarQuartoModal(' . $quarto->numero_quarto . ')"><i class="fas fa-trash-alt"></i></button>
									<button class="bg-azul" onclick=\'editarQuarto(`' . json_encode(['NUM' => $quarto->numero_quarto, 'TIPO' => $quarto->id_tipo_quarto, 'ANDAR' => $quarto->andar, 'PRECO' => $quarto->preco_hora, 'STATUS' => $quarto->status_quarto]) . '`)\'><i class="fas fa-pencil-alt"></i></button>
								</div>	
							</div>
							<div class="conteudoCardQuarto">
								<p><span class="' . (($quarto->status_quarto == 'D') ? 'bg-verde' : 'bg-vermelho') . '">' . (($quarto->status_quarto == 'D') ? 'Disponível' : 'Indisponível') . '</span></p><br>
								<p><span>Tipo: </span>' . $quarto->nome_tipo . '</p>
								<p><span>Andar: </span>' . $quarto->andar . '</p>
								<p><span>Status: </span><' . (($quarto->status_quarto == 'D') ? 'Disponível' : 'Indisponível') . '</p>
								<p><span>Preço por hora: </span>R$' . number_format($quarto->preco_hora, 2, ',', '.') . '</p><br>
								<p><span>Criado em: </span>' . date('d/m/Y H:i:s', strtotime($quarto->created_at)) . '</p>
								<p><span>Última Atualização: </span>' . date('d/m/Y H:i:s', strtotime($quarto->updated_at)) . '</p>
							</div>
							<div class="footerCardQuarto"></div>
						</div>';
			endforeach;

			if(count($quartos) >= 10):
				$html .= '<form method="POST" action="?a=pesquisa_quartos" class="formularioCarrega" onsubmit="executaFormCarrega(carregaQuartos, loadingCarregaQuartoBusca, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<input type="hidden" name="tipo" value="' . $tipo . '">
							<input type="hidden" name="status" value="' . $status . '">
							<input type="hidden" name="pesquisa" value="' . $numero . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
			endif;
		else:
			if($min == 0):
				if(empty($numero)) $html = '<h5 style="grid-column: 1/5">Não há quartos cadastrados no sistema</h5>';
				else $html = '<h5 style="grid-column: 1/5">Não foi possível localizar um quarto com o número ' . $numero . '</h5>';
			endif;
		endif;

		echo $html;
	}

	public function cadastra_quarto(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$camposErrados = array();
		$msg = 'Não foi possível adicionar este quarto, ';

		// Buscando os dados passados pelo form

		$tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$numero = trim(filter_input(INPUT_POST, 'numero_quarto', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$andar = trim(filter_input(INPUT_POST, 'andar', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$preco = trim(filter_input(INPUT_POST, 'preco', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		// Validando os dados passados

		if(empty($tipo) || !is_numeric($tipo)) array_push($camposErrados, 'TIPO QUARTO');
		if(empty($numero) || !is_numeric($numero)) array_push($camposErrados, 'NÚMERO');
		if(empty($andar)) array_push($camposErrados, 'ANDAR');
		if(empty($preco) || !is_numeric($preco)) array_push($camposErrados, 'PREÇO POR HORA');

		// Validando todos os campos e finalizando o cadastro

		if(empty($camposErrados)):
			if(!$this->quartos->numeroExiste($numero)):
				if($this->quartos->cadastrarQuarto($numero, $andar, $preco, $tipo)):
					echo json_encode(['RES' => true, 'MSG' => 'Quarto cadastrado com sucesso!']);
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar cadastrar o quarto!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um quarto cadastrado com este número!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
		endif;
	}

	public function editar_quarto(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$camposErrados = array();
		$msg = 'Não foi possível salvar as edições deste quarto, ';

		// Buscando os dados passados pelo form

		$tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$numero_antigo = trim(filter_input(INPUT_POST, 'numero_antigo', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$numero_atualizado = trim(filter_input(INPUT_POST, 'numero_quarto', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$andar = trim(filter_input(INPUT_POST, 'andar', FILTER_SANITIZE_NUMBER_INT) ?? '');
		$preco = trim(filter_input(INPUT_POST, 'preco', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
		$status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

		// Validando o númro angigo do quarto

		if(empty($numero_antigo) || !is_numeric($numero_antigo)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar editar este quarto!']);
			return;
		endif;

		// Validando os dados passados

		if(empty($tipo) || !is_numeric($tipo)) array_push($camposErrados, 'TIPO QUARTO');
		if(empty($numero_antigo) || !is_numeric($numero_antigo)) array_push($camposErrados, 'NÚMERO');
		if(empty($andar)) array_push($camposErrados, 'ANDAR');
		if(empty($preco) || !is_numeric($preco)) array_push($camposErrados, 'PREÇO POR HORA');

		// Validando todos os campos e finalizando o cadastro

		if(empty($camposErrados)):
			if(!$this->quartos->numeroExiste($numero_atualizado) || $numero_antigo == $numero_atualizado):
				if($this->quartos->editarQuarto($numero_antigo, $numero_atualizado, $andar, $preco, $status, $tipo)):
					echo json_encode(['RES' => true, 'MSG' => 'Quarto editado com sucesso!']);
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar editar este quarto!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Já existe um quarto cadastrado com este número!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
		endif;
	}

	public function deleta_quarto(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível deletar este quarto, ';

		// Validando os dados do POST

		$numero_quarto = trim(filter_input(INPUT_POST, 'numero_quarto', FILTER_SANITIZE_NUMBER_INT) ?? '');

		// Verificando se o numero do quarto é válido

		if(!is_numeric($numero_quarto) || empty($numero_quarto)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao deletar este quarto!']);
			return;
		endif;

		// Verificando se o quarto tem está envolvido em alguma reserva NÃO concluida no hotel

		if(!$this->quartos->existeReservaQuarto($numero_quarto)):
			if($this->quartos->deletaQuarto($numero_quarto)):
				echo json_encode(['RES' => true, 'MSG' => 'Quarto deletado com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao deletar este quarto!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Este quarto está envolvido em algumas reservas NÃO concluidas em nosso hotel, Só será possível deletá-lo após a conclusão das mesmas!']);
		endif;
	}
}