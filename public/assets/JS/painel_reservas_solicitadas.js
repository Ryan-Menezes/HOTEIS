window.onload = () => {
	// Evento que fecha todas as modais do sistema

	let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

	for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

	// Evento a caixa de mensagem fixa

	window.document.getElementById('msgFixo').addEventListener('click', () => event.target.style.display = 'none')

	// Adicionando um evento de submit para cada formulario da classe formularioExecuta

	let forms = window.document.getElementsByClassName('formularioExecuta')

	for(let form of forms){
		if(form.dataset.edit) form.addEventListener('submit', () => executaForm(false))
		else form.addEventListener('submit', executaForm)
	}

	// Adicionando um evento ao primeiro formulário que carrega as reservas solicitadas

	formulario = window.document.getElementById('formCarrega')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaSolicitacoesReserva, loadingCarregaSolicitacoesReserva, null)
	})

	// Inicializando carregamento do conteúdo

	let btns = window.document.getElementsByClassName('btnIniciaCarregamento')

	for(let btn of btns) btn.click()
}

// Função que apresenta os dados do usuário numa modal

function visualizarPedido(dados){
	let pedido = JSON.parse(dados)

	let modal = window.document.getElementById('modalVis')
	let container = modal.getElementsByClassName('informacoes')[0]

	let inputs = modal.getElementsByClassName('id_pedido_modal')

	for(let input of inputs) input.value = pedido.IDPEDIDO

	container.innerHTML = `<h5>Quarto - ${pedido.NUMERO_QUARTO}:</h5><br>

							<table>
								<thead>
									<tr>
										<th>Tipo</th>
										<th>Andar</th>
										<th>Valor por hora(R$)</th>
										<th>Data da Reserva</th>
										<th>Data do Encerramento</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>${pedido.TIPO}</td>
										<td>${pedido.ANDAR}</td>
										<td>${pedido.PRECO}</td>
										<td>${pedido.DATARESERVA}</td>
										<td>${pedido.DATAENCERRAMENTO}</td>
									</tr>
								</tbody>
							</table>

						    <br><h5>Cliente:</h5><br>

						    <table>
								<thead>
									<tr>
										<th>Foto</th>
										<th>Nome Completo</th>
										<th>CPF</th>
										<th>E-Mail</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><img src="data:image/*;base64,${pedido.IMGCLIENTE}"></td>
										<td>${pedido.NOMECLIENTE}</td>
										<td>${pedido.CPFCLIENTEMASK}</td>
										<td>${pedido.EMAILCLIENTE}</td>
									</tr>
								</tbody>
							</table>`

	modal.style.display = 'flex';
}

// Função que carrega as solicitações

function carregaSolicitacoesReserva(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	form.remove()
	loading.remove()

	container.innerHTML += res
}

// Função que apresenta um loadin na tela

function loadingCarregaSolicitacoesReserva(form){
	form.innerHTML = ''
	form.appendChild(loading)
}

// Funções para finalizar um pedido

function finalizaPedido(form, html, res){
	let modal = window.document.getElementById('modalVis')
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	modal.style.display = 'none'
}

// Função que inicia um loading de carregamento para finalizar um pedido

function loadingFinalizaPedido(form){
	form.innerHTML = ''
	form.appendChild(loading)
}