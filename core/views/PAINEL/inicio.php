<section class="modal" id="modalSolicita">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Solicitar Reserva</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=solicita_reserva" class="formularioExecuta">
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<fieldset class="infoForm"><legend>Quarto - 1</legend>
				<p>Tipo: Luxo</p>
				<p>Andar: 1</p>
				<p>Valor por hora: 10.00</p>
			</fieldset>

			<div class="divide">
				<div>
					<h5>Data da Reserva:</h5>
					<input type="datetime-local" name="data_reserva" class="inputsForm">
					<span class="spanAlerta">Por favor indique a data que deseja reservar este quarto</span>
				</div>
				<div>
					<h5>Data de Encerramento:</h5>
					<input type="datetime-local" name="data_encerrar" class="inputsForm">
					<span class="spanAlerta">Por favor indique até quando você ficará hospedado nesse quarto</span>
				</div>
			</div>

			<div class="contBtnModal">
				<input type="submit" value="Solicitar Reserva">
			</div>
		</form>
	</main>
</section>

<?php if($dadosUser['ACESSO'] === 'A'): ?>
<section id="cardsInfo">
	<a href="?a=usuarios"><div class="cardInfo">
		<div class="conteudoCard">
			<i class="fas fa-users laranja"></i>
			<div>
				<h3><?= $totais['USUARIOS'] ?></h3>
				<p>Total de Usuários</p>
			</div>
		</div>
	</div></a>

	<a href="?a=quartos"><div class="cardInfo">
		<div class="conteudoCard">
			<i class="fas fa-door-closed azul"></i>
			<div>
				<h3><?= $totais['QUARTOS'] ?></h3>
				<p>Total de Quartos</p>
			</div>
		</div>
	</div></a>

	<a href="?a=reservas"><div class="cardInfo">
		<div class="conteudoCard">
			<i class="fas fa-bed verde"></i>
			<div>
				<h3><?= $totais['RESERVAS'] ?></h3>
				<p>Total de Reservas</p>
			</div>
		</div>
	</div></a>

	<a href="?a=reservas_solicitadas"><div class="cardInfo">
		<div class="conteudoCard">
			<i class="fas fa-calendar amarelo"></i>
			<div>
				<h3><?= $totais['PEDIDOS'] ?></h3>
				<p>Total de Solicitações de Reserva</p>
			</div>
		</div>
	</div></a>
</section>
<section id="graficos">
	<main style="grid-row: 1/3">
		<select id="selecionaGrafico">
			<option value="0">Semana Atual</option>
			<option value="1">Últimos meses</option>
			<option value="2">Últimos anos</option>
		</select>
		<div id="graficoMovientacoes"></div>
	</main>
	<main id="graficoReservas" style="grid-row: 1/2; grid-column: 2/2"></main>
	<main id="graficoUsuarios"></main>
</section>
<?php else: ?>
<section id="quartos">
	<div style="display: flex; justify-content: space-between; align-items: center;">
		<h3>Quartos Disponíveis para Reserva:</h3>

		<form method="POST" action="?a=quartos_disponiveis" id="pesquisa">
			<input type="hidden" name="min" value="0">

			<div id="filtros">
				<div>
					<label>Tipos:</label>
					<select name="tipo" class="status">
						<option value="T">Todos</option>
						<?php foreach($tipos_quarto as $tipo_quarto): ?>
						<option value="<?= $tipo_quarto->id_tipo_quarto ?>"><?= $tipo_quarto->nome_tipo ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<input type="submit" id="btnPesquisa" hidden>
		</form>
	</div>

	<section id="containerQuartos">
		<form method="POST" action="?a=quartos_disponiveis" id="formCarrega" class="formularioCarrega">
			<input type="hidden" name="min" value="0">
			<input type="hidden" name="tipo" value="T">
			<button type="submit" class="btnIniciaCarregamento"><i class="fas fa-plus"></i></button>
		</form>
	</section>	
</section>
<?php endif; ?>

<section id="msgFixo"></section>

<script type="text/javascript" src="../../public/assets/JS/libs/jquery.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/genericos.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_notificacao.js" defer></script>
<?php if($dadosUser['ACESSO'] === 'C'): ?> 
<script type="text/javascript" src="../../public/assets/JS/painel_inicio.js" defer></script>
<?php else: ?>
<script type="text/javascript" src="../../public/assets/JS/libs/apexcharts.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_inicio_admin.js" defer></script>
<?php endif; ?>
