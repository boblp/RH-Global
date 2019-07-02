<?php

function getListaCand() {
	global $bd, $usr;
	$jsres = "";
	$counter = 0;
	$candidatos = $bd->query_arr("select a.candidato_id as cid, a.nombre as nom, a.estatus as est, a.msgcte as mensaje, count(b.empleo_id) as emp from candidatos a left join empleos b using(candidato_id) where a.estatus & 1 and a.usuario_id=".$usr->idu." group by a.candidato_id");
	foreach ($candidatos as $candidato) {
		$jsres.="aCand[$counter] = new Array(\"".$candidato["cid"]."\",\"".prepara($candidato["nom"])."\",\"".prepara($candidato["emp"])."\",".prepara($candidato["est"]).",\"".prepara($candidato["mensaje"])."\");";
		$counter++;
	}
	return $jsres;
}

function getDatosCand($cand) {
	global $bd, $usr;
	$jsres = "";
	$candidato = $bd->query_row("select nombre,solpor,domicilio,ciudad,telefono,telefonorec,lugarnac,imss, date_format(fechanac,'%Y') as fna, date_format(fechanac,'%m') as fnm, date_format(fechanac,'%d') as fnd from candidatos where candidato_id=".$cand." and usuario_id=".$usr->idu);
	$jsres.="forma.f1nom.value = '".prepara($candidato["nombre"])."';";
	$jsres.="forma.f1sol.value = '".prepara($candidato["solpor"])."';";
	$jsres.="forma.f1dom.value = '".prepara($candidato["domicilio"])."';";
	$jsres.="forma.f1ciu.value = '".prepara($candidato["ciudad"])."';";
	$jsres.="forma.f1tel.value = '".prepara($candidato["telefono"])."';";
	$jsres.="forma.f1telr.value = '".prepara($candidato["telefonorec"])."';";
	$jsres.="forma.f1lnac.value = '".prepara($candidato["lugarnac"])."';";
	$jsres.="forma.f1fna.value = '".prepara($candidato["fna"])."';";
	$jsres.="forma.f1fnm.value = '".prepara($candidato["fnm"])."';";
	$jsres.="forma.f1fnd.value = '".prepara($candidato["fnd"])."';";
	$jsres.="forma.f1imss.value = '".prepara($candidato["imss"])."';";
	$jsres.="forma.f1id.value = ".$cand.";";
	return $jsres;
}

function getDatosEmp($emp) {
	global $bd, $usr;
	$jsres = "";
	$empleo = $bd->query_row("select * from empleos where empleo_id=".$emp);
	$jsres.="forma.f2emp.value = '".prepara($empleo["empresa"])."';";
	$jsres.="forma.f2lada.value = '".prepara($empleo["lada"])."';";
	$jsres.="forma.f2tel.value = '".prepara($empleo["telefonos"])."';";
	$jsres.="forma.f2jef.value = '".prepara($empleo["jefe"])."';";
	$jsres.="forma.f2pue.value = '".prepara($empleo["puesto"])."';";
	$jsres.="forma.f2fa.value = '".prepara($empleo["fechaing"])."';";
	$jsres.="forma.f2fb.value = '".prepara($empleo["fechabaja"])."';";
	$jsres.="forma.f2mot.value = '".prepara($empleo["motivobaja"])."';";
	$jsres.="forma.f2cid.value = '".prepara($empleo["candidato_id"])."';";
	$jsres.="forma.f2empid.value = '".prepara($empleo["empleo_id"])."';";
	return $jsres;
}

function getListaEmp($cand) {
	global $bd;
	$jsres = "";
	$nombrecand = $bd->query_uno("select nombre from candidatos where candidato_id=".$cand);
	$jsres.= "aCand[0]=\"".$nombrecand."\";aCand[1] = \"".$cand."\";aCand[2] = new Array();";
	$counter = 0;
	$empleos = $bd->query_arr("select empleo_id,empresa from empleos where candidato_id=".$cand." order by empleo_id");
	foreach ($empleos as $empleo) {
		$jsres.="aCand[2][$counter] = new Array(\"".$empleo["empleo_id"]."\",\"".prepara($empleo["empresa"])."\");";
		$counter++;
	}
	return $jsres;
}

switch ($_POST["accion"]) {
	case "listado":
		$jsres = "pawsengine=true;";
		$jsres.= getListaCand();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "datoscand":
		$jsres = "pawsengine=true;";
		$jsres.= getDatosCand($_POST["cand_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "guardacand":
		$jsres = "pawsengine=true;";
		$_POST["usuario_id"] = $usr->idu;
		$_POST["tsinicio"] = "00000000000000";
		$_POST["tsfin"] = "00000000000000";
		$_POST["estatus"] = "1";
		$bd->agrega($qf->insert("candidatos",$_POST));
		$jsres.= getListaCand();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "editacand":
		$jsres = "pawsengine=true;";
		$_POST["usuario_id"] = $usr->idu;
		$_POST["estatus"] = $bd->query_uno("select estatus from candidatos where candidato_id=".$_POST["candidato_id"]);
		$bd->actualiza($qf->update("candidatos",$_POST,$_POST["candidato_id"]));
		$jsres.= getListaCand();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "borracand":
		$jsres = "pawsengine=true;";
		$bd->correr("delete from candidatos where candidato_id=".$_POST["candidato_id"]." and usuario_id=".$usr->idu);
		$bd->correr("delete from empleos where candidato_id=".$_POST["candidato_id"]);
		$jsres.= getListaCand();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "procesacand":
		$jsres = "pawsengine=true;";
		$bd->correr("update candidatos set estatus=if(estatus=1,2,estatus-1), msgcte = if(msgcte is null,'',concat('Actualizado: ',now())), tsinicio=if(tsinicio<1,now(),tsinicio) where candidato_id=".$_POST["candidato_id"]." and usuario_id=".$usr->idu);
		$jsres.= getListaCand();
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "listaemp":
		$jsres = "pawsengine=true;";
		$jsres.= getListaEmp($_POST["cand_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "datosemp":
		$jsres = "pawsengine=true;";
		$jsres.= getDatosEmp($_POST["emp_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "guardaemp":
		$jsres = "pawsengine=true;";
		$bd->agrega($qf->insert("empleos",$_POST));
		$jsres.= getListaEmp($_POST["candidato_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "editaemp":
		$jsres = "pawsengine=true;";
		$bd->actualiza($qf->update("empleos",$_POST,$_POST["empleo_id"]));
		$jsres.= getListaEmp($_POST["candidato_id"]);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	case "borraemp":
		$jsres = "pawsengine=true;";
		$candidato = $bd->query_uno("select candidato_id from empleos where empleo_id=".$_POST["empleo_id"]);
		$bd->correr("delete from empleos where empleo_id=".$_POST["empleo_id"]);
		$jsres.= getListaEmp($candidato);
		$resultado=1;
		$mensaje="OK";
		$itemtipo="js";
		$itemencoding="b64";
		$item= base64_encode($jsres);
	break;
	default:
	pawsError("Accion Desconocida: ".$_REQUEST["accion"]);
	break;
}
?>
