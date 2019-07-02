<?php
$frm_socio = <<<HTML
	<div class="editform">
		<form id="frmsocio">
			<p class="input"><label class="block" for="empresa">Departamento</label><input type="text" id="depto" name="depto" value="{$socio["depto"]}" /></p>
			<p class="input"><label class="block" for="contacto">Contacto</label><input type="text" id="contacto" name="contacto" value="{$socio["contacto"]}" /></p>
			<p class="input"><label class="block" for="email">Email</label><input type="text" id="email" name="email" value="{$socio["email"]}" /></p>
			<p class="input"><label class="block" for="usuario">Usuario</label><input type="text" id="usuario" name="usuario" value="{$socio["usuario"]}" /></p>
			<p class="input"><label class="block" for="pass">Password</label><input type="text" id="pass" name="pass" value="{$socio["pass"]}" /></p>
			<p><label class="inline" for="statusgral">Estatus</label>
				<select name="statusgral" id="statusgral">
					<option value="1">Activo</option>
					<option value="0"{$socio["inactivo"]}>Inactivo</option>
				</select>
			</p>
			<p><input type="hidden" name="socioid" id="socioid" value="{$socio["id"]}" /><input type="hidden" name="tipo" id="tipo" value="{$socio["tipo"]}" /><input type="button" value="Guardar" onclick="doSave(this.form);" /></p>
		</form>
	</div>
HTML;
?>