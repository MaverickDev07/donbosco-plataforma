<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_colegios_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
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
		$this->load->view('config_colegios_view');
		$this->load->view('layouts/fin');
	}
	public function ajax_get_id()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');

		$codgen='';
		$num_rows=$this->id->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->id->get_idcod_table($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idcod;
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


	public function ajax_set_colegios()
	{
		//valores enviados 
		$data=array(
			'sigla'=>$this->input->post('sigla'),
			'nombre'=>$this->input->post('colegio'),
			'sie'=>$this->input->post('sie'),
			'direccion'=>$this->input->post('direccion'),
			'telefono'=>$this->input->post('telefono'),
			'dependencia'=>$this->input->post('dependencia')
		);		

		$insert=$this->coles->save_coles($data);

		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_list()
	{
		
		$list=$this->coles->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $colegio) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $colegio->nombre;
			$row[] = $colegio->sie;
			$row[] = $colegio->direccion;
			$row[] = $colegio->telefono;
			// $row[] = "<img src='".$colegio->logo."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_coles('."'".$colegio->id_col."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_coles('."'".$colegio->id_col."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;


		}

		//print_r($data);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->coles->count_all(),
						"recordsFiltered" => $this->coles->count_filtered(),
						"data" => $data,
				);
		//output to json format
		
		
		echo json_encode($output);

	}


	public function ajax_delete($id)
	{
		$this->coles->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_coles($id)
	{
		//print_r($id);
		$data=$this->coles->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_colegios()
	{
		//valores enviados 
		$data=array(
			
			'sigla'=>$this->input->post('sigla'),
			'nombre'=>$this->input->post('colegio'),
			'sie'=>$this->input->post('sie'),
			'direccion'=>$this->input->post('direccion'),
			'telefono'=>$this->input->post('telefono'),
			'dependencia'=>$this->input->post('dependencia')
		);
		//print_r($data);

		$this->coles->update(array('colegio'=>$this->input->post('colegio')),$data);

		echo json_encode(array("status"=>TRUE));
	}


}