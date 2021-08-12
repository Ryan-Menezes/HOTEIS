let loading = window.document.createElement('div')
loading.classList.add('loading')

// Variáveis que armazenam as requisições ajax

let executaFormCarregaConfig = null

// FUNÇÃO QUE VERIFICA OS CAMPOS VÁZIO DE UM DETERMINADO FORMULÁRIO

function verificaInputs(form){
	let inputs = form.getElementsByClassName('inputsForm')
	let spans = form.getElementsByClassName('spanAlerta')
	let resultado = true

	for(let i = 0; i < inputs.length; i++){
		if(inputs[i].type === 'email' && (inputs[i].value.trim().length === 0 || !inputs[i].value.trim().includes('@'))){
			inputs[i].style.border = '1px solid #f27e50'
			spans[i].style.display = 'block'
			resultado = false
		}else if(inputs[i].value.trim().length === 0){
			inputs[i].style.border = '1px solid #f27e50'
			spans[i].style.display = 'block'
			resultado = false
		}
	}

	return resultado
}

// FUNÇÃO QUE EXECUTA UM FORMULÁRIO E RETORNA UMA MENSAGEM

function executaForm(reset = true, funcao = null){
	let form = event.target

	event.preventDefault()

	if(verificaInputs(form)){
		$.ajax({
			method: form.method,
			url: form.action,
			data: new FormData(form),
			processData: false, 
			contentType: false,
			beforeSend: function(){
				window.document.getElementById('loading').style.display = 'flex'
			}
		})
		.done(function(res){
			let resultado = JSON.parse(res)

			let msg = form.getElementsByClassName('msg')[0]
			let msgText = form.getElementsByClassName('textMsg')[0]

			msgText.innerText = resultado.MSG

			msg.classList.remove('erro')
			msg.classList.remove('sucesso')

			if(resultado.RES){
				msg.classList.add('sucesso')
				if(reset) form.reset()
			}else{
				msg.classList.add('erro')
			}

			window.document.getElementById('loading').style.display = 'none'
			msg.style.display = 'flex'

			if(funcao !== null) funcao()
		})
		.fail(function(){
			window.document.getElementById('loading').style.display = 'none'
		})
	}
}

// FUNÇÃO QUE EXECUTA UM FORMULÁRIO E RETORNA DADOS

function executaFormCarrega(sucesso, loading, erro){
	event.preventDefault()

	let form = event.target
	let html = form.innerHTML

	//if(executaFormCarregaConfig != null) executaFormCarregaConfig.abort()

	executaFormCarregaConfig = $.ajax({
		method: form.method,
		url: form.action,
		data: new FormData(form),
		processData: false, 
		contentType: false,
		beforeSend: function(){
			if(loading != null) loading(form)
		}
	})
	.done(function(res){
		executaFormCarregaConfig = null

		sucesso(form, html, res)
	})
	.fail(function(){
		executaFormCarregaConfig = null
		
		if(erro != null) erro()
	})
}

// Função para fechar todas as modais do sistema

function fechaModais(){
	let modais = window.document.getElementsByClassName('modal')

	for(let modal of modais){
		modal.style.display = 'none'

		// Resetando todos os formulários

		let formularios = modal.getElementsByTagName('form')

		for(let formulario of formularios) formulario.reset()

		// Fechando todas as mensagens dentro de uma modal

		let msgs = modal.getElementsByClassName('msg')

		for(let msg of msgs){
			msg.classList.remove('erro')
			msg.classList.remove('sucesso')
			msg.style.display = 'none'
		}
	}
}

// Função para fechar caixas de mensagens de formulários

function fechaMensagem(caixa){
	caixa.style.display = 'none'
}

// Função que executa um formulário de pesquisa da página

function recarregaForm(){
	let form = window.document.getElementById('pesquisa')
	let input = form.querySelector('input[type=submit], button[type=submit]')

	input.click()
}