				<div id="listado">
					<h3>Listado de Operadores</h3>
					<table id="tlista" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th>ID</th><th class="tlnombre">Nombre del Operador</th><th>Acci&oacute;n</th>
						</tr>
						</thead>
						<tbody id="tlb">
						<tr class="even">
							<td colspan="4">No hay Operadores en el sistema<br /></td>
						</tr>
						</tbody>
						<tfoot>
							<tr><td colspan="4" class="tagrega"> <input type="button" value=" Agregar Operador " class="fbot" onclick="agregaoper();" /></td></tr>
						</tfoot>
					</table>
				</div>
				<div id="editarea">
					<h3>Area de edici&oacute;n</h3>
					<form name="editar" id="editar" method="post" action="<?php echo $_SERVER["request_URI"]; ?>">
					<fieldset class="seccionedit" id="f1">
						<legend id="f1leg">Titulo Edicion</legend><br />
						<p>
							<label for="f1nom">Nombre del Operador:</label>
							<input type="text" class="ftxt" id="f1nom" name="f1nom" size="20" value="" />
						</p>
						<p>
							<label for="f1nom">Login:</label>
							<input type="text" class="ftxt3" id="f1nick" name="f1nick" size="20" value="" />
						</p>
						<p>
							<label for="f1nom">Password:</label>
							<input type="text" class="ftxt3" id="f1pass" name="f1pass" size="20" value="" />
						</p>
						<p>
							<label for="f1nom">Email:</label>
							<input type="text" class="ftxt" id="f1mail" name="f1mail" size="20" value="" />
						</p>
						<p>
							<label for="f1nom">Permisos para:</label>
							<input type="checkbox" id="f1p1" name="f1p1" value="1" /> Capturar<br />
							<input type="checkbox" id="f1p2" name="f1p2" value="2" /> Supervisar<br />
							<input type="checkbox" id="f1p3" name="f1p3" value="4" /> Administrar
						</p>
						<p>
							<label for="f1est">Estado:</label>
							<select name="f1est" id="f1est"><option value="0">Inactivo</option><option value="1">Activo</option></select>
						</p>
						<p class="psub">
							<input type="hidden" name="f1id" id="f1id" value="" />
							<input type="button" value="Guardar Operador" class="fbot" id="f1boton" /> <input type="button" value=" X " class="fbot" onclick="cierraedit();" />
						</p>
					</fieldset>
					</form>
				</div>
