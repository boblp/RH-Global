<?php
require ("constantes.php");
require ("class.bdmysql.php");
require ("extras.funciones.php");
require ("fpdf.php");
require ("class.edc.php");

$bd = new Bdmysql;
$hoy = date("Ymd",mktime(0,0,0));
$limitedias = ($_GET["tipo"]=="a") ? false : false ;
$fechas = checkdates($_GET["fini"],$_GET["ffin"],$limitedias);
$error = false;

switch ($_GET["tipo"]) {
  case "s":
  $datos_sup = $bd->query_row("select supervisor_id, nombre from supervisores where supervisor_id=".$_GET["tid"]);
  $comp_string = str_rot13(md5($datos_sup["supervisor_id"].$hoy.$datos_sup["nombre"]));
  if(($comp_string == $_GET["verif"] && $fechas) || $fechas) {
    $listactas = $bd->query_col("select a.usuario_id from usuarios a left join z2u b USING (usuario_id) LEFT join s2z c using (zona_id) where c.supervisor_id=".$_GET["tid"]);
    $ctas = ($listactas) ? implode(",",$listactas) : "0";
    $query = "select a.nombre as cta, b.nombre as cand, b.solpor, b.tsfin, b.resultado FROM usuarios a left join candidatos b using(usuario_id) where b.estatus=16 and a.usuario_id in(".$ctas.") and tsfin<='".$fechas["f"]."' and tsfin>='".$fechas["i"]."' order by cta, cand";
    $datos["total"] = $bd->query_uno("select COUNT(b.candidato_id) FROM usuarios a left join candidatos b using(usuario_id) where b.estatus=16 and a.usuario_id in(".$ctas.") and tsfin<='".$fechas["f"]."' and tsfin>='".$fechas["i"]."'");
    $datos["cuenta"] = $datos_sup["nombre"];
  } else {
    $error = true;
  }
  break;
  
  case "a":
  $datos_cte = $bd->query_row("select cliente_id, nombre from clientes where cliente_id=".$_GET["elemid"]);
  $comp_string = str_rot13(md5($_GET["tid"].$hoy));
  if(($comp_string == $_GET["verif"] && $fechas) || $fechas) {
    $extrawhere = ($_GET["fff"]!="") ? " and notas_op is null" : "";
    $query = "select a.nombre as cta, b.nombre as cand, b.solpor, b.tsfin, b.resultado FROM usuarios a left join candidatos b using(usuario_id) where b.estatus=16 and a.cliente_id=".$datos_cte["cliente_id"]." and tsfin<='".$fechas["f"]."' and tsfin>='".$fechas["i"]."' ".$extrawhere." order by cta, cand";
    $datos["total"] = $bd->query_uno("select COUNT(b.candidato_id) FROM usuarios a left join candidatos b using(usuario_id) where b.estatus=16 and a.cliente_id=".$datos_cte["cliente_id"]." and tsfin<='".$fechas["f"]."' and tsfin>='".$fechas["i"]."'".$extrawhere."");
    $datos["cuenta"] = $datos_cte["nombre"];
  } else {
    $error = true;
  }
  break;
  default:
  $error = true;
}
if($error) {
  die("Error procesando estado de cuenta. Verifica tus datos e inténtalo de nuevo.");
} else {
  $e->rMain($datos["cuenta"],date_es("j \de F \de Y"),date_es("j \de F \de Y",strtotime($fechas["i"])),date_es("j \de F \de Y",strtotime($fechas["f"])),$datos["total"]);
  if($datos["total"]>0) {
    $resultados = array(1=>"Apto",2=>"Apto CR",3=>"No Apto");
    $registros = $bd->query_obj($query);
    $e->Fila("headrow","Cuenta","Solicitó","Candidato","Procesado","Resultado");
    $datos = array();
    $loopita = "noneloopedyet";
    while($reg = $registros->fetch("assoc")) {
      $nomparts = explode(" ",$reg["cta"]);
      if($nomparts[0]!=$loopita) {
        $loopita = $nomparts[0];
        $datos[$nomparts[0]] = array("cta"=>$reg["cta"],"cant"=>0);
      }
      $datos[$loopita]["cant"]+=1;
      $tipofila = ($tipofila=="even") ? "odd" : "even";
      $e->Fila($tipofila,$nomparts[0],$reg["solpor"],$reg["cand"],date_es("d-M-y",strtotime($reg["tsfin"])),$resultados[$reg["resultado"]]);
    }
    $e->Resumen($datos);
  }
  if($_GET["fff"]=="-u") {
    $bd->correr("update candidatos set notas_op='".date("Y-m-d H:i:s")."' where tsfin<='".$fechas["f"]."' and tsfin>='".$fechas["i"]."' and usuario_id in(select usuario_id from usuarios where cliente_id=".$datos_cte["cliente_id"].")");
  }
  $e->Output($_GET["fini"]."a".$_GET["ffin"].".pdf","D");
}
?>