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
		case "clientes":
			$contenido["file"] = "principal.php";
			$contenido["subfile"] = "principal.clientes.php";
			$contenido["titulo"] = "Administrar Clientes";
			$pagina->pushScript(PATH_JS."principal.clientes.js");
		break;
		
		case "operadores":
			$contenido["file"] = "principal.php";
			$contenido["subfile"] = "principal.operadores.php";
			$contenido["titulo"] = "Candidatos en Proceso";
			$pagina->pushScript(PATH_JS."principal.operadores.js");
		
		break;
		
		case "reportes":
			$contenido["file"] = "principal.php";
			$contenido["subfile"] = "principal.reportes.php";
			$contenido["titulo"] = "Reportes";
			$pagina->pushScript(PATH_JS."dpjs.js");
			$pagina->pushScript(PATH_JS."principal.reportes.js");
			$pagina->pushEstilo(PATH_IF."dp.css",1);
		
		break;
		
		default:
			header("Location: /clientes");
			die();
		break;
	}
}
$pagina->setTitle("RH Global ADMIN - ".$contenido["titulo"]);
?>