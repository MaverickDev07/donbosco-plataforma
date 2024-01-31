<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_inscrip_edit_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->model('Reg_inscrip_edit_model','estud');
		//$this->load->model('Config_curso_model','curso');
 
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
		$this->load->view('Reg_inscrip_edit_view');
		$this->load->view('layouts/fin');
	}
	 

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
		
		foreach ($list as $estudiante) {
			$no++;
			$row = array();
			// $row[] = $estudiante->idest;
			// $row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			// $row[] = $estudiante->idcurso;	
			// $row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="Edit" onclick="inscrip('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i> Inscribir</a>';
		
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


	public function ajax_edit_estud($id)
	{
		//print_r($id);
		$data=$this->estud->get_by_id($id);
		echo json_encode($data);
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

	public function ajax_get_debe()
	{
		
		$idest=$this->input->post('idest');


		$list=$this->estud->get_debe($idest);

		$data=array();
		foreach($list as $idestud)
		{
			$data[]=$idestud->debe;
			$data[]=$idestud->repitente;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
		//print_r($nivel."-".$gestion."-".$colegio."-".$curso);	
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
	}

	public function ajax_list_idcurso($id)
	{
		$ids=explode('-', $id, -1);
		//print_r($ids);
		//exit();
		$curso=$ids[0]; 
		$nivel=$ids[1];
		$gestion=$ids[2];
		$list=$this->estud->get_datatables_by_idcur($curso,$nivel,$gestion);
		$data = array();
		$no = $_POST['start'];
		
		foreach ($list as $estudiante) {
			//$no++;
			$row = array();
			// $row[] = $estudiante->idest;
			// $row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombre;
			$row[] = $estudiante->genero;
			// $row[] = $estudiante->idcurso;	
			// $row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="Edit" onclick="inscrip('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>';
			$row[] = '<a class="btn btn-sm bg-primary-400" href="javascript:void(0)" title="IMPRIMIR CTTO" onclick="print_ctto('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i> IMPRIMIR CTTO</a>';
			$row[] = '<a class="btn btn-sm bg-danger-400" href="javascript:void(0)" title="Imprimir RUDE" onclick="export_pdf('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i> Imprimir RUDE</a>';
		
			$data[] = $row;

		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->estud->count_all(),
						"recordsFiltered" => $this->estud->count_filtered_byid($curso,$nivel,$gestion),
						"data" => $data,
				);

		echo json_encode($output);

	}
	

	public function print_report($id)
	{
		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($id);
		$curso=$this->estud->get_print_curso_pdf($id);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS'),0,0,'C');
            $this->pdf->Ln('15');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
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
            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
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

	
	

}
