<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('login_model','login');

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
		$this->load->view('principal_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}
	
	public function ajax_usuario()
	{
		$data=[
				"id"=>$this->session->userdata('id'),
				"nomb"=>$this->session->userdata('name'),
				"rol"=>$this->session->userdata('rol'),
			];
		echo json_encode($data);
	}

	
}
