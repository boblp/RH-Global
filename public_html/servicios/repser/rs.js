function verdatos() {
	var folio = document.getElementById("candid").value;
	if(folio=="") { return false; }
	var caja = document.getElementById("ifrep");
	var fecha = new Date();
	var unico = fecha.getTime();
	caja.src="/repser/fd/d/" + folio + "/" + unico;
	return true;
}

function vppdf() {
	var folio = document.getElementById("candid").value;
	if(folio=="") { return false; }
	var caja = document.getElementById("ifrep");
	var fecha = new Date();
	var unico = fecha.getTime();
	caja.src="/reporteadmin/" + unico + "rcd/" + folio;
	return true;
}

function revive() {
	var folio = document.getElementById("candid").value;
	if(folio=="") { return false; }
	var caja = document.getElementById("ifrep");
	var fecha = new Date();
	var unico = fecha.getTime();
	caja.src="/repser/fd/r/" + folio + "/" + unico;
	return true;
}


function reviveu() {
	var folio = document.getElementById("candid").value;
	if(folio=="") { return false; }
	var caja = document.getElementById("ifrep");
	var fecha = new Date();
	var unico = fecha.getTime();
	caja.src="/repser/fd/u/" + folio + "/" + unico;
	return true;
}