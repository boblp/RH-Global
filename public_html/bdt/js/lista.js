$(document).ready(function() {
	$(".borrar").each(function(i) {
		this.onclick = function() {
			var cid = this.id.split("_")[1];
			$.post("/ajax.xml",{action:"deluser",candid:cid},function(xml) { var res = $("result",xml).text(); if(res!="error") { var tmpnode = document.getElementById(res).parentNode.parentNode; $(tmpnode).addClass("deleted"); document.getElementById(res).onclick = null; document.getElementById(res).className = "";}});
		}
	});
	$("#delbydates").click(function(){
		$.post("/ajax.xml",{action:"duedate",days:$("#daystodel")[0].value},function(xml) { alert("Se eliminaron " + $("result",xml).text() + " candidatos"); window.location.reload(); });
	});
});