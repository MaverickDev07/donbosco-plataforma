<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudiantes_su_contr extends CI_Controller {
 
	   
   
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Estudiantes_su_model','mat');
		$this->load->model('Est_estudiantes_model','estud');
		$this->load->model('Rep_centralizador_model','estud1');
		$this->load->helper(array('url', 'form', 'download' , 'html'));
		if(!$this->session->userdata("login"))
		{
			$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/LoginE/';			
		 	header("Location: ".$bu);
		} 
	}   
 
	public function index()
	{
		$this->load->helper('url');
		$this->vista();		
	}

	public function vista()
	{
		$this->load->view('layouts/inicio');
		$this->load->view('layouts/encabezado');
		$this->load->view('Estudiantes_su_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_get_id()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');

		$codgen='';
		$num_rows=$this->mat->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->mat->get_idcod_table($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idmat;//considerar nombre del id;				
				$newcod=substr($idcod,$n,strlen($idcod));
		        if($newcod>=$may)
		        {
		            $may=$newcod;    
		        }
			}
			$may=$may+1;
			$codgen=$cod."".$may;
		}
		else
		{
			$codgen=$cod.'1';	
		}
		
		echo json_encode(array("status"=>TRUE,"codgen"=>$codgen));
	}

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/LoginE';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}

	public function ajax_get_gestion()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->mat->get_rows_gestion($table); //envia
		$data=array();
		foreach ($list as $gestion) {			
			$data[] =$gestion->gestion;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}
	/*boletines*/
	public function boletines() //impresion centralizador
	{
		$this->load->library('pdf');
		// $this->pdf=new FPDF('P','cm','Legal');
		ob_start();
		$this->pdf=new FPDF('L','mm',array(215.9,333.0));
		
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();

		$gestion1=$this->mat->get_gestion(); 
		$gestion=$gestion1->gestion;	

		$estudiante=$this->mat->Estudiante($id_est,$gestion); //envia

		$ids=explode('-', $estudiante->codigo."-", -1);
		$cur=$ids[0];	
		$nivel=$ids[1];	
		$bi=7; 
		$list=$this->mat->Estudiantes($id_est,$gestion);
		
		$ni=$this->estud1->get_niveles($nivel);
		foreach ($ni as $nis) 
         {
         	$niveles=$nis->nivel;
         	$turno=$nis->turno;
         	$ninombre=$nis->nombrec;
         	$id_col=$nis->id_col;
         }
         $col=$this->estud1->get_colegio($id_col);
         foreach ($col as $cols) 
         {
         	$colegio=$cols->nombre;
         }
         $bim=$this->estud1->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
         	$bimestre=$bims->nombre;
         }
         $cur1=$this->estud1->get_cursos($cur);
         foreach ($cur1 as $curs) 
         {
         	$cursos=$curs->nombre;
         }

		if($estudiante->boletin){
			$this->pdf->AddPage('L',array(215.9,333.0)); 
		    	//$this->pdf->AddPage();
				$this->pdf->AliasNbPages();

				//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
		        $this->pdf->Image('assets/images/logo.png',6,2,22,0);
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',25);
				$this->pdf->Cell(30);
			    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
			    $this->pdf->Cell(30);            
				$this->pdf->setXY(80,20);
				$this->pdf->SetFont('Arial','B',18);
			    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
			    $this->pdf->Line(10,30,320,30);		
			    $this->pdf->Ln('15');            
			    $this->pdf->Cell(30);            
				$this->pdf->setXY(15,32);
				$this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
			    $this->pdf->setXY(60,32);
			    $this->pdf->SetFont('Arial','',10);
			   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

			    $this->pdf->setXY(165,32);
				$this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
			    $this->pdf->setXY(180,32);
			    $this->pdf->SetFont('Arial','',10);
			   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

			   	$this->pdf->setXY(230,32);
				$this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
			    $this->pdf->setXY(250,32);
			    $this->pdf->SetFont('Arial','',10);
			   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

			   	$this->pdf->setXY(15,40);
				$this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
			    $this->pdf->setXY(60,40);
			    $this->pdf->SetFont('Arial','',10);
			   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


			   $this->pdf->setXY(130,40);
				$this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
			    $this->pdf->setXY(178,40);
			    $this->pdf->SetFont('Arial','',10);

				foreach ($list as $estud) {
			   		$this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');
			   	}
			   $this->pdf->setXY(100,100);
			   $this->pdf->SetLeftMargin(10);
			    $this->pdf->SetRightMargin(10);
			    $this->pdf->SetFont('Arial','BU',50);
				$this->pdf->Cell(30);
			    $this->pdf->Cell(60,21,utf8_decode('DESHABILITADO POR MORA '),0,0,'C');
			$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
			ob_end_flush();  
		}
		else{
			if($nivel=='PM' OR $nivel=='PT'  ){
			foreach ($list as $estud) {
		$mate=$this->estud1->get_mate_primariac($estud->id_est,1,3,5,9,10,11,12,13,20,25,5,$gestion);
		$mate2=$this->estud1->get_mate_primariac($estud->id_est,1,3,5,9,10,11,12,13,20,25,6,$gestion);
		//print_r($mate2);
		//exit();
		$mate3=$this->estud1->get_mate_primariac($estud->id_est,1,3,5,9,10,11,12,13,20,25,7,$gestion);
		//$mate4=$this->estud->get_mate_primaria($estud->id_est,1,3,5,9,10,11,12,13,20,25,4,$gestion);
		$this->pdf->AddPage('L',array(215.9,333.0)); 
    	//$this->pdf->AddPage();
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        $this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,49,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,14,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,14,round(($mate[1]->ingles+$mate[0]->lenguaje)/2),'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    	//$this->pdf->Cell(16.3,14,round(($mate2[1]->ingles+$mate2[0]->lenguaje)/2),'TBLR',0,'C','1');
	    }
	    else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    	//$this->pdf->Cell(16.3,14,round(($mate3[1]->ingles+$mate3[0]->lenguaje)/2),'TBLR',0,'C','1');
	    }
	    else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
	    }
	   /* if($bi>=4){
	    	$this->pdf->Cell(16.3,7,$mate4[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,14,round(($mate4[1]->ingles+$mate4[0]->lenguaje)/2),'TBLR',0,'C','1');
	    }
	    else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
	    }*/
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }
	    else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }
	    else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(287.8);

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	   

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->matematicas,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->matematicas,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[6]->matematicas,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->matematicas,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[6]->matematicas,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->matematicas,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'TECNICA TECNOLOGICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->informatica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->informatica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[7]->informatica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[7]->informatica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[7]->informatica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[7]->informatica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,7,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[8]->maturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[8]->maturales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[8]->maturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[8]->maturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[8]->maturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[8]->maturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,7,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'V. ESPI. Y RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'VALORES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->religion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->religion,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[9]->religion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[9]->religion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[9]->religion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[9]->religion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    

        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');
	    	    /*KARDEX POR CADA ESTUDIANTES EN UN CURSO*/
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   //$reprobados=$ids[5];
		//print_r($kardex);
		//exit();
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');
	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 13);
	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }
	   if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }

	
		
	}
	   $this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush();
     
    }
  		if(($nivel=='ST' OR $nivel=='SM') AND ($cur=='1A' OR $cur=='1B' OR $cur=='2A' OR $cur=='2B')  ){
    	
//&&$pdf->AddPage('P','Legal'); 
    	foreach ($list as $estud) {
    		
    	$mate=$this->estud1->get_matec($estud->id_est,1,27,4,5,9,10,11,12,14,15,16,21,24,25,5,$gestion);	
    	$mate2=$this->estud1->get_matec($estud->id_est,1,27,4,5,9,10,11,12,14,15,16,21,24,25,6,$gestion);	
    	$mate3=$this->estud1->get_matec($estud->id_est,1,27,4,5,9,10,11,12,14,15,16,21,24,25,7,$gestion);	
    	
    	//$this->pdf->AddPage('L',array(215.9,333.0)); 
    	$this->pdf->AddPage();
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        $this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,49,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,14,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    /*if($gestion==2020){

		    $this->pdf->Cell(16.3,14,round($mate[0]->lenguaje),'TBLR',0,'C','1');
		    if($bi>=6){
		    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round($mate2[0]->lenguaje),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		    if($bi>=7){
		    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round($mate3[0]->lenguaje),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
	    }else
	    {*/

		    $this->pdf->Cell(16.3,14,round(($mate[13]->gramatica+$mate[0]->lenguaje)/2),'TBLR',0,'C','1');
		    if($bi>=6){
		    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round(($mate2[13]->gramatica+$mate2[0]->lenguaje)/2),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		    if($bi>=7){
		    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round(($mate3[13]->gramatica+$mate3[0]->lenguaje)/2),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
	   // }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'GRAMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->gramatica,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[13]->gramatica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[13]->gramatica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'LENGUAJE EXTRANJERA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,28,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->mate,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->mate,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[6]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[6]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,21,'TECNICA TECNOLOGICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->infromatica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,21,round(($mate[9]->quimica+$mate[8]->fisica+$mate[7]->infromatica)/3),'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[7]->infromatica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,round(($mate2[9]->quimica+$mate2[8]->fisica+$mate2[7]->infromatica)/3),'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[7]->infromatica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,round(($mate3[9]->quimica+$mate3[8]->fisica+$mate3[7]->infromatica)/3),'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[8]->fisica,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[8]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[8]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->quimica,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[9]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[9]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,7,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'BIOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[10]->naturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[10]->naturales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[10]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[10]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[10]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[10]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'C. FILOSOFIA Y  PSICOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FILOSOFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[11]->filosofia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[11]->filosofia,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[11]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[11]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[11]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[11]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'VAL. ESPIRIT.  Y RELIGIONES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->religuion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->religuion,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[12]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[12]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[12]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[12]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');
	    /*KARDEX POR CADA ESTUDIANTES EN UN CURSO*/
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');
	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 13);
	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }
	   if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }
	    
}
$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush();
 }
    
    if(($nivel=='ST' OR $nivel=='SM') AND ($cur=='3A' OR $cur=='3B')  ){
    	//$pdf->AddPage('P','Legal'); 
    	foreach ($list as $estud) {
    	$mate=$this->estud1->get_mate4c($estud->id_est,1,27,4,5,9,10,11,12,18,19,21,22,23,24,25,5,$gestion);
    	$mate2=$this->estud1->get_mate4c($estud->id_est,1,27,4,5,9,10,11,12,18,19,21,22,23,24,25,6,$gestion);
    	$mate3=$this->estud1->get_mate4c($estud->id_est,1,27,4,5,9,10,11,12,18,19,21,22,23,24,25,7,$gestion);
    	//print_r($mate);exit();
    	$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,49,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,14,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    /*if($gestion==2020){
		    $this->pdf->Cell(16.3,14,round($mate[0]->lenguaje),'TBLR',0,'C','1');
		    if($bi>=6){
		    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round($mate2[0]->lenguaje),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		    if($bi>=7){
		    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round($mate3[0]->lenguaje),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }	
	    }else{*/

		    $this->pdf->Cell(16.3,14,round(($mate[15]->gramatica+$mate[0]->lenguaje)/2),'TBLR',0,'C','1');
		    if($bi>=6){
		    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round(($mate2[15]->gramatica+$mate2[0]->lenguaje)/2),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		    if($bi>=7){
		    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round(($mate3[15]->gramatica+$mate3[0]->lenguaje)/2),'TBLR',0,'C','1');
		    }else{
		    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
	    //}

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'GRAMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->gramatica,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[15]->gramatica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[15]->gramatica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'LENGUAJE EXTRANJERA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->mate,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->mate,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[6]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[6]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($mate[8]->sistemas==0)
        {
        	$siste=$mate[9]->electronica;
        }
        if($mate[9]->electronica==0)
        {
        	$siste=$mate[8]->sistemas;
        }
	    if($bi>=6){
	    	if($mate2[8]->sistemas==0)
	        {
	        	$siste2=$mate2[9]->electronica;
	        }
	        if($mate2[9]->electronica==0)
	        {
	        	$siste2=$mate2[8]->sistemas;
	        }
	    }
	    if($bi>=7){
	    	if($mate3[8]->sistemas==0)
	        {
	        	$siste3=$mate3[9]->electronica;
	        }
	        if($mate3[9]->electronica==0)
	        {
	        	$siste3=$mate3[8]->sistemas;
	        }
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'TECNICA TECNOLOGICA GEN.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,21,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	   	$this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'BIOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[10]->naturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[10]->naturales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[10]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[10]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[10]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[10]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

		$this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[11]->fisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[11]->fisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[11]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[11]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[11]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[11]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->quimica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->quimica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[12]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[12]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[12]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[12]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'C. FILOSOFIA Y  PSICOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FILOSOFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->filosofia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->filosofia,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[13]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[13]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[13]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[13]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'VAL. ESPIRIT.  Y RELIGIONES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->religuion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->religuion,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[14]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[14]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[14]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[14]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');
	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 13);
	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }
	   if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }
}
$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush();
     
    }
   if(($nivel=='ST' OR $nivel=='SM') AND ($cur=='4A' OR $cur=='4B')  ){
   	foreach ($list as $estud) {
    	$mate=$this->estud1->get_mate6c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,5,$gestion);
    	$mate2=$this->estud1->get_mate6c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,6,$gestion);
    	$mate3=$this->estud1->get_mate6c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,7,$gestion);
    	//$mate4=$this->estud->get_mate6($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,4,$gestion);
    	$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,42,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'LENGUAJE EXTRANJERA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	  	if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[3]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[4]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7, $mate[5]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7, $mate[5]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7, $mate2[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7, $mate2[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7, $mate3[5]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7, $mate3[5]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->mate,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->mate,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[6]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[6]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    if($mate[8]->sistemas==0)
    	{
    		$siste=$mate[9]->electronica;
    	}
    	if($mate[9]->electronica==0)
    	{
    		$siste=$mate[8]->sistemas;
    	}
	    if($bi>=6){
	    	if($mate2[8]->sistemas==0)
	    	{
	    		$siste2=$mate2[9]->electronica;
	    	}
	    	if($mate2[9]->electronica==0)
	    	{
	    		$siste2=$mate2[8]->sistemas;
	    	}
	    }
	    if($bi>=7){
	    	if($mate3[8]->sistemas==0)
	    	{
	    		$siste3=$mate3[9]->electronica;
	    	}
	    	if($mate3[9]->electronica==0)
	    	{
	    		$siste3=$mate3[8]->sistemas;
	    	}
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'TECNICA TECNOLOGICA GEN.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7); 
	    $this->pdf->Cell(60,21,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	   	$this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'BIOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[10]->naturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[10]->naturales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[10]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[10]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[10]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[10]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

		$this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[11]->fisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[11]->fisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[11]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[11]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[11]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[11]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->quimica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->quimica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[12]->quimica,'TBLR',0,'C','1');
	   		$this->pdf->Cell(16.3,7,$mate2[12]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[12]->quimica,'TBLR',0,'C','1');
	   		$this->pdf->Cell(16.3,7,$mate3[12]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'C. FILOSOFIA Y  PSICOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FILOSOFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->filosofia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->filosofia,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[13]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[13]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[13]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[13]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'VAL. ESPIRIT.  Y RELIGIONES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->religuion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->religuion,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[14]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[14]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[14]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[14]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');
	  	    /*KARDEX POR CADA ESTUDIANTES EN UN CURSO*/
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');
	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 13);
	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }
	   if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }
   
}
$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush();
      
    }
    if(($nivel=='ST' OR $nivel=='SM') AND ($cur=='5A'  OR $cur=='6A')  ){

    	foreach ($list as $estud) {
    	$mate=$this->estud1->get_mate1c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,5,$gestion);
    	$mate2=$this->estud1->get_mate1c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,6,$gestion);
    	$mate3=$this->estud1->get_mate1c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,7,$gestion);
    	//$mate4=$this->estud->get_mate1($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,4,$gestion);
    	$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,42,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'LENGUAJE EXTRANJERA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[6]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[6]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[7]->edmusica,'TBLR',0,'C','1');
	   		$this->pdf->Cell(16.3,7,$mate2[7]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[7]->edmusica,'TBLR',0,'C','1');
	   		$this->pdf->Cell(16.3,7,$mate3[7]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7, $mate[8]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7, $mate[8]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7, $mate2[8]->artes,'TBLR',0,'C','1');
	   		$this->pdf->Cell(16.3,7, $mate2[8]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7, $mate3[8]->artes,'TBLR',0,'C','1');
	   		$this->pdf->Cell(16.3,7, $mate3[8]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->mate,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->mate,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[9]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[9]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[9]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[9]->mate,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
		if($mate[10]->sistemas==0)
    	{
    		$siste=$mate[11]->electronica;
    	}
    	if($mate[11]->electronica==0)
    	{
    		$siste=$mate[10]->sistemas;
    	}
	    if($bi>=6){
	    	if($mate2[10]->sistemas==0)
	    	{
	    		$siste2=$mate2[11]->electronica;
	    	}
	    	if($mate2[11]->electronica==0)
	    	{
	    		$siste2=$mate2[10]->sistemas;
	    	}
	    }
	    if($bi>=7){
	    	if($mate3[10]->sistemas==0)
	    	{
	    		$siste3=$mate3[11]->electronica;
	    	}
	    	if($mate3[11]->electronica==0)
	    	{
	    		$siste3=$mate3[10]->sistemas;
	    	}
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'TECNICA TECNOLOGICA GEN.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,21,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	   	$this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'BIOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->naturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->naturales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[12]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[12]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[12]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[12]->naturales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
		$this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->fisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->fisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[13]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[13]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[13]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[13]->fisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->quimica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->quimica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[14]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[14]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[14]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[14]->quimica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'C. FILOSOFIA Y  PSICOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FILOSOFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->filosofia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->filosofia,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[15]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[15]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[15]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[15]->filosofia,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'VAL. ESPIRIT.  Y RELIGIONES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[16]->religuion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[16]->religuion,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[16]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[16]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[16]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[16]->religuion,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');

	    	    /*KARDEX POR CADA ESTUDIANTES EN UN CURSO*/
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');
	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 13);
	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }
	  
   if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }
}
$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush();

   
    }
    
    if(($nivel=='ST' OR $nivel=='SM') AND ($cur=='5B' OR $cur=='6B')  ){

    	foreach ($list as $estud) {
    	$mate=$this->estud1->get_mate3c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,26,5,$gestion);
    	$mate2=$this->estud1->get_mate3c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,26,6,$gestion);
    	$mate3=$this->estud1->get_mate3c($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,26,7,$gestion);
    	//$mate4=$this->estud->get_mate3($estud->id_est,1,4,5,9,10,11,12,18,19,21,22,23,24,25,26,4,$gestion);
    	$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');

	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,42,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    if($bi>=6){
	    	 $this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	 $this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'LENGUAJE EXTRANJERA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[2]->sociales,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[2]->sociales,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[6]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[6]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->edfisica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[7]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[7]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[7]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[7]->edmusica,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7, $mate[8]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7, $mate[8]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7, $mate2[8]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7, $mate2[8]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7, $mate3[8]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7, $mate3[8]->artes,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,21,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,14,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->mate,'TBLR',0,'C','1');
	    if($gestion==2020){
		    $this->pdf->Cell(16.3,14,round($mate[9]->mate),'TBLR',0,'C','1');
		    if($bi>=6){
		    	$this->pdf->Cell(16.3,7,$mate2[9]->mate,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round($mate2[9]->mate),'TBLR',0,'C','1');
		    }else{
		    	 $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		    if($bi>=7){
		    	$this->pdf->Cell(16.3,7,$mate3[9]->mate,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round($mate3[9]->mate),'TBLR',0,'C','1');
		    }else{
		    	 $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
	    }else{


		    $this->pdf->Cell(16.3,14,round(($mate[17]->estadisticas+$mate[9]->mate)/2),'TBLR',0,'C','1');
		    if($bi>=6){
		    	$this->pdf->Cell(16.3,7,$mate2[9]->mate,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round(($mate2[17]->estadisticas+$mate2[9]->mate)/2),'TBLR',0,'C','1');
		    }else{
		    	 $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		    if($bi>=7){
		    	$this->pdf->Cell(16.3,7,$mate3[9]->mate,'TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,round(($mate3[17]->estadisticas+$mate3[9]->mate)/2),'TBLR',0,'C','1');
		    }else{
		    	 $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    	$this->pdf->Cell(16.3,14,'','TBLR',0,'C','1');
		    }
		}
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'ESTADISTICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[17]->estadisticas,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$mate2[17]->estadisticas,'TBLR',0,'C','1');
	    
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$mate3[17]->estadisticas,'TBLR',0,'C','1');
	    }else{
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

		if($mate[10]->sistemas==0)
    	{
    		$siste=$mate[11]->electronica;
    	}
    	if($mate[11]->electronica==0)
    	{
    		$siste=$mate[10]->sistemas;
    	}
	    if($bi>=6){
	    	if($mate2[10]->sistemas==0)
        	{
        		$siste2=$mate2[11]->electronica;
        	}
        	if($mate2[11]->electronica==0)
        	{
        		$siste2=$mate2[10]->sistemas;
        	}
	    }
	    if($bi>=7){
	    	if($mate3[10]->sistemas==0)
        	{
        		$siste3=$mate3[11]->electronica;
        	}
        	if($mate3[11]->electronica==0)
        	{
        		$siste3=$mate3[10]->sistemas;
        	}
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'TECNICA TECNOLOGICA GEN.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    if($bi>=6){
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);	    
	    $this->pdf->Cell(60,21,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	   	$this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'BIOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->naturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->naturales,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[12]->naturales,'TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,$mate2[12]->naturales,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[12]->naturales,'TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,$mate3[12]->naturales,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

		$this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->fisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->fisica,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[13]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[13]->fisica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[13]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[13]->fisica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->quimica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->quimica,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[14]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[14]->quimica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[14]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[14]->quimica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }	    

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'C. FILOSOFIA Y  PSICOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FILOSOFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->filosofia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->filosofia,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[15]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[15]->filosofia,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[15]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[15]->filosofia,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'VAL. ESPIRIT.  Y RELIGIONES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[16]->religuion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[16]->religuion,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[16]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[16]->religuion,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[16]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[16]->religuion,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');

	    /*KARDEX POR CADA ESTUDIANTES EN UN CURSO*/
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');
	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 13);
	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }
	if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }
   
}
$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush();
    }
    if(($nivel=='ST' OR $nivel=='SM') AND ($cur=='5C' OR $cur=='6C')  ){
        //Le aplicamos ancho las columnas.
        foreach ($list as $estud) {
    	$mate=$this->estud1->get_mate2c($estud->id_est,1,4,6,7,8,9,10,11,12,18,19,21,22,23,24,25,5,$gestion);
    	$mate2=$this->estud1->get_mate2c($estud->id_est,1,4,6,7,8,9,10,11,12,18,19,21,22,23,24,25,6,$gestion);
    	$mate3=$this->estud1->get_mate2c($estud->id_est,1,4,6,7,8,9,10,11,12,18,19,21,22,23,24,25,7,$gestion);
    	//$mate4=$this->estud->get_mate2($estud->id_est,1,4,6,7,8,9,10,11,12,18,19,21,22,23,24,25,4,$gestion);
    	$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('RENDIMIENTO ACADÉMICO -  '.$bimestre),0,0,'C');
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(80,20);
		$this->pdf->SetFont('Arial','B',18);
	    $this->pdf->Cell(200,5,utf8_decode(''.$ninombre),0,0,'L');
	    $this->pdf->Line(10,30,320,30);		
	    $this->pdf->Ln('15');            
	    $this->pdf->Cell(30);            
		$this->pdf->setXY(15,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('UNIDAD EDUCATIVA: '),0,0,'L');
	    $this->pdf->setXY(60,32);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');

	    $this->pdf->setXY(165,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('TURNO: '),0,0,'L');
	    $this->pdf->setXY(180,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($turno),0,0,'L');

	   	$this->pdf->setXY(230,32);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
	    $this->pdf->setXY(250,32);
	    $this->pdf->SetFont('Arial','',10);
	   	$this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');

	   	$this->pdf->setXY(15,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('AÑO DE ESCOLARIDAD: '),0,0,'L');
	    $this->pdf->setXY(60,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');


	   $this->pdf->setXY(130,40);
		$this->pdf->SetFont('Arial','B',10);
	    $this->pdf->Cell(35,5,utf8_decode('APELLIDOS Y NOMBRES : '),0,0,'L');
	    $this->pdf->setXY(178,40);
	    $this->pdf->SetFont('Arial','',10);
	   $this->pdf->Cell(15,5,utf8_decode($estud->nombres),0,0,'L');

	   $this->pdf->setXY(0,60);
	   $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Ln(5);
	    $this->pdf->Cell(60,21,'CAMPOS DE SABER Y CONOC.','TBL',0,'L','1');
	    $this->pdf->Cell(60,21,'AREAS CURRICULARES','TBL',0,'C','1');
	    $this->pdf->Cell(60,21,utf8_decode('MATERIAS'),'TBL',0,'C','1');
	    $this->pdf->Cell(97.8,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(32.6,7,'1ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'2DO TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Cell(32.6,7,'3ER TRIMESTRE','TBLR',0,'C','1');
	    $this->pdf->Ln(7);
	    $this->pdf->SetX(190);
	    $this->pdf->Cell(16.3,7,'1ER','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'2DO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'3RO','TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,'P.A.','TBLR',0,'C','1');
	    $this->pdf->Ln(7);

	    $this->pdf->SetFillColor(255,255,255);
	    $this->pdf->SetFont('Arial', '', 10);
	    $this->pdf->Cell(60,56,'COMUNIDAD Y SOCIEDAD','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'COMUNICACION Y LENGUAJE','TBLR',0,'C','1');
	    $this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[0]->lenguaje,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[0]->lenguaje,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'LENGUAJE EXTRANJERA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[1]->ingles,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[1]->ingles,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[1]->ingles,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,21,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'HISTORIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[3]->historia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,21,round(($mate[5]->geografia+$mate[4]->civica+$mate[3]->historia)/3),'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[3]->historia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,round(($mate2[5]->geografia+$mate2[4]->civica+$mate2[3]->historia)/3),'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[3]->historia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,round(($mate3[5]->geografia+$mate3[4]->civica+$mate3[3]->historia)/3),'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,21,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'CIVICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[4]->civica,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[4]->civica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[4]->civica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(130);
	    $this->pdf->Cell(60,7,'GEOGRAFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[5]->geografia,'TBLR',0,'C','1');
	    $this->pdf->SetX(222.6);
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[5]->geografia,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    $this->pdf->SetX(255.2);
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[5]->geografia,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION FISICA Y DEPORTES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->edfisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[6]->edfisica,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[6]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[6]->edfisica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[6]->edfisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[6]->edfisica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'EDUCACION MUSICAL','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->edmusica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[7]->edmusica,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[7]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[7]->edmusica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[7]->edmusica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[7]->edmusica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'ARTES PLASTICAS Y VISUALES','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7, $mate[8]->artes,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7, $mate[8]->artes,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7, $mate2[8]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7, $mate2[8]->artes,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7, $mate3[8]->artes,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7, $mate3[8]->artes,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'CIENCIAS TECNOLOGIA Y PROD.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'MATEMATICAS','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->mate,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[9]->mate,'TBLR',0,'C','1');
	    if($bi>=6){
		     $this->pdf->Cell(16.3,7,$mate2[9]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[9]->mate,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		     $this->pdf->Cell(16.3,7,$mate3[9]->mate,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[9]->mate,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

		if($mate[10]->sistemas==0)
    	{
    		$siste=$mate[11]->electronica;
    	}
    	if($mate[11]->electronica==0)
    	{
    		$siste=$mate[10]->sistemas;
    	}
	    if($bi>=6){
	    	if($mate2[10]->sistemas==0)
			{
				$siste2=$mate2[11]->electronica;
			}
			if($mate2[11]->electronica==0)
			{
				$siste2=$mate2[10]->sistemas;
			}
	    }
	    if($bi>=7){
	    	if($mate3[10]->sistemas==0)
			{
				$siste3=$mate3[11]->electronica;
			}
			if($mate3[11]->electronica==0)
			{
				$siste3=$mate3[10]->sistemas;
			}
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'TECNICA TECNOLOGICA GEN.','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'INFORMATICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$siste,'TBLR',0,'C','1');
	    if($bi>=6){
		     $this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste2,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		     $this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$siste3,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,21,'VIDA TIERRA TERRITORIO','TBL',0,'L','1');
	   	$this->pdf->Cell(60,7,'CIENCIAS NATURALES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'BIOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->naturales,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[12]->naturales,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[12]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[12]->naturales,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[12]->naturales,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[12]->naturales,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

		$this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	     $this->pdf->Cell(60,7,'FISICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->fisica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[13]->fisica,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[13]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[13]->fisica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[13]->fisica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[13]->fisica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'QUIMICA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->quimica,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[14]->quimica,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[14]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[14]->quimica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[14]->quimica,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[14]->quimica,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->Cell(60,14,'COSMOS Y PENSAMIENTO','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'C. FILOSOFIA Y  PSICOLOGIA','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'FILOSOFIA','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->filosofia,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[15]->filosofia,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[15]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[15]->filosofia,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[15]->filosofia,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[15]->filosofia,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }

	    $this->pdf->Ln(7);
	    $this->pdf->SetX(70);
	    $this->pdf->Cell(60,7,'VAL. ESPIRIT.  Y RELIGIONES','TBL',0,'L','1');
	    $this->pdf->Cell(60,7,'RELIGION','TBL',0,'L','1');
	    $this->pdf->Cell(16.3,7,$mate[16]->religuion,'TBLR',0,'C','1');
	    $this->pdf->Cell(16.3,7,$mate[16]->religuion,'TBLR',0,'C','1');
	    if($bi>=6){
		    $this->pdf->Cell(16.3,7,$mate2[16]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate2[16]->religuion,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
	    if($bi>=7){
		    $this->pdf->Cell(16.3,7,$mate3[16]->religuion,'TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,$mate3[16]->religuion,'TBLR',0,'C','1');
	    }else{
		    $this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    	$this->pdf->Cell(16.3,7,'','TBLR',0,'C','1');
	    }
        $this->pdf->Line(10,48,320,48);		            
	    $this->pdf->Ln('12');

   	    /*KARDEX POR CADA ESTUDIANTES EN UN CURSO*/
	    $this->pdf->AddPage(); 
	    $this->pdf->Line(10,12,320,12);	
		$this->pdf->AliasNbPages();

		//$this->pdf->Image('assets/images/donbosco2.jpg',8,8,15,0);
        //$this->pdf->Image('assets/images/logo.png',6,2,22,0);
		$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
		$this->pdf->setXY(10,2);
		$this->pdf->SetFont('Arial','BU',25);
		$this->pdf->Cell(30);
	    $this->pdf->Cell(250,8,utf8_decode('KARDEX '.$bimestre),0,0,'C');

	   $kardex=$this->estud1->kardex($estud->id_est,$gestion,$bi);
	   	$this->pdf->SetFont('Arial', '', 13);
	   	$this->pdf->setXY(10,15);
	    $this->pdf->SetLeftMargin(10);
	    $this->pdf->SetRightMargin(10);
	    $this->pdf->SetFillColor(192,192,192);
	    $this->pdf->SetFont('Arial', 'B', 10);
	    $this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    $this->pdf->Cell(33,7,'FECHA','TBL',0,'C','1');
	    $this->pdf->Cell(70,7,utf8_decode('CATEGORIA'),'TBL',0,'C','1');
	    $this->pdf->Cell(200,7,'DESCRIPCION','TBL',0,'C','1');

	    $i=0;
	   foreach ($kardex as $kardexs){
	   	$i++;
	   	$this->pdf->Ln(7);
	    $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    $this->pdf->Cell(33,7,$kardexs->fecha,'TBL',0,'L','1');
	    $this->pdf->Cell(70,7,utf8_decode($kardexs->categoria),'TBL',0,'L','1');
	    $this->pdf->Cell(200,7,utf8_decode($kardexs->descripcion),'TBLR',0,'C','1');
	   }

	   if($i>=25){
	   	$this->pdf->AddPage(); 
	    //$this->pdf->Line(10,12,320,12);
	   }
}
$this->pdf->Output("BOLETINES ".$cursos." ".$turno.".pdf", 'I');
ob_end_flush(); 

    	}
    }
	}
	public function compromiso()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();

		$gestion=$this->mat->get_gestion(); 
		
		$gestion=$gestion->gestion;
		$adenda=$this->mat->Estudiante($id_est,$gestion);
		$contrato=$this->estud->get_data_contrato($gestion,$id_est);
        $id_padre=$contrato->id_padre;
        $codigo=$contrato->codigo;
        $fechactt=$contrato->fecha;

        $padre=$this->estud->get_data_padres($id_padre);

     	$nombre=$padre->appaterno." ".$padre->apmaterno." ".$padre->nombre;
		$carnet=$padre->ci." ".$padre->com." ".$padre->ex;
		$fono=$padre->celular;

         
        $estudiante1=$this->estud->get_data_estudiante($id_est);
		$estudiante=$estudiante1->appaterno." ".$estudiante1->apmaterno." ".$estudiante1->nombre;
		$inscripcion=$this->estud->get_data_inscrips($id_est,$gestion);
		$cod_curso=$inscripcion->curso;
		$cod_nivel=$inscripcion->nivel;
		$fecha=$inscripcion->fecha."-";
        $fechas=explode('-', $fecha, -1);
        $curso1=$this->estud->get_data_cursos($cod_curso);
		$curso=$curso1->nombre;
        $nivel1=$this->estud->get_data_niveles($cod_nivel);
		$nivel=$nivel1->nivel." ".$nivel1->turno;
		$turno=$nivel1->turno;
        $direcion=$this->estud->get_data_direccion($gestion,$id_est);
        foreach ($direcion as $direciones) 
         {
			$calle=$direciones->calle;
			$num=$direciones->nro;
			$zona=$direciones->zona;
         }

         if ($adenda->cpse==0) {
        
		$this->load->library('Pdf');

		ob_start();
		$this->pdf=new Pdf('Letter');
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle($estudiante);
		

       	$this->pdf->SetFont('Arial','BU',10);
		$this->pdf->Cell(30);
    	$this->pdf->Cell(135,8,utf8_decode('COMPROMISO DE PAGO POR SERVICIO EDUCATIVO COMPLEMENTARIO 
'),0,0,'C');
    	$this->pdf->setXY(40,15);
    	$this->pdf->Cell(135,8,utf8_decode('Nº  '.$codigo.'   /2020
'),0,0,'C');
    	$this->pdf->Image('assets/images/firma.png',40,210,50,0);

    	$this->pdf->SetFont('Arial','',10);
    	$this->pdf->Ln('6');

   		$this->pdf->setXY(11,35);
    	$this->pdf->MultiCell(185,4,utf8_decode('Conste por el presente documento privado, que reconocido tendrá el valor de un Documento Público que le asignan los Art. 519 y 1297 del Código Civil, consistente en un COMPROMISO DE PAGO indisoluble al contrato de prestación de servicios educativos N° '.$codigo.' suscrito en fecha '.$fechactt.' , de acuerdo a las cláusulas siguientes:


ANTECEDENTES. - Debido a la emergencia sanitaria que se encuentra atravesando el país por el SARS -COV -2 (Covid -19), el Gobierno Nacional ha determinado la suspensión de actividades académicas presenciales, y de acuerdo al D.S. 4260 el Ministerio de Educación ha homologado la validez de la educación virtual, con la finalidad de que los estudiantes continúen con el proceso de aprendizaje. Por consiguiente, se llevará adelante el presente Convenio de acuerdo a las cláusulas siguientes: 

PRIMERA. -  La Unidad Educativa Técnico Humanístico de Convenio se compromete:

             1.	 A cumplir con el avance curricular de la Gestión 2020 de manera regular, por medio de la Plataforma con 
                la que cuenta el establecimiento para llevar adelante las clases virtuales. (De acuerdo a lo determinado en
                la   Asamblea llevada adelante con la Directivas de PPFF en fecha 3.08.20) 
             2.	Entrega de Boletines a la finalización de los trimestres a nivel interno.

SEGUNDA. - Los Padres de Familia y/o Tutores y/o Apoderados del Alumno, se comprometen a:

            1.	La asistencia regular del Alumno a las clases virtuales establecidas
            2.	Rendir con los exámenes correspondientes
            3.	Realizar el pago de los aportes voluntarios, que deben ser efectuados hasta el último mes correspondiente
               a noviembre de 2020  

TERCERA. - Ambas partes, al momento de proceder con la firma del presente documento, manifiestan su plena conformidad con todas y cada una de las cláusulas descritas. Por lo que en señal de conformidad firman al pie del mismo.



                                                           Sucre,............ de ............................ del 2020

'),0);
    	//$html='<b>Que el(a) contratista </b>, identificado(a) con Cédula de Ciudadanía No. presto(a) sus servicios mediante la siguiente orden:';
 
    $this->pdf->setXY(40,230);
//$this->pdf->WriteHTML($html);
    $this->pdf->setXY(40,235);
    $this->pdf->SetFont('Arial','BU',10);
	$this->pdf->Cell(45,5,'                                                ',0,0,'R');			
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',8);
	$this->pdf->setXY(40,240);
	$this->pdf->Cell(40,5,utf8_decode('LA UNIDAD EDUCATIVA'),0,0,'C');
	$this->pdf->setXY(40,245);
	$this->pdf->Cell(40,5,utf8_decode('P. ALBERTO VÁSQUEZ CÁCERES	'),0,0,'C');
	$this->pdf->setXY(40,250);
	$this->pdf->Cell(40,5,utf8_decode('C.I 3122876 CBBA	'),0,0,'C');
	
	$this->pdf->setXY(130,235);
    $this->pdf->SetFont('Arial','BU',10);
	$this->pdf->Cell(45,5,'                                                ',0,0,'R');			
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',8);
	$this->pdf->setXY(130,240);
	$this->pdf->Cell(40,5,utf8_decode('EL RESPONSABLE'),0,0,'C');
	$this->pdf->setXY(130,245);
	$this->pdf->Cell(40,5,utf8_decode('Sr./Sra. '.$nombre),0,0,'C');
	$this->pdf->setXY(130,250);
	$this->pdf->Cell(40,5,utf8_decode('C.I.'.$carnet),0,0,'C');

   		
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',6);
	$this->pdf->setXY(10,270);
	$this->pdf->Cell(40,5,utf8_decode('ESTUDIANTE: '.$appat.' '.$apmat.' '.$name),0,0,'C');
	$this->pdf->setXY(160,270);
	$this->pdf->Cell(40,5,utf8_decode('GRADO DE ESCOLARIDAD: '.$curso),0,0,'C');
    $this->pdf->Output($estudiante.".pdf", 'I');

 }
			
	}
	public function print_cpse()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();

		$gestion=$this->mat->get_gestion(); 
		
		$gestion=$gestion->gestion;
		$adenda=$this->mat->Estudiante($id_est,$gestion);
		$contrato=$this->estud->get_data_contrato($gestion,$id_est);
        $id_padre=$contrato->id_padre;
        $codigo=$contrato->codigo;
        $fechactt=$contrato->fecha;

        $padre=$this->estud->get_data_padres($id_padre);

     	$nombre=$padre->appaterno." ".$padre->apmaterno." ".$padre->nombre;
		$carnet=$padre->ci." ".$padre->com." ".$padre->ex;
		$fono=$padre->celular;

         
        $estudiante1=$this->estud->get_data_estudiante($id_est);
		$estudiante=$estudiante1->appaterno." ".$estudiante1->apmaterno." ".$estudiante1->nombre;
		$inscripcion=$this->estud->get_data_inscrips($id_est,$gestion);
		$cod_curso=$inscripcion->curso;
		$cod_nivel=$inscripcion->nivel;
		$fecha=$inscripcion->fecha."-";
        $fechas=explode('-', $fecha, -1);
        $curso1=$this->estud->get_data_cursos($cod_curso);
		$curso=$curso1->nombre;
        $nivel1=$this->estud->get_data_niveles($cod_nivel);
		$nivel=$nivel1->nivel." ".$nivel1->turno;
		$turno=$nivel1->turno;
        $direcion=$this->estud->get_data_direccion($gestion,$id_est);
        foreach ($direcion as $direciones) 
         {
			$calle=$direciones->calle;
			$num=$direciones->nro;
			$zona=$direciones->zona;
         }

         if ($adenda->cpse) {
        
		$this->load->library('Pdf');

		ob_start();
		$this->pdf=new Pdf('Letter');
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle($estudiante);
		

       	$this->pdf->SetFont('Arial','BU',10);
		$this->pdf->Cell(30);
    	$this->pdf->Cell(135,8,utf8_decode('ADENDA CONTRATO DE PRESTACION DE SERVICIO EDUCATIVO 
'),0,0,'C');
    	$this->pdf->setXY(40,15);
    	$this->pdf->Cell(135,8,utf8_decode('Nº  '.$codigo.'   /2020
'),0,0,'C');
    	$this->pdf->Image('assets/images/firma.png',40,210,50,0);

    	$this->pdf->SetFont('Arial','',10);
    	$this->pdf->Ln('6');

   		$this->pdf->setXY(11,35);
    	$this->pdf->MultiCell(185,4,utf8_decode('Conste por el presente documento privado, que reconocido tendrá el valor de documento público que le asignan los Art. 519 y 1.297 del Código Civil, consistente en una ADENDA indisoluble al contrato de prestación de servicios educativos N° '.$codigo.' , suscrito en fecha '.$fechactt.' , de acuerdo a las cláusulas siguientes:


PRIMERA. - ANTECEDENTES.- En fecha '.$fechactt.' UNIDAD EDUCATIVA y RESPONSABLE suscribieron un contrato de prestación de servicios educativos para la gestión 2020. Que, por determinaciones gubernamentales, debido a la pandemia del SARS -COV -2 (Covid -- 19) que aqueja a nuestra población, ha suspendido las actividades académicas presenciales. Que por DS 4260 el Ministerio de Educación ha homologado la validez de la educación virtual y debido a la situación económica por la que atraviesa la ciudadanía en general, se ve necesario arribar a un acuerdo voluntario que garanticen el cumplimiento del contrato, con la finalidad que los estudiantes continúen el proceso de enseñanza/aprendizaje acordado.


SEGUNDA.-DEL OBJETO DE LA ADENDA.- Por los antecedentes expuestos, ambas partes, UNIDAD EDUCATIVA y el RESPONSABLE determinan, en cuanto a la cláusula quinta  del contrato principal, referente al precio y forma de pago por el servicio educativo, aplicar una reducción del 14% a las cuotas mensuales de abril, mayo, junio, etc, hasta que dure la suspensión de actividades presenciales de la gestión 2020. 


TERCERA.- DE LA ACEPTACIÓN.-  Ambas partes, al momento de firmar el presente contrato, manifiestan su plena conformidad con todas y cada una de las cláusulas precedentemente estipuladas y se ratifican, por lo demás en el contrato principal.






                                                           Sucre,............ de ............................ del 2020

'),0);
    	//$html='<b>Que el(a) contratista </b>, identificado(a) con Cédula de Ciudadanía No. presto(a) sus servicios mediante la siguiente orden:';
 
    $this->pdf->setXY(40,230);
//$this->pdf->WriteHTML($html);
    $this->pdf->setXY(40,235);
    $this->pdf->SetFont('Arial','BU',10);
	$this->pdf->Cell(45,5,'                                                ',0,0,'R');			
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',8);
	$this->pdf->setXY(40,240);
	$this->pdf->Cell(40,5,utf8_decode('LA UNIDAD EDUCATIVA'),0,0,'C');
	$this->pdf->setXY(40,245);
	$this->pdf->Cell(40,5,utf8_decode('P. ALBERTO VÁSQUEZ CÁCERES	'),0,0,'C');
	$this->pdf->setXY(40,250);
	$this->pdf->Cell(40,5,utf8_decode('C.I 3122876 CBBA	'),0,0,'C');
	
	$this->pdf->setXY(130,235);
    $this->pdf->SetFont('Arial','BU',10);
	$this->pdf->Cell(45,5,'                                                ',0,0,'R');			
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',8);
	$this->pdf->setXY(130,240);
	$this->pdf->Cell(40,5,utf8_decode('EL RESPONSABLE'),0,0,'C');
	$this->pdf->setXY(130,245);
	$this->pdf->Cell(40,5,utf8_decode('Sr./Sra. '.$nombre),0,0,'C');
	$this->pdf->setXY(130,250);
	$this->pdf->Cell(40,5,utf8_decode('C.I.'.$carnet),0,0,'C');

    $this->pdf->Output($estudiante.".pdf", 'I');

 }
			
	}

	public function Get_estu()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$cursos=$this->mat->Get_curso($cod_curso);
		$niveles=$this->mat->get_nivel($cod_nivel);
		$data=array();
		$data[] =$appat." ".$apmat." ".$name;	
		$data[] =$id_est;	
		$data[] =$gest;	
		$data[] =$niveles->nivel." ".$niveles->turno;	
		$data[] =$cursos->nombre;	
		$data[] =$cod_nivel;	
		$data[] =$cod_curso;	
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}


	public function ajax_get_cole()
	{
		$table=$this->input->post('table');
		$nivel=$this->input->post('nivel');

		$list=$this->mat->get_rows_cole($table,$nivel);
		$data=array();
		foreach ($list as $cole ) {
			$data[]=$cole->colegio;
			$data[]=$cole->gestion;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_curso()
	{
		$nivel=$this->input->post('nivel');

		$list=$this->mat->get_rows_curso_nivel($nivel);
		// print_r($list);
		// exit();
		$data=array();
		$data1=array();
		foreach ($list as $curso ) {
			$data[]=$curso->curso;
			$data1[]=$curso->id_asg_cur;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_temas()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$id_mat=$nivel=$this->input->post('id_mat');
		$id_prof=$nivel=$this->input->post('id_prof');
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$list=$this->mat->get_temas($cod_nivel,$cod_curso,$gest,$id_mat,$id_prof);
		
		//print_r($list);
		//exit();
		$data=array();
		$data1=array();
		foreach ($list as $temas ) {
			$data[]=$temas->tema;
			$data1[]=$temas->id;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_profesor()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$id_mat=$nivel=$this->input->post('id_mat');
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$list=$this->mat->get_profesor($cod_nivel,$cod_curso,$gest,$id_mat);
		
		$data=array();
		$data1=array();
		foreach ($list as $profesor ) {
			$data[]=$profesor->appaterno." ".$profesor->apmaterno." ".$profesor->nombre;
			$data1[]=$profesor->id_prof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_materias()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$list=$this->mat->get_rows_curso_materia($cod_nivel,$cod_curso,$gest);
		
		$data=array();
		$data1=array();
		foreach ($list as $materias ) {
			$data[]=$materias->nombre;
			$data1[]=$materias->id_mat;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_get_area()
	{
		$id_mat=$this->input->post('id_mat');
		$materia=$this->mat->get_materiass($id_mat);
		// print_r($id_mat);
		// exit();
		$area=$this->mat->get_materias_area($materia->id_mat);
		$data=array();
		$data[]=$area->nombre;
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}
	public function ajax_get_materia()
	{
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$list=$this->mat->rows_curso_materia($nivel,$curso);
		// print_r($list);
		// exit();
		$data=array();
		$data1=array();
		foreach ($list as $materia ) {
			$data[]=$materia->materia;
			$data1[]=$materia->id_mat;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_get_curso2()
	{
		$table=$this->input->post('table');
		$idcur=$this->input->post('idcur');

		$list=$this->mat->get_rows_curso2($table,$idcur);

		$data=array();
		foreach ($list as $curso ) {
			$data[]=$curso->curso;
			$data[]=$curso->corto;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);

	}

	public function ajax_get_corto()
	{
		$table=$this->input->post('table');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');

		$list=$this->mat->get_rows_corto($table,$curso,$nivel);
		$data=array();
		foreach ($list as $curso ) {
			$data[]=$curso->corto;
			$data[]=$curso->idcurso;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_profe()
	{
		$list=$this->mat->get_rows_profe();
		$data=array();
		$data1=array();
		foreach ($list as $profe ) {
			$data[]=$profe->appaterno." ".$profe->apmaterno." ".$profe->nombre;
			$data1[]=$profe->id_prof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);
	}
	public function ajax_get_profe2()
	{
		$table=$this->input->post('table');
		$idprof=$this->input->post('idprof');
		$list=$this->mat->get_rows_profe2($table,$idprof);
		$data=array();
		foreach ($list as $profe ) {
			$data[]=$profe->appaterno." ".$profe->apmaterno." ".$profe->nombres;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_idprofe()
	{
		$table=$this->input->post('table');
		$txt=$this->input->post('profe');

		$n=strpos($txt," ");
		$appat=substr($txt,0,$n);
		$txt2=substr($txt,$n+1,strlen($txt));

		$n2=strpos($txt2," ");
		$apmat=substr($txt2,0,$n2);

		$nomb=substr($txt2,$n2+1,strlen($txt2));

		//print_r($appat."-".$apmat."-".$nomb);

		$list=$this->mat->get_rows_idprofe($table,$appat,$apmat,$nomb);
		$data=array();
		foreach ($list as $profe ) {
			$data[]=$profe->idprof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_set_profesor()
	{ 

		$materia=$this->input->post('materia');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$idprof=$this->input->post('idprof');
		$gestion=$this->input->post('gestion');
		$materias=$this->mat->get_materia($materia);
		// print_r($materias);
		// exit();
		$data=array(
			'codigo'=>$materias->codigo."-".$materia."-".$idprof,
			'gestion'=>$gestion,
			'id_asg_mate'=>$materia,
			'id_prof'=>$idprof,			
		);
		
		$insert=$this->mat->save_mat($data);
		
		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_list()
	{
		
		$list=$this->mat->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		
		foreach ($list as $materia) {
			$no++;
			$row = array();
			$row[] = $materia->idmat;
			$row[] = $materia->materia;
			$row[] = $materia->area;
			$row[] = $materia->campo;
			$row[] = $materia->idcurso;
			$row[] = $materia->curso;
			$row[] = $materia->nivel;
			$row[] = $materia->idprof;
			$row[] = $materia->gestion;
						
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_materia('."'".$materia->idmat."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_materia('."'".$materia->idmat."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;

			//print_r($data);
		}

		//print_r($data);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mat->count_all(),
						"recordsFiltered" => $this->mat->count_filtered(),
						"data" => $data,
				);
		
		echo json_encode($output);

	}

public function subircompomiso()
	{ 
		 //$excel=$_FILES['planilla'];
		 //$excel['name'];
		//print_r($excel['name']);
		//print_r("tema:".$_POST['id_tema']);
		//print_r("tarea:".$_POST['id_tarea']);
		//exit();
		 //print_r($_POST['Fprofe']);
		 //exit();
		//$nombre_carpeta='./public/planillas/Subirtareas';
		//$nombre_carpeta='/home/ARCHIVOS/COMPROMISO';

		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();

		$gestion=$this->mat->get_gestion(); 
		$gest=$gestion->gestion;	
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		$ids=explode('-', $estudiante->codigo."-", -1);
		$curso=$ids[0];	
		$nivel=$ids[1];	
		$compromiso=$this->mat->get_compromiso($id_est,$gest);
		if (is_null($compromiso)) {
			# code...
		}else{

		unlink("/run/media/server/DATOS/ARCHIVOS/COMPROMISO/2020/".$compromiso->nombre);
		$this->mat->delete_by_id($compromiso->id,"Compomiso");
		}

		$nombre_carpeta='/run/media/server/DATOS/ARCHIVOS/COMPROMISO/2020';
		if(!is_dir($nombre_carpeta)){
			@mkdir($nombre_carpeta, 0700);
			}
		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('planilla')){
			echo 'no subio';
			echo $this->upload->display_errors();
		}
		else{
			$excel = $this->upload->data();
		}
 
		$url=$excel['full_path'];
		$nombre=$excel['file_name'];

		try {
    		//$inputFileType = PHPExcel_IOFactory::identify($nombre);
    		//$objReader = PHPExcel_IOFactory::createReader($inputFileType);
    		//$objPHPExcel = $objReader->load($nombre);
		} catch(Exception $e) {
   			 die('Error loading file "'.pathinfo($nombre,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		$data=array(
			'cod_curso'=>$curso,
			'cod_nivel'=>$nivel,
			'id_est'=>$id_est,
			'gestion'=>$gest,
			'url'=>$url,
			'nombre'=>$nombre
		);		
			//print_r($data);
		$insert=$this->mat->insert($data,"Compomiso");
		
	echo "<script type='text/javascript'>alert('TU COMPROMISO SE SUBIO  CORRECTAMENTE');</script>";
	redirect(base_url().'Estudiantes_su_contr/', 'refresh');

	//echo '</table>';
	//alert("se inserto correctamente");

	}
	public function subir()
	{ 
		 $excel=$_FILES['planilla'];
		 //print_r($excel);
		 //exit();
		//print_r($excel['name']);
		//print_r("tema:".$_POST['id_tema']);
		//print_r("tarea:".$_POST['id_tarea']);
		//exit();
		 //print_r($_POST['Fprofe']);
		 //exit();
		//$nombre_carpeta='./public/planillas/Subirtareas';
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$nombre_carpeta='./upload/student/2021/'.$appat.' '.$apmat.' '.$name;
		/* Creamos carpeta delm estudiante si no existe*/
		if (!file_exists($nombre_carpeta)) {
			mkdir($nombre_carpeta, 0777, true);
		}
		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = '*';

		$config['file_name']=$id_est.'_'.$_POST['id_tarea'];
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('planilla')){
			echo 'no subio';
			$this->response = $this->upload->display_errors();
			//exit();
			 $error = $this->upload->display_errors();
        $result = array('error' => true, 'mens' => $error, 'estado' => 3);
        echo json_encode($result);
		}
		else{
			$excel = $this->upload->data();
		}

		$url=$nombre_carpeta;
		$nombre=$excel['file_name'];

		try {
    		//$inputFileType = PHPExcel_IOFactory::identify($nombre);
    		//$objReader = PHPExcel_IOFactory::createReader($inputFileType);
    		//$objPHPExcel = $objReader->load($nombre);
		} catch(Exception $e) {
   			 die('Error loading file "'.pathinfo($nombre,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		$gest=$gestion->gestion;	
		$data=array(
			'url'=>$url,
			'nombre'=>$nombre,
			'id_tema'=>$_POST['id_tema'],
			'id_tarea'=>$_POST['id_tarea'],
			'gestion'=>$gest,
			'id_est'=>$id_est
		);		
			//print_r($data);
		$insert=$this->mat->insert($data,"tarea_subir");
		
	echo "<script type='text/javascript'>alert('TU TAREA SE SUBIÓ CORRECTAMENTE');</script>";
	redirect(base_url().'Estudiantes_su_contr/', 'refresh');

	//echo '</table>';
	//alert("se inserto correctamente");

	}
	public function tarea_faltante() 
	{
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		$gest=$gestion->gestion;	
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$curso=$ids[0];	
		$nivel=$ids[1];	
		$mater=$this->mat->tarea_materia($gest,$curso,$nivel);
		$tema=array();
		$materia=array();
		$porf=array();
		$fecha=array();
		foreach ($mater as $materias) {			
			$tarea=$this->mat->tarea_tema($gest,$curso,$nivel,$materias->id_materia);
			foreach ($tarea as $tareas) {		
				$veri=$this->mat->verificar_tarea($id_est,$tareas->id_tema,$tareas->id_tarea,$gest);

				if(is_null($veri)){
					$materia[]=$materias->materia;
					$porf[]=$tareas->appaterno." ".$tareas->apmaterno." ".$tareas->nombre;
					$fecha[]=$tareas->fecha;
					$tema[]=$tareas->tema;
				}	
			}
		}
		$output=array(
			"status"=>TRUE,
			"materia"=>$materia,
			"tema"=>$tema,
			"fecha"=>$fecha,
			"porf"=>$porf
		);

		echo json_encode($output);
	}

	public function descarga_correo($id)
	{
	     $nombre_carpeta='./upload/correos/PM';
	     //$nombre_carpeta='/home/ARCHIVOS/ESTUDIANTES';
	     if(!is_dir($nombre_carpeta)){
			@mkdir($nombre_carpeta, 0700);
			}
            $file = file_get_contents($nombre_carpeta.'/'.$id);
            //download file from directory
		
            force_download($id, $file);
	}
	public function ajax_list_temas($id)
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$pwd_access = $this->session->userdata("access");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		$gest=$gestion->gestion;	
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$curso=$ids[0];	
		$nivel=$ids[1];	
		$ids=explode('-', $id."-", -1);
		$materia=$ids[0];
		$id_prof=$ids[1];
		$subir = array();
		$descarga = array();

		$list=$this->mat->get_datatables_gestion($curso,$nivel,$gest,$materia,$id_prof);
		$j=-1;
		$d=-1;
		$s=-1;
		$urls="Estudiantes_su_contr/subir";
		foreach ($list as $temas) {
			$j++;
			//print_r($list);
			//exit();
			$veri=$this->mat->verificar_tarea($id_est,$temas->id_tema,$temas->id_tarea,$gest);	
			if(is_null($veri)){
				$s++;
				$subir[$s]["estado"] = "FALTA ENTREGAR";
				$subir[$s]["materia"] = $temas->materia;
				$subir[$s]["profe"] = $temas->appaterno." ".$temas->apmaterno." ".$temas->nombre;
				$subir[$s]["tema"] = $temas->tema;
				if($temas->fechaentrega == ''){
					$subir[$s]["fecha_entrega"] = '-';
				} else {
					$subir[$s]["fecha_entrega"] = $temas->fechaentrega;
				}
				$subir[$s]["fecha_subida"] = '-';
				$subir[$s]["hora_subida"] = '-';
				$subir[$s]["accion"] = '
				<form action="'.$urls.'" method="post" enctype="multipart/form-data">
					<input id="id_tema" name="id_tema" type="hidden" value="'.$temas->id_tema.'">
					<input id="id_tarea" name="id_tarea" type="hidden" value="'.$temas->id_tarea.'">
					<input type="file" name="planilla" id="planilla" />
					<button class="btn btn-primary" onclick="'.$urls.'" id="bt2l"><i class="glyphicon glyphicon-cloud-upload"></i>SUBIR</button>
				</form>';
			}else{

				$d++;
				$descarga[$d]["estado"] = "ENTREGADO";
				$descarga[$d]["materia"] = $temas->materia;
				$descarga[$d]["profe"] = $temas->appaterno." ".$temas->apmaterno." ".$temas->nombre;
				$descarga[$d]["tema"] = $temas->tema;
				if($temas->fechaentrega == ''){
					$descarga[$d]["fecha_entrega"] = '-';
				} else {
					$descarga[$d]["fecha_entrega"] = $temas->fechaentrega;
				}
				$descarga[$d]["fecha_subida"] = $veri->fechas;
				$descarga[$d]["hora_subida"] = $veri->hora;
				if($pwd_access === "157f3261a72f2650e451ccb84887de31746d6c6c") { // dev_test: agrega acceso root
					$descarga[$d]["accion"] = '<a class="btn btn-sm btn-primary"  title="Descargar " href="'.base_url().'Profesor_de_contr/descarga_tarea/'.$veri->id.'"><i class="glyphicon glyphicon-download"></i>Descargar</a>
					<br><a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_temas('."'".$veri->id."'".')"><i class="glyphicon glyphicon-trash"></i>Eliminar</a>
					';	
				} else {
					$descarga[$d]["accion"] = '<a class="btn btn-sm btn-primary"  title="Descargar " href="'.base_url().'Profesor_de_contr/descarga_tarea/'.$veri->id.'"><i class="glyphicon glyphicon-download"></i>Descargar</a>';	
				}
			}
		}
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		$ss=-1;
		$dd=-1;
		foreach ($subir as $subirs) {
			$no++;
			$ss++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $subir[$ss]["estado"];
			$row[] = $subir[$ss]["materia"];
			$row[] = $subir[$ss]["profe"];
			$row[] = $subir[$ss]["tema"];
			$row[] = $subir[$ss]["fecha_entrega"];
			$row[] = $subir[$ss]["fecha_subida"];
			$row[] = $subir[$ss]["hora_subida"];
			$row[] = $subir[$ss]["accion"];	
			$data[] = $row;

		}
		foreach ($descarga as $descargas) {
			$no++;
			$i++;
			$dd++;
			$row = array();
			$row[] = $i;
			$row[] = $descarga[$dd]["estado"];
			$row[] = $descarga[$dd]["materia"];
			$row[] = $descarga[$dd]["profe"];
			$row[] = $descarga[$dd]["tema"];
			$row[] = $descarga[$dd]["fecha_entrega"];
			$row[] = $descarga[$dd]["fecha_subida"];
			$row[] = $descarga[$dd]["hora_subida"];
			$row[] = $descarga[$dd]["accion"];
			$data[] = $row;

		}
		// print_r($list);
		// exit();

		/*foreach ($list as $temas) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $temas->materia;
			$row[] = $temas->appaterno." ".$temas->apmaterno." ".$temas->nombre;
			$row[] = $temas->tema;
			$row[] = $temas->fecha;
			$row[] = $temas->hora;
			$row[] = '<a class="btn btn-sm btn-primary"  title="Descargar " href="http://181.115.156.38/donbosco/Profesor_de_contr/descarga_tarea/'.$temas->archivo.'"><i class="glyphicon glyphicon-download"></i>Descargar</a>';	
			$data[] = $row;

		}*/
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->mat->count_all(),
				"recordsFiltered" => $this->mat->count_filtered_gestion($curso,$nivel,$gest,$materia,$id_prof),
				"data" => $data,
		);	
		
		
		echo json_encode($output);

	}

	public function ajax_delete_temas($id)
	{
		$tarea=$this->mat->get_subir_tarea($id);	
		$data=array(
			'id_est'=>$tarea->id_est,
			'id_tema'=>$tarea->id_tema,
			'id_tarea'=>$tarea->id_tarea,
			'id_mat'=>$tarea->id_mat
		);		
			//print_r($data);
		$insert=$this->mat->insert($data,"bitacora_tarea");
		unlink("/home/ARCHIVOS/ESTUDIANTES/".$tarea->nombre);
		$this->mat->delete_by_id($id,"tarea_subir");
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_delete_tareas($id)
	{
		$this->mat->delete_by_id($id,"tareas");
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_profesor($id)
	{
		$data=$this->mat->get_by_id($id);
		//print_r($data);
		echo json_encode($data);
	}

	public function ajax_update_profeso()
	{
		$id=$this->input->post('id');
		$materia=$this->input->post('materia');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$idprof=$this->input->post('idprof');
		$gestion=$this->input->post('gestion');
		$materias=$this->mat->get_materia($materia);
		// print_r($materias);
		// exit();
		$data=array(
			'codigo'=>$materias->codigo."-".$materia."-".$idprof,
			'gestion'=>$gestion,
			'id_asg_mate'=>$materia,
			'id_prof'=>$idprof,			
		);			
		
		$this->mat->update(array('id_asg_prof'=>$id),$data);

		echo json_encode(array("status"=>TRUE));
	}

	public function temas()
	{
		$id=$this->input->post('id');
		$materia=$this->input->post('materia');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$idprof=$this->input->post('id_prof');
		$gestion=$this->input->post('gestion');
		$tema=$this->mat->get_temas($materia,$curso,$nivel,$idprof,$gestion);
		$data=array();
		$data1=array();
		foreach ($tema as $temas) {
			$data[]=$temas->id;
			$data1[]=$temas->tema;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
			"data1"=>$data1,
		);

		echo json_encode($output);
	}

	public function generar($id)
	{
		$ids=explode('-', $id."-", -1);
		// print_r($ids);
		// exit();
		$curso=$ids[2]; 
		$nivel=$ids[1];
		$gestion=$ids[0];
		$niveles=$this->mat->get_nivel($nivel);
		$estudiante=$this->mat->get_rows_nota_prom($nivel,$curso,$gestion);
		print_r($niveles);
		exit();
		foreach ($estudiante as $estudiantes ) {
			$materia=$this->mat->get_materia_secundaria($curso);
			$area_estu=$this->mat->nota_area($gestion,$estudiantes->id_est);
			foreach ($materia as $materias ) {
				$mat_estu=$this->mat->nota_materia($gestion,$estudiantes->id_est,$materias->id_mat);
				if($mat_estu=null)
				{
				}
			}
			
			if($area_estu=null)
			{
			}
		}
		$list=$this->mat->get_rows_idprofe($table,$appat,$apmat,$nomb);
		$data=array();
		foreach ($list as $profe ) {
			$data[]=$profe->idprof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

/*
	public function print()
	{
		$this->load->library('pdf');
		//$list=$this->mat->get_print_prof_pdf();
		
		

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE PROFESORES");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(15,7,'ID','TBL',0,'C','1');
    		$this->pdf->Cell(15,7,'ITEM','TBL',0,'C','1');
    		$this->pdf->Cell(15,7,'CI','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'DIRECCION','TBLR',0,'C','1');
    		$this->pdf->Cell(15,7,'FONO','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $prof) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$prof->idest,'TBL',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->idprof,'TBL',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->item,'TBLR',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->ci,'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,strtoupper($prof->appaterno),'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,strtoupper($prof->apmaterno),'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,strtoupper($prof->nombres),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,$prof->direccion,'TBLR',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->telefono,'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$prof->genero,'TBLR',0,'L',0);
		     // $this->pdf->Cell(10,5, $this->Image($prof->foto,180,8,15,0),'TBLR',0,'C',0);
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Profesores.pdf", 'I');

		    ob_end_flush();


	}
*/

}
