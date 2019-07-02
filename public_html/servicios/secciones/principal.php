  <div id="main">
    <div id="encabezado">
      <ul id="navtop">
        <li><a<?php if($_GET["base"]=="nuevos") { echo " class=\"activo\"";} ?> href="/nuevos"><span>Nuevos</span></a></li>
        <li><a<?php if($_GET["base"]=="proceso") { echo " class=\"activo\"";} ?> href="/proceso"><span>En Proceso</span></a></li>
        <li><a<?php if($_GET["base"]=="terminados") { echo " class=\"activo\"";} ?> href="/terminados"><span>Terminados</span></a></li>
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

