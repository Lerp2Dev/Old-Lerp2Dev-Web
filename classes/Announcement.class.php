<?php
//include('../main.loader.php');
//Announcement::setSettings('checked','checked','checked');
define('DEBUG',true);

class Announcement
{
    /**
     * @param int $id
     * @param bool $json
     * @return array Announcement
     */
    public static function getAnnouncementById($id, $json = false)
    {
        $result = self::fechArray("SELECT `id`, `pos`, `lang`, `data` FROM `announcements` WHERE `id` = {$id}");
        return self::run($result[0],$json);
    }

    /**
     * @param string $idioma
     * @param bool $order
     * @param bool $json
     * @return array Announcements
     */
    public static function getAnnouncementsByLang($idioma = 'es',$order = true, $json = false)
    {
        if(!$order)
        {
            $result = self::fechArray("SELECT * FROM `announcements` WHERE `lang` LIKE '{$idioma}' ORDER BY RAND()");

        }else{
            $result = self::fechArray("SELECT * FROM `announcements` WHERE `lang` LIKE '{$idioma}' ORDER BY `announcements`.`pos` ASC");
        }
        return self::run($result,$json);
    }

    /**
     * @param bool $json
     * @param bool $random  Is for random return announcements
     * @return array Announcements
     */
    public static function getAnnouncements($json = false, $random = false)
    {
        if (!$random)
        {
            $result = self::fechArray("SELECT * FROM `announcements`");
        }else{
            $result = self::fechArray("SELECT * FROM `announcements` ORDER BY RAND()");
        }
        return self::run($result,$json);
    }

    /**
     * @param bool $asc
     * @param array $limits[start,end]
     * @param bool $json
     * @return array Announcements
     */
    public static function getAnnouncementsOrdered($asc = false, $limits = array(0,0), $json = false)
    {
        if($limits[0]> 0 || $limits[1]> 0){
            if(!$asc)
            {
                $result = self::fechArray("SELECT * FROM `announcements` ORDER BY `announcements`.`pos` DESC LIMIT {$limits[0]}, {$limits[1]}");
            }else{
                $result = self::fechArray("SELECT * FROM `announcements` ORDER BY `announcements`.`pos` ASC LIMIT {$limits[0]}, {$limits[1]}");
            }
        }else{
            if(!$asc)
            {
                $result = self::fechArray("SELECT * FROM `announcements` ORDER BY `announcements`.`pos` DESC");
            }else{
                $result = self::fechArray("SELECT * FROM `announcements` ORDER BY `announcements`.`pos` ASC");
            }
        }

        return self::run($result,$json);
    }

    /**
     * @param bool $json
     * @return array Announcements
     */
    public static function getLastPosAnnouncements($json = false)
    {
        $result = self::fechArray("SELECT `pos` FROM `announcements` ORDER BY `announcements`.`pos` DESC");
        return self::run($result,$json);
    }

    /**
     * @param $pos
     * @param $lang
     * @param $data
     * @param bool $callback
     * @return string
     */
    public static function newAnnounce($pos, $lang, $data, $callback = false)
    {
        if(!is_int($pos) || !isset($pos) )
        {
            $lastPos = self::getLastPosAnnouncements();
            $pos = $lastPos[0]['pos'] + 1;
        }
        $result = Query::run("INSERT INTO `announcements` (`pos`, `lang`, `data`) VALUES ('{$pos}', '{$lang}', '{$data}')");
        if(self::run($result))
        {
            if ($callback)
            {
                return self::lastId();
            }else{
                return true;
            }
        }
    }

    /**
     * @param $id
     * @param $lang
     * @param $data
     * @param bool $callback
     * @return string
     */
    public static function editAnnounce($id, $lang, $data, $callback = false)
    {
        $result = Query::run("UPDATE `announcements` SET `data` = '{$data}', `lang`= '{$lang}'  WHERE `id` = '{$id}'");

        if(self::run($result))
        {
            echo true;
        }
    }

    /**
     * @param bool $unserialice
     * @param bool $json
     * @return array|bool
     */
    public static function getSettings($unserialice = true, $json = false)
    {
        $result = self::fechArray("SELECT * FROM `settings` WHERE `name` LIKE 'announcer_settings'");
        if ($unserialice)
        {
            if($json)
            {
                echo json_encode(unserialize($result[0]['data']));
            }else{
                return unserialize($result[0]['data']);
            }
        }else{
            echo 'no unserialice<br>';

            if($json)
            {
                echo json_encode($result[0]['data']);
            }else{
                return $result[0]['data'];
            }
        }
    }

    /**
     * @param array $settings
     */
    public static function setSettings($settings)
    {
        $data = serialize($settings);
        $result = Query::run("UPDATE `settings` SET `data` = '{$data}' WHERE `settings`.`id` = 3");
        echo $result;
    }

    /**
     * @param $sql
     * @return array|bool
     */
    public static function fechArray($sql)
    {
        $result = array();
        $res = Database::conn()->query($sql);

        if ($res)
        {
            if($res->num_rows > 0)
            {
                while($row =  mysqli_fetch_assoc($res))
                {
                    $result[] = $row;
                }
                return $result;
            }else{
                return 'No hay registros';
            }

        }else{
            return false;
        }

    }

    /**
     * @return mixed
     */
    public static function lastId()
    {
        $mysql = Database::conn();
        return $mysql->insert_id;
    }


    /**
     * @return string
     */
    public static function getMouseOver()
    {
        $mouseover = self::getSettings();

        if ($mouseover['stop_onmouseover'] == 'checked')
        {
            return 'onmouseover="this.stop();" onmouseout="this.start();"';
        }
    }

    /**
     * @return bool
     */
    public static function getRandom()
    {
        $random = self::getSettings();
        if (!$random['random_change'] == 'checked')
        {
            return true;
        }else{
            return false;
        }
    }
    /**
     * @return int
     */
    public static function getSpeed()
    {
        $speed = self::getSettings();
        return $speed['speed'];
    }

    /**
     * @param $result
     * @param bool $json
     * @return bool|array
     */
    public static function run($result, $json = false)
    {
        if(!$result)
        {
            if(DEBUG)
            {
                $fail = new Exception('Error en la consulta.');
                echo    'Msg: '. $fail->getMessage() ." <br> 
                        Archivo: " . $fail->getFile()."<br> 
                        Linea: ".$fail->getLine()."<br> 
                        Funcion: ".$fail->getTrace()[1]['function']."<br>
                        Class: ".$fail->getTrace()[1]['class']."<br>";
            }
            return false;

        }else{

            if ($json)
            {
                echo json_encode($result);
                return true;
            }else{

                return $result;
            }
        }

    }


}
