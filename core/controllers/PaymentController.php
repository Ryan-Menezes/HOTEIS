<?php

namespace core\controllers;

use core\classes\Store;
use core\classes\Payment;
use core\models\Reservas;
use core\models\Pagamentos;

class PaymentController{
	private $reservas;
	private $pagamentos;

	public function __construct(){
		// Verificando se existe alguém logado

		if(!Store::logado()):
			Store::redirect(['a' => 'inicio']);
		endif;

		$this->reservas = new Reservas();
		$this->pagamentos = new Pagamentos();
	}

	public function payment_invoice(){
		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		// Pegando os dados passados e validando-os

		$dados = json_decode(file_get_contents('php://input'));
		$dadosUser = Store::dadosUsuarioLogado();

		if(!empty($dados->id) && is_numeric($dados->id) && $this->reservas->reservaUsuarioExiste($dados->id, $dadosUser['CPF'])):
			$total = $this->reservas->totalPagar($dados->id);

			$data = [
				'intent' => 'sale',
				'payer' => [
					'payment_method' => 'paypal'
				],
				'transactions' => [
					[
						'amount' => [
							'currency' => 'BRL',
							'total' => $total,
							'details' => [
								'shipping' => '0',
								'subtotal' => $total,
								'shipping_discount' => '0.00',
								'insurance' => '0.00',
								'handling_fee' => '0.00',
								'tax' => '0.00'
							]
						],
						'description' => 'This is the payment transaction description',
						'payment_options' => [
							'allowed_payment_method' => 'IMMEDIATE_PAY'
						],
						'item_list' => [
							'shipping_address' => [
								'recipient_name' => 'PP Plus Recipient',
								'line1' => ADDRESS_STREET . ', ' . ADDRESS_NUMBER,
								'line2' => ADDRESS_DISTRICT,
								'city' => ADDRESS_CITY,
								'country_code' => ADDRESS_COUNTRY_CODE,
								'postal_code' => ADDRESS_POSTAL_CODE,
								'state' => ADDRESS_STATE,
								'phone' => ADDRESS_PHONE
							],
							'items' => [
								[
									'name' => 'handbag',
									'description' => 'red diamond',
									'quantity' => '1',
									'price' => $total,
									'sku' => 'reserva' . $dados->id,
									'currency' => 'BRL'
								]
							]
						]
					]
				],
				'redirect_urls' => [
					'return_url' => 'https://example.com/return',
					'cancel_url' => 'https://example.com/cancel'
				]
			];

			echo json_encode(Payment::invoice(json_encode($data)));
		else:
			echo json_encode(['RES' => false, 'MSG' => 'Não foi possível finalizar o pagamento, Ocorreu um erro no processo de pagamento!']);
		endif;
	}

	public function payment_execute(){
		// Verificando se houve uma requisição POST

		if($_SERVER['REQUEST_METHOD'] !== 'POST'):
			Store::redirect(['a' => 'inicio'], PAINEL);
		endif;

		$dados = json_decode(file_get_contents('php://input'));
		$dadosUser = Store::dadosUsuarioLogado();

		if(!empty($dados->payment_id) && !empty($dados->payer_id) && !empty($dados->reserva_id) && $this->reservas->reservaUsuarioExiste($dados->reserva_id, $dadosUser['CPF'])):
			$result = Payment::execute($dados->payment_id, $dados->payer_id);

			if($result->state == 'approved' && $this->reservas->editaStatusReserva($dados->reserva_id, 'C') && $this->pagamentos->addPagamento($dados->payment_id, $result->transactions[0]->amount->total, $result->state, $dados->reserva_id)):
				echo json_encode($result);
			else:
				echo json_encode(['state' => 'refused']);
			endif;
		else:
			echo json_encode(['state' => 'refused']);
		endif;
	}

	public function payment_notification_receive(){
		$dados = json_decode(file_get_contents('php://input'));

		$status = [
			'PAYMENT.AUTHORIZATION.CREATED' => 'C',
			'PAYMENT.AUTHORIZATION.VOIDED' => 'P',
			'PAYMENT.CAPTURE.COMPLETED' => 'C',
			'PAYMENT.CAPTURE.DENIED' => 'P',
			'PAYMENT.CAPTURE.PENDING' => 'P',
			'PAYMENT.CAPTURE.REFUNDED' => 'C',
			'PAYMENT.CAPTURE.REVERSED' => 'P'
		];

		if(array_key_exists($dados->event_type, $dados)):
			$pagamento = $this->pagamentos->buscaPagamento($dados->resource->parent_payment);

			if(!is_null($pagamento)):
				$this->pagamentos->editStatusPagamento($dados->resource->parent_payment, $dados->event_type);
				$this->reservas->editaStatusReserva($pagamento->id_reserva, $status[$dados->resource->parent_payment]);
			endif;
		endif;
	}
}