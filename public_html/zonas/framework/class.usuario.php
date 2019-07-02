<?php
class Usuario {
	var $bd;
	var $sesion;
	var $cookie;
	var $redir;
	var $idu;
	var $nom;
	var $error;
	var $debug;
	
	
	// Constructor
	function Usuario(&$bd,&$sesion) {
		$this->bd = &$bd;
		$this->sesion = &$sesion;
		$this->redir = "http://".$_SERVER["HTTP_HOST"]."/";
		if (isset($_GET["salir"])) {
			$this->salir();
		}
		$this->idu = 0;
		$this->nom = "";
		$this->error = false;
		$this->login();
	}
	
	
	function login() {
		if ($this->sesion->get(SS_IDENT)) {
			$this->confirma_sesion();
			return;
		}
		
        if (!isset($_POST["loginusr"]) || !isset($_POST["loginpwd"])) {
            return;
        }
        
        $pwd = $this->bd->escapar($_POST["loginpwd"]);
        $usr = $this->bd->escapar($_POST["loginusr"]);
        $query = "select nombre as nom, supervisor_id as idu from supervisores where nick='".$_POST["loginusr"]."' and pass='".$_POST["loginpwd"]."'";
        if ($userid = $this->bd->query_row($query)) {
			$this->idu = $userid["idu"];
			$this->nom = $userid["nom"];
			$this->graba_sesion($this->idu,$this->nom);
			$this->goredir();
			return;
		} else {
        $this->debug=$query;
			$this->error = true;
			return;
		}
        
	}
	
	function graba_sesion($id,$nom) {
		$cad = $id."|".md5(session_id().$id.HASH_STRING)."|".base64_encode($nom);
		$this->sesion->set(SS_IDENT,$cad);
		return;
	}
	
	function confirma_sesion() {
		$partes = explode("|",$this->sesion->get(SS_IDENT));
		if ($partes[0]."|".md5(session_id().$partes[0].HASH_STRING)."|".$partes[2] == $this->sesion->get(SS_IDENT)) {
			$this->idu = $partes[0];
			$this->nom = base64_decode($partes[2]);
		} else {
			$this->salir();
		}
		return;
	}
	
	
	function salir() {
		$this->sesion->del(SS_IDENT);
		header ("Location: ".$this->redir);
		exit;
	}
	
	function goredir() {
		header ("Location: ".$this->redir);
		exit;
	}
	
}
?>