<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudiantes_sac_contr extends CI_Controller {
 
	 
 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Estudiantes_sac_model','mat');
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
		$this->load->view('Estudiantes_sac_view');
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

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/LoginE';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}
	public function ajax_list()
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
		$curso=$ids[0];	
		$nivel=$ids[1];
		$list=$this->mat->get_datatables_gestion($curso,$nivel,$gest);	
		$dia = ["LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO", "DOMINGO"];
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $temas) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $temas->materia;
			$row[] = $temas->appaterno." ".$temas->apmaterno." ".$temas->nombre;
			$row[] = $dia[$temas->dia-1];
			$row[] = $temas->h_entrada;
			$row[] = $temas->h_salida;
			$row[] = '<a class="btn btn-sm"  title="Clase " href="'.$temas->link.'" target="_blank"><img src="assets/images/meet1.png" alt="" width="55" height="55" ></a>';
					
			//add html for action
		
			$data[] = $row;

		}	
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->mat->count_all(),
				"recordsFiltered" => $this->mat->count_filtered_gestion($curso,$nivel,$gest),
				"data" => $data,
		);
		
		echo json_encode($output);

	}

	

}
