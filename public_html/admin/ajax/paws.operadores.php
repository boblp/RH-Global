<?php

function getListaOper() {
	global $bd, $usr;
	$jsres = "";
	$counter = 0;
	$operadores = $bd->query_arr("select operador_id as cid, nombre as nom from operadores where operador_id!=1 order by nombre");
	foreach ($operadores as $operador) {
		$jsres.="aOper[$counter] = new Array(\"".$operador["cid"]."\",\"".prepara($operador["nom"])."\");";
		$counter++;
	}
	return $jsres;
}

function getDatosOper($oper) {
	global $bd, $usr;
	$jsres = "";
	$operador = $bd->query_row("select nombre,login,pass,email,nivel,estatus from operadores where operador_id=".$oper);
	$jsres.="forma.f1nom.value = '".prepara($operador["nombre"])."';";
	$jsres.="forma.f1nick.value = '".prepara($operador["login"])."';";
	$jsres.="forma.f1pass.value = '".prepara($operador["pass"])."';";
	$jsres.="forma.f1mail.value = '".prepara($operador["email"])."';";
	$jsres.="forma.f1p1.checked = ".(($operador["nivel"] & 1) ? "true" : "false").";";
	$jsres.="forma.f1p2.checked = ".(($operador["nivel"] & 2) ? "true" : "false").";";
	$jsres.="forma.f1p3.checked = ".(($operador["nivel"] & 4) ? "true" : "false").";";
	$jsres.="forma.f1est.selectedIndex = ".prepara($operador["estatus"]).";";
	$jsres.="forma.f1id.value = ".$oper.";";
	return $jsres;
}


switch ($_POST["accion"]) {
	case "listado":
		$jsres = "pawsengine=true;";
		$jsres.= getListaOper();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "datosoper":
		$jsres = "pawsengine=true;";
		$jsres.= getDatosOper($_POST["oper_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "guardaoper":
		$jsres = "pawsengine=true;";
		$existe = $bd->query_uno("select operador_id from operadores where login='".$_POST["login"]."'");
		if($existe) {
			$jsres.= "mError=true;";
		} else {
			$bd->agrega($qf->insert("operadores",$_POST));
			$jsres.= getListaOper();
		}
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "editaoper":
		$jsres = "pawsengine=true;";
		$existe = $bd->query_uno("select operador_id from operadores where login='".$_POST["login"]."' and operador_id!=".$_POST["operador_id"]);
		if($existe) {
			$jsres.= "mError=true;";
		} else {
			$bd->actualiza($qf->update("operadores",$_POST,$_POST["operador_id"]));
			$jsres.= getListaOper();
		}
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "borraoper":
		$jsres = "pawsengine=true;";
		$bd->correr("delete from operadores where operador_id=".$_POST["operador_id"]);
		$jsres.= getListaOper();
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
