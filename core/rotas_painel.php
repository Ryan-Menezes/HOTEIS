<?php

define('ROTAS', [
	'inicio' 							=> 'painel@inicio',
	'busca_dados_grafico' 				=> 'painel@busca_dados_grafico',

	// Quartos

	'quartos' 							=> 'quartos@quartos',
	'quartos_disponiveis' 				=> 'quartos@quartos_disponiveis',
	'pesquisa_quartos' 					=> 'quartos@pesquisa_quartos',
	'cadastra_quarto' 					=> 'quartos@cadastra_quarto',
	'editar_quarto' 					=> 'quartos@editar_quarto',
	'deleta_quarto' 					=> 'quartos@deleta_quarto',

	// Usuários

	'usuarios' 							=> 'usuarios@usuarios',
	'pesquisa_usuarios' 				=> 'usuarios@pesquisa_usuarios',
	'editar_usuario' 					=> 'usuarios@editar_usuario',
	'deleta_usuario' 					=> 'usuarios@deleta_usuario',
	'recupera_usuario' 					=> 'usuarios@recupera_usuario',
	'editar_dados_pessoais' 			=> 'usuarios@editar_dados_pessoais',
	'alterar_senha' 					=> 'usuarios@alterar_senha',
	'deletar_conta' 					=> 'usuarios@deletar_conta',
	'alterar_imagem_perfil' 			=> 'usuarios@alterar_imagem_perfil',

	// Reservas

	'reservas' 							=> 'reservas@reservas',
	'reservas_solicitadas' 				=> 'reservas@reservas_solicitadas',
	'minhas_reservas' 					=> 'reservas@minhas_reservas',
	'solicita_reserva' 					=> 'reservas@solicita_reserva',
	'meus_pedidos_reserva' 				=> 'reservas@meus_pedidos_reserva',
	'minhas_reservas_usuario' 			=> 'reservas@minhas_reservas_usuario',
	'cancela_solicitacao_reserva' 		=> 'reservas@cancela_solicitacao_reserva',
	'pesquisa_solicitacoes_reservas' 	=> 'reservas@pesquisa_solicitacoes_reservas',
	'finaliza_pedido_reserva' 			=> 'reservas@finaliza_pedido_reserva',
	'pesquisa_reservas' 				=> 'reservas@pesquisa_reservas',
	'editar_reserva' 					=> 'reservas@editar_reserva',
	'finaliza_reserva' 					=> 'reservas@finaliza_reserva',
	'total_reservas_tempo' 				=> 'reservas@total_reservas_tempo',

	// Configurações

	'configuracoes' 					=> 'configuracoes@configuracoes',
	'alterar_redes_sociais' 			=> 'configuracoes@alterar_redes_sociais',
	'alterar_nome_sistema' 				=> 'configuracoes@alterar_nome_sistema',
	'alterar_logo_sistema' 				=> 'configuracoes@alterar_logo_sistema',
	'alterar_imgs_destaque_sistema' 	=> 'configuracoes@alterar_imgs_destaque_sistema',
	'alterar_carrossel_sistema' 		=> 'configuracoes@alterar_carrossel_sistema',
	'alterar_endereco' 					=> 'configuracoes@alterar_endereco',
	'alterar_contato'					=> 'configuracoes@alterar_contato',
	'get_payment_config' 				=> 'configuracoes@get_payment_config',

	// Notificações

	'notificacoes' 						=> 'notificacoes@notificacoes',
	'deleta_notificacao' 				=> 'notificacoes@deleta_notificacao',
	'visualiza_notificacao' 			=> 'notificacoes@visualiza_notificacao',

	// Payment

	'payment_invoice' 					=> 'payment@payment_invoice',
	'payment_execute' 					=> 'payment@payment_execute',
	'payment_notification_receive' 		=> 'payment@payment_notification_receive',

	// Sistema

	'logout'							=> 'painel@logout'
]);

$acao = trim(filter_input(INPUT_GET, 'a', FILTER_SANITIZE_SPECIAL_CHARS));

if(!array_key_exists($acao, ROTAS)) $acao = 'inicio';

$partes = explode('@', ROTAS[$acao]);

$contolador = 'core\\controllers\\' . ucfirst($partes[0]) . 'Controller';
$metodo = $partes[1];

$contolador = new $contolador();
$contolador->$metodo();