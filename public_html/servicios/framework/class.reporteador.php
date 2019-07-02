<?php
// Clase que extiende FPDF para obtener reportes del sistema
class Reporteador extends FPDF {
  function Header(){
    $this->Image(REPORTE_FILES.'logohead.png',8,8,40);
    $this->SetY(13);
    $this->SetFont('Arial','B',15);
    $this->SetTextColor(196);
    $this->Cell(0,6,'C O N F I D E N C I A L',0,1,'R');
    $this->SetFont('Arial','B',13);
    $this->SetTextColor(72);
    $this->Cell(0,6,'Reporte de Investigación Laboral',"B",1,'R');
    $this->Ln(5);
  }

  function Footer(){
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Página '.$this->PageNo().' de {nb}','T',0,'R');
  }

  
  // Funciones Publicas
  function rMain($cte,$usr,$sol,$folio,$nombre,$resultado,$recomendacion,$fecha) {
    $this->AddPage();
    $this->Format("b");
    $this->Cell(25,5,"Fecha:");
    $this->Format("n");
    $this->Cell(80,5,$fecha,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Cliente:");
    $this->Format("n");
    $this->Cell(80,5,$cte,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Usuario:");
    $this->Format("n");
    $this->Cell(80,5,$usr,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Solicitó:");
    $this->Format("n");
    $this->Cell(80,5,$sol,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Folio:");
    $this->Format("rojo");
    $this->Cell(80,5,$folio,0,1);
    $this->Format("negro");
    $this->Cell(25,5,"Candidato:");
    $this->Format("rojo");
    $this->Cell(80,5,$nombre,0,1);
    $this->Format();
    $this->Image(REPORTE_FILES."ico".$resultado.".png",170,30,35);
    $this->SetY(65);
    if($recomendacion!="") {
      $this->Line(10,62,205,62);
      $this->Format("b");
      $this->Cell(80,5,"Recomendación General:",0,1);
      $this->Format("n,s");
      $this->Write(4,$recomendacion);
      $this->Format();
      $this->ln(10);
    }
  }
  
  function rDatos($nombre,$domicilio,$telefonos,$lnac,$fnac) {
    $this->Format("h1");
    $this->Cell(0,5,"Datos Generales","T",1,"L",1);
    $this->ln(3);
    $this->Format();
    $this->Format("b");
    $this->Cell(25,5,"Nombre:");
    $this->Format("n");
    $this->Cell(0,5,$nombre,0,1);
    if($domicilio) {
      $this->Format("b");
      $this->Cell(25,5,"Domicilio:");
      $this->Format("n");
      $this->Cell(0,5,$domicilio,0,1);
    }
    if($telefonos) {
      $this->Format("b");
      $this->Cell(25,5,"Teléfono:");
      $this->Format("n");
      $this->Cell(0,5,$telefonos,0,1);
    }
    if($lnac) {
      $this->Format("b");
      $this->Cell(45,5,"Lugar de Nacimiento:");
      $this->Format("n");
      $this->Cell(0,5,$lnac,0,1);
    }
    if($fnac) {
      $this->Format("b");
      $this->Cell(45,5,"Fecha de  Nacimiento:");
      $this->Format("n");
      $this->Cell(0,5,$fnac,0,1);
    }
    $this->ln(5);
  }
  
  function rLista($lista,$numimss) {
    $this->Format("h1");
    $this->Cell(0,5,"Registro de Empleos en el Instituto Mexicano del Seguro Social","T",1,"L",1);
    $this->ln(3);
    $this->Format();
    $this->Format("b");
    $this->Cell(45,5,"Número de Afiliación:");
    $this->Format("n");
    $this->Cell(0,5,$numimss,0,1);
    $this->Format();
    $this->ln(3);
    $this->Format();
    $this->Format("th,b,s");
    $this->Cell(90,6,"Empresa",1,0,"L",1);
    $this->Cell(35,6,"Ingreso",1,0,"L",1);
    $this->Cell(35,6,"Baja",1,1,"L",1);
    $filas = explode("\n",$lista);
    $tipofila = "";
    foreach($filas as $fila) {
      $tipofila = ($tipofila=="tr1") ? "tr2" : "tr1";
      $dt = explode("|",$fila);
      $empresa = $dt[0];
      $fini = explode("/",$dt[1]);
      $fini = (count($fini)==3 && strlen($fini[0])==4) ? implode("/",array_reverse($fini)) : implode("/",$fini) ;
      $ffin = explode("/",$dt[2]);
      $ffin = (count($ffin)==3 && strlen($ffin[0])==4) ? implode("/",array_reverse($ffin)) : implode("/",$ffin) ;
      $this->Format($tipofila.",n,s");
      $this->Cell(90,6,$empresa,1,0,"L",1);
      $this->Cell(35,6,$fini,1,0,"L",1);
      $this->Cell(35,6,$ffin,1,1,"L",1);
    }
    $this->Format();
    $this->ln(5);
  }
  
  function rJud($datos) {
    $this->Format("h1");
    $this->Cell(0,5,"Procesos Judiciales donde el Candidato aparece involucrado","T",1,"L",1);
    $this->ln(3);
    $this->Format();
    $this->Format("n,s");
    $this->Write(4,$datos);
    $this->ln(7);
    $this->Format("n,xs,i");
    $this->Write(3,"Nota:  La presente indagatoria  se llevo a cabo en:  Juntas de Conciliación y Arbitraje Estatales y Federales , Agencias del Ministerio Publico del Fuero Común  y Federal, así como en Juzgados Penales, Familiares con Competencia Estatal y Federal");
    $this->ln(10);
    $this->Format();
  }
  
  function rSind($datos) {
    $this->Format("h1");
    $this->Cell(0,5,"Nexos Sindicales del Candidato","T",1,"L",1);
    $this->ln(3);
    $this->Format();
    $this->Format("n,s");
    $this->Write(4,$datos);
    $this->ln(10);
    $this->Format();
  }
  
  function IniciaEmp($folio) {
    $this->AddPage();
    $this->Format("h1,xg");
    $this->Cell(0,8,"Verificación de Referencias Laborales - Folio: ".$folio,"T",1,"L",1);
    $this->ln(10);
    $this->Format();
  }
  
  function rEmp($empleos,$folio) {
    foreach($empleos as $empleo) {
      if(($this->GetY()+50)>250) {
        $this->IniciaEmp($folio);
      }
      $this->Format("tr2,r,b");
      $this->Cell(125,6,$empleo["empresa"],"T",0,"L",1);
      $this->Format("tr2,s,n");
      $this->Cell(35,6,"De: ".$empleo["fechaing"],"T",0,"L",1);
      $this->Cell(0,6,"A: ".$empleo["fechabaja"],"T",1,"L",1);
      if($empleo["omitido"]) {
        $this->Format("alerta");
        $this->Cell(0,4,"Este empleo fué omitido de la solicitud original","T",1,"L",1);
      }
      if($empleo["difiere"]) {
        $this->Format("alerta2");
        $this->Cell(0,4,"Los datos de este empleo difieren de los proporcionados originalmente","T",1,"L",1);
      }
      $this->ln(3);
      $this->Format();
      $this->Format("s,b");
      $this->Cell(25,4,"Teléfonos: ",0,0);
      $this->Format("n");
      $this->Cell(55,4,"(".$empleo["lada"].") ".$empleo["telefonos"],"B",0);
      $this->Format("b");
      $this->Cell(25,4,"Jefe Directo: ",0,0,"R");
      $this->Format("n");
      $this->Cell(0,4,$empleo["jefe"],"B",0);
      $this->ln(6);
      $this->Format("b");
      $this->Cell(30,4,"Puesto: ",0,0,"L");
      $this->Format("n");
      $this->Cell(0,4,$empleo["puesto"],"B",0);
      $this->ln(6);
      $this->Format("b");
      $this->Cell(30,4,"Motivo de Baja: ",0,0,"L");
      $this->Format("n");
      $this->Cell(0,4,$empleo["motivobaja"],"B",0);
      $this->ln(6);
      $this->Format("b");
      $this->Cell(30,4,"COMENTARIOS: ",0,1,"L");
      $this->Format("n,i");
      $this->Write(4,$empleo["comentarios"]);
      $this->ln(6);
      
      $this->ln(8);
    }
  }
  
}



// Inicialización del reporte
$r = new Reporteador('P','mm',"Letter");
$r->AliasNbPages();
$r->SetAuthor("RH Global");
$r->SetCreator("Sistema RH Global - Creado por [Manolo Guerrero]");
$r->SetTitle("Reporte de Investigación Laboral - RH Global");
$r->SetDisplayMode("real","continuous");
$r->Format();
?>