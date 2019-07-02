<?php
if(!isset($_GET["base"])) {
	header("Location: /nuevo");
	exit;
}

$contentfile = array();
$extrahead = array();
$extrahead[] = "<script type=\"text/javascript\" src=\"".PATH_JS."jq.if.js\"></script>";
$extrahead[] = "<script type=\"text/javascript\" src=\"".PATH_JS."jq.ts.js\"></script>";
$extrahead[] = "<script type=\"text/javascript\" src=\"".PATH_JS."jq.dc.js\"></script>";
switch ($_GET["base"]) {
	case "nuevo":
	$extrahead[] = "<script type=\"text/javascript\" src=\"".PATH_JS."nuevo.js\"></script>";
	$contentfile[] = "nuevo.main.php";
	break;
	case "lista":
	$extrahead[] = "<script type=\"text/javascript\" src=\"".PATH_JS."lista.js\"></script>";
	$contentfile[] = "lista.main.php";
	break;
	case "usuarios":
	$extrahead[] = "<script type=\"text/javascript\" src=\"".PATH_JS."socios.js\"></script>";
	$contentfile[] = "socios.main.php";
	break;
	default:
	break;
}
?>