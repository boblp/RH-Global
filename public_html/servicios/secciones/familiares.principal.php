<?php
	$_GET["folio"] = ($_GET["folio"]) ? $_GET["folio"] : "0";
	
	$contador = $bd->contar("candidatos", " candidato_id='" .$_GET["folio"] . "'");

	if($_GET["folio"] == 0 || $contador == 0){
		$resultHTML ="<tr class='even'><td colspan='4'>El Candidato no existe o no fue encontrado en la base de datos<br/></td></tr>";		
	}else{
		$candidato = $bd->query_row("Select candidato_id, nombre, telefono, familiares from candidatos where candidato_id='" .$_GET["folio"] . "'");
		
		if($candidato["familiares"] == "" || $candidato["familiares"] == null){
			$candidato["familiares"] = "Este candidato no tiene familiares.";
		}
		
		$resultHTML ="<tr class='even'><td valign='top'>". $candidato["candidato_id"] . "</td><td valign='top'>". $candidato["nombre"] . "</td><td valign='top'>". $candidato["telefono"] . "</td><td>". nl2br($candidato["familiares"]) . "</td></tr>";		
	}
	
	
	
?>

<div id="listadofull">
	<h3>Listado de Familiares de el candidato #<?php echo $_GET["folio"]; ?></h3>
	
	<table cellspacing="0" cellpadding="0" id="tlista">
		<thead>
			<tr>
				<th>Folio</th><th class="tlnombre">Nombre</th><th>Telefono</th><th>Familiares</th>
			</tr>
		</thead>
		<tbody id="tlb">
			<?php echo $resultHTML; ?>
		</tbody>
	</table>

</div>