<?php
require ("constantes.php");
require ("class.session.php");
require ("class.bdmysql.php");
require ("class.usuario.php");
require ("extras.funciones.php");
require ("fpdf.php");
require ("class.reporteador.php");

$sesion = new Session;
$bd = new Bdmysql;
$usr = new Usuario($bd,$sesion);
$override = false;
// SUSTITUIR LO SIGUIENTE POR LA VERIFICACION DE USUARIO Y CARGA DE VARIABLES VIA GET
switch($_GET["tipo"]) {
  case "secure":
  $usuario = $usr->idu;
  $folio = $_GET["candid"];
  break;
  case "admin":
  $override = true;
  $usuario = 0;
  $folio = $_GET["candid"];
  break;
  default:
  $usuario = 0;
  $folio = 0;
  break;
}
// INICIA LA CONSTRUCCION DEL REPORTE EN PDF

if($bd->contar("candidatos","usuario_id=".$usuario." and candidato_id=".$folio." and estatus=16") || ($bd->contar("candidatos","candidato_id=".$folio) && $override)) {
  $candidato = $bd->query_row("select * from candidatos where candidato_id=".$folio);
  $solicitante = $bd->query_row("select a.nombre as usuario, b.nombre as empresa from usuarios a left join clientes b using(cliente_id) where a.usuario_id=".$candidato["usuario_id"]);
  
  // Top
  $candidato["resultado"] = ($candidato["resultado"]>0) ? $candidato["resultado"] : 0;
  $r->rMain($solicitante["empresa"],$solicitante["usuario"],$candidato["solpor"],$folio,$candidato["nombre"],$candidato["resultado"],$candidato["comentariosgral"],date_es("j \de F \de Y"));
  
  // Datos Generales
  $tels = ($candidato["telefono"]!="" && $candidato["telefonorec"]!="") ? $candidato["telefono"]." (Recados: ".$candidato["telefonorec"].")" : ($candidato["telefono"]!="") ? $candidato["telefono"] : "(Recados) ".$candidato["telefonorec"];
  $domi = ($candidato["domicilio"]!="" && $candidato["ciudad"]!="") ? $candidato["domicilio"].", ".$candidato["ciudad"] : $candidato["domicilio"].$candidato["ciudad"];
  //$fnac = date_es("F j, Y",$candidato["fechanac"]);
  $fnac = $candidato["fechanac"];
  $r->rDatos($candidato["nombre"],$domi,$tels,$candidato["lugarnac"],$fnac);
  
  //Lista IMSS
  $limss = str_replace("\r","",(trim($candidato["listaimss"])));
  if (preg_match("/^([^|]+\|[0-9\/]+\|([0-9\/]+|(actual *))\n)*([^|]+\|[0-9\/]+\|([0-9\/]+|(actual *)))$/iU",$limss,$res)) {
    $r->rLista($limss,$candidato["imss"]);
  }
  
  // Judicial
  if($candidato["comentariosjud"]) {
    $r->rJud($candidato["comentariosjud"]);
  }
  
  // Sindicatos
  if($candidato["comentariossind"]) {
    $r->rSind($candidato["comentariossind"]);
  }
  
  // Empleos
  $listaemp = $bd->query_arr("select * from empleos where candidato_id=".$folio." and orden>0 order by orden, empleo_id");
  if(count($listaemp)) {
    $r->IniciaEmp($folio);
    $r->rEmp($listaemp,$folio);
  }
  
  // Finalmente damos la salida
  $nfile = strtolower($candidato["nombre"]);
  $nfile = str_replace(array("á","é","í","ó","ú","ñ"," ","Á","É","Í","Ó","Ú","Ñ","-"),array("a","e","i","o","u","n","_","A","E","I","O","U","N","_"),$nfile);
  $nfile = preg_replace("/[^a-z_0-9]/","",$nfile);
  $r->Output($nfile.".pdf","D");
} else {
  die("<b>ERROR:</b> <br>Por razones de seguridad solo el usuario que solicitó la investigación puede descargar el archivo con el resultado. Y solo puede hacerlo mientras está navegando con su usuario y password en el sistema RH Global.<br><br>El servidor ha rechazado la solicitud de reporte.<br />Verifica que el candidato solicitado tenga estatus de terminado y haya sido solicitado desde tu cuenta, además asegúrate de que que hayas ingresado al sistema con tu usuario y password.<br>");
}
?>