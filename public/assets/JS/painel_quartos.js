window.onload = () => {
	// Adicionando eventos ao select de busca do status

	let status = window.document.getElementsByClassName('status')

	for(let st of status) st.addEventListener('change', () => {
		window.document.getElementById('btnPesquisa').click()
	})

	// Evento que abre o formulário de cadastro

	window.document.getElementById('btnAdd').addEventListener('click', function(){
		window.document.getElementById('modalAdd').style.display = 'flex'
	})

	// Evento que fecha todas as modais do sistema

	let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

	for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

	// Evento a caixa de mensagem fixa

	window.document.getElementById('msgFixo').addEventListener('click', () => event.target.style.display = 'none')

	// Adicionando um evento ao botão de cancelar da modal
	
	let formulario = window.document.getElementById('formDeleta')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(deletaQuarto, loadingDeletaQuarto, null)
	})

	// Adicionando um evento de submit para cada formulario da classe formularioExecuta

	let forms = window.document.getElementsByClassName('formularioExecuta')

	for(let form of forms){
		if(form.dataset.edit) form.addEventListener('submit', () => executaForm(false, recarregaForm))
		else form.addEventListener('submit', () => executaForm(true, recarregaForm))
	}

	// Evento ao furmulário de busca

	let pesquisa = window.document.getElementById('pesquisa')

	pesquisa.addEventListener('submit', function(){
		executaFormCarrega(carregaQuartosBusca, loadingCarregaQuarto, null)
	})

	// Adicionando um evento ao primeiro formulário que carrega os quartos disponíveis

	formulario = window.document.getElementById('formCarrega')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaQuartos, loadingCarregaQuartoBusca, null)
	})

	// Inicializando carregamento do conteúdo

	let btns = window.document.getElementsByClassName('btnIniciaCarregamento')

	for(let btn of btns) btn.click()
}

// Função que inicia uma edição de um quarto

function editarQuarto(dados){
	let quarto = JSON.parse(dados)

	let modal = window.document.getElementById('modalEdit')
	let form = modal.getElementsByClassName('formularioExecuta')[0]

	form.elements['numero_antigo'].value = quarto.NUM
	form.elements['numero_quarto'].value = quarto.NUM
	form.elements['andar'].value = quarto.ANDAR
	form.elements['preco'].value = quarto.PRECO

	let ops = form.elements['tipo'].options

	for(let op of ops){
		if(op.value == quarto.TIPO) op.selected = true;
		else op.selected = false;
	}

	ops = form.elements['status'].options

	for(let op of ops){
		if(op.value == quarto.STATUS) op.selected = true;
		else op.selected = false;
	}

	modal.style.display = 'flex';
}

// Função que inicia a exclusão de um quarto

function deletarQuartoModal(numero){
	let modal = window.document.getElementById('modalDeletaQuarto')
	let input = window.document.getElementById('numero_quarto_deleta')

	if(numero !== null){
		input.value = numero
		modal.style.display = 'flex'
	}else{
		input.value = ''
		modal.style.display = 'none'
	}
}

// Função que recebe o resultado da exclusão de um quarto

function deletaQuarto(form, html, res){
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	recarregaForm()
	deletarQuartoModal(null)
}

// Função que inicia um loading de carregamento da exclusão

function loadingDeletaQuarto(form){
	form.innerHTML = ''
	form.appendChild(loading)
}

// Função que recebe os dados caso houver sucesso na transação

function carregaQuartos(form, html, res){
	let container = window.document.getElementById('containerQuartos')
	
	loading.remove()
	form.remove()

	container.innerHTML += res
}

// Função para fazaer uma busca

function carregaQuartosBusca(form, html, res){
	let container = window.document.getElementById('containerQuartos')

	loading.remove()

	container.innerHTML = res
}

// Função de loading

function loadingCarregaQuarto(form){
	let container = window.document.getElementById('containerQuartos')

	container.innerHTML = ''
	container.appendChild(loading)
}

// Função de loading para busca

function loadingCarregaQuartoBusca(form){
	let container = window.document.getElementById('containerQuartos')

	container.appendChild(loading)
}