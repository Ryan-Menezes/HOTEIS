<?php

// Inicializando sessão

ini_set('session.cookie_httponly', true);
ini_set('session.use_only_cookies', true);
ini_set('session.cookie_secure', true);

if(!isset($_SESSION)) session_start();
if(!isset($_SESSION['last_IP'])) $_SESSION['last_IP'] = $_SERVER['REMOTE_ADDR'];
if($_SESSION['last_IP'] !== $_SERVER['REMOTE_ADDR']) session_destroy();

require_once '../vendor/autoload.php';

error_reporting(ERROR_REPORTING);
date_default_timezone_set(TIMEZONE);

require_once '../core/rotas.php';