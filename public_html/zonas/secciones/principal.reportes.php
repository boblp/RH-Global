<?php
$zonas_sup = $bd->query_arr("select a.nombre, a.zona_id from zonas a left join s2z b using(zona_id) where b.supervisor_id=".$usr->idu);
$zonaslist = array();
foreach($zonas_sup as $zst) {
	$zonaslist[]= $zst["zona_id"];
}
$cuentas_sup = $bd->query_arr("select a.nombre, a.usuario_id from usuarios a left join z2u b using(usuario_id) where b.zona_id in(".implode(",",$zonaslist).") order by a.nombre");
if(!$zonas_sup || !$cuentas_sup) {
	echo "<p>No existen grupos asignados a tu cuenta.</p>";
} else {
	$minfecha = $bd->query_uno("select date_format(MIN(c.tsfin),'%Y-%m-%d') as minfecha from usuarios a left join z2u b using(usuario_id) left join candidatos c using(usuario_id) where b.zona_id in(".implode(",",$zonaslist).") and c.tsfin>0 order by a.nombre");
	$datos = ($_POST) ? $_POST : array("scope"=>0,"finicio"=>date("Y-m-d",mktime(0,0,0,date("m"),1)),"ffin"=>date("Y-m-d"),"sel1"=>0,"sel2"=>0);
?>
<h1>Reportes Detallados</h1>
<?php echo ($_POST) ? "<p id=\"toggleform\" onclick=\"muestraforma();\">Mostrar Formulario</p>" : ""; ?>
<div class="<?php echo ($_POST) ? "ocultaform" : "muestraform"; ?>">
<form name="grep" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
<fieldset>
	<legend>Periodo</legend>
	<p>Desde el día <input type="text" class="format-y-m-d divider-dash range-low-<?php echo $minfecha; ?> range-high-<?php echo date("Y-m-d"); ?>" id="finicio" name="finicio" value="<?php echo $datos["finicio"]; ?>" maxlength="10" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hasta el día <input type="text" class="format-y-m-d divider-dash range-low-<?php echo $minfecha; ?> range-high-<?php echo date("Y-m-d"); ?>" id="ffin" name="ffin" value="<?php echo $datos["ffin"]; ?>" maxlength="10" /></p>
</fieldset>
<fieldset>
	<legend>Cuentas</legend>
	<p><input type="radio" name="scope" id="scope1" value="1"<?php echo ($datos["scope"]==1) ? " checked=\"checked\"" : "" ;?> />Por Zona <input type="radio" name="scope" id="scope2" value="2"<?php echo ($datos["scope"]==2) ? " checked=\"checked\"" : "" ;?> />Por Cuenta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<span id="sel1" class="togglesel"> Selecciona una Zona 
		<select name="zonas" id="fsel1">
		<option value="0">--</option>
			<?php
			foreach($zonas_sup as $zs) {
				$zsel = ($datos["zonas"]==$zs["zona_id"]) ? " selected=\"selected\"" : "" ;
				echo "<option value=\"".$zs["zona_id"]."\"".$zsel.">".$zs["nombre"]."</option>";
			}
			?>
		</select>
		<?php 
		$hoy = date("Ymd",mktime(0,0,0));
		$edcver = str_rot13(md5($usr->idu.$hoy.$usr->nom));
		?>
		 &oacute; Genera Estado de Cuenta <input type="button" value="Generar" onclick="getedc(<?php echo $usr->idu; ?>,'<?php echo $edcver; ?>');" />
	</span>
	<span id="sel2" class="togglesel"> Selecciona una Cuenta 
		<select name="cuentas" id="fsel2">
		<option value="0">--</option>
			<?php
			foreach($cuentas_sup as $cs) {
				$csel = ($datos["cuentas"]==$cs["usuario_id"]) ? " selected=\"selected\"" : "" ;
				echo "<option value=\"".$cs["usuario_id"]."\"".$csel.">".$cs["nombre"]."</option>";
			}
			?>
		</select>
	</span>
	</p>
</fieldset>
<p id="botonok"><input type="submit" value="Generar Reporte" /></p>
</form>
</div>
<?php
}
if($_POST) {
	switch($datos["scope"]) {
		case 1:
			$res = $bd->query_row("select b.zona_id,sum(if(c.resultado=1,1,0)) as aptos, sum(if(c.resultado=2,1,0)) as aptoscr,sum(if(c.resultado=3,1,0)) as noaptos,sum(if(c.estatus&6,1,0)) as proceso,count(candidato_id) as total,if(c.usuario_id is null,'nulo','activo') as checa from usuarios a left join z2u b using(usuario_id) LEFT join candidatos c using(usuario_id) where b.zona_id=".$datos["zonas"]." and ((c.tsfin BETWEEN '".$datos["finicio"]." 00:00:00' and '".$datos["ffin"]." 23:59:59') or c.candidato_id is null or c.estatus&6) group by zona_id order by a.nombre");
			$titulo = "Zona: ".$bd->query_uno("select nombre from zonas where zona_id=".$datos["zonas"]);
			$subtitulo = "Periodo del ".$datos["finicio"]." al ".$datos["ffin"];
		break;
		case 2:
			$res = $bd->query_row("select a.usuario_id,sum(if(c.resultado=1,1,0)) as aptos, sum(if(c.resultado=2,1,0)) as aptoscr,sum(if(c.resultado=3,1,0)) as noaptos,sum(if(c.estatus&6,1,0)) as proceso,count(candidato_id) as total,if(c.usuario_id is null,'nulo','activo') as checa from usuarios a left join z2u b using(usuario_id) LEFT join candidatos c using(usuario_id) where a.usuario_id=".$datos["cuentas"]." and ((c.tsfin BETWEEN '".$datos["finicio"]." 00:00:00' and '".$datos["ffin"]." 23:59:59') or c.candidato_id is null or c.estatus&6) group by usuario_id order by a.nombre");
			$titulo = "Cuenta: ".$bd->query_uno("select nombre from usuarios where usuario_id=".$datos["cuentas"]);
			$subtitulo = "Periodo del ".$datos["finicio"]." al ".$datos["ffin"];
		break;
		default:
			$res = array();
			$titulo = "Resultados Invalidos";
			$subtitulo = "ERROR";
		break;
	}
	echo "<h2>".$titulo."</h2>";
	echo "<div class=\"zres\">";
	echo "<h3>".$subtitulo."</h3>";
	echo "<div class=\"izq datos\">";
	echo "<p class=\"dathead\"><strong>Concepto</strong> Cantidad</p>";
	echo "<p><strong>Total de Candidatos:</strong> ".($res["aptos"] + $res["aptoscr"] + $res["noaptos"])."</p>";
	echo "<p><strong>Aptos:</strong> ".($res["aptos"] + 0)."</p>";
	echo "<p><strong>Aptos con Reservas:</strong> ".($res["aptoscr"] + 0)."</p>";
	echo "<p><strong>No Aptos:</strong> ".($res["noaptos"] + 0)."</p>";
	echo "</div>";
	echo "<div class=\"der chartholder ch-w-450 ch-h-250 ch-t-portipo ch-v-ca_".$res["aptos"].".cr_".$res["aptoscr"].".na_".$res["noaptos"]."\"></div>";
	echo "<div class=\"nobreak\"></div>";	
	echo "</div>";
}
?>
<div id="tools">
	<h4>Herramientas</h4>
	<p>Descargar reporte del folio: <input type="text" name="idfolio" id="idfolio" size="5" /><input type="button" value="Ver" onclick="getfolio(<?php echo $usr->idu; ?>);" /></p>
	<hr />
	<p>Para generar un estado de cuenta general de las zonas a tu cargo da click aquí</p>
</div>