<section class="modal" id="modalVis">
	<main>
		<header>
			<h5><?= APP_NAME ?> - Mais Informações</h5>
			<span class="modalFecha">&times;</span>
		</header>
		<div class="informacoes"></div>
		<div class="btnsModal">
   			<form action="?a=finaliza_pedido_reserva" method="POST" onsubmit="executaFormCarrega(finalizaPedido, loadingFinalizaPedido, null)">
   				<input type="hidden" name="status" value="N">
   				<input type="hidden" name="id_pedido_reserva" class="id_pedido_modal" value="">
   				<button type="submit">Recusar <i class="fas fa-times vermelho"></i></button>
   			</form>
   			<form action="?a=finaliza_pedido_reserva" method="POST" onsubmit="executaFormCarrega(finalizaPedido, loadingFinalizaPedido, null)">
   				<input type="hidden" name="status" value="A">
   				<input type="hidden" name="id_pedido_reserva" class="id_pedido_modal" value="">
   				<button type="submit">Aceitar <i class="fas fa-check verde"></i></button>
   			</form>
   		</div>
	</main>
</section>

<section id="quartos">
	<h3>Reservas Solicitadas:</h3>

	<section id="containerQuartos">
		<form method="POST" action="?a=pesquisa_solicitacoes_reservas" id="formCarrega" class="formularioCarrega">
			<input type="hidden" name="min" value="0">
			<button type="submit" class="btnIniciaCarregamento"><i class="fas fa-plus"></i></button>
		</form>
	</section>
</section>

<section id="msgFixo"></section>

<script type="text/javascript" src="../../public/assets/JS/libs/jquery.js"></script>
<script type="text/javascript" src="../../public/assets/JS/genericos.js"></script>
<script type="text/javascript" src="../../public/assets/JS/painel_notificacao.js"></script>
<script type="text/javascript" src="../../public/assets/JS/painel_reservas_solicitadas.js"></script>