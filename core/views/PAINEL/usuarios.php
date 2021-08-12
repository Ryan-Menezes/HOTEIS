<section class="modal" id="modalVis">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Mais Informações</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<div class="informacoes"></div>
	</main>
</section>

<section class="modal" id="modalDeletaUsuario">
	<main class="deletar">
		<form method="POST" id="formDeleta" action="?a=deleta_usuario">
			<h4>Opa!, Você realmente deseja deletar este usuário?</h4><br>
			<input type="hidden" name="cpf" id="cpf_usuario_deleta">

			<div>
				<button class="cancelar" type="button" onclick="deletarUsuarioModal(null)">Cancelar</button>
				<button type="submit">Sim!, Quero deletar</button>
			</div>
		</form>
	</main>
</section>

<section class="modal" id="modalRecuperaUsuario">
	<main class="deletar">
		<form method="POST" id="formRecupera" action="?a=recupera_usuario">
			<h4>Opa!, Você realmente deseja recuperar este usuário?</h4><br>
			<input type="hidden" name="cpf" id="cpf_usuario_recupera">

			<div>
				<button class="cancelar" type="button">Cancelar</button>
				<button type="submit">Sim!, Quero Recuperar</button>
			</div>
		</form>
	</main>
</section>

<section class="modal" id="modalEdit">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Editar Usuário</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=editar_usuario" class="formularioExecuta" data-edit="true">
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<input type="hidden" name="cpf_antigo">
			<input type="hidden" name="email_antigo">

			<div class="divide">
				<div>
					<h5>Nome:</h5>
					<input type="text" name="nome" class="inputsForm" placeholder="Nome">
					<span class="spanAlerta">Por favor digíte o nome do usuário</span>
				</div>
				<div>
					<h5>Sobrenome:</h5>
					<input type="text" name="sobrenome" class="inputsForm" placeholder="Sobrenome">
					<span class="spanAlerta">Por favor digíte o sobrenome do usuário</span>
				</div>
			</div>

			<h5>CPF:</h5>
			<input type="text" name="cpf" class="inputsForm" placeholder="CPF">
			<span class="spanAlerta">Por favor informe o CPF do usuário</span>

			<h5>E-Mail:</h5>
			<input type="email" name="email" class="inputsForm" placeholder="E-Mail">
			<span class="spanAlerta">Por favor informe o E-Mail do usuário</span>

			<div class="divide">
				<div>
					<h5>Status do Usuário:</h5>
					<select name="status">
						<option value="B">Bloqueado</option>
						<option value="D">Desbloqueado</option>
					</select>
				</div>
				<div>
					<h5>Acesso:</h5>
					<select name="acesso">
						<option value="C">Cliente</option>
						<option value="A">Administrador</option>
					</select>
				</div>
			</div>
			
			<h5>Verificação da Conta:</h5>
			<select name="ativo">
				<option value="1">Verificada</option>
				<option value="0">Não Verificada</option>
			</select>	

			<div class="contBtnModal">
				<input type="submit" value="Salvar Edições">
			</div>
		</form>
	</main>
</section>

<section id="usuarios">
	<form method="POST" action="?a=pesquisa_usuarios" id="pesquisa">
		<div id="barraPesquisa">
			<input type="text" name="pesquisa" placeholder="Pesquisar usuário...">
			<button type="submit" id="btnPesquisa"><i class="fas fa-search"></i></button>
		</div>
		<div id="filtros">
			<div>
				<label>Situação da conta:</label>
				<select name="conta" class="status">
					<option value="T">Todos</option>
					<option value="1">Verificado</option>
					<option value="0">Não verificado</option>
				</select>
			</div>
			<div>
				<label>Status:</label>
				<select name="status" class="status">
					<option value="T">Todos</option>
					<option value="B">Bloqueado</option>
					<option value="D">Desbloqueado</option>
				</select>
			</div>
			<div>
				<label>Acesso:</label>
				<select name="acesso" class="status">
					<option value="T">Todos</option>
					<option value="C">Cliente</option>
					<option value="A">Administrador</option>
				</select>
			</div>
		</div>
	</form>

	<section id="usuariosContainer">
		<table>
			<thead>
				<tr>
					<th>Foto</th>
					<th>CPF</th>
					<th>Nome Completo</th>
					<th>Acesso</th>
					<th>Status</th>
					<th>Criado em</th>
					<th>Última atualização</th>
					<th>Deletado</th>
					<th>Opções</th>
				</tr>
			</thead>
			<tbody id="containerUsuarios">
				<tr>
					<td colspan="9">
						<form method="POST" action="?a=pesquisa_usuarios" id="formCarrega" class="formularioCarrega">
							<input type="hidden" name="min" value="0">
							<input type="hidden" name="conta" value="T">
							<input type="hidden" name="acesso" value="T">
							<input type="hidden" name="status" value="T">
							<input type="hidden" name="pesquisa" value="">
							<button type="submit" class="btnIniciaCarregamento"><i class="fas fa-plus"></i></button>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</section>
</section>

<section id="msgFixo"></section>

<script type="text/javascript" src="../../public/assets/JS/libs/jquery.js"></script>
<script type="text/javascript" src="../../public/assets/JS/genericos.js"></script>
<script type="text/javascript" src="../../public/assets/JS/painel_notificacao.js"></script>
<script type="text/javascript" src="../../public/assets/JS/painel_usuarios.js"></script>