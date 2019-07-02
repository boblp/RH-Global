window.onload = function() {
	forma = document.getElementById("ingreso");
	if(testajax()) {
		document.getElementById("botonsubmit").onclick = function() { forma.submit() };
	}
	forma.loginusr.focus();
}

function alerta() {
	alert("NO SE PUEDE INGRESAR\nEsta aplicacion requiere un navegador de ultima\ngeneracion, como Internet Explorer 5.5 o superior, o Firefox");
}

function testajax() {
	var dom = (document.getElementById && document.getElementsByTagName);
	// La que sigue la invierto para que me sirva como validador
	var ie5 = (typeof(esIE50)!="undefined") ? false : true;
	var xhr = false;
	if(dom && ie5) {
		if(window.ActiveXObject) {
			xhr = true;
		} else if(window.XMLHttpRequest) {
			xhr = true;
		}
	}
	return (dom && ie5 && xhr);
}