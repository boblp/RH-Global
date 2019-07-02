<?php
// Construcción de los elementos básicos de la página
$pagina->pushMeta("Content-Language","es",2);
$pagina->pushMeta("Generator","TextPad");
$pagina->pushMeta("Author","Manuel Guerrero");
$pagina->pushMeta("Description","RH Global - Sistema de servicios en linea");
$pagina->pushHeadline("<!--[if lte IE 5.0]><script type=\"text/javascript\">esIE50 = true;</script><![endif]-->");
if($usr->idu==0) {
  $contenido["file"] = "login.php";
  $contenido["titulo"] = "Ingresar al Sistema";
  $pagina->pushEstilo(PATH_IF."login.css",1);
  $pagina->pushScript(PATH_JS."testajax.js");
} else {
  $pagina->pushEstilo(PATH_IF."principal.css",1);
  $pagina->pushScript(PATH_JS."main.js");
  switch ($_GET["base"]) {
    case "resumen":
      $mmyr = $bd->query_row("select MAX(year(tsfin)) as maxyr, MIN(year(tsfin)) as minyr from candidatos where estatus&16");
      $usyr = (is_numeric($_GET["extra"]) && $_GET["extra"]<= $mmyr["maxyr"] && $_GET["extra"]>= $mmyr["minyr"]) ? $_GET["extra"] : date("Y");
      $contenido["file"] = "principal.php";
      $contenido["subfile"] = "principal.resumen.php";
      $contenido["titulo"] = "Resumen de Operaciones";
    break;
    
    case "reportes":
      $contenido["file"] = "principal.php";
      $contenido["subfile"] = "principal.reportes.php";
      $contenido["titulo"] = "Reportes";
      $pagina->pushScript(PATH_JS."dpjs.js");
      $pagina->pushEstilo(PATH_IF."dp.css",1);
    break;
    
    default:
      header("Location: /resumen");
      die();
    break;
  }
}
$pagina->setTitle("RH Global - ".$contenido["titulo"]);
?>