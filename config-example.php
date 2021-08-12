<?php
// CONFIGURAÇÕES DO SITE

define('TIMEZONE', 'America/Sao_Paulo');								// TIMEZONE DA APLICAÇÃO
define('ERROR_REPORTING', E_ALL);										// APRESENTAÇÃO DE ERROS(PARA NÃO APRESENTAR ERROS NA TELA, BASTA COLOCAR UM 0)

// URLS

define('URL', 'http://exemple');										// URL DO SISTEMA
define('URL_HOME', URL . 'public/');									// CAMINHO DA PÁGINA HOME
define('URL_PAINEL', URL_HOME . 'PAINEL/');								// CAMINHO DA PÁGINA DE DASHBOARD DO SISTEMA

// CONFIGURAÇÕES DO SISTEMA

define('CONFIGJSON', 'config.json');									// ARQUIVO DE CONFIGURAÇÕES(.json) CONTENDO MAIS ALGUMAS INFORMAÇÕES DO SISTEMA

$CONFIG = json_decode(file_get_contents(URL . CONFIGJSON));

define('APP_NAME', $CONFIG->name);
define('VERSION', $CONFIG->version);
define('LOGO', $CONFIG->logo);

// CONFIGURAÇÕES DAS REDES SOCIAIS

define('FACEBOOK', $CONFIG->social->facebook);
define('INSTAGRAM', $CONFIG->social->instagram);
define('TWITTER', $CONFIG->social->twitter);

// CONFIGURAÇÕES DE ENDEREÇO E CONTATO

define('ADDRESS_STREET', $CONFIG->address->street);
define('ADDRESS_NUMBER', $CONFIG->address->number);
define('ADDRESS_CITY', $CONFIG->address->city);
define('ADDRESS_DISTRICT', $CONFIG->address->district);
define('ADDRESS_COUNTRY_CODE', $CONFIG->address->country_code);
define('ADDRESS_POSTAL_CODE', $CONFIG->address->postal_code);
define('ADDRESS_STATE', $CONFIG->address->state);
define('ADDRESS_PHONE', $CONFIG->address->phone);

// CONFIGURAÇÕES DO BANCO DE DADOS

define('DB_SERVER', 'localhost');
define('DB_USER', 'hoteis');
define('DB_PASSWORD', 'hoteis');
define('DB_DATABASE', 'hoteis');
define('DB_CHARSET', 'utf8');

// CONFIGURAÇÕES DO EMAIL

define('EMAIL_SERVER', 'smtp.gmail.com');
define('EMAIL_FROM', '');												// EMAIL ONDE IRÁ RECEBER E ENVIAR MENSAGENS
define('EMAIL_PASS', '');												// SENHA DO E-MAIL
define('EMAIL_PORT', '587');

// OPÇÕES DE ROTA

define('HOME', 1);
define('PAINEL', 2);

// IMAGEM PADRÃO DE PERFIL

define('IMG_PERFIL_DEFAULT', URL_HOME . 'assets/IMGS/anonimo.png');
define('MAX_FILE_SIZE', '1048576');

// CONFIGURAÇÕES DO CHECKOUT SEGURO DO PAYPAL

define('MODE', 'sandbox'); 												// MODO DE PAGAMENTO DO PAYPAL(sandbox - somente para testes | live - para pagamentos reais)

if(MODE === 'sandbox'):
	define('PAYMENT_URL', 'https://api-m.sandbox.paypal.com/');			// URL ONDE SERÁ FEITA TODAS AS REQUISIÇÕES SSL PARA A API DO PAYPAL NO MODO sandbox
	define('PAYMENT_CLIENTID', '');										// ESTE ID É FORNECIDO PELO PAYPAL PARA O MODO sandbox
	define('PAYMENT_SECRETKEY', '');									// ESTA CHAVE É FORNECIDA PELO PAYPAL PARA O MODO sandbox
else:
	define('PAYMENT_URL', 'https://api-m.paypal.com/');					// URL ONDE SERÁ FEITA TODAS AS REQUISIÇÕES SSL PARA A API DO PAYPAL NO MODO live
	define('PAYMENT_CLIENTID', '');										// ESTE ID É FORNECIDO PELO PAYPAL PARA O MODO live
	define('PAYMENT_SECRETKEY', '');									// ESTA CHAVE É FORNECIDA PELO PAYPAL PARA O MODO live
endif;

define('PAYMENT_EMAIL', $CONFIG->payment->email);
define('PAYMENT_FIRSTNAME', $CONFIG->payment->firstName);
define('PAYMENT_LASTNAME', $CONFIG->payment->lastName);
define('PAYMENT_TAXID', $CONFIG->payment->taxID);
define('PAYMENT_COUNTRY', $CONFIG->payment->country);
define('PAYMENT_LANGUAGE', $CONFIG->payment->language);

unset($CONFIG);