<header id="header_pagina">
	<main id="menu">
		<img src="./assets/IMGS/<?= LOGO ?>">

		<nav>
			<ul>
				<li><a href="#header_pagina"><i class="fas fa-concierge-bell"></i> Fazer uma reserva</a>|</li>
				<li><a href="#contatos"><i class="fas fa-phone-alt"></i> Contate-nos</a>|</li>
				<li><a href="#login"><i class="fas fa-sign-in-alt"></i> Entrar</a>|</li>
				<li><a href="#cadastro"><i class="fas fa-user"></i> Cadastrar-se</a></li>
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
			<li><a href="#header_pagina"><i class="fas fa-concierge-bell"></i> Fazer uma reserva</a>|</li>
			<li><a href="#contatos"><i class="fas fa-phone-alt"></i> Contate-nos</a>|</li>
			<li><a href="#login"><i class="fas fa-sign-in-alt"></i> Entrar</a>|</li>
			<li><a href="#cadastro"><i class="fas fa-user"></i> Cadastrar-se</a></li>
		</ul>
	</nav>
</main>