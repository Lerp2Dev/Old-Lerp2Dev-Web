<?php

class SettingsManager
{
    public static function gets($query = "*")
    {
        $query = "SELECT {$query} FROM settings";
        if(isset($is_admin)) $query .= "WHERE is_admin = '$is_admin'";
        $result = Query::run($query);
        $arr = array();
        while($row = mysqli_fetch_assoc($result)) {
            $data = @unserialize($row['data']);
            if($data !== false)
                $row['data'] = $data;
            $arr[] = $row;
        }
        return $arr;
    }

    public static function get($name)
    {
        return mysqli_fetch_array(Query::run("SELECT data FROM settings WHERE name = '$name'"))['data'];
    }

    public static function manage($name, $text, $success_text)
    {
        //global $conn, $msg_type;
        $r = false; //No se si borro esto q puede pasar
        //$is_admin = strpos($_SERVER["HTTP_HOST"].$_SERVER["HTTP_REFERER"], 'admin') !== false ? '1' : '0';
        if(!Query::count('name', 'settings', "WHERE name = '$name'"))
            $r = Query::run("INSERT INTO settings (name, data) VALUES ('$name', '$text')");
        else
            $r = Query::run("UPDATE settings SET data = '$text' WHERE name = '$name'");
        if($r) {
            ContentManager::$msg_type = 1;
            ContentManager::addMsg($success_text);
        } else
            ContentManager::addMsg('hackTry');
    }
}