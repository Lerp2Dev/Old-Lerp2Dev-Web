<?php

$id = (int)mysqli_escape_string(Database::conn(), @$_GET['id']);

$result = Query::run("SELECT * FROM users WHERE id = '$id'");

if(!isset($id)) die('No se puede cargar los datos del usuario sin una ID.');
if(!mysqli_num_rows($result)) 
	die('No se encuentra ningún proyecto por la ID solicitada.');

$data = mysqli_fetch_assoc($result);

//Nombre, contraseña, correo, rango y avatar?

if(strlen($data['avatar'])) 
{
	echo '
	<div id="avatar_popup" style="display: none;">
		<div>
			<h3>Avatar</h3>
			<img src="./images/icons/cross.png" title="Cerrar" onclick="this.parentNode.parentNode.style.display = \'none\';">
			<img src="'.$data['avatar'].'" />
		</div>
	</div>';
}

echo '	
<div class="menu_block center">
	<div>'.$data['username'].' - Editar</div>
	<div>
		<div>
			<table class="profile-editor">
				<tr>
					<td>Datos personales</td>
					<td>Datos de identificación</td>
				</tr>
				<tr>
					<td>
						<form id="admin_upt_userinfo">
							Nombre:<br>
							<input type="text" name="username" value="'.$data['username'].'" /><br>
							Rango:<br>
							<select name="rank">';

							$result = Query::run("SELECT * FROM ranks");
							while($row = mysqli_fetch_assoc($result))
								echo '<option value="'.$row['id'].'" '.($row['id'] == $data['rank_id'] ? 'selected' : '').'>'.$row['name'].'</option>';

						echo '
							</select>
							Rank_duration is still a WIP...
							<br>
							Avatar:
							<input type="hidden" name="profile_id" value="'.$_GET['id'].'" />
							<center>
							    <!--- <input type="button" value="Guardar cambios" onclick="aSendForm(this.parentNode.parentNode)" /> -->
							    <input type="submit" value="Guardar cambios" />
							</center>
							<script>
								var e = document.currentScript; 
								e.parentNode.insertBefore(fUpload("avatar"), e.previousElementSibling);';
								if(strlen($data['avatar'])) 
								{
									echo '
									var td2 = document.getElementById("td2"),
										av_thumb = document.getElementById("avatar_thumb");
									td2.style.display = "block";
									av_thumb.style.backgroundImage = "url(\''.$data['avatar'].'\')";
									av_thumb.style.backgroundSize = "cover";
									av_thumb.setAttribute("onclick", "document.getElementById(\'avatar_popup\').style.display = \'block\'");
									document.getElementsByName("avatar_link")[0].value = "'.$data['avatar'].'";';
								}
							echo '</script>
						</form>
					</td>
					<td>
						<form id="admin_upt_idinfo">
							Nueva contraseña:<br>
							<input type="password" name="password" /><br>
							Confirmar nueva contraseña:<br>
							<input type="password" name="confirm_password" /><br>
							Nuevo correo:<br>
							<input type="text" name="email" /><br>
							Confirmar nuevo correo:<br>
							<input type="text" name="confirm_email" /><br>
							<input type="hidden" name="profile_id" value="'.$_GET['id'].'" />
							<center style="position:relative;margin-top:5px;">
								<!--- <input type="button" value="Guardar cambios" onclick="aSendForm(this.parentNode.parentNode)" /> -->
								<input type="submit" value="Guardar cambios" />
							</center>
						</form>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>';


?>