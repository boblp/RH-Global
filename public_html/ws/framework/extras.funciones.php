<?php
/*
******************************************
Colección de funciones adicionales simples
******************************************


*/
	function es_email($email) {
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	}

// Convierte un Numero (en digitos) a texto
// Creada por Manuel Guerrero. Publicada en FDW en 2003
// con licencia LGPL

	function numerotexto ($numero,$moneda="NO") {
		// Primero tomamos el numero y le quitamos los caracteres especiales y extras
		// Dejando solamente el punto "." que separa los decimales
		// Si encuentra mas de un punto, devuelve error.
		// NOTA: Para los paises en que el punto y la coma se usan de forma
		// inversa, solo hay que cambiar la coma por punto en el array de "extras"
		// y el punto por coma en el explode de $partes

		$arrmoneda= array ("MX"=>" Pesos ","US"=>" Dólares ","NO"=>"");
		$extras= array("/[\$]/","/ /","/,/","/-/");
		$limpio=preg_replace($extras,"",$numero);
		$partes=explode(".",number_format(abs($limpio),2));
		if (count($partes)>2) {
			return "Error, el n&uacute;mero no es correcto";
			exit();
		}

		// Ahora explotamos la parte del numero en elementos de un array que
		// llamaremos $digitos, y contamos los grupos de tres digitos
		// resultantes
		$partes[0]=preg_replace($extras,"",$partes[0]);
		$digitos_piezas=chunk_split ($partes[0],1,"#");
		$digitos_piezas=substr($digitos_piezas,0,strlen($digitos_piezas)-1);
		$digitos=explode("#",$digitos_piezas);
		$todos=count($digitos);
		$grupos=ceil (count($digitos)/3);

		// comenzamos a dar formato a cada grupo

		$unidad = array   ('un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve');
		$decenas = array ('diez','once','doce', 'trece','catorce','quince');
		$decena = array   ('dieci','veinti','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa');
		$centena = array   ('ciento','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos');
		$resto=$todos;

		for ($i=1; $i<=$grupos; $i++) {

			// Hacemos el grupo
			if ($resto>=3) {
				$corte=3; } else {
				$corte=$resto;
			}
				$offset=(($i*3)-3)+$corte;
				$offset=$offset*(-1);

			$num=implode("",array_slice ($digitos,$offset,$corte));
			$resultado[$i] = "";
			$cen = (int) ($num / 100);
			$doble = $num - ($cen*100);
			$dec = (int)($num / 10) - ($cen*10);
			$uni = $num - ($dec*10) - ($cen*100);
			if ($cen > 0) {
			   if ($num == 100) $resultado[$i] = "cien";
			   else $resultado[$i] = $centena[$cen-1].' ';
			}
			if ($doble>0) {
			   if ($doble == 20) {
				  $resultado[$i] .= " veinte";
			   }elseif (($doble < 16) and ($doble>9)) {
				  $resultado[$i] .= $decenas[$doble-10];
			   }else {
				  $resultado[$i] .=' '. $decena[$dec-1];
			   }
			   if ($dec>2 and $uni<>0) $resultado[$i] .=' y ';
			   if (($uni>0) and ($doble>15) or ($dec==0)) {
				  if ($i==1 && $uni == 1) $resultado[$i].="uno";
				  elseif ($i==2 && $num == 1) $resultado[$i].="";
				  else $resultado[$i].=$unidad[$uni-1];
			   }
			}

			// Le agregamos la terminacion del grupo
			switch ($i) {
				case 2:
				$resultado[$i].= ($resultado[$i]=="") ? "" : " mil ";
				break;
				case 3:
				$resultado[$i].= ($num==1) ? " mill&oacute;n " : " millones ";
				break;
			}
			$resto-=$corte;
		}

		// Sacamos el resultado (primero invertimos el array)
		$resultado_inv= array_reverse($resultado, TRUE);
		$final="";
		foreach ($resultado_inv as $parte){
			$final.=$parte;
		}
		$final.=$arrmoneda[$moneda];
		$final.=$partes[1];
		$final.="/100";
		return preg_replace("/[\s]+/"," ",ltrim($final));
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