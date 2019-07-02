<?php
if(!isset($_GET["q"]) || !isset($_GET["f"])) {
	die("Acceso Prohibido");
}
require ("constantes.php");
require ("class.bdmysql.php");
$bd = new Bdmysql;
$folio = $_GET["f"];
switch ($_GET["q"]) {
	case "r":
	$existe = $bd->contar("candidatos","candidato_id=".$folio." and estatus=16");
	if(!$existe) {
		die("El candidato no se encuentra en estatus de terminado");
	} else {
		$bd->correr("update candidatos set estatus=4 where candidato_id=".$folio);
		die("Candidato revivido");
	}
	break;
	case "u":
	$existe = $bd->contar("candidatos","candidato_id=".$folio." and estatus=16");
	if(!$existe) {
		die("El candidato no se encuentra en estatus de terminado");
	} else {
		$bd->correr("update candidatos set estatus=2 where candidato_id=".$folio);
		die("Candidato revivido");
	}
	break;
	case "d":
	$existe = $bd->contar("candidatos","candidato_id=".$folio);
	if(!$existe) {
		die("El candidato no existe");
	} else {
		$datoscand = $bd->query_row("select * from candidatos where candidato_id=".$folio);
		foreach($datoscand as $dck => $dcv) {
			$dcv = ($dcv) ? $dcv : "[ VACIO ]";
			if($dck!="comentariosimss" && $dck!="notas_op" && $dck!="empleos_op") {
				echo "<p><strong>".$dck."</strong><br />".nl2br($dcv)."<hr /></p>";
			}
		}
		die("======== FIN DEL REPORTE =========");
	}
	break;
}
?>