//Ajax handler

var furl = "./main.ajax.php";

$(document).ready(function()
{
    /*Arrancamos la marquesina*/
    getAds();
    /*Finalizado la marquesina*/

    $('form').on('submit', function (e)
    { //sendForm(elem, furl, sl, datatype, c)
        sendForm(this, e);
    });
});

function sendForm(t, e)
{
    e.preventDefault();
    var id = $(t).attr('id'),
        d = $(t).serialize()+"&action="+id,
        returnlp = id == "register",
        c = "showMsg(html, returnlp);",
        sl = true,
        data_type = "json";
    if(id == "logout") // || añadir más opciones las cuales solo esten en el main.ajax.php del root
        furl = "./main.ajax.php";
    if($(t).find("#success_function").length)
        c = $(t).find("#success_function").val();
    if($(t).find("#show_list").length)
        sl = $(t).find("#show_list").val() == "true";
    if($(t).find("#data_type").length)
        data_type = $(t).find("#data_type").val();
    if(sl) d += "&showlist";
    //console.log(d);
    $.ajax({
        type: "POST",
        url: furl, //furl || "./main.main.ajax.php", //Esto no se va a hacer nunca más así, puesto que el de la administración tiene su propio archivo de ajax y no hace falta refenciar a este
        data: d,
        dataType: data_type || "json",
        cache: false,
        success: function(html) {
            eval(c);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
    return false;
}

//Lang & Settings

var lang = [], lang_name, settings;

(function() {
    $.ajax({
        url: './lang/lang.php?getlang',
        type: 'GET',
        cache: false,
        dataType: 'json',
        success: function(html)
        {
            console.log(html);
            lang = html["lang"];
            lang_name = html["lang_name"];
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
})();

//Online

var lastpage = localStorage.getItem("lastpage"),
    statusIntervalId = window.setInterval(gop, 5000);

function gop() 
{
    var otime = document.getElementById('otime'),
        data = otime.options[otime.selectedIndex].value;
    $.ajax({
        type: 'POST',
        url: './main.ajax.php',
        data: {action: 'getOnlinePeople', data: data}, //'action=getOnlinePeople&data='+data,
        //dataType: 'text',
        success: function(data)
        {
            var _gop = document.getElementById('gop');
            if(_gop.innerHTML != data) {
                _gop.innerHTML = data;
                _gop.style.color = "#ff0000";
                setTimeout(function () {
                    _gop.style.color = "#fff";
                }, 250);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
}

function showhide(elem) 
{
    elem.style.display = ((elem.style.display == "block") ? "none" : "block");
}

function get(id) 
{
    return document.getElementById(id).value;
}

/*function sendForm(elem, furl, sl, datatype, c)
{
    c = c || "showMsg(html, returnlp);";
    sl = sl || true;
    var returnlp = elem.id == "register",
        d = $(elem).serialize()+"&action="+elem.id;
    if(sl) d += "&showlist";
    console.log(d);
    $.ajax({
        type: "POST",
        url: furl || "./main.main.ajax.php",
        data: d,
        dataType: datatype || "json",
        cache: false,
        success: function(html) {
            eval(c);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
    return false;
}*/

//Esto tiene q ir dividio en 2 fases, una q envie el post y otro q lo reciba en la misma funcion 
function showMsg(data, returnlp) 
{
    console.log(data);
    if(typeof data != "object") data = JSON.parse(data);
    if(!data.f) {
        returnlp = returnlp || false;
        data.f = true;
        $.redirect(returnlp ? lastpage : window.location, data);
    } else {
        var msg = document.getElementById("msg");
        msg.innerHTML = data.msg;
        msg.className = "msg "+(data.success == 1 ? "success" : "error");
    }
}

/*function displayMsg(key) 
{
    showMsg({"success":"1","msg":lang[key],"f":"true"});
}*/

var Color = function(red, green, blue) 
{
  this.r = red;
  this.g = green;
  this.b = blue;
  Color.lerpColor = function(f, t, p) 
  {
    return new Color(Math.lerp(f.r, t.r, p), 
                     Math.lerp(f.g, t.g, p), 
                     Math.lerp(f.b, t.b, p));
  };
  Math.lerp = function(min, max, p) 
  {
    return min + (max - min) * p;
  };
  this.string = function() 
  {
    return "rgb("+Math.round(this.r)+", "+Math.round(this.g)+", "+Math.round(this.b)+")";
  };
};

var red = new Color(255, 0, 0),
    gray = new Color(204, 204, 204);

function charcount(input, maxchars) 
{
    maxchars = maxchars || 5000;

    if(input.maxLength == -1)
        input.maxLength = maxchars;

    var r = maxchars - input.value.length,
        f = input.parentNode.children[0];

    if(r < maxchars*0.1) f.style.color = Color.lerpColor(red, gray, r/(maxchars*0.1)).string(); 
    f.innerHTML = r+' caracteres';
}

function stacktrace() 
{
    return (new Error()).stack;
}

function showResults(html) 
{
    console.log(html);
    var i = 0, table = document.createElement("table"), cu;
    for(; i < html.projects.length; ++i) 
    {
        cu = html.projects[i].data[lang_name];
        table.innerHTML += '<tr><td><img class="game-thumb" src="'+cu.thumb+'"></td><td class="mem_pres"><b>'+lang.name+':</b> '+cu.name+'<br><b>'+lang.desc+':</b> '+cu.desc+'<br><b>'+lang.avers+':</b> '+cu.avers+'<br><b>'+lang.author+':</b> '+cu.author+'<br><b>'+lang.type+':</b> '+lang.projectType[cu.type]+'<br><b>'+lang.creation_date+':</b> '+cu.creation_date+'<br><b>'+lang.publish_date+':</b> '+cu.publish_date+'<br><b>'+lang.odata+':</b> '+cu.other_data.odata_text+'<br><b style="font-size: 16px;"><a href="index.php?action=preview&id='+html.projects[i].id+'">'+lang.see[cu.type]+'</a></b></td></tr>';
    }
    document.getElementById("projects").innerHTML = table.outerHTML;
    document.getElementById('pag').innerHTML = html.paginator;
}

function hideAd(e, c) 
{
    c = c || false;
    e.style.display = 'none';
    e.nextElementSibling.style.display = 'none';
    if(c) createCookie('hideAd', 'true', 1000);
}

function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    } else {
        var expires = "";
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

function getBBCode() 
{
    var ifr = document.getElementsByClassName("sceditor-container ltr")[0].children[1],
        innerDoc = ifr.contentDocument || ifr.contentWindow.document,
        bbcode = innerDoc.body.children[0].innerHTML;
    return bbcode;
}

function appendSibling(e, node)
{
    var r = null;
    if (e.nextSibling)
        r = e.parentNode.insertBefore(node, e.nextSibling);
    else
        r = e.parentNode.appendChild(node);
    return r;
}

function spacer(e) //Poder poner estilos 
{
    var t = Math.floor(e.offsetWidth/5.5), i = 0, s = '';
    for(; i < t; ++i)
        s += '-';
    e.insertAdjacentHTML('afterend', '<br>'+s+'<br>');
}

function nl2br (str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function getSetting(name, f) 
{
    $.ajax({
        type: 'POST',
        url: './main.ajax.php',
        data: {action: 'getSetting', name: name},
        dataType: "json",
        cache: false,
        success: function(html) {
            f(html);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
    return false;
}

function getRandomArbitrary(min, max) 
{
    return Math.random() * (max - min) + min;
}

function getRandomInt(min, max) 
{
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function castHTML(str) 
{
    var parser = new DOMParser(), doc = parser.parseFromString(str, "text/xml");
    return doc;
}

function getChildIndex(child) 
{
    var i = 0;
    while((child = child.previousElementSibling) != null) 
        ++i;
    return i;
}

function checkDuration() 
{
    var dur = document.getElementById("duration"), 
        inf = document.getElementById("infinite");
    dur.readOnly = inf.checked;
    dur.value = inf.checked ? dur.max : dur.min;
}

if(document.URL.indexOf("login") === -1 && document.URL.indexOf("logout") === -1)
    localStorage.setItem("lastpage", document.URL);


$('forms').submit(function () {
    return false;
});



/**  Marquesina   **/
/** Se carga la funcion al estar cargada por completo toda la pantalla **/
function getAds()
{
    $.ajax({
        type: "POST",
        url: './main.ajax.php',
        data: 'action=getads',
        cache: false,
        dataType: 'json',
        success: function(html) {
            ads = html;
            addAds(ads);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
}

function addAds(ads) 
{
    console.debug(ads);
    st = '';
    for (var i = 0;i<ads.length;i++)
    {
        st += ads[i]['data'];

        if(i<ads.length-1)
        {
            st += "  ||  ";
        }
    }
    $('#marqueeTxt').append(st);
}

(function ($) {

    window.addRule = function (selector, styles, sheet) {
        
        styles = (function (styles) {
            if (typeof styles === "string") return styles;
            var clone = "";
            for (var p in styles) {
                if (styles.hasOwnProperty(p)) {
                    var val = styles[p];
                    p = p.replace(/([A-Z])/g, "-$1").toLowerCase(); // convert to dash-case
                    clone += p + ":" + (p === "content" ? '"' + val + '"' : val) + "; ";
                }
            }
            return clone;
        }(styles));
        sheet = sheet || document.styleSheets[document.styleSheets.length - 1];

        if (sheet.insertRule) sheet.insertRule(selector + " {" + styles + "}", sheet.cssRules.length);
        else if (sheet.addRule) sheet.addRule(selector, styles);

        return this;

    };

    if ($) $.fn.addRule = function (styles, sheet) {
        addRule(this.selector, styles, sheet);
        return this;
    };

}(window.jQuery));

function toggleAvatarBubble(e, toggle) 
{
    if(toggle) 
    {
        e.style.display = 'block';
        var rect = e.getBoundingClientRect();
        //console.log(rect.top, rect.right, rect.bottom, rect.left);
        //console.log(window.innerWidth, window.innerHeight);
        //console.log(window.innerWidth - rect.left, e.children[0].offsetWidth, window.innerWidth - rect.left < e.children[0].offsetWidth);
        var t, b;
        if(rect.left < 0)
        {
            t = Math.round(-200+Math.abs(rect.left));
            b = (Math.abs(t) - 222) + "px";
            e.style.left = t + "px";
            e.children[0].children[1].style.left = b;
        }
        else if(window.innerWidth - rect.left < e.children[0].offsetWidth)
        {
            t = Math.round(-570+Math.abs(rect.right));
            b = (Math.abs(t) - 294) + "px";
            e.style.right = t + "px";
            e.style.left = "auto";
            e.children[0].children[1].style.left = b;
        }
    }
    else
    {
        e.style.display = 'none';
        e.style.left = "-212px";
    }
}

/**  Marquesina   **/