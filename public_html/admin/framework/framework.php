<?php
// Prevenimos la salida al navegador
// (Se "suelta" con la llamada a
// el metodo getPageTop de XHTML
ob_start();
// Primero requerimos los archivos necesarios
require ("constantes.php");
require ("class.session.php");
require ("class.bdmysql.php");
require ("class.usuario.php");
require ("class.xhtml.php");
require ("extras.funciones.php");
// Ahora instanciamos las classes generales
$sesion = new Session;
$bd = new Bdmysql;
$usr = new Usuario($bd,$sesion);
$pagina = new Xhtml;
?>
