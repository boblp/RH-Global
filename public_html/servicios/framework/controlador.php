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
	$pagina->pushScript(PATH_JS."core_crypt.js");
	$pagina->pushScript(PATH_JS."main.js");
	$pagina->pushScript(PATH_JS."ajax.js");
	switch ($_GET["base"]) {
		case "nuevos":
			$contenido["file"] = "principal.php";
			$contenido["subfile"] = "principal.nuevos.php";
			$contenido["titulo"] = "Administrar Nuevos Candidatos";
			$pagina->pushScript(PATH_JS."principal.nuevos.js");
		break;
		
		case "proceso":
			header("Refresh: 60;URL=/proceso");
			$contenido["file"] = "principal.php";
			$contenido["subfile"] = "principal.proceso.php";
			$contenido["titulo"] = "Candidatos en Proceso";
		
		break;
		
		case "terminados":
			$contenido["file"] = "principal.php";
			$contenido["subfile"] = "principal.terminados.php";
			$contenido["titulo"] = "Proceso Terminado";
		
		break;
		case "familiares":
			$contenido["file"] = "familiares.php";
			$contenido["subfile"] = "familiares.principal.php";
			$contenido["titulo"] = "Familiares";
		
		break;		
		
		default:
			header("Location: /nuevos");
			die();
		break;
	}
}
$pagina->setTitle("RH Global - ".$contenido["titulo"]);
?>