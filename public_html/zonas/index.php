<?php
require ("framework.php");
include ("controlador.php");
?>
<?php echo $pagina->getPagetop(); ?>
<!--[if IE]><script type="text/javascript">esIE = true;</script><![endif]-->
<?php @include($contenido["file"]); ?>
<?php echo $pagina->getPagefoot(); ?>