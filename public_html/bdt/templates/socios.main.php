<?php
$listasocios = $bd->query_obj("select id,depto,contacto,statusgral from bdtusers");
?>
<h2>Cuentas de Acceso</h2>
<p id="addnew">Agregar nuevo registro</p>
<table class="listadomain part" id="lsocios" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>ID</th>
			<th>Departamento</th>
			<th>Contacto</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	while($r = $listasocios->fetch()) {
		$trclass = (strstr($trclass,"even")) ? "odd" : "even";
		$trclass.= ($r["statusgral"]==0) ? " inactivo" : "";
	?>
		<tr class="<?php echo $trclass; ?>">
			<td><?php echo str_pad($r["id"],3,"0",STR_PAD_LEFT); ?></td>
			<td><?php echo htmlentities($r["depto"]); ?></td>
			<td><?php echo htmlentities($r["contacto"]); ?></td>
			<td><span class="editar" title="Editar este Usuario" id="sid_<?php echo htmlentities($r["id"]); ?>">Editar</span>|<span class="borrar" title="Borrar este Usuario" id="bid_<?php echo htmlentities($r["id"]); ?>">Borrar</span></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<div id="editarea">

</div>