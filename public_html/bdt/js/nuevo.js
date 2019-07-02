$(document).ready(function() {
	$("input[@type=text]").focus(function() { $(this).addClass("highlighted");});
	$("input[@type=text]").blur(function() {$(this).removeClass("highlighted");});
	$("#tm_eadd").click(addEsc);
	$("#tm_ladd").click(addLab);
	$("#saveform").click(sendForm);
	//setTimeout("fadeNote()",2000);
});


function fadeNote() {
	$("#oksaved").fadeOut(1500,delNote);
}

function delNote() {
	var div2delete = $("#oksaved")[0];
	div2delete.parentNode.removeChild(div2delete);
	return;
}

function addEsc() {
	var _de = $("#tm_ede")[0].value;
	var _a = $("#tm_ea")[0].value;
	var _in = $("#tm_ein")[0].value;
	var _dt = $("#tm_edt")[0].value;
	if(!(_de&&_a&&_in&&_dt)) {
		alert("Deben llenarse los campos para poder agregar");
	} else {
		var _ne = $.DIV({ Class:"elemento",contentEditable:true },
			$.P({ Class:"pdatos" },
				$.STRONG({},_in),
				$.BR({}),
				$.STRONG({},$.SPAN({Class:"fechas"},"De " + _de + " a " + _a)),
				$.BR({}),
				$.SPAN({},_dt)
			)
		);
		$("#holdesc").append(_ne);
		$("#histed")[0].value = $("#holdesc")[0].innerHTML;
		$(".mf_e").each(function(i){this.value="";});
	}
}

function addLab() {
	var _de = $("#tm_lde")[0].value;
	var _a = $("#tm_la")[0].value;
	var _em = $("#tm_lem")[0].value;
	var _pu = $("#tm_lpu")[0].value;
	var _dt = $("#tm_ldt")[0].value;
	if(!(_de&&_a&&_em&&_pu&&_dt)) {
		alert("Deben llenarse los campos para poder agregar");
	} else {
		var _ne = $.DIV({ Class:"elemento",contentEditable:true },
			$.P({ Class:"pdatos" },
				$.STRONG({},_em + " (" + _pu + ")"),
				$.BR({}),
				$.STRONG({},$.SPAN({Class:"fechas"},"De " + _de + " a " + _a)),
				$.BR({}),
				$.SPAN({},_dt)
			)
		);
		$("#holdlab").append(_ne);
		$("#histlab")[0].value = $("#holdlab")[0].innerHTML;
		$(".mf_l").each(function(i){this.value="";});
	}
}

function pad(num) {
	if(num<10) {
		return "0" + num;
	} else {
		return "" + num;
	}
}

function sendForm() {
	if($("#nombre")[0].value=="") { alert("El nombre no puede quedar vacío"); return false;};
	if($("#sexom")[0].checked==false && $("#sexof")[0].checked==false) { alert("Debes indicar el sexo del aplicante"); return false;};
	var fechanac = new Date();
	fechanac.setFullYear($("#fnyr")[0].value);
	fechanac.setMonth($("#fnmo")[0].value-1);
	fechanac.setDate($("#fndy")[0].value);
	if(isNaN(fechanac.getDate())) {
		alert("Debes indicar la fecha de nacimiento completa");
		return false;
	} else {
		$("#fnac")[0].value=fechanac.getFullYear() + "-" + pad(fechanac.getMonth()+1) + "-" + pad(fechanac.getDate());
	}
	if($("#ecivil")[0].value=="No") { alert("Debes indicar el estado civil"); return false;};
	if($("#carrera_id")[0].value=="" || isNaN(parseInt($("#carrera_id")[0].value))) { alert("El ID de Carrera no puede quedar vacío"); return false;};
	if($("#univ_id")[0].value=="" || isNaN(parseInt($("#univ_id")[0].value))) { alert("El ID de Universidad no puede quedar vacío"); return false;};
	if($("#domicilio")[0].value=="") { alert("El domicilio no puede quedar vacío"); return false;};
	if($("#ciudad")[0].value=="") { alert("La ciudad no puede quedar vacía"); return false;};
	if($("#zona")[0].value=="") { alert("La zona no puede quedar vacía"); return false;};
	if($("#telefono")[0].value=="") { alert("El  no puede quedar vacío"); return false;};
	if($("#niveled")[0].value=="No") { alert("Debes seleccionar un nivel de estudios"); return false;};
	if($("#keywords")[0].value=="") { alert("Introduce al menos una palabra clave"); return false;};
	$(".ffs").each(function(i){this.parentNode.removeChild(this);});
	$("#ncand")[0].submit();
}