let imgs = []
let removidos = []

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
		else form.addEventListener('submit', () => executaForm(true))
	}

	// Adicionando um evento a um botão que chama a modal para delatar a conta

	window.document.getElementById('iniDeletarConta').addEventListener('click', function(){
		window.document.getElementById('modalSenha').style.display = 'flex'
	})

	// Adicionando um evento ao formulário da modal para deletar a conta

	window.document.getElementById('deletaConta').addEventListener('submit', () => {
		let form = event.target

		if(!verificaInputs(form)) event.preventDefault()
	})

	// Adicionando um preview da imagem selecionada ao inputs file

	let destaques = window.document.getElementsByClassName('destaque')

	for(let destaque of destaques){
		imgs.push(window.document.getElementById(`imgs${destaque.dataset.index}`).src)

		destaque.addEventListener('change', imgPreview)
	}
}

function inputAddImg(){
	let inp = event.target
	let file = inp.files[0]
	let index = inp.dataset.index
	let img = window.document.getElementById(`img${index}`)

	let reader = new FileReader()

	reader.onloadend = function(){
		img.src = event.target.result

		if(inp.dataset.add === "true"){
			inp.dataset.add = "false"
			window.document.getElementById(`txt${index}`).classList.add('inputsForm')

			index++

			// Criando o container de seleção de imagem

			let div = window.document.createElement('div')

			let label = window.document.createElement('label')
			label.setAttribute('for', `input${index}`)

			let img = window.document.createElement('img')
			img.setAttribute('id', `img${index}`)
			img.setAttribute('src', '../assets/IMGS/add.png')
			label.appendChild(img)

			let textarea = window.document.createElement('textarea')
			textarea.setAttribute('id', `txt${index}`)
			textarea.setAttribute('placeholder', 'Descrição')
			textarea.setAttribute('name', 'descricao[]')

			let input = window.document.createElement('input')
			input.setAttribute('type', 'file')
			input.setAttribute('accept', 'image/*')
			input.setAttribute('class', 'inputsAdd')
			input.setAttribute('name', 'imgs[]')
			input.setAttribute('id', `input${index}`)
			input.dataset.index = index
			input.dataset.add = true
			input.onchange = inputAddImg

			let btn = window.document.createElement('button')
			btn.setAttribute('class', 'bg-vermelho')
			btn.setAttribute('type', 'button')
			btn.onclick = () => removeImg(index)

			let i = window.document.createElement('i')
			i.setAttribute('class', 'fas fa-trash-alt')
			btn.appendChild(i)

			div.appendChild(label)
			div.appendChild(textarea)
			div.appendChild(input)
			div.appendChild(btn)

			window.document.getElementsByClassName('imagemCont')[0].appendChild(div)
		}
	}

	if(file) reader.readAsDataURL(file)
	else img.src = '../assets/IMGS/add.png'
}

function imgPreview(){
	let inp = event.target
	let file = inp.files[0]
	let index = inp.dataset.index
	let img = window.document.getElementById(`imgs${index}`)

	let reader = new FileReader()

	reader.onloadend = function(){
		img.src = event.target.result
	}

	if(file) reader.readAsDataURL(file)
	else img.src = imgs[index]
}

function removeImg(index){
	let inp = window.document.getElementById(`input${index}`)
	removidos.push(index)
	window.document.getElementById('removidos').value = removidos.join()

	if(inp.dataset.add !== "true") inp.parentNode.remove()
}