<?php 
require ("constantes.php");
require ("class.session.php");
require ("class.bdmysql.php");
require ("class.usuario.php");
require ("extras.funciones.php");
// Ahora instanciamos las classes generales
$sesion = new Session;
$bd = new Bdmysql;
$usr = new Usuario($bd,$sesion);

if($usr->idu==0) {
	die("<chart></chart>");
}

$vars = getvars($_GET["varchain"]);

ob_start();

switch($_GET["chart"]) {
	case "portipo":
	include("xml.portipo.php");
	break;
	case "porcta":
	$datosxml = $bd->query_arr("select a.nombre, a.usuario_id,sum(if(c.resultado=1,1,0)) as aptos, sum(if(c.resultado=2,1,0)) as aptoscr,sum(if(c.resultado=3,1,0)) as noaptos,sum(if(c.estatus&6,1,0)) as proceso,count(candidato_id) as total,if(c.usuario_id is null,'nulo','activo') as checa from usuarios a left join z2u b using(usuario_id) LEFT join candidatos c using(usuario_id) where b.zona_id=".$vars["z"]." and (date_format(c.tsfin,'%Y')='".$vars["y"]."' or c.candidato_id is null or c.estatus&6) group by usuario_id order by a.nombre");
	$series = array("<null />");
	$sap = array("<string>Aptos</string>");
	$sar = array("<string>Aptos CR</string>");
	$sna = array("<string>No Aptos</string>");
	$cuenta = 1;
	foreach($datosxml as $dx) {
		$series[$cuenta] = "<string>".prefijo($dx["nombre"])."</string>";
		$sap[$cuenta] = "<number>".$dx["aptos"]."</number>";
		$sar[$cuenta] = "<number>".$dx["aptoscr"]."</number>";
		$sna[$cuenta] = "<number>".$dx["noaptos"]."</number>";
		$cuenta++;
	}
	include("xml.porcta.php");
	break;
}

if($nodata) {
	ob_clean();
	include("xml.nodata.php");
}

$xml = "<chart>".ob_get_contents()."</chart>";
ob_end_clean();
header("Content-Type: text/xml; charset=UTF-8");
print(utf8_encode($xml));
?>