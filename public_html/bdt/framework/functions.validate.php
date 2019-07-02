<?php
// Especial para limpiar HTML del navegador
function cleanup($txt,$type) {
	if($txt=="") {
		return "";
	}
	$class = ($type=="Lab") ? "empleo" : "estudio";
	$find = array("<DIV class=elemento contentEditable=true>","<P class=pdatos>","<STRONG>","<SPAN>","<BR>","<SPAN class=fechas>","</P>","</DIV>","</STRONG>","</SPAN>");
	$repl = array("<div class=\"".$class."\">","<p>","<strong>","<span>","<br />","<span class=\"fechas\">","</p>","</div>","</strong>","</span>");
	return str_replace($find,$repl,$txt);
}
?>