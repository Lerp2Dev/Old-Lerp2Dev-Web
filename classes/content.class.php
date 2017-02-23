<?php

	class ContentUtils
	{
		public static function dump($data)
		{
			if (is_array($data)) { //If the given variable is an array, print using the print_r function.
				print "<pre>-----------------------\n";
				print_r($data);
				print "-----------------------</pre>";
			} else if (is_object($data)) {
				print "<pre>==========================\n";
				var_dump($data);
				print "===========================</pre>";
			} else {
				print "=========&gt; ";
				var_dump($data);
				print " &lt;=========";
			}
		}

		public static function removenl($replace, $string)
		{
			return str_replace(array("\n\r", "\n", "\r"), $replace, $string);
		}

		public static function fix_serialized($data)
		{
			return preg_replace_callback('!s:(\d+):"(.*?)";!', function($match) {
				return ($match[1] == strlen($match[2])) ? $match[0] : 's:'.strlen($match[2]).':"'.$match[2].'";';
			}, $data);
		}

		public static function searchFromSQL($arr, $column, $as, $like)
		{
			if(empty($as))
				return $arr;
			$farr = array();
			for($i = 0; $i < count($arr); ++$i) {
				$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr[$i]));
				foreach($it as $k => $v)
					if($column === $k && (($like && strpos($v, $as) !== false) || (!$like && $v == $as)))
						$farr[] = $arr[$i];
			}
			return $farr;
		}

		//http://stackoverflow.com/questions/16823611/passing-multiple-arguments-to-function-php
		//Esto lo voy a utilizar para hacer la funcion del idioma (hare un string format)

		public static function str_format()
		{
			$args = func_get_args();
			$str = $args[0];
			for($i=1; $i < count($args); ++$i)
				$str = str_replace("{".($i-1)."}", $args[$i], $str);
			return $str;
		}

        public static function getAvatarBubble($row)
        {
            return '<div class="avatar_cont" onmouseover="toggleAvatarBubble(this.children[0], true);" onmouseout="toggleAvatarBubble(this.children[0], false);">
						<div class="avatar_bubble" style="display: none;">
							<div>
								<table style="margin: 0px;padding: 0px;border-collapse: collapse;border-spacing:0px;border:0px;width:100%;">
									<tr>
										<td rowspan="2" colspan="2" style="text-align: center;">
											<img class="avatar large" src="'.UserUtils::getAvatar($row['avatar']).'" style="margin: -20px 1px 1px 1px; position: relative; top: 12px;" />
											<div class="medal_table">
												<!--- <h2 style="display: inline;">0</h2><span style="position: relative;top: -3px;height: 27px;display: inline-block;margin-left: 5px;font-size:12px;color:#ddd;">REPUTACIÓN</span> -->
												<!--- In the future, when there is any useful thing on the web reputation and people reached, I will put one there -->
												<div class="medal gold">999</div>
												<div class="medal silver">999</div>
												<div class="medal bronze">999</div>
												<div class="medal unknown" style="margin-top: 3px;">999</div>
											</div>
										</td>
										<td colspan="2">
											<h3>'.$row['username'].'</h3>
										</td>
									</tr>
									<tr>
										<td>
											<div class="user-cap large"><div>0</div>Profile visits</div>
											<div class="user-cap large"><div><img src="./images/icons/mp.png" style="width:16px;height:12px;" /></div>Send PM</div>
										</td>
										<td style="position: relative; left: -69px;">
											<div class="user-cap large"><div>0</div>Friends</div>
											<div class="user-cap large"><div><img src="./images/icons/add_friend.png" /></div>Add friend</div>
										</td>
										<td style="position: relative; left: -139px;">
											<div class="user-cap large"><div>0</div>Followers</div>
											<div class="user-cap large"><div><img src="./images/icons/follow_user.png" /></div>Follow</div>
										</td>
										<td style="position: relative; left: -140px;">
											<div class="user-cap large"><div>0</div>Following</div>
											<div class="user-cap large"><div><img src="./images/icons/deny.png" /></div>Block</div>
										</td>
										<td style="position: relative; left: -141px;">
											<div class="user-cap large"><div>0</div>Referrals</div> <!--- Put profile visits in the profile not in this box -->
											<div class="user-cap large"><div><img src="./images/icons/flag.png" /></div>Report</div>
										</td>
									</tr>
									<!--- <tr>
										<td>
											<div class="user-cap" style="margin-top: -2px;">'.UserUtils::getCurStatus($row['last_activity']).'</div>
											<div class="user-cap"><img src="./images/icons/shield.png" style="margin: -3px 0 0 0;"><span style="margin: -28px 0 0 20px;">'.UserUtils::getRankCapById($row['rank_id']). '</span></div>
										</td>
										<td>
											<div class="user-cap"><img title="Género" src="'.($row['gender'] ? './images/icons/female.png' : './images/icons/male.png').'" style="width: 16px;height: 16px;" /><span>'.($row['gender'] ? 'Mujer' : 'Hombre').'</span></div>
											<div class="user-cap"><img title="Nivel" src="./images/icons/empty-star.png" /><span>Nivel 0</span></div>
										</td>
										<td>
											<div class="user-cap wider"><img title="Nacionalidad" src="./images/icons/world.png" /><span style="margin: -24px 0 0 20px;">España (<img src="https://www.hotelcasanamaria.com/templates/images/flag_es.png" />)</span></div> <!--- Nationality
											<div class="user-cap wider"><img title="Tiempo desde el registro / fecha (en el tooltip)" src="./images/icons/calendar.png" /><span title="Registrado el 01, lunes de enero de 2016 a las 00h 00m">0 años de antigüedad</span></div>
										</td>
										<td>
											<div class="user-cap"><img title="Edad" src="./images/icons/cake.png" /><span>100 años</span></div>
											<div class="user-cap"><img title="Estatus de normas" src="./images/icons/percentage.png" /><span>100%</span></div> <!--- This is like the Karma System from Taringa, but, in this case when this reach 0% you are auto-banned for 1d the first time, next, 3d, 1w, 1m, 3m, 6m, 1y and permaban										
										</td>
										<td>
											<div class="user-cap wider"><img title="Página web" src="./images/icons/web_globe.png" /><span title="Lerp2Dev! - Our webpage (http://lerp2dev.com/)"><a href="http://lerp2dev.com/">Lerp2Dev! - Our web...</a></span></div>
											<div class="user-cap wider"><img title="Tiempo de actividad" src="./images/icons/clock.png" /><span>1y 1M 1w 1d 1m 1s</span></div>
										</td>
										<td>
											<div class="user-cap wider"><img title="Contacto" src="./images/icons/at.png" /><span title="Correo electrónico">ejemplo@dominio.com</span></div> <!--- There is visualized the favourite contact method you choose from your profile editor
											<div class="user-cap wider"><img title="Fondos de Lerped Coins" src="./images/icons/coins.png" /><span>999.99M Coins</span></div>
											<!--- In the future, when there is any useful thing on the web reputation and people reached
										</td>
									</tr> -->
									<tr>
										<td>
											<div class="user-cap" style="margin-top: -2px;">'.UserUtils::getCurStatus($row['last_activity']).'</div>
											<div class="user-cap"><img src="./images/icons/shield.png" style="margin: -3px 0 0 0;"><span style="margin: -28px 0 0 20px;">'.UserUtils::getRankCapById($row['rank_id']). '</span></div>
										</td>
										<td>
											<div class="user-cap"><img title="Género" src="'.($row['gender'] ? './images/icons/female.png' : './images/icons/male.png').'" style="width: 16px;height: 16px;" /><span>'.($row['gender'] ? 'Mujer' : 'Hombre').'</span></div>
											<div class="user-cap"><img title="Nivel" src="./images/icons/empty-star.png" /><span>Nivel 0</span></div>
										</td>
										<td>
											<div class="user-cap wider"><img title="Nacionalidad" src="./images/icons/world.png" /><span style="margin: -24px 0 0 20px;">España (<img src="./images/msdropdown/icons/blank.gif" class="flag es fnone" style="margin: 0 2px;" />)</span></div> <!--- Nationality -->
											<div class="user-cap wider"><img title="Fondos de Lerped Coins" src="./images/icons/coins.png" /><span>999.99M Coins</span></div>											
											<!---  -->
										</td>
										<!--- <td>
											<div class="user-cap wider"><img title="Página web" src="./images/icons/web_globe.png" /><span title="Lerp2Dev! - Our webpage (http://lerp2dev.com/)"><a href="http://lerp2dev.com/">Lerp2Dev! - Our web...</a></span></div>
											<div class="user-cap wider"><img title="Tiempo de actividad" src="./images/icons/clock.png" /><span>1y 1M 1w 1d 1m 1s</span></div>
										</td> -->
										<td>
											<div class="user-cap wider" style="margin-right: 4px;"><img title="Contacto" src="./images/icons/at.png" /><span title="Correo electrónico">ejemplo@dominio.com</span></div> <!--- There is visualized the favourite contact method you choose from your profile editor -->
											<div class="user-cap wider"><img title="Tiempo desde el registro / fecha (en el tooltip)" src="./images/icons/calendar.png" /><span title="Registrado el 01, lunes de enero de 2016 a las 00h 00m">0 años de antigüedad</span></div>
										</td>
										<td>
											<div class="user-cap"><img title="Edad" src="./images/icons/cake.png" /><span>100 años</span></div>
											<div class="user-cap"><img title="Estatus de normas" src="./images/icons/percentage.png" /><span>100%</span></div> <!--- This is like the Karma System from Taringa, but, in this case when this reach 0% you are auto-banned for 1d the first time, next, 3d, 1w, 1m, 3m, 6m, 1y and permaban -->
										</td>
									</tr> 
									<tr>
										<td colspan="5" style="height: 25px;background: #f8ffb2;font-weight: bold;text-align: center;"><a href="index.php?action=profile&id='.$row['id'].'">Ver perfil</a></td>
									</tr>
								</table>
								<div class="arrow"></div>
							</div>
						</div>
						<img class="avatar" style="margin-right:5px;" src="'.UserUtils::getAvatar($row['avatar']).'" />
					</div>';
        }
    }

	class ContentManager
	{
		public static $msg_type = 0; //0: Error & 1: Success
		public static $msg_list = array();

		public static function addMsg($elem) 
		{
			//global $msg_list;
			array_push(self::$msg_list, $elem);
		}

		public static function getList($lang) //This will display the html elements
		{
			//global $msg_list, $msg_type;

			if(!count(self::$msg_list))
				return null;

			$msg = "";
			if(!self::$msg_type) 
				$msg .= ((count(self::$msg_list) > 1) ? "Los siguientes ".count(self::$msg_list)." errores" : "El siguiente error")." ocurrieron al intentar enviar el formulario:<br><br>";
			for($i = 0; $i < count(self::$msg_list); ++$i) {
				$msg .= ((count(self::$msg_list) > 1 && self::$msg_type == 0) ? "- " : "").self::msgCaption(self::$msg_list[$i], $lang);
				if(count(self::$msg_list) > 1)
					$msg .= "<br>";
			}

			return json_encode(array("success" => self::$msg_type, "msg" => $msg));
		}

		public static function msgCaption($name, $lang)
		{
			if(array_key_exists($name, Lang::$lang->text))
				return Lang::$lang->text[$name];
			else //Custom msg
				return $name;
		}

	}