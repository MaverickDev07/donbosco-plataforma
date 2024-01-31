<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Est_estudiante_contr extends CI_Controller  {
 
	  
   
	public function __construct()  
	{
		parent::__construct();		   
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->model('Est_estudiante_model','estud');
		//$this->load->model('Config_curso_model','curso');
		$this->load->library('excel');
		$this->load->library('drawing'); 

		if(!$this->session->userdata("login"))
		{
			$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
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
		$this->load->view('Est_estudiante_view');
		$this->load->view('layouts/fin');
	}
	// public function ajax_get_id()
	// {
	// 	//valores enviados 
	// 	$table=$this->input->post('table');
	// 	$cod=$this->input->post('cod');

	// 	$codgen='';
	// 	$num_rows=$this->curso->get_count_rows($table);
	// 	if($num_rows!=0)
	// 	{
	// 		$n=strlen($cod);		
	// 		$list = $this->curso->get_idcod_table($table);
	// 		$may=0;
				
	// 		foreach ($list as $codigo) {	
	// 			$idcod=$codigo->idcurso;//considerar nombre del id;				
	// 			$newcod=substr($idcod,$n,strlen($idcod));
	// 	        if($newcod>=$may)
	// 	        {
	// 	            $may=$newcod;    
	// 	        }
	// 		}
	// 		$may=$may+1;
	// 		$codgen=$cod."".$may;
	// 	}
	// 	else
	// 	{ 
	// 		$codgen=$cod.'1';	
	// 	}
		
	// 	echo json_encode(array("status"=>TRUE,"codgen"=>$codgen));
	// }

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}
	
	
	public function ajax_list()
	{
		
		$list=$this->estud->get_datatables();

		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $estudiante) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			//$row[] = $estudiante->idcurso;
			$curso_=$this->estud->get_curso_est($estudiante->idest);	
			$row[] = $curso_->curso;
			$row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";
			if($this->session->userdata("appat")=='ARCIENEGA' AND $this->session->userdata("apmat")=='ZAMORANO' AND $this->session->userdata("name")=='WILSON')
			{	
				$row[]="";
			}else{
			//add html for action
				$row[] = '<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
			}
			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="EditRUDE" onclick="edit_rude('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Print RUDE" onclick="print_rude('."'".$estudiante->idest."'".')"><i class="icon icon-file-pdf"></i></a>
				<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Print CTTO" onclick="print_ctto('."'".$estudiante->idest."'".')"><i class="icon icon-file-pdf"></i></a>';



			$data[] = $row;

		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->estud->count_all(),
						"recordsFiltered" => $this->estud->count_filtered(),
						"data" => $data,
				);

		echo json_encode($output);

	}

	public function ajax_get_idins($id)
	{
		$list=$this->estud->get_idinscrip($id);
		$idins=$list->idinscrip;	
		
		echo json_encode(array("status"=>TRUE,"idins"=>$idins));	
	}




	public function ajax_delete($id)
	{
		$list=$this->estud->get_idinscrip($id);
		$idins=$list->idinscrip;		
		$this->estud->delete_by_id($id,$idins);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_estud($id)
	{
		//print_r($id);
		$data=$this->estud->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_estud()
	{
		//valores enviados 
		$data=array(
			//'idest'=>$this->input->post('idest'),
			'rude'=>$this->input->post('rude'),
			'ci'=>$this->input->post('ci'),
			'appaterno'=>$this->input->post('appaterno'),
			'apmaterno'=>$this->input->post('apmaterno'),
			'nombres'=>$this->input->post('nombre'),
			'genero'=>$this->input->post('genero'),
			'codigo'=>$this->input->post('codigo'),
			'debe'=>$this->input->post('debe'),
			'repitente'=>$this->input->post('repite'),
			'foto'=>$this->input->post('foto'),
			'observ'=>$this->input->post('observ')
		);
		
		$this->estud->update(array('idest'=>$this->input->post('idest')),$data);

		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_save_estud()
	{

		//valores enviados 
		$data=array(
			
			// 'idest'=>$this->input->post('idest'),
			'rude'=>$this->input->post('rude'),
			'ci'=>$this->input->post('ci'),
			'appaterno'=>$this->input->post('appaterno'),
			'apmaterno'=>$this->input->post('apmaterno'),
			'nombre'=>$this->input->post('nombres'),
			'genero'=>$this->input->post('genero'),
			'preinscripcion'=>true
			// 'idcurso'=>$this->input->post('idcurso'),
			// 'nivel'=>$this->input->post('nivel'),
			// 'gestion'=>$this->input->post('gestion'),
			// 'colegio'=>$this->input->post('colegio'),
			// 'codigo'=>$this->input->post('codigo')
		);
		$insert=$this->estud->save_estud_news("estudiantes",$data);
		
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_get_gestion()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->estud->get_rows_gestion($table); //envia
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

	public function ajax_get_nivel()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->estud->get_rows_nivel($table); //envia
		$data=array();
		foreach ($list as $nivel) {			
			$data[] =$nivel->nivel." ".$nivel->turno;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_level()
	{
		$table=$this->input->post('table');//recibe
		$lvl=$this->input->post('lvl');

		
		$list=$this->estud->get_rows_level($table,$lvl); //envia

		$data=array();
		foreach ($list as $level) {			
			$data[] =$level->gestion;					 
			$data[] =$level->colegio;
			$data[]	=$level->idcurso;
			$data[]	=$level->curso;				 				 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}


	public function ajax_get_curso()
	{
		$tablecur=$this->input->post('TablaCur');
		$nivel=$this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list=$this->estud->get_rows_curso($tablecur,$nivel);

		$data=array();
		foreach ($list as $curso){
			$data[]=$curso->curso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
	}

	public function ajax_get_idcurso()
	{

		
		$nivel=$this->input->post('EstNivel');
		$curso=$this->input->post('EstCurso');

		$list=$this->estud->get_idcurso($nivel,$curso);

		$data=array();
		foreach($list as $idcur)
		{
			$data[]=$idcur->idcurso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
		//print_r($nivel."-".$gestion."-".$colegio."-".$curso);	
	}

	public function ajax_list_idcurso($id)
	{		
		//print_r($id);
		$n=strlen($id);
		$id1=substr($id,0,4);
		$id2=substr($id,4,$n);
		//print_r($id1."?".$id2);

		$list=$this->estud->get_datatables_by_idcur($id1,$id2);
		$data = array();
		$no = $_POST['start'];
		$i=0;
		foreach ($list as $estudiante) {
			//$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			$curso_=$this->estud->get_curso_est($estudiante->idest);
			//$row[] = $estudiante->idcurso;	
			$row[] = $curso_->curso;
			$row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";
			if($this->session->userdata("idcod") == 'US-1683' && $this->session->userdata("idcod") == 'US-1683') // dev_test: solo puede acceder los administradores
			{	
				$row[] = '<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
			}else{
				//add html for action
				$row[]="";
			}

			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="EditRUDE" onclick="edit_rude('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Print RUDE" onclick="print_rude('."'".$estudiante->idest."'".')"><i class="icon icon-file-pdf"></i></a>
				<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Print CTTO" onclick="print_ctto('."'".$estudiante->idest."'".')"><i class="icon icon-file-pdf"></i></a>';

			$data[] = $row;

		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->estud->count_all(),
						"recordsFiltered" => $this->estud->count_filtered_byid($id1,$id2),
						"data" => $data,
				);

		echo json_encode($output);

	}

	public function ajax_get_idestnew()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');
		$idcamp='idest';

		$codgen='';
		$num_rows=$this->estud->get_count_rows_est($idcamp,$table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->estud->get_idcod_table_est($idcamp,$table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idest;
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
//

	public function print_generoCursoColegio($id)
	{ 
		
		$gestion=$id;


		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE GENEROS'),0,0,'C');
            $this->pdf->Ln('5'); 


            $this->pdf->Cell(30); 
			$this->pdf->setXY(15,55);
			$this->pdf->SetFont('Arial','B',10);
            
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setXY(35,55);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');
            
            $this->pdf->setX(65);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(80);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($nivel),0,0,'L');

            

            $this->pdf->Cell(30); 
			$this->pdf->setX(115); 
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('CURSO: '),0,0,'L');
             $this->pdf->setX(130); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setXY(15,65);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('F',$nivel,$colegio,$gestion,$cursos)),0,0,'L');
    		$this->pdf->setXY(15,70);
           
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('M',$nivel,$colegio,$gestion,$cursos)),0,0,'L');

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_report_fecha_exel($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0]; 
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		

		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		
		$curso=$this->estud->get_print_curso_pdf($cursos);

		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');

        $estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '045aaa')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $estilobor = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
        )
	);
        //Contador de filas
        $contador = 1;
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(45);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(65);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);


     $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	 //$this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $nivel);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'COLEGIO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $colegio);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $gestion);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $curso->curso);
        $contador++;$contador++;

        //Le aplicamos negrita a los títulos de la cabecera.
        //$this->excel->getActiveSheet()->getStyle("A{$contador}:L{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("A{$contador}:Q{$contador}")->applyFromArray($estilo);
        /*$this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("G{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("H{$contador}")->getFont()->setBold(true);*/
       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'AP. PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'AP. MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'RUDE');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'FEC. NACIMIENTO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'C.I.');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'EDAD');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'GEN');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'PADRE/MADRE/TUTOR');
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'DIRECCION DEL ESTUDIANTE');
        $this->excel->getActiveSheet()->setCellValue("L{$contador}", 'TEl PADRE');
        $this->excel->getActiveSheet()->setCellValue("M{$contador}", 'CEL PADRE');
        $this->excel->getActiveSheet()->setCellValue("N{$contador}", 'TEl MADRE');
        $this->excel->getActiveSheet()->setCellValue("O{$contador}", 'CEl MADRE');
        $this->excel->getActiveSheet()->setCellValue("P{$contador}", 'TEl TUTOR');
        $this->excel->getActiveSheet()->setCellValue("Q{$contador}", 'CEl TUTOR');
        
    
        $x = 1;
		 foreach ($list as $estud) {
		$padre=$this->estud->get_print_padre($estud->idest);
		$madre=$this->estud->get_print_madre($estud->idest);
		$tutor=$this->estud->get_print_tutor($estud->idest);
		if($estud->vivecon=="Padre y Madre")
		{
			if($padre=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$padre->appat." ".$padre->apmat." ".$padre->nombres;
				$tel1=$padre->ofifono;
				$cel1=$padre->celular;
			}		
		}
		if($estud->vivecon=="Solo Madre")
		{
			if($madre=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$madre->appat." ".$madre->apmat." ".$madre->nombres;
				$tel1=$madre->ofifono;
				$cel1=$madre->celular;
			}			
		}
		if($estud->vivecon=="Tutor")
		{
			if($tutor=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$tutor->appat." ".$tutor->apmat." ".$tutor->nombres;
				$tel1=$tutor->ofifono;
				$cel1=$tutor->celular;
			}		
		}
		if($estud->vivecon=="Solo Padre")
		{
			if($padre=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$padre->appat." ".$padre->apmat." ".$padre->nombres;
				$tel1=$padre->ofifono;
				$cel1=$padre->celular;
			}			
		}
		 	$contador++;
		 	if($this->estud->get_print_fecha_pdf($estud->idest)=='0')
		 	{
		 		$dia='Re';	
		 		$mes='Re';	
		 		$anio='Re';	
		 		
		 	}
		 	else {
		 		$fecha=$this->estud->get_print_fecha_pdf($estud->idest);

		 		$dia=$fecha->dia;	
		 		$mes=$fecha->mes;	
		 		$anio=$fecha->anio;
		 	 
		 	}
		 	if($this->estud->get_estudiante_telefono($estud->idest)=='0')
		 	{
		 		//$fe='Re';	
		 		$ce='Re';
		 		$fp='Re';	
		 		$cp='Re';
		 		$fm='Re';	
		 		$cm='Re';
		 		$ft='Re';	
		 		$ct='Re';
		 		
		 	}
		 	else {
		 		$f=$this->estud->get_estudiante_telefono($estud->idest);
		 		//$fe=$f->foest;	
		 		$ce=$f->ceest;
		 		$fp=$f->fpa;	
		 		$cp=$f->cpa;
		 		$fm=$f->fma;	
		 		$cm=$f->cma;
		 		$ft=$f->ftu;	
		 		$ct=$f->ctu;
		 	 
		 	}

		 	if($this->estud->get_idinscrip_localizacion($estud->idest)=='0')
		 	{
		 		$dire='Re';	
		 		
		 	}
		 	else{
		 	  $direct=$this->estud->get_idinscrip_localizacion($estud->idest);
		      $dire='Zona : '.$direct->zona.' calle : '.$direct->calle.' N:'.$direct->num;
		      $dire=str_replace("SIN NUMERO", "S/N", $dire);
		      $dire=str_replace("CALLE", "c/", $dire);
		 	}
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:Q{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
       	 	$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", ' '. $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", ' '.$estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", ' '.$estud->rude);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $dia.'/'.$mes.'/'.$anio);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador}", ' '.date('Y')-$anio);
        	$this->excel->getActiveSheet()->setCellValue("I{$contador}", $estud->genero);
        	$this->excel->getActiveSheet()->setCellValue("J{$contador}", ' '.$nombrev);
        	$this->excel->getActiveSheet()->setCellValue("K{$contador}", ' '.$dire);
        	$this->excel->getActiveSheet()->setCellValue("L{$contador}", ' '.$fp);
        	$this->excel->getActiveSheet()->setCellValue("M{$contador}", ' '.$cp);
        	$this->excel->getActiveSheet()->setCellValue("N{$contador}", ' '.$fm);
        	$this->excel->getActiveSheet()->setCellValue("O{$contador}", ' '.$cm);
        	$this->excel->getActiveSheet()->setCellValue("P{$contador}", ' '.$ft);
        	$this->excel->getActiveSheet()->setCellValue("Q{$contador}", ' '.$ct);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->curso}_{$curso->nivel}_{$curso->colegio}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}

	public function print_report_est_curso($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		
		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);


		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        //Contador de filas
        $contador1 = 2;
       
        $estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '045aaa')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $estilobor = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
        )
	);

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
      
      	 $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	 //$this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $nivel);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'COLEGIO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $colegio);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $gestion);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $curso->curso);
        $contador1++;$contador1++;

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:H{$contador1}")->applyFromArray($estilo);
        

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'RUDE');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'CI');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", 'GEN');
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", 'FECHA NAC.');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador1++;
		 	//$lugarNac='';
		 	if($this->estud->get_print_fecha_pdf($estud->idest)=='0')
		 	{
		 		$dia='Re';	
		 		$mes='Re';	
		 		$anio='Re';
		 		
		 		$fec='Re';	
		 		
		 	}
		 	else {
		 		$fecha=$this->estud->get_print_fecha_pdf($estud->idest);
		 		$dia=$fecha->dia;	
		 		$mes=$fecha->mes;
		 		$anio=$fecha->anio;
		 		$fec= $dia.'/'.$mes.'/'.$anio;
		 		//$fec= strtotime('September');
		 	 
		 	}

		 	$this->excel->getActiveSheet()->getStyle("A{$contador1}:H{$contador1}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador1}", ' '.$estud->rude);
		    $this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador1}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador1}", $estud->genero);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador1}", $fec);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->curso}_{$curso->nivel}_{$curso->colegio}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}

	public function print_report_lugar_nac($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		
		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);


		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        //Contador de filas
        $contador = 1;
       
        $estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '045aaa')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $estilobor = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
        )
	);

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(80);
        //$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
       /* $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);*/

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:J{$contador}")->applyFromArray($estilo);
        /*$this->excel->getActiveSheet()->getStyle("A{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("G{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("H{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("I{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("J{$contador}")->getFont()->setBold(true);
        //$this->excel->getActiveSheet()->getStyle("K{$contador}")->getFont()->setBold(true);*/
       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'CI');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'DIA');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'MES');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'AÑO');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'GEN');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'LUGAR DE NACIMIENTO');
        //$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'DIRECCION');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador++;
		 	//$lugarNac='';
		 	if($this->estud->get_print_fecha_pdf($estud->idest)=='0')
		 	{
		 		$dia='Re';	
		 		$mes='Re';	
		 		$anio='Re';
		 		$lugarNac='Re';	
		 		
		 	}
		 	else {
		 		$fecha=$this->estud->get_print_fecha_pdf($estud->idest);
		 		$lugarNac='Pais: '.$fecha->pais.' Departamento: '.$fecha->dpto.' lugar: '.$fecha->localidad;
		 		$dia=$fecha->dia;	
		 		$mes=$fecha->mes;
		 		$anio=$fecha->anio;
		 	 
		 	}

		 	if($this->estud->get_idinscrip_localizacion($estud->idest)=='0')
		 	{
		 		$dire='Re';	
		 		
		 	}
		 	else{
		 	  $direct=$this->estud->get_idinscrip_localizacion($estud->idest);
		      $dire='Zona : '.$direct->zona.' calle : '.$direct->calle.' N:'.$direct->num;
		      $dire=str_replace("SIN NUMERO", "S/N", $dire);
		      $dire=str_replace("CALLE", "c/", $dire);
		 	}
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:J{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $dia);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $mes);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador}", $anio);
        	$this->excel->getActiveSheet()->setCellValue("I{$contador}", $estud->genero);
        	$this->excel->getActiveSheet()->setCellValue("J{$contador}", $lugarNac);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->curso}_{$curso->nivel}_{$curso->colegio}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}

	public function print_report_fecha($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		

		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS'),0,0,'C');
            $this->pdf->Ln('5');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($gestion),0,0,'C');
            $this->pdf->Ln('6'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
    		$this->pdf->setX(35);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(75); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('COLEGIO:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		//$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		//$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		//$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(25,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'DIA','TBLR',0,'C','1');
    		$this->pdf->Cell(20,7,'MES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'A','TBLR',0,'C','1');
    		$this->pdf->Cell(55,7,'DIRECCION','TBLR',0,'C','1');
    		//$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');


    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		     // $this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      //$this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $fecha=$this->estud->get_print_fecha_pdf($estud->idest);
		      $direct=$this->estud->get_idinscrip_localizacion($estud->idest);
		      //$dire='Zona : '.$direct->zona.' calle : '.$direct->calle.' N:'.$direct->num;
		      $dire=''.$direct->calle.' N'.$direct->num;
		      $dire=str_replace("SIN NUMERO", "S/N", $dire);
		      $dire=str_replace("CALLE", "c/", $dire);
		      $this->pdf->Cell(25,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,utf8_decode(strtoupper($fecha->dia)),'TBLR',0,'L',0);
		      $this->pdf->Cell(20,5,utf8_decode(strtoupper($fecha->mes)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,utf8_decode(strtoupper($fecha->anio)),'TBLR',0,'L',0);
		      $this->pdf->Cell(55,5,utf8_decode(strtoupper($dire)),'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		    	      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_mejor_notas_pdf($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);;
		

		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);
		
		$list=$this->estud->get_promedio_nota($nivel,$gestion);
		//sort($list, SORT_NUMERIC);
//print_r($list);
	//exit();
		asort($list->nota);
		//var_export($list);


		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('MEJORES NOTAS'),0,0,'C');
            $this->pdf->Ln('5'); 


            $this->pdf->Cell(30); 
			$this->pdf->setXY(15,55);
			$this->pdf->SetFont('Arial','B',10);
            
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setXY(35,55);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');
            $this->pdf->Ln('6');
            $this->pdf->setXY(15,60); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(30);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($nivel),0,0,'L');

            

            $this->pdf->Cell(30); 
			$this->pdf->setX(90); 
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
             $this->pdf->setX(110); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setXY(15,65);
            $this->pdf->SetFont('Arial','B',10);

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);

			$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		//$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		//$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		//$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(20,7,'CURSO','TBLR',0,'C','1');
    		//$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		      //$this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      //$this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(20,5,utf8_decode(strtoupper($estud->curso)),'TBLR',0,'L',0);
		      $this->pdf->Cell(20,5,utf8_decode(strtoupper($estud->nota)),'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		      /*if($x==4)
		      {
		      	$this->pdf->Ln(40);

		    	$this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
		    	 ob_end_flush();
		      }	*/	      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);

		    }
		    $this->pdf->Ln(40);

		    	$this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
		    	 ob_end_flush();

		   


	}

	public function print_mejor_notas_exel($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		

		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);
		
		$list=$this->estud->get_promedio_nota($nivel,$gestion);

			$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');

        $contador = 1;
       
        $estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '045aaa')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $estilobor = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
        )
	);

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
        //$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->applyFromArray($estilo);

       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NOTAS');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'GEN');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador++;
		 
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->nota);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $estud->curso);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $estud->genero);
        	
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$nivel}_{$colegio}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}
	public function print_generoCurso($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);;
		

		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);


		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE GENEROS'),0,0,'C');
            $this->pdf->Ln('5'); 


            $this->pdf->Cell(30); 
			$this->pdf->setXY(15,55);
			$this->pdf->SetFont('Arial','B',10);
            
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setXY(35,55);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');
            $this->pdf->Ln('6');
            $this->pdf->setXY(15,60); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(30);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($nivel),0,0,'L');

            

            $this->pdf->Cell(30); 
			$this->pdf->setX(90); 
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('CURSO: '),0,0,'L');
             $this->pdf->setX(110); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setXY(15,65);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('F',$nivel,$colegio,$gestion,$cursos)),0,0,'L');
    		$this->pdf->setXY(15,70);
           
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('M',$nivel,$colegio,$gestion,$cursos)),0,0,'L');

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_genero($id)
	{

		$n=strlen($id);
		$id1=substr($id,0,4);//gestion
		$id2=substr($id,4,$n);//idcur

		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($id1,$id2);
		$generos=$this->estud->get_print_genero_pdf('F','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019');
		$curso=$this->estud->get_print_curso_pdf($id2);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE GENEROS'),0,0,'C');
            $this->pdf->Ln('5');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
			//primaria mañana
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('PRIMARIA MAÑANA'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('TECNICO HUMANISTICO DON BOSCO'),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,55);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')+$this->estud->get_print_genero_pdf('F','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		//secundaria mañana
    		 $this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,65);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,65);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('SECUNDARIA MAÑANA'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('TECNICO HUMANISTICO DON BOSCO'),0,0,'L');
            
            $this->pdf->Ln('8'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')+$this->estud->get_print_genero_pdf('F','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		//primaria tarde 
    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,85);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,85);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('PRIMARIA TARDE'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('DON BOSCO A'),0,0,'L');
            
            $this->pdf->Ln('8'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','PRIMARIA TARDE','DON BOSCO A','2019')),0,0,'L');
            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA TARDE','DON BOSCO A','2019')),0,0,'L');

    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,98);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA TARDE','DON BOSCO A','2019')+$this->estud->get_print_genero_pdf('F','PRIMARIA TARDE','DON BOSCO A','2019')),0,0,'L');


    		//secundaria tarde
    		 $this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,105);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,105);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('SECUNDARIA TARDE'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('DON BOSCO B'),0,0,'L');
            $this->pdf->Ln('8'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','SECUNDARIA TARDE','DON BOSCO B','2019')),0,0,'L');

            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA TARDE','DON BOSCO B','2019')),0,0,'L');


    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,120);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA TARDE','DON BOSCO B','2019')+$this->estud->get_print_genero_pdf('F','SECUNDARIA TARDE','DON BOSCO B','2019')),0,0,'L');

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		/*$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');*/
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		   /* foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		      $this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      $this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		      		      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }*/
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_report($id)
	{
		$n=strlen($id);
		$id1=substr($id,0,4);//gestion
		$id2=substr($id,4,$n);//idcur


		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($id1,$id2);
		$curso=$this->estud->get_print_curso_pdf($id2);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS'),0,0,'C');
            $this->pdf->Ln('5');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($id2),0,0,'L');
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($id1),0,0,'C');
            $this->pdf->Ln('6'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
    		$this->pdf->setX(35);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(75); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('COLEGIO:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		      $this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      $this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		      		      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_report_fono($id)
	{
		$ids=explode('-', $id, -1);
		$gestion=$ids[0]; 
		$nivel=$ids[1];
		$curso=$ids[2];

		if($nivel=="PT") {$nivel="PRIMARIA TARDE"; }
		if($nivel=="ST") {$nivel="SECUNDARIA TARDE"; }
		if($nivel=="PM") {$nivel="PRIMARIA MAÑANA"; }
		if($nivel=="SM") {$nivel="SECUNDARIA MAÑANA"; }
		if($curso=='1A'){$curso='PRIMERO A';}
		if($curso=='1B'){$curso='PRIMERO B';}
		if($curso=='2A'){$curso='SEGUNDO A';}
		if($curso=='2B'){$curso='SEGUNDO B';}
		if($curso=='3A'){$curso='TERCERO A';}
		if($curso=='3B'){$curso='TERCERO B';}
		if($curso=='4A'){$curso='CUARTO A';}
		if($curso=='4B'){$curso='CUARTO B';}
		if($curso=='5A'){$curso='QUINTO A';}
		if($curso=='5B'){$curso='QUINTO B';}
		if($curso=='5C'){$curso='QUINTO C';}
		if($curso=='6A'){$curso='SEXTO A';}
		if($curso=='6B'){$curso='SEXTO B';}
		if($curso=='6C'){$curso='SEXTO C';}
		// print_r($ids);
		// exit();
		$this->load->library('pdf');
		$list=$this->estud->get_curso_student($gestion,$ids[2],$ids[1]);
		// $curso=$this->estud->get_print_curso_pdf($id2);
		/*print_r($list);
		print_r($curso);
		exit();*/

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS- TELEFONOS'),0,0,'C');
            $this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('GRADO: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($gestion),0,0,'C');
            $this->pdf->Ln('6'); 
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(75); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($nivel),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('COLEGIO:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($ids[3]),0,0,'L');
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(20,7,'CEL PADRE','TBL',0,'C','1');
    		$this->pdf->Cell(20,7,'CEL MADRE','TBL',0,'C','1');
    		$this->pdf->Cell(18,7,'FONO FIJO','TBL',0,'C','1');
    		$this->pdf->Cell(20,7,'CEL TUTOR','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');

    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;

		    
		    foreach ($list as $estud) {
		    	//print_r($estud->idest);
		    	
		      $tutores=$this->estud->get_print_tutores("PADRE",$estud->id_est);
		      // print_r($tutores);
		      // exit();
		      $padre=$this->estud->get_print_padres($tutores->id_padre);
		      $tutores=$this->estud->get_print_tutores("MADRE",$estud->id_est);
		      $madre=$this->estud->get_print_padres($tutores->id_padre);
		      $tutores=$this->estud->get_print_tutores("TUTOR",$estud->id_est);
		      $tutor=$this->estud->get_print_padres($tutores->id_padre);

		   
		      //print_r($fonopadre->celular."-");
		     
		     // print_r($fonotutor->celular."-");
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		     $this->pdf->Cell(20,5,utf8_decode($padre->celular),'TBLR',0,'C',0);
		     $this->pdf->Cell(20,5,utf8_decode($madre->celular),'TBLR',0,'C',0);
		     $this->pdf->Cell(18,5,utf8_decode($padre->telefono),'TBLR',0,'C',0);

		     $this->pdf->Cell(20,5,utf8_decode($tutor->celular),'TBLR',0,'C',0);		     
		      		      
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombre)),'TBLR',0,'L',0);
		     	
		      
		    
		  /*  if($x==2)
		    {
		    		$this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
		    	exit();
		    }	 */     
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso."- ".$nivel." -".$gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function export_exel($id)
	{
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		$colegio=str_replace("TECNICO HUMANISTICO DON BOSCO", "TEC. HUM. DON BOSCO", $ids[2]);
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);

		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        
        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

        $contador = 1;
       
        $estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '045aaa')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $estilobor = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
        )
	);

        
		$contador=2;
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(28);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);

       // $this->excel->getActiveSheet()->getDrawingCollection() ; 
      	//$this->excel->getActiveSheet()->getHeaderFooter()->getImages();
      	//$this->excel->getDrawingCollection()->setImageResource('images/logo.png');


       // $gdImage = $this->excel->imagecreatefrompng('images/logo.png');
        //$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();

	$this->drawing->setName('Logotipo1');
	$this->drawing->setDescription('Logotipo1');
	$img = imagecreatefrompng('assets/images/logo.png');
	$this->drawing->setImageResource($img);
	$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$this->drawing->setHeight(100);
	$this->drawing->setCoordinates('A1');
	$this->drawing->setWorksheet($this->excel->getActiveSheet());

        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":H".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'LISTA DE ESTUDIANTES');
       
        	 $contador++;
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'U.E.');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", $colegio);
        //$contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $nivel);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $gestion);
        
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'AÑO DE ESCOR.');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", $curso->curso);
        $contador++;$contador++;

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($estilo);

       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'RUDE');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'CI');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'COD');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'GEN');
    
        $x = 9;
		 foreach ($list as $estud) {
		 	$contador++;
		 
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", ' '.$estud->rude);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", ' '.$estud->codigo);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador}", $estud->genero);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->corto}_{$nivel}_{$colegio}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}
	public function printxls($id)
	{
		/*
		require_once APPPATH."/third_party/phpexcel/phpexcel.php";
		require_once APPPATH."/third_party/phpexcel/PHPExcel/Writer/Excel2007.php";
		$list=$this->estud->get_print_estud_pdf($id);
		$curso=$this->estud->get_print_curso_pdf($id);

		//print_r($curso->corto);

		
		$this->objPHPExcel=new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("");
		$this->objPHPExcel->getProperties()->setLastModifieldBy("");
		$this->objPHPExcel->getProperties()->setTitle("");
		$this->objPHPExcel->getProperties()->setSubject("");
		$this->objPHPExcel->getProperties()->setDescription("");
		
		
		$this->objPHPExcel->setActiveSheetIndex(0);

		$this->objPHPExcel->getActiveSheet()->SetCellValue('A1','IDEST');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('B1','RUDE');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('C1','CI');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('D1','CODIGO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('E1','PATERNO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('F1','MATERNO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('G1','NOMBRES');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('H1','GENERO');

		$row=2;

		foreach ($list as $estud) {
			
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->rude);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->ci);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->codigo);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->appaterno);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->apmaterno);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->nombres);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->genero);

			$row++;
		}

		$filename="Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".xlsx";
		$this->objPHPExcel->getActiveSheet()->setTitle("Lista Alumnos ".$curso->corto);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$writer->save('php://output');
		exit;
		*/


	}
	

}
