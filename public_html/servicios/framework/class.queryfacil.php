<?php
class Queryfacil {
	var $bd;
	var $datos;
	function Queryfacil(&$bd) {
		$this->bd = &$bd;
		$this->datos = &$_POST;
	}

	// Extrae los campos de la tabla y los pone en un array
	function campos($tabla) {
		$tabdef = $this->bd->query_arr("show columns from ".$tabla);
		$res = array("campos"=>array());
		if ($tabdef) {
			$patron = "/^(int|tinyint|float|bigint)/";
			foreach ($tabdef as $td) {
				if ($td["Key"]=="PRI" && $td["Extra"]=="auto_increment") {
					$res["key"] = $td["Field"];
				} else {
					if (preg_match($patron,$td["Type"])) {
						$tipo = "n";
					} else {
						$tipo = "s";
					}
					$null = ($td["Null"]=="YES");
					$res["campos"][$td["Field"]]=array("nulo"=>$null,"tipo"=>$tipo);
				}
			}
		}
		return $res;
	}

	function pares($tabla,$datos,$tipo=1) {
		$campos = $this->campos($tabla);
		$res = array();
		foreach ($campos["campos"] as $ck => $cv) {
			if($datos[$ck] || !($datos[$ck]!=="0")) {
				$res["campos"][$ck] = ($cv["tipo"]=="n") ? $datos[$ck] : "'". $datos[$ck]."'";
			} elseif ($cv["nulo"]) {
				if ($tipo==1) {$res["campos"][$ck] = "NULL";}
			} else {
				$res["campos"][$ck] = ($cv["tipo"]=="n") ? 0 : "''";
			}
		}
		$res["key"] = $campos["key"];
		return $res;
	}

	function update($tabla,$datos,$id) {
		$pares = $this->pares($tabla,$datos,0);
		$campos = array_keys($pares["campos"]);
		$valores = array_values($pares["campos"]);
		$cuantos = count($campos);
		if ($cuantos) {
			$res = "update ".$tabla." ";
			$res.= "set ";
			$sets = array();
				for ($i=0;$i<$cuantos;$i++) {
					$sets[]=$campos[$i]."=".$valores[$i];
				}
			$res.=implode(",",$sets);
			$res.= " where ".$pares["key"]."=".$id;
			return $res;
		} else {
			return false;
		}
	}

	function insert($tabla,$datos) {
		$pares = $this->pares($tabla,$datos);
		$campos = array_keys($pares["campos"]);
		$valores = array_values($pares["campos"]);
		$cuantos = count($campos);
		if ($cuantos) {
			$res = "insert into ".$tabla." ";
			$res.="(".implode(",",$campos).") values ";
			$res.="(".implode(",",$valores).")";
			return $res;
		} else {
			return false;
		}
	}
}
?>