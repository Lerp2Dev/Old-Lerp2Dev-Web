<?php

echo '
<div class="menu_block center">
	<div>Estadísticas</div>
		<div>
			<div>Work in progress...</div>
		</div>
	</div>
	<div class="menu_block center">
		<div style="display:none;">Miembros de administración</div>
		<div style="border: 0px;">
			<div class="caption">Administradores</div>
			<div class="sep"></div>
			<div>Work in progress...</div>
			<div class="sep"></div>
			<div class="caption">Moderadores</div>
			<div class="sep"></div>
			<div>Work in progress...</div>
		</div>
	</div>
	<div class="menu_block center">
		<div>Actividad de administración</div>
		<div>
			<div>Work in progress...</div>
		</div>
	</div>
</td>
<td>
	<div class="menu_block lateral">
		<div>Bloc de notas</div>
		<div>
			<div>
				<form id="admin_notepad" style="position:relative;"> <!--- onkeypress="if(event.keyCode == 13) return manageNotepad(this);" //Por el momento no es necesario -->
					<img src="./images/notepad-yellow.png" />
					<textarea class="notepad" name="notepad_text">'.SettingsManager::get('notepad').'</textarea>
					<center>
					    <input type="button" value="Guardar cambios" onclick="manageNotepad(this.parentNode.parentNode)" />
					</center>
				</form>
			</div>
		</div>
	</div>
	<div class="menu_block lateral">
		<div>Buscar un usuario</div>
		<div>
			<div>Work in progress...</div>
		</div>
	</div>';

?>