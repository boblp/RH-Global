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

function diasenmes(mm,aaaa) {
	var dias = 31;
	switch(mm) {
		case 4:
		case 6:
		case 9:
		case 11:
			dias = 30;
			break;
		case 2:
			dias = (((aaaa % 4 == 0) && ( (!(aaaa % 100 == 0)) || (aaaa % 400 == 0))) ? 29 : 28 );
			break;
	}
	return dias;
}

function checafecha(aaaa,mm,dd) {
	var anio = parseInt(aaaa,10);
	var mes = parseInt(mm,10);
	var dia = parseInt(dd,10);
	if(!anio || !mes || !dia) {
		return "Año, mes y día deben ser numéricos";
	}
	if(anio<1940 || anio>2100) {
		return "El año debe ser de 4 digitos y dentro de un rango correcto";
	}
	if(mes<1 || mes>12) {
		return "El mes debe ser un numero entre 1 y 12";
	}
	if(dia<1 || dia>diasenmes(mes,anio)) {
		return "El día no coincide con el mes y el año";
	}
	return "OK";
}

function foco() {
	inputs = document.getElementsByTagName("input");
	for (var i = 0;i<inputs.length; i++) {
		if(inputs[i].className.match(/(^ftxt)/)) {
			if(esIE) {
				this.autocomplete = "off";
			}
			inputs[i].value="";
			inputs[i].onfocus = function() {
				this.style.backgroundColor="#fafad2";
				this.parentNode.getElementsByTagName("label")[0].style.fontWeight="bold";
			}
			inputs[i].onblur = function() {
				this.style.backgroundColor="#ffffff";
				this.parentNode.getElementsByTagName("label")[0].style.fontWeight="normal";
			}
		}
	}
}
addLoad(foco);