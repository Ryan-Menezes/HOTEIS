(function(win, doc){
	let payment_id
	let id_reserva
	let ppp
	let container

	async function checkout(id){
		fechaModais()
		window.document.getElementById('modalPayment').style.display = 'flex'

		id_reserva = id

		container = window.document.getElementById('ppplus')
		container.innerHTML = '<div class="loading"></div>'

		let respPayment = await fetch('?a=payment_invoice', {
			method: 'POST',
			body: JSON.stringify({
				id: id_reserva
			}),
			headers: {
				Accept: 'application/json',
				'Content-type': 'application/json'
			}
		})

		let respConfig = await fetch('?a=get_payment_config', {
			method: 'POST',
			headers: {
				Accept: 'application/json',
				'Content-type': 'application/json'
			}
		})

		let payment = await respPayment.json()
		let config = await respConfig.json()

		if(payment.RES === undefined){
			ppp = await PAYPAL.apps.PPP({
				approvalUrl: payment.links[1].href,
	    		placeholder: 'ppplus',
	    		mode: config.mode,
	    		payerEmail: config.email,
	    		payerFirstName: config.firstName,
	    		payerLastName: config.lastName,
	    		payerPhone: config.phone,
	    		payerTaxId: config.taxID,
	    		country: config.country,
	    		language: config.language
	    	});

			payment_id = await payment.id

			window.document.getElementById('btnPagarReserva').style.display = 'block'
		}else{
			container.innerHTML = `<div class="msgFinal">
										<h3>${payment.MSG}</h3>
										<i class="far fa-times-circle vermelho"></i>
										<div>
											<button onclick="closePayment()">OK</button>
										</div>
									</div>`
		}
	}

	win.checkout = checkout

	if(doc.getElementById('btnPagarReserva')){
		doc.getElementById('btnPagarReserva').addEventListener('click', function(){
			event.preventDefault()

			ppp.doContinue()
		})
	}

	async function messageListener(event) {
	    try{
	        var data = await JSON.parse(event.data);

	        if(data.result.payment_approved && data.result.state == 'APPROVED'){
	        	let response = await fetch('?a=payment_execute', {
					method: 'POST',
					headers: {
						Accept: 'application/json',
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						payment_id: payment_id,
						payer_id: data.result.payer.payer_info.payer_id,
						reserva_id: id_reserva
					})
				})

				let json = await response.json()

				if(json.state == 'approved'){
					container.innerHTML = `<div class="msgFinal">
												<h3>Transação realizada com sucesso!</h3>
												<i class="far fa-check-circle verde"></i>
												<div>
													<button onclick="closePayment()">OK</button>
												</div>
											</div>`
				}else{
					container.innerHTML = `<div class="msgFinal">
												<h3>Transação NÃO realizada, Ocorreu um erro no processo!</h3>
												<i class="far fa-times-circle vermelho"></i>
												<div>
													<button onclick="closePayment()">OK</button>
												</div>
											</div>`
				}

				container.style.height = 'auto'
				doc.getElementById('btnPagarReserva').style.display = 'none'
	        }
	    }catch(exc){}
	}

	if(win.addEventListener) win.addEventListener("message", messageListener, false);
	else if(win.attachEvent) win.attachEvent("onmessage", messageListener);
	else throw new Error("Can't attach message listener");
})(window, window.document)

function closePayment(){
	window.document.getElementById('modalPayment').style.display = 'none'
}