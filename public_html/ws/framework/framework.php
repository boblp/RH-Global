<?php
function preparaUTF8($buffer) {
	return utf8_encode($buffer);
}
ob_start("preparaUTF8");
require ("constantes.php");
require ("class.bdmysql.php");
require ("class.getxml.php");
require ("class.queryfacil.php");
require ("extras.funciones.php");
// Ahora instanciamos las classes generales
$bd = new Bdmysql;
$qf = new Queryfacil($bd);
$xml = new getXML;
?>
