<?php
ob_start();
session_start();
require ("constantes.php");
require ("class.bdmysql.php");

$bd = new Bdmysql;


if(!is_array($_SESSION["bdtlogin"])) {
	exit("Error, acceso denegado");
}

if(!$_GET["ver"] || !$_GET["id"]) {
	exit("Error, acceso denegado");
}



$datos = $bd->query_row("select a.*,b.nombre as uni, c.nombre as carrera from bdt a left join universidades b on(a.univ_id=b.univ_id) left join carreras c on(a.carrera_id=c.carrera_id) where statusgral=1 and folio=".$_GET["id"]);

/* --------------- Verificación de petición ------------------ */
if(!$datos) {
	exit("Error, no existe ese candidato");
}

if($_GET["ver"]!=(md5($datos["folio"].$datos["nombre"]."1"))) {
	exit("Error, debe ingresarse a traves de los enlaces de la aplicación");
}

/* -------------------Procesamiento --------------------------*/
// Marcamos la descarga para estadisticas
$bd->correr("insert into descargasbdt (usuario,candidato,fecha) values (".$_SESSION["bdtlogin"]["id"].",".$_GET["id"].",now())");
header('Content-type: application/msword');
header('Content-Disposition: attachment; filename="folio_'.$_GET["id"].'.doc"');
extract($datos);
?>
<html>
<body>
<font face="Trebuchet MS">
<small><em>Confidencial. Resumen del currículum con folio# <?php echo $_GET["id"]; ?> </em></small>
<table cellspacing="10" border="0">
<tr>
	<td width="50%"><font face="Trebuchet MS">
		<h2>Datos Generales</h2>
		<p>
			<b>Nombre:</b> <?php echo htmlentities($nombre); ?><br>
			<b>Sexo:</b> <?php echo ($sexo=="f") ? "Femenino" : "Masculino"; ?> - <b>Estado Civil:</b> <?php echo htmlentities($ecivil); ?><br>
			<b>Fecha de nacimiento:</b> <?php echo htmlentities($fnac); ?><br>
			<b>Lugar de nacimiento:</b> <?php echo htmlentities($lnac); ?><br>
		</p>
	</font></td>
	<td width="50%"><font face="Trebuchet MS">
		<h2>Datos de Contacto</h2>
		<p>
			<b>Domicilio:</b> <?php echo htmlentities($domicilio); ?><br>
			<b>Ciudad:</b> <?php echo htmlentities($ciudad); ?><br>
			<b>Teléfono:</b> <?php echo ($telefono) ? htmlentities($telefono) : "N/A"; ?><br>
			<b>Email:</b> <?php echo ($email) ? "<a href='mailto:".htmlentities($email)."'>".htmlentities($email)."</a>" : "N/A"; ?><br>
		</p>
	</font></td>
</tr>
</table>
<p>&nbsp;</p>
<h2>Escolaridad</h2>
<p>
	<b>Universidad:</b> <?php echo htmlentities($uni); ?><br>
	<b>Carrera:</b> <?php echo htmlentities($carrera); ?><br>
	<b>Máximo nivel de estudios:</b> <?php echo htmlentities($niveled); ?><br>
	<b>Dominio del idioma inglés:</b> <?php echo htmlentities($ingles); ?>%<br>
	<b>Otros Idiomas:</b> <?php echo ($idiomas) ? htmlentities($idiomas) : "No"; ?><br>
	<b>Areas de interés, otros conocimientos:</b> <?php echo htmlentities($keywords); ?><br>
</p>
<?php if($histed) { ?>
<h3>Historia escolar</h3>
<div>
	<?php echo $histed; ?>
</div>
<?php } ?>
<?php if($histlab) { ?>
<p>&nbsp;</p>
<h2>Historia Laboral</h2>
<div>
	<?php echo $histlab; ?>
</div>
<?php } ?>
</font>
</body>
</html>
<?php
ob_end_flush();
?>