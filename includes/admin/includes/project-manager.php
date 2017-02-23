<?php

//https://scontent-mad1-1.xx.fbcdn.net/hphotos-xap1/t31.0-8/s960x960/886864_453080824892547_8774485805036172898_o.jpg

$do = @$_GET['do'];

//http://www.w3schools.com/php/showphpfile.asp?filename=demo_db_select_proc

if(!isset($do)) 
{
	echo '
	<style>
		.menu_manager table tr td:nth-child(1) 
		{
		    width: 100%;
		}
	</style>
	<div id="delete_msg" style="display: none;"></div>
	<div class="menu_manager">
		<div>
			<div>Gestor de proyectos</div>
			<div onclick="goParam(\'do\', \'add\')">+</div>
		</div>
		<div>';

	$num_rows = Query::count('id', 'projects');

//query del paginator: http://code.tutsplus.com/tutorials/how-to-paginate-data-with-php--net-2928#comment-1034652698

	if($num_rows > 0) 
	{		
		$pages = new Paginator($num_rows, 9);
		$result = Query::run("SELECT * FROM projects ORDER BY id ASC LIMIT $pages->limit_start, $pages->limit_end"); //No se si el order by hará falta en ese caso ^^'
		echo '<table>
			<tr>
				<td>Nombre</td>
				<td>Acción</td>
			</tr>';
			while($row = mysqli_fetch_assoc($result)) 
			{
				$r = unserialize($row['data']);
				$data = $r[Lang::$lang->lang_name];
				echo '
				<tr>
					<td>'.$data['name'].'</td>
					<td>
						<a href="index.php?action=admin&go=project-manager&do=edit&id='.$row['id'].'" style="text-decoration: none;">
							<img src="./images/icons/edit.png" title="Editar proyecto" />
						</a>';
						if(isset($r['markedProject']) && $r['markedProject'])
							echo '<img src="./images/icons/unstar.png" onclick="changeProjectRemark('.$row['id'].', 0)" title="Quitar proyecto del destacado">';
						else
							echo '<img src="./images/icons/star.png" onclick="changeProjectRemark('.$row['id'].', 1)" title="Marcar proyecto como destacado">';
						echo '<img src="./images/icons/cross.png" onclick="deleteManager('.$row['id'].', 0)" title="Borrar proyecto" />
					</td>
				</tr>';
			}
		echo '</table>';
		//$pages->display_jump_menu().$pages->display_items_per_page()
		echo '</div>
			<div>'.$pages->display_pages().'</div>';
	} else
		echo '<center><h3>No hay resultados para mostrar, usa "+" para agregar un nuevo proyecto.</h3></center></div>
		</div>';

	$setts = SettingsManager::get('project_settings');
	$setts = isset($setts) ? unserialize($setts) : null;

	echo '
	<div class="sep"></div>
	<h2>Opciones</h2>
	<div class="sep"></div>
	<form id="admin_prj_settings">
		Número máximo de proyectos a mostrar por página: <input type="text" name="max_entries" size="3" value="'.(isset($setts['max_entries']) ? $setts['max_entries'] : '10').'" /> entradas
		<br>
		<small>* Si especificas 0 como número máximo de entrada por página se mostrarán todos los elementos.</small>
		<center style="position:relative;margin-top:5px;">
			<!--- <input type="button" value="Guardar cambios" onclick="aSendForm(this.parentNode.parentNode)" /> -->
			<input type="submit" value="Guardar cambios" />
		</center>
	</form>';
} 
else if($do == "add" || $do == "edit") //Me falta hacer un edit el cual cargue con  un script el template y establezca todos los campos necesarios
{ //Puede que le meta un bbceditor a la descripcion
	echo '
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script type="text/javascript" src="./js/thumb-core.js"></script>
	<script>uploadPopup();</script>
	<script>
	$.datepicker.regional["es"] = {
	 closeText: "Cerrar",
	 prevText: "<Ant",
	 nextText: "Sig>",
	 currentText: "Hoy",
	 monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
	 monthNamesShort: ["Ene","Feb","Mar","Abr", "May","Jun","Jul","Ago","Sep", "Oct","Nov","Dic"],
	 dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
	 dayNamesShort: ["Dom","Lun","Mar","Mié","Juv","Vie","Sáb"],
	 dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sá"],
	 weekHeader: "Sm",
	 dateFormat: "dd/mm/yy",
	 firstDay: 1,
	 isRTL: false,
	 showMonthAfterYear: false,
	 yearSuffix: ""
	 };
	 $.datepicker.setDefaults($.datepicker.regional["es"]);
	$(function () {
	$("#datepicker").datepicker();
	});
	</script>
	<script>getAuthor();</script>
	<h2>Gestor de publicaciones</h2>
	<div class="sep"></div>
	<div class="menu_block supercenter" style="margin-top: 10px;">
		<div>
			Añadir proyecto
		</div>
		<div style="padding: 5px;">
			<form id="admin_sendpost" style="position:relative;"> <!--- onkeypress="if(event.keyCode == 13) return manager(0);" //Por el momento no es necesario -->
				<div style="display:inline-block;width:50%;vertical-align:top;">
					<label>Nombre:</label><br>
					<input type="text" name="post_name" /><br>
					<label class="charcount">
				    	Descripción:<label>5000 caracteres</label><br>
				      	<textarea onkeydown="charcount(this)" name="post_desc" rows="6" cols="40" style="margin-bottom:5px;padding:5px 5px 0 5px;"></textarea>
				    </label><br>
				    	Versión actual:<br>
				    	<input type="text" name="post_avers" /><br>
				    	Notas de versión (changelog):
				    	<br>
				    	<div style="margin-top:5px; margin-bottom: -2px;">
				    		<input type="button" value="Añadir nota de versión" id="addvnote" onclick="addNoteVers(this.parentNode.querySelector(\'label\'))" style="width:195px;" />
					    	<br>
					    	<label class="charcount">
					    		<label>5000 caracteres</label>
						    	<input type="text" name="post_vers_name[]" placeholder="Nombre de la versión" style="margin-bottom: 5px;width:180px;" /><br>
						      	<textarea onkeydown="charcount(this)" name="post_vers_note[]" style="padding:5px 5px 0 5px;" rows="4" cols="40" placeholder="Datos sobre la versión"></textarea>
					      	</label>
				      	</div>
					<label>Fecha de creación:</label><br>
					<input type="text" name="post_cdate" id="datepicker" /><br>
					<label class="charcount">
				    	Otros datos:<label>1000 caracteres</label><br>
				      	<textarea onkeydown="charcount(this, 1000)" name="post_odata" rows="2" cols="40"></textarea>
				    </label><br>
					<label>Tipo:</label><br>
					<select name="post_type" onchange="updateContent(this.value)">
						<option disabled selected>Selecciona una opción</option>
						<option value="0">Minijuego</option>
						<option value="1">Asset</option>
						<option value="2">Servicio web</option>
						<option value="3">Aplicación de escritorio</option>
						<option value="4">Plugin MC</option>
					</select>
					<input type="hidden" name="post_thumb" id="post_thumb" />
					<div id="c1" style="margin-top:5px;"></div>
				</div>
				<div style="display:inline-block;width:49%;position:relative;margin-top:35px;" id="c2"></div>
				<center>
				    <input type="button" value="Guardar cambios" onclick="manager(0)" />
				</center>
				<div id="c3"></div>
				<div style="position:absolute;right:-3px;top:0;">
					Idioma del proyecto:
					<select id="project_lang" onchange="changeLanguage(event.target, 0);" alt="Al seleccionar una opción los campos del formulario se resetearan para que puedas escribirlo en un idioma distinto.">
						'.LangManager::getLangOpts().'
					</select>
				</div>
			</form>
		</div>
	</div>';
	if($do == "edit") 
	{
		$id = @$_GET['id'];
		if(!isset($id)) die('No se puede cargar el contenido.');
		if(!mysqli_fetch_assoc(Query::run("SELECT COUNT(id) as total FROM projects WHERE $id = '$id'"))['total']) 
			die('No se encuentra ningún proyecto por la ID solicitada.');
		else
			echo '<script>loadManagerData('.$id.', "'.Lang::$lang->lang_name.'", 0);</script>'; //Esto es una comprobación tonta
	}
}

?>