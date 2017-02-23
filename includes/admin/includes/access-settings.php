<?php

$reg_setts = unserialize(SettingsManager::get('register_settings'));

echo '
<h1>Ajustes de acceso a nuevos usuarios</h1>
<div class="sep"></div>
<h2>Opciones de registro</h2>
<div class="sep"></div>
<form id="admin_register_settings">
	<label><input type="radio" name="reg_opt" value="0" '.((int)$reg_setts['reg_access_opt'] === 0 ? 'checked' : '').'> Sin confirmación</label><br>
	<label><input type="radio" name="reg_opt" value="1" '.((int)$reg_setts['reg_access_opt'] === 1 ? 'checked' : '').'> Validación por correo</label><br>
	<label><input type="radio" name="reg_opt" value="2" '.((int)$reg_setts['reg_access_opt'] === 2 ? 'checked' : '').'> Validación por admin</label><br>
	<label><input type="radio" name="reg_opt" value="3" '.((int)$reg_setts['reg_access_opt'] === 3 ? 'checked' : '').'> Acceso denegado (no se adminten nuevos registros)</label><br>
	<label><input type="checkbox" name="reg_captcha"> Captcha en el registro</label>
	<div id="val_mail_opts" style="display:none;">
		Límite de tiempo de validación de un registro:<br>
		<input name="validation_duration" type="number" min="24" max="720" value="1" /> horas
	</div>
	<div id="no-confirm_opts" style="display:none;">
		<label><input type="checkbox" name="auto_login"> Logeo automático al finalizar registro</label>
	</div>
	<label style="display:block;"><input type="checkbox" name="reg_cmail"> Confirmación de correo necesaria al registrarse</label>
	<center>
	    <!--- <input type="button" value="Guardar cambios" onclick="aSendForm(this.parentNode.parentNode)" /> -->
	    <input type="submit" value="Guardar cambios" />
	</center>
	<script>
		var opts1 = document.currentScript.parentNode.querySelectorAll("input[type=\'radio\']"), x = 0;
		for(; x < opts1.length; ++x)
			opts1[x].setAttribute("onchange", "valMailOpts(this)");
	</script>
</form>
<div class="sep"></div>
<h2>Opciones de logeo</h2>
<div class="sep"></div>
<form id="admin_login_settings">
	<label><input type="radio" name="log_opt" value="0"> Sin número máximo de intentos</label><br>
	<label><input type="radio" name="log_opt" value="1"> Con un número máximo de intentos</label><br>
	<label><input type="radio" name="log_opt" value="2"> Acceso denegado (los usuarios no se pueden logear)</label><br>
	<div id="max_log_tries_opts" style="display:none;">
		Máximos números de intento de logeo:<br>
		<input name="max_tries" type="number" min="1" max="10" value="3" /> intentos<br>
		<label><input type="checkbox" onchange="loginCaptchaOpts(this)"> Captcha al exceder el número de intentos</label><br>
		<div id="login_captcha_opts" style="display:none;">
			Tiempo de espera después de exceder el nº máximo de intentos:<br>
			<input name="cooldown_exceeded_tries" id="cool_exc_trs" type="number" min="5" max="3600" value="1" /> minutos
		</div>
	</div>
	<center>
	    <!--- <input type="button" value="Guardar cambios" onclick="aSendForm(this.parentNode.parentNode)" /> -->
	    <input type="submit" value="Guardar cambios" />
	</center>
	<script>
		var opts2 = document.currentScript.parentNode.querySelectorAll("input[type=\'radio\']"), y = 0;
		for(; y < opts2.length; ++y)
			opts2[y].setAttribute("onchange", "maxLogTriesOpts(this)");
	</script>
</form>';

?>