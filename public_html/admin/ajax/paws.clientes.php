<?php

function getListaCte() {
	global $bd, $usr;
	$jsres = "";
	$counter = 0;
	$clientes = $bd->query_arr("select a.cliente_id as cid, a.nombre as nom, count(b.usuario_id) as usr from clientes a left join usuarios b using(cliente_id) group by a.cliente_id order by a.nombre");
	foreach ($clientes as $cliente) {
		$jsres.="aCte[$counter] = new Array(\"".$cliente["cid"]."\",\"".prepara($cliente["nom"])."\",\"".prepara($cliente["usr"])."\");";
		$counter++;
	}
	return $jsres;
}

function getDatosCte($cte) {
	global $bd, $usr;
	$jsres = "";
	$cliente = $bd->query_row("select nombre,estatus from clientes where cliente_id=".$cte);
	$jsres.="forma.f1nom.value = '".prepara($cliente["nombre"])."';";
	$jsres.="forma.f1est.selectedIndex = ".prepara($cliente["estatus"]).";";
	$jsres.="forma.f1id.value = ".$cte.";";
	return $jsres;
}

function getDatosUsr($usrid) {
	global $bd, $usr;
	$jsres = "";
	$usuario = $bd->query_row("select * from usuarios where usuario_id=".$usrid);
	$jsres.="forma.f2nom.value = '".prepara($usuario["nombre"])."';";
	$jsres.="forma.f2nick.value = '".prepara($usuario["nick"])."';";
	$jsres.="forma.f2pass.value = '".prepara($usuario["pass"])."';";
	$jsres.="forma.f2mail.value = '".prepara($usuario["email"])."';";
	$jsres.="forma.f2est.selectedIndex = ".prepara($usuario["estatus"]).";";
	$jsres.="forma.f2usrid.value = '".prepara($usuario["usuario_id"])."';";
	return $jsres;
}

/*OK*/
function getListaUsr($cte) {
	global $bd;
	$jsres = "";
	$nombrecte = $bd->query_uno("select nombre from clientes where cliente_id=".$cte);
	$jsres.= "aCte[0]=\"".$nombrecte."\";aCte[1] = \"".$cte."\";aCte[2] = new Array();";
	$counter = 0;
	$usuarios = $bd->query_arr("select usuario_id,nombre from usuarios where cliente_id=".$cte." order by usuario_id");
	foreach ($usuarios as $usuario) {
		$jsres.="aCte[2][$counter] = new Array(\"".$usuario["usuario_id"]."\",\"".prepara($usuario["nombre"])."\");";
		$counter++;
	}
	return $jsres;
}

switch ($_POST["accion"]) {
	case "listado":
		$jsres = "pawsengine=true;";
		$jsres.= getListaCte();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "datoscte":
		$jsres = "pawsengine=true;";
		$jsres.= getDatosCte($_POST["cte_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "guardacte":
		$jsres = "pawsengine=true;";
		$bd->agrega($qf->insert("clientes",$_POST));
		$jsres.= getListaCte();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "editacte":
		$jsres = "pawsengine=true;";
		$bd->actualiza($qf->update("clientes",$_POST,$_POST["cliente_id"]));
		$jsres.= getListaCte();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "borracte":
		$jsres = "pawsengine=true;";
		$bd->correr("delete from clientes where cliente_id=".$_POST["cliente_id"]);
		$bd->correr("delete from usuarios where cliente_id=".$_POST["cliente_id"]);
		$jsres.= getListaCte();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "procesacte":
		$jsres = "pawsengine=true;";
		$bd->correr("update clientes set estatus=2 where cliente_id=".$_POST["cliente_id"]." and usuario_id=".$usr->idu);
		$jsres.= getListaCte();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "listausr":
		$jsres = "pawsengine=true;";
		$jsres.= getListaUsr($_POST["cte_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "datosusr":
		$jsres = "pawsengine=true;";
		$jsres.= getDatosUsr($_POST["usr_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "guardausr":
		$jsres = "pawsengine=true;";
		$actual = $bd->query_uno("select usuario_id from usuarios where cliente_id=".$_POST["cliente_id"]." and nick='".$_POST["nick"]."'");
		if($actual) {
			$jsres.="alert('Ese login ya existe para este Cliente.\\nNo se ha guardado. Cambia el login e intentalo de nuevo');";
		} else {
			$bd->agrega($qf->insert("usuarios",$_POST));
			$jsres.= getListaUsr($_POST["cliente_id"]);
		}
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "editausr":
		$jsres = "pawsengine=true;";
		$actual = $bd->query_uno("select usuario_id from usuarios where cliente_id=".$_POST["cliente_id"]." and nick='".$_POST["nick"]."' and usuario_id!=".$_POST["usuario_id"]);
		if($actual) {
			$jsres.="alert('Ese login ya existe para este Cliente.\\nNo se ha guardado. Cambia el login e intentalo de nuevo');";
		} else {
			$bd->actualiza($qf->update("usuarios",$_POST,$_POST["usuario_id"]));
			$jsres.= getListaUsr($_POST["cliente_id"]);
		}
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "borrausr":
		$jsres = "pawsengine=true;";
		$cliente = $bd->query_uno("select cliente_id from usuarios where usuario_id=".$_POST["usuario_id"]);
		$bd->correr("delete from usuarios where usuario_id=".$_POST["usuario_id"]);
		$jsres.= getListaUsr($cliente);
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
