<h2>Agregar Candidato</h2>
<?php
if($_SESSION["saved"]) {
	echo "<p id=\"oksaved\">El candidato ".$_SESSION["saved"]." se ha guardado exitosamente <strong>(FOLIO: ".$_SESSION["savedid"].")</strong></p>";
	$_SESSION["saved"] = "";
	$_SESSION["savedid"] = "";
}
?>
<form name="ncand" id="ncand" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<h3>Datos Generales</h3>
<table cellpadding="0" cellspacing="0" class="forma">	
	<tbody>
		<tr>
		<td>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Nombre Completo *</td>
					<td class="label">Sexo *</td>
				</tr>
				<tr>
					<td class="field primero"><input type="text" class="textpand" size="80" id="nombre" name="nombre" value="" /></td>
					<td class="field"><input type="radio" id="sexom" name="sexo" value="m" /> Masc <input type="radio" id="sexof" name="sexo" value="f" /> Fem</td>
				</tr>
				</tbody>
			</table>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Fecha de Nacimiento *</td>
					<td class="label">Lugar de Nacimiento</td>
					<td class="label">Estado Civil *</td>
				</tr>
				<tr>
					<td class="field primero">
						<select class="ffs" name="fnyr" id="fnyr"><option value="No">Año</option>
							<?php
							for($i=(date("Y")-60);$i<(date("Y")-15);$i++) {
								echo "<option value=\"".$i."\">".$i."</option>";
							}
							?>
						</select>
						<select class="ffs" name="fnmo" id="fnmo"><option value="No">Mes</option>
							<?php
							$meses = array("","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
							for($i=1;$i<=12;$i++) {
								$im =str_pad($i,2, "0", STR_PAD_LEFT);
								echo "<option value=\"".$im."\">".$meses[$i]."</option>";
							}
							?>
						</select>
						<select class="ffs" name="fndy" id="fndy"><option value="No">Día</option>
							<?php
							for($i=1;$i<=31;$i++) {
								$im =str_pad($i,2, "0", STR_PAD_LEFT);
								echo "<option value=\"".$i."\">".$im."</option>";
							}
							?>
						</select>
						<input type="hidden" name="fnac" id="fnac" value="" />
					</td>
					<td class="field"><input type="text" class="textpand" size="40" id="lnac" name="lnac" value="" /></td>
					<td class="field">
						<select name="ecivil" id="ecivil">
							<option value="No">--</option>
							<option value="Soltero(a)">Soltero(a)</option>
							<option value="Casado(a)">Casado(a)</option>
							<option value="Divorciado(a)">Divorciado(a)</option>
							<option value="Viudo(a)">Viudo(a)</option>
							<option value="Unión Libre">Unión Libre</option>
						</select>
					</td>
				</tr>
				</tbody>
			</table>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Detalles</td>
				</tr>
				<tr>
					<td class="field primero">
					ID Universidad: <input type="text" class="textpand" size="5" id="univ_id" name="univ_id" value="" />&nbsp;&nbsp;&nbsp;&nbsp;
					ID Carrera: <input type="text" class="textpand" size="5" id="carrera_id" name="carrera_id" value="" />
					<!--
						<input type="checkbox" name="turnos" id="turnos" value="1" /> Disp. para trabajar por turnos. &nbsp;
						<input type="checkbox" name="viajar" id="viajar" value="1" /> Disp. para viajar. &nbsp;
						<input type="checkbox" name="licencia" id="licencia" value="1" /> Licencia vigente. &nbsp;
						<input type="checkbox" name="autopropio" id="autopropio" value="1" /> Auto propio. &nbsp;
					-->
					</td>
				</tr>
				</tbody>
			</table>
		</td>
		</tr>
	</tbody>
</table>

<h3>Datos de Contacto</h3>
<table cellpadding="0" cellspacing="0" class="forma">	
	<tbody>
		<tr>
		<td>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Domicilio *</td>
					<td class="label">Ciudad *</td>
					<td class="label">Zona *</td>
				</tr>
				<tr>
					<td class="field primero"><input type="text" class="textpand" size="45" id="domicilio" name="domicilio" value="" /></td>
					<td class="field"><input type="text" class="textpand" size="20" id="ciudad" name="ciudad" value="" /></td>
					<td class="field">
						<select name="zona" id="zona">
							<option value="No">--</option>
							<option value="Poniente">Poniente</option>
							<option value="Oriente">Oriente</option>
							<option value="Centro">Centro</option>
							<option value="Norte">Norte</option>
							<option value="Sur">Sur</option>
							<option value="Otra">Otra</option>
							
						</select>
					</td>
				</tr>
				</tbody>
			</table>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Teléfono *</td>
					<td class="label">email</td>
				</tr>
				<tr>
					<td class="field primero"><input type="text" class="textpand" size="30" id="telefono" name="telefono" value="" /></td>
					<td class="field"><input type="text" class="textpand" size="60" id="email" name="email" value="" /></td>
				</tr>
				</tbody>
			</table>
		</td>
		</tr>
	</tbody>
</table>

<h3>Escolaridad</h3>
<table cellpadding="0" cellspacing="0" class="forma">	
	<tbody>
		<tr>
		<td>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Máximo nivel de estudios *</td>
					<td class="label">Inglés</td>
					<td class="label">Otros Idiomas</td>
				</tr>
				<tr>
					<td class="field primero">
						<select name="niveled" id="niveled">
							<option value="No">-- Selecciona una Opción --</option>
							<option value="Sin Estudios">Sin Estudios</option>
							<option value="Primaria">Primaria</option>
							<option value="Secundaria">Secundaria</option>
							<option value="Preparatoria / Bachillerato">Preparatoria / Bachillerato</option>
							<option value="Carrera Comercial">Carrera Comercial</option>
							<option value="Carrera Técnica">Carrera Técnica</option>
							<option value="Universidad Inconclusa">Universidad Inconclusa</option>
							<option value="Universidad (Titulado)">Universidad (Titulado)</option>
							<option value="Postgrado">Postgrado</option>
						</select>
					</td>
					<td class="field">
						<select name="ingles" id="ingles">
							<option value="0">0% - Desconocido</option>
							<option value="30">30% - Básico</option>
							<option value="50">50% - Regular</option>
							<option value="70">70% - Bueno</option>
							<option value="90">90% - Excelente</option>
							<option value="100">100% - Nativo</option>
						</select>
					</td>
					<td class="field"><input type="text" class="textpand" size="25" id="idiomas" name="idiomas" value="" /></td>
				</tr>
				</tbody>
			</table>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Historial Académico</td>
				</tr>
				<tr>
					<td class="field primero">
						<div class="temphold" id="holdesc">

						</div>
						<fieldset class="ffs">
							<p class="miniform">
								De: <input class="mf_e" type="text" id="tm_ede" size="5" /> a <input class="mf_e" type="text" id="tm_ea" size="5" /> &nbsp;&nbsp;&nbsp;&nbsp;
								Institución: <input class="mf_e" type="text" id="tm_ein" size="40" /><br />
								Detalles: <input class="mf_e" type="text" id="tm_edt" size="50" /> <input type="button" value="Agregar" id="tm_eadd" />
							</p>
						</fieldset>
						<input type="hidden" name="histed" id="histed" value="" />
					</td>
				</tr>
				</tbody>
			</table>
		</td>
		</tr>
	</tbody>
</table>

<h3>Experiencia</h3>
<table cellpadding="0" cellspacing="0" class="forma">	
	<tbody>
		<tr>
		<td>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Experiencia Laboral</td>
				</tr>
				<tr>
					<td class="field primero">
						<div class="temphold" id="holdlab">

						</div>
						<fieldset class="ffs">
							<p class="miniform">
								De: <input class="mf_l" type="text" id="tm_lde" size="5" /> a <input class="mf_l" type="text" id="tm_la" size="5" />  &nbsp;&nbsp;&nbsp;&nbsp;
								Empresa: <input class="mf_l" type="text" id="tm_lem" size="30" /> Puesto: <input class="mf_l" type="text" id="tm_lpu" size="15" /><br />
								Detalles: <input class="mf_l" type="text" id="tm_ldt" size="50" /> <input type="button" value="Agregar" id="tm_ladd" />
							</p>
						</fieldset>
						<input type="hidden" name="histlab" id="histlab" value="" />
					</td>
				</tr>
				</tbody>
			</table>
		</td>
		</tr>
	</tbody>
</table>

<h3>Guardar</h3>
<table cellpadding="0" cellspacing="0" class="forma">	
	<tbody>
		<tr>
		<td>
			<table cellpadding="2" cellspacing="0" class="subforma">
				<tbody>
				<tr>
					<td class="label primero">Palabras Clave *</td>
					<td class="label">&nbsp;</td>
				</tr>
				<tr>
					<td class="field primero"><input type="text" class="textpand" size="45" id="keywords" name="keywords" value="" /></td>
					<td class="field">
						<input type="button" id="saveform" value="Guardar" />
						<input type="hidden" name="ppc" value="bdt.addresume" />
					</td>
				</tr>
				</tbody>
			</table>
		</td>
		</tr>
	</tbody>
</table>
</form>