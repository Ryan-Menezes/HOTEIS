<header id="header_pagina">
	<main id="menu">
		<img src="./assets/IMGS/<?= LOGO ?>">

		<nav>
			<ul>
				<?php if($logado): ?>
				<li><a href="<?= URL_PAINEL ?>"><i class="fas fa-concierge-bell"></i> Fazer uma reserva</a>|</li>
				<?php else: ?>
				<li><a href="#login"><i class="fas fa-concierge-bell"></i> Fazer uma reserva</a>|</li>
				<?php endif; ?>
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
			<div id="texto">
				<p id="pText"></p>
			</div>
			<div>
				<?php if($logado): ?>
				<a href="<?= URL_PAINEL ?>?a=inicio"><button><i class="fas fa-concierge-bell"></i> Fazer uma reserva</button></a>
				<?php else: ?>
				<a href="#login"><button><i class="fas fa-concierge-bell"></i> Fazer uma reserva</button></a>
				<?php endif; ?>
			</div>
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