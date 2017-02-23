<?php
    class LangManager
    {
        public static $supported_langs = array("es", "en");
        public $lang_name = "es";
        public $text = array();
        public function __construct($ln = "es")
        {
            $this->lang_name = $ln;
            if(isset($_GET['lang']))
            {
                $this->lang_name = $_GET['lang'];
                setcookie('lang', $this->lang_name, time() + 3600 * 24 * 30, '/');
            }
            else if(isset($_COOKIE['lang']))
                $this->lang_name = $_COOKIE['lang'];
            if(file_exists(DirectoryManager::getPath('lang/lang.'.$this->lang_name.'.php')))
            {
                include(DirectoryManager::getPath('lang/lang.'.$this->lang_name.'.php'));
                $this->text = $lang;
            }
            else
                die("Lang file not found in ".DirectoryManager::getPath('lang/lang.'.$this->lang_name.'.php')."!");
        }
        public function getLangJS()
        {
            return json_encode(array("lang_name" => $this->lang_name, "lang" => $this->text));
        }
        public static function getLangOpts($idioma = 'es')
        {
            $s = "";
            foreach(LangManager::$supported_langs as $v)
            {
                if(file_exists(DirectoryManager::getPath('lang/lang.'.$v.'.php')))
                    include(DirectoryManager::getPath('lang/lang.'.$v.'.php'));
                if ($idioma['lang'] == $v)
                {
                    $s .= '<option value="'.$v.'" selected>'. $lang["caption"].' ('.$lang[$v.'_caption'].')</option>';

                }else{
                    $s .= '<option value="'.$v.'" >'. $lang["caption"].' ('.$lang[$v.'_caption'].')</option>';

                }
            }
            return $s;
        }
    }

    class Lang
    {
        public static $lang;
        public static function InitLangs()
        {
            self::$lang = new LangManager();
        }
    }

    Lang::InitLangs();

//$lang = new LangManager(); //Set it to be used by the following includes