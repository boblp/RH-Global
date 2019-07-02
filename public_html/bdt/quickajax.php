<?php
session_start();
require("constants.php");
require("class.bd.php");
$bd = new BD(_USER_,_PWD_,_BD_,_HOST_);
$restxt = "error";

function cleanInput(&$ele,$key) {
	$ele = utf8_decode($ele);
}

function cleanOutput(&$ele,$key) {
	$ele = htmlentities($ele);
}

switch($_POST["action"]) {
	case "deluser":
	if(is_numeric($_POST["candid"])) {
		$bd->run("update bdt set statusgral=0 where folio=".$_POST["candid"]);
		$restxt = "bfolio_".$_POST["candid"];
	}
	break;
	case "duedate":
	if(is_numeric($_POST["days"])) {
		$restxt = $bd->update("update bdt set statusgral=0 where fechaalta<'".date("Y-m-d 00:00:00",strtotime("-".$_POST["days"]." day"))."'");
	}
	break;
	case "delsocio":
	if(is_numeric($_POST["socioid"])) {
		$bd->run("delete from bdtusers where id=".$_POST["socioid"]);
		$restxt = "ok";
	}
	break;
	case "sociosave":
	array_walk($_POST,"cleanInput");
	if(!is_numeric($_POST["socioid"]) || $socio = $bd->query_row("select * from bdtusers where id=".$_POST["socioid"])) {
		$_POST["id"] = $socio["id"];
		$query = ($_POST["tipo"]=="edit") ? $bd->quickupdate("bdtusers",$_POST,$socio["id"]) : $bd->quickinsert("bdtusers",$_POST);
		$bd->run($query);
		$restxt = "ok";
	}
	break;
	case "socioform":
	if(is_numeric($_POST["socioid"]) && $socio = $bd->query_row("select * from bdtusers where id=".$_POST["socioid"])) {
		array_walk($socio,"cleanOutput");
		$socio["tipo"] = "edit";
		$socio["inactivo"] = ($socio["statusgral"]==0) ? " selected=\"selected\"" : "" ;
	} else {
		$socio = array("tipo"=>"new");
	}
	include "socios.edit.form.php";
	$restxt = $frm_socio;
	break;
}
if($_POST["restype"]=="html") {
	$result = $restxt;
	header("Content-type: text/html");
	die($result);
} else {
	$result = "<result>".$restxt."</result>";
	header("Content-type: text/xml");
	die($result);
}
?>