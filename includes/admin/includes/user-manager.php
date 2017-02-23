<?php

//Work in progress...

$do = @$_GET['do'];
if(!isset($do)) $do = 'manage-new-users';
echo '
<div id="cssmenu" style="margin-bottom:10px;">
	<ul>
		<li '.((@$_GET['do'] == 'manage-new-users' || empty(@$_GET['do'])) ? 'class="active"' : '' ).'><a href="index.php?action=admin&go=user-manager&do=manage-new-users">Gestión de usuarios</a></li>
	   	<li '.((@$_GET['do'] == 'confirm-new-requests') ? 'class="active"' : '' ).'><a href="index.php?action=admin&go=user-manager&do=confirm-new-requests">Confirmar nuevos registros</a></li> <!--- Solo aparece si la confirmación de email es obligatoria -->
	   	<li '.((@$_GET['do'] == 'manage-bans') ? 'class="active"' : '' ).'><a href="index.php?action=admin&go=user-manager&do=manage-bans">Gestión de baneados</a></li>
	</ul>
</div>';

if($do == 'manage-new-users') 
{
	echo '
	<center style="margin-bottom:5px;"><i>Coming in the way... Un buscador :)</i></center>
	<div id="delete_msg" style="display: none;"></div>
	<div class="menu_block userpreset">
		<div>Nuevo usuario</div>
		<div>
			<div>
				<center>
					<img src="./images/icons/add.png" title="Añadir usuario usuario (WIP)" />
				</center>
			</div>
		</div>
	</div>';
	$num_rows = Query::count('id', 'users');
	if($num_rows > 0) 
	{
		$pages = new Paginator($num_rows, 9);
		$result = Query::run("SELECT id, username, avatar FROM users ORDER BY id ASC LIMIT $pages->limit_start, $pages->limit_end");
		while($row = mysqli_fetch_assoc($result)) 
		{
			echo '	
			<div class="menu_block userpreset">
				<div>'.$row['username'].'</div>
				<div>
					<div style="text-align:center;">
						<img src="'.(empty($row['avatar']) ? './images/avatars/no-avatar.png' : $row['avatar']).'" class="admin-avatar" />
						<br>
						<center>
							<a href="index.php?action=admin&go=profile-editor&id='.$row['id'].'"><img src="./images/icons/edit.png" title="Editar usuario" /></a>
							<a href="index.php?action=admin&go=ban-mananger&ban_id='.$row['id'].'"><img src="./images/icons/ban.png" title="Banear usuario" /></a>
							<img src="./images/icons/cross.png" title="Eliminar usuario" onclick="deleteManager('.$row['id'].', 2);" />
						</center>
					</div>
				</div>
			</div>';
		}
		echo '
		<div class="sep"></div>
		<center>'.$pages->display_pages().'</center>
		<div class="sep"></div>';
	} else
		echo '<center><h3>Hubo un error al obtener la lista de usuarios.</h3></center>';
} 
else if($do == 'confirm-new-requests') 
{
	echo 'confirm-new-requests';
} 
else if($do == 'manage-bans') 
{
	echo 'manage-bans';
}

//Si por un casual el menú no cabiese podria flechas a los lados y un marginLeft animando

?>