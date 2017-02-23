<?php

$setts = unserialize(SettingsManager::get('register_settings'));

if((int)$setts['reg_access_opt'] !== 3) {
	echo '
	<center>
		<form id="register">
			<h4>Usuario:</h4>
			<input name="username" type="text" maxlength="20" />
			<br>
			<h4>Contraseña:</h4>
			<input name="password" type="password" />
			<br>
			<h4>Confirme contraseña:</h4>
			<input name="pass_confirm" type="password" />
			<br>
			<h4>Email:</h4>
			<input name="email" type="text" />
			<br>
			<h4>Captcha:</h4>
			<img src="./c/captcha.php"/><br>
			<input type="text" name="vercode" style="margin-bottom:10px;" />
			<br>
			<!--- <input type="button" value="¡Registrarse!" style="margin-bottom:10px;" onclick="sendForm(this.parentNode)" /> -->
			<input type="submit" value="¡Registrarse!" />
		</form>
	</center>';
} else
	echo '<center><h1>No se permiten nuevos registros.</h1></center>';