<?php

$id = (int)mysqli_escape_string(Database::conn(), @$_GET['id']);
$result = Query::run("SELECT data FROM projects WHERE id = '$id'");

if(!isset($id)) die('No se puede cargar el contenido sin una ID.');
if(!mysqli_num_rows($result)) 
	die('No se encuentra ningún proyecto por la ID solicitada.');

$data = unserialize(mysqli_fetch_assoc($result)['data'])[Lang::$lang->lang_name];

$page_title = $data['name'];

echo '
<u><h1>'.$data['name'].'</h1></u>
<div class="projectMain">';

if($data['type']) 
{
	echo '
	<table>
		<tr>
			<td><img src="'.$data['thumb'].'" class="projectThumb" /></td>
			<td style="vertical-align: top;">
				<b>Categoría:</b> '.Lang::$lang->text['projectType'][$data['type']].'<br>
				<b>Autor:</b> '.UserUtils::getStat($data['author'], 'username').'<br><br>
				<a href="'.$data['other_data']['link'].'">'.Lang::$lang->text['see'][$data['type']].'</a>
				<br><br>
				'.$data['desc'].'
			</td>
		</tr>
	</table>';
} 
else 
{
	//print_r($data);
	echo '
	<link rel="stylesheet" type="text/css" href="./css/unity.css" />
	<script>var game_url = "'.$data['other_data']['link'].'";</script>
	<script type="text/javascript" src="./js/unity-init.js"></script>
	<script type="text/javascript" src="./js/unity-core.js"></script>
	<div class="content">
		<div id="unityPlayer">
			<div class="missing">
				<a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
					<img alt="Unity Web Player. Install now!" src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63" />
				</a>
			</div>
			<div class="broken">
				<a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now! Restart your browser after install.">
					<img alt="Unity Web Player. Install now! Restart your browser after install." src="http://webplayer.unity3d.com/installation/getunityrestart.png" width="193" height="63" />
				</a>
			</div>
		</div>
	</div>
	<p class="footer">&laquo; created with <a href="http://unity3d.com/unity/" title="Go to unity3d.com">Unity</a> &raquo;</p>
	<div class="sep"></div>
	<h3>Controles:</h3>
	<table>
		<tr>';
		$controls = $data['other_data']['controls'];
		$keys = $controls['keys'];
		$actions = $controls['actions'];
		for($i = 0; $i < count($keys); ++$i)
			echo '<td><img src="./images/controls/'.$keys[$i].'.png" /></td><td>'.$actions[$i].'</td>';
	echo '</tr>
	</table>'; //cellspacing="10"
}

echo '
<div class="sep"></div>
	<center>
		<div id="bigThumb" style="display:none;"></div>
		<div id="thumbPlace">
			<div class="btnArrow" id="btnLeftArrow" style="display: none;" onclick="arrowClick(this)"></div>
		    <div class="btnArrow" id="btnRightArrow" style="display: none;" onclick="arrowClick(this)"></div>
		    <div id="thumbRect">
			    <div onwheel="wheely(event, this)">
			  		<!-- DIVS GOES HERE -->
			    </div>
		    </div>
		</div>
	</center>
	<script type="text/javascript" src="./js/thumb-core.js"></script>
	<script>
		var l = 0, 
			m = 0,
			imgs = '.json_encode($data['other_data']['images']).',
			vids = '.(isset($data['other_data']['videos']) ? json_encode($data['other_data']['videos']) : 'null').';
		for(; l < imgs.length; ++l)
			addThumb(null, document.getElementById("thumbRect").children[0], "image", imgs[l], false);
		if(vids)
			for(; m < vids.length; ++m) 
				addThumb(null, document.getElementById("thumbRect").children[0], "video", vids[m], false);
	</script>
	<div class="sep"></div>
	<b>Versión:</b> '.$data['avers'].'
	<span class="hsep"></span>'.(strpos($data['other_data']['odata_text'], ':') !== false ? '<b>' : '').str_replace(':', ':</b>', str_replace(', ', '<span class="hsep"></span><b>', $data['other_data']['odata_text'])).'<br>
	<b>Fecha de creación:</b> '.date('d-m-Y H:i', $data["creation_date"]).'<span class="hsep"></span>
	<b>Fecha de publicación:</b> '.date('d-m-Y H:i', $data["publish_date"]).'<br>
</div>
<div class="projectMain">
	<center>
		<h3 style="display:inline;">Notas de versión</h3> 
		<span size="5" onclick="var aa = document.getElementById(\'v_notes\'), ab = document.getElementById(\'v_notes_aux\');aa.style.display = (aa.style.display == \'none\' ? \'block\' : \'none\');ab.style.display = (aa.style.display == \'none\' ? \'block\' : \'none\');this.innerHTML = (aa.style.display == \'none\' ? \'(ocultar)\' : \'(mostrar)\');">(mostrar)</span>
	</center>
	<div class="sep" style="margin:5px 0;"></div>
	<div id="v_notes_aux">
		<center>
			<h3 style="margin:0px;">...</h3>
		</center>
	</div>
	<div id="v_notes" style="margin-top:10px;">';
		$vers = $data['vers'];
		for($i = 0; $i < count($vers); ++$i) 
			echo '<b>'.$vers[$i]['vers_name'].'</b><script>spacer(document.currentScript.previousElementSibling);document.currentScript.remove();</script>'.nl2br($vers[$i]['vers_note']).'<br><br>'; //str_replace(':<br>', ':<br><br>', str_replace(' - ', '<br> - ', $vers[$i]['vers_note']))
	echo '</div>
	<script>document.getElementById("v_notes").style.display = "none";document.currentScript.remove();</script>
</div>
<div class="projectMain"><center>Comentarios coming soon.</center></div>';