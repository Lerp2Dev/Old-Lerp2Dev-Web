<?php

echo '
<p class="intro">
	'.Lang::$lang->text["intro"].'
</p>
<center>
	<!--- <div class="sep"></div>
	<a href="http://sykoland.com"><img src="http://i.imgur.com/DYKmBJ1.png" style="width: 660px; height: 100px;" /></a>
	<p class="small">'.Lang::$lang->text["sykoland_click"].'</p>
	<div class="sep"></div>
	<table>
		<tr>
			<td>
				<img src="./images/icons/download.png" />
			</td>
			<td>
				<p class="big">'.ucfirst(Lang::$lang->text["download"]).' Terraria 1.3.0.6 ('.Lang::$lang->text["using"].' Adf.ly)<br><a target="_blank" href="http://adf.ly/1LA4Vu">[Mediafire]</a> <a target="_blank" href="http://adf.ly/1L9wyR">[Mega]</a></p>
				<p class="small">'.Lang::$lang->text["ddownload"].' <a target="_blank" href="http://www.mediafire.com/download/6n2k3fc7ymgh21b/Terraria+1.3.0.6.rar">[Mediafire]</a> <a target="_blank" href="https://mega.co.nz/#!e4RV2ZLD!0XxyAozgRDXN91GHtRcjmYZ1U1dRfHxTeh80mTy5nmw">[Mega]</a></p>
				'.Lang::$lang->text["terraria_overs"].' <a target="_blank" href="http://pastebin.com/sTRzsNnC">http://pastebin.com/sTRzsNnC</a><br>
				<a target="_blank" href="https://www.virustotal.com/es/file/c843411e713949559dcf8f8f58576251b23b285bd49a37f20764e0b33605c998/analysis/1435877353/">Virustotal.com Scan</a>
			</td>
		</tr>
	</table>
	<br> -->
	<div class="sep"></div>';
	if(!isset($_COOKIE["hideAd"]))
		echo '<div class="msg ad" style="position:relative;text-align:left;">
			<img src="./images/icons/close.png" style="position:absolute;right:3px;top:3px;" onclick="hideAd(this.parentNode);" />
			<h2 style="display:inline;">Aviso:</h2> El desarrollo de la página web sigue en pie, se ha realizado una actualización con fecha xx/03/2016<br>
			Los próximos cambios podrán ser vistos aquí: <b><a href="index.php?action=preview&id=1">Ver changelog web</a></b>; mientras un sistema de Blog es realizado, los nuevos cambios se irán anunciando ahí.<br>
			Por último, cabe de decir que la descarga de Terraria se ha movido al banner superior de esta ventana.<br>
			<u>Un saludo del equipo de Lerp2Dev, atentamente Ikillnukes.</u>
			<br>
			<center><b onclick="hideAd(this.parentNode.parentNode, true);">No mostrar más</b></center>
		</div>
		<div class="sep"></div>';	echo '<table style="width: 700px;">
		<tr>
			<td>
				<script id="cid0020000096170212519" data-cfasync="false" async src="//st.chatango.com/js/gz/emb.js" style="width: 500px;height: 400px;">{"handle":"lerp2dev","arch":"js","styles":{"a":"c9ba47","b":100,"c":"000000","d":"000000","h":"ffffff","k":"c9ba47","l":"c9ba47","m":"cc9933","p":"10","q":"c9ba47","r":100,"sbc":"948934","fwtickm":1}}</script>
			</td>
			<td style="font-size: 14px;">
				<div class="rules">
					'.Lang::$lang->text["chat_rules"].'
				</div>
			</td>
		</tr>
	</table>
	<!--- <div class="sep"></div>
	<a href="http://sykoland.com"><img src="http://minecraft-mp.com/regular-banner-90790.png" /></a>
	<p class="small">'.Lang::$lang->text["sykoland_click"].'</p> -->
	<div class="sep"></div> 
	<div class="copyright">'.Lang::$lang->text["copyright"].'</div>
</center>';