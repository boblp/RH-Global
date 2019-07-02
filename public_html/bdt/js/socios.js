$(document).ready(function() {
	$("#lsocios").tableSorter({stripingRowClass: ['even','odd']});
	$(".borrar").each(function(i) {
		this.onclick = function() {
			doDelete(this);
		}
	});
	$(".editar").each(function(i) {
		this.onclick = function() {
			doEdit(this.id);
		}
	});
	$("#addnew").each(function(i) {
		this.onclick = function() {
			doAdd();
		}
	});
});

var doDelete = function(ele) {
	if(confirm("¿Seguro que deseas eliminar este usuario de la lista?")) {
		$.post("ajax.xml",{action:"delsocio",socioid:ele.id.split("_")[1]},function(xml) { if($("result",xml).text()=="ok") { removeRow(ele); } });
	}
}

var removeRow = function(ele) {
	var fila = ele.parentNode.parentNode;
	var tabla = fila.parentNode;
	tabla.removeChild(fila);
	var nfilas = tabla.childNodes;
	var claseNueva = "";
	for(i=0;i<nfilas.length;i++) {
		if(nfilas[i].tagName == "TR") {
			claseNueva = (claseNueva.indexOf("even")!=-1) ? "odd" : "even";
			claseNueva+= (nfilas[i].className.indexOf("inact")!=-1) ? " inactivo" : "";
			nfilas[i].className = claseNueva;
		}
	}
}

var doEdit = function(eleid) {
	$.post("ajax.xml",{action:"socioform",socioid:eleid.split("_")[1],restype:"html"},function(xml) { $("#editarea").html(xml); });
}

var doAdd = function() {
	$.post("ajax.xml",{action:"socioform",socioid:"Nuevo",restype:"html"},function(xml) { $("#editarea").html(xml); });
}

var doSave = function(theForm) {
	var jDepto = theForm.depto.value;
	var jContacto = theForm.contacto.value;
	var jEmail = theForm.email.value;
	var jUsuario = theForm.usuario.value;
	var jPass = theForm.pass.value;
	var jStatus = theForm.statusgral.value;
	var jTipo = theForm.tipo.value;
	var jId = theForm.socioid.value;
	if(!jDepto || !jContacto || !jUsuario || !jPass) {
		alert("Debe capturarse Departamento, contacto, usuario y password para poder guardar.");
	} else {
		$.post("ajax.xml",{action:"sociosave",socioid:jId,depto:jDepto,contacto:jContacto,email:jEmail,usuario:jUsuario,pass:jPass,tipo:jTipo,statusgral:jStatus},function(xml) { if($("result",xml).text()=="ok") { $("#editarea").empty(); window.location.reload(); } else { alert("No se ha guardado, favor de volverlo a intentar"); } });
	}
}