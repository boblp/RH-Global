  <div id="main">
    <div id="encabezado">
      <ul id="navtop">
        <li><a<?php if($_GET["base"]=="resumen") { echo " class=\"activo\"";} ?> href="/resumen"><span>Resumen</span></a></li>
        <li><a<?php if($_GET["base"]=="reportes") { echo " class=\"activo\"";} ?> href="/reportes"><span>Detalle</span></a></li>
      </ul>
    </div>
    <div id="midbar">
      <p><?php echo $usr->cte." &gt; ".$usr->nom; ?></p><p id="logout"><a href="/salir">Cerrar Sesi&oacute;n</a></p>
    </div>
    <div id="cuerpo">
      <div class="padder">
        <?php
        include($contenido["subfile"]);
        ?>
      </div>
      <div class="nobreak"></div>
    </div>
    <div id="pie">
      <p>&copy; Copyright RH Global 2006-2008 - Todos los derechos reservados.</p>
    </div>
  </div>

