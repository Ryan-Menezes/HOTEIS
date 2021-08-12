window.onload = () => {
	// Evento que fecha todas as modais do sistema

	let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

	for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

	// Adicionando um evento de submit para cada formulario da classe formularioExecuta

	let forms = window.document.getElementsByClassName('formularioExecuta')

	for(let form of forms){
		if(form.dataset.edit) form.addEventListener('submit', () => executaForm(false, recarregaForm))
		else form.addEventListener('submit', () => executaForm(true, recarregaForm))
	}

	// Evento a caixa de mensagem fixa

	window.document.getElementById('msgFixo').addEventListener('click', () => event.target.style.display = 'none')

	// Adicionando um evento ao botão de cancelar da modal

	let formulario = window.document.getElementById('formCancela')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(cancelaSolicitacaoReserva, loadingCancelaSolicitacaoReserva, null)
	})

	// Adicionando um evento ao primeiro formulário que carrega as reservas solicitadas

	formulario = window.document.getElementById('formCarrega')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaSolicitacoesReservas, loadingCarregaSolicitacoesReservas, null)
	})

	// Adicionando um evento ao segundo formulário que carrega as reservas do usuário

	formulario = window.document.getElementById('formCarregaReservas')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaReservas, loadingCarregaReservas, null)
	})

	// Inicializando carregamento do conteúdo

	let btns = window.document.getElementsByClassName('btnIniciaCarregamento')

	for(let btn of btns) btn.click()
}

// FUNÇÕES PARA O CANCELAMENTO DE UMA SOLICITAÇÃO DE RESERVA

// Função que pergunta ao usuário se ele realmente deseja cancelar a solicitação selecionada

function cancelarSolicitcao(id){
	let modal = window.document.getElementById('modalCancelarSolicitacao')
	let input = window.document.getElementById('id_pedido_reserva')

	if(id !== null){
		input.value = id
		modal.style.display = 'flex'
	}else{
		input.value = ''
		modal.style.display = 'none'
	}
}

// Função que recebe e apresenta o resultado do cancelamento

function cancelaSolicitacaoReserva(form, html, res){
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	cancelarSolicitcao(null)
}

// Função de loading do cancelamento

function loadingCancelaSolicitacaoReserva(form){
	form.innerHTML = ''
	form.appendChild(loading)
}

// FUNÇÕES PARA CARREGAMENTO DAS SOLICITAÇÕES DE RESERVA

// Função carrega as solicitações feitas pelo usuário

function carregaSolicitacoesReservas(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	form.remove()
	loading.remove()

	container.innerHTML += res
}

// Função de loading

function loadingCarregaSolicitacoesReservas(form){
	let container = window.document.getElementById('containerQuartos')

	container.appendChild(loading)
}

// FUNÇÕES PARA CARREGAR AS RESERVAS DO USUÁRIO

// Função carrega as reservas

function carregaReservas(form, html, res){
	let container = window.document.getElementsByClassName('containerReservas')[0]

	form.remove()
	loading.remove()

	container.innerHTML += res
}

// Função de loading

function loadingCarregaReservas(form){
	let container = window.document.getElementsByClassName('containerReservas')[0]

	container.appendChild(loading)
}

// FUNÇÕES PARA FINALIZAR UMA RESERVA

function finalizaReserva(form, html, res){
	let modal = window.document.getElementById('modalVis')
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	modal.style.display = 'none'
}

function loadingFinalizaReserva(form){
	form.innerHTML = ''
	form.appendChild(loading)
}

// Função que apresenta os dados do usuário numa modal

function visualizarReserva(dados){
	let reserva = JSON.parse(dados)

	let modal = window.document.getElementById('modalVis')
	let container = modal.getElementsByClassName('informacoes')[0]

	container.innerHTML = `<h5>Quarto - ${reserva.NUMERO_QUARTO}:</h5><br>

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
										<td>${reserva.TIPO}</td>
										<td>${reserva.ANDAR}</td>
										<td>${reserva.PRECO}</td>
										<td>${reserva.DATARESERVAFORMAT}</td>
										<td>${reserva.DATAENCERRAMENTOFORMAT}</td>
									</tr>
								</tbody>
							</table>`

	if(reserva.STATUS[0] == 'R'){
		container.innerHTML += `<form action="?a=finaliza_reserva" class='finalizaPagaReserva' method="POST" onsubmit="executaFormCarrega(finalizaReserva, loadingFinalizaReserva, null)">
					   				<input type="hidden" name="id_reserva" value="${reserva.IDRESERVA}">
					   				<button type="submit">Finalizar Reserva</button>
					   			</form>`
	}else if(reserva.STATUS[0] == 'P'){
		container.innerHTML += `<div class='finalizaPagaReserva' onclick='checkout(${reserva.IDRESERVA})'><button>Pagar</button></div>`
	}

	modal.style.display = 'flex';
}