/* 	
	PAWS = PHP Ajax Web Services
	PAWS CLIENTE
	Framework AJAX creado por Manolo Guerrero
	requiere server side especifico (propietario), actualmente solo disponible en PHP
	Ambos lados (cliente/servidor) se distribuyen con licencia Creative Commons By 2.0
*/



// Para almacenar el tipo de request (IE)
var tipoRequest = null;

// Constructor
var paws = function(){
	this.vars = new Array();
}

paws.prototype.addVar = function(variable,valor) {
	this.vars.push(variable + "=" + encodeURIComponent(valor));
}


// Creador de la instancia de xmlHttpRequest
paws.prototype.go = function(grupo,accion,funcion) {
	var xhold = null;
	if(tipoRequest) {
		xhold = new ActiveXObject(tipoRequest);
	} else {
		if (window.XMLHttpRequest) {
			xhold = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			var versiones = ["Msxml2.XMLHTTP.7.0", "Msxml2.XMLHTTP.6.0", "Msxml2.XMLHTTP.5.0", "Msxml2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"];
			for (var i=0; i<versiones.length; i++) {
				try {
					xhold = new ActiveXObject(versiones[i]);
					if (xhold) {
						tipoRequest = versiones[i];
						break;
					}
				} catch(e) {
					// Nada... La que sigue!
				}
			}
		}
	}
	this.vars.push("accion=" + encodeURIComponent(accion));
	xhold.onreadystatechange = function() {
		if (xhold.readyState == 4) {
			if (xhold.status == 200) {
				var xx = { resultado:0, tipo:"", encoding:"", contenido:""};
				xx.resultado = xhold.responseXML.getElementsByTagName("resultado")[0].firstChild.nodeValue;
				xx.mensaje = xhold.responseXML.getElementsByTagName("mensaje")[0].firstChild.nodeValue;
				xx.contenido = xhold.responseXML.getElementsByTagName("item")[0].firstChild.nodeValue;
				xx.tipo = xhold.responseXML.getElementsByTagName("item")[0].getAttribute("tipo");
				xx.encoding = xhold.responseXML.getElementsByTagName("item")[0].getAttribute("encoding");
				funcion(xx);
				xhold = null;
			} else {
				alert("No se pudo procesar, el servidor respondio:\n" + xhold.statusText);
			}
		}
	}
	var url = "/ajaxserver/" + grupo + ".ajax"
	xhold.open("POST",url,true);
	//xhold.setRequestHeader("Connection", "close");
	xhold.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhold.setRequestHeader("Method", "POST " + url + "HTTP/1.1");
	xhold.send(implode("&",this.vars));
}




// Funcion generica para procesar requests.
// Puede crearse cualquier funcion para procesar el resultado, dicha funcion recibirá
// un objeto con las 5 propiedades del objeto paws.x
// Ya con eso puedo hacer cualquier cosa, pero la mas comun (eval) la ejecuta esta funcion (corre)

function corre(obj) {
	eval(b64.decode(obj.contenido));
}
