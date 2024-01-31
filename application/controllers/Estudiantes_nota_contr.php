<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudiantes_nota_contr extends CI_Controller {
 
	
 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Estudiantes_nota_model','mat');
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
		$this->load->view('Estudiantes_nota_view');
		$this->load->view('layouts/fin');
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
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}
	public function ajax_materias()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		//print_r($id_est."d".$gest);
		//exit();
		$list=$this->mat->get_rows_curso_materia($id_est,$gest);
		
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

	public function buscar($id)
	{		
		$ids=explode('-', $id."-", -1);
		$id_mat=$ids[0];
		$sh=$ids[1];
		$id_est=$this->session->userdata("id");
		$gestion=$this->mat->get_gestion(); 
		$gest=$gestion->gestion;
		$list=$this->mat->get_datatables_gestion($gest,$id_mat,$id_est,$sh);	
		// print_r($list);
		// exit();
		$data = array();
		$no = $_POST['start'];
		// print_r($list);
		$i=0;
		foreach ($list as $nota) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $nota->materia;
			$row[] = $nota->appaterno.' '.$nota->apmaterno.' '.$nota->nombre;
			$row[] = $nota->tema;
			$row[] = $nota->nota;
			$row[] =$nota->descrip;
			$row[] = '<a class="btn btn-sm btn-success"  title="Descargar " href="'.base_url().'Profesor_de_contr/descarga_tarea/'.$nota->id_tarea.'"><i class="glyphicon glyphicon-download"></i>Descargar</a>';

			$data[] = $row;

		}
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->mat->count_all(),
				"recordsFiltered" => $this->mat->count_filtered_gestion($gest,$id_mat,$id_est,$sh),
				"data" => $data,
		);	
		
		echo json_encode($output);

	}

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/LoginE';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}	

}
