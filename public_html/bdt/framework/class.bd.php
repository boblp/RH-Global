<?php
/* Revised Database Class
   Written by Manuel Guerrero [Manoloweb] - May 2006
*/

class BD {
	var $conn;
	
	function BD($usr,$pwd,$db,$hst) {
		$this->conn = @mysql_connect($hst,$usr,$pwd);
		$diemsg = (_DEBUG_) ? "Error DB :".mysql_error() : "Database Error";
		@mysql_select_db($db,$this->conn) or die ($diemsg." [".$db."]");
	}
	
	function query_obj($query) {
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query,$this->conn)) {
			$rs = &new _rs($qry);
			return $rs;
		} else {
			$rs = &new _rs($qry);
			return $rs;
		}
	}
	
	function query_arr($query) {
		$rs = array();
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query,$this->conn)) {
			while ($fila = mysql_fetch_assoc($qry)) {
				$rs[] = $fila;
			}
			@mysql_free_result($qry);
		}
		return $rs;
	}
	
	function query_col($query) {
		$rs = array();
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query,$this->conn)) {
			while ($fila = mysql_fetch_row($qry)) {
				$rs[] = $fila[0];
			}
			@mysql_free_result($qry);
		}
		return $rs;
	}

	function query_row($query) {
		$rs = array();
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query,$this->conn)) {
			if ($fila = @mysql_fetch_assoc($qry)) {
				$rs = $fila;
			}
			@mysql_free_result($qry);
		}
		return $rs;
	}
	
	function query_one($query) {
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query,$this->conn)) {
			if(@mysql_num_rows($qry)) {
				return mysql_result($qry,0);
			}
		}
		return false;
	}
	
	function rowcount($tabla,$criterio=false) {
		$rs = 0;
		$query = "SELECT count(*) FROM ".$tabla;
		$query.= ($criterio) ? " where ".$criterio : "";
		if ($qry = @mysql_query($query,$this->conn)) {
			$rs = mysql_result($qry,0);
			@mysql_free_result($qry);
		}
		return (int) $rs;
	}
	
	function insert($query) {
		if (stristr($query,"insert") && $qry = @mysql_query($query,$this->conn)) {
			if ($insid = @mysql_insert_id()) {
				return $insid;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	function update($query) {
		if (stristr($query,"update") && $qry = @mysql_query($query,$this->conn)) {
			if ($cuantos = @mysql_affected_rows($this->conn)) {
				return $cuantos;
			} else {
				return 0;
			}
		} else {
			return false;
		}
	}
	
	function run($query) {
		$resid = @mysql_query($query,$this->conn);
		return $resid;
	}
	
	// Quick Query Constructor

	function _getfields($table) {
		$tabdef = $this->query_arr("show columns from ".$table);
		$res = array("fields"=>array());
		if ($tabdef) {
			$pattern = "/^(int|tinyint|float|bigint)/";
			foreach ($tabdef as $td) {
				if ($td["Key"]=="PRI" && $td["Extra"]=="auto_increment") {
					$res["key"] = $td["Field"];
				} else {
					if (preg_match($pattern,$td["Type"])) {
						$type = "n";
					} else {
						$type = "s";
					}
					$null = ($td["Null"]=="YES");
					$res["fields"][$td["Field"]]=array("null"=>$null,"type"=>$type);
				}
			}
		}
		return $res;
	}

	function _getpairs($table,$data) {
		$fields = $this->_getfields($table);
		$res = array();
		foreach ($fields["fields"] as $ck => $cv) {
			if(array_key_exists($ck,$data)) {
				$res["fields"][$ck] = ($cv["type"]=="n") ? ((is_numeric($data[$ck])) ? $data[$ck] : 0 ) : "'". $data[$ck]."'";
			}
		}
		$res["key"] = $fields["key"];
		return $res;
	}
	
	function quickupdate($table,$data,$id) {
		$pairs = $this->_getpairs($table,$data);
		$fields = array_keys($pairs["fields"]);
		$values = array_values($pairs["fields"]);
		$count = count($fields);
		if ($count) {
			$res = "update ".$table." ";
			$res.= "set ";
			$sets = array();
				for ($i=0;$i<$count;$i++) {
					$sets[]=$fields[$i]."=".$values[$i];
				}
			$res.=implode(",",$sets);
			$res.= " where ".$pairs["key"]."=".$id;
			return $res;
		} else {
			return false;
		}
	}
	
	function quickinsert($table,$data) {
		$pairs = $this->_getpairs($table,$data);
		$fields = array_keys($pairs["fields"]);
		$values = array_values($pairs["fields"]);
		$count = count($fields);
		if ($count) {
			$res = "insert into ".$table." ";
			$res.="(".implode(",",$fields).") values ";
			$res.="(".implode(",",$values).")";
			return $res;
		} else {
			return false;
		}
	}

}

class _rs {
	var $recordset;
	function _rs(&$qry) {
		$this->recordset = &$qry;
	}
	
	function fetch() {
		if ($fila = @mysql_fetch_assoc($this->recordset)) {
			return $fila;
		} else {
			@mysql_free_result($this->recordset);
			return false;
		}
	}
}

?>