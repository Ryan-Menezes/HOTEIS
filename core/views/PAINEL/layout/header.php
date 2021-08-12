<main id="menuLateral">
	<div>
		<img src="data:image/*;base64,<?= $dadosUser['IMG'] ?>">
		<main>
			<p><?= $dadosUser['NOME'] ?> <?= $dadosUser['SOBRENOME'] ?></p>
			<p><?= $dadosUser['EMAIL'] ?></p>
		</main>	
	</div>
	<ul>
		<a href="?a=inicio"><li><i class="fas fa-home"></i> Início</li></a>
		<?php if($dadosUser['ACESSO'] === 'A'): ?>
		<a href="?a=quartos"><li><i class="fas fa-door-closed"></i> Quartos</li></a>
		<a href="?a=usuarios"><li><i class="fas fa-users"></i> Usuários</li></a>
		<a href="?a=reservas"><li><i class="fas fa-bed"></i> Reservas</li></a>
		<a href="?a=reservas_solicitadas"><li><i class="fas fa-calendar"></i> Reservas Solicitadas</li></a>
		<?php else: ?>
		<a href="?a=minhas_reservas"><li><i class="fas fa-calendar-plus"></i> Minhas Reservas</li></a>
		<?php endif; ?>
		<a href="?a=configuracoes"><li><i class="fas fa-cog"></i> Configurações</li></a>
		<a href="?a=logout"><li><i class="fas fa-sign-out-alt"></i> Sair</li></a>
	</ul>
</main>

<section id="principalContainer">
	
<header id="header_pagina">
	<main id="menu">
		<img src="../assets/IMGS/<?= LOGO ?>">

		<nav>
			<ul>
				<li>
					<span id="spanNotification">Notificações 
						<?php if($totalNotification > 0): ?>
						<span><?= $totalNotification ?></span>
						<?php endif; ?> 
						<i class="fas fa-bell"></i> <i class="fas fa-caret-down"></i></span>
					<ul id="not" class="notificacao">
						<div>
							<h3>Últimas Notificações</h3>
						</div>
						<div class="notificacaoCont">
							<form method="POST" action="?a=notificacoes" id="formCarregaNotificacao" class="formularioCarrega">
								<input type="hidden" name="min" value="0">
								<button type="submit" class="btnIniciaCarregamentoNot"><i class="fas fa-plus"></i></button>
							</form>
						</div>
					</ul>
				</li>
			</ul>
		</nav>
	</main>
</header>

<section id="containerPainel">