<?php

//include('main.php');
include('main.loader.php');

/* Configuracion de anuncios
Recuperamos toda la configuración de los anuncios para seter la configuracion de los mismos*/
$settings = Announcement::getSettings();

$random = $settings['random_change'];
$open_blank = $settings['open_blank'];
/*Fin configuracion anuncios*/

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Lerp2Dev! - '.Lang::$lang->text["home"].'</title>
		<link rel="stylesheet" type="text/css" href="./css/web.css" />
		<link rel="shortcut icon" href="./images/icons/favicon.png" />
		<script src="//code.jquery.com/jquery-latest.js"></script>
		<script src="./js/jquery.redirect.js"></script>
		<script type="text/javascript" src="./js/web.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/msdropdown/dd.css" />
		<script src="./js/msdropdown/jquery.dd.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/msdropdown/flags.css" />
		<meta charset="utf-8" />
		<meta name="google-site-verification" content="EScUp05w_VgaJJiTpF9uwB9fr3vUo-3ynUHI8Ljk7so"> <!--- Cuidado con no joder las stats -->
	</head>
	<body>
		<div class="background">
		<!--- <img src="./images/background.jpg" /> -->
		</div>
		<div class="header">
			<a href="http://'.$_SERVER["SERVER_NAME"].'/">
				<img src="./images/logo.png" />
			</a>
		</div>
		<div class="body">
			<div id="cssmenu">
				<ul style="display: inline-block;">
					<li '.((@$_GET['action'] == 'index' || empty(@$_GET['action'])) ? 'class="active"' : '' ).'><a href="index.php">'.Lang::$lang->text["home"].'</a></li>
				   	<li '.((@$_GET['action'] == 'members') ? 'class="active"' : '' ).'><a href="index.php?action=members">'.Lang::$lang->text["members"].'</a></li>
				   	<li '.((@$_GET['action'] == 'projects') ? 'class="active"' : '' ).'><a href="index.php?action=projects">'.Lang::$lang->text["projects"].'</a></li>
				   	<li '.((@$_GET['action'] == 'contact') ? 'class="active"' : '' ).'><a href="index.php?action=contact">'.Lang::$lang->text["contact"].'</a></li>
				   	<li style="position:relative;" onmouseover="document.getElementById(\'topprojects\').style.display = \'block\';" onmouseout="document.getElementById(\'topprojects\').style.display = \'none\';">
						<a href="javascript:;">'.Lang::$lang->text["top_projects"].'</a>
						<div id="topprojects" style="display: none;">
							<table>';
							$i = 0;
							$result = Query::run("SELECT * FROM projects"); //Esto necesita un cambio guapo, una optimizacion vaya jeje
							$style = 'margin: 5px 0 5px 0; border-top: 2px dashed #a4a4a4;';
							while($row = mysqli_fetch_assoc($result))
							{
								$r = unserialize($row['data']);
								$data = $r[Lang::$lang->lang_name];
								$s = $i > 0 && $i < 5 ? 'style="'.$style.'"' : '';
								if(isset($r['markedProject']) && $r['markedProject'] && $i < 5)
								{
									echo '<tr>
										<td '.$s.'>
											<img style="width:80px;height:50px;" src="'.$data['thumb'].'" />
										</td>
										<td class="proj_box" '.$s.'>
											<a href="index.php?action=preview&id='.$row['id'].'">'.$data["name"].'</a>
											'.substr($data["desc"], 0, 65).'...
										</td>
									</tr>';
									++$i;
								}
								if($i > 5) 
								{
									echo '<a href="index.php?action=projects"><small>Más proyectos...</small></a>';
									break;
								}
							}
							if($i == 0)
								echo Lang::$lang->text["no_projects"];
							echo '</table>
							</div>
				   	</li>
				   	'.((UserUtils::isRanked('Admin')) ? '<li '.((@$_GET['action'] == 'admin') ? 'class="active"' : '' ).'><a href="index.php?action=admin">'.Lang::$lang->text["admin"].'</a></li>' : '').'
				</ul>
				<select name="countries" id="countries" style="width: 200px;display: inline-block;position: relative;top: -20px;height: 45px;border-radius: 5px 5px 0 0;border-bottom: 0;padding-left:12px;">
					<option value="es" data-image="images/msdropdown/icons/blank.gif" data-imagecss="flag es" data-title="'.Lang::$lang->text["flag_es"].'" data-lang="es">'.Lang::$lang->text["flag_es"].' (Español)</option>
					<option value="us" data-image="images/msdropdown/icons/blank.gif" data-imagecss="flag us" data-title="'.Lang::$lang->text["flag_en"].'" data-lang="en">'.Lang::$lang->text["flag_en"].' (English)</option>
				</select>
				<script>
					$(document).ready(function() {
						$("#countries").msDropdown();
					});
				</script>';

				echo '<div style="position: absolute;right:5px;top: 15px;">';
				if(UserUtils::isOnline())
				{
					echo '  <div class="menu_button no-border">
								<img src="./images/icons/eye.png" />
							</div>
							<div class="menu_button no-border">
								<img src="./images/icons/mp.png" />
							</div>';
				}
				else
				{
					echo '<div class="menu_button">
							<img src="./images/icons/register.png" /> <a href="index.php?action=register" class="no_link">Registrarse</a>
						</div>';
				}
				echo '<div class="menu_button" '.(UserUtils::isOnline() ? 'style="width: 24px;"' : "").'>';
				if(UserUtils::isOnline())
				{
					echo '<img src="./images/icons/menu.png" onclick="showhide(document.getElementById(\'menu-popup\'));" />
					<div id="menu-popup" class="bubblemenu" style="display: none;width:250px;">
						<div>
							<form id="logout">
								<table style="margin-bottom: -18px; border-spacing: 0;">
									<tr>
										<td>
											<img class="avatar" src="'.UserUtils::getAvatar(ClientUtils::$curClient->avatar).'" />
										</td>
										<td>
											¡Hola, <b>'.ClientUtils::$curClient->username.'</b>! Tienes 0 mensajes, 0 son nuevos.
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<a href="javascript:;" class="no_link">Obtener coins gratuitas</a>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<a href="javascript:;" class="no_link">Ver privilegios disponibles</a>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<a href="javascript:;" class="no_link">Editar mi perfil</a>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="submit" class="like_link no_link" value="Cerrar sesión" />
										</td>
									</tr>
								</table>
								<br>
								<!--- <a href="javascript:;" onclick="sendForm(this.parentNode)">Cerrar sesión</a>-->
							</form>
						</div>
					</div>';
				} 
				else 
				{ //Checkduration no es necesario ahí
					echo '<img src="./images/icons/login.png" /> <a href="javascript:;" class="no_link" onclick="showhide(document.getElementById(\'login-popup\'));">Identificarse</a>
					<div id="login-popup" class="bubblemenu" style="display: none;">
						<div style="margin: 5px 10px 5px 10px;">
							<form id="login">
								<h3>Usuario:</h3>
								<input type="text" name="username" />
								<h3>Contraseña:</h3>
								<input type="password" name="password" />
								<br>
								<h3>Duración de la sesión:</h3>
								<input name="duration" id="duration" type="number" min="10" max="31556926" value="10" onkeypress="if(event.keyCode == 13) return sendForm(this.parentNode, event);" /> minutos
								<br>
								<div style="margin-bottom: 5px;">
									<label><input id="infinite" type="checkbox" onclick="checkDuration()" style="margin:0;" /> Sesión infinita</label>
								</div>
								<center>
									<!--- <input type="button" value="Iniciar sesión" onclick="sendForm(this.parentNode.parentNode)" /> -->
									<input type="submit" value="Iniciar sesión" />
								</center>
							</form>
						</div>
						<div style="border-top:2px #A4A4A4 dashed;margin: 5px 0 5px 0;"></div>
						<center style="font-size:12px;"><a href="index.php?action=register">No tengo cuenta</a></center>
						<div style="border-top:2px #A4A4A4 dashed;margin: 5px 0 5px 0;"></div>
						<center style="font-size:12px;margin-bottom: 5px;">He olvidado mi contraseña</center>
					</div>';
				}
				echo '</div>
				   </div>
				</div>
			
			<MARQUEE '. Announcement::getMouseOver() .' scrollamount="'. Announcement::getSpeed() .'">
				<p id="marqueeTxt"></p>
			</MARQUEE >
			<div >
				
			</div>
			<div class="subbody">
			<div id="msg"></div>';

			$go = @$_GET['action'];
			if(empty($go)){$go='index';}

			$page_title = "";
			 
			switch($go)
			{

			  case 'index':
			  	$page_title = "Inicio"; //Esto deberia estar dentro del Lang::$lang->text
			    include(INCLUDES.'index.php');
			    break;

			  case 'members':
			  	$page_title = "Miembros";
			    include(INCLUDES.'members.php');
			    break;

			  case 'projects':
			  	$page_title = "Proyectos";
			    include(INCLUDES.'projects.php');
			    break;

			  case 'contact':
			  	$page_title = "Contacto";
			    include(INCLUDES.'contact.php');
			    break;		

			  case 'preview':
			    include(INCLUDES.'preview.php');
			    break;

			  case 'register':
			  	$page_title = "Registro";
			    include(INCLUDES.'register.php');
			    break;

			  case 'who':
				$page_title = "¿Quién está en línea?";
				include(INCLUDES.'who.php');
				break;

			  case 'admin':
			    include(INCLUDES.'/admin/index.php');
			    break;

			  default:
			  	include(INCLUDES.'/404.php');
			  	break;

			}

			echo '</div>
		</div>
		<div class="online">
			<div onclick="document.getElementById(\'otime\').style.display = ((document.getElementById(\'otime\').style.display == \'none\') ? \'block\' : \'none\');">
				<img alt="'.Lang::$lang->text["online_explanation"].'" src="./images/icons/online_bar.png" />
				<span>
					<a href="index.php?action=who" id="gop">'.count(UserUtils::getOnlinePeople()).'</a>
				</span>
			</div>
			<select id="otime" style="display: none;">
				<option value="1">1m</option>
				<option value="2" selected>2m</option>
				<option value="5">5m</option>
				<option value="10">10m</option>
				<option value="15">15m</option>
				<option value="30">30m</option>
				<option value="60">1h</option>
				<option value="120">2h</option>
				<option value="150">2.5h</option>
				<option value="180">3h</option>
				<option value="300">5h</option>
				<option value="600">10h</option>
				<option value="720">12h</option>
				<option value="1440">1d</option>
				<option value="2880">2d</option>
			</select>
		</div>';
		if(isset($_POST["msg"]))
			echo '<script>showMsg('.json_encode($_POST).');</script>';
		echo '<script>document.title = "Lerp2Dev! - '.$page_title.'";</script>';
	echo '</body>
</html>';

/*<script id="cid0020000096130988482" data-cfasync="false" async src=".//st.chatango.com/js/gz/emb.js" style="width: 500px;height: 400px;">{"handle":"lerp2dev","arch":"js","styles":{"a":"e0e0e0","b":100,"c":"000000","d":"000000","k":"e0e0e0","l":"e0e0e0","m":"e0e0e0","p":"10","q":"e0e0e0","r":100,"fwtickm":1}}</script>*/

?>