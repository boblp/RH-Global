				<div id="listadofull">
					<h3>Candidatos en proceso de investigación</h3>
					<table id="tlista" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th>Folio</th><th class="tlnombre">Nombre</th><th>Inicio del proceso</th><th>Solicitó</th>
						</tr>
						</thead>
						<tbody id="tlb">
						<?php
						$lpendientes = $bd->query_obj("select candidato_id as cid, nombre as nom, date_format(tsinicio,'%Y-%m-%d') as fini, solpor from candidatos where estatus & 6 and usuario_id=".$usr->idu." order by tsinicio");
						$contlp = 0;
						$filalp = "";
						while($lpf = $lpendientes->fetch("array")) {
							$filalp = ($filalp=="even") ? "odd" : "even";
							$fechaip = date_es("d \de F \de Y",$lpf["fini"]);
						?>
						<tr class="<?php echo $filalp; ?>">
							<td><?php echo $lpf["cid"]; ?></td><td><?php echo $lpf["nom"]; ?></td><td><?php echo $fechaip; ?></td><td><?php echo $lpf["solpor"]; ?></td>
						</tr>
						<?php
							$contlp++;
						}
						if ($contlp == 0) {
						?>
						<tr class="even">
							<td colspan="4">No hay candidatos en proceso<br /></td>
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>