<?php
/*
*************************************************
Clase para administrar cookies
Autor: Manuel Guerrero - www.convergensys.com
Licencia: GPL
*************************************************
*/

class Cookie {
	var $cookdat;
    function Cookie () {
        $this->cookdat = $_COOKIE;
    }
    // Exp son los dias que queremos que viva la cookie
    function set ($name,$value,$exp=7,$path="/") {
		if (!headers_sent()) {
			$dias = ceil(time() + (60 * 60 * 24 * $exp));
        	setcookie($name,$value,$dias,$path);
        	return true;
		} else {
			return false;
		}
    }
    function get ($name) {
        if ( isset ( $_COOKIE[$name] ) )
            return $_COOKIE[$name];
        else
            return false;
    }
    function del ($name) {
        if ( isset ( $_COOKIE[$name] ) ) {
            $this->set ($name,"",-1);
            return true;
        } else {
            return false;
        }
    }
}
?>