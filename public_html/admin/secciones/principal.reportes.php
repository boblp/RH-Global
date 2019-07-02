<h3>Estados de Cuenta</h3>
<div class="muestraform">
<form name="grep" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
<fieldset>
	<legend>Periodo</legend>
	<p>Desde el día <input type="text" class="format-y-m-d divider-dash range-low-<?php echo $minfecha; ?> range-high-<?php echo date("Y-m-d"); ?>" id="finicio" name="finicio" value="<?php echo date("Y-m-d",mktime(0,0,0,date("m"),1)); ?>" maxlength="10" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hasta el día <input type="text" class="format-y-m-d divider-dash range-low-<?php echo $minfecha; ?> range-high-<?php echo date("Y-m-d"); ?>" id="ffin" name="ffin" value="<?php echo date("Y-m-d"); ?>" maxlength="10" /></p>
</fieldset>
<fieldset>
	<legend>Cliente</legend>
	<p>
	<span> Selecciona un Cliente 
		<select name="cliente" id="cliente">
		<option value="0">--</option>
			<?php
			$clienteslist = $bd->query_arr("select cliente_id,nombre from clientes order by nombre");
			foreach($clienteslist as $zs) {
				echo "<option value=\"".$zs["cliente_id"]."\">".$zs["nombre"]."</option>";
			}
			?>
		</select>
		<?php 
		$hoy = date("Ymd",mktime(0,0,0));
		$tid = randchain(5,10);
		$edcver = str_rot13(md5($tid.$hoy));
		?>
	</span><br />
	 <input type="checkbox" id="nofact" name="nofact" value="1" /> Solo NO Facturados &nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" id="factura" name="factura" value="1" /> Procesar como factura
	</p>
</fieldset>
<br /><input type="button" value="Generar" onclick="getedc('<?php echo $tid; ?>','<?php echo $edcver; ?>');" />
</form>
</div>

<br /><hr />
<?php
$epquery = "select a.candidato_id as folio, b.nombre as usuario, c.nombre as cliente, a.nombre as candidato, DATE_FORMAT(tsinicio, '%d-%m-%Y') as fecha, empleos_op as operid, d.nombre as operador from candidatos a left join usuarios b on a.usuario_id=b.usuario_id left join clientes c on b.cliente_id=c.cliente_id left join operadores d on a.empleos_op=d.operador_id where a.estatus=2 and a.empleos_op is not null";
$eplista = $bd->query_arr($epquery);
?>
<h3>Candidatos en Proceso</h3>
<table class="eplista" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>Folio</th>
			<th>Cliente</th>
			<th>Usuario</th>
			<th>Nombre</th>
			<th>Fecha/Hora</th>
			<th>Operador</th>
		</tr>
	</thead>
	<tbody id="eplista">
	<?php
	foreach($eplista as $epl) {
		$epclass=($epclass==" class=\"even\"") ? " class=\"odd\"" : " class=\"even\"";
	?>
		<tr<?php echo $epclass; ?>>
			<td><?php echo htmlentities($epl["folio"]); ?></td>
			<td><?php echo htmlentities($epl["cliente"]); ?></td>
			<td><?php echo htmlentities($epl["usuario"]); ?></td>
			<td><?php echo htmlentities($epl["candidato"]); ?></td>
			<td><?php echo htmlentities($epl["fecha"]); ?></td>
			<td><span id="chng_<?php echo $epl["folio"]; ?>_<?php echo $epl["operid"]; ?>" class="allowchange"><?php echo htmlentities(ucwords(strtolower($epl["operador"]))); ?></span></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>