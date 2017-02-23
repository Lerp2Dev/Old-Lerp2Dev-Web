<?php

use \Dropbox as dbx;

define("REGISTER_HIT", false);
include('../../main.loader.php');

if(!UserUtils::isRanked('admin'))
	die('No eres un administrador.');

if(isset($_POST["new_content_type"])) 
{
	//Según el tipo seleccionado aquí se mostraran distintos inputs, como por ejemplo, 
	//en el minijuego, los controles y un slider de fotos en la parte superior, link al browser en dropbox, 
	//en el asset, fotos y link del asset store, 
	//en el servicio web, la url y fotos, 
	//en la aplicacion de escritorio, fotos y link de descarga
	//en el plugin, fotos y link de descarga

	//Sobre los links quizas añada la opcion de añadir varios links, segun el caso

	/*En los templates debajo de la thumb, el nombre, la desc... y el boton para ir poner una box para sharear y otra para votar y mas debajo, los comentarios
	en plan TARINGA*/

	switch($_POST["new_content_type"]) 
	{
		case '0': //Minijuego
			//http://foro.elhacker.net/desarrollo_web/problema_con_javascript_documentwrite_me_tiene_mania-t387193.10.html
			echo '
			<div id="c1">
				Link al archivo:<br>
				<script>var e = document.getElementById("c1").getElementsByTagName("BR")[0]; e.parentNode.insertBefore(fUpload("asset"), e.nextSibling);</script>
				Controles:<br>
				<input style="margin-top:5px;" type="button" value="Añadir tecla" onclick="addControl(this.parentNode)" />
			</div>';
			break;
		case '1': //Asset
			echo '
			<div id="c1">
				Link al Asset:<br><input type="text" name="post_link" />
			</div>';
			break;
		case '2': //Servicio web
			echo '
			<div id="c1">
				Link externo:<br><input type="text" name="post_link" />
			</div>';
			break;
		case '3': //Aplicacion de escritorio
			echo '
			<div id="c1">
				Link de descarga:<br><input type="text" name="post_link" />
			</div>';
			break;
		case '4': //Plugin MC
			echo '
			<div id="c1">
				Link al Plugin:<br><input type="text" name="post_link" />
			</div>';
			break;
		default:
			die(':(');
			break;
	}
	echo '
	<div id="c2">
		<center>
			<h2>Galería de fotos y vídeos</h2>
			<div id="bigThumb">No hay ninguna imagen</div>
			<div id="thumbPlace">
				<div class="btnArrow" id="btnLeftArrow" style="display: none;" onclick="arrowClick(this)"></div>
			    <div class="btnArrow" id="btnRightArrow" style="display: none;" onclick="arrowClick(this)"></div>
			    <div id="thumbRect">
				    <div onwheel="wheely(event, this)">
				  		<!-- DIVS GOES HERE -->
				    </div>
			    </div>
			</div>
			<input type="button" value="Añadir foto/vídeo" onclick="document.getElementById(\'filePopup\').style.display = \'block\';" />
		</center>
	</div>';
}
else if(isset($_GET['files'])) 
{
	require_once "../../libs/dropbox-sdk/lib/Dropbox/autoload.php";

    $files = array();
    $ext = array();

    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/temp/';
    foreach($_FILES as $file)
        if(move_uploaded_file($file['tmp_name'], $uploaddir.basename($file['name']))) {
            $files[] = $uploaddir.$file['name'];
            $ext[] = strtolower(end(explode('.',$file['name'])));
        }
        else
            die(json_encode(array('error' => 'An internal error has ocurred!')));

    $results = array();

    $fg = $_GET['files'];
    $setts = array(
    	'asset' => array('ext' => array('unity3d'), 'place' => '/Web Unity Assets/'), 
    	'thumb' => array('ext' => array('png', 'jpg', 'jpeg', 'gif'), 'place' => '/Web Thumbnails/'),
    	'avatar' => array('ext' => array('png', 'jpg', 'jpeg'), 'place' => '/Web Avatars/'));

    $errors = array();

    foreach($files as $k => $fl) 
    {

    	if(!in_array($ext[$k], $setts[$fg]['ext'])) 
    	{
    		$errors[] = "Extensión no permitida.";
    		break;
    	}

    	$accessToken = "Kx74rfa-w3AAAAAAAAAAZx710CUmOKgn-GtnaH0SPOIl8UnhA13e5I9tBBWmdyMt";

		$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");

		$f = fopen($fl, "rb");
		$p = $setts[$fg]['place'].basename($fl);
		$result = $dbxClient->uploadFile($p, dbx\WriteMode::add(), $f);
		fclose($f);

		$file = $dbxClient->getMetadata($p);

		$dropboxPath = $file['path'];
		$pathError = dbx\Path::findError($dropboxPath);
		if ($pathError !== null) {
		    fwrite(STDERR, "Invalid <dropbox-path>: $pathError\n");
		    die;
		}

		$link = $dbxClient->createTemporaryDirectLink($dropboxPath);
		$dw_link = $link[0]."?dl=1";

		unlink($fl);

		$results[] = array('result' => $result, 'link' => $dw_link);
    }

    if(!count($errors))
    	die(json_encode($results));
    else
    	die(json_encode(array('error' => $errors)));
}
else if(isset($_POST['action'])) 
{
	switch ($_POST['action']) {
		case 'admin_sendpost':
			ProjectManager::manage(isset($_POST['id']) ? $_POST['id'] : null, $_POST['info']);
			break;

		case 'admin_sendad':
			//AdManager::manage(isset($_POST['id']) ? $_POST['id'] : null, $_POST['info']);
			$lang = key(json_decode($_POST['info'], true));
			$text = json_decode($_POST['info'], true)[$lang];
			if (isset($_POST['id']))
			{
				$id = $_POST['id'];
				Announcement::editAnnounce($id,$lang,$text);
			}else{
				Announcement::newAnnounce(Announcement::getLastPosAnnouncements()[0]['pos']+1,$lang,$text);
			}

			break;

		//Unificar estos 3 cases?
		case 'deleteproject':
			$id = (int)mysqli_escape_string(Database::conn(), @$_POST['id']);
			Query::run("DELETE FROM projects WHERE id = $id");
            ContentManager::$msg_type = 1;
            ContentManager::addMsg('projectDeleted');
			break;

		case 'deletead':
			$id = (int)mysqli_escape_string(Database::conn(), @$_POST['id']);
			Query::run("DELETE FROM announcements WHERE id = $id");
            ContentManager::$msg_type = 1;
            ContentManager::addMsg('adDeleted');
			break;

		case 'deleteuser':
			$id = (int)mysqli_escape_string(Database::conn(), @$_POST['id']);
			Query::run("DELETE FROM users WHERE id = $id");
            ContentManager::$msg_type = 1;
            ContentManager::addMsg('userDeleted');
			break;

		case 'loadprojectmanager':
			$id = (int)mysqli_escape_string(Database::conn(), @$_POST['id']);
			echo json_encode(unserialize(Query::firstResult("SELECT data FROM projects WHERE id = '$id'")));
			break;

		case 'loadadmanager':
			$id = (int)mysqli_escape_string(Database::conn(), @$_POST['id']);
			echo json_encode(Announcement::getAnnouncementById($id,true));
			//echo json_encode(unserialize(Query::firstResult("SELECT data FROM announcements WHERE id = '$id'")));
			break;

		case 'get_author':
			echo UserUtils::getStat();
			break;

		case 'getpreset':
			$id = mysqli_escape_string(Database::conn(), @$_POST['id']);
			echo json_encode(unserialize(Query::firstResult("SELECT data FROM projects WHERE id = '$id'")));
			break;

		case 'changeProjectRemark':
			$id = mysqli_escape_string(Database::conn(), @$_POST['id']);
			$r = unserialize(Query::firstResult("SELECT data FROM projects WHERE id = '$id'"));
			$rem = mysqli_escape_string(Database::conn(), @$_POST['remark']);
			if($r)
			{
				$r['markedProject'] = $rem;
				$r = serialize($r);
				$r1 = Query::run("UPDATE projects SET data = '$r' WHERE id = '$id'");
				if(!$r1)
					ContentManager::addMsg('hackTry');
			}
			else
				ContentManager::addMsg('hackTry');
			if(!count(ContentManager::$msg_list))
			{
				ContentManager::$msg_type = 1;
				ContentManager::addMsg($rem ? 'stickedProject' : 'unstickedProject');
			}
			break;

		case 'admin_notepad':
			SettingsManager::manage('notepad', @$_POST['notepad_text'], 'editNotepad');
			break;

		case 'admin_ann_settings':
			SettingsManager::manage('announcer_settings', serialize(array('stop_onmouseover' => @$_POST['stop_onmouseover'], 'random_change' => @$_POST['random_change'], 'open_blank' => @$_POST['open_blank'], 'speed' => @$_POST['speed'])), 'editAnnSettings');
			break;

		case 'admin_prj_settings':
			if(isset($_POST['max_entries']) && @$_POST['max_entries'] > 100)
			{
				ContentManager::addMsg('maxEntriesAllowedReached');
				ContentManager::$msg_type = 0;
				$_POST["showlist"] = true;
			} else
				SettingsManager::manage('project_settings', serialize(array('max_entries' => (isset($_POST['max_entries']) && strlen($_POST['max_entries'])) ? $_POST['max_entries'] : '10')), 'editProjSettings');
			break;

		case 'change_ad_order':
			if(isset($_POST['id']) && isset($_POST['new_pos']) && isset($_POST['pos'])) 
			{
				$id = $_POST['id'];
				$new_pos = $_POST['new_pos'];
				$pos = $_POST['pos'];
			    $r1 = Query::run("UPDATE announcements SET pos = '$pos' WHERE pos = '$new_pos'");
			    $r2 = Query::run("UPDATE announcements SET pos = '$new_pos' WHERE id = '$id'");
			    if($r1 && $r2) 
			    {
                    ContentManager::$msg_type = 1;
                    ContentManager::addMsg('orderChanged');
				} else
                    ContentManager::addMsg('hackTry');
			} else
                ContentManager::addMsg('hackTry');
			break;

		case 'admin_upt_userinfo':
			$id = mysqli_escape_string(Database::conn(), @$_POST['profile_id']);
			$username = mysqli_escape_string(Database::conn(), @$_POST['username']);
			$rank = mysqli_escape_string(Database::conn(), @$_POST['rank']);
			$avatar = mysqli_escape_string(Database::conn(), @$_POST['avatar_link']);
			ProfileManager::editProfileData($id, $username, $rank, $avatar);
			break;

		case 'admin_upt_idinfo':
			$id = mysqli_escape_string(Database::conn(), @$_POST['profile_id']);
			$password = @$_POST['password'];
			$confirm_password = @$_POST['confirm_password'];
			$email = mysqli_escape_string(Database::conn(), @$_POST['email']);
			$confirm_email = mysqli_escape_string(Database::conn(), @$_POST['confirm_email']);
			ProfileManager::editIdentificationData($id, $password, $confirm_password, $email, $confirm_email);
			break;

		case 'admin_register_settings':
			SettingsManager::manage('register_settings', serialize(array('reg_access_opt' => @$_POST['reg_opt'])), 'editRegSettings');
			break;
		
		default:
			die('Action '.$_POST['action'].' is not registered!');
			break;
	}

	if(isset($_POST["showlist"]))
		echo ContentManager::getList(Lang::$lang);
}
else 
	if(!isset($_POST)) 
	{
		header("Location: /");
		exit;
	}

?>