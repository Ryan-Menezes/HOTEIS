<header id="header_pagina">
	<main id="menu">
		<img src="./assets/IMGS/<?= LOGO ?>">

		<nav>
			<ul>
				<li><a href="#header_pagina">Fazer uma reserva</a>|</li>
				<li><a href="#contatos">Contate-nos</a>|</li>
				<li><a href="#login">Entrar</a>|</li>
				<li><a href="#cadastro">Cadastrar-se</a></li>
			</ul>
		</nav>
	</main>
	<main id="info">
		<button id="btnLast"><<</button>

		<div id="reserva">
			<h1><?= APP_NAME ?></h1>
			<div>
				<?php if($logado): ?>
				<a href="<?= URL_PAINEL ?>?a=inicio"><button>Fazer uma reserva</button></a>
				<?php else: ?>
				<a href="#login"><button>Fazer uma reserva</button></a>
				<?php endif; ?>
			</div>
		</div>
		<div id="texto">
			<p id="pText"></p>
		</div>

		<button id="btnNext">>></button>
	</main>
</header>

<main id="menuFixo">
	<img src="./assets/IMGS/<?= LOGO ?>">

	<nav>
		<ul>
			<li><a href="#header_pagina">Fazer uma reserva</a>|</li>
			<li><a href="#contatos">Contate-nos</a>|</li>
			<li><a href="#login">Entrar</a>|</li>
			<li><a href="#cadastro">Cadastrar-se</a></li>
		</ul>
	</nav>
</main>