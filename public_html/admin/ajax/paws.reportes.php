<?php

function getListaOper() {
	global $bd, $usr;
	$jsres = "";
	$counter = 0;
	$operadores = $bd->query_arr("select operador_id as cid, nombre as nom from operadores where operador_id!=1 order by nombre");
	foreach ($operadores as $operador) {
		$jsres.="aOper[$counter] = new Array(\"".$operador["cid"]."\",\"".prepara(ucwords(strtolower($operador["nom"])))."\");";
		$counter++;
	}
	return $jsres;
}

switch ($_POST["accion"]) {
	case "operadores":
		$jsres = "pawsengine=true;";
		$jsres.= getListaOper();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "cambiaoper":
		$jsres = "pawsengine=true;";
		$bd->actualiza("update candidatos set empleos_op=".$_POST["operador_id"]." where candidato_id=".$_POST["candidato_id"]);
		$opnombre = $bd->query_uno("select nombre from operadores where operador_id=".$_POST["operador_id"]);
		$jsres = "doBack(".$_POST["operador_id"].",'".prepara(ucwords(strtolower($opnombre)))."');";
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	default:
	pawsError("Accion Desconocida: ".$_POST["accion"]);
	break;
}
?>
