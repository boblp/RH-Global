<?php

// Creada por Manoloweb Jun-05
// www.ideasfreelance.com

class Imagen {
	var $archivo;
	var $salida;
	var $medidas_orig;
	var $ok = false;
	var $img;
	var $metodo;
	var $formato;
	
	function Imagen($campo) {
		$this->archivo = &$_FILES[$campo];
		if(is_uploaded_file($this->archivo["tmp_name"]) && preg_match("/(gif|jpg|jpeg|pjpeg|png)$/",$this->archivo["type"])) {
			$this->medidas_orig = getimagesize($this->archivo["tmp_name"]);
			$this->ok = true;
			switch ($this->medidas_orig["mime"]) {
				case "image/pjpeg":
				case "image/jpg":
				case "image/jpeg":
				$this->formato = "jpg";
				$this->img = imagecreatefromjpeg($this->archivo["tmp_name"]);
				break;
				case "image/gif":
				$this->formato = "gif";
				$this->img = imagecreatefromgif($this->archivo["tmp_name"]);
				break;
				case "image/png":
				case "image/x-png":
				$this->formato = "png";
				$this->img = imagecreatefrompng($this->archivo["tmp_name"]);
				break;
			}
		}
		$this->metodo = "crop";
	}
	
	
	/*
	nombre = ruta y nombre del archivo destino
	ancho = valor en pixeles de el ancho de la nueva imagen
	alto =
		a) valor en pixeles
		b) auto (proporciona de acuerdo a la img original)
		c) A:B como proporcion requerida para la imagen, por ejemplo 4:3
	salida = tipo de archivo (jpg,gif,png)
	*/
	function crear($path,$ancho,$alto="auto") {
		if($ancho>1 && is_numeric($ancho) && $this->ok) {
			$medidas = array();
			$medidas[0] = round($ancho);
			switch ($alto) {
				case "auto":
				$medidas[1] = round(($this->medidas_orig[1]/$this->medidas_orig[0]) * $medidas[0]);
				break;
				default:
					if ($alto>1 && is_numeric($alto)) {
						$medidas[1] = round($alto);
					} elseif (preg_match("/^(([0-9\.]+):([0-9\.]+))$/",$alto,$props)) {
						$medidas[1] = round(($props[3]/$props[2]) * $medidas[0]);
					} else {
						return false;
					}
				break;
			}
			$ni = imagecreatetruecolor($medidas[0],$medidas[1]);
			$ntam = ($alto=="auto") ? $medidas : $this->resize($medidas[0],$medidas[1]);
			imagecopyresampled($ni,$this->img,0,0,0,0,$ntam[0],$ntam[1],$this->medidas_orig[0],$this->medidas_orig[1]);
			if($this->guarda($path,&$ni)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/* Esta se encarga de determinar el tamaño de la imagen a redimensionar, tomando en cuenta
	   el tipo de adaptación de imágen que se haya pedido ($this->metodo), regresa un array
	   de dos valores [0]=ancho y [1]=alto

	   crop = se asegura de que la imagen quede en su proporcion original, ademas de que no
	          sobresalgan areas sin imagen (esta es el default)
	   stretch = la imagen cambia sus proporciones para adaptarse, en realidad cualquier
	          valor diferente a "crop" causa este efecto, que no es deseable, pero lo pongo
	          por si acaso...
	*/
	function resize($ancho,$alto) {
		$ntam = array($ancho,$alto);
		if ($this->metodo=="crop") {
			$prop = ($this->medidas_orig[1]/$this->medidas_orig[0]);
			$nh = round($prop * $ancho);
			if ($nh<$alto) {
				$ntam[0] = $alto * (1/$prop);
			} else {
				$ntam[1] = $nh;
			}
		}
		return $ntam;
	}

	function guarda($path,&$img) {
		if(preg_match("/\.(gif|jpg|png)$/i",$path,$datos)) {
			switch ($datos[1]) {
				case "gif":
				$instruccion = "imagegif(\$img,'".$path."');";
				break;
				case "png":
				$instruccion = "imagepng(\$img,'".$path."');";
				break;
				case "jpg":
				$instruccion = "imagejpeg(\$img,'".$path."',80);";
				break;
			}
			eval($instruccion);
			return true;
		} else {
			return false;
		}
	}
}
?>