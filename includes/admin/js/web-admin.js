//For browser compatibility

var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

//Ajax Settings

furl = "./includes/admin/main.ajax.php";

//Misc

function getQueryVariable(variable)
{
	var query = window.location.search.substring(1), 
		vars = query.split("&"),
		i = 0;
  	for (; i < vars.length; ++i) 
  	{
    	var pair = vars[i].split("=");
    	if (pair[0] == variable)
      		return pair[1];
  	} 
  	return '';
}

function subGo(e, ev) 
{
	var url = "index.php?action=admin&go="+e.getAttribute("data-go"), win, evtObj = window.event ? event : ev;
	if(evtObj.ctrlKey) {
		win = window.open(url, '_blank');
		win.focus();
	} else
		window.location = url;
}

/*function aSendForm(elem) 
{
	sendForm(elem, './includes/admin/main.main.ajax.php');
}*/

function insertParam(param)
{
	_url = location.href;
	var p = param.substring(0, param.indexOf("=")), oldp;
	if(_url.indexOf("?"+p) > -1 || _url.indexOf("&"+p) > -1) 
	{
		oldp = _url.substring(_url.indexOf(p));
	  	if(oldp.indexOf("&") > -1)
	   		oldp = oldp.substring(oldp.indexOf("&")-1);
		   	_url = _url.replace(oldp, param);
	} 
	else 
	{
	  	_url += (_url.split('?')[1] ? '&':'?') + param;
	}
	return _url;
}

function goParam(param, vr) 
{
	window.location = insertParam(param+"="+vr);
}

function updateContent(v, c) 
{
	$.ajax({
        type: "POST",
        url: './includes/admin/main.ajax.php',
        data: "new_content_type="+v,
        cache: false,
        success: function(html) {
        	var result = null;
        	if(html.indexOf("<script>") > -1)
	        	result = html.match(/<script>(.*?)<\/script>/g).map(function(val){
				   return val.replace(/<\/?script>/g,'');
				});
			var doc = $.parseHTML(html);
            if(doc && doc[1] && doc[3])
            {
                document.getElementById("c1").innerHTML = doc[1].innerHTML;
                document.getElementById("c2").innerHTML = doc[3].innerHTML;
                if(result) eval(result[0]);
                if(c) c();
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
    return false;
}

//Minigames

var controls = [{value: "flechas", text: "Flechas de movimiento"},
				{value: "letras", text: "Letras (WASD)"},
				{value: "enter", text: "Enter"},
                {value: "control", text: "Ctrl"},
                {value: "alt", text: "Alt"},
                {value: "espacio", text: "Espacio"},
                {value: "izq", text: "Click izquierdo"},
                {value: "der", text: "Click derecho"},
                {value: "mover", text: "Mover el ratón"},
                {value: "shift", text: "Shift"},
                {value: "escape", text: "Escape"},
                {value: "tab", text: "Tabulación"},
                {value: "customkey", text: "Especificar tecla"}],
	sep = "<div class='sep' id='firstsep' style='width: 200px;'></div>";//,

function addControl(e) 
{
	if(e.lastChild && e.lastChild.className != "sep") e.insertAdjacentHTML('beforeend', sep);
	var r = e.appendChild(getControlSelect());
	e.insertAdjacentHTML('beforeend', sep.replace(" id='firstsep'", ""));
	return r;
}

function onSelectChange(e) 
{
	//Modify the array
	var x = e.parentNode.parentNode.parentNode.querySelectorAll('select#htmlkey option[value="'+e.options[e.selectedIndex].value+'"]'), 
		y = e.parentNode.parentNode.parentNode.querySelectorAll('select#htmlkey option'),
		z = e.parentNode.parentNode.parentNode.querySelectorAll('select#htmlkey'), 
		i = 0, si = 0, j = 0;

	//Enable all the options
	for(; i < y.length; ++i)
        if(typeof y[i].getAttribute('no-change') === 'undefined')
		    y[i].disabled = false;

	//Re-disable options
	for(i = 0; i < z.length; ++i) //We iterate all the selects
		if(e != z[i])
        { //We don't want our select
			s = z[i].selectedIndex; //Get the selected index from this select
			for(j = 1; j < z.length; ++j) //Now we will disable this option in all the other select
				if(z[j].selectedIndex != si) //We won't disable the same options that we are selecting
					z[j].options[si].disabled = true; //And woala!
		}

	//Disable my input
	for(i = 0; i < x.length; ++i) //We will disable our option in the rest of the selects
		if(x[i].parentNode != e && x[i].value != controls[controls.length - 1].value) //typeof x[i].getAttribute('no-change') === 'undefined'
			x[i].disabled = true;

	//Change associated
	if(e.selectedIndex != controls.length-1) 
	{
		if(e.nextSibling.tagName == "INPUT")
		{
			e.nextSibling.remove();
			e.style.width = "162px";
		}
		e.parentNode.parentNode.lastChild.value = e.options[e.selectedIndex].value;
	}
	else 
	{
		e.style.width = "124px";
		var t = document.createElement('input');
		t.type = "text";
		t.style.width = "30px";
		t.style.marginLeft = "4px";
		t.maxLength = 1;
		t.onkeypress = function(ev) {e.parentNode.parentNode.lastChild.value = String.fromCharCode(ev.keyCode);};
		appendSibling(e, t);
	}

}

function deleteFirstSep() 
{
	if(!document.getElementById("firstsep").nextSibling) document.getElementById("firstsep").remove();
}

function getControlSelect() 
{
	var select = document.createElement('select'),
	    option,
	    i = 0,
	    il = controls.length,
	    html = document.createElement('div'),
	    text = document.createElement('input'),
	    delbtn = document.createElement('input'),
	    ascinpt = document.createElement('input'),
	    html1 = document.createElement('div');

	html1.style.display = "inline-block";

	text.type = "text";
	text.name = "accion[]";
	text.style.width = "158px";
    text.style.height = "18px";

	delbtn.type = "button";
	delbtn.setAttribute('onclick', 'var e = this.parentNode;e.nextSibling.remove();e.remove();deleteFirstSep();');
	delbtn.value = "-";
	delbtn.style.padding = "5px 10px";
	delbtn.style.marginLeft = "-30px";
	delbtn.style.position = "relative";
    if(!isFirefox)
	    delbtn.style.top = "-1px";
    else
        delbtn.style.top = "1px";
    delbtn.style.width = "31px";
    delbtn.style.height = "34px";

	ascinpt.name = "tecla[]";
	ascinpt.type = "hidden";

	select.id = "htmlkey";
	select.setAttribute("onchange", "onSelectChange(this)");

    option = document.createElement('option');
    option.setAttribute('selected', 'true');
    option.disabled = true;
    option.setAttribute('no-change', 'true');
    option.appendChild(document.createTextNode('Selecciona una tecla'));
    select.appendChild(option);

	for (; i < il; ++i)
	{
	    option = document.createElement('option');
	    option.setAttribute('value', controls[i].value);
	    option.appendChild(document.createTextNode(controls[i].text));
	    select.appendChild(option);
	}
	html1.innerHTML += "<b>Tecla</b><br>";
	html1.appendChild(select);
	html1.innerHTML += "<br><b>Acción</b><br>";
	html1.appendChild(text);
	html.appendChild(html1);
	html.appendChild(delbtn);
	html.appendChild(ascinpt);

	return html;
}

//File uploader

function fUpload(t) 
{
	//El parametro 'multiple' no es necesario

	var node = document.createElement("table"),
		tr = document.createElement("tr"),
		td1 = document.createElement("td"),
		td2 = document.createElement("td"),
		avt = document.createElement("div"),
		form = document.createElement("form"),
		inpt = document.createElement("input"),
		inpt1 = document.createElement("input"),
		inpt2 = document.createElement("input"),
		exts = {asset: ["unity3d"], thumb: ["jpg", "jpeg", "png", "gif"], avatar: ["jpg", "jpeg", "png"]},
		myexts = exts[t],
		i = 0,
		mes = ".";

	for(; i < myexts.length; ++i)
		mes += myexts[i] + (i + 1 < myexts.length ? ", ." : "");
    
    node.style.marginBottom = "5px";
	node.style.marginLeft = "-2px";

	form.style.marginTop = "5px";

	avt.id = 'avatar_thumb';
	avt.title = 'Haz click para ampliar el avatar.';

    if(t == "thumb")
	    td1.style.textAlign = 'center';

	td2.id = 'td2';
	td2.style.display = "none";

	inpt.type = "text";
	if(t != 'avatar')
		inpt.name = "link";
	else
		inpt.name = "avatar_link";
	inpt.style.width = "215px";

	//this.parentNode.parentNode.parentNode.className
	inpt.setAttribute("onkeyup", "this.nextSibling.nextSibling.style.display = this.value.length == 0 ? 'block' : 'none';if(event.keyCode == 13 && '"+t+"' == 'thumb') addThumb(this.parentNode.parentNode.parentNode.parentNode.nextElementSibling, document.getElementById(\"thumbRect\").children[0], \"image\", this.value);");

	inpt1.type = "button";
	inpt1.value = "Obtener enlace";
	inpt1.style.marginLeft = "5px";
	inpt1.style.display = "none";
	inpt1.setAttribute("onclick", "sendTempFile(this.nextSibling.firstChild, '"+t+"');");

	inpt2.name = "ufile";
	inpt2.type = "file";
	inpt2.id = t;
    inpt2.style.width = "230px";
	inpt2.accept = mes;
	inpt2.setAttribute("onchange", "this.parentNode.parentNode.firstChild.disabled = this.value.length > 0;var n = document.createElement('input'); n.value = '-'; n.type = 'button'; n.style.padding = '5px 10px'; n.style.position = 'relative'; n.style.marginTop = '-1px'; n.setAttribute('onclick', 'this.previousSibling.value = \"\"; this.previousSibling.style.width = \\'301px\\'; this.parentNode.parentNode.firstChild.disabled = false; this.remove();'); if(this.nextSibling && this.nextSibling.tagName == 'INPUT') this.nextSibling.remove(); if(this.value.length > 0) appendSibling(this, n); this.style.width = (this.value.length == 0 ? '301' : (20 + (this.value.length + 1) * 8)) + 'px'; this.parentNode.previousSibling.style.display = 'inline-block';");

	form.appendChild(inpt2);
	if(t == 'avatar')
		td2.appendChild(avt);
	td1.appendChild(inpt);
	td1.appendChild(inpt1);
	td1.appendChild(form);
	if(t == 'avatar')
		tr.appendChild(td2);
	tr.appendChild(td1);
	node.appendChild(tr);

	return node;
}

function sendTempFile(e, t) 
{
	var data = new FormData(),
		el = e.parentNode.previousSibling.previousSibling;

	if(!e.value || e.value.length == 0)
		return;

	el.value = "";
	el.style.border = "1px solid #a9a9a9";
	el.style.height = "17px";

    $.each(e.files, function(key, value)
    {
        data.append(key, value);
    });

    $.ajax({
        url: './includes/admin/main.ajax.php?files='+e.id,
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            if(typeof data.error === 'undefined')
            {
                el.value = data[0].link;
                if(t == 'avatar')
                	document.getElementById('avatar_thumb').value = data[0].link;
                el.style.background = "#EBEBE4";
                e.parentNode.previousSibling.remove();
                e.parentNode.remove();
            }
            else 
            {
            	el.value = 'ERROR';
            	el.style.background = "#EBEBE4";
            	displayError(data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        },
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload)
            	myXhr.upload.addEventListener('progress', function(evt) {updateProgress(evt, el);}, false);
            return myXhr;
        }
    });

}


function updateProgress(evt, e, t) 
{
    if (evt.lengthComputable) {
        var percentComplete = ((evt.loaded / evt.total)*100) + "%";
        e.style.background = "linear-gradient(90deg, white "+percentComplete+", #EBEBE4 "+percentComplete+")";
        if(percentComplete == "100%")
        	e.value = " "+lang.dropboxUploading; //" Subiendo a Dropbox..."; //" "+translateKey('dropboxUploading');
    } else
        console.log('unable to complete');
}

//#############
//## Content ##
//#############

function displayError(m) 
{
	//Tengo que conseguir obtener el idioma desde js tambien
	var e = document.getElementById("msg"), i = 0;
	e.className = "msg error";
	e.innerHTML = (m.constructor === Array && m.length > 1  ? "Los siguientes "+m.length+" errores ocurrieron " : "El suguiente error ocurrió ")+"al intentar enviar el forumlario:";
	e.appendChild(document.createElement('br'));
	for(; i < m.length; ++i) {
		e.innerHTML += (m.length > 1 ? "- " : "")+m[i];
		if(m.length > 1)
			e.appendChild(document.createElement('br'));
	}
}

//Projects related

function uploadPopup() 
{
	var div1 = document.createElement('div'),
		div2 = document.createElement('div'),
		btnPhoto = document.createElement('input'),
		btnPAccept = document.createElement('input'),
		btnVideo = document.createElement('input'),
		txtVideo = document.createElement('input'),
		btnVAccept = document.createElement('input'),
		error1 = document.createElement('div'),
		error2 = error1.cloneNode(false),
		h21 = document.createElement('h2'),
		h22 = document.createElement('h2'),
		h23 = document.createElement('h2'),
		center1 = document.createElement('center'),
		center2 = center1.cloneNode(false),
		center3 = center1.cloneNode(false),
		pPanel = document.createElement('div'),
		vPanel = document.createElement('div'),
		back1 = document.createElement('input'),
		btnClose1 = document.createElement('img');

	div1.id = 'filePopup';
	div1.style.display = 'none';
	div1.setAttribute('onclick', 'containerHide(this)');

	div2.className = 'fpSubdiv';
	div2.style.display = 'block';

	btnPhoto.type = 'button';
	btnPhoto.className = 'btn Photo';
	btnPhoto.value = 'Insertar imagen';
	btnPhoto.setAttribute('onclick', 'this.parentNode.parentNode.nextSibling.style.display = "block";');
	
	btnPAccept.type = "button";
	btnPAccept.value = "Añadir imagen";
	btnPAccept.setAttribute('onclick', 'addThumb(this, document.getElementById(\"thumbRect\").children[0], \"image\", this.previousElementSibling.firstElementChild.firstElementChild.firstElementChild.firstElementChild.value)');

	btnVideo.type = 'button';
	btnVideo.className = 'btn Video';
	btnVideo.value = 'Insertar vídeo';
	btnVideo.setAttribute('onclick', 'this.parentNode.parentNode.nextSibling.nextSibling.style.display = "block";');

	txtVideo.type = 'text';
	txtVideo.setAttribute('onkeypress', "if(event.keyCode == 13 && this.parentNode.style.marginBottom != '5px') {addThumb(this.nextSibling.nextSibling, document.getElementById(\"thumbRect\").children[0], \"video\", youtube_parser(this.value)); return false;}");

	btnVAccept.type = "button";
	btnVAccept.value = "Añadir vídeo";
	btnVAccept.style.marginTop = "5px";
	btnVAccept.setAttribute('onclick', 'addThumb(this, document.getElementById(\"thumbRect\").children[0], \"video\", youtube_parser(this.previousSibling.previousSibling.value));');
	
	btnClose1.className = 'btnClose';
	btnClose1.src = './images/icons/close.png';
	btnClose1.setAttribute('onclick', 'this.parentNode.parentNode.style.display = "none";');

	var btnClose2 = btnClose1.cloneNode(false),
        btnClose3 = btnClose1.cloneNode(false);

    btnClose2.setAttribute('onclick', 'closePopup(this.previousElementSibling.lastChild, \"image\");');
    btnClose3.setAttribute('onclick', 'closePopup(this.previousElementSibling.lastChild, \"video\");');
	
	h21.innerHTML = 'Elige una opción:';

	back1.className = 'btnBack';
	back1.value = 'Atrás';
	back1.type = 'button';
	back1.setAttribute('onclick', 'this.parentNode.style.display = "none";');

	var back2 = back1.cloneNode(false);

	pPanel.className = 'fpSubdiv';
	pPanel.style.display = 'none';

	var vPanel = pPanel.cloneNode(false);

	center1.appendChild(h21);
	center1.appendChild(btnPhoto);
	center1.appendChild(btnVideo);
	div2.appendChild(center1);
	div2.appendChild(btnClose1);
	div1.appendChild(div2);

	h22.innerHTML = 'Añadir una foto:';

	center2.appendChild(error1);
	center2.appendChild(h22);
	center2.appendChild(fUpload("thumb"));
	center2.appendChild(btnPAccept);
	pPanel.appendChild(center2);
	pPanel.appendChild(btnClose2);
	pPanel.appendChild(back1);

	h23.innerHTML = 'Añadir enlace de YouTube:';

	center3.appendChild(error2);
	center3.appendChild(h23);
	center3.appendChild(txtVideo);
	br(center3);
	center3.appendChild(btnVAccept);
	vPanel.appendChild(center3);
	vPanel.appendChild(btnClose3);
	vPanel.appendChild(back2);

	div1.appendChild(pPanel);
	div1.appendChild(vPanel);
	
	document.body.insertAdjacentHTML('afterbegin', div1.outerHTML);
}

//http://www.mdzol.com/files/image/635/635026/562598a165d48.png
//http://youtu.be/0zM3nApSvMg

var widthBox = 68,
    l = null,
    r = null,
    s = 0,
	edit = document.createElement('img'),
    trash = document.createElement('img'),
    pushpin = document.createElement('img'),
    projectPreset = {},
    lastLang = 'es',
    dinfo = null;

//action=admin&go=project-manager&do=edit&id=2
(function() {
	if(getQueryVariable('action') == 'admin' && getQueryVariable('go') == 'project-manager' && getQueryVariable('do') == 'edit') 
	{
		$.ajax({
	        url: './includes/admin/main.ajax.php',
	        data: 'action=getpreset&id='+getQueryVariable('id'),
	        type: 'POST',
	        cache: false,
	        dataType: 'json',
			success: function(json)
	        {
	        	projectPreset = json;
			},
	        error: function(jqXHR, textStatus, errorThrown)
	        {
	            console.log(jqXHR.responseText);
	        }
		});
	}
})();

edit.src = 'images/icons/edit.png';
trash.src = 'images/icons/trash.png';
pushpin.src = 'images/icons/pushpin.png';

function is_set(url) 
{
	var c = document.getElementById('c3').children,
        i = 0;
	for(; i < c.length; ++i)
		if(c[i].value == url && c[i].hasAttribute('data-thumb') && c[i].getAttribute('data-thumb') == 'true')
		    return true;
    return false;
}

function setthumb(e, index) 
{
	var c = document.getElementById('c3').children,
        i = 0;
    for(; i < c.length; ++i) //Reset
        if(c[i].dataset.thumb)
            c[i].dataset.thumb = false;
	c[index].dataset.thumb = true;
	e.style.display = 'none';
	document.getElementById('post_thumb').value = c[index].value;
}

function deletekey(index) 
{
	if(index < 0) return; //Rare bugs
	if(confirm("¿Deseas borrar esta imagen?")) 
	{
		var tr = document.getElementById("thumbRect"),
			bt = document.getElementById("bigThumb");
		document.getElementById('c3').children[index].remove();
		tr.firstElementChild.children[index].remove();
		console.log(tr.firstElementChild.children.length);
		if(tr.firstElementChild.children.length > 0) {
			s = tr.firstElementChild.children.length-1;
			set(tr.firstElementChild);
		} else {
			bt.style = null;
			bt.innerHTML = 'No hay ninguna imagen.';
		}
	}
	return false;
}

var st = "";

function edit_link(s, t)
{
    var e = document.getElementById('filePopup'),
        v = document.getElementById('c3').children[s].value,
        h = null,
		b = null;
    e.style.display = 'block';
    e = e.children[1];
    if(t == 'video')
        e = e.nextElementSibling;
    h = e.children[0].children[1];
    b = e.children[0].lastElementChild;
    if(t == 'image')
    {
        h.innerHTML = "Editar una foto:";
		b.value = "Editar imagen";
    }
    else
    {
        h.innerHTML = "Editar enlace de YouTube:";
        b.value = "Editar vídeo";
    }
    e.style.display = 'block';
    e.lastElementChild.style.display = 'none';
    e.children[0].children[2].children[0].children[0].children[0].children[0].value = v;
    e.children[0].children[2].children[0].children[0].children[0].children[2].style.display = "none";
    st = b.getAttribute('onclick'); //Escapar esto, a ver si finalmente funciona
    b.removeAttribute('onclick');
    b.setAttribute('onclick', "return_button('"+t+"', '"+v+"')");
    //Cambiarle la función al botón de añadir imagen / video
	//El thumb en cuestión no se edita, y a veces se ve el file upl puede ser?
	//A parte, al hacer enter se añade foto no se edita
}

function edit_thumb_value(t, o)
{
    var c = document.getElementById('c3').children,
        d = document.getElementById('thumbRect').firstElementChild.children,
        i = 0,
        j = 0,
        e = document.getElementById('filePopup'),
        n = null;
    e = e.children[1];
    if(t == 'video')
        e = e.nextElementSibling;
    n = e.children[0].children[2].children[0].children[0].children[0].children[0].value;
    for(; i < c.length; ++i)
        if(c[i].value == o)
            c[i].value = n;
    for(; j < d.length; ++j)
        if(d[j].src == o)
            d[j].src = n;
    document.getElementById('bigThumb').style.backgroundImage = 'url("' + n + '")';
}

function return_button(t, oldv)
{
    var e = document.getElementById('filePopup'),
        h = null,
        b = null; // = null no hace falta, manías mías xD
    e.style.display = 'none';
	e = e.children[1];
    if(t == 'video')
        e = e.nextElementSibling;
    h = e.children[0].children[1];
    b = e.children[0].lastElementChild;
    if(t == 'image')
    {
        h.innerHTML = 'Añadir una foto:';
        b.value = 'Añadir imagen';
    }
    else
    {
        h.innerHTML = 'Añadir un enlace de YouTube:';
        b.value = 'Añadir vídeo';
    }
    e.style.display = 'none';
    e.lastElementChild.style.display = 'block';
    e.children[0].children[2].children[0].children[0].children[0].children[0].value = "";
    e.children[0].children[2].children[0].children[0].children[0].children[2].style.display = 'block';
    b.setAttribute('onclick', st);
    edit_thumb_value(t, oldv);
}

function youtube_parser(url)
{
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/,
    	match = url.match(regExp);
    return (match && match[7].length == 11) ? match[7] : false;
}

function thumb_exists(e, url) 
{
	//console.log(url);
	//console.log(stacktrace());
	var r = false, 
		c = document.getElementById('c3').children,
		i = 0;
	if(url.indexOf('youtu') > -1) url = youtube_parser(url);
	for(; i < c.length; ++i)
		if(c[i].value == url) 
		{
			if(e) e.innerHTML = 'ERROR: Esta miniatura ya existe.';
			r = true;
			break;
		}
	return r;
}

function br(node) 
{
	node.appendChild(document.createElement("br"));
}

function containerHide(e) 
{
	//var activeChild = JSLINQ(ev.target.getElementsByClassName("fpSubdiv")).SingleOrDefault(function(x) {return x.style.display == 'block';});
	var activeChild = e.firstChild;
	if(!contains(activeChild.getBoundingClientRect(), new Point(window.event.clientX, window.event.clientY)))
		e.style.display = "none";
}

function contains(r, p) 
{
	return p.x > r.left && p.x < r.right && p.y > r.top && p.y < r.bottom;
}

var Point = function(xCoord, yCoord) 
{
	this.x = xCoord;
	this.y = yCoord;
};

var manager_keywords = [{load: 'loadprojectmanager', add: 'admin_sendpost', delMsg: '¿Deseas eliminar el proyecto?', del: 'deleteproject'}, 
						{load: 'loadadmanager', add: 'admin_sendad', delMsg: '¿Deseas eliminar la noticia?', del: 'deletead'},
						{delMsg: '¿Estás seguro de que deseas borrar a este usuario?', del: 'deleteuser'}], 
	aut = "";

function deleteManager(id, index) 
{
	var e = document.getElementById('delete_msg'),
		center = document.createElement('center'),
		br = document.createElement('br'),
		im1 = document.createElement('img'),
		im2 = document.createElement('img');

	e.innerHTML = '';
	e.className = 'del-msg';
	e.style.display = 'block';

	im1.src = './images/icons/tick.png';
	im1.title = 'Sí';
	im1.onclick = function() 
	{
		$.ajax({
	        type: "POST",
	        url: './includes/admin/main.ajax.php',
	        data: 'action='+manager_keywords[index]['del']+'&id='+id+"&showlist",
	        cache: false,
	        success: function(html) {
	        	showMsg(html);
	        },
	        error: function(jqXHR, textStatus, errorThrown)
	        {
	            console.log(jqXHR.responseText);
	        }
    	});
		return false;
	};

	im2.src = './images/icons/cross.png';
	im2.title = 'No';
	im2.onclick = function() 
	{
		var el = document.getElementById('delete_msg'); 
		el.style.display = 'none'; 
		el.className = ''; 
		el.innerHTML = '';
	};

	center.innerHTML += manager_keywords[index]['delMsg'];
	center.appendChild(br);
	center.appendChild(im1);
	center.appendChild(im2);
	e.appendChild(center);

}

/*
function deleteConfirmManager(cap, f) 
{
	var e = document.getElementById('delete_msg');

	e.className = 'del-msg';
	e.style.display = 'block';
	e.innerHTML = '<center>'+cap+'<br><img src="./images/icons/tick.png" title="Sí" onclick="'+f+'" style="margin-right: 24px;" /><img src="./images/icons/cross.png" title="No" onclick="var e = document.getElementById(\'delete_msg\'); e.style.display = \'none\'; e.className = \'\'; e.innerHTML = \'\';" /></center>';

}
*/

function loadManagerData(id, lang, index) 
{
	if(id)
		$.ajax({
		    type: "POST",
		    url: './includes/admin/main.ajax.php',
		    data: 'action='+manager_keywords[index]['load']+'&id='+id,
		    dataType: 'json',
		    cache: false,
		    success: function(data) {
		    	console.log(data);
				console.log(JSON.stringify(data));
				projectPreset = data;
			    data = data[lang];
				loadManagerHtml(data, index);
			},
			error: function(jqXHR, textStatus, errorThrown) {
			    console.log(jqXHR.responseText);
			}
		});
	else
		loadManagerHtml(projectPreset[lang], index);
}

function loadManagerHtml(data, index) 
{
	switch(index) 
	{
		case 0: //Tendre q estar al cuaidado con el selector por el tema del input hidden y la id cuando diga de meter nuevos hiddens
			var fi = document.getElementById('admin_sendpost').querySelectorAll("input[type='text'], input[name='post_link'], textarea"),
                i = 0,
                j = 0,
                k = 0,
                l = 0,
                m = 0,
                n = 1,
                ak = document.getElementById('admin_sendpost').children[0];
			console.log(fi);
			dinfo = data;
			for(; i < fi.length; ++i) { //fi[i].name != "post_id"
			    if(fi[i].name != "post_cdate" && fi[i].name != "post_odata" && fi[i].name != "post_vers_name[]" && fi[i].name != "post_vers_note[]")
			     	fi[i].value = data[fi[i].name.replace("post_", "")];
			    else if(fi[i].name == "post_cdate" && data.creation_date && data.creation_date != "")
			      	fi[i].value = date('d/m/Y', parseInt(data.creation_date));
			    else if(fi[i].name == "post_odata")
			      	fi[i].value = data.other_data.odata_text;
			}
			var av = data.vers, 
			  	av2 = document.getElementById('addvnote').parentNode.querySelector('label');
			av2.children[1].value = av[0].vers_name;
			av2.children[3].value = av[0].vers_note;
			for(; n < av.length; ++n) 
			{
			   	var ave = addNoteVers(av2);
			   	ave.children[1].value = av[n].vers_name;
			   	ave.children[4].value = av[n].vers_note; 
			}
			document.getElementById('admin_sendpost').querySelector('select').value = data.type;
			updateContent(data.type, function() {
				var acts = null, 
					keys = null, 
					imgs = data.other_data.images, 
					vids = data.other_data.videos,
					bimg = document.getElementById('filePopup').children[1].children[0].children[3],
					bvid = document.getElementById('filePopup').children[2].children[0].children[4];
				if(data.type == 0) 
				{
					acts = data.other_data.controls.actions;
					keys = data.other_data.controls.keys;
				  	for(; j < acts.length; ++j) 
				  	{
				  		var e = addControl(ak),
                            sel = e.children[0].querySelector('select'),
                            actt = e.children[0].lastChild;
						for (; k < sel.options.length; ++k)
							if (sel.options[k].value == keys[j])
                            {
								//console.log(k);
						    	sel.value = k;
						    	sel.selectedIndex = k;
						    	sel.options[k].selected = true;
						    	//onSelectChange(sel); //Esto tengo q hacer q funcione
						    	break;
						  	}
						actt.value = acts[j];
					}
				}
				document.getElementById('admin_sendpost').querySelector("input[name='"+(data.type == 0 ? 'link' : 'post_link')+"']").value = data.other_data.link;
				for(; l < imgs.length; ++l) //Esto todavia no funca
					addThumb(bimg, document.getElementById("thumbRect").children[0], "image", imgs[l]);
				if(vids)
					for(; m < vids.length; ++m) 
						addThumb(bvid, document.getElementById("thumbRect").children[0], "video", vids[m]);
				getThumb(data.thumb).onclick();
				if(document.getElementById('bigThumb').children[1])
					document.getElementById('bigThumb').children[1].onclick();
				if(document.getElementById('thumbRect').children[0])
					document.getElementById('thumbRect').children[0].lastChild.onclick();
				//if(c) c();
			});
			break;
		case 1:
			setBBCEditor(data);
			break;
	}
}

function getThumb(url) 
{
	var aimg = document.getElementsByTagName("img"), i = 0;
	for(; i < aimg.length; ++i) 
		if(aimg[i].src == url && aimg[i].parentNode.parentNode.id == "thumbRect")
			return aimg[i];
}

function addNoteVers(e) 
{
	var cln = e.cloneNode(true);
	cln.children[1].style.marginRight = "5px";
	cln.children[1].insertAdjacentHTML('afterend', '<input type="button" value="-" onclick="this.parentNode.previousSibling.remove();this.parentNode.remove();" style="padding: 5px 10px;position: relative;top: -1px;" />');
	cln.children[1].value = "";
	cln.children[4].value = "";
	e.parentNode.appendChild(document.createElement('br'));
	return e.parentNode.appendChild(cln);
}

function changeLanguage(e, i) 
{
	console.log("ChangeLanguage")
	var index, newLang;

  	index = e.selectedIndex;
  	if (index >= 0 && e.options.length > index)
    	newLang = e.options[index].value;

    projectPreset[lastLang] = getManagerJSON(i);
    resetManager(i);

    if(projectPreset[newLang])
		loadManagerData(null, newLang, i);

    console.log(projectPreset);
	console.log("ChangeLanguage")

	lastLang = newLang;
}

function getAuthor() 
{
	$.ajax({
        url: './includes/admin/main.ajax.php',
        data: 'action=get_author',
        type: 'POST',
        cache: false,
		success: function(html)
        {
        	aut = html;
		},
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
	});
}

//https://gyazo.com/0632f9135e5fd2dd48607b9f9607ec79
//Tengo que comprobar si el value es nulo
//Al cambiar de idioma se pueden guardar varios tipos
//Al obtener la array pierdo varias cosas, como por ejemplo, el publish_date y el type, a ver si la pudiese obtener desde donde obtengo la informacion (linea 744)
//Aunque va a ser dificil cuando este creando el post, mejor sera obtenerlo todo desde otros lados, por ejemplo, el publish_date desde new Date() y el type desde el select
//Ademas tengo q distinguir entre los distintos tipos, ya que hay ciertas cosas que no son comunes entre el mismo JSON Object
//Otra cosa a tener en cuenta es cuando se cambie de idioma se tiene q tener en cuenta si el select esta seleccionado, pq si no hay q lanzar un erro pidiendo q se especifique un type
//Pa el author si que tengo un problema, si es add tengo q hacer un ajax query y si no lo tengo q obtener de la linea 744

function getManagerJSON(index) 
{
	switch(index) 
	{
		case 0:
			var fi = document.getElementById('admin_sendpost').querySelectorAll('input[type="text"], input[type="hidden"], textarea'), 
				i = 0,
                arr = [],
				obj = {}, 
				v = 0,
				st = document.getElementsByName('post_type')[0],
				sin = st.selectedIndex,
				so = sin != null && sin != 0 && st.options[sin] != null ? st.options[sin].value : null,
				pdate = Math.round(new Date().getTime() / 1000),
				vers = [];
			if(getQueryVariable("do") == "edit" && dinfo) 
				aut = dinfo.author;
			//else if(getQueryVariable("do") == "add")
			//	aut = getAuthor();
			for(; i < fi.length; ++i)
				if(fi[i].name.indexOf('[]') == -1)
                    arr[fi[i].name] = fi[i].value;
				else 
				{
					v = fi[i].name.replace("[]", "");
					if(!(typeof arr[v] != 'undefined' && arr[v] instanceof Array)) arr[v] = [];
					arr[v].push(fi[i].value);
				}
			//console.log(arr);
			for(i = 0; i < arr["post_vers_name"].length; ++i)
				vers.push({"vers_name": arr["post_vers_name"][i], "vers_note": arr["post_vers_note"][i]});
			obj = {"type": so, "thumb": arr["post_thumb"], "name": arr["post_name"], "desc": arr["post_desc"], "avers": arr["post_avers"],"vers": vers, "author": aut, "publish_date": pdate, "other_data": {"odata_text": arr["post_odata"], "images": arr["image"], "videos": arr["video"]}};
			if(arr["post_cdate"] != "")
                obj["creation_date"] = Math.round(new Date(arr["post_cdate"].split(/\//).reverse().join('/')).getTime()/1000);
            if(parseInt(so) == 0)
			{
				obj.other_data.link = arr["link"];
				obj.other_data.controls = {};
				obj.other_data.controls.keys = arr["tecla"];
				obj.other_data.controls.actions = arr["accion"];
			} else
				obj.other_data.link = arr["post_link"];
			console.log(obj);
			console.log(arr);
			return obj;
		break;
		case 1:
			return getBBCode();
			break;
	}
}

function manager(i) 
{
	var d = getQueryVariable('do') == 'edit' ? {id: getQueryVariable('id')} : {},
		sel = document.getElementById('project_lang'),
		l = sel.options[sel.selectedIndex].value;
	projectPreset[l] = getManagerJSON(i);
	d.info = JSON.stringify(projectPreset);
	d.action = manager_keywords[i]['add'];
	d.showlist = true;
	//d = JSON.parse(nl2br(JSON.stringify(d)));
	$.ajax({
        url: './includes/admin/main.ajax.php',
        data: d,
        type: 'POST',
        cache: false,
        dataType: 'json',
		success: function(html)
        {
        	showMsg(html);

		},
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
	});
	return false;
}



function resetManager(index)
{
	switch(index) {
		case 0:
			var i = 0,
				fi = document.getElementById('admin_sendpost').querySelectorAll("input[type='text']:not(#datepicker):not([name='post_link']):not([name='link']), textarea");
			for(; i < fi.length; ++i)
				fi[i].value = "";
			break;
		case 1:
			setBBCEditor("");
			break;
	}
}

function changeProjectRemark(id, r)
{
	var d = {id: id, action: 'changeProjectRemark', remark: r, showlist: true};
	$.ajax({
		url: './includes/admin/main.ajax.php',
		data: d,
		type: 'POST',
		cache: false,
		dataType: 'json',
		success: function(html)
		{
			showMsg(html);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log(jqXHR.responseText);
		}
	});
}

function setBBCEditor(value) 
{
	console.log("value: "+value+", stack: "+stacktrace());
	var ifr = document.getElementsByClassName("sceditor-container ltr")[0].children[1],
	   	innerDoc = ifr.contentDocument || ifr.contentWindow.document;
	innerDoc.body.children[0].innerHTML = value;
}

function manageNotepad(e) 
{
	$.ajax({
        url: './includes/admin/main.ajax.php',
        data: {action: e.id, showlist: true, notepad_text: e.elements[0].value},
        type: 'POST',
        cache: false,
        dataType: 'json',
		success: function(html)
        {
        	showMsg(html);
		},
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
	});
	return false;
}

function changeAdOrder(id, pos, new_pos) 
{
	$.ajax({
        url: './includes/admin/main.ajax.php',
        data: {action: 'change_ad_order', showlist: true, id: id, pos: pos, new_pos: new_pos},
        type: 'POST',
        cache: false,
        dataType: 'json',
		success: function(html)
        {
        	showMsg(html);
		},
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
	});
	return false;
}

function valMailOpts(e) 
{
	var vmo = document.getElementById('val_mail_opts'),
		dur = vmo.getElementsByTagName('INPUT')[0],
		nco = document.getElementById('no-confirm_opts'),
		alog = nco.getElementsByTagName('INPUT')[0];
	vmo.style.display = e.value == '1' ? 'block' : 'none';
	dur.enabled = e.value == '1';
	nco.style.display = e.value == '0' ? 'block' : 'none';
	alog.enabled = e.value == '0';
}

function maxLogTriesOpts(e) 
{
	var mlto = document.getElementById('max_log_tries_opts'),
		tries = mlto.getElementsByTagName('INPUT')[0];
	mlto.style.display = e.value == '1' ? 'block' : 'none';
	tries.enabled = e.checked;
}

function loginCaptchaOpts(e) 
{
	var lco = document.getElementById('login_captcha_opts');
	lco.style.display = e.checked ? 'block' : 'none';
}

