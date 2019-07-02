<html>
<head>
	<title>Report Viewer</title>
</head>
<body>
<input type="button" id="btngo" value="Descargar Reporte" />
</body>
<script type="text/javascript">
	btn = document.getElementById("btngo");
	btn.onclick = function() {
		window.location.href = "http://servicios.rhglobal.com.mx/reporteadmin/view/" + <?php echo $_GET["candid"]; ?> + ".pdf";
	}
</script>
</html>