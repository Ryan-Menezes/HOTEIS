<?php

namespace core\controllers;

use core\classes\Store;
use core\classes\Email;
use core\models\Reservas;
use core\models\Quartos;
use core\models\Usuarios;
use core\models\Notificacoes;

class ReservasController{
	private $reservas;
	private $usuarios;
	private $quartos;

	public function __construct(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$this->reservas = new Reservas();
		$this->usuarios = new Usuarios();
		$this->quartos = new Quartos();
	}

	public function reservas(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$notificacao = new Notificacoes();

		// Carregando os dados da página

		$dados = [
			'titulo' => 'Reservas',
			'dadosUser' => $dadosUser,
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/reservas',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}

	public function pesquisa_reservas(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT));
		$pesquisa = trim(addslashes(filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_NUMBER_INT)));
		$status = trim(addslashes(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)));
		$data_reserva = trim(addslashes(filter_input(INPUT_POST, 'data_reserva', FILTER_SANITIZE_SPECIAL_CHARS)));
		$data_encerrar = trim(addslashes(filter_input(INPUT_POST, 'data_encerrar', FILTER_SANITIZE_SPECIAL_CHARS)));

		if(!is_numeric($min)) $min = 0;
		if(empty($status)) $status = 'T';

		$data_reserva = str_ireplace('T', ' ', $data_reserva);
		$data_encerrar = str_ireplace('T', ' ', $data_encerrar);

		// Formatando uma apresentação e retornando-a

		$html = '';

		$reservas = $this->reservas->buscaReservas($pesquisa, $status, $data_reserva, $data_encerrar, $min);

		if(!empty($reservas)):
			foreach($reservas as $reserva):
				$dadosReserva = ['IDRESERVA' => $reserva->id_reserva, 'STATUS' => $reserva->status_reserva, 'NUMERO_QUARTO' => $reserva->numero_quarto, 'TIPO' => $reserva->nome_tipo, 'ANDAR' => $reserva->andar, 'PRECO' => number_format($reserva->preco_hora, 2, ',', '.'), 'DATARESERVA' => $reserva->data_reserva, 'DATAENCERRAMENTO' => $reserva->data_encerrar, 'DATARESERVAFORMAT' => date('d/m/Y H:i', strtotime($reserva->data_reserva)), 'DATAENCERRAMENTOFORMAT' => date('d/m/Y H:i', strtotime($reserva->data_encerrar)), 'NOMECLIENTE' => $reserva->nome, 'SOBRENOMECLIENTE' => $reserva->sobrenome, 'CPFCLIENTE' => $reserva->cpf, 'CPFCLIENTEMASK' => Store::mask('###.###.###-##', $reserva->cpf), 'EMAILCLIENTE' => $reserva->email, 'IMGCLIENTE' => base64_encode($reserva->img_perfil)];

				$html .= '<div class="cardQuarto" id="card' . $dadosReserva['IDRESERVA'] . '">
							<div class="headerCardQuarto">
								<p>Quarto - ' . $dadosReserva['NUMERO_QUARTO'] . '</p>
								<div>
									<button class="bg-azul" onclick=\'editarReserva(`' . json_encode($dadosReserva) . '`)\'><i class="fas fa-pencil-alt"></i></button>
								</div>
							</div>
							<div class="conteudoCardQuarto">
								<p><span class="' . (($dadosReserva['STATUS'] == 'R') ? 'bg-vermelho' : (($dadosReserva['STATUS'] == 'P') ? 'bg-amarelo' : 'bg-verde')) . '">' . (($dadosReserva['STATUS'] == 'R') ? 'RESERVADO' : (($dadosReserva['STATUS'] == 'P') ? 'PAGAMENTO' : 'CONCLUIDO')) . '</span></p><br>
								<p><span>Tipo: </span>' . $dadosReserva['TIPO'] . '</p>
								<p><span>Andar: </span>' . $dadosReserva['ANDAR'] . '</p>
								<p><span>Valor por hora: </span>R$' . $dadosReserva['PRECO'] . '</p><br>
								<p><span>Data da reserva: </span>' . $dadosReserva['DATARESERVAFORMAT'] . '</p>
								<p><span>Data de encerramento: </span>' . $dadosReserva['DATAENCERRAMENTOFORMAT'] . '</p><br>
								<p><span>Cliente: </span>' . $dadosReserva['NOMECLIENTE'] . '</p>
								<p><span>CPF: </span>' . $dadosReserva['CPFCLIENTEMASK'] . '</p>
							</div>
							<div class="footerCardQuarto">
								<button onclick=\'visualizarReserva(`' . json_encode($dadosReserva) . '`)\'>Mais Informações <i class="fas fa-info-circle"></i></button>
							</div>
						</div>';
			endforeach;

			if(count($reservas) >= 10):
				$html .= '<form method="POST" action="?a=pesquisa_reservas" class="formularioCarrega" onsubmit="executaFormCarrega(carregaSolicitacoesReserva, loadingCarregaSolicitacoesReserva, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<input type="hidden" name="data_encerramento" value="' . $data_encerrar . '">
							<input type="hidden" name="data_reserva" value="' . $data_reserva . '">
							<input type="hidden" name="status" value="' . $status . '">
							<input type="hidden" name="pesquisa" value="' . $pesquisa . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
			endif;
		else:
			if($min == 0) $html = '<h5 style="grid-column: 1/5">Não há nenhuma reserva neste hotel!</h5>';
		endif;

		echo $html;
	}

	public function editar_reserva(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível editar esta reserva, ';
		$camposErrados = array();

		$id_reserva = trim(addslashes(filter_input(INPUT_POST, 'id_reserva', FILTER_SANITIZE_NUMBER_INT)));
		$numero_quarto = trim(addslashes(filter_input(INPUT_POST, 'numero_quarto', FILTER_SANITIZE_NUMBER_INT)));
		$cpf_usuario = trim(addslashes(filter_input(INPUT_POST, 'cpf_usuario', FILTER_SANITIZE_NUMBER_INT)));
		$data_reserva = trim(addslashes(filter_input(INPUT_POST, 'data_reserva', FILTER_SANITIZE_SPECIAL_CHARS)));
		$data_encerrar = trim(addslashes(filter_input(INPUT_POST, 'data_encerrar', FILTER_SANITIZE_SPECIAL_CHARS)));
		$status = trim(addslashes(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)));

		// Validando todos os campos

		if(empty($cpf_usuario) || !is_numeric($cpf_usuario) || strlen($cpf_usuario) != 11) array_push($camposErrados, 'CPF DO USUÁRIO');
		if(empty($numero_quarto) || !is_numeric($numero_quarto)) array_push($camposErrados, 'NÚMERO DO QUARTO');
		if(empty($data_reserva)) array_push($camposErrados, 'DATA DA RESERVA');
		if(empty($data_encerrar)) array_push($camposErrados, 'DATA DE ENCERRAMENTO');
		if(empty($status)) array_push($camposErrados, 'STATUS DA RESERVA');

		if(!empty($camposErrados)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Os seguintes campos estão incorretos: ' . implode(', ', $camposErrados)]);
			return;
		endif;

		// Buscando os dados do quarto passado e validando-o

		$q = $this->quartos->buscaQuarto($numero_quarto);

		if(!is_null($q) && is_object($q)):
			// Validando CPF do usuário

			if($this->usuarios->CPFExiste($cpf_usuario)):
				// Validando as datas

				if(strtotime($data_encerrar) >= strtotime($data_reserva)):
					if($this->reservas->editaReserva($id_reserva, $numero_quarto, $q->preco_hora, $data_reserva, $data_encerrar, $status, $cpf_usuario)):
						echo json_encode(['RES' => true, 'MSG' => 'Reserva editada com sucessso!']);
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar editar esta reserva!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'A data e a hora de encerramento deve ser superior a data e a hora da reserva!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Não existe nenhum usuário cadastrado com esse CPF!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'O quarto selecionado NÃO existe em nosso hotel!']);
		endif;
	}

	public function solicita_reserva(){
		// Verificando se o usuário logado é um cliente

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'C'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível enviar sua solicitação, ';

		$numero_quarto = trim(addslashes(filter_input(INPUT_POST, 'numero_quarto', FILTER_SANITIZE_NUMBER_INT)));
		$data_reserva = trim(addslashes(filter_input(INPUT_POST, 'data_reserva', FILTER_SANITIZE_SPECIAL_CHARS)));
		$data_encerrar = trim(addslashes(filter_input(INPUT_POST, 'data_encerrar', FILTER_SANITIZE_SPECIAL_CHARS)));

		// Verificando se o número do quarto é válido

		if(!is_numeric($numero_quarto) || empty($numero_quarto)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar enviar sua solicitação!']);
			return;
		endif;

		// Verificando se o usuário já fez uma solicitação para este quarto

		if($this->reservas->solicitaReservaExiste($numero_quarto, $dadosUser['CPF'])):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Você já fez uma solicitação para este quarto!']);
			return;
		endif;

		// Buscando os dados do quarto passado e validando-o

		$q = $this->quartos->buscaQuarto($numero_quarto);

		// Validando a data da reserva

		if(strtotime(date('Y-m-d H:i:s')) >= strtotime($data_reserva)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'A data e o horário da reserva deve ser superior a data e o horário de hoje!']);
			return;
		endif;

		if(!is_null($q) && is_object($q)):
			// Validando as datas

			if(!empty($data_reserva) && !empty($data_encerrar)):
				if(strtotime($data_encerrar) >= strtotime($data_reserva)):
					if($this->reservas->solicitaReserva($numero_quarto, $q->preco_hora, $data_reserva, $data_encerrar, $dadosUser['CPF'])):
						echo json_encode(['RES' => true, 'MSG' => 'Solicitação feita com sucesso, Iremos analisar sua solicitação e lhe enviar uma resposta por aqui, por dentro de alguns dias!']);
					else:
						echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar enviar sua solicitação!']);
					endif;
				else:
					echo json_encode(['RES' => false, 'MSG' => $msg . 'A data e a hora de encerramento deve ser superior a data e a hora da reserva!']);
				endif;
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Por favor preencha as datas corretamente!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'O quarto selecionado NÃO existe em nosso hotel!']);
		endif;
	}

	public function reservas_solicitadas(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$notificacao = new Notificacoes();

		// Carregando os dados da página

		$dados = [
			'titulo' => 'Reservas Solicitadas',
			'dadosUser' => $dadosUser,
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/reservas_solicitadas',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}

	public function minhas_reservas(){
		// Verificando se o usuário logado é um cliente

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'C'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$notificacao = new Notificacoes();

		// Carregando os dados da página

		$dados = [
			'titulo' => 'Minhas Reservas',
			'dadosUser' => $dadosUser,
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/minhas_reservas',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}

	public function minhas_reservas_usuario(){
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

		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT));

		if(!is_numeric($min)) $min = 0;

		// Formatando uma apresentação e retornando-a

		$html = '';

		$reservas = $this->reservas->buscaReservasUsuario($dadosUser['CPF'], $min);

		if(!empty($reservas)):
			foreach($reservas as $reserva):
				$dadosReserva = ['IDRESERVA' => $reserva->id_reserva, 'STATUS' => $reserva->status_reserva, 'NUMERO_QUARTO' => $reserva->numero_quarto, 'TIPO' => $reserva->nome_tipo, 'ANDAR' => $reserva->andar, 'PRECO' => number_format($reserva->preco_hora, 2, ',', '.'), 'DATARESERVA' => $reserva->data_reserva, 'DATAENCERRAMENTO' => $reserva->data_encerrar, 'DATARESERVAFORMAT' => date('d/m/Y H:i', strtotime($reserva->data_reserva)), 'DATAENCERRAMENTOFORMAT' => date('d/m/Y H:i', strtotime($reserva->data_encerrar)), 'NOMECLIENTE' => $dadosUser['NOME'], 'SOBRENOMECLIENTE' => $dadosUser['SOBRENOME'], 'CPFCLIENTE' => $dadosUser['CPF'], 'CPFCLIENTEMASK' => Store::mask('###.###.###-##', $dadosUser['CPF']), 'EMAILCLIENTE' => $dadosUser['EMAIL'], 'IMGCLIENTE' => base64_encode($dadosUser['IMG'])];

				$html .= '<div class="cardQuarto" id="card' . $dadosReserva['IDRESERVA'] . '">
							<div class="headerCardQuarto">
								<p>Quarto - ' . $dadosReserva['NUMERO_QUARTO'] . '</p>
							</div>
							<div class="conteudoCardQuarto">
								<p><span class="' . (($dadosReserva['STATUS'] == 'R') ? 'bg-vermelho' : (($dadosReserva['STATUS'] == 'P') ? 'bg-amarelo' : 'bg-verde')) . '">' . (($dadosReserva['STATUS'] == 'R') ? 'RESERVADO' : (($dadosReserva['STATUS'] == 'P') ? 'PAGAMENTO' : 'CONCLUIDO')) . '</span></p><br>
								<p><span>Tipo: </span>' . $dadosReserva['TIPO'] . '</p>
								<p><span>Andar: </span>' . $dadosReserva['ANDAR'] . '</p>
								<p><span>Valor por hora: </span>R$' . $dadosReserva['PRECO'] . '</p><br>
								<p><span>Data da reserva: </span>' . $dadosReserva['DATARESERVAFORMAT'] . '</p>
								<p><span>Data de encerramento: </span>' . $dadosReserva['DATAENCERRAMENTOFORMAT'] . '</p><br>
							</div>
							<div class="footerCardQuarto">
								<button onclick=\'visualizarReserva(`' . json_encode($dadosReserva) . '`)\'>Mais Informações <i class="fas fa-info-circle"></i></button>
							</div>
						</div>';
			endforeach;

			if(count($reservas) >= 10):
				$html .= '<form method="POST" action="?a=minhas_reservas_usuario" class="formularioCarrega" onsubmit="executaFormCarrega(carregaReservas, loadingCarregaReservas, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
			endif;
		else:
			if($min == 0) $html = '<h5 style="grid-column: 1/5">Não há nenhuma reserva que você tenha feito em nosso hotel!</h5>';
		endif;

		echo $html;
	}

	public function meus_pedidos_reserva(){
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

		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT));

		if(!is_numeric($min)) $min = 0;

		// Formatando uma apresentação e retornando-a

		$html = '';

		$pedidos = $this->reservas->buscaSolicitacoesUsuario($dadosUser['CPF'], $min);

		if(!empty($pedidos)):
			foreach($pedidos as $pedido):
				$btn = ($pedido->status[0] == 'P') ? '<button type="submit" onclick="cancelarSolicitcao(' . $pedido->id_pedido_reserva . ')">Cancelar solicitação <i class="fas fa-ban"></i></button>' : '<button type="submit" title="Deletar" onclick="deletaSolicitacao(' . $pedido->id_pedido_reserva . ')"><i class="fas fa-trash-alt"></i></button>';

				$html .= '<div class="cardQuarto" id="card' . $pedido->id_pedido_reserva . '">
							<div class="headerCardQuarto">Quarto - ' . $pedido->numero_quarto . '</div>
							<div class="conteudoCardQuarto">
								<p><span>Tipo: </span>' . $pedido->nome_tipo . '</p>
								<p><span>Andar: </span>' . $pedido->andar . '</p>
								<p><span>Valor por hora: </span>R$' . number_format($pedido->preco_hora, 2, ',', '.') . '</p>
								<p><span>Data da reserva: </span>' . date('d/m/Y H:i', strtotime($pedido->data_reserva)) . '</p>
								<p><span>Data de encerramento: </span>' . date('d/m/Y H:i', strtotime($pedido->data_encerrar)) . '</p><br>
								<p><span class="' . (($pedido->status[0] == 'P') ? 'bg-amarelo' : (($pedido->status[0] == 'A') ? 'bg-verde' : 'bg-vermelho')) . '">' . $pedido->status . '</span></p>
							</div>
							<div class="footerCardQuarto">
								' . $btn . '
							</div>
						</div>';
			endforeach;

			if(count($pedidos) >= 10):
				$html .= '<form method="POST" action="?a=meus_pedidos_reserva" class="formularioCarrega" onsubmit="executaFormCarrega(carregaSolicitacoesReservas, loadingCarregaSolicitacoesReservas, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
			endif;
		else:
			if($min == 0) $html = '<h5 style="grid-column: 1/5">Você ainda não fez nehuma solicitação para uma reserva!</h5>';
		endif;

		echo $html;
	}

	public function cancela_solicitacao_reserva(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'C'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível cancelar essa solicitação, ';

		// Validando os dados do POST

		$id_pedido_reserva = trim(addslashes(filter_input(INPUT_POST, 'id_pedido_reserva', FILTER_SANITIZE_NUMBER_INT)));

		// Verificando se o id_pedido_reserva é válido

		if(!is_numeric($id_pedido_reserva) || empty($id_pedido_reserva)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao cancelar essa solicitação!']);
			return;
		endif;

		// Cancelando a solicitação e emitindo uma mensagem

		if($this->reservas->solicitacaoReservaUsuarioExiste($id_pedido_reserva, $dadosUser['CPF'])):
			if($this->reservas->cancelaPedidoReserva($id_pedido_reserva)):
				echo json_encode(['RES' => true, 'MSG' => 'Solicitação cancelada com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao cancelar essa solicitação!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao cancelar essa solicitação!']);
		endif;
	}

	public function pesquisa_solicitacoes_reservas(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requsição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$min = trim(filter_input(INPUT_POST, 'min', FILTER_SANITIZE_NUMBER_INT));

		if(!is_numeric($min)) $min = 0;

		// Formatando uma apresentação e retornando-a

		$html = '';

		$pedidos = $this->reservas->buscaSolicitacoesReservas($min);

		if(!empty($pedidos)):
			foreach($pedidos as $pedido):
				$dadosPedido = ['IDPEDIDO' => $pedido->id_pedido_reserva, 'NUMERO_QUARTO' => $pedido->numero_quarto, 'TIPO' => $pedido->nome_tipo, 'ANDAR' => $pedido->andar, 'PRECO' => number_format($pedido->preco_hora, 2, ',', '.'), 'DATARESERVA' => date('d/m/Y H:i', strtotime($pedido->data_reserva)), 'DATAENCERRAMENTO' => date('d/m/Y H:i', strtotime($pedido->data_encerrar)), 'NOMECLIENTE' => $pedido->nome_completo, 'CPFCLIENTE' => $pedido->cpf, 'CPFCLIENTEMASK' => Store::mask('###.###.###-##', $pedido->cpf), 'EMAILCLIENTE' => $pedido->email, 'IMGCLIENTE' => base64_encode($pedido->img_perfil)];

				$html .= '<div class="cardQuarto" id="card' . $dadosPedido['IDPEDIDO'] . '">
							<div class="headerCardQuarto">Quarto - ' . $dadosPedido['NUMERO_QUARTO'] . '</div>
							<div class="conteudoCardQuarto">
								<p><span>Tipo: </span>' . $dadosPedido['TIPO'] . '</p>
								<p><span>Andar: </span>' . $dadosPedido['ANDAR'] . '</p>
								<p><span>Valor por hora: </span>R$' . $dadosPedido['PRECO'] . '</p>
								<p><span>Data da reserva: </span>' . $dadosPedido['DATARESERVA'] . '</p>
								<p><span>Data de encerramento: </span>' . $dadosPedido['DATAENCERRAMENTO'] . '</p><br>
								<p><span>Cliente: </span>' . $dadosPedido['NOMECLIENTE'] . '</p>
								<p><span>CPF: </span>' . $dadosPedido['CPFCLIENTE'] . '</p>
							</div>
							<div class="footerCardQuarto">
								<button onclick=\'visualizarPedido(`' . json_encode($dadosPedido) . '`)\'>Mais Informações <i class="fas fa-info-circle"></i></button>
							</div>
						</div>';
			endforeach;

			if(count($pedidos) >= 10):
				$html .= '<form method="POST" action="?a=pesquisa_solicitacoes_reservas" class="formularioCarrega" onsubmit="executaFormCarrega(carregaSolicitacoesReserva, loadingCarregaSolicitacoesReserva, null)">
							<input type="hidden" name="min" value="' . ($min + 10) . '">
							<button type="submit"><i class="fas fa-plus"></i></button>
						</form>';
			endif;
		else:
			if($min == 0) $html = '<h5 style="grid-column: 1/5">Não há nenhuma solicitação de reserva feita!</h5>';
		endif;

		echo $html;
	}

	public function finaliza_pedido_reserva(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$id_pedido_reserva = trim(addslashes(filter_input(INPUT_POST, 'id_pedido_reserva', FILTER_SANITIZE_NUMBER_INT)));
		$status = trim(addslashes(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)));

		// Validando o status

		if(!empty($status)):
			$evento = ($status == 'A') ? 'aceitar' : 'recusar';
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar fazer a reserva!']);
			return;
		endif;

		$msg = 'Não foi possível ' . $evento . ' esta reserva, ';

		// Verificando se o id_pedido_reserva é válido

		if(!is_numeric($id_pedido_reserva) || empty($id_pedido_reserva)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar ' . $evento . ' esta reserva!']);
			return;
		endif;

		// Finalizando o pedido da reserva

		if($this->reservas->finalizaPedidoReserva($id_pedido_reserva, $status)):
			echo json_encode(['RES' => true, 'MSG' => 'Reserva ' . (($status == 'A') ? 'aceita' : 'recusada') . ' com sucesso!']);
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar ' . $evento . ' esta reserva!']);
		endif;
	}

	public function finaliza_reserva(){
		// Verificando se o usuário logado é um cliente

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'C'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$msg = 'Não foi possível finalizar esta reserva, ';

		// Validando os dados do POST

		$id_reserva = trim(addslashes(filter_input(INPUT_POST, 'id_reserva', FILTER_SANITIZE_NUMBER_INT)));		

		// Verificando se o id_pedido_reserva é válido

		if(!is_numeric($id_reserva) || empty($id_reserva)):
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar finalizar esta reserva!']);
			return;
		endif;

		// Finalizando o pedido da reserva

		if($this->reservas->reservaUsuarioExiste($id_reserva, $dadosUser['CPF'])):
			if($this->reservas->finalizaReserva($id_reserva)):
				echo json_encode(['RES' => true, 'MSG' => 'Reserva finalizada com sucesso!']);
			else:
				echo json_encode(['RES' => false, 'MSG' => $msg . 'Ocorreu um erro ao tentar finalizar esta reserva!']);
			endif;
		else:
			echo json_encode(['RES' => false, 'MSG' => $msg . 'Esta reserva não pertence a este usuário!']);
		endif;
	}

	public function total_reservas_tempo(){
		// Verificando se o usuário logado é um administrador

		$dadosUser = Store::dadosUsuarioLogado();

		if($dadosUser['ACESSO'] !== 'A'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Validando os dados do POST

		$tipo = trim(addslashes(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT)));
		
		$metodos = ['totalReservasSemana', 'totalReservasMeses', 'totalReservasAnos'];

		if(is_numeric($tipo) && array_key_exists($tipo, $metodos)):
			$metodo = $metodos[$tipo];

			$dados = $this->reservas->$metodo();

			echo json_encode(['KEYS' => array_keys($dados), 'VALUES' => array_values($dados)]);
		else:
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;
	}
}