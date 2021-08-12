window.onload = () => {
	// Adicionando eventos ao select de busca do status

	let status = window.document.getElementsByClassName('status')

	for(let st of status) st.addEventListener('change', () => {
		window.document.getElementById('btnPesquisa').click()
	})

	// Evento que fecha todas as modais do sistema

	let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

	for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

	// Evento a caixa de mensagem fixa

	window.document.getElementById('msgFixo').addEventListener('click', () => event.target.style.display = 'none')

	// Adicionando um evento ao botão de cancelar da modal deleta
	
	let formulario = window.document.getElementById('formDeleta')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(deletaUsuario, loadingDeletaUsuario, null)
	})

	// Adicionando um evento ao botão de cancelar da modal recupera
	
	formulario = window.document.getElementById('formRecupera')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(recuperaUsuario, loadingDeletaUsuario, null)
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
		executaFormCarrega(carregaUsuariosBusca, loadingCarregaUsuariosBusca, null)
	})

	// Adicionando um evento ao primeiro formulário que carrega os usuários

	formulario = window.document.getElementById('formCarrega')

	formulario.addEventListener('submit', function(){
		executaFormCarrega(carregaUsuarios, loadingCarregaUsuarios, null)
	})

	// Inicializando carregamento do conteúdo

	let btns = window.document.getElementsByClassName('btnIniciaCarregamento')

	for(let btn of btns) btn.click()
}

// Função que apresenta os dados do usuário numa modal

function visualizarUsuario(dados){
	let usuario = JSON.parse(dados)

	let modal = window.document.getElementById('modalVis')
	let container = modal.getElementsByClassName('informacoes')[0]

	container.innerHTML = `<div style="display: flex; flex-direction: column;">
								<div style="display: flex; align-items: center; margin-bottom: 20px;">
									<img src="data:image/*;base64,${usuario.IMG}" style="width: 40px; height: 40px; border-radius: 50%;">
									<h5 style="margin-left: 10px;">${usuario.NOME} ${usuario.SOBRENOME}</h5>
								</div>

							    <table>
									<thead>
										<tr>
											<th>CPF</th>
											<th>E-Mail</th>
											<th>Acesso</th>
											<th>Conta Verificada</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>${usuario.CPFMASK}</td>
											<td>${usuario.EMAIL}</td>
											<td>${usuario.ACESSO}</td>
											<td>${usuario.ATIVO == 1 ? '<i class="fas fa-check-circle verde"></i>' : '<i class="fas fa-times-circle vermelho"></i>'}</td>
											<td>${usuario.STATUS}</td>
										</tr>
									</tbody>
								</table>
							</div>`

	modal.style.display = 'flex';
}

// Função que inicia uma edição de um quarto

function editarUsuario(dados){
	let usuario = JSON.parse(dados)

	let modal = window.document.getElementById('modalEdit')
	let form = modal.getElementsByClassName('formularioExecuta')[0]

	form.elements['nome'].value = usuario.NOME
	form.elements['sobrenome'].value = usuario.SOBRENOME
	form.elements['cpf'].value = usuario.CPF
	form.elements['cpf_antigo'].value = usuario.CPF
	form.elements['email'].value = usuario.EMAIL
	form.elements['email_antigo'].value = usuario.EMAIL

	// SELECT ACESSO

	let ops = form.elements['acesso'].options

	for(let op of ops){
		if(op.value == usuario.ACESSO[0]) op.selected = true;
		else op.selected = false;
	}

	// SELECT STATUS

	ops = form.elements['status'].options

	for(let op of ops){
		if(op.value == usuario.STATUS[0]) op.selected = true;
		else op.selected = false;
	}

	// SELECT VERIFICAÇÃO

	ops = form.elements['ativo'].options

	for(let op of ops){
		if(op.value == usuario.ATIVO) op.selected = true;
		else op.selected = false;
	}

	modal.style.display = 'flex';
}

// Função que inicia a exclusão de um usuário

function deletarUsuarioModal(cpf){
	let modal = window.document.getElementById('modalDeletaUsuario')
	let input = window.document.getElementById('cpf_usuario_deleta')

	if(cpf !== null){
		input.value = cpf
		modal.style.display = 'flex'
	}else{
		input.value = ''
		modal.style.display = 'none'
	}
}

// Função que recebe o resultado da exclusão de um usuário

function deletaUsuario(form, html, res){
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	recarregaForm()
	deletarUsuarioModal(null)
}

// Função que inicia um loading de carregamento da exclusão

function loadingDeletaUsuario(form){
	form.innerHTML = ''
	form.appendChild(loading)
}

// Função para recuperar um usuário deletado

function recuperaUsuarioModal(cpf){
	let modal = window.document.getElementById('modalRecuperaUsuario')
	let input = window.document.getElementById('cpf_usuario_recupera')

	if(cpf !== null){
		input.value = cpf
		modal.style.display = 'flex'
	}else{
		input.value = ''
		modal.style.display = 'none'
	}
}

// Função que recebe o resultado da recuperação de um usuário

function recuperaUsuario(form, html, res){
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	recarregaForm()
	recuperaUsuarioModal(null)
}

// Função que recebe os dados caso houver sucesso na transação

function carregaUsuarios(form, html, res){
	let container = window.document.getElementById('containerUsuarios')

	form.parentNode.parentNode.remove()
	loading.parentNode.parentNode.remove()

	container.innerHTML += res
}

// Função para fazaer uma busca

function carregaUsuariosBusca(form, html, res){
	let container = window.document.getElementById('containerUsuarios')

	loading.parentNode.parentNode.remove()

	container.innerHTML = res
}

// Função de loading

function loadingCarregaUsuarios(form){
	let container = window.document.getElementById('containerUsuarios')

	form.innerHTML = ''

	let tr = window.document.createElement('tr')
	let td = window.document.createElement('td')
	td.setAttribute('colspan', 9)

	td.appendChild(loading)
	tr.appendChild(td)

	container.appendChild(tr)
}

// Função de loading para busca

function loadingCarregaUsuariosBusca(form){
	let container = window.document.getElementById('containerUsuarios')

	let tr = window.document.createElement('tr')
	let td = window.document.createElement('td')
	td.setAttribute('colspan', 9)

	td.appendChild(loading)
	tr.appendChild(td)

	container.innerHTML = ''
	container.appendChild(tr)
}