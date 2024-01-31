<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/fpdf/fpdf.php";
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Ins_pdf extends FPDF {
        public function __construct() {
            parent::__construct();
        }
        public function Header(){

            /*$this->Image('assets/images/logo.png',20,4,17,0);
            $this->Image('assets/images/donbosco2.jpg',263,2,17,0);
            $this->SetFont('Arial','B',5);
            $this->Cell(20);
            $this->setXY(10,25);
            $this->Cell(150,5,utf8_decode('CUMUNIDAD EDUCATIVA PASTORAL'),0,0,'L');
            $this->Ln('5');
            $this->Cell(21);
            $this->setXY(16,27);
            $this->Cell(50,5,utf8_decode('DON BOSCO SUCRE'),0,0,'L');
            $this->Ln('4');
            $this->setXY(1,20);
            $this->SetFont('Arial','I',5);
            $this->Cell(30);
            $this->setXY(260,23);
            $this->Cell(25,5,utf8_decode('Calle Loa Nro 882 Casilla 214'),0,0,'C');
            $this->Ln('3');
            $this->Cell(30);
            $this->setXY(260,25);
            $this->Cell(25,5,utf8_decode('Telf:(4)6451163 - (4)6442390'),0,0,'C');
            $this->Ln('3');
            $this->Cell(30);
            $this->setXY(261,27);
            $this->Cell(25,5,utf8_decode('   Fax:(4)6444204'),0,0,'C');
            $this->Ln('3');
            $this->Cell(266);
            $this->setXY(262,29);
            $this->Cell(25,5,utf8_decode('Sucre - Bolivia '),0,0,'C');
            $this->Ln('5');
            $this->Line(10,33,285,33);*/

       }
       // El pie del pdf
       public function Footer(){
           //$this->Line(10,195,285,195);
           $this->SetY(-15);
           $this->Ln('2');
           $this->SetTextColor(50,80,120);
           $this->Cell(180,6,utf8_decode('DON BOSCO SUCRE'),0,0,'C');
           $this->SetX(15);
           $this->Cell(30,5,'Impresion: '.date('Y-m-d'),0,0,'L');
           $this->Ln('3');
           $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
      }
      function TextWithDirection($x, $y, $txt, $direction='R')
      {
        if ($direction=='R')
          $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        elseif ($direction=='L')
          $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        elseif ($direction=='U')
          $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        elseif ($direction=='D')
          $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        else
          $s=sprintf('BT %.2F %.2F Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        if ($this->ColorFlag)
          $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
      }

      function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
      {
        $font_angle+=90+$txt_angle;
        $txt_angle*=M_PI/180;
        $font_angle*=M_PI/180;

        $txt_dx=cos($txt_angle);
        $txt_dy=sin($txt_angle);
        $font_dx=cos($font_angle);
        $font_dy=sin($font_angle);

        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
        if ($this->ColorFlag)
          $s='q '.$this->TextColor.' '.$s.' Q';
        $this->_out($s);
      }
      
      
    }
?>
