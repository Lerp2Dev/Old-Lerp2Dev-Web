function addThumb(e, v, t, url, set_at_start) 
{    
	if(typeof set_at_start === 'undefined') set_at_start = true;

    if(url.length == 0)
    {
        e.parentNode.firstChild.innerHTML = "ERROR: No puedes especificar un link vacío.";
        return;
    }

	if(e && thumb_exists(e.parentNode.firstChild, url)) 
    	return;

	var smallThumb = null,
		hidValue = document.createElement('input');

	l = document.getElementById('btnLeftArrow');
    r = document.getElementById('btnRightArrow');

	switch(t) 
	{
		case 'image':
			smallThumb = document.createElement('img');
			smallThumb.src = url;
			smallThumb.className = 'btnThumb';
			v.appendChild(smallThumb);
			break;
		case 'video':
			smallThumb = document.createElement('div');
			var vid = document.createElement('iframe'),
				dvid = document.createElement('div');
			vid.frameBorder = "0";
			vid.src = 'http://www.youtube.com/embed/' + url;
			smallThumb.appendChild(vid);
			smallThumb.appendChild(dvid);
			v.appendChild(smallThumb);
			break;
	}

	smallThumb.className = 'box';
	smallThumb.setAttribute('onclick', 'click_set(this);'); //set(this.parentNode, '+(!e)+');

	if(e) 
	{
		hidValue.type = 'hidden';
		hidValue.name = t+'[]';
		hidValue.value = url;
		document.getElementById('c3').appendChild(hidValue);
	}

	if(v.children.length > 4) 
	{
		if(l.style.display == 'none') l.style.display = 'inline-block';
		if(r.style.display == 'inline-block') r.style.display = 'none';
		v.style.left = (v.children.length-5)*-widthBox + 'px';
		v.style.width = v.children.length*widthBox + 'px';
	}

	if(set_at_start) 
	{
		s = v.children.length - 1;
		set(v, !e);
	}

	if(e)
        closePopup(e, t);
		/*e.parentNode.firstChild.innerHTML = '';
		if(t == 'image') 
		{
			e.previousElementSibling.firstElementChild.firstElementChild.firstElementChild.children[2].style.display = 'block';
			e.previousElementSibling.firstElementChild.firstElementChild.firstElementChild.firstElementChild.value = "";
			e.parentNode.parentNode.style.display = "none";
			e.parentNode.parentNode.parentNode.style.display = "none";
		}
		else if(t == 'video') 
		{
			e.previousElementSibling.previousSibling.value = "";
			e.parentNode.parentNode.style.display = "none";
			e.parentNode.parentNode.parentNode.style.display = "none";
		}*/
}

function closePopup(e, t)
{
    e.parentNode.firstChild.innerHTML = '';
    if(t == 'image')
    {
        e.previousElementSibling.firstElementChild.firstElementChild.firstElementChild.children[2].style.display = 'block';
        e.previousElementSibling.firstElementChild.firstElementChild.firstElementChild.firstElementChild.value = "";
        e.parentNode.parentNode.style.display = "none";
        e.parentNode.parentNode.parentNode.style.display = "none";
    }
    else if(t == 'video')
    {
        e.previousElementSibling.previousSibling.value = "";
        e.parentNode.parentNode.style.display = "none";
        e.parentNode.parentNode.parentNode.style.display = "none";
    }
}

function arrowClick(e)
{
	upt(e.id.indexOf('Left') > -1, e.parentNode.children[2].children[0]);
}

function upt(di, v) 
{
	l = document.getElementById('btnLeftArrow');
	r = document.getElementById('btnRightArrow');
    if(document.getElementById("thumbRect").firstElementChild.children.length > 4)
        if (di) //negative == Left clicked
        {
            if (s >= 0 && s < v.children.length) --s;
            l.style.display = s <= 0 ? 'none' : 'inline-block';
            r.style.display = s > 0 && s < v.children.length ? 'inline-block' : 'block';
            if (v.children.length - s >= 5 && Math.abs(parseInt(v.style.left)) > 0) v.style.left = (((v.children.length - 5) * -widthBox) + (v.children.length - s - 4) * widthBox) + 'px';
        }
        else {
            if (s >= 0 && s < v.children.length) ++s;
            r.style.display = s >= v.children.length - 1 ? 'none' : 'inline-block';
            l.style.display = s > 0 && s < v.children.length ? 'inline-block' : 'block';
            if (v.children.length - s < 5 && parseInt(v.style.left) > (v.children.length - 5) * -widthBox) v.style.left = ((v.children.length - s - 5) * widthBox) + 'px';
        }
    else
      if(di)
      {
          if (s >= 0 && s < v.children.length) --s;
      }
      else
      {
          if(s >= 0 && s < v.children.length) ++s;
      }
	if(s == -1) ++s;
	else if(s == v.children.length) --s;
	set(v);
}

function set(v, lite) 
{
	if(!v.children[s]) return; //Rare bugs
	var bt = document.getElementById('bigThumb'), c_img;
	if(bt.innerHTML.length > 0) bt.innerHTML = "";
	//check if image is already setted
	if(!lite) 
	{
		if(v.children[s].tagName == "IMG")
		{
            edit.alt = "Editar enlace de esta imagen.";
            trash.alt = "Borrar esta imagen.";
            pushpin.alt = "Establecer esta imagen como miniatura del proyecto.";
		}
		else if(v.children[s].tagName == "DIV")
		{
            edit.alt = "Editar enlace de este vídeo.";
            trash.alt = "Borrar este vídeo.";
		}
        edit.setAttribute('onclick', 'edit_link(' + s + ', \'' + (v.children[s].tagName == "IMG" ? 'image' : 'video') + '\')');
		trash.setAttribute('onclick', 'deletekey(' + s + ')');
		pushpin.setAttribute('onclick', 'setthumb(this, ' + s + ')');
        bt.appendChild(edit);
		bt.appendChild(trash);
		if(v.children[s].tagName == "IMG" && !is_set(v.children[s].src)) bt.appendChild(pushpin); //append push-pin
		if(pushpin.style.display == 'none') pushpin.style.display = 'block';
	} 
	else 
	{
		c_img = document.createElement('img');
		c_img.src = './images/icons/close.png';
		c_img.setAttribute('onclick', "$('#bigThumb').slideUp('slow', null);");
		bt.appendChild(c_img);
		$("#bigThumb").slideDown("slow", null);
	}
	if(v.children[s].tagName == "IMG") 
	{
 		bt.style.backgroundImage = "url('"+v.children[s].src+"')";
 		bt.style.backgroundSize = '100% 100%';
	}
	else if(v.children[s].tagName == "DIV") 
	{
		var vid = document.createElement('iframe');
		vid.style.width = '340px';
		vid.style.height = '340px';
		vid.frameBorder = "0";
		vid.src = v.children[s].firstChild.src;
		bt.appendChild(vid);
	}
	for(i = 0; i < v.children.length; ++i)
    {
		v.children[i].style.border = "0";
		v.children[i].style.width = '64px';
		v.children[i].style.height = '64px';
		v.children[i].style.position = '';
		v.children[i].style.top = "";
	}
	v.children[s].style.border = "1px red solid";
	v.children[s].style.width = '62px';
	v.children[s].style.height = '62px';
	if(v.children[s].tagName == "DIV") 
	{
		v.children[s].style.position = 'relative';
		v.children[s].style.top = '-1px';
	}
}
function wheely(ev, e) 
{
	if(e.children.length <= 5)
		return;
	ev.stopPropagation();
	ev.preventDefault();
	ev.returnValue = false;
	ev.cancelBubble = false;
	upt(ev.wheelDelta > 0, e);
}

function click_set(child)
{
  var p = child.parentNode,
      c = p.children,
      i = 0,
      j = 0;
    for (; i < c.length; ++i)
        if (child == c[i])
            break;
    var deltaS = s - i;
    for(; j < Math.abs(deltaS); ++j)
        upt(deltaS > 0, document.getElementById('thumbRect').firstElementChild);
}