<?php

namespace core\controllers;

use core\classes\Store;
use core\models\Quartos;
use core\models\Usuarios;
use core\models\Reservas;
use core\models\Notificacoes;

class PainelController{
	public function __construct(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;
	}

	public function inicio(){
		// Carregando os dados da página

		$quarto = new Quartos();
		$dadosUser = Store::dadosUsuarioLogado();
		$notificacao = new Notificacoes();

		$dados = [
			'titulo' => 'Início',
			'totais' => Store::totais(), // Buscando o total de dados em cada tabela
			'tipos_quarto' => $quarto->buscaTiposQuarto(),
			'dadosUser' => $dadosUser,
			'totalNotification' => $notificacao::totalNotificacoes($dadosUser['CPF'])
		];

		Store::layout([
			'PAINEL/layout/html_header',
			'PAINEL/layout/header',
			'PAINEL/inicio',
			'PAINEL/layout/html_footer'
		], $dados, PAINEL);
	}

	public function busca_dados_grafico(){
		$usuario = new Usuarios();
		$reserva = new Reservas();

		echo json_encode(array('USUARIOS' => $usuario->totalUsuariosAcesso(), 'RESERVAS' => $reserva->totalReservasStatus()));
	}

	public function logout(){
		Store::fecharSessao();
		Store::redirect(['a' => 'inicio']);
	}
}