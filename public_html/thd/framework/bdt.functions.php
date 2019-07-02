<?php
function _makeSex($cur) {
	$res = '<select name="sexo">';
		$res.= '<option value="todas">--</option>';
		$res.= '<option '.(($cur=="f")?'selected="selected" ':'').'value="f">Femenino</option>';
		$res.= '<option '.(($cur=="m")?'selected="selected" ':'').'value="m">Masculino</option>';
	$res.= '</select><br />';
	return $res;
}

function _makeAge($cur,$type) {
	$res = '<select name="'.$type.'age">';
		$res.= '<option value="todas">--</option>';
		for ($i=16;$i<60;$i++) {
			$res.= '<option '.(($cur==$i)?'selected="selected" ':'').'value="'.$i.'">'.$i.'</option>';
		}
	$res.= '</select><br />';
	return $res;
}

function _makeUni($cur) {
	global $bd;
	$res = '<select name="univ_id">';
	$univs = $bd->query_arr("select distinct b.univ_id,b.nombre from bdt a left join universidades b on(a.univ_id=b.univ_id) where b.univ_id is not null and b.nombre!='Otra' order by b.nombre");
	$total = count($univs);
		$res.= '<option value="todas">--</option>';
		for ($i=0;$i<$total;$i++) {
			$res.= '<option '.(($cur==$univs[$i]["univ_id"])?'selected="selected" ':'').'value="'.$univs[$i]["univ_id"].'">'.$univs[$i]["nombre"].'</option>';
		}
	$res.= '</select><br />';
	return $res;
}

function _makeCar($cur) {
	global $bd;
	$res = '<select name="carrera_id">';
	$carrs = $bd->query_arr("select distinct b.carrera_id,b.nombre from bdt a left join carreras b on(a.carrera_id=b.carrera_id) where b.carrera_id is not null and b.nombre!='Otra' order by b.nombre");
	$total = count($carrs);
		$res.= '<option value="todas">--</option>';
		for ($i=0;$i<$total;$i++) {
			$res.= '<option '.(($cur==$carrs[$i]["carrera_id"])?'selected="selected" ':'').'value="'.$carrs[$i]["carrera_id"].'">'.$carrs[$i]["nombre"].'</option>';
		}
	$res.= '</select><br />';
	return $res;
}

function _makeEcivil($cur) {
	$res = '<select name="ecivil">';
		$res.= '<option value="todas">--</option>';
		$res.= '<option '.(($cur=="Soltero(a)")?'selected="selected" ':'').'value="Soltero(a)">Soltero(a)</option>';
		$res.= '<option '.(($cur=="Casado(a)")?'selected="selected" ':'').'value="Casado(a)">Casado(a)</option>';
		$res.= '<option '.(($cur=="Divorciado(a)")?'selected="selected" ':'').'value="Divorciado(a)">Divorciado(a)</option>';
		$res.= '<option '.(($cur=="Viudo(a)")?'selected="selected" ':'').'value="Viudo(a)">Viudo(a)</option>';
		$res.= '<option '.(($cur=="Unión Libre")?'selected="selected" ':'').'value="Unión Libre">Unión Libre</option>';
	$res.= '</select><br />';
	return $res;
}

function _makeEd($cur) {
	$res = '<select name="niveled">';
		$res.= '<option value="todas">--</option>';
		$res.= '<option '.(($cur=="Sin Estudios")?'selected="selected" ':'').'value="Sin Estudios">Sin Estudios</option>';
		$res.= '<option '.(($cur=="Primaria")?'selected="selected" ':'').'value="Primaria">Primaria</option>';
		$res.= '<option '.(($cur=="Secundaria")?'selected="selected" ':'').'value="Secundaria">Secundaria</option>';
		$res.= '<option '.(($cur=="Preparatoria / Bachillerato")?'selected="selected" ':'').'value="Preparatoria / Bachillerato">Preparatoria / Bachillerato</option>';
		$res.= '<option '.(($cur=="Carrera Comercial")?'selected="selected" ':'').'value="Carrera Comercial">Carrera Comercial</option>';
		$res.= '<option '.(($cur=="Carrera Técnica")?'selected="selected" ':'').'value="Carrera Técnica">Carrera Técnica</option>';
		$res.= '<option '.(($cur=="Universidad Inconclusa")?'selected="selected" ':'').'value="Universidad Inconclusa">Universidad Inconclusa</option>';
		$res.= '<option '.(($cur=="Universidad (Titulado)")?'selected="selected" ':'').'value="Universidad (Titulado)">Universidad (Titulado)</option>';
		$res.= '<option '.(($cur=="Postgrado")?'selected="selected" ':'').'value="Postgrado">Postgrado</option>';
	$res.= '</select><br />';
	return $res;
}

function _makeIng($cur) {
	$res = '<select name="ingles">';
		$res.= '<option value="todas">--</option>';
		$res.= '<option '.(($cur=="30")?'selected="selected" ':'').'value="30">30% - Básico</option>';
		$res.= '<option '.(($cur=="50")?'selected="selected" ':'').'value="50">50% - Regular</option>';
		$res.= '<option '.(($cur=="70")?'selected="selected" ':'').'value="70">70% - Bueno</option>';
		$res.= '<option '.(($cur=="90")?'selected="selected" ':'').'value="90">90% - Excelente</option>';
		$res.= '<option '.(($cur=="100")?'selected="selected" ':'').'value="100">100% - Nativo</option>';
	$res.= '</select><br />';
	return $res;
}

function bdt_makeform() {
		$res = '<div id="filtershow"><table width="100%" cellpadding="5" cellspacing="5"><tr>';
			$res.= '<td width="100"><strong>Generales</strong><br />Sexo: '._makeSex($_SESSION["bdts"]["sexo"]).' Edad mínima: '._makeAge($_SESSION["bdts"]["minage"],"min").' Edad máxima: '._makeAge($_SESSION["bdts"]["maxage"],"max").' Estado Civil: '._makeEcivil($_SESSION["bdts"]["ecivil"]).'</td>';
			$res.= '<td><strong>Educación</strong><br />Nivel de estudios mínimo:<br />'._makeEd($_SESSION["bdts"]["niveled"]).'Nivel mínimo de inglés:<br />'._makeIng($_SESSION["bdts"]["ingles"]).'Universidad:<br />'._makeUni($_SESSION["bdts"]["univ_id"]).'Carrera:<br />'._makeCar($_SESSION["bdts"]["carrera_id"]).'</td>';
		$res.= '</tr></table></div>';
	return $res;
}



/* Construccion del resultado */

function _sWords($wordstring) {
	$words = explode(" ",$wordstring);
	array_walk($words,"superclean");
	$words = array_unique($words);
	$res = " where (";
	$parts = array();
	foreach($words as $word) {
		$parts[] = "(histed like('%".$word."%') or histlab like('%".$word."%') or keywords like('%".$word."%') or z.nombre like('%".$word."%') or a.nombre like('%".$word."%') or b.nombre like('%".$word."%') or idiomas like('%".$word."%'))";
	}
	return $res.=implode(" or ",$parts).")";
}

function _fSex($sex) {
	if($sex!="todas") {
		return " and sexo='".$sex."'";
	} else {
		return "";
	}
}

function _fMinage($minage) {
	if($minage!="todas") {
		return " and to_days(fnac)<=".to_days(date("Ymd"),-$minage);
	} else {
		return "";
	}
}

function _fMaxage($maxage) {
	if($maxage!="todas") {
		return " and to_days(fnac)>=".to_days(date("Ymd"),-$maxage);
	} else {
		return "";
	}
}

function _fEcivil($ecivil) {
	if($ecivil!="todas") {
		return " and ecivil='".$ecivil."'";
	} else {
		return "";
	}
}

function _fNed($ned) {
	if($ned!="todas" && $ned!="Sin Estudios") {
		$niveles = array("Primaria","Secundaria","Preparatoria / Bachillerato","Carrera Comercial","Carrera Técnica","Universidad Inconclusa","Universidad (Titulado)","Postgrado");
		$nivelesin = array_slice($niveles,array_search($ned,$niveles));
		return " and niveled in('".implode("','",$nivelesin)."')";
	} else {
		return "";
	}
}

function _fIng($ing) {
	if($ing!="todas") {
		return " and ingles>=".$ing;
	} else {
		return "";
	}
}

function _fUni($uni) {
	if($uni!="todas") {
		return " and z.univ_id=".$uni;
	} else {
		return "";
	}
}

function _fCar($car) {
	if($car!="todas") {
		return " and z.carrera_id=".$car;
	} else {
		return "";
	}
}

$templateRecord = <<<HEREDOC
<div class="bdtrecord">
	<p class="bdtfolio"><span>Folio: ##FOLIO##</span></p>
	<p class="bdtdata">
		<strong>Nombre</strong>: ##NOMBRE## &nbsp;&nbsp; <strong>Sexo</strong>: ##TSEXO## - <strong>Edad</strong>: ##EDAD##<br />
		<strong>Estado Civil</strong>: ##ECIVIL## &nbsp;&nbsp; <strong>Máximo Nivel de estudios</strong>: ##NIVELED## <br>
		<strong>Universidad</strong>: ##UNI##<br>
		<strong>Carrera</strong>: ##CARRERA##
	</p>
	<p class="bdtliga"><a href="/descargabdt/##VERIF##/##FOLIO##"><span>Descargar Resumen</span></a></p>
</div>
HEREDOC;

function _makepairs($arr) {
	$narr = array(array(),array());
	$counter = 0;
	foreach($arr as $k=>$v) {
		$narr[0][$counter] = "##".strtoupper($k)."##";
		$narr[1][$counter] = htmlentities($v);
		$counter++;
	}
	return $narr;
}

function _makePager($max,$cur,$tot) {
	if($tot==0) {
		$pages = "<div class=\"bdtpager\"><div class=\"presumen\">Tu búsqueda no a coincidido con ningún registro</div></div>";
	} else if($max<2) {
		$pages = "<div class=\"bdtpager\"><div class=\"presumen\">Tu búsqueda ha retornado ".$tot." resultados</div></div>";
	} else {
		$pages = "<div class=\"bdtpager\"><div class=\"presumen\">Tu búsqueda ha retornado ".$tot." resultados en ".$max." páginas</div><div class=\"ppager\">Página <select onchange=\"gotopage(this.value)\">";
		for ($i=1;$i<=$max;$i++) {
			$cursel = ($cur==$i) ? " selected=\"selected\"" : "";
			$pages.= " <option value=\"".$i."\"".$cursel.">".$i."</option> ";
		}
		$pages.= "</select> de ".$max."</div></div>";
	}
	return $pages;
}

function bdt_makeresult($page) {
	global $bd, $templateRecord;
	if(!$_SESSION["bdts"]) {
		$result = "";
		$pager = "";
	} else {
		$perpage = 20;
		$qry = "select #### from bdt z left join universidades a on(z.univ_id=a.univ_id) left join carreras b on(z.carrera_id=b.carrera_id) ";
		$qry.= _sWords($_SESSION["bdts"]["srchword"]);
		$qry.= _fSex($_SESSION["bdts"]["sexo"]);
		$qry.= _fMinage($_SESSION["bdts"]["minage"]);
		$qry.= _fMaxage($_SESSION["bdts"]["maxage"]);
		$qry.= _fEcivil($_SESSION["bdts"]["ecivil"]);
		$qry.= _fNed($_SESSION["bdts"]["niveled"]);
		$qry.= _fUni($_SESSION["bdts"]["univ_id"]);
		$qry.= _fCar($_SESSION["bdts"]["carrera_id"]);
		$qry.= _fIng($_SESSION["bdts"]["ingles"]);
		$qry.= " and statusgral=1";
		//$qry.= " and statusgral=1";
		$total = $bd->query_uno(str_replace("####","count(folio)",$qry));
		$totalpages = ceil($total/$perpage);
		$thispage = ($page>0 && $page<=$totalpages) ? $page : 1;
		$thislimit = " order by folio desc limit ".(($thispage*$perpage)-$perpage).",".$perpage;
		$queryfinal = str_replace("####","z.folio,z.nombre,if(z.sexo='f','Femenino','Masculino') as tsexo,z.niveled,z.ecivil,ceil((to_days(now())-to_days(fnac))/365) as edad, a.nombre as uni, b.nombre as carrera",$qry).$thislimit;
		$listabdt = $bd->query_arr($queryfinal);
		$result = "";
		foreach($listabdt as $elebdt) {
			$elebdt["verif"] = md5($elebdt["folio"].$elebdt["nombre"]."1");
			$rep = _makepairs($elebdt);
			$result.= str_replace($rep[0],$rep[1],$templateRecord);
		}
		$pager = _makePager($totalpages,$thispage,$total);
	}
	return $pager.$result;
}

?>