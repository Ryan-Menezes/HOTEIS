<?php

define('ROTAS', [
	'inicio' 					=> 'main@inicio',
	'cadastro' 					=> 'usuarios@cadastro',
	'contato' 					=> 'main@contato',
	'validar_conta' 			=> 'usuarios@validar_conta',
	'login' 					=> 'usuarios@login',
	'recupera_senha' 			=> 'usuarios@recupera_senha',
	'envia_recupera_senha' 		=> 'usuarios@envia_recupera_senha',
	'alterar_senha_recupera' 	=> 'usuarios@alterar_senha_recupera'
]);

$acao = trim(filter_input(INPUT_GET, 'a', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

if(!array_key_exists($acao, ROTAS)) $acao = 'inicio';

$partes = explode('@', ROTAS[$acao]);

$contolador = 'core\\controllers\\' . ucfirst($partes[0]) . 'Controller';
$metodo = $partes[1];

$contolador = new $contolador();
$contolador->$metodo();