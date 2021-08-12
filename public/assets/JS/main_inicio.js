(function(){
	let imgsCarrossel, indiceProximo

	// FUNÇÃO QUE APRESENTA O PROXIMO BANNER DO CARROSSEL

	function next(){
		let container = window.document.getElementById('header_pagina')
		let text = window.document.getElementById('pText')
		indiceProximo++

		if(indiceProximo >= imgsCarrossel.site.carrossel.imgs.length) indiceProximo = 0

		text.innerText = imgsCarrossel.site.carrossel.imgs[indiceProximo].text
		container.style.backgroundImage = `url("./assets/IMGS/CARROSSEL/${imgsCarrossel.site.carrossel.imgs[indiceProximo].img}")`
	}

	// FUNÇÃO QUE APRESENTA O BANNER ANTERIOR DO CARROSSEL

	function last(){
		let container = window.document.getElementById('header_pagina')
		let text = window.document.getElementById('pText')
		indiceProximo--

		if(indiceProximo < 0) indiceProximo = imgsCarrossel.site.carrossel.imgs.length - 1

		text.innerText = imgsCarrossel.site.carrossel.imgs[indiceProximo].text
		container.style.backgroundImage = `url("./assets/IMGS/CARROSSEL/${imgsCarrossel.site.carrossel.imgs[indiceProximo].img}")`
	}

	// FUNÇÃO QUE INICIALIZ\A O CARROSSEL

	function iniciaCarrossel(){
		let container = window.document.getElementById('header_pagina')
		let text = window.document.getElementById('pText')

		indiceProximo = 0
		let xmlhttp

		if(window.XMLHttpRequest) xmlhttp = new XMLHttpRequest()
		else xmlhttp = new ActiveXObject('Microsoft.XMLHTTP')

		xmlhttp.onreadystatechange = function(){
			if(this.readyState === 4 && this.status === 200){
				imgsCarrossel = JSON.parse(this.responseText)

				text.innerText = imgsCarrossel.site.carrossel.imgs[indiceProximo].text
				container.style.backgroundImage = `url("./assets/IMGS/CARROSSEL/${imgsCarrossel.site.carrossel.imgs[indiceProximo].img}")`
			}
		}

		xmlhttp.open('POST', '../config.json', true)
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
		xmlhttp.send()	
	}

	// FUNÇÃO QUE APRSENTA O MENU FIXO APÓS A ROLAGEM DO SCROLL DA TELA CHEGAR ATÁ UM LIMITE DETERMINADO

	function showMenuFixo(){
		let header = window.document.getElementById('header_pagina')
		let menu = window.document.getElementById('menuFixo')

		if(window.document.documentElement.scrollTop >= header.offsetTop + header.offsetHeight) menu.style.display = 'flex'
		else menu.style.display = 'none'
	}

	window.addEventListener('load', function(){
		// Inicializando carrossel

		iniciaCarrossel()

		window.document.getElementById('btnNext').addEventListener('click', next)
		window.document.getElementById('btnLast').addEventListener('click', last)

		setInterval(next, 10000);

		// Evento que fecha todas as modais do sistema

		let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

		for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

		// Adicionando um evento para cada form da tela

		let forms = window.document.forms

		for(let form of forms){
			if(form.dataset.ajax !== undefined && form.dataset.ajax) form.addEventListener('submit', executaForm)
			else form.addEventListener('submit', () => {
				if(!verificaInputs(event.target)) event.preventDefault()
			})
		}

		// Evento para recuperar senha

		window.document.getElementById('esqueciSenha').addEventListener('click', function(){
			event.preventDefault()

			window.document.getElementById('modalEsqueciSenha').style.display = 'flex'
		})

		// Evento de scroll na página

		window.onscroll = showMenuFixo
		showMenuFixo()
	})
})()