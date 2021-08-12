window.onload = () => {
	// Adicionando eventos ao select de busca do status

	let status = window.document.getElementsByClassName('status')

	for(let st of status) st.addEventListener('change', () => {
		window.document.getElementById('btnPesquisa').click()
	})

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

	// Evento ao furmulário de busca

	let pesquisa = window.document.getElementById('pesquisa')

	pesquisa.addEventListener('submit', function(){
		executaFormCarrega(carregaMinhasReservasBusca, loadingCarregaMinhasReservasBusca, null)
	})

	// Adicionando um evento ao primeiro formulário que carrega os quartos disponíveis

	let formulario = window.document.getElementById('formCarrega')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaMinhasReservas, loadingCarregaMinhasReservas, null)
	})

	// Inicializando carregamento do conteúdo

	let btns = window.document.getElementsByClassName('btnIniciaCarregamento')

	for(let btn of btns) btn.click()
}

// Função que abre o formulário para fazer um pedido de reserva

function solicitarReserva(dados){
	let quarto = JSON.parse(dados)
	let modalSolicita = window.document.getElementById('modalSolicita')
	let info = window.document.getElementsByClassName('infoForm')[0]

	info.innerHTML = `<legend>Quarto - ${quarto.NUM}</legend>
					  <p><strong>Tipo: <strong>${quarto.TIPO}</p>
					  <p><strong>Andar: <strong>${quarto.ANDAR}</p>
					  <p><strong>Valor por hora: <strong>R$${quarto.PRECO}</p>
					  <input type="hidden" name="numero_quarto" value="${quarto.NUM}">`

	modalSolicita.style.display = 'flex'
}

// Função que recebe os dados caso houver sucesso na transação

function carregaMinhasReservas(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	form.remove()
	loading.remove()

	container.innerHTML += res
}

// Função para fazaer uma busca

function carregaMinhasReservasBusca(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	loading.remove()

	container.innerHTML = res
}

// Função de loading

function loadingCarregaMinhasReservas(form){
	let container = window.document.getElementById('containerQuartos')

	container.appendChild(loading)
}

// Função de loading

function loadingCarregaMinhasReservasBusca(form){
	let container = window.document.getElementById('containerQuartos')

	container.innerHTML = ''
	container.appendChild(loading)
}