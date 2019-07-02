<?php
function isleap($yr) {
	return ($yr%4) ? 0 : (($yr%100) ? 1 : (($yr%400) ? 1 :0));
}

function to_days($datestring) {
	if(strlen($datestring)!=8) {
		return false;
	}
	$daysum = 693960; // Dic 31, 1899
	$yr = substr($datestring,0,4);
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