<section class="modal" id="modalSenha">
	<main class="deletar">
		<header style="position: absolute; width: 100%; top: 0px; left: 0px;">
			<h5><?= APP_NAME ?> - Senha</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=deletar_conta" id="deletaConta" style="margin-top: 40px;">
			<h5>Senha Atual:</h5>
			<input type="password" name="senha" class="inputsForm" placeholder="Senha">
			<span class="spanAlerta">Por favor digíte sua senha</span>

			<div class="contBtnModal">
				<input type="submit" value="Deletar Conta">
			</div>
		</form>
	</main>
</section>

<section id="configuracoes">
	<details>
		<summary>Perfil</summary>
		<main class="containerConf">
			<header class="headerConf">
				<h5>Imagem de Perfil</h5>
			</header>
			<form method="POST" action="?a=alterar_imagem_perfil" class="formularioExecuta mainConf" enctype="multipart/form-data">
				<div class="msg">
					<p class="textMsg"></p>
					<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
				</div>

				<input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE ?>">

				<h5>*Deve ser uma imagem jpeg, png ou gif com tamanho menor ou igual a 1MB:</h5>
				<input type="file" name="imagem" accept="image/*" class="inputsForm">
				<span class="spanAlerta">Por favor selecione uma imagem!</span>
				
				<div class="inp">
					<input type="submit" value="Salvar Alterações">
				</div>
			</form>
		</main>
	</details>
	<details>
		<summary>Conta</summary>
		<article>
			<main class="containerConf">
				<header class="headerConf">
					<h5>Dados Pessoais</h5>
				</header>
				<form method="POST" action="?a=editar_dados_pessoais" class="formularioExecuta mainConf" data-edit="true">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<h5>CPF:</h5>
					<input type="text" name="cpf" class="inputsForm" placeholder="CPF" value="<?= $dadosUser['CPF'] ?>">
					<span class="spanAlerta">Por favor digíte seu CPF</span>

					<div class="dividir">
						<div>
							<h5>Nome:</h5>
							<input type="text" name="nome" class="inputsForm" placeholder="Nome" value="<?= $dadosUser['NOME'] ?>">
							<span class="spanAlerta">Por favor digíte seu nome</span>
						</div>
						<div>
							<h5>Sobrenome:</h5>
							<input type="text" name="sobrenome" class="inputsForm" placeholder="Sobrenome" value="<?= $dadosUser['SOBRENOME'] ?>">
							<span class="spanAlerta">Por favor digíte seu sobrenome</span>
						</div>
					</div>

					<h5>E-Mail:</h5>
					<input type="text" name="email" class="inputsForm" placeholder="E-Mail" value="<?= $dadosUser['EMAIL'] ?>">
					<span class="spanAlerta">Por favor digíte seu E-Mail</span>
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>

			<main class="containerConf">
				<header class="headerConf">
					<h5>Alterar Senha:</h5>
				</header>
				<form  method="POST" action="?a=alterar_senha" class="formularioExecuta mainConf">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<h5>Senha Atual:</h5>
					<input type="password" name="senha" class="inputsForm" placeholder="Senha Atual">
					<span class="spanAlerta">Por favor digíte sua senha atual</span>

					<h5>Nova Senha:</h5>
					<input type="password" name="nsenha" class="inputsForm" placeholder="Nova Senha">
					<span class="spanAlerta">Por favor digíte sua nova senha</span>

					<h5>Repetir Nova Senha:</h5>
					<input type="password" name="rnsenha" class="inputsForm" placeholder="Nova Senha">
					<span class="spanAlerta">Por favor repita sua nova senha</span>
					
					<div class="inp">
						<input type="submit" value="Alterar Senha">
					</div>
				</form>
			</main>
		</article>	
	</details>

	<?php if($dadosUser['ACESSO'] === 'A'): ?>
	<details>
		<summary>Site</summary>
		<article>
			<main class="containerConf">
				<header class="headerConf">
					<h5>Endereço do hotel:</h5>
				</header>
				<form method="POST" action="?a=alterar_endereco" class="formularioExecuta mainConf" data-edit="true">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<h5>CEP:</h5>
					<input type="text" name="cep" id="cep" placeholder="CEP" class="inputsForm" maxlength="8" value="<?= $config->address->postal_code ?>">
					<span class="spanAlerta">Por favor digíte o cep do hotel</span>

					<div class="divide">
						<div>
							<h5>Logradouro:</h5>
							<input type="text" name="logradouro" id="logradouro" placeholder="Logradouro" class="inputsForm" value="<?= $config->address->street ?>">
							<span class="spanAlerta">Por favor digíte o logradouro do hotel</span>
						</div>

						<div>
							<h5>Número:</h5>
							<input type="number" name="numero" id="numero" placeholder="Número" class="inputsForm" value="<?= $config->address->number ?>">
							<span class="spanAlerta">Por favor digíte o número do hotel</span>
						</div>
					</div>	

					<h5>Bairro:</h5>
					<input type="text" name="bairro" id="bairro" placeholder="Bairro" class="inputsForm" value="<?= $config->address->district ?>">
					<span class="spanAlerta">Por favor digíte o bairro do hotel</span>

					<div class="divide">
						<div>
							<h5>Estado:</h5>
							<select name="estado" id="estado"></select>
						</div>

						<div>
							<h5>Cidade:</h5>
							<select name="cidade" id="cidade"></select>
						</div>
					</div>
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>
			<main class="containerConf">
				<header class="headerConf">
					<h5>Redes Sociais</h5>
				</header>
				<form method="POST" action="?a=alterar_redes_sociais" class="formularioExecuta mainConf" data-edit="true">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<h5>Facebook:</h5>
					<input type="text" name="facebook" placeholder="Facebook" value="<?= $config->social->facebook ?>">

					<h5>Instagram:</h5>
					<input type="text" name="instagram" placeholder="Instagram" value="<?= $config->social->instagram ?>">

					<h5>Twitter:</h5>
					<input type="text" name="twitter" placeholder="Twitter" value="<?= $config->social->twitter ?>">
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>
			<main class="containerConf">
				<header class="headerConf">
					<h5>Logo do Sistema</h5>
				</header>
				<form method="POST" action="?a=alterar_logo_sistema" class="formularioExecuta mainConf" enctype="multipart/form-data">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE ?>">

					<h5>Deve ser uma imagem jpeg, png ou gif com tamanho menor ou igual a 1MB:</h5>
					<input type="file" name="imagem" accept="image/*" class="inputsForm">
					<span class="spanAlerta">Por favor selecione sua nova logo!</span>
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>
			<main class="containerConf">
				<header class="headerConf">
					<h5>Nome do Sistema</h5>
				</header>
				<form method="POST" action="?a=alterar_nome_sistema" class="formularioExecuta mainConf" data-edit="true">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<h5>Nome do Sistema:</h5>
					<input type="text" name="nome" class="inputsForm" placeholder="Nome do Site" value="<?= $config->name ?>">
					<span class="spanAlerta">Por favor digíte o novo nome do sistema</span>
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>

			<main class="containerConf" style="grid-column: 1/3">
				<header class="headerConf">
					<h5>Imagens do Carrossel:</h5>
				</header>
				<form method="POST" action="?a=alterar_carrossel_sistema" class="formularioExecuta mainConf" enctype="multipart/form-data">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<div id="imgsContDesc" class="imagemCont">
						<?php for($i = 0; $i < count($config->site->carrossel->imgs); $i++): ?>
						<div>
							<label for="input<?= $i ?>"><img id="img<?= $i ?>" src="../assets/IMGS/CARROSSEL/<?= $config->site->carrossel->imgs[$i]->img ?>"></label>
							<textarea id="txt<?= $i ?>" placeholder="Descrição" name="descricao[]" class="inputsForm"><?= $config->site->carrossel->imgs[$i]->text ?></textarea>
							<input type="file" onchange="inputAddImg()" accept="image/*" name="imgs[]" id="input<?= $i ?>" data-index="<?= $i ?>" data-add="false">
							<button class="bg-vermelho" type="button" onclick="removeImg(<?= $i ?>)"><i class="fas fa-trash-alt"></i></button>
						</div>
						<?php endfor; ?>
						<div>
							<label for="input<?= $i ?>"><img id="img<?= $i ?>" src="../assets/IMGS/add.png"></label>
							<textarea id="txt<?= $i ?>" placeholder="Descrição" name="descricao[]"></textarea>
							<input type="file" onchange="inputAddImg()" accept="image/*" name="imgs[]" id="input<?= $i ?>" data-index="<?= $i ?>" data-add="true">
							<button class="bg-vermelho" type="button" onclick="removeImg(<?= $i ?>)"><i class="fas fa-trash-alt"></i></button>
						</div>
					</div>

					<input type="hidden" id="removidos" name="removidos">
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>

			<main class="containerConf" style="grid-column: 1/3">
				<header class="headerConf">
					<h5>Imagens de Destaque:</h5>
				</header>
				<form method="POST" action="?a=alterar_imgs_destaque_sistema" class="formularioExecuta mainConf" enctype="multipart/form-data">
					<div class="msg">
						<p class="textMsg"></p>
						<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
					</div>

					<div class="imagemCont">
						<?php for($i = 0; $i < count($config->site->imagens); $i++): ?>
						<div>
							<label for="inputs<?= $i ?>"><img id="imgs<?= $i ?>" src="../assets/IMGS/DESTAQUE/<?= $config->site->imagens[$i] ?>"></label>
							<input type="file" accept="image/jpeg,image/png" class="destaque" name="imgs[]" id="inputs<?= $i ?>" data-index="<?= $i ?>">
						</div>
						<?php endfor; ?>
					</div>
					
					<div class="inp">
						<input type="submit" value="Salvar Alterações">
					</div>
				</form>
			</main>
		</article>
	</details>
	<?php endif; ?>

	<details>
		<summary>Zona de Perigo</summary>

		<main class="containerConf" id="zonaPerigo">
			<header class="headerConf">
				<h5>Zona de Perigo:</h5>
			</header>
			<form method="POST" action="javascript:void(0)" class="mainConf">
				<h5>Depois de excluir sua conta, não há como voltar atrás. Por favor, esteja certo.</h5>

				<div>
					<button type="submit" id="iniDeletarConta">Deletar minha conta <i class="fas fa-trash-alt"></i></button>
				</div>
			</form>
		</main>
	</details>
</section>

<section id="msgFixo"></section>

<script type="text/javascript" src="../../public/assets/JS/libs/jquery.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/genericos.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_notificacao.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_configuracoes.js" defer></script>