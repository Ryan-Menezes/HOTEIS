<head>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

		*{
			padding: 0;
			margin: 0;
			box-sizing: border-box;
			font-family: 'Roboto', arial, sans-serif;
			outline: none;
		}
		body{
			background-image: url('./assets/IMGS/fundo.gif');
			background-size: cover;
		}
		#containerRecupera{
			width: 100%;
			height: 85vh;
			display: flex;
			align-items: center;
			justify-content: center;	
		}
		#containerRecupera form{
			display: flex;
			flex-direction: column;
			width: 500px;
			border: 1px solid #c9c7c7;
			border-radius: 5px;
			font-size: 14px;
			background-color: white;
		}
		#containerRecupera form header, #containerRecupera form main, #containerRecupera form footer{
			padding: 10px;
		}
		#containerRecupera form header{
			border-bottom: 1px solid #c9c7c7;
		}
		#containerRecupera form footer{
			border-top: 1px solid #c9c7c7;
			display: flex;
			justify-content: flex-end;
		}
		#containerRecupera form footer button{
			padding: 10px;
			border: none;
			border-radius: 3px;
			background: #ff3f2e;
			color: white;
			cursor: pointer;
		}
		#containerRecupera form main{
			display: flex;
			flex-direction: column;
		}
		#containerRecupera form main input{
			padding: 8px;
			border-radius: 3px;
			border: 1px solid #c9c7c7;
			margin-top: 5px;
			margin-bottom: 5px;
		}
		#containerRecupera form .msg{
			padding: 10px;
			display: grid;
			display: none;
			justify-content: space-between;
			align-items: center;
			height: auto;
			color: white;
			border-radius: 3px;
			font-size: 14px;
			font-weight: bold;
			margin: 0px;
			margin-bottom: 10px;
		}
		#containerRecupera form .msg .btnFechaMsg{
			cursor: pointer;
		}
		#containerRecupera form .sucesso{
			background-color: #04cc35;
			border: 1px solid #039627;
			border-left: 5px solid #039627;
		}
		#containerRecupera form .erro{
			background-color: #ff4938;
			border: 1px solid #cf1200;
			border-left: 5px solid #cf1200;	
		}
	</style>
</head>
<body>

<section id="containerRecupera">
	<form method="POST" action="?a=alterar_senha_recupera" class="formularioExecuta">
		<header>
			<h3><?= APP_NAME ?> - <?= $titulo; ?></h3>
		</header>
		<main>
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<input type="hidden" name="h" value="<?= $hash ?>">
			<input type="hidden" name="c" value="<?= $curl ?>">

			<h5>Nova Senha:</h5>
			<input type="password" name="nsenha" class="inputsForm" placeholder="Nova Senha">
			<span class="spanAlerta">Por favor digíte sua nova senha</span>

			<h5>Repetir Nova Senha:</h5>
			<input type="password" name="rnsenha" class="inputsForm" placeholder="Nova Senha">
			<span class="spanAlerta">Por favor repita sua nova senha</span>
		</main>
		<footer>
			<button>Alterar Senha</button>
		</footer>
	</form>
</section>

<script type="text/javascript" src="../public/assets/JS/libs/jquery.js" defer></script>
<script type="text/javascript" src="../public/assets/JS/genericos.js" defer></script>

<script type="text/javascript" defer>
	window.onload = () => {
		let forms = window.document.getElementsByClassName('formularioExecuta')

		for(let form of forms) form.addEventListener('submit', executaForm)
	}
</script>