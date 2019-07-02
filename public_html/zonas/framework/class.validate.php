<?php
/* Class for form and general data validation
   this is meant to replace my old process.post.functions
*/
require ("lang.validate.php");
class Validate {
	var $data;
	var $errorfields = array();
	
	function Validate($data) {
		$this->data = $data;
		array_walk($this->data,array($this,"escape"));
	}
	
	function setdefault($field,$value) {
		$this->data[$field] = ($this->data[$field] || $this->data[$field]===0) ? $this->data[$field] : $value ;
	}
	
	function forcevalue($field,$value) {
		$this->data[$field] = $value ;
	}
	
	function ver($field,$alias,$type,$pattern="",$extra=0,$custmsg=false) {
		$alias = ($alias) ? $alias : $field;
		// No empty
		if ($type & 1) {
			if (!isset($this->data[$field]) || $this->data[$field]=="") {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_NOEMPTY,$custmsg);
			}
		}
		// Alnum + spaces + dash + underscore
		if ($type & 2) {
			if (isset($this->data[$field]) && $this->data[$field]!="" && !(preg_match("/^([\wÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïñòóôõöøùúûüýÿ \-\_\\\\'´]+)$/i",$this->data[$field]))) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_ALNUM,$custmsg);
			}
		}
		// Alnum + spaces + dash + underscore (for text fields)
		if ($type & 1024) {
			if (isset($this->data[$field]) && $this->data[$field]!="" && !(preg_match("/^([\w\n\rÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïñòóôõöøùúûüýÿ,\.#$\[\]\(\)\=\!\?\%\¿\¡ \-\_\\\\']+)$/i",$this->data[$field]))) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_ALNUM,$custmsg);
			}
		}
		// Numeric
		if ($type & 4) {
			$this->data[$field] = preg_replace("/([^0-9\.])/","",$this->data[$field]);
			if (isset($this->data[$field]) && $this->data[$field]!="" && !(preg_match("/^[0-9]+(\.[0-9]+)?$/",$this->data[$field]))) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_NUM,$custmsg);
			}
		}
		// Date
		if ($type & 8) {
			$this->data[$field] = preg_replace(array("/([^0-9\-\/])/","/\//"),array("","-"),$this->data[$field]);
			if (isset($this->data[$field]) && $this->data[$field]!="") {
				$slices = explode("-",$this->data[$field]);
				if (($slices[0]>2015 || $slices[0]<2000) || !checkdate($slices[1]*1,$slices[2]*1,$slices[0]*1)) {
					$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_DATE,$custmsg);
				}
			}
		}
		// Email
		if ($type & 16) {
			if (isset($this->data[$field]) && $this->data[$field]!="" && !($this->is_email($this->data[$field]))) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_EMAIL,$custmsg);
			}
		}
		// Custom Regex
		if ($type & 32) {
			if (isset($this->data[$field]) && $this->data[$field]!="" && !(@preg_match($pattern,$this->data[$field]))) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_BADFORMAT,$custmsg);
			}
		}
		// Repeated
		if ($type & 64) {
			if ($extra == true) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_REPEATED,$custmsg);
			}
		}
		// No Match
		if ($type & 128) {
			if ($extra == true) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_NOMATCH,$custmsg);
			}
		}
		// No Select
		if ($type & 256) {
			if ($extra == true) {
				$this->pusherror($field,"<strong>$alias</strong> ".LNG_FRMERR_NOSELECT,$custmsg);
			}
		}
		// Special Message
		if ($type & 512) {
			if ($extra == true) {
				$this->pusherror($field,$alias,$custmsg);
			}
		}
		return true;
	}
	
	function is_email($addr) {
		return(preg_match("/^[a-z0-9_\-]+(\.[_a-z0-9\-]+)*@([_a-z0-9\-]+.)+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)$/i",$addr));
	}
	
	function pusherror($field,$message,$custmsg=false) {
		$this->errorfields[$field] = ($custmsg) ? $custmsg : $message;
	}
	
	function haserrors() {
		return (count($this->errorfields)) ? true : false ;
	}
	
	function getdata($formysql=false) {
		if(!$formysql) {
			array_walk($this->data,array($this,"removeslashes"));
			array_walk($this->data,array($this,"entities"));
		}
		return $this->data;
	}
	
	function entities(&$arr,$key) {
		$arr = htmlspecialchars($arr);
	}

	function removeslashes(&$arr,$key) {
		$arr = stripslashes($arr);
	}

	function escape(&$arr,$key) {
		$arr = (get_magic_quotes_gpc()) ? $arr : addslashes($arr);
	}

}

?>