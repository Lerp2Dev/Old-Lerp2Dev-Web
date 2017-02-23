<?php

echo '
<!--- <center>
	<table style="width: 500px;word-wrap: break-word;">
		<tr>
			<td>
				<img class="avatar" src="./images/avatars/ikillnukes.jpg" />
			</td>
			<td class="mem_pres">
				<b>'.Lang::$lang->text["name"].':</b> Ikillnukes</i><br>
				<b>'.Lang::$lang->text["age"].':</b> 17 '.Lang::$lang->text["years"].'<br>
				<b>'.Lang::$lang->text["nacionality"].':</b> '.Lang::$lang->text["spain"].'<br>
				<b>'.Lang::$lang->text["gender"].':</b> '.Lang::$lang->text["man"].'<br>
				<b>'.Lang::$lang->text["specialities"].':</b> '.Lang::$lang->text["ikill_esp"].'
			</td>
		</tr>
		<tr>
			<td>
				<img class="avatar" src="./images/avatars/fenex.jpg" />
			</td>
			<td class="mem_pres">
				<b>'.Lang::$lang->text["name"].':</b> Fenex</i><br>
				<b>'.Lang::$lang->text["age"].':</b> 18 '.Lang::$lang->text["years"].'<br>
				<b>'.Lang::$lang->text["nacionality"].':</b> '.Lang::$lang->text["spain"].'<br>
				<b>'.Lang::$lang->text["gender"].':</b> '.Lang::$lang->text["man"].'<br>
				<b>'.Lang::$lang->text["specialities"].':</b> '.Lang::$lang->text["fenex_esp"].'
			</td>
		</tr>
		<tr>
			<td>
				<img class="avatar" src="./images/avatars/nico.jpg" />
			</td>
			<td class="mem_pres">
				<b>'.Lang::$lang->text["name"].':</b> NightmareXtreme</i><br>
				<b>'.Lang::$lang->text["age"].':</b> 19 '.Lang::$lang->text["years"].'<br>
				<b>'.Lang::$lang->text["nacionality"].':</b> '.Lang::$lang->text["spain"].'<br>
				<b>'.Lang::$lang->text["gender"].':</b> '.Lang::$lang->text["man"].'<br>
				<b>'.Lang::$lang->text["specialities"].':</b> '.Lang::$lang->text["nico_esp"].'
			</td>
		</tr>
		<tr>
			<td>
				<img class="avatar" src="./images/avatars/xxluigimario.jpg" />
			</td>
			<td class="mem_pres">
				<b>'.Lang::$lang->text["name"].':</b> XXLuigiMario</i><br>
				<b>'.Lang::$lang->text["age"].':</b> 16 '.Lang::$lang->text["years"].'<br>
				<b>'.Lang::$lang->text["nacionality"].':</b> '.Lang::$lang->text["spain"].'<br>
				<b>'.Lang::$lang->text["gender"].':</b> '.Lang::$lang->text["man"].'<br>
				<b>'.Lang::$lang->text["specialities"].':</b> '.Lang::$lang->text["xxl_esp"].'
			</td>
		</tr>
	</table>
</center> -->';
echo '<center>
    <table>';
    $admins = Query::run("SELECT id, avatar, username, real_name, birthdate, location, gender, specialties FROM users WHERE rank_id = ".UserUtils::getRankIdByName('admin')); //Esto necesita un cambio guapo, una optimizacion vaya jeje
    while($row = mysqli_fetch_assoc($admins))
    {
		echo '<tr>
			<td>
				<img class="avatar" src="'.UserUtils::getAvatar($row['avatar']).'" />
			</td>
			<td class="mem_pres">
				<b>'.Lang::$lang->text["name"].':</b> '.$row['real_name'].' ('.$row['username'].')</i><br>
				<b>'.Lang::$lang->text["age"].':</b> '.CoreUtils::getUserAge($row['birthdate']).' '.Lang::$lang->text["years"].'<br>
				<b>'.Lang::$lang->text["nacionality"].':</b> '.$row['location'].'<br>
				<b>'.Lang::$lang->text["gender"].':</b> '.$row['gender'].'<br>
				<b>'.Lang::$lang->text["specialities"].':</b> '.$row['specialties'].'<br>
				<a href="index.php?action=projects&do=search&author='.$row['id'].'">Ver mis proyectos</a>
			</td>
		</tr>';
    }
echo '</table>
</center>
<div class="sep"></div>';
$num_rows = Query::count('id', 'users'); //"WHERE rank_id = ".UserUtils::getRankIdByName('user')
if($num_rows > 0)
{
    $pages = new Paginator($num_rows, 9);
    $no_admins = Query::run("SELECT * FROM users WHERE rank_id = ".UserUtils::getRankIdByName('user')." ORDER BY reg_time ASC LIMIT $pages->limit_start, $pages->limit_end");
    //$no_admins = Query::run("SELECT * FROM users ORDER BY reg_time ASC LIMIT $pages->limit_start, $pages->limit_end");

    echo '<h2>Miembros ('.mysqli_num_rows($no_admins).')</h2>
    <div class="sep"></div>'; //Tengo q meter el nombre de ivan cea, jesus x2, gabriel

    while($row = mysqli_fetch_assoc($no_admins))
        echo ContentUtils::getAvatarBubble($row);
    echo '<div class="sep"></div>
    <center>'.$pages->display_pages().'</center>';
} else
    echo '<center><h2>No hay miembros aún.</h2></center>';

//Hacer que haya un botton que ponga mostrar proyectos de dicho usuario, al hacer click se redireccionara a los proyectos con el parametro cambiado
//Asi mismo hacer que haya una barra de busqueda en dicha pestaña