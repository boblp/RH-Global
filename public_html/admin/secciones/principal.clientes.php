				<div id="listado">
					<h3>Listado de Clientes</h3>
					<table id="tlista" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th>ID</th><th class="tlnombre">Nombre del Cliente</th><th>Usuarios</th><th>Acci&oacute;n</th>
						</tr>
						</thead>
						<tbody id="tlb">
						<tr class="even">
							<td colspan="4">No Clientes en el sistema<br /></td>
						</tr>
						</tbody>
						<tfoot>
							<tr><td colspan="4" class="tagrega"> <input type="button" value=" Agregar Cliente " class="fbot" onclick="agregacte();" /></td></tr>
						</tfoot>
					</table>
				</div>
				<div id="editarea">
					<h3>Area de edici&oacute;n</h3>
					<form name="editar" id="editar" method="post" action="<?php echo $_SERVER["request_URI"]; ?>">
					<fieldset class="seccionedit" id="f1">
						<legend id="f1leg">Titulo Edicion</legend><br />
						<p>
							<label for="f1nom">Nombre del Cliente:</label>
							<input type="text" class="ftxt" id="f1nom" name="f1nom" size="20" value="" />
						</p>
						<p>
							<label for="f1est">Estado:</label>
							<select name="f1est" id="f1est"><option value="0">Inactivo</option><option value="1">Activo</option></select>
						</p>
						<p class="psub">
							<input type="hidden" name="f1id" id="f1id" value="" />
							<input type="button" value="Guardar Cliente" class="fbot" id="f1boton" /> <input type="button" value=" X " class="fbot" onclick="cierraedit();" />
						</p>
					</fieldset>
					<fieldset class="seccionedit" id="f2">
						<legend id="f2leg">Agregar Usuario</legend>
							<span id="f2nombrecte">Nombre del Cliente</span><br />
						<p>
							<label for="f2nom">Nombre de la Cuenta:</label>
							<input type="text" class="ftxt" id="f2nom" name="f2nom" size="20" value="" />
						</p>
						<p>
							<label for="f2nick">Login:</label>
							<input type="text" class="ftxt3" id="f2nick" name="f2nick" size="20" value="" />
						</p>
						<p>
							<label for="f2pass">Password:</label>
							<input type="text" class="ftxt3" id="f2pass" name="f2pass" size="20" value="" />
						</p>
						<p>
							<label for="f2mail">Email:</label>
							<input type="text" class="ftxt2" id="f2mail" name="f2mail" size="20" value="" /> 
						</p>
						<p>
							<label for="f2est">Estado:</label>
							<select name="f2est" id="f2est"><option value="0">Inactivo</option><option value="1">Activo</option></select>
						</p>
						<p class="psub">
							<input type="hidden" class="ftxthidden" value="" name="f2cid" id="f2cid" /><input type="hidden" class="ftxthidden" value="" name="f2usrid" id="f2usrid" />
							<input type="button" value="Guardar Usuario" class="fbot" id="f2boton" /> <input type="button" value=" X " class="fbot" onclick="cierraedit();" />
						</p>
					</fieldset>
					</form>
				</div>
