				<div id="listadofull">
					<h3>Ultimos 100 Candidatos investigados</h3>
					<table id="tlista" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th>Folio</th><th class="tlnombre">Nombre</th><th>Solicitó</th><th>Inicio</th><th>Finalizado</th><th>Resultado</th><th>Reporte</th>
						</tr>
						</thead>
						<tbody id="tlb">
						<?php
						$lpendientes = $bd->query_obj("select candidato_id as cid, nombre as nom, date_format(tsinicio,'%Y-%m-%d') as fini, date_format(tsfin,'%Y-%m-%d') as ffin, resultado, solpor from candidatos where estatus=16 and usuario_id=".$usr->idu." order by tsfin desc limit 100");
						$contlp = 0;
						$filalp = "";
						while($lpf = $lpendientes->fetch("array")) {
							$filalp = ($filalp=="even") ? "odd" : "even";
							$fechaip = date_es("d/M/Y",$lpf["fini"]);
							$fechafp = date_es("d/M/Y",$lpf["ffin"]);
						?>
						<tr class="<?php echo $filalp; ?>">
							<td><?php echo $lpf["cid"]; ?></td><td><?php echo $lpf["nom"]; ?></td><td><?php echo $lpf["solpor"]; ?></td><td><?php echo $fechaip; ?></td><td><?php echo $fechafp; ?></td><td><img src="/interfase/ico_res<?php echo  $lpf["resultado"]; ?>.gif" width="80" height="20" /></td><td><a href="/reportingservices/<?php echo $lpf["cid"]; ?>.pdf" target="_blank">VER REPORTE</a></td>
						</tr>
						<?php
							$contlp++;
						}
						if ($contlp == 0) {
						?>
						<tr class="even">
							<td colspan="4">No hay candidatos nuevos<br /></td>
						</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>