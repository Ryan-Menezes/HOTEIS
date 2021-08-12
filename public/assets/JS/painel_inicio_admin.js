window.onload = function(){
	// Evento que fecha todas as modais do sistema

	let btnsFechaModal = window.document.getElementsByClassName('modalFecha')

	for(let btnFechaModal of btnsFechaModal) btnFechaModal.addEventListener('click', fechaModais)

	// Evento a caixa de mensagem fixa

	window.document.getElementById('msgFixo').addEventListener('click', () => event.target.style.display = 'none')

	// Evento para o select dos gráficos

	window.document.getElementById('selecionaGrafico').addEventListener('change', () => atualizaGraficoMovimentacoes(Number(event.target.value)))

	// Inicializa gráficos

	carregaDadosGraficos()
}

let chartMovimentacoes = null

function carregaDadosGraficos(){
	let xmlhttp

	if(window.XMLHttpRequest) xmlhttp = new XMLHttpRequest()
	else xmlhttp = new ActionXObject('Microsoft.XMLHTTP')

	xmlhttp.onreadystatechange = function(){
		if(this.readyState === 4 && this.status === 200){
			let res = JSON.parse(this.responseText)

			iniciaGraficoMovientacoes()
			iniciaGraficoUsuarios(Number(res.USUARIOS.A), Number(res.USUARIOS.C))
			iniciaGraficoReservas(Number(res.RESERVAS.R), Number(res.RESERVAS.P), Number(res.RESERVAS.C))
		}
	}

	xmlhttp.open('GET', '?a=busca_dados_grafico', true)
	xmlhttp.send()
}

function iniciaGraficoMovientacoes(){
	let elemento = window.document.getElementById('graficoMovientacoes')
	let options = {
		chart: {
			type: 'area'
		},
		series: [],
		colors: ['#d14108']
	}

	chartMovimentacoes = new ApexCharts(elemento, options)

	atualizaGraficoMovimentacoes()
	chartMovimentacoes.render()
}

function atualizaGraficoMovimentacoes(tipo = 0){
	let xmlhttp

	if(window.XMLHttpRequest) xmlhttp = new XMLHttpRequest()
	else xmlhttp = new ActionXObject('Microsoft.XMLHTTP')

	xmlhttp.onreadystatechange = function(){
		if(this.readyState === 4 && this.status === 200){
			let res = JSON.parse(this.responseText)
			let msgs = [
				'Total de reservas da semana',
				'Total de reservas dos últimos meses do ano',
				'Total de reservas dos últimos anos'
			]

			chartMovimentacoes.updateSeries([
				{
					name: 'Total',
					data: res.VALUES
				}
			])

			chartMovimentacoes.updateOptions({
			  	xaxis: {
			  		categories: res.KEYS
			  	},
			  	title: {
					text: msgs[tipo],
					align: 'left'
				}
			})
		}
	}

	xmlhttp.open('POST', '?a=total_reservas_tempo', true)
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded')
	xmlhttp.send(`tipo=${tipo}`)
}

function iniciaGraficoUsuarios(adm, cli){
	let elemento = window.document.getElementById('graficoUsuarios')
	let options = {
		chart: {
			type: 'donut'
		},
		title: {
			text: 'Usuários',
			align: 'left'
		},
		series: [cli, adm],
		labels: ['Clientes', 'Administradores'],
		colors: ['#ff2424', '#fa9e00']
	}

	let chart = new ApexCharts(elemento, options)

	chart.render()
}
function iniciaGraficoReservas(r, p, c){
	let elemento = window.document.getElementById('graficoReservas')
	let options = {
		chart: {
			type: 'donut'
		},
		title: {
			text: 'Reservas',
			align: 'left'
		},
		series: [r, p, c],
		labels: ['Em Andamento', 'Aguardando Pagamento', 'Concluidas'],
		colors: ['#ff2424', '#fa9e00', '#04c26f']
	}

	let chart = new ApexCharts(elemento, options)

	chart.render()
}