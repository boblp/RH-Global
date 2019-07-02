<?php
// Clase que extiende FPDF para obtener reportes del sistema
class EDC extends FPDF {
  function Header(){
    $this->Image(REPORTE_FILES.'edclogo.png',8,8);
    $this->SetY(13);
    $this->SetFont('Arial','B',15);
    $this->SetTextColor(196);
    $this->Cell(0,6,'ESTADO DE CUENTA',0,1,'R');
    $this->SetFont('Arial','B',13);
    $this->SetTextColor(72);
    $this->Cell(0,6,'RH Global',"B",1,'R');
    $this->Ln(5);
  }

  function Footer(){
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Página '.$this->PageNo().' de {nb}','T',0,'R');
  }

  
  // Funciones Publicas
  function rMain($cuenta,$fecha,$inicio,$fin,$total) {
    $this->AddPage();
    $this->Format("b");
    $this->Cell(25,5,"Fecha:");
    $this->Format("n");
    $this->Cell(80,5,$fecha,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Cuenta:");
    $this->Format("n");
    $this->Cell(80,5,$cuenta,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Desde:");
    $this->Format("n");
    $this->Cell(80,5,$inicio,0,1);
    $this->Format("b");
    $this->Cell(25,5,"Hasta:");
    $this->Format("n");
    $this->Cell(80,5,$fin,0,1);
    $this->Format("b");
    $this->Format("rojo");
    $this->Cell(25,5,"Total:");
    $this->Format("n");
    $this->Cell(80,5,$total,0,1);
    $this->Format();
    $this->SetY(65);
    $this->Line(10,62,205,62);
  }
  
  function Fila($estilo,$cuenta,$solpor,$cand,$tsfin,$res) {
    $this->Format($estilo);
    $this->Cell(25,4,$cuenta,1,0,"L",1);
    $this->Cell(35,4,$solpor,1,0,"L",1);
    $this->Cell(80,4,$cand,1,0,"L",1);
    $this->Cell(30,4,$tsfin,1,0,"L",1);
    $this->Cell(25,4,$res,1,1,"L",1);
  }
  
  function Resumen($datos) {
    $this->AddPage();
    $this->Format("reset,xg,b");
    $this->Cell(195,10,"Resumen","B",1,"L",0);
    $this->Ln();
    $this->Format("reset,r");
    $total = 0;   
    $this->SetDrawColor(220);
    foreach($datos as $linea) {
      $this->Cell(90,6,$linea["cta"],"B",0,"L");
      $this->Cell(25,6,$linea["cant"],"B",1,"R");
      $total+=$linea["cant"];
    }
    $this->Format("reset,r,b");
    $this->Cell(90,6,"Total:",0,0,"L");
    $this->Cell(25,6,$total,0,1,"R");
    
  }
}



// Inicialización del reporte
$e = new EDC('P','mm',"Letter");
$e->AliasNbPages();
$e->SetAuthor("RH Global");
$e->SetCreator("Sistema RH Global - Creado por [Manolo Guerrero]");
$e->SetTitle("Estado de Cuenta - RH Global");
$e->SetDisplayMode("real","continuous");
$e->Format();
?>