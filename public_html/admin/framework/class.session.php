<?php
/*
*************************************************
Clase para administrar sesiones
Autor: Manuel Guerrero - www.convergensys.com
Licencia: GPL
*************************************************
*/

class Session {
	var $sesdat;
    function Session () {
        session_start();
        $this->sesdat = $_SESSION;
    }
    function set ($name,$value) {
        $_SESSION[$name]=$value;
    }
    function get ($name) {
        if ( isset ( $_SESSION[$name] ) )
            return $_SESSION[$name];
        else
            return false;
    }
    function del ($name) {
        if ( isset ( $_SESSION[$name] ) ) {
            unset ( $_SESSION[$name] );
            return true;
        } else {
            return false;
        }
    }
    function destroy () {
        $_SESSION = array();
        session_destroy();
    }
}
?>