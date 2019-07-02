<?php
require ("framework.php");
include ("controlador.php");
?>
<?xml version="1.0" encoding="utf-8"?>
<response>
	<resultado>
		<?php echo $xml_resultado; ?>
	</resultado>
	<contenido>
		<?php echo $xml_contenido; ?>
	</contenido>
	<debug>
		<?php echo $xml_debug; ?>
	</debug>
</response>
<?php
ob_end_flush();
?>