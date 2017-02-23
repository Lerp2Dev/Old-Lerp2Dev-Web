<?php
    class ProjectManager 
    {
        public static function manage($id, $json_data)
        {
            //global Database::conn(), ContentManager::$msg_list, ContentManager::$msg_type, Lang::$lang->lang_name;

            $data = json_decode($json_data, true);

            //$data = array_map('str_escape', $data);

            if(empty($data[Lang::$lang->lang_name]["thumb"]))
                ContentManager::addMsg('emptyProjectThumb');
            else if(!CoreUtils::isValidUrl($data[Lang::$lang->lang_name]["thumb"]))
                ContentManager::addMsg('wrongProjectThumbLink');

            if(empty($data[Lang::$lang->lang_name]["name"]))
                ContentManager::addMsg('emptyProjectName');
            else if(strlen($data[Lang::$lang->lang_name]["name"]) < 6)
                ContentManager::addMsg('shortProjectName');
            else if(strlen($data[Lang::$lang->lang_name]["name"]) > 30)
                ContentManager::addMsg('longProjectName');

            if(empty($data[Lang::$lang->lang_name]["desc"]))
                ContentManager::addMsg('emptyProjectDesc');

            if(empty($data[Lang::$lang->lang_name]["avers"]))
                ContentManager::addMsg('emptyProjectAVers');

            if(!count($data[Lang::$lang->lang_name]["vers"]))
                ContentManager::addMsg('emptyProjectVersNotes');

            if(!isset($data[Lang::$lang->lang_name]["creation_date"]))
                ContentManager::addMsg('emptyProjectCDate');
            else if(time()-(int)$data[Lang::$lang->lang_name]["creation_date"] < 0)
                ContentManager::addMsg('futureProjectCDate');

            if(!count($data[Lang::$lang->lang_name]["other_data"]) || empty($data[Lang::$lang->lang_name]["other_data"]['odata_text'])) {
                ContentManager::addMsg('emptyODataText');
            } else {//check if other indexes are empty only when type is met
                if((int)$data[Lang::$lang->lang_name]["type"] == 0) 
                    if(empty($data[Lang::$lang->lang_name]["other_data"]['controls']) || empty($data[Lang::$lang->lang_name]["other_data"]['controls']['keys']) || empty($data[Lang::$lang->lang_name]["other_data"]['controls']['actions']))
                        ContentManager::addMsg('emptyControls');
                else
                    if(empty($data[Lang::$lang->lang_name]["other_data"]['link'])) 
                        ContentManager::addMsg('emptyProjectLink');
                    else if(!CoreUtils::isValidUrl($data[Lang::$lang->lang_name]["other_data"]['link']))
                        ContentManager::addMsg('wrongProjectLink');
                if(empty($data[Lang::$lang->lang_name]["other_data"]['images']))
                    ContentManager::addMsg('emptyImages');
                /*if(empty($other_data['videos'])) {
                    ContentManager::addMsg('emptyVideos');
                }*/
            }

            if(!count(ContentManager::$msg_list))
            { //Esta array va a venir ya preparada cuando haga lo del js
                //if(!isset($id)) $data["author"] = getStat(); //Esto es incorrectisimo jajaja
                //$data = fix_serialized(mysqli_escape_string(Database::conn(), serialize($data))); //serialize(array('es' => array('type' => $type, 'thumb' => $thumb, 'name' => $name, 'desc' => $desc, 'avers' => $avers, 'vers' => $vers, 'author' => $author, 'creation_date' => $creation_date, 'publish_date' => $publish_date, 'other_data' => $other_data)));
                $data = serialize($data);
                if(!isset($id))
                {
                    //Query::run("INSERT INTO projects (`type`, `thumb`, `name`, `desc`, `avers`, `author_id`, `creation_date`, `publish_date`, `other_data`) VALUES ('$type', '$thumb', '$name', '$desc', '$avers', '$author', '$creation_date', '$publish_date', '$other_data')");
                    Query::run("INSERT INTO projects (`data`) VALUES ('$data')");
                    ContentManager::addMsg('newProject');
                }
                else
                {
                    //http://www.w3schools.com/php/php_mysql_update.asp
                    //Query::run("UPDATE projects SET `type` = '$type', `thumb` = '$thumb', `name` = '$name', `desc` = '$desc', `avers` = '$avers', `author_id` = '$author', `creation_date` = '$creation_date', `other_data` = '$other_data' WHERE id = '$id'");
                    $id = (int)mysqli_escape_string(Database::conn(), $id);
                    Query::run("UPDATE projects SET `data` = '$data' WHERE id = '$id'");
                    ContentManager::addMsg('editProject');
                }
                ContentManager::$msg_type = 1;
            }
        }
    }

    class AdManager
    {
        //Cuando se elimine un anuncio se tendrá que reoganizar el pos
        public static function manage($id, $json_data)
        {
            //global Database::conn(), ContentManager::$msg_list, ContentManager::$msg_type, Lang::$lang->lang_name;

            $data = json_decode($json_data, true);

            foreach($data as $k => $v)
                $data[$k] = str_replace("<br>", "", $v);

            if(!count(ContentManager::$msg_list))
            {
                //$data = fix_serialized(mysqli_escape_string(Database::conn(), serialize($data)));
                $data = serialize($data);
                if(!isset($id))
                {
                    $pos = (int)mysqli_fetch_assoc(Query::run("SELECT COUNT(id) as total FROM announcements"))['total']+1;
                    Query::run("INSERT INTO announcements (`data`, `pos`) VALUES ('$data', '$pos')");
                    ContentManager::addMsg('newAd');
                }
                else
                {
                    $id = (int)mysqli_escape_string(Database::conn(), $id);
                    Query::run("UPDATE announcements SET `data` = '$data' WHERE id = '$id'");
                    ContentManager::addMsg('editAd');
                }
                ContentManager::$msg_type = 1;
            }
        }
    }