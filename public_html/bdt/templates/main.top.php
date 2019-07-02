<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Administrador de Bolsa de Trabajo</title>
	<script type="text/javascript" src="<?php echo PATH_JS; ?>jq.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo PATH_IF; ?>style.css" />
	<?php
	echo implode("",$extrahead);
	?>
</head>
<body>
	<div id="container">
		<div id="mainhead">
		<ul id="menumain">
			<li<?php echo ($_GET["base"]=="nuevo") ? " class=\"current\"" : ""; ?>><a href="/nuevo" class="nuevo"><span>Nuevo</span></a></li>
			<li<?php echo ($_GET["base"]=="lista") ? " class=\"current\"" : ""; ?>><a href="/lista" class="lista"><span>Lista</span></a></li>
			<li<?php echo ($_GET["base"]=="usuarios") ? " class=\"current\"" : ""; ?>><a href="/usuarios" class="socios"><span>Usuarios</span></a></li>
		</ul>
		</div>
		<div id="content">
			<div id="contentop"></div>
			<div id="main">