				<div id="listado">
					<h3>Listado</h3>
					<table id="tlista" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
							<th>Folio</th><th class="tlnombre">Nombre</th><th>Empleos</th><th>Acci&oacute;n</th>
						</tr>
						</thead>
						<tbody id="tlb">
						<tr class="even">
							<td colspan="4">No hay candidatos nuevos<br /></td>
						</tr>
						</tbody>
						<tfoot>
							<tr><td colspan="4" class="tagrega"> <input type="button" value=" Agregar Candidato " class="fbot" onclick="agregacand();" /></td></tr>
						</tfoot>
					</table>
				</div>
				<div id="editarea">
					<h3>Area de edici&oacute;n</h3>
					<form name="editar" id="editar" method="post" action="<?php echo $_SERVER["request_URI"]; ?>">
					<fieldset class="seccionedit" id="f1">
						<legend id="f1leg">Titulo Edicion</legend><br />
						<p>
							<label for="f1nom">Nombre del candidato:</label>
							<input type="text" class="ftxt" id="f1nom" name="f1nom" size="20" value="" />
						</p>
						<p>
							<label for="f1sol">Solicitado por:</label>
							<input type="text" class="ftxt" id="f1sol" name="f1sol" size="20" value="" />
						</p>
						<p>
							<label for="f1dom">Domicilio:</label>
							<input type="text" class="ftxt" id="f1dom" name="f1dom" size="20" value="" />
						</p>
						<p>
							<label for="f1ciu">Ciudad:</label>
							<input type="text" class="ftxt2" id="f1ciu" name="f1ciu" size="20" value="" />
						</p>
						<p>
							<label for="f1tel">Tel&eacute;fonos:</label>
							<input type="text" class="ftxt3" id="f1tel" name="f1tel" size="20" value="" /> 
							Rec: <input type="text" class="ftxt3" id="f1telr" name="f1telr" size="20" value="" />
						</p>
						<p>
							<label for="f1lnac">Lugar de nacimiento:</label>
							<input type="text" class="ftxt" id="f1lnac" name="f1lnac" size="20" value="" />
						</p>
						<p>
							<label for="f1fna">Fecha de nacimiento:</label>
							AAAA: <input type="text" class="ftxt4" id="f1fna" name="f1fna" size="10" value="" /> 
							MM: <input type="text" class="ftxt5" id="f1fnm" name="f1fnm" size="5" value="" />
							DD: <input type="text" class="ftxt5" id="f1fnd" name="f1fnd" size="5" value="" />
						</p>
						<p>
							<label for="f1imss">N&uacute;mero IMSS:</label>
							<input type="text" class="ftxt" id="f1imss" name="f1imss" size="20" value="" />
						</p>
						<p>
							<label for="f1fam">Familiares:</label>
							<textarea class="ftxt" id="f1fam" name="f1fam" rows="5" cols="5"> </textarea>
						</p>						
						<p class="psub">
							<input type="hidden" name="f1id" id="f1id" value="" />
							<input type="button" value="Guardar Candidato" class="fbot" id="f1boton" /> <input type="button" value=" X " class="fbot" onclick="cierraedit();" />
						</p>
					</fieldset>
					<fieldset class="seccionedit" id="f2">
						<legend id="f2leg">Agregar Empleo</legend>
						<span id="f2nombrecan">Nombre del Candidato</span><br />
						<p>
							<label for="f2emp">Nombre de la Empresa <strong> y Sucursal</strong>:</label>
							<input type="text" class="ftxt" id="f2emp" name="f2emp" size="20" value="" />
						</p>
						<p>
							<label for="f2tel">Tel&eacute;fonos:</label>
							Lada: <input type="text" class="ftxt5" id="f2lada" name="f2lada" size="5" value="" /> Tel(s):<input type="text" class="ftxt3" id="f2tel" name="f2tel" size="20" value="" />
						</p>
						<p>
							<label for="f2pue">Puesto:</label>
							<input type="text" class="ftxt2" id="f2pue" name="f2pue" size="20" value="" /> 
						</p>
						<p>
							<label for="f2jef">Jefe directo:</label>
							<input type="text" class="ftxt2" id="f2jef" name="f2jef" size="20" value="" />
						</p>
						<p>
							<label for="f2fa">Fechas de ingreso / baja:</label>
							I: <input type="text" class="ftxt3" id="f2fa" name="f2fa" size="20" value="" />
							B: <input type="text" class="ftxt3" id="f2fb" name="f2fb" size="20" value="" />
						</p>
						<p>
							<label for="f2mot">Motivo de Baja:</label>
							<input type="text" class="ftxt" id="f2mot" name="f2mot" size="20" value="" />
						</p>
						<p class="psub">
							<input type="hidden" class="ftxthidden" value="" name="f2cid" id="f2cid" /><input type="hidden" class="ftxthidden" value="" name="f2empid" id="f2empid" />
							<input type="button" value="Guardar Empleo" class="fbot" id="f2boton" /> <input type="button" value=" X " class="fbot" onclick="cierraedit();" />
						</p>
					</fieldset>
					</form>
				</div>
