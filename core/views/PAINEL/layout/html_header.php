<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title><?= APP_NAME . ' - ' . $titulo ?></title>
	<meta charset="utf-8">
	<link rel="shortcut icon" type="image/png" href="../../public/assets/IMGS/<?= LOGO ?>" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/painel_config.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/brands.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/regular.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/solid.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/svg-with-js.min.css">
	<link rel="stylesheet" type="text/css" href="../../public/assets/CSS/fontawesome/css/v4-shims.min.css">
</head>
<body>

<section id="loading">
	<div></div>
</section>

<section class="modal" id="modalNotificacao">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Mensagem</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=deleta_notificacao" id="formDeletaNotificacao">
			<h5 class="titulo"></h5>

			<input type="hidden" class="id_not" name="id_not">

			<textarea class="mensagem" placeholder="Mensagem" readonly></textarea>
			<div class="contBtnModal">
				<button type="submit"><i class="fas fa-trash-alt"></i></button>
				<h6><i class="fas fa-clock"></i> <span class="data">12/12/2012</span></h6>
			</div>
		</form>
	</main>
</section>

<section id="principal">