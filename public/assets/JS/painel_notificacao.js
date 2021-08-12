
// Adicionando um evento ao primeiro formulário que carrega os quartos disponíveis

let formulario = window.document.getElementById('formCarregaNotificacao')

formulario.addEventListener('submit', function(){
	executaFormCarrega(carregaNotificacoes, loadingNotificacoes, null)
})

// Adicionando um evento ao botão de deletar da modal deleta
	
formulario = window.document.getElementById('formDeletaNotificacao')

formulario.addEventListener('submit', function(){
	executaFormCarrega(deletaNotificacao, loadingDeletaNotificacao, null)
})

// Inicializando carregamento do conteúdo

let btns = window.document.getElementsByClassName('btnIniciaCarregamentoNot')

for(let btn of btns) btn.click()

// Adicionando evento para abrir as notificações

window.document.getElementById('spanNotification').addEventListener('click', function(){
	let container = window.document.getElementById('not')

	if(container.style.display != 'block') container.style.display = 'block'
	else container.style.display = 'none'
})

// Função que recebe o resultado da exclusão de uma notificação

function deletaNotificacao(form, html, res){
	let container = window.document.getElementById('msgFixo')

	form.innerHTML = html

	container.innerHTML = JSON.parse(res).MSG
	container.style.display = 'inline-block'

	window.document.getElementById('modalNotificacao').style.display = 'none'
}

// Função que inicia um loading de carregamento da exclusão

function loadingDeletaNotificacao(form){
	form.innerHTML = ''
	form.appendChild(loading)
}

// Função que carrega as notificações no container

function carregaNotificacoes(form, html, res){
	form.remove()
	window.document.getElementsByClassName('notificacaoCont')[0].innerHTML += res
}

// Função que adiciona um carregamento no container

function loadingNotificacoes(form){
	let loadingNot = window.document.createElement('div')
	loadingNot.classList.add('loading')

	form.innerHTML = ''
	form.appendChild(loadingNot)
}

// Função que abre a notificação para leitura

function abreNotificacao(dados){
	let notificacao = JSON.parse(dados)
	let modal = window.document.getElementById('modalNotificacao')

	modal.getElementsByClassName('id_not')[0].value = notificacao.ID
	modal.getElementsByClassName('titulo')[0].innerText = notificacao.TITULO
	modal.getElementsByClassName('mensagem')[0].value = notificacao.MENSAGEM
	modal.getElementsByClassName('data')[0].innerText = notificacao.DATA

	visualizaNotificacao(notificacao.ID)

	modal.style.display = 'flex'
}

function visualizaNotificacao(id){
	let xmlhttp

	if(window.XMLHttpRequest) xmlhttp = new XMLHttpRequest()
	else xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')

	xmlhttp.onreadystatechange = function(){
		if(this.readyState === 4 && this.status === 200){
			if(Number(this.responseText)){
				let span = window.document.querySelector('#spanNotification span')

				if(Number(span.innerText) > 1) span.innerText = Number(span.innerText) - 1
				else span.remove()
			}
		}
	}

	xmlhttp.open('POST', '?a=visualiza_notificacao', true)
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
	xmlhttp.send(`id=${id}`)
}