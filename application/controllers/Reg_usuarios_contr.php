<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_usuarios_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Reg_usuarios_model','id');

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
		$this->load->view('reg_usuarios_view');
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

	public function ajax_set_usuario()
	{
		//valores enviados 
		$data=array(
			'idcod'=>$this->input->post('codigo'),
			'usuario'=>$this->input->post('usuario'),
			'clave'=>sha1($this->input->post('clave')),
			'appat'=>$this->input->post('appat'),
			'apmat'=>$this->input->post('apmat'),
			'nombre'=>$this->input->post('nombre'),
			'rol'=>$this->input->post('rol'),
			'activo'=>$this->input->post('activo'),
			'foto'=>$this->input->post('foto'),
		);
		
		//print_r($data);
		//print_r($codigo." ".$nombre." ".$usuario." ".$clave." ".$activo);

		$insert=$this->id->save_user($data);
		
		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_list()
	{
		
		$list=$this->id->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $usuario) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $usuario->usuario;
			$row[] = $usuario->appat;
			$row[] = $usuario->apmat;
			$row[] = $usuario->nombre;
			$row[] = $usuario->rol;
			$row[] = $usuario->activo;
			$row[] = "<img src='".$usuario->foto."' width='100' height='100'>";


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_usuario('."'".$usuario->idcod."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_usuario('."'".$usuario->idcod."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;

			//print_r($data);
		}

		//print_r($data);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->id->count_all(),
						"recordsFiltered" => $this->id->count_filtered(),
						"data" => $data,
				);
		
		echo json_encode($output);

	}


	public function ajax_delete($id)
	{
		$this->id->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_usuario($id)
	{
		$data=$this->id->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_usuario()
	{
		//valores enviados 
		$data=array(
			'usuario'=>$this->input->post('usuario'),
			'clave'=>sha1($this->input->post('clave')),
			'appat'=>$this->input->post('appat'),
			'apmat'=>$this->input->post('apmat'),
			'nombre'=>$this->input->post('nombre'),
			'rol'=>$this->input->post('rol'),
			'activo'=>$this->input->post('activo'),
			'foto'=>$this->input->post('foto'),
		);
		//print_r($data);

		$this->id->update(array('idcod'=>$this->input->post('codigo')),$data);

		echo json_encode(array("status"=>TRUE));
	}


}
