mainstart = function() {
	$(".srvdesc").css("display","none");
	startshow("investigacion");
	$(".lnksrv").click(function(){
		$(".lnksrv").removeClass("active");
		srvname = $(this).attr("href").split("#")[1];
		$(".srvdesc:visible").slideUp("slow",function(){
			startshow(srvname.split("_")[1]);
		});
		return false;
	});
}

var startshow = function(secname) {
	$(".lnk_" + secname).addClass("active");
	$("#srv_" + secname).show("slow");
}