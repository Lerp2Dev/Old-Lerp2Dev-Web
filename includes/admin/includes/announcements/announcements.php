<?php

//Work in progress...

//Aquí dentro irá una tabla con con dos tds, en el primero habrá una una numeración (#x) y con unos botones para subir y bajar, 
//y en el segundo un label con un textarea que tendrá el anuncio en cuestion (+ una cuenta de caracteres restantes), y despues un separador y varias opciones
//Añadir marquee si la noticia no cabe

$do = @$_GET['do'];

if(!isset($do)) 
{
	echo '
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<style>
		.menu_manager table tr td {
			vertical-align: middle;
		}
		.menu_manager table tr td:nth-child(1) {
		    width: 19px;
		    vertical-align:middle;
		    text-align:center;
		}
		.menu_manager table tr td:nth-child(3) {
		    width: 53px;
		}
		.menu_manager table td:nth-child(2) {
		    border-right: 2px dashed #a4a4a4;
		}
	</style>
	<div id="delete_msg" style="display: none;"></div>
	<div class="menu_manager">
		<div>
			<div>Gestor de noticias</div>
			<div onclick="goParam(\'do\', \'add\')">+</div>
		</div>
		<div>';

	$num_rows = Query::count('id', 'announcements');

//query del paginator: http://code.tutsplus.com/tutorials/how-to-paginate-data-with-php--net-2928#comment-1034652698
//Crear una tercera columna en la primera posición llamada orden con las flechas

	if($num_rows > 0)
	{
		$pages = new Paginator($num_rows, 9);
		$result = Query::run("SELECT * FROM announcements ORDER BY pos ASC LIMIT $pages->limit_start, $pages->limit_end");
		echo '<table id="annTable">
			<tbody>
			<tr>
				<td></td>
				<td>Nombre</td>
				<td>Acción</td>
			</tr>';
			$i = 0;
			while($row = mysqli_fetch_assoc($result))
			{
				echo '
				<tr data-id="'.$row['id'].'" data-pos="'.$row['pos'].'">
					<td>
						'.($i ? '<span onclick="changeAdOrder('.$row['id'].', '.$row['pos'].', '.((int)$row['pos']-1).')">▲</span>' : '').'<br>#'.$row['pos'].'<br>'.($i < $num_rows-1 ? '<span onclick="changeAdOrder('.$row['id'].', '.$row['pos'].', '.((int)$row['pos']+1).')">▼</span>' : '').'
					</td>
					<td>'.(strlen($row['data']) <= 60 ? $row['data'] : substr($row['data'], 0, 60).'...').'</td>
					<td>
						<a href="index.php?action=admin&go=announcements&do=edit&id='.$row['id'].'" style="text-decoration: none;">
							<img src="./images/icons/edit.png" />
						</a> 
						<img src="./images/icons/cross.png" onclick="deleteManager('.$row['id'].', 1)" />
					</td>
				</tr>';
				++$i;
			}
		echo '</tbody>
		</table>
		<script>
			$("#annTable > tbody").sortable({
			    items: "> tr:not(:first)",
			    appendTo: "parent",
			    helper: "clone",
			    update: function(event, ui) {
			    	changeAdOrder(ui.item[0].dataset.id, ui.item[0].dataset.pos, getChildIndex(ui.item[0]));
			    }
			}).disableSelection();
		</script>';
		//$pages->display_jump_menu().$pages->display_items_per_page()
		echo '</div>
			<div>'.$pages->display_pages().'</div>';
	} else
		echo '<center><h3>No hay resultados para mostrar, usa "+" para agregar un nuevo anuncio.</h3></center></div>
		</div>';

	//Recuperamos las opciones que tenemos en la base de datos. Estan en la base de datos serializadas, por lo que la propia clase de anuncios nos permite deserializarlas por defecto. Para otras opciones ver la clase  "annuncements.class.php"
	$settings = Announcement::getSettings();
	$mouseover = $settings['stop_onmouseover'];
	$random = $settings['random_change'];
	$open_blank = $settings['open_blank'];
	$speed = $settings['speed'];

	echo '
	<div class="sep"></div>
	<h2>Opciones</h2>
	<div class="sep"></div>
	<form id="admin_ann_settings">
		<label><input type="checkbox" name="stop_onmouseover" value="checked" '. $mouseover.' />Parar al poner el ratón encima</label><br>
		<label><input type="checkbox" name="random_change" value="checked" '.$random.' />Cambiar aleatoriamente</label><br>
		<label><input type="checkbox" name="open_blank" value="checked" '.$open_blank.' />Abrir enlaces en una nueva pestaña</label><br>
		<label><input type="number" name="speed" value="'. $speed.'" />Velocidad de los anuncios</label>
		<center>
			<!--- <input type="button" value="Guardar cambios" onclick="aSendForm(this.parentNode.parentNode)" /> -->
			<input type="submit" value="Guardar cambios" />
	    </center>
	</form>';
} 
else if($do == "add" || $do == "edit") //Me falta hacer un edit el cual cargue con  un script el template y establezca todos los campos necesarios
{

?>
	<link rel="stylesheet" href="./misc/bbcode/minified/themes/default.min.css" type="text/css" media="all" />
	<script src="./misc/bbcode/minified/jquery.sceditor.bbcode.min.js"></script>
	<script>
		// Source: http://www.backalleycoder.com/2011/03/20/link-tag-css-stylesheet-load-event/
		$(document).ready(function() {
			var initEditor = function() {
				$("#bbcode_field").sceditor({
					plugins: 'bbcode',
					toolbar: "bold,italic,underline,strike,subscript,superscript|font,size,color,removeformat|link,unlink|emoticon,date,time|source",
					style: "./misc/bbcode/minified/jquery.sceditor.default.min.css"
				});
			};
			initEditor();
		});
	</script>
	<h2>Gestor de noticias</h2>
	<div class="sep"></div>
	<div class="menu_manager supercenter" style="margin-top: 10px;">
		<div>
			<div>Añadir noticia</div>
		</div>
		<div style="padding: 5px;">
			<form id="admin_sendad" style="position:relative;" action="announcements_model.php" method="post"> <!--- onkeypress="if(event.keyCode == 13) return manager(1);" //Por el momento no es necesario -->
				<h3>
					Cuerpo de la noticia:
					<div style="float: right;font-weight:normal;font-size:15px;position: relative;top: -5px;">
						Idioma del proyecto:
						<select id="project_lang"  alt="Al seleccionar una opción los campos del formulario se resetearan para que puedas escribirlo en un idioma distinto.">
							<?php echo LangManager::getLangOpts(Announcement::getAnnouncementById($_GET['id'])); ?>
						</select>
					</div>
				</h3>
				<div style="border-top: 3px dashed #A4A4A4;margin: 10px 0 5px 0;"></div>
				<center>
					<textarea id="bbcode_field" style="height:300px;width:100%;"><?php echo (isset($_GET['id'])) ?  Announcement::getAnnouncementById($_GET['id'])['data']: "";  ?></textarea>
					<input type="hidden" value="<?php echo (isset($_GET['id'])) ?  $_GET['id'] : "";  ?>">
					<input type="button" value="Guardar cambios" onclick="manager(1)" style="margin-top:5px;" />
				</center>
			</form>
		</div>
	</div>
<?php
	if($do == "edit") 
	{
		$id = (int)mysqli_escape_string(Database::conn(), @$_GET['id']);
		$lang_name = Lang::$lang->lang_name;
		if(!isset($id)) die('No se puede cargar el contenido.');
		if(!Query::count('id', 'announcements', "WHERE $id = '$id'"))
			die('No se encuentra ninguna noticia por la ID solicitada.');
		else

			echo '<script>loadManagerData('.$id.' ,"'.$lang_name.'", 1);</script>'; //Esto es una comprobación tonta
	}
}

?>