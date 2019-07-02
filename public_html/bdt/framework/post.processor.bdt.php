<?php
switch($ppc[1]) {
	case "addresume":
		// Last stop. If everything is ok at this point, then we can proceed to BD insertion
		if(!$v->haserrors()) {
			$v->setdefault("turnos",0);
			$v->setdefault("viajar",0);
			$v->setdefault("licencia",0);
			$v->setdefault("autopropio",0);
			$v->forcevalue("statusgral",1);
			$v->forcevalue("histed",cleanup($v->data["histed"],"Edu"));
			$v->forcevalue("histlab",cleanup($v->data["histlab"],"Lab"));
			$insertdata = $v->getdata(true);
			$query = $bd->quickinsert("bdt",$insertdata);
			$nid = $bd->insert($query);
			$_SESSION["saved"] = $insertdata["nombre"];
			$_SESSION["savedid"] = $nid;
		}
		$redirect = $_SERVER["REQUEST_URI"];
		$errmsg = "errorfrm";
		$fdata = "formdat";
		break;
		
	default:
		die("Error");
		break;
}
?>