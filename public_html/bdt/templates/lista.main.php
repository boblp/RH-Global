<?php
$allowc = array("fechaalta","folio","nombre");
$allowo = array("a","d");
$ordero = array("a"=>"asc","d"=>"desc");
$pagesize = 25;
$total = $bd->rowcount("bdt","statusgral=1");
$pagecount = ceil($total/$pagesize);
$sortby = (in_array($_GET["c"],$allowc)) ? $_GET["c"] : "folio" ;
$sortorder = (in_array($_GET["o"],$allowo)) ? $ordero[$_GET["o"]] : "desc" ;
$hrefqs = ($_GET["o"] && $_GET["c"]) ? "?o=".$_GET["o"]."&c=".$_GET["c"] : (($_GET["o"]) ? "?o=".$_GET["o"] : (($_GET["c"]) ? "?c=".$_GET["c"] : ""));
$thispage = (is_numeric($_GET["extra"])) ? (($_GET["extra"]>$pagecount) ? $pagecount : (($_GET["extra"]<1) ? 1 : $_GET["extra"])) : 1;
$thislimit = (($thispage*$pagesize) - $pagesize).",".$pagesize;
$query = "select folio,nombre,date_format(fechaalta,'%Y-%m-%d') as falta from bdt where statusgral=1 order by $sortby $sortorder limit $thislimit";
$res = $bd->query_arr($query);
?>
<h2>Listado de Solicitudes</h2>
<table class="listadomain" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th><a href="/lista/1?c=folio&amp;<?php echo ($sortby=="folio") ? (($sortorder=="desc") ? "o=a" : "o=d") : "o=d" ; ?>">Folio</a></th>
			<th><a href="/lista/1?c=nombre&amp;<?php echo ($sortby=="nombre") ? (($sortorder=="desc") ? "o=a" : "o=d") : "o=d" ; ?>">Nombre</a></th>
			<th><a href="/lista/1?c=fechaalta&amp;<?php echo ($sortby=="fechaalta") ? (($sortorder=="desc") ? "o=a" : "o=d") : "o=d" ; ?>">Fecha Alta</a></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($res as $r) {
		$trclass = ($trclass=="even") ? "odd" : "even";
	?>
		<tr class="<?php echo $trclass; ?>">
			<td><?php echo str_pad($r["folio"],4,"0",STR_PAD_LEFT); ?></td>
			<td><?php echo htmlentities($r["nombre"]); ?></td>
			<td><?php echo htmlentities(date_es("M j, Y",$r["falta"])); ?></td>
			<td><span class="borrar" title="Eliminar este Candidato" id="bfolio_<?php echo htmlentities($r["folio"]); ?>">Borrar</span></td>
		</tr>
	<?php
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2"><p class="pager">Páginas: <strong>
			<?php
				for($i=1;$i<=$pagecount;$i++) {
					if($i==$thispage) {
						echo "<span>&nbsp;".$i."&nbsp;</span>";
					} else {
						echo "<a href=\"/lista/".$i.$hrefqs."\">&nbsp;".$i."&nbsp;</a>";
					}
				}
			?>
			</strong></td>
			<td colspan="2">Mostrando <?php echo ((($thispage*$pagesize)+1) - $pagesize)." a ".((($thispage*$pagesize) - $pagesize)+count($res))." de <strong>".$total."</strong>"; ?></td>
		</tr>
	</tfoot>
</table>
<div class="listools"> Eliminar Candidatos con m&aacute;s de 
	<select id="daystodel">
		<option value="15">15 D&iacute;as</option>
		<option value="30">30 D&iacute;as</option>
		<option value="45">45 D&iacute;as</option>
		<option selected="selected" value="60">60 D&iacute;as</option>
		<option value="75">75 D&iacute;as</option>
		<option value="90">90 D&iacute;as</option>
		<option value="105">105 D&iacute;as</option>
	</select>
	<input type="button" id="delbydates" value="Eliminar" />
</div>