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
			background-image: url('<?= URL_HOME ?>/assets/IMGS/fundo.gif');
			background-size: cover;
		}
		#containerMensagem{
			width: 100%;
			height: 85vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		#containerMensagem main{
			display: flex;
			flex-direction: column;
			width: 500px;
			border: 1px solid #c9c7c7;
			border-radius: 5px;
			font-size: 14px;
			background-color: white;
		}
		#containerMensagem main header, #containerMensagem main div, #containerMensagem main footer{
			padding: 10px;
		}
		#containerMensagem main header{
			border-bottom: 1px solid #c9c7c7;
		}
		#containerMensagem main footer{
			border-top: 1px solid #c9c7c7;
			display: flex;
			justify-content: flex-end;
		}
		#containerMensagem main footer button{
			padding: 10px;
			border: none;
			border-radius: 3px;
			background: #ff3f2e;
			color: white;
			cursor: pointer;
		}
	</style>
</head>
<body>

	<section id="containerMensagem">
		<main>
			<header>
				<h3><?= APP_NAME ?> - <?= $titulo; ?></h3>
			</header>
			<div><?= $mensagem; ?></div>
			<footer>
				<a href="?a=inicio"><button>Retornar para a tela inicial</button></a>
			</footer>
		</main>
	</section>

</body>
</html>