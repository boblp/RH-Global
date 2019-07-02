<?php
/*
	Clase para generación de emails de VACANTES.NET
	Desarrollada por Manoloweb en Febrero de 2006
	
	TO-DO:
		- Decidir que pagina de error mostrar si no hay INI
		- Decidir que hacer si el mail proporcionado por el usuario no es correcto.
		  (por el momento voy a mandar a un error)
		- Jorge: tu verificador de email en javascript es muy deficiente, hay que ver
		  que hacemos al respecto, porque no valida estructura, permite caracteres inválidos
		  y sobre todo acepta cadenas que no pueden usarse en un "from" 
		  (ejemplo: @123, áéíóú@tal.sss, etc)
		
	
*/

class Hum_Mail {
	// Propiedades =============================================================
	var $br;
	var $brmsg;
	var $charset;
	var $separador_mix;
	var $separador_alt;
	var $mimes_foto;
	var $mimes_curr;
	var $campos_requeridos;
	var $ini;
	var $errorreturn;
	var $correo_from;
	var $mail_to;
	var $adjuntos = array();
	var $cuerpomsg = "";
	var $errorfiles = array(1=>"El tamaño del archivo supera el límite permitido",2=>"El tamaño del archivo supera el límite permitido",3=>"El archivo no se ha enviado correctamente",6=>"Ha habido erores al procesar el arcchivo adjunto. Intentelo de nuevo, o bien trate sin enviar attachments");
	
	
	// Métodos  ================================================================
	
	// Constructor
	function Hum_Mail() {
		$this->br = (strstr(PHP_OS, 'WIN')) ? "\r\n" : "\n";
		$this->brmsg = (strstr(PHP_OS, 'WIN')) ? "\r\n" : "\n";
		$this->charset = "ISO-8859-1";
		$this->campos_requeridos = array("_1_cte","HS_eMail"); // Sin estos dos, simplemente no podemos seguir
		$this->ini = @parse_ini_file($_POST["_1_cte"].".ini");
		$this->errorreturn = ($_SERVER["HTTP_REFRER"]) ? "javascript:history.go(-1);": "http://www.vacantes.net";
		$this->verifica();
		$this->separador_mix = "=-VACANTES_NET_MIX_".md5(uniqid(rand()));
		$this->separador_alt = "=-VACANTES_NET_ALT_".md5(uniqid(rand()));
		$this->mimes_foto = (isset($this->ini["FileFotoAllow"])) ? explode(",",$this->ini["FileFotoAllow"]) : array();
		$this->mimes_curr = (isset($this->ini["FileCurrAllow"])) ? explode(",",$this->ini["FileCurrAllow"]) : array();
		$this->adjunta_foto();
		$this->adjunta_curr();
		$this->construye_msg();
		$this->mandamail();
		$this->mandaconfirma();
	}
	
	function adjunta_foto() {
		if(isset($_FILES[$this->ini["FileFotoField"]]) && $_FILES[$this->ini["FileFotoField"]]["error"]!=UPLOAD_ERR_NO_FILE) {
			if($_FILES[$this->ini["FileFotoField"]]["error"]==UPLOAD_ERR_OK) {
				if(in_array($_FILES[$this->ini["FileFotoField"]]["type"],$this->mimes_foto) || $this->checaext("doc,ppt,pps,xls,pdf,rtf,txt,jpg,gif,jpeg,png,pic,wmf,bmp",$_FILES[$this->ini["FileFotoField"]]["name"])) {
					if($datos = $this->procesa_archivo($_FILES[$this->ini["FileFotoField"]])) {
						array_push($this->adjuntos,$datos);
					} else {
						$this->error("Error adjuntando foto: Problemas procesando archivo","javascript:history.go(-1);");
					}
				} else {
					$this->error("Error adjuntando foto: El tipo de archivo no está permitido","javascript:history.go(-1);");
				}
			} else {
				$this->error("Error adjuntando foto: ".$this->errorfiles[$_FILES[$this->ini["FileFotoField"]]["error"]],"javascript:history.go(-1);");
			}
		}
	}
	
	function adjunta_curr() {
		if(isset($_FILES[$this->ini["FileCurrField"]]) && $_FILES[$this->ini["FileCurrField"]]["error"]!=UPLOAD_ERR_NO_FILE) {
			if($_FILES[$this->ini["FileCurrField"]]["error"]==UPLOAD_ERR_OK) {
				if(in_array($_FILES[$this->ini["FileCurrField"]]["type"],$this->mimes_curr) || $this->checaext("doc,ppt,pps,xls,pdf,rtf,txt,jpg,gif,jpeg,png,pic,wmf,bmp",$_FILES[$this->ini["FileCurrField"]]["name"])) {
					if($datos = $this->procesa_archivo($_FILES[$this->ini["FileCurrField"]])) {
						array_push($this->adjuntos,$datos);
					} else {
						$this->error("Error adjuntando currículum: Problemas procesando archivo","javascript:history.go(-1);");
					}
				} else {
					$this->error("Error adjuntando currículum: El tipo de archivo no está permitido","javascript:history.go(-1);");
				}
			} else {
				$this->error("Error adjuntando currículum: ".$this->errorfiles[$_FILES[$this->ini["FileCurrField"]]["error"]],"javascript:history.go(-1);");
			}
		}
	}
	
	function checaext($extlist,$filename) {
		$extlist = strtolower($extlist);
		$filename = strtolower($filename);
		$parts = explode(".",$filename);
		$last = count($parts) - 1;
		$ext = $parts[$last];
		$extarr = explode(",",$extlist);
		return in_array($ext,$extarr);
	}
	
	function procesa_archivo($ref) {
		$datos = array();
		if($archivodat = @file_get_contents($ref["tmp_name"])) {
			$datos["content"] = chunk_split(base64_encode($archivodat),76,$this->br);
			$datos["type"] = $ref["type"];
			$datos["name"] = $ref["name"];
			return $datos;
		} else {
			return false;
		}
	}
	
	function construye_msg() {
		$campos = $this->deja_hs($_POST);
		$campos = ($this->ini["StripEmptyFields"]==1) ? $this->quita_vacios($campos) : $campos;
		foreach($campos as $clave=>$campo) {
			$this->cuerpomsg.=str_pad($clave.":",16).str_replace("\n",$this->brmsg."                ",$campo).$this->brmsg;
		}
		$this->cuerpomsg.=$this->brmsg.$this->brmsg.$this->ini["FooterMessage"];
	}
	
	
	function quita_vacios($arr) {
		$narr = array();
		foreach($arr as $k=>$v) {
			if($v!="") {
				$narr[$k]=$v;
			}
		}
		return $narr;
	}
	
	function deja_hs($arr) {
		$narr = array();
		foreach($arr as $k=>$v) {
			if(strstr($k,"HS_")!=false) {
				$narr[$k]=$v;
			}
		}
		return $narr;
	}
	
	function mandamail() {
		if($this->cuerpomsg) {
			$mailbody = "";
			$mailheaders = "";
			if(count($this->adjuntos)) {
				$tipo = "Content-Type: multipart/mixed; boundary=\"".$this->separador_mix."\"";
				$mailbody = "--".$this->separador_mix.$this->br;
				$mailbody.= "Content-Type: text/plain; charset=\"".$this->charset."\"".$this->br;
				$mailbody.= "Content-Transfer-Encoding: 7bit".$this->br.$this->br;
				$mailbody.= $this->cuerpomsg.$this->br.$this->br;
				foreach($this->adjuntos as $archivo){
					$mailbody.= "--".$this->separador_mix.$this->br;
					$mailbody.= "Content-Type: ".$archivo["type"]."; name=\"".$archivo["name"]."\"".$this->br;
					$mailbody.= "Content-Disposition: attachment; filename=\"".$archivo["name"]."\"".$this->br;
					$mailbody.= "Content-Transfer-Encoding: base64".$this->br.$this->br;
					$mailbody.= $archivo["content"].$this->br.$this->br;
				}
				$mailbody.= "--".$this->separador_mix."--".$this->br;
			} else {
				$tipo = "Content-Type: text/plain; charset=\"".$this->charset."\"";
				$mailbody = $this->cuerpomsg;
			}
			$mailheaders = $this->creaheaders($tipo);
			if(@mail($this->ini["MailReply"],$this->ini["MailSubject"],$mailbody,$mailheaders)) {
				header("Location: ".$this->ini["SuccessDocument"]);
			} else {
				$this->error("Error enviando el email, por favor intentelo más tarde",$this->ini["ErrorDocument"]);
			}
		} else {
			$this->error("Error procesando formulario, no hay campos que mostrar","javascript:history.go(-1);");
		}
	}
	
	function mandaconfirma() {
		if(isset($this->ini["UserMessage"]) && $this->ini["UserMessage"]!="" && $this->es_email($_POST["HS_eMail"])) {
			$cuerpomsg = str_replace("#","\n",$this->ini["UserMessage"]);
			$headers = "";
			$headers.= "From: ".$this->ini["MailReply"].$this->br;
			$headers.= "Reply-To: ".$this->ini["MailReply"].$this->br;
			$headers.= "MIME-Version: 1.0".$this->br;
			$headers.= "X-Mailer: HumSoft Mailer".$this->br;
			$headers.= "Content-Type: text/plain; charset=\"".$this->charset."\"";
			$subject = ($this->ini["UserSubject"]) ? $this->ini["UserSubject"] : "Expediente Recibido";
			@mail($_POST["HS_eMail"],$subject,$cuerpomsg,$headers);
		}
	}
	
	
	// Verificador, aqui establecemos los requisitos mínimos para poder
	// seguir adelante.
	function verifica() {
		// Si no hay formulario...
		if(count($_POST)<1) {
			$this->error("No se ha recibido un formulario",$this->errorreturn);
			return false;
		}
		// Si no existe el INI 
		if(!$this->ini) {
			$this->error("No existe el procesador para este formulario",$this->errorreturn);
			return false;
		}
		
		// De aqui en adelante asumo que POST=OK y que el INI se ha procesado 
		// y cuenta con los campos adecuados
		
		// Si no viene algun campo requerido...
		foreach($this->campos_requeridos as $creq) {
			if(!array_key_exists($creq,$_POST)) {
				$this->error("Faltan campos requeridos",$this->ini["ErrorDocument"]);
				return false;				
			}
		}
		// Si el email proporcionado no es correcto 
		if($this->ini["MailFrom"]=="HS_eMail") {
			if(!$this->es_email($_POST["HS_eMail"])) {
				$this->correo_from = $this->ini["MailReply"];
			} else {
				$this->correo_from = $_POST["HS_eMail"];
			}
		} else {
			$this->correo_from = $this->ini["MailFrom"];
		}
		
		// Mailto loop...
		if($this->es_email($this->ini["MailTo"]) || 1==1) {
			if($this->ini["MailToVariable"] && $this->ini["MailToDominio"] && $_POST["MailTo"] && $this->es_email($_POST["MailTo"])) {
				$mailparts = explode("@",$_POST["MailTo"]);
				if($mailparts[1]==$this->ini["MailToDominio"]) {
					$this->mail_to = $_POST["MailTo"];
				} else {
					$this->mail_to = $this->ini["MailTo"];
				}
			} else {
				$this->mail_to = $this->ini["MailTo"];
			}
		} else {
			$this->error("Configuración incorrecta (MailTo invalido)",$this->ini["ErrorDocument"]);
			return false;				
		}
		
		
		
		// Prueba superada!!
		// Comienza la construcción del correo
		return true;
	}
	
	function creaheaders($tipo){
		$headers = "";
		$headers.= "From: ".$this->correo_from.$this->br;
		$headers.= "Reply-To: ".$this->correo_from.$this->br;
		$headers.= "Bcc: ".$this->mail_to.$this->br;
		$headers.= "MIME-Version: 1.0".$this->br;
		$headers.= "X-Mailer: HumSoft Mailer".$this->br;
		$headers.= $tipo;
		return $headers;
	}
	
	// Verificador de email
	function es_email($email) {
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
	}
	
	// Procesador de errores
	function error($mensaje,$url) {
		die("<html><body><h1>Error</h1><p>".$mensaje."</p><p>Para regresar al formulario, <a href=\"".$url."\">De click aqu&iacute;</a></p></body></html>");
	}
}
?>