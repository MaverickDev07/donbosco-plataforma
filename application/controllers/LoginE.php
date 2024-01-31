<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginE extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url', 'form', 'download', 'html'));
		$this->load->model('Studens_model', 'login');

		// /* Verificamos si ya tiene session */
		// if ($this->session->userdata("login")) {
		// 	$redirect = base_url() . 'Estudiantes_su_contr';
		// 	header("Location: " . $redirect);
		// }
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
		$this->load->view('loginE_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_validacion()
	{
		$pwd_entry = $this->input->post('cl');
		$pwd_validator = explode("-", $pwd_entry);
		/* Verificamos si tenemos una acceso especial */
		if (count($pwd_validator) > 1) {
			$pwd_access = sha1($pwd_validator[1]);
		} else {
			$pwd_access = '';
		}
		$pwd = $pwd_validator[0];
		$estudiante = $this->login->estudiante($pwd);

		if (is_null($estudiante)) {
			//$estudiantes=$this->login->estudiantes($pwd);
			$data = $this->login->validar($pwd);
			if ($data) {
				$moreData = explode("-", $data->codigo);

				// obtenemos el curso
				if ($moreData[0]) {
					$course = $moreData[0];
				} else {
					$course = '';
				}

				// obtenemos el nivel
				if ($moreData[1]) {
					$nivel = $moreData[1];
				} else {
					$nivel = '';
				}

				$data = [
					"id" => $data->id_est,
					"appat" => $data->appaterno,
					"apmat" => $data->apmaterno,
					"name" => $data->nombre,
					"ci" => $data->ci,
					"debe" => $data->debe,
					"inscrito" => $data->inscrito,
					"gestion" => $data->gestion,
					"course" => $course,
					"nivel" => $nivel,
					"pre" => FALSE,
					"login" => TRUE,
					"access" => $pwd_access,
				];
				$this->session->set_userdata($data);
			} else {
				$data = $this->login->validar2($pwd);
				if ($data) {
					$data = [
						"id" => $data->id_est,
						"appat" => $data->appaterno,
						"apmat" => $data->apmaterno,
						"name" => $data->nombre,
						"ci" => $data->ci,
						"debe" => $data->debe,
						"inscrito" => $data->inscrito,
						"gestion" => $data->gestion,
						"pre" => FALSE,
						"login" => TRUE,
						"access" => $pwd_access,
					];
					$this->session->set_userdata($data);
				} else $data = ["login" => FALSE];
			}
		} else {
			$data = [
				"id" => $estudiante->id_est,
				"appat" => $estudiante->appaterno,
				"apmat" => $estudiante->apmaterno,
				"name" => $estudiante->nombre,
				"debe" => false,
				"inscrito" => TRUE,
				"pre" => TRUE,
				"login" => TRUE
			];
			$this->session->set_userdata($data);
		}

		echo json_encode($data);
	}
}
