<section class="modal" id="modalVis">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Mais Informações</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<div class="informacoes"></div>
	</main>
</section>

<section class="modal" id="modalEdit">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Editar Reserva</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=editar_reserva" class="formularioExecuta" data-edit="true">
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<input type="hidden" name="id_reserva">

			<h5>Número do Quarto:</h5>
			<input type="number" name="numero_quarto" class="inputsForm" placeholder="Número">
			<span class="spanAlerta">Por favor digíte o número do quarto</span>

			<h5>CPF do Usuário:</h5>
			<input type="text" name="cpf_usuario" class="inputsForm" placeholder="CPF do Usuário">
			<span class="spanAlerta">Por favor digíte o CPF do usuário</span>

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

			<h5>Status da Reserva:</h5>
			<select name="status">
				<option value="R">Reservado</option>
				<option value="P">Pagamento</option>
				<option value="C">Concluido</option>
			</select>
			
			<div class="contBtnModal">
				<input type="submit" value="Salvar Edições">
			</div>
		</form>
	</main>
</section>

<section id="quartos">
	<form method="POST" action="?a=pesquisa_reservas" id="pesquisa">
		<input type="hidden" name="min" value="0">
		
		<div id="barraPesquisa">
			<input type="number" name="pesquisa" placeholder="Número do quarto...">
			<button type="submit" id="btnPesquisa"><i class="fas fa-search"></i></button>
		</div>
		<div id="filtros">
			<div>
				<label>Data da Reserva:</label>
				<input type="datetime-local" name="data_reserva" class="change">
			</div>
			<div>
				<label>Data de Encerramento:</label>
				<input type="datetime-local" name="data_encerrar" class="change">
			</div>
			<div>
				<label>Status:</label>
				<select name="status" class="change">
					<option value="T">Todos</option>
					<option value="R">Reservado</option>
					<option value="P">Pagamento</option>
					<option value="C">Concluido</option>
				</select>
			</div>
		</div>
	</form>

	<section id="containerQuartos">
		<form method="POST" action="?a=pesquisa_reservas" id="formCarrega" class="formularioCarrega">
			<input type="hidden" name="min" value="0">
			<input type="hidden" name="data_encerramento" value="">
			<input type="hidden" name="data_reserva" value="">
			<input type="hidden" name="status" value="T">
			<input type="hidden" name="pesquisa" value="">
			<button type="submit" class="btnIniciaCarregamento"><i class="fas fa-plus"></i></button>
		</form>
	</section>
</section>

<section id="msgFixo"></section>

<script type="text/javascript" src="../../public/assets/JS/libs/jquery.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/genericos.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_notificacao.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_reservas.js" defer></script>