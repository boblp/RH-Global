  <div id="main">
    <div id="encabezado">
      <ul id="navtop">
        <li><a<?php if($_GET["base"]=="select") { echo " class=\"activo\"";} ?> href="/select"><span>Selecciona</span></a></li>
        <li><a<?php if($_GET["base"]=="empleos") { echo " class=\"activo\"";} ?> href="/empleos"><span>Verifica Empleos</span></a></li>
        <li><a<?php if($_GET["base"]=="datos") { echo " class=\"activo\"";} ?> href="/datos"><span>Verifica Datos</span></a></li>
      </ul>
    </div>
    <div id="midbar">
      <p>OPERACION &gt; <?php echo $usr->nom; ?></p><p id="logout"><a href="/salir">Cerrar Sesi&oacute;n</a></p>
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

