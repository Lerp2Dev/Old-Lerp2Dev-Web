<?php
    //$droot = $_SERVER["DOCUMENT_ROOT"];
    
    //if(strpos($_SERVER["DOCUMENT_ROOT"], 'xampp') !== false)
    //	$droot .= "/lerp2dev";
    
    //define("REGISTER_HIT", false);
    
    //require_once($droot.'/main.php');
    //require_once('../main.loader.php');

    require_once('../classes/dir.class.php');
    require_once('../classes/lang.class.php');
    $lang = new LangManager();
    
    /*if(file_exists('lang/lang.'.Lang::$lang->lang_name.'.php'))
        require_once('lang/lang.'.Lang::$lang->lang_name.'.php');*/
    
    if(isset($_GET['getlang'])) //echo json_encode(array("lang_name" => $lang_name, "lang" => $lang));
        echo Lang::$lang->getLangJS();
    else 
    {
        header("Location: /");
        exit;
    }