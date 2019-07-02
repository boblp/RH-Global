<?php
$xml = <<<HEREDOC
<?xml version="1.0" encoding="utf-8"?>
<paws>
      <respuesta>
                 <resultado id="res">{$resultado}</resultado>
                 <mensaje id="msg">{$mensaje}</mensaje>
      </respuesta>
      <contenido>
                 <item id="itm" tipo="{$itemtipo}" encoding="{$itemencoding}">{$item}</item>
      </contenido>
</paws>
HEREDOC;
?>