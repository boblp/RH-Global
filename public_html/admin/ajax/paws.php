<?php
require ("constantes.php");
require ("class.session.php");
require ("class.bdmysql.php");
require ("class.queryfacil.php");
require ("class.usuario.php");
require ("extras.funciones.php");
$sesion = new Session;
$bd = new Bdmysql;
$qf = new Queryfacil($bd);
$usr = new Usuario($bd,$sesion);

function limpia(&$item, $key) {
   $item = utf8_decode($item);
   //$item = $item;
}

if(count($_POST)) {
	array_walk($_POST,"limpia");
}

//Inicializo las variables con error por si no se procesa un resultado
$resultado=0;
$mensaje="Resultado sin procesar";
$itemtipo="js";
$itemencoding="b64";
$item= base64_encode("alert('Error Paws: ".$mensaje."');");

function pawsError($msg) {
	global $resultado, $mensaje, $itemtipo, $itemencoding, $item;
	$resultado = 0;
	$mensaje = $msg;
	$itemtipo = "js";
	$itemencoding = "b64";
	$item = base64_encode("alert('Error Paws: ".$msg."');");
}


function prepara($string) {
	return preg_replace(array("/\r/","/\n/","/'/","/\"/"),array("","\\n","\\'","\\\\\""),$string);
}

/* LA SIGUIENTE SECCION DEBE PROCESAR EL INPUT Y REGRESAR 5 VARIABLES:
		-$resultado (0=error ó 1=OK)
		-$mensaje (Mensaje de Error u "OK")
		-$itemtipo ("html" ó "js")
		-$itemencoding ("b64" ó "raw")
		-$item (Contenido real de la respuesta)
*/

// Parse general del resultado. Debe venir un "servicio"[GET] (sección) y una "accion"[POST] (metodo)
// que ayudarán a decidir que archivo y que función procesarán el resultado

// Si no esta logueado, no le damos nada
if ($usr->idu==0) {
	pawsError("Usuario desconocido, debe ingresar a la aplicacion");
} else {
	switch ($_GET["servicio"]) {
		case "clientes":
			include("paws.clientes.php");
		break;
		case "operadores":
			include("paws.operadores.php");
		break;
		case "reportes":
			include("paws.reportes.php");
		break;
		default:
		pawsError("Servicio Desconocido: ".$_GET["servicio"]);
		break;
	}
}

// Solo de refuerzo, no puede salir una respuesta vacía
if ($mensaje=="" || $itemtipo=="" || $itemencoding=="" || $item=="") {
	pawsError("Respuesta incompleta, error en el servidor. Avisar al administrador");
}

// Salida final
include("xmltemplate.php");
header("Content-type: text/xml");
print($xml);
exit();
?>