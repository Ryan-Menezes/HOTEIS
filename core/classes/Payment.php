<?php

namespace core\classes;

class Payment{
	private static $url;
	private static $post;
	private static $token;

	public static function curl(string $action){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, self::$url);
		if($action == 'token') curl_setopt($ch, CURLOPT_HEADER, false);
		else curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json', 'Authorization: Bearer ' . self::$token->access_token]);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, self::$post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($action == 'token') curl_setopt($ch, CURLOPT_USERPWD, PAYMENT_CLIENTID . ':' . PAYMENT_SECRETKEY);

		$data = json_decode(curl_exec($ch));

		curl_close($ch);

		return $data;
	}

	public static function getToken(){
		self::$url = PAYMENT_URL . 'v1/oauth2/token';
		self::$post = 'grant_type=client_credentials';

		self::$token = self::curl('token');
	}

	public static function invoice($data){
		self::getToken();

		self::$url = PAYMENT_URL . 'v1/payments/payment';
		self::$post = $data;

		return self::curl('invoice');
	}

	public static function execute($payment_id, $payer_id){
		self::getToken();

		self::$url = PAYMENT_URL . 'v1/payments/payment/' . $payment_id . '/execute/';
		self::$post = json_encode(['payer_id' => $payer_id]);

		return self::curl('payment');
	}
}