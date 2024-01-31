<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_materia_contr extends CI_Controller {

	
 
	public function __construct()
	{
		parent::__construct();		
		$this->load->helper(array('url', 'form'));
		$this->load->model('Config_materia_model','curso');
		$this->load->model('Config_nivel_model','nivel');
		$this->load->model('Config_colegios_model','coles');

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
		$this->load->view('config_materia_view');
		$this->load->view('layouts/fin');
	}
	public function ajax_get_id()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');

		$codgen='';
		$num_rows=$this->curso->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->curso->get_idcod_table($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idcurso;//considerar nombre del id;	
							
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

	public function ajax_usuario()
	{
		$data=[
				"id"=>$this->session->userdata('id'),
				"nomb"=>$this->session->userdata('name'),
			];
		echo json_encode($data);
	}

	public function ajax_get_nivel()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->curso->get_rows_nivel($table); //envia
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
	public function ajax_get_cursos()
	{
		$list=$this->curso->ajax_get_cursos(); //envia
		$data=array();
		$data1=array();
		foreach ($list as $curso) {			
			$data[] =$curso->nombre;
			$data1[] =$curso->codigo;					 
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
		$list=$this->curso->ajax_get_area(); //envia
		$data=array();
		$data1=array();
		foreach ($list as $area) {			
			$data[] =$area->nombre;
			$data1[] =$area->id_area;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);
	}

	public function ajax_get_level()
	{
		$table=$this->input->post('table');//recibe
		
		$txt=$this->input->post('lvl');

		$n=strpos($txt," ");
		$nivel=substr($txt,0,$n);
		$turno=substr($txt,$n+1,strlen($txt));

		$list=$this->curso->get_rows_level($table,$nivel,$turno); //envia

		$data=array();
		foreach ($list as $level) {			
			$data[] =$level->gestion;					 
			$data[] =$level->colegio;					 				 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);

	}


	public function ajax_set_materia()
	{
		//valor
		$materia=$this->input->post('materia');
		$area=$this->input->post('area');
		$sigla=$this->input->post('sigla');
		$data=array(
			'nombre'=>$materia,
			'sigla'=>$sigla,
			'id_area'=>$area,
		);		

		$insert=$this->curso->save_curso($data);

		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_list()
	{
		
		$list=$this->curso->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$x=0;
		foreach ($list as $materia) {
			$x++;
			$no++;
			$row = array();
			$row[] = $x;
			$row[] = $materia->materia;
			$row[] = $materia->area;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_materia('."'".$materia->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_curso('."'".$materia->id."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;


		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->curso->count_all(),
						"recordsFiltered" => $this->curso->count_filtered(),
						"data" => $data,
				);

		echo json_encode($output);

	}


	public function ajax_delete($id)
	{
		$this->curso->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_materia($id)
	{
		//print_r($id);
		$data=$this->curso->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_materia()
	{
		//valores enviados
		$id=$this->input->post('id');
		$materia=$this->input->post('materia');
		$area=$this->input->post('area');
		$sigla=$this->input->post('sigla');
		$data=array(
			'nombre'=>$materia,
			'sigla'=>$sigla,
			'id_area'=>$area,
		);	
		$this->curso->update(array('id_mat'=>$id),$data);

		echo json_encode(array("status"=>TRUE));
	}
}