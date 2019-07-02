<?php
/*
******************************************
Colección de funciones adicionales simples
******************************************


*/


function isleap($yr) {
	return ($yr%4) ? 0 : (($yr%100) ? 1 : (($yr%400) ? 1 :0));
}

function to_days($datestring,$addyrs=0) {
	if(strlen($datestring)!=8) {
		return false;
	}
	$daysum = 693960; // Dic 31, 1899
	$yr = substr($datestring,0,4) + $addyrs;
	if($yr<1900 || $yr>date("Y")) {
		return false;
	}
	$mo = ltrim(substr($datestring,4,2),"0");
	$dy = ltrim(substr($datestring,6,2),"0");
	for($i=1900;$i<$yr;$i++) {
		$leap = isleap($i);
		$daysum+= ($leap) ? 366 : 365;
	}
	$leap = isleap($yr);
	for($i=1;$i<$mo;$i++) {
		switch($i) {
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
			$daysum+=31;
			break;
			case 4:
			case 6:
			case 9:
			case 11:
			$daysum+=30;
			break;
			case 2:
			$daysum+= ($leap) ? 29 : 28;
			break;
		}
	}
	$daysum+=$dy;
	return $daysum;
}


function clean(&$arr,$key) {
	if(is_array($arr)) {
		array_map($arr,"superclean");
		return;
	}
	$arr = trim(preg_replace(array("/[^a-zA-Z0-9 áéíóúÁÉÍÓÚàèìòùñÑüÜ\/\(\)]/i","/\s+/"),array(""," "),$arr));
	$arr = (get_magic_quotes_gpc()) ? $arr : addslashes($arr);
}

function superclean(&$arr,$key) {
	if(is_array($arr)) {
		array_map($arr,"superclean");
		return;
	}
	$arr = trim(preg_replace(array("/[^a-zA-Z0-9 áéíóúÁÉÍÓÚàèìòùñÑüÜ\/\(\)]/i","/\s+/"),array(""," "),$arr));
	$arr = (get_magic_quotes_gpc()) ? $arr : addslashes($arr);
}




function es_email($email) {
	return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
}

// Funcion para sacar la fecha en español, igual que date();
// Creada por Manuel Guerrero. Publicada en FDW en 2003
// con licencia LGPL

	function date_es($formato="F j, Y",$fecha=0) {
		if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $fecha,$partes)) {
			if (checkdate($partes[2],$partes[3],$partes[1])) {
				$fecha=strtotime($fecha);
			} else {
				return(-1);
			}
		} elseif ($fecha==0) {
			$fecha=time();
		}
		$dias=array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
		$dias_c=array("Dom","Lun","Mar","Mie","Jue","Vie","Sab");
		$meses=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		$meses_c=array("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");

		$valores=explode("|",date ("a|A|B|d|D|F|g|G|h|H|i|I|j|l|L|m|M|n|O|r|s|S|t|T|U|w|W|Y|y|z|Z",$fecha));
		$claves= array ("a","A","B","d","D","F","g","G","h","H","i","I","j","l","L","m","M","n","O","r","s","S","t","T","U","w","W","Y","y","z","Z");
		for ($i=0;$i<count($claves);$i++) {
			$conv[$claves[$i]]=$valores[$i];
		}
		$conv["D"]=$dias_c[$conv["w"]];
		$conv["l"]=$dias[$conv["w"]];
		$conv["F"]=$meses[$conv["n"]];
		$conv["M"]=$meses_c[$conv["n"]];
		$conv["r"]=$conv["D"].", ".$conv["d"]." ".$conv["M"]." ".$conv["Y"]." ".$conv["H"].":".$conv["i"].":".$conv["s"]." ".$conv["O"];
		$conv["S"]="o";
		$escape='\\';
		$escapado=0;
		$f=$formato;
		$res="";
		for ($t=0;$t<strlen($formato);$t++) {
			if ($escapado==1) {
				$res.=$f{$t};
				$escapado=0;
			} else {
				if($f{$t}==$escape) {
					$escapado=1;
				} else {
					if (isset($conv[$f[$t]])){
						$res.=$conv[$f[$t]];
					} else {
						$res.=$f{$t};
					}
				}
			}
		}
		return $res;
	}


?>