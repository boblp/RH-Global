<?php
header("Content-type: text/xml;charset=utf-8");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache"); 
//$debug = fopen("debug.txt","a+");
if($xml->procesar()) {
	$x = $xml->request;
	//fwrite($debug,print_r($x,true). "\n");
	$tieneError = false;
	$afectados = 0;
	switch($x["main"]["accion"]) {
		case "login":
		$frm_user = $x["vars"]["usr"];
		$frm_pass = $x["vars"]["pwd"];
		$frm_guid = $x["vars"]["guid"];
		$usuario = $bd->query_row("select * from operadores where login='".$frm_user."' and estatus=1");
		if(count($usuario)) {
			if(md5($usuario["pass"].$frm_guid)==$frm_pass) {
				$xml_resultado = "<codigo>1</codigo><mensaje>Login Correcto</mensaje>";
				$xml_contenido.= "<usrid>".$usuario["operador_id"]."</usrid>";
				$xml_contenido.= "<nombre>".$usuario["nombre"]."</nombre>";
				$xml_contenido.= "<nivel>".$usuario["nivel"]."</nivel>";
			} else {
				$xml_resultado = "<codigo>2</codigo><mensaje>Contraseña incorrecta</mensaje>";
			}
		} else {
			$xml_resultado = "<codigo>2</codigo><mensaje>Usuario desconocido</mensaje>";
		}
		break;
		
		case "rs":
		$v = $x["vars"];
		$rslist = array(
			"lista_candidatos_pendientes"=>"select a.nombre, a.candidato_id as cid, if(extract(hour from tsinicio)>extract(hour from now()),(((dayofyear(now())-(dayofyear(tsinicio)+1))*24)+24-(extract(hour from tsinicio)-extract(hour from now()))),(((dayofyear(now())-dayofyear(tsinicio))*24)+(extract(hour from now())-extract(hour from tsinicio)))) as horas, concat(c.nombre,\": \",b.nombre) as cte, count(d.empleo_id) as emp from candidatos a left join usuarios b on a.usuario_id=b.usuario_id  left join clientes c on b.cliente_id=c.cliente_id left join empleos d on a.candidato_id=d.candidato_id where a.estatus & 2 and (a.empleos_op is null or a.empleos_op=0) group by a.candidato_id order by tsinicio",
			"lista_candidatos_asignados"=>"select a.nombre, a.candidato_id as cid, if(extract(hour from tsinicio)>extract(hour from now()),(((dayofyear(now())-(dayofyear(tsinicio)+1))*24)+24-(extract(hour from tsinicio)-extract(hour from now()))),(((dayofyear(now())-dayofyear(tsinicio))*24)+(extract(hour from now())-extract(hour from tsinicio)))) as horas, concat(c.nombre,\": \",b.nombre) as cte, count(d.empleo_id) as emp from candidatos a left join usuarios b on a.usuario_id=b.usuario_id  left join clientes c on b.cliente_id=c.cliente_id left join empleos d on a.candidato_id=d.candidato_id where a.estatus & 2 and a.empleos_op=".$v["usrid"]." group by a.candidato_id order by tsinicio",
			"lista_candidatos_fin"=>"select a.nombre, a.candidato_id as cid, if(extract(hour from tsinicio)>extract(hour from now()),(((dayofyear(now())-(dayofyear(tsinicio)+1))*24)+24-(extract(hour from tsinicio)-extract(hour from now()))),(((dayofyear(now())-dayofyear(tsinicio))*24)+(extract(hour from now())-extract(hour from tsinicio)))) as horas, concat(c.nombre,\": \",b.nombre) as cte, count(d.empleo_id) as emp from candidatos a left join usuarios b on a.usuario_id=b.usuario_id  left join clientes c on b.cliente_id=c.cliente_id left join empleos d on a.candidato_id=d.candidato_id where a.estatus & 4 group by a.candidato_id order by tsinicio",
			"detalle_datos_candidato"=>"select * from candidatos where candidato_id=".$v["candid"],
			"detalle_solicitante_candidato"=>"select b.nombre as usuario, c.nombre as empresa from candidatos a left join usuarios b using(usuario_id) left join clientes c using(cliente_id) where candidato_id=".$v["candid"],
			"lista_empleos_candidato"=>"select * from empleos where candidato_id=".$v["candid"]." order by orden, empleo_id",
			"detalle_empleo_candidato"=>"select * from empleos where empleo_id=".$v["empid"]);
		if(array_key_exists($v["qr"],$rslist)) {
			$xml_resultado = "<codigo>1</codigo><mensaje>Consulta aceptada, si hay coincidencias se listarán en el contenido</mensaje>";
			$xml_contenido = $bd->query_xml($rslist[$v["qr"]]);
		} else {
			$xml_resultado = "<codigo>2</codigo><mensaje>Petición desconocida</mensaje>";
		}
		break;
		
		case "toma":
		$asignado = $bd->actualiza("update candidatos set empleos_op=".$x["vars"]["usrid"]." where candidato_id=".$x["vars"]["candid"]." and (empleos_op is null or empleos_op=0)");
		if($asignado) {
			$xml_resultado = "<codigo>1</codigo><mensaje>Candidato asignado</mensaje>";
		} else {
			$xml_resultado = "<codigo>2</codigo><mensaje>Candidato no asignado</mensaje>";
		}
		break;
		
		case "agregaemp":
		$asignado = $bd->correr("insert into empleos (candidato_id,empresa,omitido) values (".$x["vars"]["candid"].",'".$x["vars"]["empresa"]."',1)");
		if($asignado) {
			$xml_resultado = "<codigo>1</codigo><mensaje>Empleo agregado</mensaje>";
		} else {
			$xml_resultado = "<codigo>2</codigo><mensaje>Empleo no agregado</mensaje>";
		}
		break;
		
		case "run":
			// Run SQL Queries
			if(is_array($x["queries"])) {
				foreach($x["queries"] as $qk => $query) {
					$qr = $qf->update($query["tabla"],$query["datos"],$query["elemid"]);
					$afectados+= $bd->actualiza($qr);
					if(mysql_errno()) {
						$xml_debug.= "El query #".($qk+1)." {".$qr."} ha regresado este error: ".mysql_error()."\n";
						$tieneError = true;
					} else {
						if(isset($x["vars"]["tsaffect"])) {
							$bd->correr("update candidatos set tsfin=now() where candidato_id=".$query["elemid"]);
						}
					}
				}
			} else {
				$xml_debug.= "No hay queries que correr\n";
				$tieneError = true;
			}
			if($tieneError) {
				$xml_resultado = "<codigo>3</codigo><mensaje>Proceso terminado, pero hay errores. Se han afectado $afectados registros.</mensaje>";
			} else {
				$xml_resultado = "<codigo>1</codigo><mensaje>Proceso terminado. Se han afectado $afectados registros.</mensaje>";
			}
		break;
		default:
		$xml_resultado = "<codigo>2</codigo><mensaje>Acción desconocida</mensaje>";
	}
} else {
	$xml_resultado = "<codigo>2</codigo><mensaje>".$xml->parsererr."</mensaje>";
}
?>