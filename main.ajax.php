<?php

define("REGISTER_HIT", false);
include('main.loader.php');

if(isset($_POST))
{
	switch(@$_POST["action"]) //Voy a hacer que los cases esten mejor escritas, dos palabras la primera en miuscula y la segunda en mayuscula
	{
		case "login":
			$expireTime = @$_POST['duration'];
			$username = mysqli_escape_string(Database::conn(), @$_POST['username']);
			$password = @$_POST['password'];
			UserLogin::Login($expireTime, $username, $password);
			break;

		case "register":
			$username = mysqli_escape_string(Database::conn(), @$_POST['username']);
			$password = @$_POST['password'];
			$email = mysqli_escape_string(Database::conn(), @$_POST['email']);
			$cpass = @$_POST['pass_confirm'];
			UserRegister::Register($username, $password, $cpass, $email);
			break;

		case "logout":
			if(UserUtils::getStat() !== null)
				UserLogout::Logout();
			break;

		case 'admin-enter':
			$pass = $_POST["adminpass"];
			if($pass == Settings::$adminpass)
			{
				$_SESSION["admin"] = true;
				header("Refresh:0");
			}
            else
				ContentManager::addMsg('wrongAdminPass');
			if(!ContentManager::$msg_list)
			{
				ContentManager::$msg_type = 1;
				ContentManager::addMsg("admin-login");
			}
			break;

		case 'getOnlinePeople':
			$data = $_POST['data'];
			$t = 120;
			if(isset($data) && is_numeric($data)) {$t = $data*60;}
			echo count(UserUtils::getOnlinePeople($t));
			break;

		case 'getads':
			
			$i = 0;
			$rawdata = array();
			$resultado = Announcement::getAnnouncementsByLang(Lang::$lang->lang_name,Announcement::getRandom());
			echo json_encode($resultado);
			break;

		case 'getSetting':
			$name = @$_POST['name'];
			if(isset($name)) 
			{
				$sett = SettingsManager::get($name);
				$data = @unserialize($sett);
				echo json_encode($data !== false ? $data : $sett);
			} 
			//else 
			//	addMsg('undefinedSettName');
			break;
			
		default:
			die("Action '".@$_POST["action"]."' not registered!");
			break;
	}

	if(isset($_POST["showlist"]))
		echo ContentManager::getList(Lang::$lang);
}
else 
{
	header("Location: /");
	exit;
}