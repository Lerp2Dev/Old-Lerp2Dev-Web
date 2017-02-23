<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 07/05/2016
 * Time: 23:20
 */

//SESSION START

session_set_cookie_params(2*7*24*60*60);
session_start();

//XAMPP DETECTION

//define("PROJECT_FOLDER_NAME", "lerp2dev-beta")

//if(strpos($_SERVER["DOCUMENT_ROOT"], 'xampp') !== false)
//    $_SERVER["DOCUMENT_ROOT"] .= "/".PROJECT_FOLDER_NAME;

define("INCLUDES", "includes/", true);
define("ADMIN_INCLUDES", "includes/admin/includes/", true);
define("SESSION_TIME", 30, true);
define("MAX_ATTEMPS", 5, true);
define("FLOOD_TIME", 30, true);

if(!defined("CONNECT"))
    define("CONNECT", true);

if(!defined("REGISTER_HIT"))
    define("REGISTER_HIT", true);

// #####################################
// ######### Include Classes ###########
// #####################################

require_once('main.settings.php');
require_once('classes/db.class.php');
require_once('classes/dir.class.php');
require_once('classes/lang.class.php');
require_once('classes/core.class.php');
require_once('classes/sessions.class.php');
require_once('classes/settings.class.php');
require_once('classes/managers.class.php');
require_once('classes/content.class.php');
require_once('classes/users.class.php');
require_once('classes/paginator.class.php');
require_once('classes/announcement.class.php');

//Client init
ClientUtils::getCurClient();