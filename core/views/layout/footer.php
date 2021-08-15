<footer id="footer_pagina">
	<div class="destaque" id="informacoes">
		<div>
			<h4>Redes Socias:</h4>
			<ul>
				<li><a href="<?= FACEBOOK ?>" target="_blank"><i class="fab fa-facebook"></i> Facebook</a></li>
				<li><a href="<?= INSTAGRAM ?>" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
				<li><a href="<?= TWITTER ?>" target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
			</ul>
		</div>

		<div>
			<h4>Endereço:</h4>
			<ul>
				<li><i class="fas fa-location-arrow"></i> <?= $postal_code ?></li>
				<li><i class="fas fa-map-marked-alt"></i> <?= ADDRESS_STREET ?> - <?= ADDRESS_NUMBER ?>, <?= ADDRESS_DISTRICT ?>, <?= ADDRESS_CITY ?></li>
				<li><i class="fas fa-map-marker-alt"></i> <?= ADDRESS_COUNTRY_CODE ?> - <?= ADDRESS_STATE ?></li>
			</ul>
		</div>

		<div>
			<h4>Contato:</h4>
			<ul>
				<li><i class="fas fa-phone-alt"></i> <?= $phone ?></li>
				<li><i class="fas fa-envelope"></i> <?= ADDRESS_EMAIL ?></li>
			</ul>
		</div>
	</div>
	
	<div class="destaque"><p><?= APP_NAME ?> - <?= date('Y') ?> | &copy; Todos os direitos reservados</p></div>
</footer>