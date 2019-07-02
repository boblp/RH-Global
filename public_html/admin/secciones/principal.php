  <div id="main">
    <div id="encabezado">
      <ul id="navtop">
        <li><a<?php if($_GET["base"]=="clientes") { echo " class=\"activo\"";} ?> href="/clientes"><span>Clientes</span></a></li>
        <li><a<?php if($_GET["base"]=="operadores") { echo " class=\"activo\"";} ?> href="/operadores"><span>Operadores</span></a></li>
        <li><a<?php if($_GET["base"]=="reportes") { echo " class=\"activo\"";} ?> href="/reportes"><span>Reportes</span></a></li>
      </ul>
    </div>
    <div id="midbar">
      <p>ADMIN &gt; <?php echo $usr->nom; ?></p><p id="logout"><a href="/salir">Cerrar Sesi&oacute;n</a></p>
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

