<?php

/*

Apartados de programación (ordenados): 

Pagina principal: http://image.slidesharecdn.com/foroactivo-com-100422113155-phpapp01/95/foroactivo-com-10-728.jpg?cb=1271935977

Pequeña bienvenida, (bloque general)

Estadisticas, control de actividad + lista de los admins y moderadores (avatares redondos en pequeño + su nick debajo) (bloque central)

Buscar un usuario, Bloc de notas, (bloques a la izquierda)

Bloque de navegación (a la derecha):

- General -> Actividad de administración y Estadísticas (estará en la pagina principal)
- Contenido -> Noticias, Publicaciones, Proyectos (añadir, editar y eliminar), Widgets (para configurar inicialmente el widget de las redes sociales) [este creo que lo añadire mas tarde]
- Usuarios -> Registros (confirmación y cancelación de registros) [como en Foroactivo], Usuarios (banear, eliminar y editar)
- Moderación -> Comentarios, Contacto (solicitudes de contacto)

*/

if(UserUtils::isRanked('admin'))
{
	if(isset($_SESSION) && isset($_SESSION["admin"]) && $_SESSION["admin"])
	{
		echo '
		<script src="./includes/admin/js/JSLINQ.js"></script>
		<script src="./includes/admin/js/phpjs.js"></script>
		<script src="./includes/admin/js/web-admin.js"></script>
		<link rel="stylesheet" type="text/css" href="./includes/admin/css/web-admin.css" />';

		if(empty($_GET['go'])) 
		{
			echo '<b>¡Bienvenido a la administración de Lerp2Dev!</b>
			Desde aquí podrás controlar los aspectos fundamentales de la página web.
			Para navegar por ella utiliza el menú de la derecha.
			Más abajo encontraras detalles estadísticos de la página web y un control de la última activdad dentro de la página web.
			A la izquierda encontrarás un bloc de notas en el cual podrás apuntar cosas para que los otros administradores las vean.
			<div class="sep"></div>';
		}
		
		echo '
		<table style="width:100%;" class="main_table">
			<tr>
				<td style="width:17.5%;">
					<div class="menu_block lateral">
						<div>Menú</div>
						<div id="admin-menu">
							<span>General</span>
							<div class="sep"></div>
							<div class="subc" onclick="subGo(this, event)" data-go="admin-activity">Actividad de administración</div>
							<div class="subc" onclick="subGo(this, event)" data-go="statitics">Estadísticas</div>
							<div class="subc" onclick="subGo(this, event)" data-go="statitics">Links (bots/registrados)</div>
							<div class="sep"></div>
							<span>Contenido</span>
							<div class="sep"></div>
							<div class="subc" onclick="subGo(this, event)" data-go="announcements">Noticias</div>
							<div class="subc" onclick="subGo(this, event)" data-go="project-manager">Proyectos</div>
							<div class="subc" onclick="subGo(this, event)" data-go="post-manager">Publicaciones</div>
							<div class="subc" onclick="subGo(this, event)" data-go="widget-manager">Widgets</div>
							<div class="sep"></div>
							<span>Usuarios</span>
							<div class="sep"></div>
							<div class="subc" onclick="subGo(this, event)" data-go="user-manager">Usuarios</div>
							<div class="subc" onclick="subGo(this, event)" data-go="access-settings">Ajustes de acceso</div>
							<div class="subc" onclick="subGo(this, event)" data-go="rank-manager">Gestor de rangos</div>
							<div class="sep"></div>
							<span>Moderación</span>
							<div class="sep"></div>
							<div class="subc" onclick="subGo(this, event)" data-go="comments">Comentarios</div>
							<div class="subc" onclick="subGo(this, event)" data-go="contact-requests">Contacto</div>
						</div>
					</div>
				</td>
				<td style="width:65%;">';

				$go_admin = @$_GET['go'];
				 
				switch($go_admin)
				{

				  case 'project-manager':
				  	$page_title = "Gestor de proyectos"; 
				    include(ADMIN_INCLUDES.'project-manager.php');
				    break;

				  case 'announcements':
				  	$page_title = "Gestor de noticias";
				    include(ADMIN_INCLUDES.'announcements/announcements.php');
				    break;

				  case 'user-manager':
					$page_title = "Gestión de usuarios";
					include(ADMIN_INCLUDES.'user-manager.php');
				  	break;

				  case 'access-settings':
					$page_title = "Ajustes de acceso";
					include(ADMIN_INCLUDES.'access-settings.php');
				  	break;

				  case 'profile-editor':
				  	$page_title = "Editor de perfiles";
					include(ADMIN_INCLUDES.'profile-editor.php');
				  	break;

				  case 'ban-manager':
				  	$page_title = "Banear usuario";
					include(ADMIN_INCLUDES.'ban-manager.php');
				  	break;

				  default:
				  	$page_title = "Administración";
				  	include(ADMIN_INCLUDES.'index.php');
				  	break;

				}

				echo '</td>
			</tr>
		</table>
		<script>
			(function() {
				var p = getQueryVariable("go");
				var e = document.getElementById("admin-menu").children;
				for(var i = 0; i < e.length; ++i) 
					if(e[i].getAttribute("data-go"))
						if(p && p == e[i].getAttribute("data-go")) 
							e[i].className="subc active";
					else
						e[i].innerHTML = "- "+e[i].innerHTML;
			})();
		</script>';
	} 
	else
	{
		$page_title = "Login a la administración";
		echo '
		<center>
			<form id="admin-enter">
				<h4>Contraseña de acceso:</h4>
				<input type="password" name="adminpass" />
				<br>
				<!--- <input type="button" value="Entrar" style="margin:10px 0 10px 0;" onclick="sendForm(this.parentNode)" /> -->
				<input type="submit" style="margin:10px 0 10px 0;" value="Entrar" />
			</form>
		</center>';
	}
} 
else 
{
	include(INCLUDES.'/404.php');
}

?>