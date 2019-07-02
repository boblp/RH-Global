<?php
/*
*************************************************
Clase para generar los elementos basicos de un
documento XHTML valido
Autor: Manuel Guerrero - www.convergensys.com
Licencia: GPL
*************************************************
*/


class Xhtml {
	var $_doctype;
	var $_lang;
	var $_encoding;
	var $_dtd;
	var $_enc;
	var $_roof;
	var $_floor;
	var $_onload;
	var $_title;

	// Constructor
	function Xhtml($titulo = "Sin Titulo") {
		$this->_doctype = "trans";
		$this->_lang = "es";
		$this->_dtd = array(
			"strict" => "<!DOCTYPE html \n     PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n     \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n",
			"trans"  => "<!DOCTYPE html \n     PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n     \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n",
			"frame"  => "<!DOCTYPE html \n     PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\"\n     \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n");
		$this->_enc = array(
			"en" => "UTF-8",
			"es" => "ISO-8859-1");
		$this->_roof = array("dtd"=>"","cuerpo"=>array(),"fin"=>"");
		$this->_floor = "</body>\n</html>";
		$this->_onload = "";
		$this->_title = $titulo;
	}


	/*
	************************************************
	Metodos publicos opcionales para generar ciertos
	elementos del encabezado del documento XHTML
	************************************************
	*/


	// Para cambiar el DOCTYPE. Default: transicional
	// Posibles valores: "trans", "strict", "frame"
	// EJEMPLO:
	// bool setDoctype("strict");
	function setDoctype ($dt) {
		$this->_doctype = (array_key_exists($dt,$this->_dtd)) ? $dt : "trans";
		return true;
	}

	// Para cambiar el titulo del documento. Default: Sin Titulo
	// NOTA: Este valor en teoria se pone desde el constructor al instanciar el objeto
	// Posibles valores: "trans", "strict", "frame"
	// EJEMPLO:
	// bool setDoctype("strict");
	function setTitle ($titulo) {
		$this->_title = $titulo;
		return true;
	}

	// Para cambiar el idioma. Default: español
	// Posibles valores: "es", "en"
	// EJEMPLO:
	// bool setLang("en");
	function setLang ($lang) {
		$this->_lang = (array_key_exists($lang,$this->_enc)) ? $lang : "es";
		return true;
	}

	// Para agregar un javascript que se ejecute al inico
	// NOTA: El script debe ser incluido como externo con pushScript();
	//       o bien auto-contenido, como "window.close()"
	// Valor: funcion de javascript que se correra al cargar la página
	// EJEMPLO:
	// bool setOnload("cierraVentana();");
	function setOnload($onload) {
		$this->_onload = " onload=\"".$onload."\"";
		return true;
	}

	// Para agregar meta tags
	// Tipo: 1=name, 2=http-equiv
	// EJEMPLO:
	// void pushMeta("author","Manuel Guerrero",1);    Meta name
	// void pushMeta("Content-Language","es-MX",2);    Meta http-equiv
	function pushMeta ($nombre,$valor,$tipo=1) {
		$tipos = array(1=>"name",2=>"http-equiv");
		$this->_roof["meta"][] = "<meta ".$tipos[$tipo]."=\"".$nombre."\" content=\"".$valor."\" />";
	}

	// Para agregar hojas de estilo externas
	// Media: 0=general, 1=All, 2=screen, 3=print, 4=handheld
	// EJEMPLO:
	// void pushEstilo("/estilos/base.css",1);    Media All
	// void pushEstilo("/estilos/print.css",3);    Media Print
	function pushEstilo ($hoja,$media = 0) {
		$medias = array(0=>"", 1=>" media=\"all\"",2=>" media=\"screen\"",3=>" media=\"print\"",4=>" media=\"handheld\"");
		$this->_roof["cuerpo"][] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$hoja."\"".$medias[$media]." />";
	}

	// Para agregar script externos
	// EJEMPLO:
	// void pushScript("/javascript/ventanas.js");
	function pushScript ($script) {
		$this->_roof["cuerpo"][] = "<script type=\"text/javascript\" src=\"".$script."\"></script>";
	}

	// Para agregar elementos adicionales al header
	// EJEMPLO:
	// void pushHeadline("/javascript/ventanas.js");
	function pushHeadline ($text) {
		$this->_roof["cuerpo"][] = $text;
	}

	// Para mostrar el encabezado del documento
	// Debe llamarse una vez que se han agregado todos los elementos necesarios
	// EJEMPLO:
	// string getPagetop();
	function getPagetop () {
		$this->_goTop();
		$this->_goFin();
		return $this->_roof["dtd"].implode("\n",$this->_roof["meta"])."\n".implode("\n",$this->_roof["cuerpo"]).$this->_roof["fin"];
	}

	// Para mostrar el pie del documento
	// EJEMPLO:
	// string getPagefoot();
	function getPagefoot () {
		return $this->_floor;
	}


	/*
	*******************
	Metodos Privados
	*******************
	*/

	// Privada
	function _goTop () {
		$this->_roof["dtd"] =$this->_dtd[$this->_doctype];
		$this->_roof["dtd"].="<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"".$this->_lang."\" xml:lang=\"".$this->_lang."\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$this->_enc[$this->_lang]."\" />\n";
		$this->_roof["dtd"].="<title>".$this->_title."</title>\n";
		@ob_end_flush();
	}

	// Privada
	function _goFin () {
		$this->_roof["fin"] = "\n</head>\n";
		$this->_roof["fin"].= "<body".$this->_onload.">\n";
	}
}
?>