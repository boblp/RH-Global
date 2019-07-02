<html>
<head>
  <title>RH Global Report Viewer</title>
  <link rel="stylesheet" type="text/css" href="/interfase/rv.css" media="all" />
  <script type="text/javascript" src="/js/rv.js"></script>
</head>
<body>
<?php
require ("constantes.php");
require ("class.bdmysql.php");
$bd = new Bdmysql;
$getrep = $bd->query_uno("select a.candidato_id from candidatos a left join z2u b using(usuario_id) left join s2z c using(zona_id) where c.supervisor_id=".$_GET["supid"]." and candidato_id=".$_GET["candid"]." and estatus=16");
if(!$getrep || (($_GET["candid"]*$_GET["supid"])+1024)!=$_GET["verifid"]) {
  echo "<p class=\"errreq\">El candidato solicitado no está disponible para descarga, esto puede deberse a que aún no está terminado, a que no corresponde a tu zona ó a que la liga que usaste es inválida</p>";
} else {
?>
<input type="button" id="btngo" value="Descargar Reporte" onclick="getreport(<?php echo $getrep; ?>)" />
<?php
}
?>
</body>
</html>