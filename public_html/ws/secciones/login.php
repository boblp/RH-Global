<div id="loginbox">
	<div id="header">
		<h1>Administrador</h1>
	</div>
	<div id="cuerpo">
		<form id="ingreso" name="ingreso" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
		<table cellspacing="5" cellpadding="0">
			<tbody>
				<?php
				if ($usr->error) {
				?>
				<tr>
					<td colspan="3"><label>Error en datos de ingreso</label></td>				
				</tr>
				<?php
				}
				?>
				<tr>
				<td><label for="loginusr">Usuario: </label></td> 
				<td><label for="loginpwd">Password: </label></td> 
				</tr>
				<tr>
				<td><input type="text" name="loginusr" id="loginusr" size="13" value="" /></td>
				<td><input type="password" name="loginpwd" id="loginpwd" size="13" value="" /></td>
				</tr>
				<tr>
				<td colspan="3"><input type="button" id="botonsubmit" onclick="alerta();" value="Ingresar al Sistema" /></td>				
				</tr>
			</tbody>
		</table>
		</form>
		<noscript>
			<p style="color:#ffffff;margin-top:30px;">ERROR: Esta aplicaci&oacute;n requiere Javascript y un navegador de ultima generación</p>				
		</noscript>
	</div>
</div>