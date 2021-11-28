<?php
echo '<footer id="rodape">

	<div id="div_footer">
		<div id="redes">
			<div class="icon">
				<a href= "http://api.whatsapp.com/send?1=pt_BR&phone=5532998061821&text=Olá%20*Rolf%20Modas*" target="_blank">
					<img class="icon" title="Whats" src="../_imagens/icon_whats.png"/>
					<span class="redes_desc">Mande-nos uma mensagem no whatsapp</span>
				</a>
			</div>
			<div class="icon">
				<a href= "https://www.facebook.com/rolfmodas" target="_blank">
					<img class="icon" title="Facebook" src="../_imagens/icon_fb.png"/>
					<span class="redes_desc">www.facebook.com/rolfmodas</span>
				</a>
			</div>
			<div class="icon">
				<a href= "../Usuario/fale_conosco.php" target="_blank">
					<img class="icon" title="E-mail" src="../_imagens/icon_email.png"/>
					<span class="redes_desc">rolf@rolfmodas.com.br</span>
				</a>
			</div>';
			// <div class="icon">
			// 	<a href="tel:(32) 35711010">
			// 		<img title="Telefone" onclick="Telefone()" class="icon" src="../_imagens/icon_tele.png"/>
			// 	</a>
			// 	<span class="redes_desc">(32) 3571-1010</span>
			// </div>
			echo '
			<div class="icon">
				<a href= "https://www.instagram.com/rolfmodas/" target="_blank">
					<img class="icon" title="Instagram" src="../_imagens/icon_instagram.png"/>
					<span class="redes_desc">www.instagram.com/rolfmodas/</span>
				</a>
			</div>
		</div>
		<div id="secure">
			<script language="JavaScript" type="text/javascript">
			TrustLogo("https://rolfmodas.com.br/_imagens/comodo_secure_seal_100x85_transp.png", "CL1", "none");
			</script>
			<a  href="https://www.positivessl.com/" id="comodoTL">Positive SSL</a>
		</div>
		<div id="copy">
			<p>Copyright&copy; 2016-'.date('Y').' - by Rolf Modas</p>
			<p id="local">Av. Raúl Soares, 65 - Centro - Rio Pomba/MG. CEP:36180-000 / CNPJ:19.556.708/0001-51</p>
		</div>
	</div>

</footer>';
?>