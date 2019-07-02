<?php
require_once("lang.validate.php");
require_once("class.validate.php");
require_once("functions.validate.php");
$ppc = explode(".",$_POST["ppc"]);

// Tamper attempt check

// Not a regular sysyem call, ppc field not correct
if(count($ppc)!=2) {
	die("Error");
}

// Allowed function categories
$afc = array("bdt");
if(!in_array($ppc[0],$afc)) {
	die("Error");
}

$v = new Validate($_POST);

include ("post.processor.".$ppc[0].".php");

if($v->haserrors()) {
	$$errmsg = "<div class=\"error\"><h3>Error</h3><ul><li>";
	$$errmsg.= implode("</li><li>",$v->errorfields);
	$$errmsg.= "</li></ul></div>";
	$$fdata = $v->getdata();
} else {
	header("Location: http://".$_SERVER["SERVER_NAME"].$redirect);
	exit;
}

?>