<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title><?= APP_NAME . ' - ' . $titulo ?></title>
	<meta charset="utf-8">
	<?php $red = (isset($painel) ? '../' : ''); ?>
	<link rel="shortcut icon" type="image/png" href="<?= $red ?>../public/assets/IMGS/<?= LOGO ?>" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/main_config.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/brands.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/regular.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/solid.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/svg-with-js.min.css">
	<link rel="stylesheet" type="text/css" href="<?= $red ?>../public/assets/CSS/fontawesome/css/v4-shims.min.css">
</head>
<body>

<section id="loading">
	<div></div>
</section>