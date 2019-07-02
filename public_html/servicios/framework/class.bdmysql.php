<?php
/*
*************************************************
Clase para manejar una base de datos Mysql
Autor: Manuel Guerrero - www.convergensys.com
Licencia: GPL

Requiere:
constantes.php requerido o incluido previo a 
instanciar la clase
*************************************************
*/

class Bdmysql {
	var $_cn;
	var $_res;
	
	// Constructor
	function Bdmysql() {
		$this->_cn = array("usr"=>_USR_,"pwd"=>_PWD_,"hst"=>_HST_,"bd"=>_BD_);
		$this->_res= @mysql_connect($this->_cn["hst"],$this->_cn["usr"],$this->_cn["pwd"]);
		@mysql_select_db($this->_cn["bd"],$this->_res) or die ("Error DB :".mysql_error());
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query y regresa un objeto (_rs) para hacer fetch
	// Ejemplo de uso:
	// $tal = $bd->query_obj("select * from tabla");
	// while ($fila = $tal->fetch()) {
	// 		//recorre la fila
	// }
	// =========================================================
	function query_obj($query) {
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query)) {
			$rs = &new _rs($qry);
			return $rs;
		} else {
			// Disparar error
			$rs = &new _rs($qry);
			return $rs;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query y regresa un array con todo el recordset
	// Ejemplo de uso:
	// $tal = $bd->query_arr("select * from tabla");
	// foreach ($tal as $fila) {
	// 		//recorre la fila
	// }
	// =========================================================
	function query_arr($query) {
		$rs = array();
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query)) {
			while ($fila = mysql_fetch_assoc($qry)) {
				$rs[] = $fila;
			}
			@mysql_free_result($qry);
			return $rs;
		} else {
			// Disparar error
			return $rs;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query y regresa un array con una columna
	// Sirve para traer un solo campo de multiples filas
	// Ejemplo de uso:
	// $tal = $bd->query_arr("select * from tabla");
	// foreach ($tal as $fila) {
	// 		//recorre la fila
	// }
	// =========================================================
	function query_col($query) {
		$rs = array();
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query)) {
			while ($fila = mysql_fetch_row($qry)) {
				$rs[] = $fila[0];
			}
			@mysql_free_result($qry);
			return $rs;
		} else {
			// Disparar error
			return $rs;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query y regresa un array con la primera fila del recordset
	// Ejemplo de uso:
	// $tal = $bd->query_row("select * from tabla");
	// $tal tiene ahora el array con la primera fila del resultado
	// =========================================================
	function query_row($query) {
		$rs = array();
		if ((stristr($query,"select") || stristr($query,"show")) && $qry = @mysql_query($query)) {
			if ($fila = @mysql_fetch_assoc($qry)) {
				$rs = $fila;
			}
			@mysql_free_result($qry);
			return $rs;
		} else {
			// Disparar error
			return $rs;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query simple (un solo campo/fila) y regresa el valor
	// Ejemplo de uso:
	// $tal = $bd->query_uno("select status from tabla where id=1");
	// echo "el status es $tal";
	// =========================================================
	/**
	* @return string
	* @param string $query
	* @desc Corre un query en la base de datos que regrese un solo campo y una sola fila, el dato es retornado como cadena
	*/
	function query_uno($query) {
		if ($test = stristr($query,"select") && $qry = @mysql_query($query)) {
			if(@mysql_num_rows($qry)) {
				return mysql_result($qry,0);
			}
		}
		return false;
	}
	
	// =========================================================
	// PUBLICA
	// Cuenta las filas coincidentes con el criterio en la tabla
	// Ejemplo de uso:
	// $cuantos = $bd->contar("tabla","status=0");
	// =========================================================
	function contar($tabla,$criterio=false) {
		$rs = 0;
		$query = "SELECT count(*) FROM ".$tabla;
		$query.= ($criterio) ? " where ".$criterio : "";
		if ($qry = @mysql_query($query)) {
			$rs = mysql_result($qry,0);
			@mysql_free_result($qry);
		}
		return $rs;
	}
	
	
	// =========================================================
	// PUBLICA
	// Corre un query tipo INSERT y regresa el ID asignado si hay uno
	// Ejemplo de uso:
	// $tal = $bd->agrega("insert into tabla (id,campo) values (NULL,'tal')");
	// echo "El nuevo registro es el Numero $tal";
	// =========================================================
	function agrega($query) {
		if (stristr($query,"insert") && $qry = @mysql_query($query)) {
			if ($insid = @mysql_insert_id($this->_res)) {
				return $insid;
			} else {
				return true;
			}
		} else {
			// Disparar error
			return false;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query tipo UPDATE y regresa la cantidad de filas afectadas
	// Ejemplo de uso:
	// $tal = $bd->actualiza("update tabla set campo='valor' where id<100");
	// echo "Se modificaron $tal registros en la Base de datos";
	// =========================================================
	function actualiza($query) {
		if (stristr($query,"update") && $qry = @mysql_query($query)) {
			if ($cuantos = @mysql_affected_rows($this->_res)) {
				return $cuantos;
			} else {
				return true;
			}
		} else {
			// Disparar error
			return false;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query tipo DELETE y regresa la cantidad de filas afectadas
	// Ejemplo de uso:
	// $tal = $bd->elimina("tabla","id=2");
	// echo "Se eliminaron $tal registros de la Base de datos";
	// =========================================================
	function elimina($tabla,$criterios) {
		$query = "delete from ".$tabla." where ".$criterios;
		if ($qry = @mysql_query($query)) {
			if ($cuantos = @mysql_affected_rows($this->_res)) {
				return $cuantos;
			} else {
				return true;
			}		
		} else {
			return false;
		}
	}
	
	// =========================================================
	// PUBLICA
	// Corre un query cualquiera y regresa el recurso creado por el query
	// Ejemplo de uso:
	// $tal = $bd->correr("select * from tabla");
	// $fila = mysql_fetch_array($tal);
	// =========================================================
	function correr($query) {
		$resid = @mysql_query($query);
		return $resid;
	}
	
	// =========================================================
	// PUBLICA
	// Escapa una cadena para que pueda incluirse en un query
	// de forma segura, evitando XSS
	// Ejemplo de uso:
	// $cadena_segura = $bd->escapar($cadena_insegura);
	// =========================================================
	function escapar($cadena) {
		if (!get_magic_quotes_gpc()) { 
		   return addslashes($cadena); 
		} else { 
		   return $cadena; 
		} 
	}
	
	
}

// =========================================================
// PRIVADA
// Clase abstracta para manejar recordsets via fetch
// =========================================================
class _rs {
	var $recordset;
	function _rs(&$qry) {
		$this->recordset = &$qry;
	}
	
	function fetch($tipo="array") {
		if ($tipo=="array") {
			if ($fila = @mysql_fetch_array($this->recordset)) {
				return $fila;
			} else {
				@mysql_free_result($this->recordset);
				return false;
			}
		} else {
			if ($fila = @mysql_fetch_assoc($this->recordset)) {
				return $fila;
			} else {
				@mysql_free_result($this->recordset);
				return false;
			}
		}
	}
}

?>