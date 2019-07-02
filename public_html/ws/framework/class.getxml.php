<?php
/* Clase creada por Manoloweb
   Abril de 2006
   Especial para leer XML.sendAndLoad desde Flash
*/
class getXML {
	//var $xmlSource = "samplerequest_a.xml";
	var $xmlSource = "php://input";
	var $OK = false;
	var $inXML;
	var $parser;
	var $inRequest = false;
	var $inVars = false;
	var $inQueries = false;
	var $inQuery = false;
	var $inDatos = false;
	var $queryIndex = 0;
	var $curNode;
	var $parsererr;
	var $request;
	
	function getXML() {
		$this->inXML = @file_get_contents($this->xmlSource,true);
		if ($this->inXML) {
			$this->OK = true;
			$this->inXML = utf8_decode($this->inXML);
		}
		$this->parser = xml_parser_create();
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "abre", "cierra");
		xml_set_character_data_handler($this->parser, "cdata");
	}
	
	function abre($parser,$tag,$atributos) {
		switch($tag) {
			case "REQUEST":
				$this->inRequest = true;
				$this->curNode = null;
				break;
			case "VARS":
				$this->inVars = true;
				$this->curNode = null;
				break;
			case "QUERIES":
				$this->inQueries = true;
				$this->curNode = null;
				break;
			case "QUERY":
				$this->inQuery = true;
				$this->curNode = null;
				break;
			case "DATOS":
				$this->inDatos = true;
				$this->curNode = null;
				break;
			default:
				$this->curNode = strtolower($tag);
		}
		
	}
	
	function cierra($parser,$tag) {
		switch($tag) {
			case "REQUEST":
				$this->inRequest = false;
				break;
			case "VARS":
				$this->inVars = false;
				break;
			case "QUERIES":
				$this->inQueries = false;
				break;
			case "QUERY":
				$this->inQuery = false;
				$this->queryIndex++;
				break;
			case "DATOS":
				$this->inDatos = false;
				break;
			default:
				if($this->inDatos) {
					$this->request["queries"][$this->queryIndex]["datos"][$this->curNode].= "";
				}
				$this->curNode = null;
		}
	}
/*	
	function cdata($parser,$cdata) {
		if($this->inDatos && $this->curNode) {
			$this->request["queries"][$this->queryIndex]["datos"][$this->curNode].= $cdata;
			return;
		}
		if($this->inQuery && $this->curNode) {
			$this->request["queries"][$this->queryIndex][$this->curNode].= $cdata;
			return;
		}
		if($this->inVars && $this->curNode) {
			$this->request["vars"][$this->curNode].= $cdata;
			return;
		}
		if($this->inRequest && $this->curNode) {
			$this->request["main"][$this->curNode].= $cdata;
			return;
		}
		
	}
*/	
	function cdata($parser,$cdata) {
		if($this->inDatos && $this->curNode) {
			$this->request["queries"][$this->queryIndex]["datos"][$this->curNode].= mysql_real_escape_string($cdata);
			return;
		}
		if($this->inQuery && $this->curNode) {
			$this->request["queries"][$this->queryIndex][$this->curNode].= mysql_real_escape_string($cdata);
			return;
		}
		if($this->inVars && $this->curNode) {
			$this->request["vars"][$this->curNode].= mysql_real_escape_string($cdata);
			return;
		}
		if($this->inRequest && $this->curNode) {
			$this->request["main"][$this->curNode].= mysql_real_escape_string($cdata);
			return;
		}
		
	}
	
	function procesar() {
		if($this->OK) {
			$res = xml_parse($this->parser, $this->inXML);
			$this->parsererr = xml_error_string(xml_get_error_code($this->parser));
			return $res;
		} else {
			$this->parsererr = "Peticion Incorrecta";
			return false;
		}
	}
}
?>