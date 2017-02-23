<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 02/07/2016
 * Time: 20:57
 */

$num_rows = Query::count('id', 'users'); //"WHERE rank_id = ".UserUtils::getRankIdByName('user') //Check better for privacy settings
if($num_rows > 0)
{
    $pages = new Paginator($num_rows, 9);
    $online_people = Query::run(ContentUtils::str_format("SELECT * FROM users WHERE {0}-last_activity < {1}", CoreUtils::Now(), SESSION_TIME*3600));
    
    echo '<h2>Miembros ('.mysqli_num_rows($online_people).')</h2>
    <div class="sep"></div>'; //Tengo q meter el nombre de ivan cea, jesus x2, gabriel

    while($row = mysqli_fetch_assoc($online_people))
        echo ContentUtils::getAvatarBubble($row);
    echo '<div class="sep"></div>
    <center>'.$pages->display_pages().'</center>';
} else
    echo '<center><h2>No hay usuarios conectados.</h2></center>';
$vb_online = Query::run(ContentUtils::str_format("SELECT * FROM visitors WHERE {0}-last_activity < {1}", CoreUtils::Now(), SESSION_TIME*3600));
$visitors_online = array();
$bots_online = array();
foreach($vb_online as $v)
    if(!VisitorUtils::checkVisitorRegister($v['ip']))
        if(CoreUtils::getUserAgentInfo($v['user_agent'])['agent_type'] != 'crawler')
            $visitors_online[] = $v;
        else
            $bots_online[] = $v;
echo '<div class="sep"></div>
<h2>Visitantes ('.count($visitors_online).')</h2>
<div class="sep"></div>
<h2>Bots ('.count($bots_online).')</h2>
<br>';