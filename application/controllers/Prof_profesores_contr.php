<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prof_profesores_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Prof_profesores_model','prof');

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
		$this->load->view('prof_profesores_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_get_id()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');

		$codgen='';
		$num_rows=$this->prof->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->prof->get_idcod_table($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idprof;//considerar nombre del id;				
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
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}

	public function ajax_set_profe()
	{
		//valores enviados 
		$data=array(
			'idprof'=>$this->input->post('codigo'),
			'item'=>$this->input->post('item'),
			'ci'=>$this->input->post('ci'),
			'appaterno'=>$this->input->post('appat'),
			'apmaterno'=>$this->input->post('apmat'),
			'nombres'=>$this->input->post('nombre'),
			'direccion'=>$this->input->post('direc'),
			'telefono'=>$this->input->post('fono'),
			'genero'=>$this->input->post('genero'),
			'foto'=>$this->input->post('foto'),
		);
		
		//print_r($data);
		//print_r($codigo." ".$nombre." ".$usuario." ".$clave." ".$activo);

		$insert=$this->prof->save_profe($data);
		
		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_list()
	{
		
		$list=$this->prof->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		
		foreach ($list as $profesor) {
			$no++;
			$row = array();
			$row[] = $profesor->idprof;
			$row[] = $profesor->item;
			$row[] = $profesor->ci;
			$row[] = $profesor->appaterno;
			$row[] = $profesor->apmaterno;
			$row[] = $profesor->nombres;
			$row[] = $profesor->direccion;
			$row[] = $profesor->telefono;
			$row[] = $profesor->genero;			
			$row[] = "<img src='".$profesor->foto."' width='100' height='100'>";


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_profesor('."'".$profesor->idprof."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_profesor('."'".$profesor->idprof."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;

			//print_r($data);
		}

		//print_r($data);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->prof->count_all(),
						"recordsFiltered" => $this->prof->count_filtered(),
						"data" => $data,
				);
		
		echo json_encode($output);

	}


	public function ajax_delete($id)
	{
		$this->prof->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_profesor($id)
	{
		$data=$this->prof->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_profe()
	{
		
		//valores enviados 
		$data=array(
			'idprof'=>$this->input->post('codigo'),
			'item'=>$this->input->post('item'),
			'ci'=>$this->input->post('ci'),
			'appaterno'=>$this->input->post('appat'),
			'apmaterno'=>$this->input->post('apmat'),
			'nombres'=>$this->input->post('nombre'),
			'direccion'=>$this->input->post('direc'),
			'telefono'=>$this->input->post('fono'),
			'genero'=>$this->input->post('genero'),
			'foto'=>$this->input->post('foto'),
		);
		//print_r($data);

		$this->prof->update(array('idprof'=>$this->input->post('codigo')),$data);

		echo json_encode(array("status"=>TRUE));
	}


	public function print()
	{
		$this->load->library('pdf');
		$list=$this->prof->get_print_prof_pdf();
		

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


}