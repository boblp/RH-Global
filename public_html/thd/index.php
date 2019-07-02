<?php
require ("framework.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Bolsa de Trabajo THD M&eacute;xico</title>
	<link rel="stylesheet" type="text/css" href="/interface/styles.css">
	<script type="text/javascript" src="/js/thd.js"></script>
</head>
<body>
<div id="container">
<?php
if($_SESSION["bdtlogin"]) {
?>
	<form name="mainform" id="mainform" action="/" method="post">
	<div id="header">
		<h1><span>Home Depot M&eacute;xico, Bolsa de trabajo</span></h1>
		<div id="searchform">
			<label for="srchword">Palabra Clave: </label> <input size="15" type="text" name="srchword" value="<?php echo $_SESSION["bdts"]["srchword"]; ?>" />
			<a id="srchbutton" href="javascript:dosearch();" title="Buscar"><span>Buscar</span></a>
		</div>
	</div>
	<div id="filters">
		<div id="filterzone">
		<?php echo bdt_makeform(); ?>
		</div>
	</div>
	<div id="filtertoggle"></div>
	</form>
<?php
} else {
?>
	<form name="mainform" id="mainform" action="/" method="post">
	<div id="header">
		<h1><span>Home Depot M&eacute;xico, Bolsa de trabajo</span></h1>
	</div>
	<div id="loginform">
		<p class="loginnote">Este es un servicio exclusivo para el departamento de recursos humanos de Home Depot México, y es de carácter confidencial. Para acceder al sistema de búsqueda es necesario que ingreses tu usuario y password.</p>
		<div id="loginarea">
			<?php if($errorloginbdt) {
					echo '<p class="loginerror">ERROR: Los datos proporcionados no conciden con ninguna cuenta de registro, favor de verificarlo e intentarlo nuevamente</p>';
			} ?>
			<p class="loginfields">
				<table cellpadding="0" cellspacing="5" width="50%">
					<tr>
						<td><label for="user">Usuario</label></td>
						<td><input type="text" name="user" id="user" value="" size="15" /></td>
					</tr>
					<tr>
						<td><label for="pwd">Password</label></td>
						<td><input type="password" name="pwd" id="pwd" value="" size="15" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Ingresar" /></td>
					</tr>
				</table>
			</p>
		</div>
	</div>
	</form>
<?php
}
?>
<div id="result">
<?php echo bdt_makeresult($_GET["p"]); ?>
</div>
<div id="footer"></div>
</div>
</body>
</html>