 defer<section class="modal" id="modalPayment">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Pagamento</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<div id="contPag">
			<div id="ppplus"></div>
			<div id="btnPagar">
				<button id="btnPagarReserva">Pagar <i class="fas fa-credit-card"></i></button>
			</div>
		</div>
	</main>
</section>

<section class="modal" id="modalVis">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Mais Informações</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<div class="informacoes"></div>
	</main>
</section>

<section class="modal" id="modalCancelarSolicitacao">
	<main class="deletar">
		<form method="POST" id="formCancela" action="?a=cancela_solicitacao_reserva">
			<h4>Opa!, Você realmente deseja cancelar este pedido?</h4><br>
			<input type="hidden" name="id_pedido_reserva" id="id_pedido_reserva">

			<div>
				<button class="cancelar" type="button" onclick="cancelarSolicitcao(null)">Cancelar</button>
				<button type="submit">Sim!, Quero cancelar</button>
			</div>
		</form>
	</main>
</section>

<section class="modal" id="modalDeletaSolicitacao">
	<main class="deletar">
		<form method="POST" id="formDeleta" action="?a=cancela_solicitacao_reserva">
			<h4>Opa!, Você realmente deseja deletar este pedido?</h4><br>
			<input type="hidden" name="id_pedido_reserva" id="id_pedido_reserva_del">

			<div>
				<button class="cancelar" type="button" onclick="deletaSolicitacao(null)">Cancelar</button>
				<button type="submit">Sim!, Quero deletar</button>
			</div>
		</form>
	</main>
</section>

<section id="quartos">
	<h3>Reservas solicitadas por você:</h3>

	<section id="containerQuartos">
		<form method="POST" action="?a=meus_pedidos_reserva" id="formCarrega" class="formularioCarrega">
			<input type="hidden" name="min" value="0">
			<button type="submit" class="btnIniciaCarregamento"><i class="fas fa-plus"></i></button>
		</form>
	</section>

	<h3 style="margin-top: 20px;">Quartos reservados por você:</h3>

	<section id="containerQuartos" class="containerReservas">
		<form method="POST" action="?a=minhas_reservas_usuario" id="formCarregaReservas" class="formularioCarrega">
			<input type="hidden" name="min" value="0">
			<button type="submit" class="btnIniciaCarregamento"><i class="fas fa-plus"></i></button>
		</form>
	</section>
</section>

<section id="msgFixo"></section>

<script type="text/javascript" src="../../public/assets/JS/libs/jquery.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/libs/ppplusdcc.min.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/genericos.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/payment.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_notificacao.js" defer></script>
<script type="text/javascript" src="../../public/assets/JS/painel_minhas_reservas.js" defer></script>