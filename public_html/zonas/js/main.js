/* EncodeURIComponent Universal, para trabajar con UTF8 en los requests */
/************************************************************************/
esIE=false;

function utf8(wide) {
  var c, s;
  var enc = "";
  var i = 0;
  while(i<wide.length) {
    c= wide.charCodeAt(i++);
    if (c>=0xDC00 && c<0xE000) {
		continue;
	}
    if (c>=0xD800 && c<0xDC00) {
      if (i>=wide.length) {
		  continue;
	  }
      s= wide.charCodeAt(i++);
      if (s<0xDC00 || c>=0xDE00) {
		  continue;
	  }
      c= ((c-0xD800)<<10)+(s-0xDC00)+0x10000;
    }
    if (c<0x80) {
		enc += String.fromCharCode(c);
	} else if (c<0x800) {
		enc += String.fromCharCode(0xC0+(c>>6),0x80+(c&0x3F));
	} else if (c<0x10000) {
		enc += String.fromCharCode(0xE0+(c>>12),0x80+(c>>6&0x3F),0x80+(c&0x3F));
	} else {
		enc += String.fromCharCode(0xF0+(c>>18),0x80+(c>>12&0x3F),0x80+(c>>6&0x3F),0x80+(c&0x3F));
	}
  }
  return enc;
}

var hexchars = "0123456789ABCDEF";

function toHex(n) {
  return hexchars.charAt(n>>4)+hexchars.charAt(n & 0xF);
}

var okURIchars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-";

function encodeURIComponentNew(s) {
	if (typeof encodeURIComponent == "function") {
		enc = encodeURIComponent(s);
	} else {
		var s = utf8(s);
		var c;
		var enc = "";
		for (var i= 0; i<s.length; i++) {
			if (okURIchars.indexOf(s.charAt(i))==-1) {
			  enc += "%"+toHex(s.charCodeAt(i));
			} else {
			  enc += s.charAt(i);
			}
		}
	}
	return enc;
}


/* Onload Manager */
/************************************************************************/

window.onload = function() {
	//Aqui poner todos los llamados necesarios al onload
	//Siempre que se tenga que repetir en todas las paginas
	// Si no, llamar la funcion addLoad() desde donde se requiera
	
}

function addLoad(funcion) {
	if(typeof window.onload == "function") {
    	var previousOnload = window.onload;
		window.onload = function() {
			previousOnload();
			funcion();
		}
	}else{
		window.onload = funcion;
	}
}

// Estas son algunas utilerias para trabajar con arrays mas php style ;-)
/************************************************************************/

function implode(separador,arr) {
	var tot = arr.length;
	var ultimo = tot-1;
	var resultado = "";
	for (i=0;i<tot;i++) {
		if(arr[i] && arr[i]!="") {
			resultado+=arr[i];
			resultado+= (i<ultimo && ultimo>0) ? separador : "";
		}
	}
	if (resultado.charAt(resultado.length-separador.length)==separador) {
		resultado = resultado.substring(0,resultado.length-separador.length);
	}
	return resultado;
}

function contar(arr) {
	var tot = arr.length;
	var resultado = 0;
	for (i=0;i<tot;i++) {
		if(arr[i] && arr[i]!="") {
			resultado+=1;
		}
	}
	return resultado;
}

function encontrar(valor,arr) {
	var tot = arr.length;
	for (i=0;i<tot;i++) {
		if (arr[i]==valor) {
			return i;
		}
	}
	return false;
}

/* Pausar ejecución... */

function pause(millis) {
	date = new Date();
	var curDate = null;
	do { 
		var curDate = new Date(); 
	} while(curDate-date < millis);
} 



/* MANIPULACION DE ESTILOS */
/************************************************************************/
// Aqui comienzan las funciones para dhtml simple (no cross-browser)


function _porId (_ele) {
	_resultado = new Array();
	if (_elemento = document.getElementById(_ele)) {
		_resultado[0] = _elemento;
	}
	return _resultado;
}

function _porTag(_tag,_clase,_contenedor) {
	_tag = _tag || "*";
	_cont = document.getElementById(_contenedor) || document;

	_temp = _cont.getElementsByTagName(_tag);
	if (_clase=="") {
		return _temp;
	}
	_resultado = new Array();
	total = _temp.length;
	_contador = 0;
	for (i=0;i<total;i++) {
		if (_temp[i].className.indexOf(_clase)!=-1) {
			_resultado[_contador] = _temp[i];
			_contador++;
		}
	}
	return _resultado;
}

function mwEstilo(_elems,_propiedades) {
	_tempProp = _propiedades.split(";");
	_cuantasProp = _tempProp.length;
	_props = new Array();
	_vals = new Array();
	for (i=0;i<_cuantasProp;i++) {
		_separa = _tempProp[i].split(":");
		_props[i] = _separa[0];
		_vals[i] = _separa[1];
	}
	_cuantosElem = _elems.length;
	for (j=0;j<_cuantosElem;j++) {
		for (i=0;i<_cuantasProp;i++) {
			_cambia(_elems[j],_props[i],_vals[i]);
		}
	}
}

function _cambia(_ele,_prop,_valor) {
	switch (_prop) {
		case "color":
		_ele.style.color = _valor;
		break;
		case "background-color":
		_ele.style.backgroundColor = _valor;
		break;
		case "background-image":
		_ele.style.backgroundImage = _valor;
		break;
		case "background-position":
		_ele.style.backgroundPosition = _valor;
		break;
		case "background-repeat":
		_ele.style.backgroundRepeat = _valor;
		break;
		case "border":
		_ele.style.border = _valor;
		break;
		case "border-bottom":
		_ele.style.borderBottom = _valor;
		break;
		case "border-top":
		_ele.style.borderTop = _valor;
		break;
		case "border-left":
		_ele.style.borderLeft = _valor;
		break;
		case "border-right":
		_ele.style.borderRight = _valor;
		break;
		case "margin":
		_ele.style.margin = _valor;
		break;
		case "padding":
		_ele.style.padding = _valor;
		break;
		case "font-weight":
		_ele.style.fontWeight = _valor;
		break;
		case "font-size":
		_ele.style.fontSize = _valor;
		break;
		case "font-family":
		_ele.style.fontFamily = _valor;
		break;
		case "text-align":
		_ele.style.textAlign = _valor;
		break;
		case "text-decoration":
		_ele.style.textDecoration = _valor;
		break;
		case "position":
		_ele.style.position = _valor;
		break;
		case "top":
		_ele.style.top = _valor;
		break;
		case "left":
		_ele.style.left = _valor;
		break;
		case "display":
		_ele.style.display = _valor;
		break;
		case "visibility":
		_ele.style.visibility = _valor;
		break;
		case "z-index":
		_ele.style.zIndex = _valor;
		break;
		case "width":
		_ele.style.width = _valor;
		break;
		case "height":
		_ele.style.height = _valor;
		break;
	}
	return;
}

function mwEstiloId(_elemid,_propiedades) {
	_elems = _porId(_elemid);
	mwEstilo(_elems,_propiedades);
}

function mwEstiloTag(_tag,_clase,_contenedor,_propiedades) {
	_elems = _porTag(_tag,_clase,_contenedor);
	mwEstilo(_elems,_propiedades);
}

function mwShow(_cadShow,_cadHide) {
	if (_cadHide!="") {
		_oculta = _cadHide.split(":");
		if (_oculta[0]=="class") { mwEstiloTag("",_oculta[1],"","display:none"); }
		if (_oculta[0]=="id") { mwEstiloId(_oculta[1],"display:none"); }
	}
	_muestra = _cadShow.split(":");
	if (_muestra[0]=="class") { mwEstiloTag("",_muestra[1],"","display:block"); }
	if (_muestra[0]=="id") { mwEstiloId(_muestra[1],"display:block"); }
}

function mwShow2(_cadShow,_cadHide) {
	if (_cadHide!="") {
		_oculta = _cadHide.split(":");
		if (_oculta[0]=="class") { mwEstiloTag("",_oculta[1],"","display:none"); }
		if (_oculta[0]=="id") { mwEstiloId(_oculta[1],"display:none"); }
	}
	_muestra = _cadShow.split(":");
	if (_muestra[0]=="class") { mwEstiloTag("",_muestra[1],"","display:inline"); }
	if (_muestra[0]=="id") { mwEstiloId(_muestra[1],"display:inline"); }
}

function mwHide(_cadHide) {
	_oculta = _cadHide.split(":");
	if (_oculta[0]=="class") { mwEstiloTag("",_oculta[1],"","display:none"); }
	if (_oculta[0]=="id") { mwEstiloId(_oculta[1],"display:none"); }
}

// Chart cheats

var hoy = new Date();

function chartVar(varname,varval,vararr) {
	vararr.push(varname + "=" + escape(varval));
}

function getUrlVars(classtxt) {
	var urlvars = new Object();
	var varcount = 0;
	var parts = classtxt.split(" ");
	var i;
	if(parts.length<=1) {
		return false;
	}
	for(i=0;i<parts.length;i++) {
		if(parts[i].indexOf("ch-")!=-1) {
			var varsplit = parts[i].split("-");
			if(varsplit.length==3) {
				urlvars[varsplit[1]] = varsplit[2];
				varcount++;
			}
		}
	}
	if(varcount) {
		// Default a mini grafica, para no descuadrar nada
		urlvars.w = (urlvars.w) ? urlvars.w : 300;
		urlvars.h = (urlvars.h) ? urlvars.h : 200;
		urlvars.t = (urlvars.t) ? urlvars.t : "badreq";
		return urlvars;
	} else {
		return false;
	}
}

function goChart() {
	var charts = _porTag("div","chartholder","cuerpo");
	var i;
	var chw,chh;
	if(charts.length) {
		for(i=0;i<charts.length;i++) {
			var vars = new Array();
			var urlvars = getUrlVars(charts[i].className);
			if(urlvars) {
				chartVar("library_path","/chrt/charts_library",vars);
				chartVar("xml_source","/xmldata/" + hoy.getTime() + "/" + urlvars.t + "/" + urlvars.v + ".xml",vars);
				showChart(charts[i],i,urlvars.w,urlvars.h,vars);
			}
		}
	}
}



function clrmem() {
	var lnks = _porTag("a","chblocks","cuerpo");
	var i;
	if(lnks.length) {
		for(i=0;i<lnks.length;i++) {
			lnks[i].onclick = null;
		}
	}
}


function showChart(ele,numid,w,h,vars) {
	var myChart = document.createElement("div");
	chvars = implode("&",vars);
	chw = w;
	chh = h;
	myChart.style.width =  chw + "px";
	myChart.style.height = chh + "px";
	chid = "chart_" + numid;
	myChart.innerHTML = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="' + chw + '" height="' + chh + '" id="' + chid + '" align=""><param name="movie" value="/chrt/charts.swf" /><param name="scale" VALUE="ShowAll"><param name="wmode" value="transparent" /><param name="flashvars" value="' + chvars + '" /><param name="quality" value=high /><param name="bgcolor" value="#ffffff" /><embed wmode="transparent" src="/chrt/charts.swf" quality="high" bgcolor="#ffffff" width="' + chw + '" height="' + chh + '" name="' + chid + '" flashvars="' + chvars + '" align="" swliveconnect="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object>';
	ele.appendChild(myChart);
	var ablock = document.createElement("a");
	var abspan = document.createElement("span");
	abspan.appendChild(document.createTextNode("Chart"));
	ablock.appendChild(abspan);
	ablock.href="#";
	ablock.onclick = function() {
		this.blur();
		return false;
	}
	ablock.className="chblock";
	ablock.style.width = chw + "px";
	ablock.style.height = chh + "px";
	ele.appendChild(ablock);
	
}

window.onunload = function() {
	clrmem();
}

addLoad(goChart);