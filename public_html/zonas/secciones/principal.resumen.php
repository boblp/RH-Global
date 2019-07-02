<h1>Resumen de Operaciones <?php echo $usyr; ?></h1>
<?php
$zonas_sup = $bd->query_arr("select a.nombre, a.zona_id from zonas a left join s2z b using(zona_id) where b.supervisor_id=".$usr->idu);

if(!$zonas_sup) {
	echo "<p>No existen grupos asignados a tu cuenta.</p>";
} else {
	$comzn = (count($zonas_sup)>1) ? " cada una de las ".count($zonas_sup)." zonas a tu cargo" : " la zona a tu cargo";
	echo "<p>A continuación se muestra el resumen para".$comzn."</p>\n";
	foreach($zonas_sup as $zs) {
		echo "<h2>".$zs["nombre"]."</h2>\n";
		echo "<div class=\"zres\">";
		if($bd->contar("z2u","zona_id=".$zs["zona_id"])) {
			echo "<h3>Consolidado Anual</h3>";
			$datos_zona = $bd->query_row("select sum(if(c.resultado=1,1,0)) as aptos, sum(if(c.resultado=2,1,0)) as aptoscr, sum(if(c.resultado=3,1,0)) as noaptos, sum(if(c.estatus&6,1,0)) as proceso, count(candidato_id) as total from z2u b  LEFT join candidatos c using(usuario_id) where b.zona_id=".$zs["zona_id"]." and (date_format(c.tsfin,'%Y')='".$usyr."' or c.candidato_id is null or c.estatus&6) group by zona_id");
			echo "<div class=\"izq datos\">";
			echo "<p class=\"dathead\"><strong>Concepto</strong> Cantidad</p>";
			echo "<p><strong>Total de Candidatos:</strong> ".$datos_zona["total"]."</p>";
			echo "<p><strong>Aptos:</strong> ".$datos_zona["aptos"]."</p>";
			echo "<p><strong>Aptos con Reservas:</strong> ".$datos_zona["aptoscr"]."</p>";
			echo "<p><strong>No Aptos:</strong> ".$datos_zona["noaptos"]."</p>";
			echo "</div>";
			echo "<div class=\"der chartholder ch-w-450 ch-h-250 ch-t-portipo ch-v-ca_".$datos_zona["aptos"].".cr_".$datos_zona["aptoscr"].".na_".$datos_zona["noaptos"]."\"></div>";
			echo "<div class=\"separa\"></div>";
			echo "<h3>Consolidado por Cuenta</h3>";
			$ctas_zona = $bd->query_arr("select a.nombre, a.usuario_id,sum(if(c.resultado=1,1,0)) as aptos, sum(if(c.resultado=2,1,0)) as aptoscr,sum(if(c.resultado=3,1,0)) as noaptos,sum(if(c.estatus&6,1,0)) as proceso,count(candidato_id) as total,if(c.usuario_id is null,'nulo','activo') as checa from usuarios a left join z2u b using(usuario_id) LEFT join candidatos c using(usuario_id) where b.zona_id=".$zs["zona_id"]." and (date_format(c.tsfin,'%Y')='".$usyr."' or c.candidato_id is null or c.estatus&6) group by usuario_id order by a.nombre");
			echo "<div class=\"izq datos\">";
			echo "<p class=\"dathead\"><strong>Cuenta</strong> Total</p>";
			foreach($ctas_zona as $cz) {
				echo "<p><strong>".$cz["nombre"].":</strong> ".$cz["total"]."</p>";
			}
			echo "</div>";
			/* AQUI CONTINUAR, SOLO FALTA AGREGAR EL XML Y CREAR LAS FILAS (USAR MISMO QUERY) */
			echo "<div class=\"der chartholder ch-w-450 ch-h-250 ch-t-porcta ch-v-y_".$usyr.".z_".$zs["zona_id"]."\"></div>";
		} else {
			echo "<p>No existen cuentas asignadas a esta zona.</p>";
		}
		echo "<div class=\"nobreak\"></div>";
		echo "</div>\n";
	}
}
?>
<?php
//
?>