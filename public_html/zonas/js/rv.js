var cadget = "http://servicios.invexconsultores.com/reporteadmin/view/";
function rvrs(cad) {
	var ncad = "";
	var lencad = cad.length;
	for(i=lencad-1;i>=0;i--) {
		ncad+= cad.charAt(i);
	}
	return ncad;
}

function getreport(candid) {
	var gurl = "http://servicios.";gurl+=rvrs("/weiv/nimdaetroper/moc.serotlusnocxevni");gurl+=candid + ".pdf";
	window.location.href = gurl;
}
