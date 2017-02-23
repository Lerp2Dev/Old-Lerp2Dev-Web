<?php
#region "Profile Classes"
    class ProfileManager
    {
        public static function editProfileData($id, $username, $rank, $avatar)
        {
            UserUtils::checkValidUsername($username, true);
            if(!count(ContentManager::$msg_list))
            {
                Query::run("UPDATE users SET `username` = '$username', `rank_id` = '$rank', `avatar` = '$avatar' WHERE id = '$id'");
                ContentManager::addMsg('profileEdited');
                ContentManager::$msg_type = 1;
            }
        }

        public static function editIdentificationData($id, $password, $confirm_password, $email, $confirm_email)
        {
            //global $conn, $msg_list, $msg_type;
            $new_pass = false;
            $new_mail = false;
            if(strlen($password) && strlen($confirm_password))
            {
                UserUtils::checkValidPassword($password, $confirm_password);
                $password = md5($password);
                $new_pass = true;
            }
            if(strlen($email) && strlen($confirm_email)) {
                UserUtils::checkValidMail($email, $confirm_email);
                $new_mail = true;
            }
            if(!count(ContentManager::$msg_list) && ($new_pass || $new_mail))
            {
                if($new_pass)
                    Query::run("UPDATE users SET `password` = '$password' WHERE id = '$id'");
                if($new_mail)
                    Query::run("UPDATE users SET `email` = '$email' WHERE id = '$id'");
                ContentManager::$msg_type = 1;
                ContentManager::addMsg('identificationEdited');
            }
        }
    }
#endregion

#region "User & Visitor & Client Classes"
    class ClientUtils
    {
        public static $curClient;
        public static function getCurClient()
        {
            //Hacer que se compruebe primero la sesión de logueo
            if(UserUtils::isOnline()) //Aquí tenemos que hacer la gestión para obtener el tiempo online desde la última sesión
            {
                $stats = UserUtils::getStats();
                $id = $stats['id'];
                $last_conn = $stats['last_activity'];
                $final_time = 0;
                $now = CoreUtils::Now();
                if($last_conn + SESSION_TIME * 60 < $now)
                { //User disconnected
                    $started_conn = $stats['started_conn_time'];
                    //$online_time = $stats['online_time'];
                    $final_time += $last_conn - $started_conn;
                    Query::run("UPDATE users SET started_conn_time = '$now' WHERE id = '$id'");
                    Query::run("UPDATE users SET online_time = '$final_time' WHERE id = '$id'");
                }
                Query::run("UPDATE users SET last_activity = '$now' WHERE id = '$id'");
                self::$curClient = new User($stats);
            }
            else
            {
                VisitorUtils::regHit();
                self::$curClient = new Visitor(VisitorUtils::getStats());
            }
            //Y si no está logeado guardar un hit
        }
        public static function getClientIP()
        {
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }
    }
    class User
    {
        public $id;
        public $username;
        public $password;
        public $ip;
        public $user_agent;
        public $reg_time;
        public $started_conn_time;
        public $last_activity;
        public $online_time;
        public $real_name;
        public $email;
        public $specialties;
        public $code;
        public $activation;
        public $prem_days;
        public $ref_id;
        public $coins;
        public $exp;
        public $lvl;
        public $avatar;
        public $gender;
        public $birthdate;
        public $location;
        public $rank_id;
        public $ban_time;
        public $ban_duration;
        public $ban_reason;
        public $rank;
        public $rank_duration;
        public function __construct($data)
        {
            $this->id = $data["id"];
            $this->username = $data["username"];
            $this->password = $data["password"];
            $this->ip = $data["ip"];
            $this->user_agent = $data["user_agent"];
            $this->reg_time = $data["reg_time"];
            $this->started_conn_time = $data["started_conn_time"];
            $this->last_activity = $data["last_activity"];
            $this->online_time = $data["online_time"];
            $this->real_name = $data["real_name"];
            $this->email = $data["email"];
            $this->specialties = $data["specialties"];
            $this->code = $data["code"];
            $this->activation = $data["activation"];
            $this->prem_days = $data["prem_days"];
            $this->ref_id = $data["ref_id"];
            $this->coins = $data["coins"];
            $this->exp = $data["exp"];
            $this->lvl = $data["lvl"];
            $this->avatar = $data["avatar"];
            $this->gender = $data["gender"];
            $this->birthdate = $data["birthdate"];
            $this->location = $data["location"];
            $this->rank_id = $data["rank_id"];
            $this->ban_time = $data["ban_time"];
            $this->ban_duration = $data["ban_duration"];
            $this->ban_reason = $data["ban_reason"];
            $this->rank = $data["rank"];
            $this->rank_duration = $data["rank_duration"];
        }
    }

    class Visitor
    {
        public $id;
        public $ip;
        public $user_agent;
        public $reg_time;
        public $last_activity;
        public $hits;
        public function __construct($data)
        {
            $this->id = $data["id"];
            $this->ip = $data["ip"];
            $this->user_agent = $data["user_agent"];
            $this->reg_time = $data["reg_time"];
            $this->last_activity = $data["last_activity"];
            $this->hits = $data["hits"];
        }
    }

    class UserUtils
    {
        public static function NewGuid($input = null)
        {
            if(empty($input)) $input = str_replace(".", "", ClientUtils::getClientIP());
            $s = strtoupper(md5(base64_encode($input)));
            $guidText =
                substr($s, 0, 8) . '-' .
                substr($s, 8, 4) . '-' .
                substr($s, 12, 4). '-' .
                substr($s, 16, 4). '-' .
                substr($s, 20);
            return $guidText;
        }

        public static function Attemps($name, $action)
        {
            if(empty($_SESSION['ATTEMPS'])) {$attempArray = array();} else {$attempArray = $_SESSION['ATTEMPS'];}
            switch ($action) {

                case 'set':
                case 'reset': //Sets or reset all the attemps
                    $attempArray[$name] = array('TRIES' => MAX_ATTEMPS);
                    break;

                case 'substract':
                    if(isset($attempArray[$name])) {
                        $attempArray[$name]['TRIES'] -= 1;
                    } else {
                        UserUtils::Attemps($name, 'set');
                        $attempArray[$name]['TRIES'] = MAX_ATTEMPS - 1;
                    }
                    break;

                case 'get':
                    if(isset($attempArray[$name])) {
                        return $attempArray[$name]['TRIES'];
                    } else {
                        UserUtils::Attemps($name, 'set');
                        return MAX_ATTEMPS;
                    }
                    break;

                default:
                    return false;
                    break;
            }
            $_SESSION['ATTEMPS'] = $attempArray;
            return false;
        }

        public static function getStats($id = null, $query = "*")
        {
            if(isset($id))
                return mysqli_fetch_array(Query::run("SELECT {$query} FROM users WHERE id = '$id'"));
            else
                if(self::isOnline()) 
                {
                    $me = SessionManager::getLoginInfo($_COOKIE['loginSession']);
                    $id = $me['user_id'];
                    if(isset($me))
                        return mysqli_fetch_array(Query::run("SELECT {$query} FROM users WHERE id = '$id'"));
                }
            return false;
        }

        public static function getStat($id = null, $stat = "id")
        {
            return self::getStats($id, $stat)[$stat];
        }

        public static function getStatsByIP($ip = null, $query = "*")
        {
            if(isset($ip))
                return mysqli_fetch_array(Query::run("SELECT {$query} FROM users WHERE ip = '$ip'"));
            else
                if(self::isOnline())
                {
                    $ip = ClientUtils::getClientIP();
                    if(isset($me))
                        return mysqli_fetch_array(Query::run("SELECT {$query} FROM users WHERE ip = '$ip'"));
                }
            return false;
        }

        public static function getStatByIP($ip = null, $stat = "id")
        {
            return self::getStatsByIP($ip, $stat)[$stat];
        }

        public static function isRanked($name)
        {
            if(self::isOnline())
            {
                $myRank = self::getStat(null, "rank_id");
                return mysqli_fetch_array(Query::run("SELECT name FROM ranks WHERE id = '$myRank'"))["name"] == $name;
            }
            return false;
        }

        public static function getRankIdByName($name)
        {
            return Query::firstResult("SELECT id FROM ranks WHERE name = '$name'");
        }

        public static function isOnline($id = null)
        {
            if(!isset($id))
                return isset($_COOKIE["loginSession"]);
            else
                return mysqli_fetch_array(Query::run("SELECT last_activity FROM users WHERE id = '$id'"))['last_activity'] + SESSION_TIME*60 > time();
        }

        public static function userExists($username)
        {
            return Query::count('id', 'users', "WHERE username = '$username'");
        }

        public static function getOnlinePeople($t = 120)
        {
            $elem = array();
            $result = Query::run(ContentUtils::str_format("SELECT * FROM visitors WHERE {0}-last_activity < {1}", CoreUtils::Now(), $t));
            while($rs = mysqli_fetch_row($result))
                $elem[] = $rs;
            return $elem;
        }

        public static function checkValidCaptcha()
        {
            if (empty($_POST["vercode"]))
                ContentManager::addMsg('emptyCaptcha');
            else if($_POST["vercode"] != $_SESSION["vercode"])
                ContentManager::addMsg('incorrectCaptcha');
        }

        public static function checkValidUsername($username, $edit)
        {
            if(empty($username))
                ContentManager::addMsg('emptyUsername');
            else if(preg_match('/\^|`|\*|\+|<|>|\[|\]|¨|´|\{|\}|\||\\|\"|\@|·|\#|\$|\%|\&|\¬|\/|\(|\)|=|\?|\'|¿|ª|º/', $username))
                ContentManager::addMsg('forbiddenChars');
            else if(!$edit && userExists($username))
                ContentManager::addMsg('userExists');
            else if(strlen($username) <= 4)
                ContentManager::addMsg('shortUsername');
            else if(strlen($username) > 20)
                ContentManager::addMsg('longUsername');
        }

        public static function checkValidPassword($password, $cpass)
        {
            if(empty($password))
                ContentManager::addMsg('emptyPassword');
            else if(strlen($password) < 6)
                ContentManager::addMsg('shortPassword');
            else if($cpass != $password)
                ContentManager::addMsg('wrongCPassword');
            else if(!preg_match("#[0-9]+#", $password))
                ContentManager::addMsg('numPassword');
            else if(!preg_match("#[a-zA-Z]+#", $password))
                ContentManager::addMsg('letterPassword');
        }

        public static function checkValidMail($email, $cmail)
        {
            if(empty($email))
                ContentManager::addMsg('emptyEmail');
            else if(!CoreUtils::isValidMail($email))
                ContentManager::addMsg('invalidMail');
            else if(isset($cmail) && $cmail != $email) //Se podría dejar así por el momento
                ContentManager::addMsg('wrongCMail');
        }

        public static function getAvatar($a)
        {
            return strlen($a) ? $a : './images/avatars/no-avatar.png';
        }

        public static function getGender($g)
        {
            return $g ? Lang::$lang->text['man'] : Lang::$lang->text['woman'];
        }

        public static function getRankCapById($id)
        {
            $r = Query::firstResult("SELECT caption FROM ranks WHERE id = $id");
            return is_array($r) && array_key_exists(Lang::$lang->lang_name, $r) ? $r[Lang::$lang->lang_name] : 'Usuario'; //cambiar name por caption
        }

        public static function getCurStatus($last_act)
        {
            return CoreUtils::Now() - $last_act < SESSION_TIME * 60 ? '<img src="./images/icons/online.png" /><span>Online</span>' : '<img src="./images/icons/offline.png" /><span>Offline</span>';
        }
    }

	class UserRegister
    {
        public static function Register($username, $password, $cpass, $email)
        {
            checkValidCaptcha();
            checkValidUsername($username, false);
            checkValidPassword($password, $cpass);
            checkValidMail($email, null); //As a reminder...

            $password = md5($password);

            if(!count(ContentManager::$msg_list))
            {
                $code = NewGuid();
                self::AddUser($username, $password, $code, $email);
            }
        }

        public static function AddUser($username, $password, $code, $email)
        {
            $ip = getClientIP();
            $now = time();

            Query::run("INSERT INTO users (username, password, ip, reg_time, email, last_activity, code) VALUES ('$username', '$password', '$ip', '$now', '$email', '$now', '$code')");

            ContentManager::$msg_type = 1;
            ContentManager::addMsg("register");
        }
    }

    class UserLogin
    {
		public static function Login($expireTime, $username, $password)
		{
			if(empty($username))
                ContentManager::addMsg('emptyUsername');
			if(empty($password))
                ContentManager::addMsg('emptyPassword');
			if(is_numeric($expireTime))
				$expireTime = (int)$expireTime;
			else
                ContentManager::addMsg('hackTry');
			if(!ContentManager::$msg_list) {
				$row = mysqli_fetch_assoc(Query::run("SELECT id FROM users WHERE username='{$username}' AND password='".md5($password)."'"));
				if(!isset($row['id']))
				{
					//LoginAttemps('wrong'); //?
                    self::LoginAttemps();
					if(UserUtils::Attemps('LOGIN', 'get') == 0)
                        ContentManager::addMsg('attempsWasted');
					else
                        ContentManager::addMsg('wrongCredentials');
					$_SESSION['LOGIN_LAST_ATTEMP'] = time();
				}
				else 
				{
					/*$code = getUStats(getUID($username))['code'];
					$session_id = md5($username.md5($password).$code);
					setcookie("SESSION_ID", base64_encode($username."|".$session_id), time()+$expireTime*60, "/");*/
					//$_SESSION['login']['username'] = $row['username'];
					//$_SESSION['login']['id'] = $row['id'];
                    $user_id = $row['id'];
                    $ip = ClientUtils::getClientIP();
                    $session_id = UserUtils::NewGuid();
                    $reg_time = CoreUtils::Now();
                    $exp_time = $reg_time+$expireTime*60;
					setcookie("loginSession", $session_id, $exp_time, "/");
                    Query::run("INSERT INTO login_sessions (user_id, ip, session_id, reg_time, exp_time) VALUES ('$user_id', '$ip', '$session_id', '$reg_time', '$exp_time')");
					ContentManager::$msg_type = 1;
                    ContentManager::addMsg("login");
				}
			}
		}

        public static function LoginAttemps()
        {
            if(UserUtils::Attemps('LOGIN', 'get') > 0)
                UserUtils::Attemps('LOGIN', 'substract');
            else
                if(isset($_SESSION['LOGIN_LAST_ATTEMP']) && (time() - $_SESSION['LOGIN_LAST_ATTEMP'] > SESSION_TIME*60))
                    UserUtils::Attemps('LOGIN', 'reset');
        }
	}

    class UserLogout
    {
        public static function Logout()
        {
            $_SESSION = array();
            session_destroy();
            setcookie('loginSession', null, -1, '/');
            ContentManager::$msg_type = 1;
            ContentManager::addMsg('logout');
        }
    }

    class VisitorUtils
    {
        public static function regHit()
        {
            $ip = ClientUtils::getClientIP();
            $agent = $_SERVER["HTTP_USER_AGENT"];
            $reg_time = CoreUtils::Now();
            //$refer = null; //Va a tener funcionalidad en algún futuro seguramente
            //if(isset($_GET['ref'])) $refer = mysqli_real_escape_string(Database::conn(), $_GET['ref']);
            //Make the query
            if(!Query::count('ip', 'visitors', "WHERE ip = '$ip'")) // check if the IP is in database //If not , add it.
                Query::run("INSERT INTO visitors (ip, user_agent, reg_time, last_activity) VALUES ('$ip', '$agent', '$reg_time', '$reg_time')") or die('Could not continue: '.mysqli_error(Database::conn()));
            else  //Else, then update some things...
                Query::run("UPDATE visitors SET hits = hits+1 WHERE ip = '$ip'") or die('Could not continue: '.mysqli_error(Database::conn()));

        }
        public static function getStats($ip = null, $query = "*")
        {
            if(empty($ip))
                $ip = ClientUtils::getClientIP();
            if(isset($ip))
                return mysqli_fetch_array(Query::run("SELECT {$query} FROM visitors WHERE ip = '$ip'"));
            return false;
        }

        public static function getStat($ip, $stat = "id")
        {
            return self::getStats($ip, $stat)[$stat];
        }

        public static function checkVisitorRegister($ip = null)
        {
            if(empty($ip))
                $ip = ClientUtils::getClientIP();
            $c = UserUtils::getStatsByIP($ip);
            return $c != null;
        }
    }
#endregion