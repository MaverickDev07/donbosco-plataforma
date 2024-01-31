<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginP extends CI_Controller {

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
		$this->load->view('loginP_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_validacion()
	{
		$us=$this->input->post('us');
		$cl=$this->input->post('cl');
		$inscrip=$this->input->post('inscrip');
		//echo $inscrip;
		if($inscrip=="Madre"){
			$inscrip="inscrip_madre";
		}
		if($inscrip=="Padre"){
			$inscrip="inscrip_padre";
		}
		if($inscrip=="Tutor"){
			$inscrip="inscrip_tutor";
		}
		$ci="NULL";
		$ciP="NULL";
		$data=$this->login->validarp($us);	
		$padre=$this->login->validarPadre($inscrip,$cl,$us);

		foreach ($padre as $padres) {
			$ciP=$padres->ci;
			$appatP=$padres->appat;
			$apmatP=$padres->apmat;
			$nameP=$padres->nombres;
		}	
		foreach ($data as $estudiante) {
			$ci=$estudiante->ci;
			$appat=$estudiante->appaterno;
			$apmat=$estudiante->apmaterno;
			$name=$estudiante->nombre;
		}			
		if($ci!="NULL")
		{
			if($ciP!="NULL")
			{
				$data=[
					"ci"=>$ci,
					"appat"=>$appat,
					"apmat"=>$apmat,
					"name"=>$name,
					"ciP"=>$ciP,
					"appatP"=>$appatP,
					"apmatP"=>$apmatP,
					"nameP"=>$nameP,
					"inscrip"=>$inscrip,
					"login"=>TRUE
				];	
				$this->session->set_userdata($data);
			}else
			{
				$data=[
					"login"=>FALSE
				];
			}		
			
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
