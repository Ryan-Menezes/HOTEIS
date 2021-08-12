window.onload = () => {
	// Adicionando eventos aos campos de busca do formulário

	let change = window.document.getElementsByClassName('change')

	for(let ch of change){
		ch.addEventListener('change', () => {
			window.document.getElementById('btnPesquisa').click()
		})
		
		ch.addEventListener('keyup', () => {
			window.document.getElementById('btnPesquisa').click()
		})
	}

	// Evento que fecha todas as modais do sistema

	let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

	for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

	// Evento a caixa de mensagem fixa

	window.document.getElementById('msgFixo').addEventListener('click', () => event.target.style.display = 'none')

	// Adicionando um evento de submit para cada formulario da classe formularioExecuta

	let forms = window.document.getElementsByClassName('formularioExecuta')

	for(let form of forms){
		if(form.dataset.edit) form.addEventListener('submit', () => executaForm(false, recarregaForm))
		else form.addEventListener('submit', () => executaForm(true, recarregaForm))
	}

	// Evento ao furmulário de busca

	let pesquisa = window.document.getElementById('pesquisa')

	pesquisa.addEventListener('submit', function(){
		executaFormCarrega(carregaReservasBusca, loadingCarregaReservasBusca, null)
	})

	// Adicionando um evento ao primeiro formulário que carrega os usuários

	formulario = window.document.getElementById('formCarrega')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaReservas, loadingCarregaReservas, null)
	})

	// Inicializando carregamento do conteúdo

	let btns = window.document.getElementsByClassName('btnIniciaCarregamento')

	for(let btn of btns) btn.click()
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
										<td><img src="data:image/*;base64,${reserva.IMGCLIENTE}"></td>
										<td>${reserva.NOMECLIENTE}</td>
										<td>${reserva.CPFCLIENTEMASK}</td>
										<td>${reserva.EMAILCLIENTE}</td>
									</tr>
								</tbody>
							</table>`

	modal.style.display = 'flex';
}

// Função que inicia uma edição de uma reserva

function editarReserva(dados){
	let reserva = JSON.parse(dados)

	let modal = window.document.getElementById('modalEdit')
	let form = modal.getElementsByClassName('formularioExecuta')[0]

	form.elements['id_reserva'].value = reserva.IDRESERVA
	form.elements['numero_quarto'].value = reserva.NUMERO_QUARTO
	form.elements['cpf_usuario'].value = reserva.CPFCLIENTE
	form.elements['data_reserva'].value = reserva.DATARESERVA.replace(' ', 'T')
	form.elements['data_encerrar'].value = reserva.DATAENCERRAMENTO.replace(' ', 'T')

	// SELECT STATUS

	ops = form.elements['status'].options

	for(let op of ops){
		if(op.value == reserva.STATUS) op.selected = true;
		else op.selected = false;
	}

	modal.style.display = 'flex';
}

// Função que recebe os dados caso houver sucesso na transação

function carregaReservas(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	form.remove()
	loading.remove()

	container.innerHTML += res
}

// Função para fazaer uma busca

function carregaReservasBusca(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	loading.remove()

	container.innerHTML = res
}

// Função de loading

function loadingCarregaReservas(form){
	let container = window.document.getElementById('containerQuartos')

	container.innerHTML = ''
	container.appendChild(loading)
}

// Função de loading para busca

function loadingCarregaReservasBusca(form){
	let container = window.document.getElementById('containerQuartos')

	container.innerHTML = ''
	container.appendChild(loading)
}