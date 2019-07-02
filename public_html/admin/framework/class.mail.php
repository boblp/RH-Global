<?php
/* Clase para enviar correo HTML con contenido en texto plano alternativo
   
   Creada por Manuel Guerrero (Convergensys.com)
   Su uso y distribucion se rigen por la licencia GNU, que puede encontrarse
   en esta direccion: http://www.gnu.org/licenses/gpl.txt
   
   En resumen, su uso o modificacion estan permitidos siempre que esta leyenda
   se mantenga al principio del archivo, y que sus subsecuentes modificaciones
   incluyan una referencia a esta version original. Dichas versiones modificadas
   deberan distribuirse bajo esta misma licencia
*/

class ManoloMail {
	
	// Inicializamos las variables
	var $nl;
	var $encabezado;
	var $separador;
	var $asunto;
	var $textoDefault;
	var $msgTexto;
	var $msgHtml;
	var $msgCompuesto;
	var $_de;
	var $_para = array();
	var $_cc = array();
	var $_bcc = array();
	var $_tipo;
	var $malpex;
	
	// Constructor
	function ManoloMail($asunto="") {
		$this->asunto= ($asunto=="") ? "Desde ".$_SERVER["HTTP_HOST"] : $asunto;
		$this->separador = "=---www_convergensys_com_".md5(uniqid(rand()));
		$this->textoDefault = "Este correo ha sido enviado con formato enriquecido (HTML)";
		$this->msgTexto = $this->textoDefault;
		$this->nl="\r\n";
		$this->malpex=($_SERVER["HTTP_HOST"]=="localhost") ? true : false;
	}
	
	function checaMail($mail) {
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$mail));
	}
	
	// Agrega Remitente (Solo Email)
	function de($mail) {
		if ($this->checaMail($mail)) {
			$this->_de=$mail;
			return true;
		}
		return false;
	}
	
	// Agrega destinatarios
	function para($mail,$nombre="") {
		if ($this->checaMail($mail)) {
			$para = ($nombre=="" || $this->malpex) ? $mail : $nombre."<".$mail.">" ;
			array_push ($this->_para,$para);
			return true;
		}
		return false;
	}
	
	// Agrega destinatarios con copia
	function cc($mail,$nombre="") {
		if ($this->checaMail($mail)) {
			$cc = ($nombre=="" || $this->malpex) ? $mail : $nombre."<".$mail.">" ;
			array_push ($this->_cc,$cc);
			return true;
		}
		return false;
	}
	
	// Agrega destinatarios con copia oculta
	function bcc($mail,$nombre="") {
		if ($this->checaMail($mail)) {
			$bcc = ($nombre=="" || $this->malpex) ? $mail : $nombre."<".$mail.">" ;
			array_push ($this->_bcc,$bcc);
			return true;
		}
		return false;
	}
	
	// Ingresa el cuerpo del mensaje de texto
	function texto($msg) {
		if ($msg!="") {
			$this->msgTexto = $msg;
			return true;
		}
		return false;
	}
	
	function html($msg) {
		if ($msg!="") {
			$this->msgHtml = $msg;
			return true;
		}
		return false;
	}
	
	function comprueba() {
		if ($this->_de!="" && count($this->_para)) {
			if ($this->msgHtml!="") {
				$this->_tipo=2;
				return true;
			} elseif ($this->msgTexto!=$this->textoDefault) {
				$this->_tipo=1;
				return true;
			}
		} else {
			return false;
		}
	}
	
	function encabezados($tipo) {
		$encabezado ="MIME-Version: 1.0".$this->nl;
		$encabezado.="From: ".$this->_de.$this->nl;
		$encabezado.="Reply-To: ".$this->_de.$this->nl;
		$encabezado.=(count($this->_cc)) ? "Cc: ".implode(",",$this->_cc).$this->nl : "" ;
		$encabezado.=(count($this->_bcc)) ? "Bcc: ".implode(",",$this->_bcc).$this->nl : "" ;
		switch ($tipo) {
			case 1:
			$encabezado.="Content-Type: text/plain charset=\"iso-8859-1\"".$this->nl;
			break;
			case 2:
			$encabezado.="Content-Type: multipart/alternative; boundary=\"".$this->separador."\"".$this->nl;
			break;
		}
		$encabezado.="Content-Transfer-Encoding: 7bit".$this->nl;
		$encabezado.="X-Mailer: ManoloMail by Convergensys.com".$this->nl;
		$this->encabezado=$encabezado;
	}
	
	function enviar() {
		if ($this->comprueba()) {
			$this->encabezados($this->_tipo);
			switch ($this->_tipo) {
				case 1:
				$msg = $this->msgTexto;
				break;
				case 2:
				$msg = "--".$this->separador.$this->nl;
				$msg.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$this->nl;
				$msg.= "Content-Transfer-Encoding: 7bit".$this->nl.$this->nl;
				$msg.= $this->msgTexto.$this->nl;
				$msg.= "--".$this->separador.$this->nl;
				$msg.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$this->nl;
				$msg.= "Content-Transfer-Encoding: 7bit".$this->nl.$this->nl;
				$msg.= $this->msgHtml.$this->nl;
				$msg.= "--".$this->separador."--";
				break;
			}
			$this->msgCompuesto = $msg;
			if(mail(implode(",",$this->_para),$this->asunto,$this->msgCompuesto,$this->encabezado)) {
				return true;
			}
		} 
		return false;
	}
}
?>