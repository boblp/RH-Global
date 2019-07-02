<?php
class Usuario {
	var $bd;
	var $sesion;
	var $cookie;
	var $redir;
	var $idu;
	var $cte;
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
		$this->cte = 0;
		$this->nom = "";
		$this->error = false;
		$this->login();
	}
	
	
	function login() {
		if ($this->sesion->get(SS_IDENT)) {
			$this->confirma_sesion();
			return;
		}
		
        if (!isset($_POST["loginusr"]) || !isset($_POST["loginpwd"]) || !isset($_POST["logincte"])) {
            return;
        }
        
        $cte = $this->bd->escapar($_POST["logincte"]);
        $pwd = $this->bd->escapar($_POST["loginpwd"]);
        $usr = $this->bd->escapar($_POST["loginusr"]);
        $query = "select a.nombre as nom, a.usuario_id as idu, b.nombre as cte from usuarios a left join clientes b using(cliente_id) where a.nick='".$_POST["loginusr"]."' and b.nombre='".$_POST["logincte"]."' and a.pass='".$_POST["loginpwd"]."' and a.estatus=1 and b.estatus=1";
        if ($userid = $this->bd->query_row($query)) {
			$this->idu = $userid["idu"];
			$this->cte = $userid["cte"];
			$this->nom = $userid["nom"];
			$this->graba_sesion($this->idu,$this->cte,$this->nom);
			setcookie("cte",$this->cte,time()+60*60*24*7);
			$this->goredir();
			return;
		} else {
        $this->debug=$query;
			$this->error = true;
			return;
		}
        
	}
	
	function graba_sesion($id,$cte,$nom) {
		$cad = $id."|".md5(session_id().$id.HASH_STRING)."|".$cte."|".base64_encode($nom);
		$this->sesion->set(SS_IDENT,$cad);
		return;
	}
	
	function confirma_sesion() {
		$partes = explode("|",$this->sesion->get(SS_IDENT));
		if ($partes[0]."|".md5(session_id().$partes[0].HASH_STRING)."|".$partes[2]."|".$partes[3] == $this->sesion->get(SS_IDENT)) {
			$this->idu = $partes[0];
			$this->cte = $partes[2];
			$this->nom = base64_decode($partes[3]);
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