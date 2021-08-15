<section class="modal" id="modalEsqueciSenha">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Esqueci minha senha</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<form method="POST" action="?a=envia_recupera_senha" class="formularioExecuta" data-ajax="true">
			<div class="msg">
				<p class="textMsg"></p>
				<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
			</div>

			<input type="email" name="email" placeholder="E-Mail" maxlength="100" class="inputsForm">
			<span class="spanAlerta">Digíte seu E-Mail</span>

			<div class="contBtnModal">
				<input type="submit" value="Recuperar Senha">
			</div>
		</form>
	</main>
</section>

<section id="principal">
	<main id="quartos">
		<h3>Temos os melhores quartos pra você:</h3>

		<div id="imgQuartos">
			<?php foreach($imgs as $img): ?>
			<div><img src="./assets/IMGS/DESTAQUE/<?= $img ?>"></div>
			<?php endforeach; ?>
		</div>
	</main>
	<main id="login" class="formulariosSite" data-ajax="false">
		<div class="formularios backLeft fundo-two">
			<div class="containerInfomation" id="imgLoginFundo">
				<img src="../public/assets/IMGS/login.png">
			</div>
			<form id="formLogin" method="POST" action="?a=login">
				<h3>Login:</h3>

				<?php if(isset($_SESSION['msg'])): ?>
				<main class="msg erro" style="display: flex;">
					<p class="textMsg"><?= $_SESSION['msg'] ?></p>
					<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
				</main>
				<?php unset($_SESSION['msg']); ?>
				<?php endif; ?>

				<input type="email" name="email" placeholder="E-Mail" maxlength="100" class="inputsForm">
				<span class="spanAlerta">Digíte seu E-Mail</span>
				<input type="password" name="senha" placeholder="Senha" maxlength="100" class="inputsForm">
				<span class="spanAlerta">Digíte sua senha</span>

				<p><a href="" id="esqueciSenha">Esqueci minha senha</a> | <a href="#cadastro">Não possuo conta</a></p>

				<div class="gSubmit"><input type="submit" value="Entrar"></div>
			</form>
		</div>
	</main>

	<main id="cadastro" class="formulariosSite">
		<div class="formularios backRight fundo-three" style="grid-template-columns: 1fr 500px;">
			<form id="formCad" method="POST" action="?a=cadastro" data-ajax="true">
				<h3>Cadastrar-se</h3>

				<main class="msg">
					<p class="textMsg"></p>
					<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
				</main>

				<input type="text" name="cpf" placeholder="CPF" maxlength="11" class="inputsForm">
				<span class="spanAlerta">Digíte seu CPF</span>

				<div class="divide">
					<div>
						<input type="text" name="nome" placeholder="Nome" maxlength="50" class="inputsForm">
						<span class="spanAlerta">Digíte seu nome</span>
					</div>
					<div>
						<input type="text" name="sobrenome" placeholder="Sobrenome" maxlength="100" class="inputsForm">
						<span class="spanAlerta">Digíte seu sobrenome</span>
					</div>
				</div>

				<input type="email" name="email" placeholder="E-Mail" maxlength="100" class="inputsForm">
				<span class="spanAlerta">Digíte um E-Mail válido</span>

				<div class="divide">
					<div>
						<input type="password" name="senha" placeholder="Senha" maxlength="100" class="inputsForm">
						<span class="spanAlerta">Digíte sua senha</span>
					</div>
					<div>
						<input type="password" name="rsenha" placeholder="Repetir Senha" maxlength="100" class="inputsForm">
						<span class="spanAlerta">Repita a senha digitada</span>
					</div>
				</div>

				<p><a href="#login">Já possuo conta</a></p>
				
				<div class="gSubmit"><input type="submit" value="Cadastrar-se"></div>
			</form>
			<div id="imgCadFundo">
				<img src="../public/assets/IMGS/cadastro.png">
			</div>
		</div>
	</main>

	<main id="contatos" class="formulariosSite">
		<div class="formularios backLeft fundo-one" id="containerForm">
			<div id="imgContatoFundo">
				<img src="../public/assets/IMGS/contato.png">
			</div>
			<form method="POST" action="?a=contato" data-ajax="true">
				<h3>Contate-nos:</h3>

				<main class="msg">
					<p class="textMsg"></p>
					<p class="btnFechaMsg" onclick="fechaMensagem(this.parentNode)">&times;</p>
				</main>

				<input type="text" name="nome" placeholder="Nome" class="inputsForm">
				<span class="spanAlerta">Digíte o seu nome</span>

				<input type="email" name="email" placeholder="E-Mail" class="inputsForm">
				<span class="spanAlerta">Digíte um E-Mail válido</span>

				<input type="text" name="assunto" placeholder="Assunto" class="inputsForm">
				<span class="spanAlerta">Informe o assundo da mensagem</span>

				<textarea name="mensagem" placeholder="Mensagem" class="inputsForm"></textarea>
				<span class="spanAlerta">Digíte a mensagem que deseja enviar</span>
				
				<div class="gSubmit"><button type="submit">Enviar</button></div>
			</form>
		</div>
	</main>
</section>

<script type="text/javascript" src="../public/assets/JS/libs/jquery.js"></script>
<script type="text/javascript" src="../public/assets/JS/genericos.js"></script>
<script type="text/javascript" src="../public/assets/JS/main_inicio.js"></script>