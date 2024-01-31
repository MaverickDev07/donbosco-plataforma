<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_nivel_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
		$this->load->helper(array('url', 'form'));
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
		$this->load->view('config_nivel_view');
		$this->load->view('layouts/fin');
	}
	public function ajax_get_id()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');

		$codgen='';
		$num_rows=$this->nivel->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->nivel->get_idcod_table($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idnivel;//considerar nombre del id; $codigo->idnivel
				$newcod=substr($idcod,$n,strlen($idcod));
		        $newcod+=0;
		        if($newcod>=$may)
		        {
		            $may=$newcod;
		        }
				$may+=1;
			}
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

	public function ajax_get_colegio()
	{
		$table=$this->input->post('table');
		$list=$this->coles->get_rows_colegio($table);
		$data=array();
		foreach ($list as $cole) {			
			$data[] =$cole->colegio;			 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}


	public function ajax_set_nivel()
	{
		//valores enviados 
		$data=array(
			'idnivel'=>$this->input->post('idnivel'),
			'nivel'=>$this->input->post('nivel'),
			'nombre'=>$this->input->post('nombre'),
			'gestion'=>$this->input->post('gestion'),
			'colegio'=>$this->input->post('colegio'),
			'turno'=>$this->input->post('turno'),
		);		

		$insert=$this->nivel->save_nivel($data);

		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_list()
	{
		
		$list=$this->nivel->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		
		foreach ($list as $nivel) {
			$no++;
			$row = array();
			$row[] = $nivel->idnivel;
			$row[] = $nivel->nivel;
			$row[] = $nivel->nombre;
			$row[] = $nivel->gestion;
			$row[] = $nivel->colegio;
			$row[] = $nivel->turno;			

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_nivel('."'".$nivel->idnivel."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_nivel('."'".$nivel->idnivel."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;


		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->nivel->count_all(),
						"recordsFiltered" => $this->nivel->count_filtered(),
						"data" => $data,
				);

		echo json_encode($output);

	}


	public function ajax_delete($id)
	{
		$this->nivel->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_nivel($id)
	{
		//print_r($id);
		$data=$this->nivel->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_nivel()
	{

		//valores enviados 
		$data=array(
			//'colegio'=>$this->input->post('colegio'),
			//'idnivel'=>$this->input->post('idnivel'),
			'nivel'=>$this->input->post('nivel'),
			'nombre'=>$this->input->post('nombre'),
			'gestion'=>$this->input->post('gestion'),
			'colegio'=>$this->input->post('colegio'),
			'turno'=>$this->input->post('turno'),
		);
		//print_r($data);

		$this->nivel->update(array('idnivel'=>$this->input->post('idnivel')),$data);

		echo json_encode(array("status"=>TRUE));
	}


}