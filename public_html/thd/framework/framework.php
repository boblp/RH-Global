<?php
session_start();

// Primero requerimos los archivos necesarios
require ("constantes.php");
require ("class.bdmysql.php");
require ("extras.funciones.php");
require ("bdt.functions.php");
// Ahora instanciamos las classes generales
$bd = new Bdmysql;

if (isset($_POST["srchword"])) {
	array_walk($_POST,"clean");
	$_SESSION["bdts"] = $_POST;
	header("Location: ".$_SERVER["REQUEST_URI"]);
}

if (isset($_POST["user"])) {
	$_P["usr"] = (get_magic_quotes_gpc()) ? $_POST["user"] : addslashes($_POST["user"]);
	$_P["pwd"] = (get_magic_quotes_gpc()) ? $_POST["pwd"] : addslashes($_POST["pwd"]);
	$usrdata = $bd->query_row("select * from bdtusers where usuario='".$_P["usr"]."' and pass='".$_P["pwd"]."' and statusgral=1");
	if($usrdata) {
		$_SESSION["bdtlogin"] = $usrdata;
		header("Location: ".$_SERVER["REQUEST_URI"]);
	} else {
		unset($_SESSION["bdtlogin"]);
		$errorloginbdt = true;
	}
}


?>