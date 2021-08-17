 defer<section class="modal" id="modalDeletaQuarto">
	<main class="deletar">
		<form method="POST" id="formDeleta" action="?a=deleta_quarto">
			<h4>Opa!, Você realamente deseja deletar este quarto?</h4><br>
			<input type="hidden" name="numero_quarto" id="numero_quarto_deleta">

			<div>
				<button class="cancelar" type="button" onclick="deletarQuartoModal(null)">Cancelar</button>
				<button type="submit">Sim!, Quero deletar</button>
			</div>
		</form>
	</main>
</section>

<section class="modal" id="modalAdd">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Adicionar novo quarto</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=cadastra_quarto" class="formularioExecuta">
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<h5>Tipo Quarto:</h5>
			<select name="tipo">
				<?php foreach($tipos_quarto as $tipo_quarto): ?>
				<option value="<?= $tipo_quarto->id_tipo_quarto ?>"><?= $tipo_quarto->nome_tipo ?></option>
				<?php endforeach; ?>
			</select>

			<div class="divide">
				<div>
					<h5>Número do Quarto:</h5>
					<input type="number" name="numero_quarto" class="inputsForm" placeholder="Número">
					<span class="spanAlerta">Por favor digíte o número do quarto</span>
				</div>
				<div>
					<h5>Andar:</h5>
					<input type="number" name="andar" class="inputsForm" placeholder="Andar">
					<span class="spanAlerta">Por favor digíte o andar do quarto</span>
				</div>
			</div>

			<h5>Preço por hora:</h5>
			<input type="text" name="preco" class="inputsForm" placeholder="Preço por hora">
			<span class="spanAlerta">Por favor informe o preço por hora do quarto</span>

			<div class="contBtnModal">
				<input type="submit" value="Adicionar Quarto">
			</div>
		</form>
	</main>
</section>

<section class="modal" id="modalEdit">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Editar Quarto</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=editar_quarto" class="formularioExecuta" data-edit="true">
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<input type="hidden" name="numero_antigo">

			<h5>Tipo Quarto:</h5>
			<select name="tipo">
				<?php foreach($tipos_quarto as $tipo_quarto): ?>
				<option value="<?= $tipo_quarto->id_tipo_quarto ?>"><?= $tipo_quarto->nome_tipo ?></option>
				<?php endforeach; ?>
			</select>

			<div class="divide">
				<div>
					<h5>Número do Quarto:</h5>
					<input type="number" name="numero_quarto" class="inputsForm" placeholder="Número">
					<span class="spanAlerta">Por favor digíte o número do quarto</span>
				</div>
				<div>
					<h5>Andar:</h5>
					<input type="number" name="andar" class="inputsForm" placeholder="Andar">
					<span class="spanAlerta">Por favor digíte o andar do quarto</span>
				</div>
			</div>

			<h5>Status do Quarto:</h5>
			<select name="status">
				<option value="D">Disponível</option>
				<option value="I">Indisponível</option>
			</select>

			<h5>Preço por hora:</h5>
			<input type="text" name="preco" class="inputsForm" placeholder="Preço por hora">
			<span class="spanAlerta">Por favor informe o preço por hora do quarto</span>

			<div class="contBtnModal">
				<input type="submit" value="Salvar Edições">
			</div>
		</form>
	</main>
</section>

<section id="quartos">
	<form method="POST" action="?a=pesquisa_quartos" id="pesquisa">
		<div id="barraPesquisa">
			<input type="number" min="1" name="pesquisa" placeholder="Número do quarto...">
			<button type="submit" id="btnPesquisa"><i class="fas fa-search"></i></button>
		</div>
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
			<div>
				<label>Status:</label>
				<select name="status" class="status">
					<option value="T">Todos</option>
					<option value="D">Disponível</option>
					<option value="I">Indisponível</option>
				</select>
			</div>
		</div>
	</form>

	<section id="novo">
		<button id="btnAdd">Adicionar novo quarto <i class="fas fa-plus"></i></button>
	</section>

	<section id="containerQuartos">
		<form method="POST" action="?a=pesquisa_quartos" id="formCarrega" class="formularioCarrega">
			<input type="hidden" name="min" value="0">
			<input type="hidden" name="tipo" value="T">
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
<script type="text/javascript" src="../../public/assets/JS/painel_quartos.js" defer></script>