<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inscrip_est_contr extends CI_Controller
{

	public $_idest = "";

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->model('Inscrip_est_model', 'estud');
		//$this->load->model('Config_curso_model','curso');

		// if (!$this->session->userdata("login")) {
		// 	$bu = 'http://' . $_SERVER['HTTP_HOST'] . '/donbosco/';
		// 	header("Location: " . $bu);
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
		$this->load->view('inscrip_est_view');
		$this->load->view('layouts/fin');
	}


	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu = 'http://' . $_SERVER['HTTP_HOST'] . '/donbosco/';
		header("Location: " . $bu);
		//echo json_encode(array("status" => TRUE));

	}

	public function ajax_get_numctto()
	{
		$table = 'inscripcion';
		$cod = 'CTTO-';
		$idcamp = 'numctto';

		$codgen = '';
		$num_rows = $this->estud->get_count_rows($idcamp, $table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->estud->get_idcod_table($idcamp, $table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->numctto;
				$newcod = substr($idcod, $n, strlen($idcod));
				$newcod += 0;
				if ($newcod >= $may) {
					$may = $newcod;
				}
			}
			$may += 1;
			$codgen = $cod . "" . $may;
		} else {
			$codgen = $cod . '1';
		}

		$output = array(
			"status" => TRUE,
			"data" => $codgen,
		);
		echo json_encode($output);
	}

	public function ajax_get_numcttoA()
	{
		$table = 'inscripcion';
		$cod = 'CTTO-';
		$idcamp = 'numctto';

		$codgen = '';
		$num_rows = $this->estud->get_count_rows($idcamp, $table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->estud->get_idcod_table($idcamp, $table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->numctto;
				$newcod = substr($idcod, $n, strlen($idcod));
				$newcod += 0;
				if ($newcod >= $may) {
					$may = $newcod;
				}
			}
			$may += 1;
			$codgen = $cod . "" . $may;
		} else {
			$codgen = $cod . '1';
		}

		return $codgen;
	}
	public function ajax_get_nivel1()
	{
		$table = $this->input->post('table'); //recibe
		$list = $this->estud->get_rows_nivel($table); //envia
		$data = array();
		foreach ($list as $nivel) {
			$data[] = $nivel->nivel . " " . $nivel->turno;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function ajax_usuario()
	{
		$appat = $this->session->userdata("appat");
		$apmat = $this->session->userdata("apmat");
		$name = $this->session->userdata("name");

		$data = array();
		$data[] = $appat . " " . $apmat . " " . $name;
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function inscrip_alumn($id)
	{

		//print_r($this->_idest);
		//$this->_idest=$id;
		$this->_idest = $id;

		$this->vista();
		$this->ajax_get_idest($this->_idest);
		//$pagins=base_url().'inscrip_contr';		
		//header("Location: ".$pagins);
		//
	}

	public function ajax_get_idest($id)
	{
		print_r("<input type='hidden' id='idest' value='" . $id . "'>");
	}

	public function ajax_get_unidedu()
	{
		$id = $this->input->post('idest');
		if (is_numeric($id)) {
			$list = $this->estud->get_data_estudiante($id);
			$data = array();
			$data[] = "";
			$data[] = $list->rude;
			$data[] = $list->ci;
			$data[] = $list->appaterno;
			$data[] = $list->apmaterno;
			$data[] = $list->nombre;
			$data[] = $list->genero;
			$data[] = $list->codigo;
			$data[] = "";
			$data[] = "";
			$data[] = $list->id_est;
		} else {
			$list = $this->estud->get_idunidad($id);

			$data = array();
			foreach ($list as $listest) {
				$estudiantes = $this->estud->get_estu1($listest->appaterno, $listest->apmaterno, $listest->nombres);
				$data[] = $listest->idcurso;
				$data[] = $listest->rude;
				$data[] = $listest->ci;
				$data[] = $listest->appaterno;
				$data[] = $listest->apmaterno;
				$data[] = $listest->nombres;
				$data[] = $listest->genero;
				$data[] = $listest->codigo;
				$data[] = $listest->idest;
				$data[] = $listest->vivecon;
				$data[] = $estudiantes->id_est;
			}
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_antunidedu()
	{
		/*$id=$this->input->post('idest');

		$list=$this->estud->get_antidunidad($id);

		$data=array();
		foreach($list as $listest)
		{
			$data[]=$listest->inscole;
			$data[]=$listest->antsie;
			$data[]=$listest->curso;


		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);*/

		$colegio = $this->input->post('colegio');

		$list = $this->estud->get_antUnidadEducativa($colegio);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->colegio;
			$data[] = $listest->SIE;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function antUnidadEducativa()
	{
		$colegio = $this->input->post('colegio');

		$list = $this->estud->get_antUnidadEducativa($colegio);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->colegio;
			$data[] = $listest->sie;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_discapacida()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_discapacida($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->discap;
			$data[] = $listest->registro;
			$data[] = $listest->tipo;
			$data[] = $listest->gradodiscap;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_nacimiento()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_nacimiento($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->oficialia;
			$data[] = $listest->libro;
			$data[] = $listest->partida;
			$data[] = $listest->folio;
			$data[] = $listest->pais;
			$data[] = $listest->dpto;
			$data[] = $listest->provincia;
			$data[] = $listest->localidad;
			$data[] = $listest->dia;
			$data[] = $listest->mes;
			$data[] = $listest->anio;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_padre()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_padre($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->appat;
			$data[] = $listest->apmat;
			$data[] = $listest->nombres;
			$data[] = $listest->ci;
			$data[] = $listest->complemento;
			$data[] = $listest->extendido;
			$data[] = $listest->idioma;
			$data[] = $listest->ocupacion;
			$data[] = $listest->grado;
			$data[] = $listest->celular;
			$data[] = $listest->ofifono;
			$data[] = $listest->fnacdia;
			$data[] = $listest->fnacmes;
			$data[] = $listest->fnacanio;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_madre()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_madre($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->appat;
			$data[] = $listest->apmat;
			$data[] = $listest->nombres;
			$data[] = $listest->ci;
			$data[] = $listest->complemento;
			$data[] = $listest->extendido;
			$data[] = $listest->idioma;
			$data[] = $listest->ocupacion;
			$data[] = $listest->grado;
			$data[] = $listest->celular;
			$data[] = $listest->ofifono;
			$data[] = $listest->fnacdia;
			$data[] = $listest->fnacmes;
			$data[] = $listest->fnacanio;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_tutor()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_tutor($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->appat;
			$data[] = $listest->apmat;
			$data[] = $listest->nombres;
			$data[] = $listest->ci;
			$data[] = $listest->complemento;
			$data[] = $listest->extendido;
			$data[] = $listest->idioma;
			$data[] = $listest->ocupacion;
			$data[] = $listest->grado;
			$data[] = $listest->celular;
			$data[] = $listest->ofifono;
			$data[] = $listest->fnacdia;
			$data[] = $listest->fnacmes;
			$data[] = $listest->fnacanio;
			$data[] = $listest->parentesco;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_idioma()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_idioma($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->idiomanatal;
			$data[] = $listest->idioma1;
			$data[] = $listest->idioma2;
			$data[] = $listest->idioma3;
			$data[] = $listest->nacion;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_direccion()
	{
		$id = $this->input->post('idest');

		$list = $this->estud->get_direccion($id);

		$data = array();
		foreach ($list as $listest) {
			$data[] = $listest->departamento;
			$data[] = $listest->provincia;
			$data[] = $listest->municipio;
			$data[] = $listest->localidad;
			$data[] = $listest->zona;
			$data[] = $listest->calle;
			$data[] = $listest->num;
			$data[] = $listest->fono;
			$data[] = $listest->celular;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_unid()
	{
		$id = $this->input->post('idcur');

		$list = $this->estud->get_idunid($id);

		$data = array();
		foreach ($list as $listcur) {
			$data[] = $listcur->colegio;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_unidAll()
	{
		// $id=$this->input->post('idcur');

		$list = $this->estud->get_idunidAll();

		$data = array();
		foreach ($list as $listcur) {
			$data[] = $listcur->colegio;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	function ajax_get_cole()
	{
		$cole = $this->input->post('cole');

		$list = $this->estud->get_data_cole($cole);

		$data = array();
		foreach ($list as $datacole) {
			$data[] = $datacole->colegio;
			$data[] = $datacole->SIE;
			$data[] = $datacole->direccion;
			$data[] = $datacole->telefono;
			$data[] = $datacole->dependencia;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_nivel()
	{
		$col = $this->input->post('col');

		$list = $this->estud->get_data_nivel($col);

		$data = array();
		foreach ($list as $datacol) {
			$data[] = $datacol->nivel . " " . $datacol->turno;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}
	public function ajax_get_niveles()
	{
		$col = $this->input->post('col');

		$list = $this->estud->get_data_nivel1($col);

		$data = array();
		foreach ($list as $datacol) {
			$data[] = $datacol->nivel . " " . $datacol->turno;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_curso()
	{
		$col = $this->input->post('col');
		$lev = $this->input->post('level');

		$list = $this->estud->get_data_curso($col, $lev);

		$data = array();
		foreach ($list as $datacur) {
			$data[] = $datacur->curso;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_id()
	{
		//valores enviados 
		$table = 'inscripcion';
		$cod = 'INS-';
		$idcamp = 'idinscrip';

		$codgen = '';
		$num_rows = $this->estud->get_count_rows($idcamp, $table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->estud->get_idcod_table($idcamp, $table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->idinscrip;
				$newcod = substr($idcod, $n, strlen($idcod));
				if ($newcod >= $may) {
					$may = $newcod;
				}
			}
			$may += 1;
			$codgen = $cod . "" . $may;
		} else {
			$codgen = $cod . '1';
		}

		return $codgen;
		//echo json_encode(array("status"=>TRUE,"codgen"=>$codgen));
	}

	public function ajax_get_idcur()
	{

		$nivel = $this->input->post('nivel');
		$curso = $this->input->post('curso');
		$inscole = $this->input->post('inscole');

		$list = $this->estud->get_data_idcurso($nivel, $curso, $inscole);

		$data = array();
		foreach ($list as $dataidcur) {
			$data[] = $dataidcur->idcurso;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_idestnew()
	{
		//valores enviados 
		$table = $this->input->post('table');
		$cod = $this->input->post('cod');
		$idcamp = 'idest';

		$codgen = '';
		$num_rows = $this->estud->get_count_rows($idcamp, $table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->estud->get_idcod_table($idcamp, $table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->idest;
				$newcod = substr($idcod, $n, strlen($idcod));

				if ($newcod >= $may) {
					$may = $newcod;
				}
			}
			$may = $may + 1;
			$codgen = $cod . "" . $may;
		} else {
			$codgen = $cod . '1';
		}

		echo json_encode(array("status" => TRUE, "codgen" => $codgen));
	}

	public function ajax_get_idestnewA()
	{
		//valores enviados 
		$table = 'estudiante';
		$cod = 'EST-';
		$idcamp = 'idest';

		$codgen = '';
		$num_rows = $this->estud->get_count_rows($idcamp, $table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->estud->get_idcod_table($idcamp, $table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->idest;
				$newcod = substr($idcod, $n, strlen($idcod));

				if ($newcod >= $may) {
					$may = $newcod;
				}
			}
			$may = $may + 1;
			$codgen = $cod . "" . $may;
		} else {
			$codgen = $cod . '1';
		}

		return $codgen;
	}


	// public function ajax_save_estud()
	// {
	// 	$id_est = $this->input->post('idestudiante');
	// 	$curso = $this->input->post('curso');
	// 	$fecha = date('Y-m-d');
	// 	// echo "salas ".$this->input->post('nivel');
	// 	// exit();
	// 	// $id_user = $this->session->userdata("id");
	// 	$id_user = $id_est;
	// 	$incripccion = array(
	// 		'fecha' => $fecha,
	// 		'departamento' => "CHUQUISACA",
	// 		'gestion' => $this->input->post('gestion'),
	// 		'vive' => $this->input->post('vivecon'),
	// 		'id_est' => $id_est,
	// 		'id_user' => $id_user,
	// 		'curso' => $curso,
	// 		'colegio' => $this->input->post('unidedu'),
	// 		'nivel' => $this->input->post('nivel')
	// 	);
	// 	$incripccion1 = $this->estud->save_reguistro_incripccion($incripccion);

	// 	$factura = array(
	// 		'nombre' => $this->input->post('nitnombre'),
	// 		'nit' => $this->input->post('nit'),
	// 		'correo' => $this->input->post('nitcorreo'),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est,
	// 		'id_user' => $id_user
	// 	);
	// 	$factura1 = $this->estud->save_factura($factura);

	// 	$pandemia = array(
	// 		'clases' => $this->input->post('pandemia_clases'),
	// 		'vacunas' => $this->input->post('pandemia_vacunas'),
	// 		'id_est' => $id_est
	// 	);
	// 	$pandemia1 = $this->estud->save_reguistro_pandemia($pandemia);

	// 	$antcolegio = array(
	// 		'sie' => $this->input->post("antsie"),
	// 		'nombre' => $this->input->post("antunidadedu"),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est
	// 	);
	// 	$antcolegio1 = $this->estud->save_reguistro_colegio($antcolegio);

	// 	$estudiantes = array(
	// 		'rude' => $this->input->post('rude'),
	// 		'ci' => $this->input->post('ci'),
	// 		'complemento' => $this->input->post('complemento'),
	// 		'extension' => $this->input->post('extension'),
	// 		'appaterno' => $this->input->post('appaterno'),
	// 		'apmaterno' => $this->input->post('apmaterno'),
	// 		'nombre' => $this->input->post('nombres'),
	// 		'genero' => $this->input->post('genero'),
	// 		'codigo' => $this->input->post('codigobanco'),
	// 		'preinscripcion' => false,
	// 		'inscrito' => true
	// 	);
	// 	$mese = 0;
	// 	if ($this->input->post('t1fnacmes') == "Enero") {
	// 		$mesp = 1;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Febrero") {
	// 		$mesp = 2;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Marzo") {
	// 		$mesp = 3;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Abril") {
	// 		$mesp = 4;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Mayo") {
	// 		$mesp = 5;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Junio") {
	// 		$mesp = 6;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Julio") {
	// 		$mesp = 7;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Agosto") {
	// 		$mesp = 8;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Septiembre") {
	// 		$mesp = 9;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Octubre") {
	// 		$mesp = 10;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Noviembre") {
	// 		$mesp = 11;
	// 	}
	// 	if ($this->input->post('t1fnacmes') == "Diciembre") {
	// 		$mesp = 12;
	// 	}

	// 	if ($this->input->post('t2fnacmes') == "Enero") {
	// 		$mesm = 1;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Febrero") {
	// 		$mesm = 2;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Marzo") {
	// 		$mesm = 3;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Abril") {
	// 		$mesm = 4;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Mayo") {
	// 		$mesm = 5;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Junio") {
	// 		$mesm = 6;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Julio") {
	// 		$mesm = 7;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Agosto") {
	// 		$mesm = 8;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Septiembre") {
	// 		$mesm = 9;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Octubre") {
	// 		$mesm = 10;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Noviembre") {
	// 		$mesm = 11;
	// 	}
	// 	if ($this->input->post('t2fnacmes') == "Diciembre") {
	// 		$mesm = 12;
	// 	}

	// 	if ($this->input->post('t3fnacmes') == "Enero") {
	// 		$mest = 1;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Febrero") {
	// 		$mest = 2;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Marzo") {
	// 		$mest = 3;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Abril") {
	// 		$mest = 4;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Mayo") {
	// 		$mest = 5;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Junio") {
	// 		$mest = 6;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Julio") {
	// 		$mest = 7;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Agosto") {
	// 		$mest = 8;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Septiembre") {
	// 		$mest = 9;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Octubre") {
	// 		$mest = 10;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Noviembre") {
	// 		$mest = 11;
	// 	}
	// 	if ($this->input->post('t3fnacmes') == "Diciembre") {
	// 		$mest = 12;
	// 	}

	// 	if ($this->input->post('nacmes') == "Enero") {
	// 		$mese = 1;
	// 	}
	// 	if ($this->input->post('nacmes') == "Febrero") {
	// 		$mese = 2;
	// 	}
	// 	if ($this->input->post('nacmes') == "Marzo") {
	// 		$mese = 3;
	// 	}
	// 	if ($this->input->post('nacmes') == "Abril") {
	// 		$mese = 4;
	// 	}
	// 	if ($this->input->post('nacmes') == "Mayo") {
	// 		$mese = 5;
	// 	}
	// 	if ($this->input->post('nacmes') == "Junio") {
	// 		$mese = 6;
	// 	}
	// 	if ($this->input->post('nacmes') == "Julio") {
	// 		$mese = 7;
	// 	}
	// 	if ($this->input->post('nacmes') == "Agosto") {
	// 		$mese = 8;
	// 	}
	// 	if ($this->input->post('nacmes') == "Septiembre") {
	// 		$mese = 9;
	// 	}
	// 	if ($this->input->post('nacmes') == "Octubre") {
	// 		$mese = 10;
	// 	}
	// 	if ($this->input->post('nacmes') == "Noviembre") {
	// 		$mese = 11;
	// 	}
	// 	if ($this->input->post('nacmes') == "Diciembre") {
	// 		$mese = 12;
	// 	}



	// 	$estudiantes1 = $this->estud->update_estudiantes($id_est, $estudiantes);
	// 	$fechanac = $this->input->post('nacanio') . "-" . $mese . "-" . $this->input->post('nacdia');
	// 	$nacimientos = array(
	// 		'pais' => $this->input->post('pais'),
	// 		'departamento' => $this->input->post('dpto'),
	// 		'provincia' => $this->input->post('provincia'),
	// 		'localidad' => $this->input->post('localidad'),
	// 		'oficialia' => $this->input->post('oficialia'),
	// 		'libro' => $this->input->post('libro'),
	// 		'partida' => $this->input->post('partida'),
	// 		'folio' => $this->input->post('folio'),
	// 		'fnacimiento' => $fechanac,
	// 		'id_est' => $id_est
	// 	);
	// 	$nacimientos1 = $this->estud->save_nacimientos($nacimientos);

	// 	$discapacidad = array(
	// 		'discapacidad' => $this->input->post('discap1'),
	// 		'ibc' => $this->input->post('regdiscap'),
	// 		'tipo' => $this->input->post('tdiscap'),
	// 		'grado' => $this->input->post('gradodiscap'),
	// 		'id_est' => $id_est
	// 	);
	// 	$discapacidad1 = $this->estud->save_discapacidad($discapacidad);

	// 	$direccion = array(
	// 		'departamento' => $this->input->post('locdpto'),
	// 		'provincia' => $this->input->post('locprovin'),
	// 		'municipio' => $this->input->post('locmuni'),
	// 		'localidad' => $this->input->post('loclocal'),
	// 		'zona' => $this->input->post('loczona'),
	// 		'calle' => $this->input->post('loccalle'),
	// 		'nro' => $this->input->post('locnum'),
	// 		'telefono' => $this->input->post('locfono'),
	// 		'celular' => $this->input->post('loccel'),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est
	// 	);
	// 	$direccion1 = $this->estud->save_direccion($direccion);

	// 	$cultura = array(
	// 		'natal' => $this->input->post('idiomanatal'),
	// 		'idioma1' => $this->input->post('idioma1'),
	// 		'idioma2' => $this->input->post('idioma2'),
	// 		'idioma3' => $this->input->post('idioma3'),
	// 		'nacion' => $this->input->post('nacion'),
	// 		'id_est' => $id_est
	// 	);
	// 	$cultura1 = $this->estud->save_cultura($cultura);
	// 	$salud = array(
	// 		'centro' => $this->input->post('posta1'),
	// 		'cantidad' => $this->input->post('veces'),
	// 		'seguro' => $this->input->post('seguro1'),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est
	// 	);
	// 	$salud1 = $this->estud->save_salud1($salud);

	// 	if ($this->input->post('visitaposta1') != 0) {
	// 		$visitaposta = array(
	// 			'id_host' => $this->input->post('visitaposta1'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
	// 	}
	// 	if ($this->input->post('visitaposta2') != 0) {
	// 		$visitaposta = array(
	// 			'id_host' => $this->input->post('visitaposta2'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
	// 	}
	// 	if ($this->input->post('visitaposta3') != 0) {
	// 		$visitaposta = array(
	// 			'id_host' => $this->input->post('visitaposta3'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
	// 	}
	// 	if ($this->input->post('visitaposta4') != 0) {
	// 		$visitaposta = array(
	// 			'id_host' => $this->input->post('visitaposta4'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
	// 	}
	// 	if ($this->input->post('visitaposta5') != 0) {
	// 		$visitaposta = array(
	// 			'id_host' => $this->input->post('visitaposta5'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
	// 	}
	// 	if ($this->input->post('visitaposta6') != 0) {
	// 		$visitaposta = array(
	// 			'id_host' => $this->input->post('visitaposta6'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
	// 	}

	// 	$servicios_basico = array(
	// 		'caneria' => $this->input->post('agua1'),
	// 		'banio' => $this->input->post('banio1'),
	// 		'alcantarillado' => $this->input->post('alcanta1'),
	// 		'electricidad' => $this->input->post('luz1'),
	// 		'basura' => $this->input->post('basura1'),
	// 		'hogar' => $this->input->post('hogar'),
	// 		'freinternet' => $this->input->post('netfrecuencia'),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est
	// 	);
	// 	$servicios_basico1 = $this->estud->save_servicios_basico($servicios_basico);

	// 	if ($this->input->post('netvivienda') != 0) {
	// 		$internet = array(
	// 			'id_inter' => $this->input->post('netvivienda'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')

	// 		);
	// 		$internet1 = $this->estud->save_internet1($internet);
	// 	}
	// 	if ($this->input->post('netunidadedu') != 0) {
	// 		$internet = array(
	// 			'id_inter' => $this->input->post('netunidadedu'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')

	// 		);
	// 		$internet1 = $this->estud->save_internet1($internet);
	// 	}
	// 	if ($this->input->post('netpublic') != 0) {
	// 		$internet = array(
	// 			'id_inter' => $this->input->post('netpublic'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')

	// 		);
	// 		$internet1 = $this->estud->save_internet1($internet);
	// 	}
	// 	if ($this->input->post('netcelu') != 0) {
	// 		$internet = array(
	// 			'id_inter' => $this->input->post('netcelu'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')

	// 		);
	// 		$internet1 = $this->estud->save_internet1($internet);
	// 	}
	// 	if ($this->input->post('nonet') != 0) {
	// 		$internet = array(
	// 			'id_inter' => $this->input->post('nonet'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$internet1 = $this->estud->save_internet1($internet);
	// 	}

	// 	$transporte = array(
	// 		'transporte' => $this->input->post('transpllega'),
	// 		'otro' => $this->input->post('otrollega'),
	// 		'tiempo' => $this->input->post('tllegada'),
	// 		'abandono' => $this->input->post('abandono1'),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est
	// 	);
	// 	$transporte1 = $this->estud->save_transportes($transporte);

	// 	if ($this->input->post('razon0') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon0'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon1') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon1'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon2') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon2'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon3') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon3'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon4') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon4'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon5') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon5'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon6') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon6'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon7') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon7'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon8') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon8'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon9') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon9'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon10') != 0) {
	// 		$abandono = array(
	// 			'id_aban' => $this->input->post('razon10'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}
	// 	if ($this->input->post('razon11') != 0) {
	// 		$abandono = array(
	// 			'nombre' => $this->input->post('otrarazon'),
	// 			'id_aban' => $this->input->post('razon11'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$abandono1 = $this->estud->save_abandonos($abandono);
	// 	}

	// 	$trabajo = array(
	// 		'trabajo' => $this->input->post('trabajo1'),
	// 		'actividad' => $this->input->post('actividad'),
	// 		'otro' => $this->input->post('otrotrabajo'),
	// 		'manana' => $this->input->post('turnoman'),
	// 		'tarde' => $this->input->post('turnotar'),
	// 		'noche' => $this->input->post('turnonoc'),
	// 		'frecuencia' => $this->input->post('trabfrecuencia'),
	// 		'pago' => $this->input->post('pagotrab1'),
	// 		'tipopago' => $this->input->post('tipopago'),
	// 		'gestion' => $this->input->post('gestion'),
	// 		'id_est' => $id_est

	// 	);
	// 	$trabajo1 = $this->estud->save_trabajos($trabajo);

	// 	if ($this->input->post('ene') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('ene'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('feb') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('feb'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('mar') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('mar'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('abr') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('abr'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('may') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('may'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('jun') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('jun'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('jul') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('jul'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('ago') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('ago'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('sep') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('sep'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('oct') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('oct'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}
	// 	if ($this->input->post('nov') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('nov'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}

	// 	if ($this->input->post('dic') != 0) {
	// 		$mes = array(
	// 			'id_mes' => $this->input->post('dic'),
	// 			'id_est' => $id_est,
	// 			'gestion' => $this->input->post('gestion')
	// 		);
	// 		$mes1 = $this->estud->save_mes($mes);
	// 	}

	// 	$nota_prom = array(
	// 		'codigo' => $curso . "-" . $this->input->post('nivel') . "-" . $this->input->post('unidedu'),
	// 		'id_est' => $id_est,
	// 		'vive' => $this->input->post('vivecon'),
	// 		'gestion' => $this->input->post('gestion')
	// 	);
	// 	$nota_prom1 = $this->estud->save_nota_prom($nota_prom);

	// 	$pa = false;
	// 	$buscarp = $this->estud->buscar_padres($this->input->post('t1appat'), $this->input->post('t1apmat'), $this->input->post('t1nombres'));
	// 	foreach ($buscarp as $listest) {
	// 		$id_padre = $listest->id;
	// 		$pa = true;
	// 	}
	// 	if ($pa) {
	// 		$reg_tutor = array(
	// 			'tipo' => "PADRE",
	// 			'id_padre' => $id_padre,
	// 			'id_est' => $id_est
	// 		);
	// 		$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
	// 	} else {
	// 		$fechap = $this->input->post('t1fnacanio') . "-" . $mesp . "-" . $this->input->post('t1fnacdia');
	// 		$padre = array(
	// 			'appaterno' => $this->input->post('t1appat'),
	// 			'apmaterno' => $this->input->post('t1apmat'),
	// 			'nombre' => $this->input->post('t1nombres'),
	// 			'idioma' => $this->input->post('t1idioma'),
	// 			'ocupacion' => $this->input->post('t1ocup'),
	// 			'lugartra' => $this->input->post('t1lug'),
	// 			'grado' => $this->input->post('t1grado'),
	// 			'fecha' => $fechap,
	// 			'ci' => $this->input->post('t1ci'),
	// 			'com' => $this->input->post('t1comple'),
	// 			'ex' => $this->input->post('t1extension'),
	// 			'telefono' => $this->input->post('t1ofifono'),
	// 			'celular' => $this->input->post('t1celular')
	// 		);
	// 		$padre1 = $this->estud->save_padres($padre);
	// 		$buscarp1 = $this->estud->buscar_padres($this->input->post('t1appat'), $this->input->post('t1apmat'), $this->input->post('t1nombres'));
	// 		foreach ($buscarp1 as $listest) {
	// 			$id_padre = $listest->id;
	// 		}
	// 		$reg_tutor = array(
	// 			'tipo' => "PADRE",
	// 			'id_padre' => $id_padre,
	// 			'id_est' => $id_est
	// 		);
	// 		$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
	// 	}

	// 	$ma = false;
	// 	$buscarm = $this->estud->buscar_padres($this->input->post('t2appat'), $this->input->post('t2apmat'), $this->input->post('t2nombres'));
	// 	foreach ($buscarm as $listest) {
	// 		$id_madre = $listest->id;
	// 		$ma = true;
	// 	}
	// 	if ($ma) {
	// 		$reg_tutor = array(
	// 			'tipo' => "MADRE",
	// 			'id_padre' => $id_madre,
	// 			'id_est' => $id_est
	// 		);
	// 		$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
	// 	} else {
	// 		$fecham = $this->input->post('t2fnacanio') . "-" . $mesm . "-" . $this->input->post('t2fnacdia');
	// 		$madre = array(
	// 			'appaterno' => $this->input->post('t2appat'),
	// 			'apmaterno' => $this->input->post('t2apmat'),
	// 			'nombre' => $this->input->post('t2nombres'),
	// 			'idioma' => $this->input->post('t2idioma'),
	// 			'ocupacion' => $this->input->post('t2ocup'),
	// 			'lugartra' => $this->input->post('t2lug'),
	// 			'grado' => $this->input->post('t2grado'),
	// 			'fecha' => $fecham,
	// 			'ci' => $this->input->post('t2ci'),
	// 			'com' => $this->input->post('t2comple'),
	// 			'ex' => $this->input->post('t2extension'),
	// 			'telefono' => $this->input->post('t2ofifono'),
	// 			'celular' => $this->input->post('t2celular')
	// 		);
	// 		$madre1 = $this->estud->save_padres($madre);
	// 		$buscarM = $this->estud->buscar_padres($this->input->post('t2appat'), $this->input->post('t2apmat'), $this->input->post('t2nombres'));
	// 		foreach ($buscarM as $listest) {
	// 			$id_madre = $listest->id;
	// 		}
	// 		$reg_tutor = array(
	// 			'tipo' => "MADRE",
	// 			'id_padre' => $id_madre,
	// 			'id_est' => $id_est
	// 		);
	// 		$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
	// 	}
	// 	$tu = false;
	// 	$buscart = $this->estud->buscar_padres($this->input->post('t3appat'), $this->input->post('t3apmat'), $this->input->post('t3nombres'));
	// 	foreach ($buscart as $listest) {
	// 		$id_tutor = $listest->id;
	// 		$tu = true;
	// 	}
	// 	if ($tu) {
	// 		$reg_tutor = array(
	// 			'tipo' => "TUTOR",
	// 			'id_padre' => $id_tutor,
	// 			'id_est' => $id_est
	// 		);
	// 		$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
	// 	} else {
	// 		$fechat = $this->input->post('t3fnacanio') . "-" . $mest . "-" . $this->input->post('t3fnacdia');
	// 		$tutor = array(
	// 			'appaterno' => $this->input->post('t3appat'),
	// 			'apmaterno' => $this->input->post('t3apmat'),
	// 			'nombre' => $this->input->post('t3nombres'),
	// 			'idioma' => $this->input->post('t3idioma'),
	// 			'ocupacion' => $this->input->post('t3ocup'),
	// 			'lugartra' => $this->input->post('t3lug'),
	// 			'grado' => $this->input->post('t3grado'),
	// 			'fecha' => $fechat,
	// 			'ci' => $this->input->post('t3ci'),
	// 			'com' => $this->input->post('t3comple'),
	// 			'ex' => $this->input->post('t3extension'),
	// 			'telefono' => $this->input->post('t3ofifono'),
	// 			'celular' => $this->input->post('t3celular'),
	// 			'parentesco' => $this->input->post('t3parentesco')
	// 		);
	// 		$tutor1 = $this->estud->save_padres($tutor);
	// 		$buscart = $this->estud->buscar_padres($this->input->post('t3appat'), $this->input->post('t3apmat'), $this->input->post('t3nombres'));
	// 		foreach ($buscart as $listest) {
	// 			$id_tutor = $listest->id;
	// 		}
	// 		$reg_tutor = array(
	// 			'tipo' => "TUTOR",
	// 			'id_padre' => $id_tutor,
	// 			'id_est' => $id_est
	// 		);
	// 		$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
	// 	}
	// 	$numero1 = $this->estud->count_contrato($this->input->post('gestion'));
	// 	foreach ($numero1 as $numeros) {
	// 		$numero2 = $numeros->numero;
	// 	}
	// 	$nu = 10000;
	// 	$numero = $nu + $numero2;
	// 	if ($this->input->post('contrato') == "P") {
	// 		$contrato = array(
	// 			'departamento' => "CHUQUISACA",
	// 			'fecha' => $fecha,
	// 			'codigo' => "CTT0-" . $numero . "/" . $this->input->post('gestion'),
	// 			'gestion' => $this->input->post('gestion'),
	// 			'id_est' => $id_est,
	// 			'id_padre' => $id_padre
	// 		);
	// 		$contrato1 = $this->estud->save_contrato($contrato);
	// 	}
	// 	if ($this->input->post('contrato') == "M") {
	// 		$contrato = array(
	// 			'departamento' => "CHUQUISACA",
	// 			'fecha' => $fecha,
	// 			'codigo' => "CTT0-" . $numero . "/" . $this->input->post('gestion'),
	// 			'gestion' => $this->input->post('gestion'),
	// 			'id_est' => $id_est,
	// 			'id_padre' => $id_madre
	// 		);
	// 		$contrato1 = $this->estud->save_contrato($contrato);
	// 	}
	// 	if ($this->input->post('contrato') == "T") {
	// 		$contrato = array(
	// 			'departamento' => "CHUQUISACA",
	// 			'fecha' => $fecha,
	// 			'codigo' => "CTT0-" . $numero . "/" . $this->input->post('gestion'),
	// 			'gestion' => $this->input->post('gestion'),
	// 			'id_est' => $id_est,
	// 			'id_padre' => $id_tutor
	// 		);
	// 		$contrato1 = $this->estud->save_contrato($contrato);
	// 	}
	// 	/*$estudi=array(
	// 		'inscrito'=>true
	// 	);
	// 	$where=array(
	// 		'idest'=>$this->input->post('idest1')
	// 	);
	// 	$estud32=$this->estud->updates("estudiante",$where,$estudi);*/

	// 	// echo "salas ".$this->input->post('nivel');
	// 	// exit();
	// 	//echo json_encode(array("status"=>TRUE));

	// 	echo json_encode(array("status" => TRUE, "idinscrip" => $id_est));
	// }

	public function ajax_save_estud()
	{
		$objDatos = json_decode(file_get_contents("php://input"));
		$id_est = $objDatos->idestudiante;
		$curso = $objDatos->curso;
		$fecha = date('Y-m-d');
		// $id_user = $this->session->userdata("id");
		$id_user = $id_est;
		$incripccion = array(
			'fecha' => $fecha,
			'departamento' => "CHUQUISACA",
			'gestion' => $objDatos->gestion,
			'vive' => $objDatos->vivecon,
			'id_est' => $id_est,
			'id_user' => $id_user,
			'curso' => $curso,
			'colegio' => $objDatos->unidedu,
			'nivel' => $objDatos->nivel
		);
		$incripccion1 = $this->estud->save_reguistro_incripccion($incripccion);

		$factura = array(
			'nombre' => $objDatos->nitnombre,
			'nit' => $objDatos->nit,
			'correo' => $objDatos->nitcorreo,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est,
			'id_user' => $id_user
		);
		$factura1 = $this->estud->save_factura($factura);

		$pandemia = array(
			'clases' => $objDatos->pandemia_clases,
			'vacunas' => $objDatos->pandemia_vacunas,
			'id_est' => $id_est
		);
		$pandemia1 = $this->estud->save_reguistro_pandemia($pandemia);

		$antcolegio = array(
			'sie' => $objDatos->antsie,
			'nombre' => $objDatos->antunidadedu,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est
		);
		$antcolegio1 = $this->estud->save_reguistro_colegio($antcolegio);

		$estudiantes = array(
			'rude' => $objDatos->rude,
			'ci' => $objDatos->ci,
			'complemento' => $objDatos->complemento,
			'extension' => $objDatos->extension,
			'appaterno' => $objDatos->appaterno,
			'apmaterno' => $objDatos->apmaterno,
			'nombre' => $objDatos->nombres,
			'genero' => $objDatos->genero,
			'codigo' => $objDatos->codigobanco,
			'preinscripcion' => false,
			'inscrito' => true
		);
		$mese = 0;
		$mesp = $objDatos->t1fnacmes;
		if ($objDatos->t1fnacmes == "Enero") {
			$mesp = 1;
		}
		if ($objDatos->t1fnacmes == "Febrero") {
			$mesp = 2;
		}
		if ($objDatos->t1fnacmes == "Marzo") {
			$mesp = 3;
		}
		if ($objDatos->t1fnacmes == "Abril") {
			$mesp = 4;
		}
		if ($objDatos->t1fnacmes == "Mayo") {
			$mesp = 5;
		}
		if ($objDatos->t1fnacmes == "Junio") {
			$mesp = 6;
		}
		if ($objDatos->t1fnacmes == "Julio") {
			$mesp = 7;
		}
		if ($objDatos->t1fnacmes == "Agosto") {
			$mesp = 8;
		}
		if ($objDatos->t1fnacmes == "Septiembre") {
			$mesp = 9;
		}
		if ($objDatos->t1fnacmes == "Octubre") {
			$mesp = 10;
		}
		if ($objDatos->t1fnacmes == "Noviembre") {
			$mesp = 11;
		}
		if ($objDatos->t1fnacmes == "Diciembre") {
			$mesp = 12;
		}

		$mesm = $objDatos->t2fnacmes;
		if ($objDatos->t2fnacmes == "Enero") {
			$mesm = 1;
		}
		if ($objDatos->t2fnacmes == "Febrero") {
			$mesm = 2;
		}
		if ($objDatos->t2fnacmes == "Marzo") {
			$mesm = 3;
		}
		if ($objDatos->t2fnacmes == "Abril") {
			$mesm = 4;
		}
		if ($objDatos->t2fnacmes == "Mayo") {
			$mesm = 5;
		}
		if ($objDatos->t2fnacmes == "Junio") {
			$mesm = 6;
		}
		if ($objDatos->t2fnacmes == "Julio") {
			$mesm = 7;
		}
		if ($objDatos->t2fnacmes == "Agosto") {
			$mesm = 8;
		}
		if ($objDatos->t2fnacmes == "Septiembre") {
			$mesm = 9;
		}
		if ($objDatos->t2fnacmes == "Octubre") {
			$mesm = 10;
		}
		if ($objDatos->t2fnacmes == "Noviembre") {
			$mesm = 11;
		}
		if ($objDatos->t2fnacmes == "Diciembre") {
			$mesm = 12;
		}

		$mest = $objDatos->t3fnacmes;
		if ($objDatos->t3fnacmes == "Enero") {
			$mest = 1;
		}
		if ($objDatos->t3fnacmes == "Febrero") {
			$mest = 2;
		}
		if ($objDatos->t3fnacmes == "Marzo") {
			$mest = 3;
		}
		if ($objDatos->t3fnacmes == "Abril") {
			$mest = 4;
		}
		if ($objDatos->t3fnacmes == "Mayo") {
			$mest = 5;
		}
		if ($objDatos->t3fnacmes == "Junio") {
			$mest = 6;
		}
		if ($objDatos->t3fnacmes == "Julio") {
			$mest = 7;
		}
		if ($objDatos->t3fnacmes == "Agosto") {
			$mest = 8;
		}
		if ($objDatos->t3fnacmes == "Septiembre") {
			$mest = 9;
		}
		if ($objDatos->t3fnacmes == "Octubre") {
			$mest = 10;
		}
		if ($objDatos->t3fnacmes == "Noviembre") {
			$mest = 11;
		}
		if ($objDatos->t3fnacmes == "Diciembre") {
			$mest = 12;
		}

		if ($objDatos->nacmes == "Enero") {
			$mese = 1;
		}
		if ($objDatos->nacmes == "Febrero") {
			$mese = 2;
		}
		if ($objDatos->nacmes == "Marzo") {
			$mese = 3;
		}
		if ($objDatos->nacmes == "Abril") {
			$mese = 4;
		}
		if ($objDatos->nacmes == "Mayo") {
			$mese = 5;
		}
		if ($objDatos->nacmes == "Junio") {
			$mese = 6;
		}
		if ($objDatos->nacmes == "Julio") {
			$mese = 7;
		}
		if ($objDatos->nacmes == "Agosto") {
			$mese = 8;
		}
		if ($objDatos->nacmes == "Septiembre") {
			$mese = 9;
		}
		if ($objDatos->nacmes == "Octubre") {
			$mese = 10;
		}
		if ($objDatos->nacmes == "Noviembre") {
			$mese = 11;
		}
		if ($objDatos->nacmes == "Diciembre") {
			$mese = 12;
		}



		$estudiantes1 = $this->estud->update_estudiantes($id_est, $estudiantes);
		$fechanac = $objDatos->nacanio . "-" . $mese . "-" . $objDatos->nacdia;
		$nacimientos = array(
			'pais' => $objDatos->pais,
			'departamento' => $objDatos->dpto,
			'provincia' => $objDatos->provincia,
			'localidad' => $objDatos->localidad,
			'oficialia' => $objDatos->oficialia,
			'libro' => $objDatos->libro,
			'partida' => $objDatos->partida,
			'folio' => $objDatos->folio,
			'fnacimiento' => $fechanac,
			'id_est' => $id_est
		);
		$nacimientos1 = $this->estud->save_nacimientos($nacimientos);

		$discapacidad = array(
			'discapacidad' => $objDatos->discap1,
			'ibc' => $objDatos->regdiscap,
			'tipo' => $objDatos->tdiscap,
			'grado' => $objDatos->gradodiscap,
			'id_est' => $id_est
		);
		$discapacidad1 = $this->estud->save_discapacidad($discapacidad);

		$direccion = array(
			'departamento' => $objDatos->locdpto,
			'provincia' => $objDatos->locprovin,
			'municipio' => $objDatos->locmuni,
			'localidad' => $objDatos->loclocal,
			'zona' => $objDatos->loczona,
			'calle' => $objDatos->loccalle,
			'nro' => $objDatos->locnum,
			'telefono' => $objDatos->locfono,
			'celular' => $objDatos->loccel,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est
		);
		$direccion1 = $this->estud->save_direccion($direccion);

		$cultura = array(
			'natal' => $objDatos->idiomanatal,
			'idioma1' => $objDatos->idioma1,
			'idioma2' => $objDatos->idioma2,
			'idioma3' => $objDatos->idioma3,
			'nacion' => $objDatos->nacion,
			'id_est' => $id_est
		);
		$cultura1 = $this->estud->save_cultura($cultura);
		$salud = array(
			'centro' => $objDatos->posta1,
			'cantidad' => $objDatos->veces,
			'seguro' => $objDatos->seguro1,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est
		);
		$salud1 = $this->estud->save_salud1($salud);

		if ($objDatos->visitaposta1 != 0) {
			$visitaposta = array(
				'id_host' => $objDatos->visitaposta1,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
		}
		if ($objDatos->visitaposta2 != 0) {
			$visitaposta = array(
				'id_host' => $objDatos->visitaposta2,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
		}
		if ($objDatos->visitaposta3 != 0) {
			$visitaposta = array(
				'id_host' => $objDatos->visitaposta3,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
		}
		if ($objDatos->visitaposta4 != 0) {
			$visitaposta = array(
				'id_host' => $objDatos->visitaposta4,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
		}
		if ($objDatos->visitaposta5 != 0) {
			$visitaposta = array(
				'id_host' => $objDatos->visitaposta5,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
		}
		if ($objDatos->visitaposta6 != 0) {
			$visitaposta = array(
				'id_host' => $objDatos->visitaposta6,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$visitaposta1 = $this->estud->save_visitaposta($visitaposta);
		}

		$servicios_basico = array(
			'caneria' => $objDatos->agua1,
			'banio' => $objDatos->banio1,
			'alcantarillado' => $objDatos->alcanta1,
			'electricidad' => $objDatos->luz1,
			'basura' => $objDatos->basura1,
			'hogar' => $objDatos->hogar,
			'freinternet' => $objDatos->netfrecuencia,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est
		);
		$servicios_basico1 = $this->estud->save_servicios_basico($servicios_basico);

		if ($objDatos->netvivienda != 0) {
			$internet = array(
				'id_inter' => $objDatos->netvivienda,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion

			);
			$internet1 = $this->estud->save_internet1($internet);
		}
		if ($objDatos->netunidadedu != 0) {
			$internet = array(
				'id_inter' => $objDatos->netunidadedu,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion

			);
			$internet1 = $this->estud->save_internet1($internet);
		}
		if ($objDatos->netpublic != 0) {
			$internet = array(
				'id_inter' => $objDatos->netpublic,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion

			);
			$internet1 = $this->estud->save_internet1($internet);
		}
		if ($objDatos->netcelu != 0) {
			$internet = array(
				'id_inter' => $objDatos->netcelu,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion

			);
			$internet1 = $this->estud->save_internet1($internet);
		}
		if ($objDatos->nonet != 0) {
			$internet = array(
				'id_inter' => $objDatos->nonet,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$internet1 = $this->estud->save_internet1($internet);
		}

		$transporte = array(
			'transporte' => $objDatos->transpllega,
			'otro' => $objDatos->otrollega,
			'tiempo' => $objDatos->tllegada,
			'abandono' => $objDatos->abandono1,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est
		);
		$transporte1 = $this->estud->save_transportes($transporte);

		if ($objDatos->razon0 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon0,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon1 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon1,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon2 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon2,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon3 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon3,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon4 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon4,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon5 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon5,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon6 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon6,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon7 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon7,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon8 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon8,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon9 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon9,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon10 != 0) {
			$abandono = array(
				'id_aban' => $objDatos->razon10,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}
		if ($objDatos->razon11 != 0) {
			$abandono = array(
				'nombre' => $objDatos->otrarazon,
				'id_aban' => $objDatos->razon11,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$abandono1 = $this->estud->save_abandonos($abandono);
		}

		$trabajo = array(
			'trabajo' => $objDatos->trabajo1,
			'actividad' => $objDatos->actividad,
			'otro' => $objDatos->otrotrabajo,
			'manana' => $objDatos->turnoman,
			'tarde' => $objDatos->turnotar,
			'noche' => $objDatos->turnonoc,
			'frecuencia' => $objDatos->trabfrecuencia,
			'pago' => $objDatos->pagotrab1,
			'tipopago' => $objDatos->tipopago,
			'gestion' => $objDatos->gestion,
			'id_est' => $id_est

		);
		$trabajo1 = $this->estud->save_trabajos($trabajo);

		if ($objDatos->ene != 0) {
			$mes = array(
				'id_mes' => $objDatos->ene,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->feb != 0) {
			$mes = array(
				'id_mes' => $objDatos->feb,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->mar != 0) {
			$mes = array(
				'id_mes' => $objDatos->mar,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->abr != 0) {
			$mes = array(
				'id_mes' => $objDatos->abr,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->may != 0) {
			$mes = array(
				'id_mes' => $objDatos->may,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->jun != 0) {
			$mes = array(
				'id_mes' => $objDatos->jun,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->jul != 0) {
			$mes = array(
				'id_mes' => $objDatos->jul,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->ago != 0) {
			$mes = array(
				'id_mes' => $objDatos->ago,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->sep != 0) {
			$mes = array(
				'id_mes' => $objDatos->sep,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->oct != 0) {
			$mes = array(
				'id_mes' => $objDatos->oct,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}
		if ($objDatos->nov != 0) {
			$mes = array(
				'id_mes' => $objDatos->nov,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}

		if ($objDatos->dic != 0) {
			$mes = array(
				'id_mes' => $objDatos->dic,
				'id_est' => $id_est,
				'gestion' => $objDatos->gestion
			);
			$mes1 = $this->estud->save_mes($mes);
		}

		$nota_prom = array(
			'codigo' => $curso . "-" . $objDatos->nivel . "-" . $objDatos->unidedu,
			'id_est' => $id_est,
			'vive' => $objDatos->vivecon,
			'gestion' => $objDatos->gestion
		);
		$nota_prom1 = $this->estud->save_nota_prom($nota_prom);

		$pa = false;
		$buscarp = $this->estud->buscar_padres($objDatos->t1appat, $objDatos->t1apmat, $objDatos->t1nombres);
		foreach ($buscarp as $listest) {
			$id_padre = $listest->id;
			$pa = true;
		}
		if ($pa) {
			$reg_tutor = array(
				'tipo' => "PADRE",
				'id_padre' => $id_padre,
				'id_est' => $id_est
			);
			$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
		} else {
			$fechap = $objDatos->t1fnacanio . "-" . $mesp . "-" . $objDatos->t1fnacdia;
			$padre = array(
				'appaterno' => $objDatos->t1appat,
				'apmaterno' => $objDatos->t1apmat,
				'nombre' => $objDatos->t1nombres,
				'idioma' => $objDatos->t1idioma,
				'ocupacion' => $objDatos->t1ocup,
				'lugartra' => $objDatos->t1lug,
				'grado' => $objDatos->t1grado,
				'fecha' => $fechap,
				'ci' => $objDatos->t1ci,
				'com' => $objDatos->t1comple,
				'ex' => $objDatos->t1extension,
				'telefono' => $objDatos->t1ofifono,
				'celular' => $objDatos->t1celular
			);
			$padre1 = $this->estud->save_padres($padre);
			$buscarp1 = $this->estud->buscar_padres($objDatos->t1appat, $objDatos->t1apmat, $objDatos->t1nombres);
			foreach ($buscarp1 as $listest) {
				$id_padre = $listest->id;
			}
			$reg_tutor = array(
				'tipo' => "PADRE",
				'id_padre' => $id_padre,
				'id_est' => $id_est
			);
			$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
		}

		$ma = false;
		$buscarm = $this->estud->buscar_padres($objDatos->t2appat, $objDatos->t2apmat, $objDatos->t2nombres);
		foreach ($buscarm as $listest) {
			$id_madre = $listest->id;
			$ma = true;
		}
		if ($ma) {
			$reg_tutor = array(
				'tipo' => "MADRE",
				'id_padre' => $id_madre,
				'id_est' => $id_est
			);
			$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
		} else {
			$fecham = $objDatos->t2fnacanio . "-" . $mesm . "-" . $objDatos->t2fnacdia;
			$madre = array(
				'appaterno' => $objDatos->t2appat,
				'apmaterno' => $objDatos->t2apmat,
				'nombre' => $objDatos->t2nombres,
				'idioma' => $objDatos->t2idioma,
				'ocupacion' => $objDatos->t2ocup,
				'lugartra' => $objDatos->t2lug,
				'grado' => $objDatos->t2grado,
				'fecha' => $fecham,
				'ci' => $objDatos->t2ci,
				'com' => $objDatos->t2comple,
				'ex' => $objDatos->t2extension,
				'telefono' => $objDatos->t2ofifono,
				'celular' => $objDatos->t2celular
			);
			$madre1 = $this->estud->save_padres($madre);
			$buscarM = $this->estud->buscar_padres($objDatos->t2appat, $objDatos->t2apmat, $objDatos->t2nombres);
			foreach ($buscarM as $listest) {
				$id_madre = $listest->id;
			}
			$reg_tutor = array(
				'tipo' => "MADRE",
				'id_padre' => $id_madre,
				'id_est' => $id_est
			);
			$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
		}
		$tu = false;
		$buscart = $this->estud->buscar_padres($objDatos->t3appat, $objDatos->t3apmat, $objDatos->t3nombres);
		foreach ($buscart as $listest) {
			$id_tutor = $listest->id;
			$tu = true;
		}
		if ($tu) {
			$reg_tutor = array(
				'tipo' => "TUTOR",
				'id_padre' => $id_tutor,
				'id_est' => $id_est
			);
			$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
		} else {
			$fechat = $objDatos->t3fnacanio . "-" . $mest . "-" . $objDatos->t3fnacdia;
			$tutor = array(
				'appaterno' => $objDatos->t3appat,
				'apmaterno' => $objDatos->t3apmat,
				'nombre' => $objDatos->t3nombres,
				'idioma' => $objDatos->t3idioma,
				'ocupacion' => $objDatos->t3ocup,
				'lugartra' => $objDatos->t3lug,
				'grado' => $objDatos->t3grado,
				'fecha' => $fechat,
				'ci' => $objDatos->t3ci,
				'com' => $objDatos->t3comple,
				'ex' => $objDatos->t3extension,
				'telefono' => $objDatos->t3ofifono,
				'celular' => $objDatos->t3celular,
				'parentesco' => $objDatos->t3parentesco
			);
			$tutor1 = $this->estud->save_padres($tutor);
			$buscart = $this->estud->buscar_padres($objDatos->t3appat, $objDatos->t3apmat, $objDatos->t3nombres);
			foreach ($buscart as $listest) {
				$id_tutor = $listest->id;
			}
			$reg_tutor = array(
				'tipo' => "TUTOR",
				'id_padre' => $id_tutor,
				'id_est' => $id_est
			);
			$reg_tutor1 = $this->estud->save_reg_tutor($reg_tutor);
		}
		$numero1 = $this->estud->count_contrato($objDatos->gestion);
		foreach ($numero1 as $numeros) {
			$numero2 = $numeros->numero;
		}
		$nu = 10000;
		$numero = $nu + $numero2;
		if ($objDatos->contrato == "P") {
			$contrato = array(
				'departamento' => "CHUQUISACA",
				'fecha' => $fecha,
				'codigo' => "CTT0-" . $numero . "/" . $objDatos->gestion,
				'gestion' => $objDatos->gestion,
				'id_est' => $id_est,
				'id_padre' => $id_padre
			);
			$contrato1 = $this->estud->save_contrato($contrato);
		}
		if ($objDatos->contrato == "M") {
			$contrato = array(
				'departamento' => "CHUQUISACA",
				'fecha' => $fecha,
				'codigo' => "CTT0-" . $numero . "/" . $objDatos->gestion,
				'gestion' => $objDatos->gestion,
				'id_est' => $id_est,
				'id_padre' => $id_madre
			);
			$contrato1 = $this->estud->save_contrato($contrato);
		}
		if ($objDatos->contrato == "T") {
			$contrato = array(
				'departamento' => "CHUQUISACA",
				'fecha' => $fecha,
				'codigo' => "CTT0-" . $numero . "/" . $objDatos->gestion,
				'gestion' => $objDatos->gestion,
				'id_est' => $id_est,
				'id_padre' => $id_tutor
			);
			$contrato1 = $this->estud->save_contrato($contrato);
		}
		/*$estudi=array(
			'inscrito'=>true
		);
		$where=array(
			'idest'=>$this->input->post('idest1')
		);
		$estud32=$this->estud->updates("estudiante",$where,$estudi);*/

		// echo "salas ".$this->input->post('nivel');
		// exit();
		//echo json_encode(array("status"=>TRUE));

		echo json_encode(array("status" => TRUE, "idinscrip" => $id_est));
	}

	public function print_rude($idins)
	{
		// $str = $estudiante->nombres;
		//$arr1 = str_split($str);
		// $tamaño=sizeof($arr1);
		// $con=35;
		// foreach($arr1 as $arr1s)
		// 	{
		// 		$this->pdf->setXY($con,55);
		//       	// $this->pdf->Cell(5,4,utf8_decode($estudiante->appaterno),'TBLR',0,'L','0');
		//       	$this->pdf->Cell(4,4,utf8_decode($arr1s),'TBLR',0,'L','0');
		//       	$con=$con+4;;
		// 	}
		// 	for($i=0;$i<=19-$tamaño;$i++)
		// 	{
		// 		$this->pdf->setXY($con,55);
		//       	$this->pdf->Cell(4,4,"",'TBLR',0,'L','0');
		//       	$con=$con+4;;
		// 	}

		$ids = explode('-', $idins, -1);
		$gestion = $ids[1];
		$id_est = $ids[0];

		$incripccion = $this->estud->get_data_inscrips($id_est, $gestion);
		/*print_r($incripccion);
		exit();*/

		$fechains = explode('-', $incripccion->fecha . "-", -1);

		$unidadeduca = $this->estud->get_data_colegio($incripccion->colegio);
		$nacimientos = $this->estud->get_data_nacimiento($idins);
		$nacimiento = $this->estud->get_data_nacimientos($id_est);
		$estudiantes = $this->estud->get_data_estudiantes1($id_est, $gestion);
		$discap = $this->estud->get_data_discap1($id_est);
		$local = $this->estud->get_data_direccion1($id_est, $gestion);
		// $idioma=$this->estud->get_data_idioma($idins);
		$idioma = $this->estud->get_data_idioma1($id_est);
		if ($idioma != null) {
			if ($idioma->nacion == 'Ninguno') $nacion1 = 'X';
			else $nacion1 = '';
			if ($idioma->nacion == 'Araona') $nacion2 = 'X';
			else $nacion2 = '';
			if ($idioma->nacion == 'Aymara') $nacion3 = 'X';
			else $nacion3 = '';
			if ($idioma->nacion == 'Baure') $nacion4 = 'X';
			else $nacion4 = '';
			if ($idioma->nacion == 'Bésiro') $nacion5 = 'X';
			else $nacion5 = '';
			if ($idioma->nacion == 'Canichana') $nacion6 = 'X';
			else $nacion6 = '';
			if ($idioma->nacion == 'Cavineño') $nacion7 = 'X';
			else $nacion7 = '';
			if ($idioma->nacion == 'Cayubaba') $nacion8 = 'X';
			else $nacion8 = '';
			if ($idioma->nacion == 'Chacobo') $nacion9 = 'X';
			else $nacion9 = '';
			if ($idioma->nacion == 'Chiman') $nacion10 = 'X';
			else $nacion10 = '';
			if ($idioma->nacion == 'Ese Ejja') $nacion11 = 'X';
			else $nacion11 = '';
			if ($idioma->nacion == 'Guaraní') $nacion12 = 'X';
			else $nacion12 = '';
			if ($idioma->nacion == 'Guarasuawe') $nacion13 = 'X';
			else $nacion13 = '';
			if ($idioma->nacion == 'Guarayo') $nacion14 = 'X';
			else $nacion14 = '';
			if ($idioma->nacion == 'Itonoma') $nacion15 = 'X';
			else $nacion15 = '';
			if ($idioma->nacion == 'Leco') $nacion16 = 'X';
			else $nacion16 = '';
			if ($idioma->nacion == 'Machajuyai-Kallawaya') $nacion17 = 'X';
			else $nacion17 = '';
			if ($idioma->nacion == 'Machineru') $nacion18 = 'X';
			else $nacion18 = '';
			if ($idioma->nacion == 'Maropa') $nacion19 = 'X';
			else $nacion19 = '';
			if ($idioma->nacion == 'Mojeño-Ignaciano') $nacion20 = 'X';
			else $nacion20 = '';
			if ($idioma->nacion == 'Mojeño-Trinitario') $nacion21 = 'X';
			else $nacion21 = '';
			if ($idioma->nacion == 'Moré') $nacion22 = 'X';
			else $nacion22 = '';
			if ($idioma->nacion == 'Mosetén') $nacion23 = 'X';
			else $nacion23 = '';
			if ($idioma->nacion == 'Movima') $nacion24 = 'X';
			else $nacion24 = '';
			if ($idioma->nacion == 'Tacawara') $nacion25 = 'X';
			else $nacion25 = '';
			if ($idioma->nacion == 'Puquina') $nacion26 = 'X';
			else $nacion26 = '';
			if ($idioma->nacion == 'Quechua') $nacion27 = 'X';
			else $nacion27 = '';
			if ($idioma->nacion == 'Sirionó') $nacion28 = 'X';
			else $nacion28 = '';
			if ($idioma->nacion == 'Tacana') $nacion29 = 'X';
			else $nacion29 = '';
			if ($idioma->nacion == 'Tapiete') $nacion30 = 'X';
			else $nacion30 = '';
			if ($idioma->nacion == 'Toromona') $nacion31 = 'X';
			else $nacion31 = '';
			if ($idioma->nacion == 'Uru-Chipaya') $nacion32 = 'X';
			else $nacion32 = '';
			if ($idioma->nacion == 'Weenhayek') $nacion33 = 'X';
			else $nacion33 = '';
			if ($idioma->nacion == 'Yaminawa') $nacion34 = 'X';
			else $nacion34 = '';
			if ($idioma->nacion == 'Yuki') $nacion35 = 'X';
			else $nacion35 = '';
			if ($idioma->nacion == 'Yuracaré') $nacion36 = 'X';
			else $nacion36 = '';
			if ($idioma->nacion == 'Zamuco') $nacion37 = 'X';
			else $nacion37 = '';
			if ($idioma->nacion == 'Afroboliviano') $nacion38 = 'X';
			else $nacion38 = '';
		}
		// $salud=$this->estud->get_data_salud($idins);
		$salud = $this->estud->get_data_salud1($id_est, $gestion);
		if ($salud->centro) $salud1 = 'X';
		else $salud1 = '';
		if ($salud->centro) $salud2 = '';
		else $salud2 = 'X';
		$salud3 = '';
		$salud4 = '';
		$salud5 = '';
		$salud6 = '';
		$salud7 = '';
		$salud8 = '';
		$hospital = $this->estud->get_data_hospital($id_est, $gestion);
		foreach ($hospital as $hospital1) {
			if ($hospital1->id_host == 1) $salud3 = 'X';
			if ($hospital1->id_host == 2) $salud4 = 'X';
			if ($hospital1->id_host == 3) $salud5 = 'X';
			if ($hospital1->id_host == 4) $salud6 = 'X';
			if ($hospital1->id_host == 5) $salud7 = 'X';
			if ($hospital1->id_host == 6) $salud8 = 'X';
		}
		// if ($salud->visitaposta1=='si') $salud3='X'; else $salud3='';
		// if ($salud->visitaposta2=='si') $salud4='X'; else $salud4='';
		// if ($salud->visitaposta3=='si') $salud5='X'; else $salud5='';
		// if ($salud->visitaposta4=='si') $salud6='X'; else $salud6='';
		// if ($salud->visitaposta5=='si') $salud7='X'; else $salud7='';
		// if ($salud->visitaposta6=='si') $salud8='X'; else $salud8='';
		if ($salud->cantidad == '1 a 2') $salud9 = 'X';
		else $salud9 = '';
		if ($salud->cantidad == '3 a 5') $salud10 = 'X';
		else $salud10 = '';
		if ($salud->cantidad == '6 o +') $salud11 = 'X';
		else $salud11 = '';
		if ($salud->cantidad == 'ninguna') $salud12 = 'X';
		else $salud12 = '';
		if ($salud->seguro) $salud13 = 'X';
		else $salud13 = '';
		if ($salud->seguro) $salud14 = '';
		else $salud14 = 'X';
		$servicios = $this->estud->get_data_servicios1($id_est, $gestion);

		if ($servicios->caneria) $serv1 = 'X';
		else $serv1 = '';
		if ($servicios->caneria) $serv2 = '';
		else $serv2 = 'X';
		if ($servicios->banio) $serv3 = 'X';
		else $serv3 = '';
		if ($servicios->banio) $serv4 = '';
		else $serv4 = 'X';
		if ($servicios->alcantarillado) $serv5 = 'X';
		else $serv5 = '';
		if ($servicios->alcantarillado) $serv6 = '';
		else $serv6 = 'X';
		if ($servicios->electricidad) $serv7 = 'X';
		else $serv7 = '';
		if ($servicios->electricidad) $serv8 = '';
		else $serv8 = 'X';
		if ($servicios->basura) $serv9 = 'X';
		else $serv9 = '';
		if ($servicios->basura) $serv10 = '';
		else $serv10 = 'X';
		if ($servicios->hogar == 'Propia') $serv11 = 'X';
		else $serv11 = '';
		if ($servicios->hogar == 'Alquilada') $serv12 = 'X';
		else $serv12 = '';
		if ($servicios->hogar == 'Anticretico') $serv13 = 'X';
		else $serv13 = '';
		if ($servicios->hogar == 'Cedida') $serv14 = 'X';
		else $serv14 = '';
		if ($servicios->hogar == 'prestada') $serv15 = 'X';
		else $serv15 = '';
		if ($servicios->hogar == 'cttomixto') $serv16 = 'X';
		else $serv16 = '';
		// $net=$this->estud->get_data_net($idins);
		$net = $this->estud->get_data_internet($id_est, $gestion);
		$net1 = '';
		$net2 = '';
		$net3 = '';
		$net4 = '';
		$net5 = '';
		foreach ($net as $nets) {
			if ($nets->id_inter == 1) $net1 = 'X';
			if ($nets->id_inter == 2) $net2 = 'X';
			if ($nets->id_inter == 3) $net3 = 'X';
			if ($nets->id_inter == 4) $net4 = 'X';
			if ($nets->id_inter == 5) $net5 = 'X';
		}
		// if ($net->netvivienda=='si') $net1='X'; else $net1='';
		// if ($net->netunidadedu=='si') $net2='X'; else $net2='';
		// if ($net->netpublic=='si') $net3='X'; else $net3='';
		// if ($net->netcelu=='si') $net4='X'; else $net4='';
		// if ($net->nonet=='si') $net5='X'; else $net5='';
		if ($servicios->freinternet == 'Diariamente') $net6 = 'X';
		else $net6 = '';
		if ($servicios->freinternet == 'Una vez a la semana') $net7 = 'X';
		else $net7 = '';
		if ($servicios->freinternet == 'Mas de una vez a la semana') $net8 = 'X';
		else $net8 = '';
		if ($servicios->freinternet == 'Una vez al mes') $net9 = 'X';
		else $net9 = '';

		// $trabajo=$this->estud->get_data_trabajo($idins);
		$trabajo = $this->estud->get_data_trabajo1($id_est, $gestion);
		if ($trabajo->trabajo) $work1 = 'X';
		else $work1 = '';
		if ($trabajo->trabajo) $work2 = '';
		else $work2 = 'X';
		$mest = $this->estud->get_data_mes($id_est, $gestion);
		$work3 = '';
		$work4 = '';
		$work5 = '';
		$work6 = '';
		$work7 = '';
		$work8 = '';
		$work9 = '';
		$work10 = '';
		$work11 = '';
		$work12 = '';
		$work13 = '';
		$work14 = '';
		foreach ($mest as $mests) {
			if ($mests->id_mes == 1) $work3 = 'X';
			if ($mests->id_mes == 2) $work4 = 'X';
			if ($mests->id_mes == 3) $work5 = 'X';
			if ($mests->id_mes == 4) $work6 = 'X';
			if ($mests->id_mes == 5) $work7 = 'X';
			if ($mests->id_mes == 6) $work8 = 'X';
			if ($mests->id_mes == 7) $work9 = 'X';
			if ($mests->id_mes == 8) $work10 = 'X';
			if ($mests->id_mes == 9) $work11 = 'X';
			if ($mests->id_mes == 10) $work12 = 'X';
			if ($mests->id_mes == 11) $work13 = 'X';
			if ($mests->id_mes == 12) $work14 = 'X';
		}
		// if ($trabajo->ene=='si') $work3='X'; else $work3='';
		// if ($trabajo->feb=='si') $work4='X'; else $work4='';
		// if ($trabajo->mar=='si') $work5='X'; else $work5='';
		// if ($trabajo->abr=='si') $work6='X'; else $work6='';
		// if ($trabajo->may=='si') $work7='X'; else $work7='';
		// if ($trabajo->jun=='si') $work8='X'; else $work8='';
		// if ($trabajo->jul=='si') $work9='X'; else $work9='';
		// if ($trabajo->ago=='si') $work10='X'; else $work10='';
		// if ($trabajo->sep=='si') $work11='X'; else $work11='';
		// if ($trabajo->oct=='si') $work12='X'; else $work12='';
		// if ($trabajo->nov=='si') $work13='X'; else $work13='';
		// if ($trabajo->dic=='si') $work14='X'; else $work14='';
		if ($trabajo->actividad == 'trabajo agricultura') $work15 = 'X';
		else $work15 = '';
		if ($trabajo->actividad == 'ayudo agricultura') $work16 = 'X';
		else $work16 = '';
		if ($trabajo->actividad == 'ayudo hogar') $work17 = 'X';
		else $work17 = '';
		if ($trabajo->actividad == 'trabajo mineria') $work18 = 'X';
		else $work18 = '';
		if ($trabajo->actividad == 'trabajo dependiente') $work19 = 'X';
		else $work19 = '';
		if ($trabajo->actividad == 'Vendedor por cuenta propia') $work20 = 'X';
		else $work20 = '';
		if ($trabajo->actividad == 'Transporte o mecánica') $work21 = 'X';
		else $work21 = '';
		if ($trabajo->actividad == 'Lustrabotas') $work22 = 'X';
		else $work22 = '';
		if ($trabajo->actividad == 'Trabajador del hogar o niñero') $work23 = 'X';
		else $work23 = '';
		if ($trabajo->actividad == 'Ayudante familiar') $work24 = 'X';
		else $work24 = '';
		if ($trabajo->actividad == 'Ayudante de venta o comercio') $work25 = 'X';
		else $work25 = '';
		if ($trabajo->actividad == 'otro') $work26 = 'X';
		else $work26 = '';
		if ($trabajo->manana) $work27 = 'X';
		else $work27 = '';
		if ($trabajo->tarde) $work28 = 'X';
		else $work28 = '';
		if ($trabajo->noche) $work29 = 'X';
		else $work29 = '';
		if ($trabajo->frecuencia == 'Todos los dias') $work30 = 'X';
		else $work30 = '';
		if ($trabajo->frecuencia == 'Fines de semana') $work31 = 'X';
		else $work31 = '';
		if ($trabajo->frecuencia == 'Dias Festivos') $work32 = 'X';
		else $work32 = '';
		if ($trabajo->frecuencia == 'Dias habiles') $work33 = 'X';
		else $work33 = '';
		if ($trabajo->frecuencia == 'Eventual / esporádico') $work34 = 'X';
		else $work34 = '';
		if ($trabajo->frecuencia == 'En vacaciones') $work35 = 'X';
		else $work35 = '';
		if ($trabajo->pago) $work36 = 'X';
		else $work36 = '';
		if ($trabajo->pago) $work40 = '';
		else $work40 = 'X';
		if ($trabajo->tipopago == 'nopago') $work37 = 'X';
		else $work37 = '';
		if ($trabajo->tipopago == 'En especie') $work38 = 'X';
		else $work38 = '';
		if ($trabajo->tipopago == 'Dinero') $work39 = 'X';
		else $work39 = '';

		// $transp=$this->estud->get_data_transporte($idins);
		$transp = $this->estud->get_data_transporte1($id_est, $gestion);
		if ($transp->transporte == 'pie') $transp1 = 'X';
		else $transp1 = '';
		if ($transp->transporte == 'vehiculo') $transp2 = 'X';
		else $transp2 = '';
		if ($transp->transporte == 'fluvial') $transp3 = 'X';
		else $transp3 = '';
		if ($transp->transporte == 'otro') $transp4 = 'X';
		else $transp4 = '';
		if ($transp->tiempo == 'Menos de media hora') $transp5 = 'X';
		else $transp5 = '';
		if ($transp->tiempo == 'Entre media hora y una hora') $transp6 = 'X';
		else $transp6 = '';
		if ($transp->tiempo == 'Entre una a dos horas') $transp7 = 'X';
		else $transp7 = '';
		if ($transp->tiempo == 'Dos horas o mas') $transp8 = 'X';
		else $transp8 = '';
		// $abandono=$this->estud->get_data_abandono($idins);
		if ($transp->abandono) $aban1 = 'X';
		else $aban1 = '';
		if ($transp->abandono) $aban2 = '';
		else $aban2 = 'X';
		$abandono = $this->estud->get_data_abandono1($id_est, $gestion);
		$aban3 = '';
		$aban4 = '';
		$aban5 = '';
		$aban6 = '';
		$aban7 = '';
		$aban8 = '';
		$aban9 = '';
		$aban10 = '';
		$aban11 = '';
		$aban12 = '';
		$aban13 = '';
		$aban14 = '';
		$otrarazon = "";
		foreach ($abandono as $abandonos) {
			if ($abandonos->id_aban == 1) $aban3 = 'X';
			if ($abandonos->id_aban == 2) $aban4 = 'X';
			if ($abandonos->id_aban == 3) $aban5 = 'X';
			if ($abandonos->id_aban == 4) $aban6 = 'X';
			if ($abandonos->id_aban == 5) $aban7 = 'X';
			if ($abandonos->id_aban == 6) $aban8 = 'X';
			if ($abandonos->id_aban == 7) $aban9 = 'X';
			if ($abandonos->id_aban == 8) $aban10 = 'X';
			if ($abandonos->id_aban == 9) $aban11 = 'X';
			if ($abandonos->id_aban == 10) $aban12 = 'X';
			if ($abandonos->id_aban == 11) $aban13 = 'X';
			if ($abandonos->id_aban == 12) $aban14 = 'X';
			$otrarazon = $abandonos->nombre;
		}
		// if ($abandono->razon0=='si') $aban3='X'; else $aban3='';
		// if ($abandono->razon1=='si') $aban4='X'; else $aban4='';
		// if ($abandono->razon2=='si') $aban5='X'; else $aban5='';
		// if ($abandono->razon3=='si') $aban6='X'; else $aban6='';
		// if ($abandono->razon4=='si') $aban7='X'; else $aban7='';
		// if ($abandono->razon5=='si') $aban8='X'; else $aban8='';
		// if ($abandono->razon6=='si') $aban9='X'; else $aban9='';
		// if ($abandono->razon7=='si') $aban10='X'; else $aban10='';
		// if ($abandono->razon8=='si') $aban11='X'; else $aban11='';
		// if ($abandono->razon9=='si') $aban12='X'; else $aban12='';
		// if ($abandono->razon10=='si') $aban13='X'; else $aban13='';
		// if ($abandono->razon11=='si') $aban14='X'; else $aban14='';
		$tutores = $this->estud->get_data_tutores($id_est, $gestion);
		$padre = $padresnull;
		$madre = $padresnull;
		$tutor = $padresnull;
		$id_padre = 0;
		$id_padre = 0;
		$id_tutor = 0;
		foreach ($tutores as $tutores1) {
			if ($tutores1->tipo == "PADRE") {
				$id_padre = $tutores1->id_padre;
			}
			if ($tutores1->tipo == "MADRE") {
				$id_madre = $tutores1->id_padre;
			}
			if ($tutores1->tipo == "TUTOR") {
				$id_tutor = $tutores1->id_padre;
			}
		}
		// print_r($tutores);
		// exit();
		$padre = $this->estud->get_data_padres($id_padre);
		$madre = $this->estud->get_data_padres($id_madre);
		$tutor = $this->estud->get_data_padres($id_tutor);
		// $madre=$this->estud->get_data_madre($idins);
		// $tutor=$this->estud->get_data_tutor($idins);
		$inscripcion = $this->estud->get_data_inscrip($idins);


		$this->load->library('ins_pdf');

		ob_start();
		$this->pdf = new Ins_pdf('P', 'cm', 'letter');
		$this->pdf->AddPage('P', 'letter');
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle("Formulario RUDE");
		$this->pdf->Image('assets/images/logo.png', 6, 2, 20, 0);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('FORMULARIO DE INSCRIPCION/ACTUALIZACION'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('REGISTRO UNICO DE ESTUDIANTES'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->SetFont('Arial', 'B', 6);
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('Resolución Ministerial N° 1298/2018'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('LA INFORMACION RECABADA POR EL RUDE SERÁ UTILIZADA Y EXCLUSIVAMENTE PARA'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('FINES DE DISEÑO Y EJECUCIÓN DE POLÍTICAS PÚBLICAS EDUCATIVAS Y SOCIALES'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setXY(10, 27);
		$this->pdf->Cell(135, 8, utf8_decode('Importante:El formulario debe ser llenado por el padre, madre o tutor(a)'), 0, 0, 'L');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('DATOS DE LA UNIDAD EDUCATIVA'), 0, 0, 'L');
		$this->pdf->setXY(120, 30);
		$this->pdf->Cell(80, 5, utf8_decode('CÓDIGO SIE DE LA UNIDAD EDUCATIVA: ' . $unidadeduca->sie), 'TBLR', 0, 'L', '0'); //SIE
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('II DATOS DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->Cell(190, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 39);
		$this->pdf->Cell(135, 8, utf8_decode('2.1 APELLIDO(S) Y NOMBRE(S):'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->Cell(135, 8, utf8_decode('Apellido Paterno:'), 0, 0, 'L');
		$this->pdf->setXY(35, 45);
		$this->pdf->Cell(80, 4, utf8_decode($estudiantes->appaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(12);
		$this->pdf->Cell(135, 8, utf8_decode('Apellido Materno:'), 0, 0, 'L');
		$this->pdf->setXY(35, 50);
		$this->pdf->Cell(80, 4, utf8_decode($estudiantes->apmaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(12);
		$this->pdf->Cell(135, 8, utf8_decode('Nombre(s):'), 0, 0, 'L');
		$this->pdf->setXY(35, 55);
		$this->pdf->Cell(80, 4, utf8_decode($estudiantes->nombre), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('2.2. LUGAR DE NACIMIENTO:'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->Cell(30, 8, utf8_decode('País:'), 0, 0, 'L');
		$this->pdf->setXY(25, 64);
		$this->pdf->Cell(40, 4, utf8_decode($nacimiento->pais), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(65, 62);
		$this->pdf->Cell(30, 8, utf8_decode('Depto:'), 0, 0, 'L');
		$this->pdf->setXY(78, 64);
		$this->pdf->Cell(37, 4, utf8_decode($nacimiento->departamento), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->Cell(30, 8, utf8_decode('Provincia:'), 0, 0, 'L');
		$this->pdf->setXY(25, 69);
		$this->pdf->Cell(40, 4, utf8_decode($nacimiento->provincia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(65, 67);
		$this->pdf->Cell(35, 8, utf8_decode('Localidad:'), 0, 0, 'L');
		$this->pdf->setXY(78, 69);
		$this->pdf->Cell(37, 4, utf8_decode($nacimiento->localidad), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(10);
		$this->pdf->Cell(50, 8, utf8_decode('2.3. CERTIFICADO DE NACIMIENTO:'), 0, 0, 'L');
		$this->pdf->setX(75);
		$this->pdf->Cell(40, 8, utf8_decode('2.4. FECHA DE NACIMIENTO:'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->setXY(12, 78);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->oficialia), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(28);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->libro), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(44);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->partida), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(60);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->folio), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(78);
		$fechainaci = explode('-', $nacimiento->fnacimiento . "-", -1);
		$this->pdf->Cell(10, 4, utf8_decode($fechainaci[2]), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(89);
		$this->pdf->Cell(10, 4, utf8_decode($fechainaci[1]), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(100);
		$this->pdf->Cell(15, 4, utf8_decode($fechainaci[0]), 'TBLR', 0, 'C', '0');
		$this->pdf->Ln('2');
		$this->pdf->setX(10);
		$this->pdf->setX(12);
		$this->pdf->Cell(20, 8, utf8_decode('Oficialia N°'), 0, 0, 'L');
		$this->pdf->setX(30);
		$this->pdf->Cell(20, 8, utf8_decode('Libro N°'), 0, 0, 'L');
		$this->pdf->setX(45);
		$this->pdf->Cell(20, 8, utf8_decode('Partida N°'), 0, 0, 'L');
		$this->pdf->setX(63);
		$this->pdf->Cell(20, 8, utf8_decode('Folio N°'), 0, 0, 'L');
		$this->pdf->setX(80);
		$this->pdf->Cell(20, 8, utf8_decode('Dia'), 0, 0, 'L');
		$this->pdf->setX(90);
		$this->pdf->Cell(20, 8, utf8_decode('Mes'), 0, 0, 'L');
		$this->pdf->setX(103);
		$this->pdf->Cell(20, 8, utf8_decode('Año'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(10);
		$this->pdf->Cell(50, 8, utf8_decode('2.5. DOCUMENTO DE INDENTIFICACION:'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->setX(12);
		$this->pdf->Cell(30, 4, utf8_decode($estudiantes->ci), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(50);
		$this->pdf->Cell(10, 4, utf8_decode($estudiantes->complemento), 'TBLR', 0, 'L', '0');
		$this->pdf->setX(65);
		$this->pdf->Cell(10, 4, utf8_decode($estudiantes->extension), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('2');
		$this->pdf->setX(16);
		$this->pdf->Cell(30, 8, utf8_decode('Carnet de Identidad'), 0, 0, 'L');
		$this->pdf->setX(50);
		$this->pdf->Cell(20, 8, utf8_decode('Compleo'), 0, 0, 'L');
		$this->pdf->setX(66);
		$this->pdf->Cell(20, 8, utf8_decode('Exten'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setXY(120, 39);
		$this->pdf->Cell(50, 8, utf8_decode('2.6. CODIGO RUDE (Código automático generado por el Sistema)'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->setX(120);
		$this->pdf->Cell(75, 4, utf8_decode($estudiantes->rude), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(120);
		$this->pdf->Cell(50, 8, utf8_decode('2.7. SEXO:'), 0, 0, 'L');
		$this->pdf->setX(132);
		$this->pdf->Cell(10, 8, utf8_decode('Masculino'), 0, 0, 'L');
		$this->pdf->setXY(144, 50);
		if ($estudiantes->genero == "M") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($genero1),'TBLR',0,'L','0');
		$this->pdf->setXY(132, 53);
		$this->pdf->Cell(10, 8, utf8_decode('Femenino'), 0, 0, 'L');
		$this->pdf->setXY(144, 55);
		if ($estudiantes->genero == "F") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($genero2),'TBLR',0,'L','0');
		$this->pdf->setXY(150, 48);
		$this->pdf->Cell(50, 8, utf8_decode('2.8. ¿ESTUDIANTE TIENE DISCAPACIDAD?'), 0, 0, 'L');
		$this->pdf->setXY(158, 53);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'L');
		$this->pdf->setXY(162, 55);
		if ($discap->discapacidad) {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discap1),'TBLR',0,'L','0');
		$this->pdf->setXY(173, 53);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'L');
		$this->pdf->setXY(178, 55);
		if ($discap->discapacidad) {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discap2),'TBLR',0,'L','0');
		$this->pdf->setXY(120, 59);
		$this->pdf->Cell(50, 8, utf8_decode('2.9. N° DE REGISTRO DE DISCAPACIDAD O IBC:'), 0, 0, 'L');
		$this->pdf->setXY(171, 61);
		$this->pdf->Cell(24, 4, utf8_decode($discap->ibc), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(120, 65);
		$this->pdf->Cell(50, 8, utf8_decode('2.10. TIPO DE DISCAPACIDAD:'), 0, 0, 'L');
		$this->pdf->setXY(158, 65);
		$this->pdf->Cell(50, 8, utf8_decode('2.11. GRADO DE DISCAPACIDAD:'), 0, 0, 'L');
		$this->pdf->setXY(129, 70);
		$this->pdf->Cell(10, 8, utf8_decode('Psíquica'), 0, 0, 'L');
		$this->pdf->setXY(140, 72);
		if ($discap->tipo == "Psíquica") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt1),'TBLR',0,'L','0');
		$this->pdf->setXY(152, 70);
		$this->pdf->Cell(10, 8, utf8_decode('Auditiva'), 0, 0, 'L');
		$this->pdf->setXY(162, 72);
		if ($discap->tipo == "auditiva") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt5),'TBLR',0,'L','0');
		$this->pdf->setXY(174, 70);
		$this->pdf->Cell(10, 8, utf8_decode('Leve'), 0, 0, 'R');
		$this->pdf->setXY(184, 72);
		if ($discap->grado == "Leve") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapg1),'TBLR',0,'L','0');
		$this->pdf->setXY(129, 75);
		$this->pdf->Cell(10, 8, utf8_decode('Autismo'), 0, 0, 'L');
		$this->pdf->setXY(140, 77);
		if ($discap->tipo == "autismo") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt2),'TBLR',0,'L','0');
		$this->pdf->setXY(152, 75);
		$this->pdf->Cell(10, 8, utf8_decode('Física-Motora'), 0, 0, 'R');
		$this->pdf->setXY(162, 77);
		if ($discap->tipo == "fisica-motora") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt6),'TBLR',0,'L','0');
		$this->pdf->setXY(174, 75);
		$this->pdf->Cell(10, 8, utf8_decode('Moderado'), 0, 0, 'R');
		$this->pdf->setXY(184, 77);
		if ($discap->grado == "Moderado") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapg2),'TBLR',0,'L','0');

		$this->pdf->setXY(129, 80);
		$this->pdf->Cell(10, 8, utf8_decode('Sindrome de Down'), 0, 0, 'R');
		$this->pdf->setXY(140, 82);
		if ($discap->tipo == "down") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt3),'TBLR',0,'L','0');
		$this->pdf->setXY(152, 80);
		$this->pdf->Cell(10, 8, utf8_decode('Sordocequera'), 0, 0, 'R');
		$this->pdf->setXY(162, 82);
		if ($discap->tipo == "sordocequera") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt4),'TBLR',0,'L','0');
		$this->pdf->setXY(174, 80);
		$this->pdf->Cell(10, 8, utf8_decode('Grave'), 0, 0, 'R');
		$this->pdf->setXY(184, 82);
		if ($discap->grado == "Grave") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapg3),'TBLR',0,'L','0');

		$this->pdf->setXY(129, 85);
		$this->pdf->Cell(10, 8, utf8_decode('Intelectual'), 0, 0, 'R');
		$this->pdf->setXY(140, 87);
		if ($discap->tipo == "intelectual") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt4),'TBLR',0,'L','0');
		$this->pdf->setXY(152, 85);
		$this->pdf->Cell(10, 8, utf8_decode('Multiple'), 0, 0, 'R');
		$this->pdf->setXY(162, 87);
		if ($discap->tipo == "multiple") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt8),'TBLR',0,'L','0');
		$this->pdf->setXY(174, 85);
		$this->pdf->Cell(10, 8, utf8_decode('Muy Grave'), 0, 0, 'R');
		$this->pdf->setXY(184, 87);
		if ($discap->grado == "Muy Grave") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapg4),'TBLR',0,'L','0');

		$this->pdf->setXY(152, 90);
		$this->pdf->Cell(10, 8, utf8_decode('Visual'), 0, 0, 'R');
		$this->pdf->setXY(162, 92);
		if ($discap->tipo == "visual") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapt9),'TBLR',0,'L','0');
		$this->pdf->setXY(174, 90);
		$this->pdf->Cell(10, 8, utf8_decode('Ceguera total'), 0, 0, 'R');
		$this->pdf->setXY(184, 92);
		if ($discap->grado == "Ceguera total") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapg5),'TBLR',0,'L','0');

		$this->pdf->setXY(174, 95);
		$this->pdf->Cell(10, 8, utf8_decode('Baja Visión'), 0, 0, 'R');
		$this->pdf->setXY(184, 97);
		if ($discap->grado == "Baja Visión") {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($discapg6),'TBLR',0,'L','0')

		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('III DIRECCIÓN ACTUAL DE LA O EL ESTUDIANTE (Información para uso exclusivo de la Unidad educativa)'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->Cell(190, 18, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 106);
		$this->pdf->Cell(25, 8, utf8_decode('Departamento:'), 0, 0, 'R');
		$this->pdf->setXY(36, 107);
		$this->pdf->Cell(40, 4, utf8_decode($local->departamento), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(70, 106);
		$this->pdf->Cell(25, 8, utf8_decode('Provincia:'), 0, 0, 'R');
		$this->pdf->setXY(95, 107);
		$this->pdf->Cell(40, 4, utf8_decode($local->provincia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(130, 106);
		$this->pdf->Cell(25, 8, utf8_decode('Municipio:'), 0, 0, 'R');
		$this->pdf->setXY(155, 107);
		$this->pdf->Cell(40, 4, utf8_decode($local->municipio), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 111);
		$this->pdf->Cell(25, 8, utf8_decode('Localidad:'), 0, 0, 'R');
		$this->pdf->setXY(36, 112);
		$this->pdf->Cell(40, 4, utf8_decode($local->localidad), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(70, 111);
		$this->pdf->Cell(25, 8, utf8_decode('Zona/Villa:'), 0, 0, 'R');
		$this->pdf->setXY(95, 112);
		$this->pdf->Cell(40, 4, utf8_decode($local->zona), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(130, 111);
		$this->pdf->Cell(25, 8, utf8_decode('Av./Calle:'), 0, 0, 'R');
		$this->pdf->setXY(155, 112);
		$this->pdf->Cell(40, 4, utf8_decode($local->calle), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 116);
		$this->pdf->Cell(25, 8, utf8_decode('N° Vivienda:'), 0, 0, 'R');
		$this->pdf->setXY(36, 117);
		$this->pdf->Cell(40, 4, utf8_decode($local->nro), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(70, 116);
		$this->pdf->Cell(25, 8, utf8_decode('Télefono Fijo:'), 0, 0, 'R');
		$this->pdf->setXY(95, 117);
		$this->pdf->Cell(40, 4, utf8_decode($local->telefono), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(130, 116);
		$this->pdf->Cell(25, 8, utf8_decode('Celular:'), 0, 0, 'R');
		$this->pdf->setXY(155, 117);
		$this->pdf->Cell(40, 4, utf8_decode($local->celular), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('5');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('IV ASPECTOS SOCIOECONÓMICOS DE LA O EL ESTUDIANTE'), 0, 0, 'L');
		$this->pdf->setXY(10, 125);
		$this->pdf->Cell(50, 8, utf8_decode('4.1 IDIOMA Y PERTENENCIA CULTURAL DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->Cell(35, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 130);
		$this->pdf->Cell(50, 8, utf8_decode('4.1.1 ¿Cuál es el idioma natal?:'), 0, 0, 'L');
		$this->pdf->setXY(13, 137);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->natal), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 140);
		$this->pdf->Cell(50, 8, utf8_decode('4.1.2 ¿Qué idiomas habla?:'), 0, 0, 'L');
		$this->pdf->setXY(13, 147);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(13, 152);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(13, 157);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(46, 131);
		$this->pdf->Cell(98, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(48, 130);
		$this->pdf->Cell(50, 8, utf8_decode('4.1.3 ¿Pertenece a una nación, pueblo indígena originario?:'), 0, 0, 'L');
		$this->pdf->setXY(51, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Ninguno'), 0, 0, 'R');
		$this->pdf->setXY(66, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Chimán'), 0, 0, 'R');
		$this->pdf->setXY(93, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Mojeño-Agraciano'), 0, 0, 'R');
		$this->pdf->setXY(119, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion20), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Tapiete'), 0, 0, 'R');
		$this->pdf->setXY(138, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion30), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(51, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Afroboliviano'), 0, 0, 'R');
		$this->pdf->setXY(66, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion38), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Ese Ejja'), 0, 0, 'R');
		$this->pdf->setXY(93, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Mojeño-Trinitario'), 0, 0, 'R');
		$this->pdf->setXY(119, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion21), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Toromona'), 0, 0, 'R');
		$this->pdf->setXY(138, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion31), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(51, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Araona'), 0, 0, 'R');
		$this->pdf->setXY(66, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Guaraní'), 0, 0, 'R');
		$this->pdf->setXY(93, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Moré'), 0, 0, 'R');
		$this->pdf->setXY(119, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion22), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Uru-Chipaya'), 0, 0, 'R');
		$this->pdf->setXY(138, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion32), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Aymara'), 0, 0, 'R');
		$this->pdf->setXY(66, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Guarasuawe'), 0, 0, 'R');
		$this->pdf->setXY(93, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Mosetén'), 0, 0, 'R');
		$this->pdf->setXY(119, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion23), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Weebhayek'), 0, 0, 'R');
		$this->pdf->setXY(138, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion33), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Baure'), 0, 0, 'R');
		$this->pdf->setXY(66, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Guarayo'), 0, 0, 'R');
		$this->pdf->setXY(93, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion14), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Movima'), 0, 0, 'R');
		$this->pdf->setXY(119, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion24), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Yaminawa'), 0, 0, 'R');
		$this->pdf->setXY(138, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion34), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Bésiro'), 0, 0, 'R');
		$this->pdf->setXY(66, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Itonoma'), 0, 0, 'R');
		$this->pdf->setXY(93, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion15), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Tacaware'), 0, 0, 'R');
		$this->pdf->setXY(119, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion25), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Yuki'), 0, 0, 'R');
		$this->pdf->setXY(138, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion35), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Canichana'), 0, 0, 'R');
		$this->pdf->setXY(66, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Leco'), 0, 0, 'R');
		$this->pdf->setXY(93, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion16), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Puquina'), 0, 0, 'R');
		$this->pdf->setXY(119, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion26), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Yuracaré'), 0, 0, 'R');
		$this->pdf->setXY(138, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion36), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Cavineño'), 0, 0, 'R');
		$this->pdf->setXY(66, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Machajuyai-Kallawaya'), 0, 0, 'R');
		$this->pdf->setXY(93, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion17), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Quechua'), 0, 0, 'R');
		$this->pdf->setXY(119, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion27), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Zamuco'), 0, 0, 'R');
		$this->pdf->setXY(138, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion37), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 174);
		$this->pdf->Cell(15, 8, utf8_decode('Cayubaba'), 0, 0, 'R');
		$this->pdf->setXY(66, 176);
		$this->pdf->Cell(4, 4, utf8_decode($nacion8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 174);
		$this->pdf->Cell(15, 8, utf8_decode('Machineri'), 0, 0, 'R');
		$this->pdf->setXY(93, 176);
		$this->pdf->Cell(4, 4, utf8_decode($nacion18), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 174);
		$this->pdf->Cell(15, 8, utf8_decode('Sironó'), 0, 0, 'R');
		$this->pdf->setXY(119, 176);
		$this->pdf->Cell(4, 4, utf8_decode($nacion28), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 179);
		$this->pdf->Cell(15, 8, utf8_decode('Chacobo'), 0, 0, 'R');
		$this->pdf->setXY(66, 181);
		$this->pdf->Cell(4, 4, utf8_decode($nacion9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 179);
		$this->pdf->Cell(15, 8, utf8_decode('Maropa'), 0, 0, 'R');
		$this->pdf->setXY(93, 181);
		$this->pdf->Cell(4, 4, utf8_decode($nacion19), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 179);
		$this->pdf->Cell(15, 8, utf8_decode('Tacana'), 0, 0, 'R');
		$this->pdf->setXY(119, 181);
		$this->pdf->Cell(4, 4, utf8_decode($nacion29), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(145, 131);
		$this->pdf->Cell(56, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(146, 125);
		$this->pdf->Cell(50, 8, utf8_decode('4.2 SALUD DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->setXY(146, 130);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.1 ¿Existe algún Centro de Salud/Posta?:'), 0, 0, 'L');
		$this->pdf->setXY(147, 135);
		$this->pdf->Cell(15, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(162, 137);
		$this->pdf->Cell(4, 4, utf8_decode($salud1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(158, 135);
		$this->pdf->Cell(15, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(173, 137);
		$this->pdf->Cell(4, 4, utf8_decode($salud2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(146, 140);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.2 ¿E que centro de Salud fue atendido?:'), 0, 0, 'L');
		$this->pdf->setXY(162, 144);
		$this->pdf->Cell(15, 8, utf8_decode('1. Caja o seguro de salud'), 0, 0, 'R');
		$this->pdf->setXY(178, 146);
		$this->pdf->Cell(4, 4, utf8_decode($salud3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 149);
		$this->pdf->Cell(15, 8, utf8_decode('2. Centros de salud públicos'), 0, 0, 'R');
		$this->pdf->setXY(178, 151);
		$this->pdf->Cell(4, 4, utf8_decode($salud4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 154);
		$this->pdf->Cell(15, 8, utf8_decode('3. Centros de salud privados'), 0, 0, 'R');
		$this->pdf->setXY(178, 156);
		$this->pdf->Cell(4, 4, utf8_decode($salud5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 159);
		$this->pdf->Cell(15, 8, utf8_decode('4. En su vivienda'), 0, 0, 'R');
		$this->pdf->setXY(178, 161);
		$this->pdf->Cell(4, 4, utf8_decode($salud6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 163);
		$this->pdf->Cell(15, 8, utf8_decode('5. Medicina Tradicional'), 0, 0, 'R');
		$this->pdf->setXY(178, 166);
		$this->pdf->Cell(4, 4, utf8_decode($salud7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 168);
		$this->pdf->Cell(15, 8, utf8_decode('6. Farmacia sin receta'), 0, 0, 'R');
		$this->pdf->setXY(178, 171);
		$this->pdf->Cell(4, 4, utf8_decode($salud8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(146, 174);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.3 ¿Cuantas veces fue al centro de salud?:'), 0, 0, 'L');
		$this->pdf->setXY(143, 178);
		$this->pdf->Cell(15, 8, utf8_decode('1 a 2 veces'), 0, 0, 'R');
		$this->pdf->setXY(158, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 178);
		$this->pdf->Cell(15, 8, utf8_decode('3 a 5 veces'), 0, 0, 'R');
		$this->pdf->setXY(175, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(176, 178);
		$this->pdf->Cell(10, 8, utf8_decode('6 ó +'), 0, 0, 'R');
		$this->pdf->setXY(186, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(190, 178);
		$this->pdf->Cell(6, 8, utf8_decode('ning'), 0, 0, 'R');
		$this->pdf->setXY(196, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(146, 183);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.3 ¿Tiene seguro de salud?:'), 0, 0, 'L');
		$this->pdf->setXY(150, 187);
		$this->pdf->Cell(15, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(165, 189);
		$this->pdf->Cell(4, 4, utf8_decode($salud13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(161, 187);
		$this->pdf->Cell(15, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(176, 189);
		$this->pdf->Cell(4, 4, utf8_decode($salud14), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 192);
		$this->pdf->Cell(50, 8, utf8_decode('4.3 ACCESO DE LA O EL ESTUDIANTE A SEVICIOS BASICOS:'), 0, 0, 'L');
		$this->pdf->setXY(10, 198);
		$this->pdf->Cell(191, 33, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO

		$this->pdf->setXY(12, 197);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.1 ¿Tiene acceso a agua por cañeria de red?:'), 0, 0, 'L');
		$this->pdf->setXY(12, 202);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(22, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(24, 202);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(34, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv2), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(62, 197);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.4 ¿Una energía eléctrica en su vivienda?:'), 0, 0, 'L');
		$this->pdf->setXY(62, 202);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(72, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(74, 202);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(84, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv8), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(120, 197);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.6 La vivienda que ocupa el hogar es:'), 0, 0, 'L');
		$this->pdf->setXY(132, 202);
		$this->pdf->Cell(10, 8, utf8_decode('Propia'), 0, 0, 'R');
		$this->pdf->setXY(142, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(179, 202);
		$this->pdf->Cell(10, 8, utf8_decode('Cedida por Servicios'), 0, 0, 'R');
		$this->pdf->setXY(189, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv14), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(12, 207);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.2 ¿Tiene Baño su vivienda?:'), 0, 0, 'L');
		$this->pdf->setXY(12, 212);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(22, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(24, 212);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(34, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv4), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(62, 207);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.5 ¿Cuenta con servicio de recojo de Basura?:'), 0, 0, 'L');
		$this->pdf->setXY(62, 212);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(72, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(74, 212);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(84, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv10), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(132, 207);
		$this->pdf->Cell(10, 8, utf8_decode('Alquilada'), 0, 0, 'R');
		$this->pdf->setXY(142, 209);
		$this->pdf->Cell(4, 4, utf8_decode($serv12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(179, 207);
		$this->pdf->Cell(10, 8, utf8_decode('Prestada por parientes o amigos'), 0, 0, 'R');
		$this->pdf->setXY(189, 209);
		$this->pdf->Cell(4, 4, utf8_decode($serv15), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(12, 217);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.2 ¿Tiene red de alcantarillado?:'), 0, 0, 'L');
		$this->pdf->setXY(12, 222);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(22, 224);
		$this->pdf->Cell(4, 4, utf8_decode($serv5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(24, 222);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(34, 224);
		$this->pdf->Cell(4, 4, utf8_decode($serv6), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(132, 212);
		$this->pdf->Cell(10, 8, utf8_decode('Anticrético'), 0, 0, 'R');
		$this->pdf->setXY(142, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(179, 212);
		$this->pdf->Cell(10, 8, utf8_decode('Contrato mixto (alquile o anticrético)'), 0, 0, 'R');
		$this->pdf->setXY(189, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv16), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 229);
		$this->pdf->Cell(50, 8, utf8_decode('4.4 ACCESO A INTERNET DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->setXY(10, 235);
		$this->pdf->Cell(191, 16, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO

		$this->pdf->setXY(12, 234);
		$this->pdf->Cell(50, 8, utf8_decode('4.4.1 El estudiante accede a internet en:'), 0, 0, 'L');
		$this->pdf->setXY(120, 234);
		$this->pdf->Cell(50, 8, utf8_decode('4.4.2 ¿Con que frecuencia usa Internet?'), 0, 0, 'L');
		$this->pdf->setXY(22, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Su vivienda'), 0, 0, 'R');
		$this->pdf->setXY(33, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(52, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Lugares Públicos'), 0, 0, 'R');
		$this->pdf->setXY(63, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(82, 238);
		$this->pdf->Cell(10, 8, utf8_decode('No accede a Internet'), 0, 0, 'R');
		$this->pdf->setXY(93, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(135, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Diariamente'), 0, 0, 'R');
		$this->pdf->setXY(145, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(177, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Mas de una vez a la semana'), 0, 0, 'R');
		$this->pdf->setXY(187, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net8), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(22, 243);
		$this->pdf->Cell(10, 8, utf8_decode('La unidad Educativa'), 0, 0, 'R');
		$this->pdf->setXY(33, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(52, 243);
		$this->pdf->Cell(10, 8, utf8_decode('Teléfono Célular'), 0, 0, 'R');
		$this->pdf->setXY(63, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net4), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(135, 243);
		$this->pdf->Cell(10, 8, utf8_decode('Una vez a la semana'), 0, 0, 'R');
		$this->pdf->setXY(145, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(177, 243);
		$this->pdf->Cell(10, 8, utf8_decode('Una vez al mes'), 0, 0, 'R');
		$this->pdf->setXY(187, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net9), 'TBLR', 0, 'L', '0');
		$this->pdf->AddPage('P', 'letter');
		$this->pdf->setXY(10, 8);
		$this->pdf->Cell(50, 8, utf8_decode('4.5 ACTIVIDAD LABORAL DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->setXY(10, 14);
		$this->pdf->Cell(191, 33, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(12, 14);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.1 ¿En la pasada gestion ¿El estudiante trabajo?'), 0, 0, 'L');
		$this->pdf->setXY(15, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
		$this->pdf->setXY(25, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(25, 18);
		$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
		$this->pdf->setXY(35, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work2), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Ene'), 0, 0, 'R');
		$this->pdf->setXY(20, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(20, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Feb'), 0, 0, 'R');
		$this->pdf->setXY(30, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Mar'), 0, 0, 'R');
		$this->pdf->setXY(40, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(40, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Abr'), 0, 0, 'R');
		$this->pdf->setXY(50, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 27);
		$this->pdf->Cell(10, 8, utf8_decode('May'), 0, 0, 'R');
		$this->pdf->setXY(20, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(20, 27);
		$this->pdf->Cell(10, 8, utf8_decode('Jun'), 0, 0, 'R');
		$this->pdf->setXY(30, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 27);
		$this->pdf->Cell(10, 8, utf8_decode('Jul'), 0, 0, 'R');
		$this->pdf->setXY(40, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(40, 27);
		$this->pdf->Cell(10, 8, utf8_decode('Ago'), 0, 0, 'R');
		$this->pdf->setXY(50, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Sep'), 0, 0, 'R');
		$this->pdf->setXY(20, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(20, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Oct'), 0, 0, 'R');
		$this->pdf->setXY(30, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Nov'), 0, 0, 'R');
		$this->pdf->setXY(40, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(40, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Dic'), 0, 0, 'R');
		$this->pdf->setXY(50, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work14), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(72, 14);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.2 ¿En la pasada gestion ¿El estudiante trabajo?'), 0, 0, 'L');
		$this->pdf->setXY(75, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Agricultura'), 0, 0, 'R');
		$this->pdf->setXY(85, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work15), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Vendedor dependiente'), 0, 0, 'R');
		$this->pdf->setXY(115, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work19), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Trabajo del hogar o niñero'), 0, 0, 'R');
		$this->pdf->setXY(150, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work23), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 23);
		$this->pdf->Cell(10, 8, utf8_decode('Ganaderia o pesca'), 0, 0, 'R');
		$this->pdf->setXY(85, 25);
		$this->pdf->Cell(4, 4, utf8_decode($work16), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 23);
		$this->pdf->Cell(10, 8, utf8_decode('Vendedor cuenta propia'), 0, 0, 'R');
		$this->pdf->setXY(115, 25);
		$this->pdf->Cell(4, 4, utf8_decode($work20), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 23);
		$this->pdf->Cell(10, 8, utf8_decode('Ayudante familiar'), 0, 0, 'R');
		$this->pdf->setXY(150, 25);
		$this->pdf->Cell(4, 4, utf8_decode($work24), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 28);
		$this->pdf->Cell(10, 8, utf8_decode('Minería'), 0, 0, 'R');
		$this->pdf->setXY(85, 30);
		$this->pdf->Cell(4, 4, utf8_decode($work18), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 28);
		$this->pdf->Cell(10, 8, utf8_decode('Transporte o mecánica'), 0, 0, 'R');
		$this->pdf->setXY(115, 30);
		$this->pdf->Cell(4, 4, utf8_decode($work21), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 28);
		$this->pdf->Cell(10, 8, utf8_decode('Ayudante en el Hogar'), 0, 0, 'R');
		$this->pdf->setXY(150, 30);
		$this->pdf->Cell(4, 4, utf8_decode($work23), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 33);
		$this->pdf->Cell(10, 8, utf8_decode('Construcción'), 0, 0, 'R');
		$this->pdf->setXY(85, 35);
		$this->pdf->Cell(4, 4, utf8_decode($work17), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 33);
		$this->pdf->Cell(10, 8, utf8_decode('Lustra Botas'), 0, 0, 'R');
		$this->pdf->setXY(115, 35);
		$this->pdf->Cell(4, 4, utf8_decode($work22), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 33);
		$this->pdf->Cell(10, 8, utf8_decode('Otro trabajo'), 0, 0, 'R');
		$this->pdf->setXY(150, 35);
		$this->pdf->Cell(4, 4, utf8_decode($work26), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(125, 38);
		$this->pdf->Cell(10, 8, utf8_decode('(Especifique)'), 0, 0, 'R');
		$this->pdf->setXY(135, 40);
		$this->pdf->Cell(19, 4, utf8_decode($trabajo->otro), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(155, 14);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.3 ¿En qué turno trabajo el estudiante?'), 0, 0, 'L');
		$this->pdf->setXY(160, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Mañana'), 0, 0, 'R');
		$this->pdf->setXY(170, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work27), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Tarde'), 0, 0, 'R');
		$this->pdf->setXY(182, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work28), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Noche'), 0, 0, 'R');
		$this->pdf->setXY(195, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work29), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(155, 22);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.4 ¿Con que frecuencia trabajo?'), 0, 0, 'L');
		$this->pdf->setXY(160, 26);
		$this->pdf->Cell(10, 8, utf8_decode('Todos dias'), 0, 0, 'R');
		$this->pdf->setXY(170, 28);
		$this->pdf->Cell(4, 4, utf8_decode($work30), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 26);
		$this->pdf->Cell(10, 8, utf8_decode('FinSe'), 0, 0, 'R');
		$this->pdf->setXY(182, 28);
		$this->pdf->Cell(4, 4, utf8_decode($work31), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 26);
		$this->pdf->Cell(10, 8, utf8_decode('Feriad'), 0, 0, 'R');
		$this->pdf->setXY(195, 28);
		$this->pdf->Cell(4, 4, utf8_decode($work32), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 31);
		$this->pdf->Cell(10, 8, utf8_decode('Dia Habil'), 0, 0, 'R');
		$this->pdf->setXY(170, 33);
		$this->pdf->Cell(4, 4, utf8_decode($work33), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 31);
		$this->pdf->Cell(10, 8, utf8_decode('Event'), 0, 0, 'R');
		$this->pdf->setXY(182, 33);
		$this->pdf->Cell(4, 4, utf8_decode($work34), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 31);
		$this->pdf->Cell(10, 8, utf8_decode('Vacaci'), 0, 0, 'R');
		$this->pdf->setXY(195, 33);
		$this->pdf->Cell(4, 4, utf8_decode($work35), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(155, 35);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.4 ¿Recibió algún pago?'), 0, 0, 'L');
		$this->pdf->setXY(152, 39);
		$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
		$this->pdf->setXY(162, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work40), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 39);
		$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
		$this->pdf->setXY(170, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work36), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 39);
		$this->pdf->Cell(10, 8, utf8_decode('Espec'), 0, 0, 'R');
		$this->pdf->setXY(182, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work38), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 39);
		$this->pdf->Cell(10, 8, utf8_decode('Dinero'), 0, 0, 'R');
		$this->pdf->setXY(195, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work39), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 45);
		$this->pdf->Cell(50, 8, utf8_decode('4.6 MEDIO DE TRANSPORTE PARA LLEGAR A LA UNIDAD EDUCATIVA'), 0, 0, 'L');
		$this->pdf->setXY(10, 51);
		$this->pdf->Cell(80, 30, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 49);
		$this->pdf->Cell(10, 8, utf8_decode('4.6.1 ¿Cómo llega el estudiante a UE?'), 0, 0, 'L');
		$this->pdf->setXY(55, 49);
		$this->pdf->Cell(10, 8, utf8_decode('4.6.2 ¿En que tiempo llega?'), 0, 0, 'L');
		$this->pdf->setXY(30, 53);
		$this->pdf->Cell(10, 8, utf8_decode('A pie'), 0, 0, 'R');
		$this->pdf->setXY(40, 55);
		$this->pdf->Cell(4, 4, utf8_decode($transp1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 53);
		$this->pdf->Cell(10, 8, utf8_decode('Menos de Media Hora'), 0, 0, 'R');
		$this->pdf->setXY(85, 55);
		$this->pdf->Cell(4, 4, utf8_decode($transp5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 58);
		$this->pdf->Cell(10, 8, utf8_decode('En vehículo terrestre'), 0, 0, 'R');
		$this->pdf->setXY(40, 60);
		$this->pdf->Cell(4, 4, utf8_decode($transp2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 58);
		$this->pdf->Cell(10, 8, utf8_decode('Entre media hora y una hora'), 0, 0, 'R');
		$this->pdf->setXY(85, 60);
		$this->pdf->Cell(4, 4, utf8_decode($transp6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 63);
		$this->pdf->Cell(10, 8, utf8_decode('Fluvial'), 0, 0, 'R');
		$this->pdf->setXY(40, 65);
		$this->pdf->Cell(4, 4, utf8_decode($transp3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 63);
		$this->pdf->Cell(10, 8, utf8_decode('Entre una a dos horas'), 0, 0, 'R');
		$this->pdf->setXY(85, 65);
		$this->pdf->Cell(4, 4, utf8_decode($transp7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 68);
		$this->pdf->Cell(10, 8, utf8_decode('Otro (especifique)'), 0, 0, 'R');
		$this->pdf->setXY(40, 70);
		$this->pdf->Cell(4, 4, utf8_decode($transp4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 68);
		$this->pdf->Cell(10, 8, utf8_decode('Más de dos horas'), 0, 0, 'R');
		$this->pdf->setXY(85, 70);
		$this->pdf->Cell(4, 4, utf8_decode($transp8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(19, 75);
		$this->pdf->Cell(25, 4, utf8_decode($transp->otro), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(90, 45);
		$this->pdf->Cell(50, 8, utf8_decode('4.7 ABANDONO ECOLAR CORRESPONDIENTE A LA GESTIÓN ANTERIOR'), 0, 0, 'L');
		$this->pdf->setXY(91, 51);
		$this->pdf->Cell(110, 30, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(91, 49);
		$this->pdf->Cell(15, 8, utf8_decode('4.7.1 ¿El estudiante abandonó la Unidad Educativa el año pasado?'), 0, 0, 'L');
		$this->pdf->setXY(160, 50);
		$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
		$this->pdf->setXY(170, 52);
		$this->pdf->Cell(4, 4, utf8_decode($aban1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(170, 50);
		$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
		$this->pdf->setXY(180, 52);
		$this->pdf->Cell(4, 4, utf8_decode($aban2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(91, 53);
		$this->pdf->Cell(15, 8, utf8_decode('4.7.2 ¿Cuál o cuales fueron las raones de abandono escolar?'), 0, 0, 'L');
		$this->pdf->setXY(115, 57);
		$this->pdf->Cell(10, 8, utf8_decode('Tuvo que ayudar a sus padres'), 0, 0, 'R');
		$this->pdf->setXY(125, 59);
		$this->pdf->Cell(4, 4, utf8_decode($aban3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 57);
		$this->pdf->Cell(10, 8, utf8_decode('Tuvo trabajo renumerado'), 0, 0, 'R');
		$this->pdf->setXY(160, 59);
		$this->pdf->Cell(4, 4, utf8_decode($aban4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 57);
		$this->pdf->Cell(10, 8, utf8_decode('Falta de dinero'), 0, 0, 'R');
		$this->pdf->setXY(195, 59);
		$this->pdf->Cell(4, 4, utf8_decode($aban5), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(115, 62);
		$this->pdf->Cell(10, 8, utf8_decode('Precodidad / Rezago'), 0, 0, 'R');
		$this->pdf->setXY(125, 64);
		$this->pdf->Cell(4, 4, utf8_decode($aban6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 62);
		$this->pdf->Cell(10, 8, utf8_decode('La UE era distante'), 0, 0, 'R');
		$this->pdf->setXY(160, 64);
		$this->pdf->Cell(4, 4, utf8_decode($aban7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 62);
		$this->pdf->Cell(10, 8, utf8_decode('Labores de casa'), 0, 0, 'R');
		$this->pdf->setXY(195, 64);
		$this->pdf->Cell(4, 4, utf8_decode($aban8), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(115, 67);
		$this->pdf->Cell(10, 8, utf8_decode('Embarazo o paternidad'), 0, 0, 'R');
		$this->pdf->setXY(125, 69);
		$this->pdf->Cell(4, 4, utf8_decode($aban9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 67);
		$this->pdf->Cell(10, 8, utf8_decode('Enfermedad/Accid/Discap'), 0, 0, 'R');
		$this->pdf->setXY(160, 69);
		$this->pdf->Cell(4, 4, utf8_decode($aban10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 67);
		$this->pdf->Cell(10, 8, utf8_decode('Viaje o Traslado'), 0, 0, 'R');
		$this->pdf->setXY(195, 69);
		$this->pdf->Cell(4, 4, utf8_decode($aban11), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(115, 72);
		$this->pdf->Cell(10, 8, utf8_decode('Falta de Interés'), 0, 0, 'R');
		$this->pdf->setXY(125, 74);
		$this->pdf->Cell(4, 4, utf8_decode($aban12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 72);
		$this->pdf->Cell(10, 8, utf8_decode('Bullying o descriminación'), 0, 0, 'R');
		$this->pdf->setXY(160, 74);
		$this->pdf->Cell(4, 4, utf8_decode($aban13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(165, 72);
		$this->pdf->Cell(10, 8, utf8_decode('Otra'), 0, 0, 'R');
		$this->pdf->setXY(175, 74);
		$this->pdf->Cell(24, 4, utf8_decode($otrarazon), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 80);
		$this->pdf->Cell(50, 8, utf8_decode('V. DATOS DEL PADRE, MADRE O TUTOR (A) DE LA O EL ESTUDIANTE'), 0, 0, 'L');
		$this->pdf->setXY(10, 86);
		$this->pdf->Cell(192, 8, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(11, 86);
		$this->pdf->Cell(50, 8, utf8_decode('5.1 LA O EL ESTUDIANTE VIVE HABITUALMENTE CON: '), 0, 0, 'L');

		$this->pdf->setXY(80, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Padre y Madre'), 0, 0, 'R');
		$this->pdf->setXY(90, 88);
		if ($incripccion->vive == 'Padre y Madre') {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		$this->pdf->setXY(100, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Solo Padre'), 0, 0, 'R');
		$this->pdf->setXY(110, 88);
		if ($incripccion->vive == 'Solo Padre') {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($vivecon2),'TBLR',0,'L','0');
		$this->pdf->setXY(120, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Solo Madre'), 0, 0, 'R');
		$this->pdf->setXY(130, 88);
		if ($incripccion->vive == 'Solo Madre') {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($vivecon3),'TBLR',0,'L','0');
		$this->pdf->setXY(140, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Tutor'), 0, 0, 'R');
		$this->pdf->setXY(150, 88);
		if ($incripccion->vive == 'Tutor') {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($vivecon4),'TBLR',0,'L','0');
		$this->pdf->setXY(160, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Solo(a)'), 0, 0, 'R');
		$this->pdf->setXY(170, 88);
		if ($incripccion->vive == 'Solo(a)') {
			$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
		} else {
			$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
		}
		// $this->pdf->Cell(4,4,utf8_decode($vivecon5),'TBLR',0,'L','0');

		$this->pdf->setXY(10, 95);
		$this->pdf->Cell(95, 46, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(11, 94);
		$this->pdf->Cell(50, 8, utf8_decode('5.2 DATOS DEL PADRE: '), 0, 0, 'L');
		$this->pdf->setXY(106, 95);
		$this->pdf->Cell(96, 46, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(107, 94);
		$this->pdf->Cell(50, 8, utf8_decode('5.3 DATOS DE LA MADRE: '), 0, 0, 'L');

		$this->pdf->setXY(10, 98);
		$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
		$this->pdf->setXY(42, 100);
		$this->pdf->Cell(35, 4, utf8_decode($padre->ci), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 100);
		$this->pdf->Cell(10, 4, utf8_decode($padre->com), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(93, 100);
		$this->pdf->Cell(10, 4, utf8_decode($padre->ex), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 103);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
		$this->pdf->setXY(40, 105);
		$this->pdf->Cell(63, 4, utf8_decode($padre->appaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 108);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
		$this->pdf->setXY(40, 110);
		$this->pdf->Cell(63, 4, utf8_decode($padre->apmaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 113);
		$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
		$this->pdf->setXY(40, 115);
		$this->pdf->Cell(63, 4, utf8_decode($padre->nombre), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 118);
		$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
		$this->pdf->setXY(40, 120);
		$this->pdf->Cell(63, 4, utf8_decode($padre->idioma), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 123);
		$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
		$this->pdf->setXY(40, 125);
		$this->pdf->Cell(63, 4, utf8_decode($padre->ocupacion), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 128);
		$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
		$this->pdf->setXY(40, 130);
		$this->pdf->Cell(63, 4, utf8_decode($padre->grado), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 133);
		$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
		$this->pdf->setXY(40, 133);
		if ($padre->fecha == null || $padre->fecha == "") {
			$fechpadre = array('0' => "", '1' => "", '2' => "");
		} else {
			$fechpadre = explode('-', $padre->fecha . "-", -1);
		}
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(50, 135);
		$this->pdf->Cell(10, 4, utf8_decode($fechpadre[2]), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(60, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(70, 135);
		$this->pdf->Cell(10, 4, utf8_decode($fechpadre[1]), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(90, 135);
		$this->pdf->Cell(13, 4, utf8_decode($fechpadre[0]), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(108, 98);
		$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
		$this->pdf->setXY(140, 100);
		$this->pdf->Cell(35, 4, utf8_decode($madre->ci), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(178, 100);
		$this->pdf->Cell(10, 4, utf8_decode($madre->com), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(190, 100);
		$this->pdf->Cell(10, 4, utf8_decode($madre->ex), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 103);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
		$this->pdf->setXY(137, 105);
		$this->pdf->Cell(63, 4, utf8_decode($madre->appaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 108);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
		$this->pdf->setXY(137, 110);
		$this->pdf->Cell(63, 4, utf8_decode($madre->apmaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 113);
		$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
		$this->pdf->setXY(137, 115);
		$this->pdf->Cell(63, 4, utf8_decode($madre->nombre), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 118);
		$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
		$this->pdf->setXY(137, 120);
		$this->pdf->Cell(63, 4, utf8_decode($madre->idioma), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 123);
		$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
		$this->pdf->setXY(137, 125);
		$this->pdf->Cell(63, 4, utf8_decode($madre->ocupacion), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 128);
		$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
		$this->pdf->setXY(137, 130);
		$this->pdf->Cell(63, 4, utf8_decode($madre->grado), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 133);
		$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
		$this->pdf->setXY(140, 133);
		if ($madre->fecha == null || $madre->fecha == "") {
			$fechmadre = array('0' => "", '1' => "", '2' => "");
		} else {
			$fechmadre = explode('-', $madre->fecha . "-", -1);
		}
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(150, 135);
		$this->pdf->Cell(10, 4, utf8_decode($fechmadre[2]), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(158, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(168, 135);
		$this->pdf->Cell(10, 4, utf8_decode($fechmadre[1]), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(177, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(187, 135);
		$this->pdf->Cell(13, 4, utf8_decode($fechmadre[0]), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 142);
		$this->pdf->Cell(95, 49, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(11, 141);
		$this->pdf->Cell(50, 8, utf8_decode('5.4 DATOS DEL TUTOR: '), 0, 0, 'L');
		$this->pdf->setXY(10, 143);
		$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
		$this->pdf->setXY(42, 145);
		$this->pdf->Cell(35, 4, utf8_decode($tutor->ci), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 145);
		$this->pdf->Cell(10, 4, utf8_decode($tutor->com), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(93, 145);
		$this->pdf->Cell(10, 4, utf8_decode($tutor->ex), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 149);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
		$this->pdf->setXY(40, 150);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->appaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 154);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
		$this->pdf->setXY(40, 155);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->apmaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 158);
		$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
		$this->pdf->setXY(40, 160);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->nombre), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 163);
		$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
		$this->pdf->setXY(40, 165);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->idioma), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 168);
		$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
		$this->pdf->setXY(40, 170);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->ocupacion), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 173);
		$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
		$this->pdf->setXY(40, 175);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->grado), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 178);
		$this->pdf->Cell(30, 8, utf8_decode('¿Cuál es el parentesco?'), 0, 0, 'R');
		$this->pdf->setXY(40, 180);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->parentesco), 'TBLR', 0, 'L', '0');
		if ($tutor->fecha == null || $tutor->fecha == "") {
			$fechtutor = array('0' => "", '1' => "", '2' => "");
		} else {
			$fechtutor = explode('-', $tutor->fecha . "-", -1);
		}
		$this->pdf->setXY(10, 183);
		$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
		$this->pdf->setXY(40, 183);
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(50, 185);
		$this->pdf->Cell(10, 4, utf8_decode($fechtutor[2]), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(60, 183);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(70, 185);
		$this->pdf->Cell(10, 4, utf8_decode($fechtutor[1]), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 183);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(90, 185);
		$this->pdf->Cell(13, 4, utf8_decode($fechtutor[0]), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(100, 145);
		$this->pdf->Cell(20, 8, utf8_decode('Lugar:'), 0, 0, 'R');
		$arr1 = str_split($incripccion->departamento);
		$tamaño = sizeof($arr1);
		$con = 125;
		foreach ($arr1 as $arr1s) {
			$this->pdf->setXY($con, 147);
			$this->pdf->Cell(4, 4, utf8_decode($arr1s), 'TBLR', 0, 'L', '0');
			$con = $con + 4;;
		}
		for ($i = 0; $i <= 11 - $tamaño; $i++) {
			$this->pdf->setXY($con, 147);
			$this->pdf->Cell(4, 4, "", 'TBLR', 0, 'L', '0');
			$con = $con + 4;;
		}
		// $this->pdf->setXY(125,147);
		// $this->pdf->Cell(30,4,utf8_decode($incripccion->departamento),'TBLR',0,'L','0');

		$this->pdf->setXY(102, 153);
		$this->pdf->Cell(20, 8, utf8_decode('Fecha:'), 0, 0, 'R');
		$this->pdf->setXY(120, 153);
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$arr1 = str_split($fechains[2]);
		$con = 130;
		foreach ($arr1 as $arr1s) {
			$this->pdf->setXY($con, 155);
			$this->pdf->Cell(4, 4, utf8_decode($arr1s), 'TBLR', 0, 'L', '0');
			$con = $con + 4;;
		}
		// $this->pdf->setXY(130,155);
		// $this->pdf->Cell(10,4,utf8_decode($fechains[2]),'TBLR',0,'L','0');
		$this->pdf->setXY(140, 153);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$arr1 = str_split($fechains[1]);
		$con = 150;
		foreach ($arr1 as $arr1s) {
			$this->pdf->setXY($con, 155);
			$this->pdf->Cell(4, 4, utf8_decode($arr1s), 'TBLR', 0, 'L', '0');
			$con = $con + 4;;
		}
		// $this->pdf->setXY(150,155);
		// $this->pdf->Cell(10,4,utf8_decode($fechains[1]),'TBLR',0,'L','0');
		$this->pdf->setXY(160, 153);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$arr1 = str_split($fechains[0]);
		$con = 170;
		foreach ($arr1 as $arr1s) {
			$this->pdf->setXY($con, 155);
			$this->pdf->Cell(4, 4, utf8_decode($arr1s), 'TBLR', 0, 'L', '0');
			$con = $con + 4;;
		}
		// $this->pdf->setXY(170,155);
		// $this->pdf->Cell(13,4,utf8_decode($fechains[0]),'TBLR',0,'L','0');

		$this->pdf->setXY(110, 180);
		$this->pdf->SetFont('Arial', 'BU', 10);
		$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
		$this->pdf->Ln(6);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->setXY(110, 184);
		$this->pdf->Cell(40, 5, utf8_decode('Firma del Padre/ Madre/ Tutor'), 0, 0, 'C');

		$this->pdf->setXY(160, 180);
		$this->pdf->SetFont('Arial', 'BU', 10);
		$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
		$this->pdf->Ln(6);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->setXY(160, 184);
		$this->pdf->Cell(40, 5, utf8_decode('Firma del Director de la UE'), 0, 0, 'C');





		$this->pdf->Output("Inscrip - RUDE " . $idins . ".pdf", 'I');

		ob_end_flush();
	}
	public function print_rude_curso($idins)
	{

		$id = str_replace("%20", " ", $idins);
		$ids = explode('W', $id, -1);
		$año = $ids[0];
		$curso = $ids[1];
		$nivel = $ids[2];
		$cur = $this->estud->cursosp($curso, $nivel);

		$this->load->library('ins_pdf');

		ob_start();
		$this->pdf = new Ins_pdf('Letter');
		foreach ($cur as $curs) {

			$unidadeduca = $this->estud->get_data_unidadeduca($curs->idins);
			$nacimiento = $this->estud->get_data_nacimiento($curs->idins);
			$estudiante = $this->estud->get_data_estudiantes($nacimiento->idest);
			if ($estudiante->genero == 'M') $genero1 = 'X';
			else $genero1 = '';
			if ($estudiante->genero == 'F') $genero2 = 'X';
			else $genero2 = '';
			if ($estudiante->vivecon == 'Padre y Madre') $vivecon1 = 'X';
			else $vivecon1 = '';
			if ($estudiante->vivecon == 'Solo Padre') $vivecon2 = 'X';
			else $vivecon2 = '';
			if ($estudiante->vivecon == 'Solo Madre') $vivecon3 = 'X';
			else $vivecon3 = '';
			if ($estudiante->vivecon == 'Tutor') $vivecon4 = 'X';
			else $vivecon4 = '';
			if ($estudiante->vivecon == 'Solo') $vivecon5 = 'X';
			else $vivecon5 = '';

			$discap = $this->estud->get_data_discap($curs->idins);
			if ($discap->discap == 'si') $discap1 = 'X';
			else $discap1 = '';
			if ($discap->discap == 'no') $discap2 = 'X';
			else $discap2 = '';
			if ($discap->tipo == 'psiquica') $discapt1 = 'X';
			else $discapt1 = '';
			if ($discap->tipo == 'autismo') $discapt2 = 'X';
			else $discapt2 = '';
			if ($discap->tipo == 'down') $discapt3 = 'X';
			else $discapt3 = '';
			if ($discap->tipo == 'instelectual') $discapt4 = 'X';
			else $discapt4 = '';
			if ($discap->tipo == 'auditiva') $discapt5 = 'X';
			else $discapt5 = '';
			if ($discap->tipo == 'fisica-motora') $discapt6 = 'X';
			else $discapt6 = '';
			if ($discap->tipo == 'sordoceguera') $discapt7 = 'X';
			else $discapt7 = '';
			if ($discap->tipo == 'multiple') $discapt8 = 'X';
			else $discapt8 = '';
			if ($discap->tipo == 'visual') $discapt9 = 'X';
			else $discapt9 = '';
			if ($discap->gradodiscap == 'Leve') $discapg1 = 'X';
			else $discapg1 = '';
			if ($discap->gradodiscap == 'Moderado') $discapg2 = 'X';
			else $discapg2 = '';
			if ($discap->gradodiscap == 'Grave') $discapg3 = 'X';
			else $discapg3 = '';
			if ($discap->gradodiscap == 'Muy Grave') $discapg4 = 'X';
			else $discapg4 = '';
			if ($discap->gradodiscap == 'ceguera total') $discapg5 = 'X';
			else $discapg5 = '';
			if ($discap->gradodiscap == 'baja vision') $discapg6 = 'X';
			else $discapg6 = '';
			$local = $this->estud->get_data_local($curs->idins);
			$idioma = $this->estud->get_data_idioma($curs->idins);
			if ($idioma->nacion == 'Ninguno') $nacion1 = 'X';
			else $nacion1 = '';
			if ($idioma->nacion == 'Araona') $nacion2 = 'X';
			else $nacion2 = '';
			if ($idioma->nacion == 'Aymara') $nacion3 = 'X';
			else $nacion3 = '';
			if ($idioma->nacion == 'Baure') $nacion4 = 'X';
			else $nacion4 = '';
			if ($idioma->nacion == 'Bésiro') $nacion5 = 'X';
			else $nacion5 = '';
			if ($idioma->nacion == 'Canichana') $nacion6 = 'X';
			else $nacion6 = '';
			if ($idioma->nacion == 'Cavineño') $nacion7 = 'X';
			else $nacion7 = '';
			if ($idioma->nacion == 'Cayubaba') $nacion8 = 'X';
			else $nacion8 = '';
			if ($idioma->nacion == 'Chacobo') $nacion9 = 'X';
			else $nacion9 = '';
			if ($idioma->nacion == 'Chiman') $nacion10 = 'X';
			else $nacion10 = '';
			if ($idioma->nacion == 'Ese Ejja') $nacion11 = 'X';
			else $nacion11 = '';
			if ($idioma->nacion == 'Guaraní') $nacion12 = 'X';
			else $nacion12 = '';
			if ($idioma->nacion == 'Guarasuawe') $nacion13 = 'X';
			else $nacion13 = '';
			if ($idioma->nacion == 'Guarayo') $nacion14 = 'X';
			else $nacion14 = '';
			if ($idioma->nacion == 'Itonoma') $nacion15 = 'X';
			else $nacion15 = '';
			if ($idioma->nacion == 'Leco') $nacion16 = 'X';
			else $nacion16 = '';
			if ($idioma->nacion == 'Machajuyai-Kallawaya') $nacion17 = 'X';
			else $nacion17 = '';
			if ($idioma->nacion == 'Machineru') $nacion18 = 'X';
			else $nacion18 = '';
			if ($idioma->nacion == 'Maropa') $nacion19 = 'X';
			else $nacion19 = '';
			if ($idioma->nacion == 'Mojeño-Ignaciano') $nacion20 = 'X';
			else $nacion20 = '';
			if ($idioma->nacion == 'Mojeño-Trinitario') $nacion21 = 'X';
			else $nacion21 = '';
			if ($idioma->nacion == 'Moré') $nacion22 = 'X';
			else $nacion22 = '';
			if ($idioma->nacion == 'Mosetén') $nacion23 = 'X';
			else $nacion23 = '';
			if ($idioma->nacion == 'Movima') $nacion24 = 'X';
			else $nacion24 = '';
			if ($idioma->nacion == 'Tacawara') $nacion25 = 'X';
			else $nacion25 = '';
			if ($idioma->nacion == 'Puquina') $nacion26 = 'X';
			else $nacion26 = '';
			if ($idioma->nacion == 'Quechua') $nacion27 = 'X';
			else $nacion27 = '';
			if ($idioma->nacion == 'Sirionó') $nacion28 = 'X';
			else $nacion28 = '';
			if ($idioma->nacion == 'Tacana') $nacion29 = 'X';
			else $nacion29 = '';
			if ($idioma->nacion == 'Tapiete') $nacion30 = 'X';
			else $nacion30 = '';
			if ($idioma->nacion == 'Toromona') $nacion31 = 'X';
			else $nacion31 = '';
			if ($idioma->nacion == 'Uru-Chipaya') $nacion32 = 'X';
			else $nacion32 = '';
			if ($idioma->nacion == 'Weenhayek') $nacion33 = 'X';
			else $nacion33 = '';
			if ($idioma->nacion == 'Yaminawa') $nacion34 = 'X';
			else $nacion34 = '';
			if ($idioma->nacion == 'Yuki') $nacion35 = 'X';
			else $nacion35 = '';
			if ($idioma->nacion == 'Yuracaré') $nacion36 = 'X';
			else $nacion36 = '';
			if ($idioma->nacion == 'Zamuco') $nacion37 = 'X';
			else $nacion37 = '';
			if ($idioma->nacion == 'Afroboliviano') $nacion38 = 'X';
			else $nacion38 = '';
			$salud = $this->estud->get_data_salud($curs->idins);
			if ($salud->posta == 'si') $salud1 = 'X';
			else $salud1 = '';
			if ($salud->posta == 'no') $salud2 = 'X';
			else $salud2 = '';
			if ($salud->visitaposta1 == 'si') $salud3 = 'X';
			else $salud3 = '';
			if ($salud->visitaposta2 == 'si') $salud4 = 'X';
			else $salud4 = '';
			if ($salud->visitaposta3 == 'si') $salud5 = 'X';
			else $salud5 = '';
			if ($salud->visitaposta4 == 'si') $salud6 = 'X';
			else $salud6 = '';
			if ($salud->visitaposta5 == 'si') $salud7 = 'X';
			else $salud7 = '';
			if ($salud->visitaposta6 == 'si') $salud8 = 'X';
			else $salud8 = '';
			if ($salud->veces == '1 a 2') $salud9 = 'X';
			else $salud9 = '';
			if ($salud->veces == '3 a 5') $salud10 = 'X';
			else $salud10 = '';
			if ($salud->veces == '6 o +') $salud11 = 'X';
			else $salud11 = '';
			if ($salud->veces == 'ninguna') $salud12 = 'X';
			else $salud12 = '';
			if ($salud->seguro == 'si') $salud13 = 'X';
			else $salud13 = '';
			if ($salud->seguro == 'no') $salud14 = 'X';
			else $salud14 = '';
			$servicios = $this->estud->get_data_servicios($curs->idins);
			if ($servicios->agua == 'si') $serv1 = 'X';
			else $serv1 = '';
			if ($servicios->agua == 'no') $serv2 = 'X';
			else $serv2 = '';
			if ($servicios->banio == 'si') $serv3 = 'X';
			else $serv3 = '';
			if ($servicios->banio == 'no') $serv4 = 'X';
			else $serv4 = '';
			if ($servicios->alcanta == 'si') $serv5 = 'X';
			else $serv5 = '';
			if ($servicios->alcanta == 'no') $serv6 = 'X';
			else $serv6 = '';
			if ($servicios->energia == 'si') $serv7 = 'X';
			else $serv7 = '';
			if ($servicios->energia == 'no') $serv8 = 'X';
			else $serv8 = '';
			if ($servicios->basura == 'si') $serv9 = 'X';
			else $serv9 = '';
			if ($servicios->basura == 'no') $serv10 = 'X';
			else $serv10 = '';
			if ($servicios->hogar == 'Propia') $serv11 = 'X';
			else $serv11 = '';
			if ($servicios->hogar == 'Alquilada') $serv12 = 'X';
			else $serv12 = '';
			if ($servicios->hogar == 'Anticretico') $serv13 = 'X';
			else $serv13 = '';
			if ($servicios->hogar == 'Cedida') $serv14 = 'X';
			else $serv14 = '';
			if ($servicios->hogar == 'prestada') $serv15 = 'X';
			else $serv15 = '';
			if ($servicios->hogar == 'cttomixto') $serv16 = 'X';
			else $serv16 = '';
			$net = $this->estud->get_data_net($curs->idins);
			if ($net->netvivienda == 'si') $net1 = 'X';
			else $net1 = '';
			if ($net->netunidadedu == 'si') $net2 = 'X';
			else $net2 = '';
			if ($net->netpublic == 'si') $net3 = 'X';
			else $net3 = '';
			if ($net->netcelu == 'si') $net4 = 'X';
			else $net4 = '';
			if ($net->nonet == 'si') $net5 = 'X';
			else $net5 = '';
			if ($net->frecuencia == 'Diariamente') $net6 = 'X';
			else $net6 = '';
			if ($net->frecuencia == 'Una vez a la semana') $net7 = 'X';
			else $net7 = '';
			if ($net->frecuencia == 'Mas de una vez a la semana') $net8 = 'X';
			else $net8 = '';
			if ($net->frecuencia == 'Una vez al mes') $net9 = 'X';
			else $net9 = '';
			$trabajo = $this->estud->get_data_trabajo($curs->idins);
			if ($trabajo->trabajo == 'si') $work1 = 'X';
			else $work1 = '';
			if ($trabajo->trabajo == 'no') $work2 = 'X';
			else $work2 = '';
			if ($trabajo->ene == 'si') $work3 = 'X';
			else $work3 = '';
			if ($trabajo->feb == 'si') $work4 = 'X';
			else $work4 = '';
			if ($trabajo->mar == 'si') $work5 = 'X';
			else $work5 = '';
			if ($trabajo->abr == 'si') $work6 = 'X';
			else $work6 = '';
			if ($trabajo->may == 'si') $work7 = 'X';
			else $work7 = '';
			if ($trabajo->jun == 'si') $work8 = 'X';
			else $work8 = '';
			if ($trabajo->jul == 'si') $work9 = 'X';
			else $work9 = '';
			if ($trabajo->ago == 'si') $work10 = 'X';
			else $work10 = '';
			if ($trabajo->sep == 'si') $work11 = 'X';
			else $work11 = '';
			if ($trabajo->oct == 'si') $work12 = 'X';
			else $work12 = '';
			if ($trabajo->nov == 'si') $work13 = 'X';
			else $work13 = '';
			if ($trabajo->dic == 'si') $work14 = 'X';
			else $work14 = '';
			if ($trabajo->actividad == 'trabajo agricultura') $work15 = 'X';
			else $work15 = '';
			if ($trabajo->actividad == 'ayudo agricultura') $work16 = 'X';
			else $work16 = '';
			if ($trabajo->actividad == 'ayudo hogar') $work17 = 'X';
			else $work17 = '';
			if ($trabajo->actividad == 'trabajo mineria') $work18 = 'X';
			else $work18 = '';
			if ($trabajo->actividad == 'trabajo dependiente') $work19 = 'X';
			else $work19 = '';
			if ($trabajo->actividad == 'Vendedor por cuenta propia') $work20 = 'X';
			else $work20 = '';
			if ($trabajo->actividad == 'Transporte o mecánica') $work21 = 'X';
			else $work21 = '';
			if ($trabajo->actividad == 'Lustrabotas') $work22 = 'X';
			else $work22 = '';
			if ($trabajo->actividad == 'Trabajador del hogar o niñero') $work23 = 'X';
			else $work23 = '';
			if ($trabajo->actividad == 'Ayudante familiar') $work24 = 'X';
			else $work24 = '';
			if ($trabajo->actividad == 'Ayudante de venta o comercio') $work25 = 'X';
			else $work25 = '';
			if ($trabajo->actividad == 'otro') $work26 = 'X';
			else $work26 = '';
			if ($trabajo->turnoman == 'si') $work27 = 'X';
			else $work27 = '';
			if ($trabajo->turnotar == 'si') $work28 = 'X';
			else $work28 = '';
			if ($trabajo->turnonoc == 'si') $work29 = 'X';
			else $work29 = '';
			if ($trabajo->frecuencia == 'Todos los dias') $work30 = 'X';
			else $work30 = '';
			if ($trabajo->frecuencia == 'Fines de semana') $work31 = 'X';
			else $work31 = '';
			if ($trabajo->frecuencia == 'Dias Festivos') $work32 = 'X';
			else $work32 = '';
			if ($trabajo->frecuencia == 'Dias habiles') $work33 = 'X';
			else $work33 = '';
			if ($trabajo->frecuencia == 'Eventual / esporádico') $work34 = 'X';
			else $work34 = '';
			if ($trabajo->frecuencia == 'En vacaciones') $work35 = 'X';
			else $work35 = '';
			if ($trabajo->pago == 'si') $work36 = 'X';
			else $work36 = '';
			if ($trabajo->pago == 'no') $work40 = 'X';
			else $work40 = '';
			if ($trabajo->tipopago == 'nopago') $work37 = 'X';
			else $work37 = '';
			if ($trabajo->tipopago == 'En especie') $work38 = 'X';
			else $work38 = '';
			if ($trabajo->tipopago == 'Dinero') $work39 = 'X';
			else $work39 = '';
			$transp = $this->estud->get_data_transporte($curs->idins);
			if ($transp->llega == 'pie') $transp1 = 'X';
			else $transp1 = '';
			if ($transp->llega == 'vehiculo') $transp2 = 'X';
			else $transp2 = '';
			if ($transp->llega == 'fluvial') $transp3 = 'X';
			else $transp3 = '';
			if ($transp->llega == 'otro') $transp4 = 'X';
			else $transp4 = '';
			if ($transp->tiempo == 'Menos de media hora') $transp5 = 'X';
			else $transp5 = '';
			if ($transp->tiempo == 'Entre media hora y una hora') $transp6 = 'X';
			else $transp6 = '';
			if ($transp->tiempo == 'Entre una a dos horas') $transp7 = 'X';
			else $transp7 = '';
			if ($transp->tiempo == 'Dos horas o mas') $transp8 = 'X';
			else $transp8 = '';
			$abandono = $this->estud->get_data_abandono($curs->idins);
			if ($abandono->abandono == 'si') $aban1 = 'X';
			else $aban1 = '';
			if ($abandono->abandono == 'no') $aban2 = 'X';
			else $aban2 = '';
			if ($abandono->razon0 == 'si') $aban3 = 'X';
			else $aban3 = '';
			if ($abandono->razon1 == 'si') $aban4 = 'X';
			else $aban4 = '';
			if ($abandono->razon2 == 'si') $aban5 = 'X';
			else $aban5 = '';
			if ($abandono->razon3 == 'si') $aban6 = 'X';
			else $aban6 = '';
			if ($abandono->razon4 == 'si') $aban7 = 'X';
			else $aban7 = '';
			if ($abandono->razon5 == 'si') $aban8 = 'X';
			else $aban8 = '';
			if ($abandono->razon6 == 'si') $aban9 = 'X';
			else $aban9 = '';
			if ($abandono->razon7 == 'si') $aban10 = 'X';
			else $aban10 = '';
			if ($abandono->razon8 == 'si') $aban11 = 'X';
			else $aban11 = '';
			if ($abandono->razon9 == 'si') $aban12 = 'X';
			else $aban12 = '';
			if ($abandono->razon10 == 'si') $aban13 = 'X';
			else $aban13 = '';
			if ($abandono->razon11 == 'si') $aban14 = 'X';
			else $aban14 = '';
			$padre = $this->estud->get_data_padre($curs->idins);
			$madre = $this->estud->get_data_madre($curs->idins);
			$tutor = $this->estud->get_data_tutor($curs->idins);
			$inscripcion = $this->estud->get_data_inscrip($curs->idins);


			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Formulario RUDE");
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('FORMULARIO DE INSCRIPCION/ACTUALIZACION'), 0, 0, 'C');
			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('REGISTRO UNICO DE ESTUDIANTES'), 0, 0, 'C');
			$this->pdf->Ln('3');
			$this->pdf->SetFont('Arial', 'B', 6);
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('Resolución Ministerial N° 1298/2018'), 0, 0, 'C');
			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('LA INFORMACION RECABADA POR EL RUDE SERÁ UTILIZADA Y EXCLUSIVAMENTE PARA'), 0, 0, 'C');
			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('FINES DE DISEÑO Y EJECUCIÓN DE POLÍTICAS PÚBLICAS EDUCATIVAS Y SOCIALES'), 0, 0, 'C');
			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->setXY(10, 27);
			$this->pdf->Cell(135, 8, utf8_decode('Importante:El formulario debe ser llenado por el padre, madre o tutor(a)'), 0, 0, 'L');
			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->setX(10);
			$this->pdf->Cell(135, 8, utf8_decode('DATOS DE LA UNIDAD EDUCATIVA'), 0, 0, 'L');
			$this->pdf->setXY(120, 30);
			$this->pdf->Cell(80, 5, utf8_decode('CÓDIGO SIE DE LA UNIDAD EDUCATIVA: ' . $unidadeduca->sie), 'TBLR', 0, 'L', '0'); //SIE
			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->setX(10);
			$this->pdf->Cell(135, 8, utf8_decode('II DATOS DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
			$this->pdf->Ln('6');
			$this->pdf->Cell(190, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(10, 39);
			$this->pdf->Cell(135, 8, utf8_decode('2.1 APELLIDO(S) Y NOMBRE(S):'), 0, 0, 'L');
			$this->pdf->Ln('4');
			$this->pdf->setX(12);
			$this->pdf->Cell(135, 8, utf8_decode('Apellido Paterno:'), 0, 0, 'L');
			$this->pdf->setXY(35, 45);
			$this->pdf->Cell(80, 4, utf8_decode($estudiante->appaterno), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('3');
			$this->pdf->setX(12);
			$this->pdf->Cell(135, 8, utf8_decode('Apellido Materno:'), 0, 0, 'L');
			$this->pdf->setXY(35, 50);
			$this->pdf->Cell(80, 4, utf8_decode($estudiante->apmaterno), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('3');
			$this->pdf->setX(12);
			$this->pdf->Cell(135, 8, utf8_decode('Nombre(s):'), 0, 0, 'L');
			$this->pdf->setXY(35, 55);
			$this->pdf->Cell(80, 4, utf8_decode($estudiante->nombres), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('3');
			$this->pdf->setX(10);
			$this->pdf->Cell(135, 8, utf8_decode('2.2. LUGAR DE NACIMIENTO:'), 0, 0, 'L');
			$this->pdf->Ln('4');
			$this->pdf->setX(12);
			$this->pdf->Cell(30, 8, utf8_decode('País:'), 0, 0, 'L');
			$this->pdf->setXY(25, 64);
			$this->pdf->Cell(40, 4, utf8_decode($nacimiento->pais), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(65, 62);
			$this->pdf->Cell(30, 8, utf8_decode('Depto:'), 0, 0, 'L');
			$this->pdf->setXY(78, 64);
			$this->pdf->Cell(37, 4, utf8_decode($nacimiento->dpto), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('4');
			$this->pdf->setX(12);
			$this->pdf->Cell(30, 8, utf8_decode('Provincia:'), 0, 0, 'L');
			$this->pdf->setXY(25, 69);
			$this->pdf->Cell(40, 4, utf8_decode($nacimiento->provincia), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(65, 67);
			$this->pdf->Cell(35, 8, utf8_decode('Localidad:'), 0, 0, 'L');
			$this->pdf->setXY(78, 69);
			$this->pdf->Cell(37, 4, utf8_decode($nacimiento->localidad), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('3');
			$this->pdf->setX(10);
			$this->pdf->Cell(50, 8, utf8_decode('2.3. CERTIFICADO DE NACIMIENTO:'), 0, 0, 'L');
			$this->pdf->setX(75);
			$this->pdf->Cell(40, 8, utf8_decode('2.4. FECHA DE NACIMIENTO:'), 0, 0, 'L');
			$this->pdf->Ln('4');
			$this->pdf->setX(12);
			$this->pdf->setXY(12, 78);
			$this->pdf->Cell(15, 4, utf8_decode($nacimiento->oficialia), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(28);
			$this->pdf->Cell(15, 4, utf8_decode($nacimiento->libro), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(44);
			$this->pdf->Cell(15, 4, utf8_decode($nacimiento->partida), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(60);
			$this->pdf->Cell(15, 4, utf8_decode($nacimiento->folio), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(78);
			$this->pdf->Cell(10, 4, utf8_decode($nacimiento->dia), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(89);
			$this->pdf->Cell(10, 4, utf8_decode($nacimiento->mes), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(100);
			$this->pdf->Cell(15, 4, utf8_decode($nacimiento->anio), 'TBLR', 0, 'C', '0');
			$this->pdf->Ln('2');
			$this->pdf->setX(10);
			$this->pdf->setX(12);
			$this->pdf->Cell(20, 8, utf8_decode('Oficialia N°'), 0, 0, 'L');
			$this->pdf->setX(30);
			$this->pdf->Cell(20, 8, utf8_decode('Libro N°'), 0, 0, 'L');
			$this->pdf->setX(45);
			$this->pdf->Cell(20, 8, utf8_decode('Partida N°'), 0, 0, 'L');
			$this->pdf->setX(63);
			$this->pdf->Cell(20, 8, utf8_decode('Folio N°'), 0, 0, 'L');
			$this->pdf->setX(80);
			$this->pdf->Cell(20, 8, utf8_decode('Dia'), 0, 0, 'L');
			$this->pdf->setX(90);
			$this->pdf->Cell(20, 8, utf8_decode('Mes'), 0, 0, 'L');
			$this->pdf->setX(103);
			$this->pdf->Cell(20, 8, utf8_decode('Año'), 0, 0, 'L');
			$this->pdf->Ln('4');
			$this->pdf->setX(10);
			$this->pdf->Cell(50, 8, utf8_decode('2.5. DOCUMENTO DE INDENTIFICACION:'), 0, 0, 'L');
			$this->pdf->Ln('6');
			$this->pdf->setX(12);
			$this->pdf->Cell(30, 4, utf8_decode($estudiante->ci), 'TBLR', 0, 'C', '0');
			$this->pdf->setX(50);
			$this->pdf->Cell(10, 4, utf8_decode($estudiante->complemento), 'TBLR', 0, 'L', '0');
			$this->pdf->setX(65);
			$this->pdf->Cell(10, 4, utf8_decode($estudiante->extension), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('2');
			$this->pdf->setX(16);
			$this->pdf->Cell(30, 8, utf8_decode('Carnet de Identidad'), 0, 0, 'L');
			$this->pdf->setX(50);
			$this->pdf->Cell(20, 8, utf8_decode('Compleo'), 0, 0, 'L');
			$this->pdf->setX(66);
			$this->pdf->Cell(20, 8, utf8_decode('Exten'), 0, 0, 'L');
			$this->pdf->Ln('4');
			$this->pdf->setXY(120, 39);
			$this->pdf->Cell(50, 8, utf8_decode('2.6. CODIGO RUDE (Código automático generado por el Sistema)'), 0, 0, 'L');
			$this->pdf->Ln('6');
			$this->pdf->setX(120);
			$this->pdf->Cell(75, 4, utf8_decode($estudiante->rude), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('3');
			$this->pdf->setX(120);
			$this->pdf->Cell(50, 8, utf8_decode('2.7. SEXO:'), 0, 0, 'L');
			$this->pdf->setX(132);
			$this->pdf->Cell(10, 8, utf8_decode('Masculino'), 0, 0, 'L');
			$this->pdf->setXY(144, 50);
			$this->pdf->Cell(4, 4, utf8_decode($genero1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(132, 53);
			$this->pdf->Cell(10, 8, utf8_decode('Femenino'), 0, 0, 'L');
			$this->pdf->setXY(144, 55);
			$this->pdf->Cell(4, 4, utf8_decode($genero2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(150, 48);
			$this->pdf->Cell(50, 8, utf8_decode('2.8. ¿ESTUDIANTE TIENE DISCAPACIDAD?'), 0, 0, 'L');
			$this->pdf->setXY(158, 53);
			$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'L');
			$this->pdf->setXY(162, 55);
			if ($discap->discapacidad) {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discap1),'TBLR',0,'L','0');
			$this->pdf->setXY(173, 53);
			$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'L');
			$this->pdf->setXY(178, 55);
			if ($discap->discapacidad) {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discap2),'TBLR',0,'L','0');
			$this->pdf->setXY(120, 59);
			$this->pdf->Cell(50, 8, utf8_decode('2.9. N° DE REGISTRO DE DISCAPACIDAD O IBC:'), 0, 0, 'L');
			$this->pdf->setXY(171, 61);
			$this->pdf->Cell(24, 4, utf8_decode($discap->ibc), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(120, 65);
			$this->pdf->Cell(50, 8, utf8_decode('2.10. TIPO DE DISCAPACIDAD:'), 0, 0, 'L');
			$this->pdf->setXY(158, 65);
			$this->pdf->Cell(50, 8, utf8_decode('2.11. GRADO DE DISCAPACIDAD:'), 0, 0, 'L');
			$this->pdf->setXY(129, 70);
			$this->pdf->Cell(10, 8, utf8_decode('Psíquica'), 0, 0, 'L');
			$this->pdf->setXY(140, 72);
			if ($discap->tipo == "Psíquica") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt1),'TBLR',0,'L','0');
			$this->pdf->setXY(152, 70);
			$this->pdf->Cell(10, 8, utf8_decode('Auditiva'), 0, 0, 'L');
			$this->pdf->setXY(162, 72);
			if ($discap->tipo == "Auditiva") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt5),'TBLR',0,'L','0');
			$this->pdf->setXY(174, 70);
			$this->pdf->Cell(10, 8, utf8_decode('Leve'), 0, 0, 'R');
			$this->pdf->setXY(184, 72);
			if ($discap->grado == "Leve") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapg1),'TBLR',0,'L','0');
			$this->pdf->setXY(129, 75);
			$this->pdf->Cell(10, 8, utf8_decode('Autismo'), 0, 0, 'L');
			$this->pdf->setXY(140, 77);
			if ($discap->tipo == "Autismo") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt2),'TBLR',0,'L','0');
			$this->pdf->setXY(152, 75);
			$this->pdf->Cell(10, 8, utf8_decode('Física-Motora'), 0, 0, 'R');
			$this->pdf->setXY(162, 77);
			if ($discap->tipo == "Física Motora") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt6),'TBLR',0,'L','0');
			$this->pdf->setXY(174, 75);
			$this->pdf->Cell(10, 8, utf8_decode('Moderado'), 0, 0, 'R');
			$this->pdf->setXY(184, 77);
			if ($discap->grado == "Moderado") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapg2),'TBLR',0,'L','0');

			$this->pdf->setXY(129, 80);
			$this->pdf->Cell(10, 8, utf8_decode('Sindrome de Down'), 0, 0, 'R');
			$this->pdf->setXY(140, 82);
			if ($discap->tipo == "Sindrome de Down") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt3),'TBLR',0,'L','0');
			$this->pdf->setXY(152, 80);
			$this->pdf->Cell(10, 8, utf8_decode('Sordocequera'), 0, 0, 'R');
			$this->pdf->setXY(162, 82);
			if ($discap->tipo == "Sordocequera") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt4),'TBLR',0,'L','0');
			$this->pdf->setXY(174, 80);
			$this->pdf->Cell(10, 8, utf8_decode('Grave'), 0, 0, 'R');
			$this->pdf->setXY(184, 82);
			if ($discap->grado == "Grave") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapg3),'TBLR',0,'L','0');

			$this->pdf->setXY(129, 85);
			$this->pdf->Cell(10, 8, utf8_decode('Intelectual'), 0, 0, 'R');
			$this->pdf->setXY(140, 87);
			if ($discap->tipo == "Intelectual") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt4),'TBLR',0,'L','0');
			$this->pdf->setXY(152, 85);
			$this->pdf->Cell(10, 8, utf8_decode('Multiple'), 0, 0, 'R');
			$this->pdf->setXY(162, 87);
			if ($discap->tipo == "Multiple") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt8),'TBLR',0,'L','0');
			$this->pdf->setXY(174, 85);
			$this->pdf->Cell(10, 8, utf8_decode('Muy Grave'), 0, 0, 'R');
			$this->pdf->setXY(184, 87);
			if ($discap->grado == "Muy Grave") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapg4),'TBLR',0,'L','0');

			$this->pdf->setXY(152, 90);
			$this->pdf->Cell(10, 8, utf8_decode('Visual'), 0, 0, 'R');
			$this->pdf->setXY(162, 92);
			if ($discap->tipo == "Visual") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapt9),'TBLR',0,'L','0');
			$this->pdf->setXY(174, 90);
			$this->pdf->Cell(10, 8, utf8_decode('Ceguera total'), 0, 0, 'R');
			$this->pdf->setXY(184, 92);
			if ($discap->tipo == "Ceguera total") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapg5),'TBLR',0,'L','0');

			$this->pdf->setXY(174, 95);
			$this->pdf->Cell(10, 8, utf8_decode('Baja Visión'), 0, 0, 'R');
			$this->pdf->setXY(184, 97);
			if ($discap->grado == "Baja Visión") {
				$this->pdf->Cell(4, 4, utf8_decode("X"), 'TBLR', 0, 'L', '0');
			} else {
				$this->pdf->Cell(4, 4, utf8_decode(""), 'TBLR', 0, 'L', '0');
			}
			// $this->pdf->Cell(4,4,utf8_decode($discapg6),'TBLR',0,'L','0');

			$this->pdf->Ln('3');
			$this->pdf->Cell(30);
			$this->pdf->setX(10);
			$this->pdf->Cell(135, 8, utf8_decode('III DIRECCIÓN ACTUAL DE LA O EL ESTUDIANTE (Información para uso exclusivo de la Unidad educativa)'), 0, 0, 'L');
			$this->pdf->Ln('6');
			$this->pdf->Cell(190, 18, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(10, 106);
			$this->pdf->Cell(25, 8, utf8_decode('Departamento:'), 0, 0, 'R');
			$this->pdf->setXY(36, 107);
			$this->pdf->Cell(40, 4, utf8_decode($local->departamento), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(70, 106);
			$this->pdf->Cell(25, 8, utf8_decode('Provincia:'), 0, 0, 'R');
			$this->pdf->setXY(95, 107);
			$this->pdf->Cell(40, 4, utf8_decode($local->provincia), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(130, 106);
			$this->pdf->Cell(25, 8, utf8_decode('Municipio:'), 0, 0, 'R');
			$this->pdf->setXY(155, 107);
			$this->pdf->Cell(40, 4, utf8_decode($local->municipio), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 111);
			$this->pdf->Cell(25, 8, utf8_decode('Localidad:'), 0, 0, 'R');
			$this->pdf->setXY(36, 112);
			$this->pdf->Cell(40, 4, utf8_decode($local->localidad), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(70, 111);
			$this->pdf->Cell(25, 8, utf8_decode('Zona/Villa:'), 0, 0, 'R');
			$this->pdf->setXY(95, 112);
			$this->pdf->Cell(40, 4, utf8_decode($local->zona), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(130, 111);
			$this->pdf->Cell(25, 8, utf8_decode('Av./Calle:'), 0, 0, 'R');
			$this->pdf->setXY(155, 112);
			$this->pdf->Cell(40, 4, utf8_decode($local->calle), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 116);
			$this->pdf->Cell(25, 8, utf8_decode('N° Vivienda:'), 0, 0, 'R');
			$this->pdf->setXY(36, 117);
			$this->pdf->Cell(40, 4, utf8_decode($local->num), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(70, 116);
			$this->pdf->Cell(25, 8, utf8_decode('Télefono Fijo:'), 0, 0, 'R');
			$this->pdf->setXY(95, 117);
			$this->pdf->Cell(40, 4, utf8_decode($local->fono), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(130, 116);
			$this->pdf->Cell(25, 8, utf8_decode('Celular:'), 0, 0, 'R');
			$this->pdf->setXY(155, 117);
			$this->pdf->Cell(40, 4, utf8_decode($local->celular), 'TBLR', 0, 'L', '0');
			$this->pdf->Ln('5');
			$this->pdf->Cell(30);
			$this->pdf->setX(10);
			$this->pdf->Cell(135, 8, utf8_decode('IV ASPECTOS SOCIOECONÓMICOS DE LA O EL ESTUDIANTE'), 0, 0, 'L');
			$this->pdf->setXY(10, 125);
			$this->pdf->Cell(50, 8, utf8_decode('4.1 IDIOMA Y PERTENENCIA CULTURAL DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
			$this->pdf->Ln('6');
			$this->pdf->Cell(35, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(10, 130);
			$this->pdf->Cell(50, 8, utf8_decode('4.1.1 ¿Cuál es el idioma natal?:'), 0, 0, 'L');
			$this->pdf->setXY(13, 137);
			$this->pdf->Cell(30, 4, utf8_decode($idioma->idiomanatal), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 140);
			$this->pdf->Cell(50, 8, utf8_decode('4.1.2 ¿Qué idiomas habla?:'), 0, 0, 'L');
			$this->pdf->setXY(13, 147);
			$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(13, 152);
			$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(13, 157);
			$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(46, 131);
			$this->pdf->Cell(98, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(48, 130);
			$this->pdf->Cell(50, 8, utf8_decode('4.1.3 ¿Pertenece a una nación, pueblo indígena originario?:'), 0, 0, 'L');
			$this->pdf->setXY(51, 134);
			$this->pdf->Cell(15, 8, utf8_decode('Ninguno'), 0, 0, 'R');
			$this->pdf->setXY(66, 136);
			$this->pdf->Cell(4, 4, utf8_decode($nacion1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 134);
			$this->pdf->Cell(15, 8, utf8_decode('Chimán'), 0, 0, 'R');
			$this->pdf->setXY(93, 136);
			$this->pdf->Cell(4, 4, utf8_decode($nacion10), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 134);
			$this->pdf->Cell(15, 8, utf8_decode('Mojeño-Agraciano'), 0, 0, 'R');
			$this->pdf->setXY(119, 136);
			$this->pdf->Cell(4, 4, utf8_decode($nacion20), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 134);
			$this->pdf->Cell(15, 8, utf8_decode('Tapiete'), 0, 0, 'R');
			$this->pdf->setXY(138, 136);
			$this->pdf->Cell(4, 4, utf8_decode($nacion30), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(51, 139);
			$this->pdf->Cell(15, 8, utf8_decode('Afroboliviano'), 0, 0, 'R');
			$this->pdf->setXY(66, 141);
			$this->pdf->Cell(4, 4, utf8_decode($nacion38), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 139);
			$this->pdf->Cell(15, 8, utf8_decode('Ese Ejja'), 0, 0, 'R');
			$this->pdf->setXY(93, 141);
			$this->pdf->Cell(4, 4, utf8_decode($nacion11), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 139);
			$this->pdf->Cell(15, 8, utf8_decode('Mojeño-Trinitario'), 0, 0, 'R');
			$this->pdf->setXY(119, 141);
			$this->pdf->Cell(4, 4, utf8_decode($nacion21), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 139);
			$this->pdf->Cell(15, 8, utf8_decode('Toromona'), 0, 0, 'R');
			$this->pdf->setXY(138, 141);
			$this->pdf->Cell(4, 4, utf8_decode($nacion31), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(51, 144);
			$this->pdf->Cell(15, 8, utf8_decode('Araona'), 0, 0, 'R');
			$this->pdf->setXY(66, 146);
			$this->pdf->Cell(4, 4, utf8_decode($nacion2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 144);
			$this->pdf->Cell(15, 8, utf8_decode('Guaraní'), 0, 0, 'R');
			$this->pdf->setXY(93, 146);
			$this->pdf->Cell(4, 4, utf8_decode($nacion12), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 144);
			$this->pdf->Cell(15, 8, utf8_decode('Moré'), 0, 0, 'R');
			$this->pdf->setXY(119, 146);
			$this->pdf->Cell(4, 4, utf8_decode($nacion22), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 144);
			$this->pdf->Cell(15, 8, utf8_decode('Uru-Chipaya'), 0, 0, 'R');
			$this->pdf->setXY(138, 146);
			$this->pdf->Cell(4, 4, utf8_decode($nacion32), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 149);
			$this->pdf->Cell(15, 8, utf8_decode('Aymara'), 0, 0, 'R');
			$this->pdf->setXY(66, 151);
			$this->pdf->Cell(4, 4, utf8_decode($nacion3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 149);
			$this->pdf->Cell(15, 8, utf8_decode('Guarasuawe'), 0, 0, 'R');
			$this->pdf->setXY(93, 151);
			$this->pdf->Cell(4, 4, utf8_decode($nacion13), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 149);
			$this->pdf->Cell(15, 8, utf8_decode('Mosetén'), 0, 0, 'R');
			$this->pdf->setXY(119, 151);
			$this->pdf->Cell(4, 4, utf8_decode($nacion23), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 149);
			$this->pdf->Cell(15, 8, utf8_decode('Weebhayek'), 0, 0, 'R');
			$this->pdf->setXY(138, 151);
			$this->pdf->Cell(4, 4, utf8_decode($nacion33), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 154);
			$this->pdf->Cell(15, 8, utf8_decode('Baure'), 0, 0, 'R');
			$this->pdf->setXY(66, 156);
			$this->pdf->Cell(4, 4, utf8_decode($nacion4), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 154);
			$this->pdf->Cell(15, 8, utf8_decode('Guarayo'), 0, 0, 'R');
			$this->pdf->setXY(93, 156);
			$this->pdf->Cell(4, 4, utf8_decode($nacion14), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 154);
			$this->pdf->Cell(15, 8, utf8_decode('Movima'), 0, 0, 'R');
			$this->pdf->setXY(119, 156);
			$this->pdf->Cell(4, 4, utf8_decode($nacion24), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 154);
			$this->pdf->Cell(15, 8, utf8_decode('Yaminawa'), 0, 0, 'R');
			$this->pdf->setXY(138, 156);
			$this->pdf->Cell(4, 4, utf8_decode($nacion34), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 159);
			$this->pdf->Cell(15, 8, utf8_decode('Bésiro'), 0, 0, 'R');
			$this->pdf->setXY(66, 161);
			$this->pdf->Cell(4, 4, utf8_decode($nacion5), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 159);
			$this->pdf->Cell(15, 8, utf8_decode('Itonoma'), 0, 0, 'R');
			$this->pdf->setXY(93, 161);
			$this->pdf->Cell(4, 4, utf8_decode($nacion15), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 159);
			$this->pdf->Cell(15, 8, utf8_decode('Tacaware'), 0, 0, 'R');
			$this->pdf->setXY(119, 161);
			$this->pdf->Cell(4, 4, utf8_decode($nacion25), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 159);
			$this->pdf->Cell(15, 8, utf8_decode('Yuki'), 0, 0, 'R');
			$this->pdf->setXY(138, 161);
			$this->pdf->Cell(4, 4, utf8_decode($nacion35), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 164);
			$this->pdf->Cell(15, 8, utf8_decode('Canichana'), 0, 0, 'R');
			$this->pdf->setXY(66, 166);
			$this->pdf->Cell(4, 4, utf8_decode($nacion6), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 164);
			$this->pdf->Cell(15, 8, utf8_decode('Leco'), 0, 0, 'R');
			$this->pdf->setXY(93, 166);
			$this->pdf->Cell(4, 4, utf8_decode($nacion16), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 164);
			$this->pdf->Cell(15, 8, utf8_decode('Puquina'), 0, 0, 'R');
			$this->pdf->setXY(119, 166);
			$this->pdf->Cell(4, 4, utf8_decode($nacion26), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 164);
			$this->pdf->Cell(15, 8, utf8_decode('Yuracaré'), 0, 0, 'R');
			$this->pdf->setXY(138, 166);
			$this->pdf->Cell(4, 4, utf8_decode($nacion36), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 169);
			$this->pdf->Cell(15, 8, utf8_decode('Cavineño'), 0, 0, 'R');
			$this->pdf->setXY(66, 171);
			$this->pdf->Cell(4, 4, utf8_decode($nacion7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 169);
			$this->pdf->Cell(15, 8, utf8_decode('Machajuyai-Kallawaya'), 0, 0, 'R');
			$this->pdf->setXY(93, 171);
			$this->pdf->Cell(4, 4, utf8_decode($nacion17), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 169);
			$this->pdf->Cell(15, 8, utf8_decode('Quechua'), 0, 0, 'R');
			$this->pdf->setXY(119, 171);
			$this->pdf->Cell(4, 4, utf8_decode($nacion27), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(123, 169);
			$this->pdf->Cell(15, 8, utf8_decode('Zamuco'), 0, 0, 'R');
			$this->pdf->setXY(138, 171);
			$this->pdf->Cell(4, 4, utf8_decode($nacion37), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 174);
			$this->pdf->Cell(15, 8, utf8_decode('Cayubaba'), 0, 0, 'R');
			$this->pdf->setXY(66, 176);
			$this->pdf->Cell(4, 4, utf8_decode($nacion8), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 174);
			$this->pdf->Cell(15, 8, utf8_decode('Machineri'), 0, 0, 'R');
			$this->pdf->setXY(93, 176);
			$this->pdf->Cell(4, 4, utf8_decode($nacion18), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 174);
			$this->pdf->Cell(15, 8, utf8_decode('Sironó'), 0, 0, 'R');
			$this->pdf->setXY(119, 176);
			$this->pdf->Cell(4, 4, utf8_decode($nacion28), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(51, 179);
			$this->pdf->Cell(15, 8, utf8_decode('Chacobo'), 0, 0, 'R');
			$this->pdf->setXY(66, 181);
			$this->pdf->Cell(4, 4, utf8_decode($nacion9), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(78, 179);
			$this->pdf->Cell(15, 8, utf8_decode('Maropa'), 0, 0, 'R');
			$this->pdf->setXY(93, 181);
			$this->pdf->Cell(4, 4, utf8_decode($nacion19), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(104, 179);
			$this->pdf->Cell(15, 8, utf8_decode('Tacana'), 0, 0, 'R');
			$this->pdf->setXY(119, 181);
			$this->pdf->Cell(4, 4, utf8_decode($nacion29), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(145, 131);
			$this->pdf->Cell(56, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(146, 125);
			$this->pdf->Cell(50, 8, utf8_decode('4.2 SALUD DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
			$this->pdf->setXY(146, 130);
			$this->pdf->Cell(50, 8, utf8_decode('4.2.1 ¿Existe algún Centro de Salud/Posta?:'), 0, 0, 'L');
			$this->pdf->setXY(147, 135);
			$this->pdf->Cell(15, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(162, 137);
			$this->pdf->Cell(4, 4, utf8_decode($salud1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(158, 135);
			$this->pdf->Cell(15, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(173, 137);
			$this->pdf->Cell(4, 4, utf8_decode($salud2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(146, 140);
			$this->pdf->Cell(50, 8, utf8_decode('4.2.2 ¿E que centro de Salud fue atendido?:'), 0, 0, 'L');
			$this->pdf->setXY(162, 144);
			$this->pdf->Cell(15, 8, utf8_decode('1. Caja o seguro de salud'), 0, 0, 'R');
			$this->pdf->setXY(178, 146);
			$this->pdf->Cell(4, 4, utf8_decode($salud3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(162, 149);
			$this->pdf->Cell(15, 8, utf8_decode('2. Centros de salud públicos'), 0, 0, 'R');
			$this->pdf->setXY(178, 151);
			$this->pdf->Cell(4, 4, utf8_decode($salud4), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(162, 154);
			$this->pdf->Cell(15, 8, utf8_decode('3. Centros de salud privados'), 0, 0, 'R');
			$this->pdf->setXY(178, 156);
			$this->pdf->Cell(4, 4, utf8_decode($salud5), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(162, 159);
			$this->pdf->Cell(15, 8, utf8_decode('4. En su vivienda'), 0, 0, 'R');
			$this->pdf->setXY(178, 161);
			$this->pdf->Cell(4, 4, utf8_decode($salud6), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(162, 163);
			$this->pdf->Cell(15, 8, utf8_decode('5. Medicina Tradicional'), 0, 0, 'R');
			$this->pdf->setXY(178, 166);
			$this->pdf->Cell(4, 4, utf8_decode($salud7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(162, 168);
			$this->pdf->Cell(15, 8, utf8_decode('6. Farmacia sin receta'), 0, 0, 'R');
			$this->pdf->setXY(178, 171);
			$this->pdf->Cell(4, 4, utf8_decode($salud8), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(146, 174);
			$this->pdf->Cell(50, 8, utf8_decode('4.2.3 ¿Cuantas veces fue al centro de salud?:'), 0, 0, 'L');
			$this->pdf->setXY(143, 178);
			$this->pdf->Cell(15, 8, utf8_decode('1 a 2 veces'), 0, 0, 'R');
			$this->pdf->setXY(158, 180);
			$this->pdf->Cell(4, 4, utf8_decode($salud9), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(160, 178);
			$this->pdf->Cell(15, 8, utf8_decode('3 a 5 veces'), 0, 0, 'R');
			$this->pdf->setXY(175, 180);
			$this->pdf->Cell(4, 4, utf8_decode($salud10), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(176, 178);
			$this->pdf->Cell(10, 8, utf8_decode('6 ó +'), 0, 0, 'R');
			$this->pdf->setXY(186, 180);
			$this->pdf->Cell(4, 4, utf8_decode($salud11), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(190, 178);
			$this->pdf->Cell(6, 8, utf8_decode('ning'), 0, 0, 'R');
			$this->pdf->setXY(196, 180);
			$this->pdf->Cell(4, 4, utf8_decode($salud12), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(146, 183);
			$this->pdf->Cell(50, 8, utf8_decode('4.2.3 ¿Tiene seguro de salud?:'), 0, 0, 'L');
			$this->pdf->setXY(150, 187);
			$this->pdf->Cell(15, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(165, 189);
			$this->pdf->Cell(4, 4, utf8_decode($salud13), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(161, 187);
			$this->pdf->Cell(15, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(176, 189);
			$this->pdf->Cell(4, 4, utf8_decode($salud14), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 192);
			$this->pdf->Cell(50, 8, utf8_decode('4.3 ACCESO DE LA O EL ESTUDIANTE A SEVICIOS BASICOS:'), 0, 0, 'L');
			$this->pdf->setXY(10, 198);
			$this->pdf->Cell(191, 33, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO

			$this->pdf->setXY(12, 197);
			$this->pdf->Cell(50, 8, utf8_decode('4.3.1 ¿Tiene acceso a agua por cañeria de red?:'), 0, 0, 'L');
			$this->pdf->setXY(12, 202);
			$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(22, 204);
			$this->pdf->Cell(4, 4, utf8_decode($serv1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(24, 202);
			$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(34, 204);
			$this->pdf->Cell(4, 4, utf8_decode($serv2), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(62, 197);
			$this->pdf->Cell(50, 8, utf8_decode('4.3.4 ¿Una energía eléctrica en su vivienda?:'), 0, 0, 'L');
			$this->pdf->setXY(62, 202);
			$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(72, 204);
			$this->pdf->Cell(4, 4, utf8_decode($serv7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(74, 202);
			$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(84, 204);
			$this->pdf->Cell(4, 4, utf8_decode($serv8), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(120, 197);
			$this->pdf->Cell(50, 8, utf8_decode('4.3.6 La vivienda que ocupa el hogar es:'), 0, 0, 'L');
			$this->pdf->setXY(132, 202);
			$this->pdf->Cell(10, 8, utf8_decode('Propia'), 0, 0, 'R');
			$this->pdf->setXY(142, 204);
			$this->pdf->Cell(4, 4, utf8_decode($serv11), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(179, 202);
			$this->pdf->Cell(10, 8, utf8_decode('Cedida por Servicios'), 0, 0, 'R');
			$this->pdf->setXY(189, 204);
			$this->pdf->Cell(4, 4, utf8_decode($serv14), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(12, 207);
			$this->pdf->Cell(50, 8, utf8_decode('4.3.2 ¿Tiene Baño su vivienda?:'), 0, 0, 'L');
			$this->pdf->setXY(12, 212);
			$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(22, 214);
			$this->pdf->Cell(4, 4, utf8_decode($serv3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(24, 212);
			$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(34, 214);
			$this->pdf->Cell(4, 4, utf8_decode($serv4), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(62, 207);
			$this->pdf->Cell(50, 8, utf8_decode('4.3.5 ¿Cuenta con servicio de recojo de Basura?:'), 0, 0, 'L');
			$this->pdf->setXY(62, 212);
			$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(72, 214);
			$this->pdf->Cell(4, 4, utf8_decode($serv9), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(74, 212);
			$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(84, 214);
			$this->pdf->Cell(4, 4, utf8_decode($serv10), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(132, 207);
			$this->pdf->Cell(10, 8, utf8_decode('Alquilada'), 0, 0, 'R');
			$this->pdf->setXY(142, 209);
			$this->pdf->Cell(4, 4, utf8_decode($serv12), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(179, 207);
			$this->pdf->Cell(10, 8, utf8_decode('Prestada por parientes o amigos'), 0, 0, 'R');
			$this->pdf->setXY(189, 209);
			$this->pdf->Cell(4, 4, utf8_decode($serv15), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(12, 217);
			$this->pdf->Cell(50, 8, utf8_decode('4.3.2 ¿Tiene red de alcantarillado?:'), 0, 0, 'L');
			$this->pdf->setXY(12, 222);
			$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
			$this->pdf->setXY(22, 224);
			$this->pdf->Cell(4, 4, utf8_decode($serv5), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(24, 222);
			$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
			$this->pdf->setXY(34, 224);
			$this->pdf->Cell(4, 4, utf8_decode($serv6), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(132, 212);
			$this->pdf->Cell(10, 8, utf8_decode('Anticrético'), 0, 0, 'R');
			$this->pdf->setXY(142, 214);
			$this->pdf->Cell(4, 4, utf8_decode($serv13), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(179, 212);
			$this->pdf->Cell(10, 8, utf8_decode('Contrato mixto (alquile o anticrético)'), 0, 0, 'R');
			$this->pdf->setXY(189, 214);
			$this->pdf->Cell(4, 4, utf8_decode($serv16), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 229);
			$this->pdf->Cell(50, 8, utf8_decode('4.4 ACCESO A INTERNET DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
			$this->pdf->setXY(10, 235);
			$this->pdf->Cell(191, 16, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO

			$this->pdf->setXY(12, 234);
			$this->pdf->Cell(50, 8, utf8_decode('4.4.1 El estudiante accede a internet en:'), 0, 0, 'L');
			$this->pdf->setXY(120, 234);
			$this->pdf->Cell(50, 8, utf8_decode('4.4.2 ¿Con que frecuencia usa Internet?'), 0, 0, 'L');
			$this->pdf->setXY(22, 238);
			$this->pdf->Cell(10, 8, utf8_decode('Su vivienda'), 0, 0, 'R');
			$this->pdf->setXY(33, 240);
			$this->pdf->Cell(4, 4, utf8_decode($net1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(52, 238);
			$this->pdf->Cell(10, 8, utf8_decode('Lugares Públicos'), 0, 0, 'R');
			$this->pdf->setXY(63, 240);
			$this->pdf->Cell(4, 4, utf8_decode($net3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(82, 238);
			$this->pdf->Cell(10, 8, utf8_decode('No accede a Internet'), 0, 0, 'R');
			$this->pdf->setXY(93, 240);
			$this->pdf->Cell(4, 4, utf8_decode($net5), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(135, 238);
			$this->pdf->Cell(10, 8, utf8_decode('Diariamente'), 0, 0, 'R');
			$this->pdf->setXY(145, 240);
			$this->pdf->Cell(4, 4, utf8_decode($net6), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(177, 238);
			$this->pdf->Cell(10, 8, utf8_decode('Mas de una vez a la semana'), 0, 0, 'R');
			$this->pdf->setXY(187, 240);
			$this->pdf->Cell(4, 4, utf8_decode($net8), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(22, 243);
			$this->pdf->Cell(10, 8, utf8_decode('La unidad Educativa'), 0, 0, 'R');
			$this->pdf->setXY(33, 245);
			$this->pdf->Cell(4, 4, utf8_decode($net2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(52, 243);
			$this->pdf->Cell(10, 8, utf8_decode('Teléfono Célular'), 0, 0, 'R');
			$this->pdf->setXY(63, 245);
			$this->pdf->Cell(4, 4, utf8_decode($net4), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(135, 243);
			$this->pdf->Cell(10, 8, utf8_decode('Una vez a la semana'), 0, 0, 'R');
			$this->pdf->setXY(145, 245);
			$this->pdf->Cell(4, 4, utf8_decode($net7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(177, 243);
			$this->pdf->Cell(10, 8, utf8_decode('Una vez al mes'), 0, 0, 'R');
			$this->pdf->setXY(187, 245);
			$this->pdf->Cell(4, 4, utf8_decode($net9), 'TBLR', 0, 'L', '0');
			$this->pdf->AddPage();
			$this->pdf->setXY(10, 8);
			$this->pdf->Cell(50, 8, utf8_decode('4.5 ACTIVIDAD LABORAL DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
			$this->pdf->setXY(10, 14);
			$this->pdf->Cell(191, 33, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(12, 14);
			$this->pdf->Cell(50, 8, utf8_decode('4.5.1 ¿En la pasada gestion ¿El estudiante trabajo?'), 0, 0, 'L');
			$this->pdf->setXY(15, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
			$this->pdf->setXY(25, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(25, 18);
			$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
			$this->pdf->setXY(35, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work2), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 22);
			$this->pdf->Cell(10, 8, utf8_decode('Ene'), 0, 0, 'R');
			$this->pdf->setXY(20, 24);
			$this->pdf->Cell(4, 4, utf8_decode($work3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(20, 22);
			$this->pdf->Cell(10, 8, utf8_decode('Feb'), 0, 0, 'R');
			$this->pdf->setXY(30, 24);
			$this->pdf->Cell(4, 4, utf8_decode($work4), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(30, 22);
			$this->pdf->Cell(10, 8, utf8_decode('Mar'), 0, 0, 'R');
			$this->pdf->setXY(40, 24);
			$this->pdf->Cell(4, 4, utf8_decode($work5), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(40, 22);
			$this->pdf->Cell(10, 8, utf8_decode('Abr'), 0, 0, 'R');
			$this->pdf->setXY(50, 24);
			$this->pdf->Cell(4, 4, utf8_decode($work6), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 27);
			$this->pdf->Cell(10, 8, utf8_decode('May'), 0, 0, 'R');
			$this->pdf->setXY(20, 29);
			$this->pdf->Cell(4, 4, utf8_decode($work7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(20, 27);
			$this->pdf->Cell(10, 8, utf8_decode('Jun'), 0, 0, 'R');
			$this->pdf->setXY(30, 29);
			$this->pdf->Cell(4, 4, utf8_decode($work8), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(30, 27);
			$this->pdf->Cell(10, 8, utf8_decode('Jul'), 0, 0, 'R');
			$this->pdf->setXY(40, 29);
			$this->pdf->Cell(4, 4, utf8_decode($work9), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(40, 27);
			$this->pdf->Cell(10, 8, utf8_decode('Ago'), 0, 0, 'R');
			$this->pdf->setXY(50, 29);
			$this->pdf->Cell(4, 4, utf8_decode($work10), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 32);
			$this->pdf->Cell(10, 8, utf8_decode('Sep'), 0, 0, 'R');
			$this->pdf->setXY(20, 34);
			$this->pdf->Cell(4, 4, utf8_decode($work11), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(20, 32);
			$this->pdf->Cell(10, 8, utf8_decode('Oct'), 0, 0, 'R');
			$this->pdf->setXY(30, 34);
			$this->pdf->Cell(4, 4, utf8_decode($work12), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(30, 32);
			$this->pdf->Cell(10, 8, utf8_decode('Nov'), 0, 0, 'R');
			$this->pdf->setXY(40, 34);
			$this->pdf->Cell(4, 4, utf8_decode($work13), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(40, 32);
			$this->pdf->Cell(10, 8, utf8_decode('Dic'), 0, 0, 'R');
			$this->pdf->setXY(50, 34);
			$this->pdf->Cell(4, 4, utf8_decode($work14), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(72, 14);
			$this->pdf->Cell(50, 8, utf8_decode('4.5.2 ¿En la pasada gestion ¿El estudiante trabajo?'), 0, 0, 'L');
			$this->pdf->setXY(75, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Agricultura'), 0, 0, 'R');
			$this->pdf->setXY(85, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work15), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(105, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Vendedor dependiente'), 0, 0, 'R');
			$this->pdf->setXY(115, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work19), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(140, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Trabajo del hogar o niñero'), 0, 0, 'R');
			$this->pdf->setXY(150, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work23), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 23);
			$this->pdf->Cell(10, 8, utf8_decode('Ganaderia o pesca'), 0, 0, 'R');
			$this->pdf->setXY(85, 25);
			$this->pdf->Cell(4, 4, utf8_decode($work16), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(105, 23);
			$this->pdf->Cell(10, 8, utf8_decode('Vendedor cuenta propia'), 0, 0, 'R');
			$this->pdf->setXY(115, 25);
			$this->pdf->Cell(4, 4, utf8_decode($work20), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(140, 23);
			$this->pdf->Cell(10, 8, utf8_decode('Ayudante familiar'), 0, 0, 'R');
			$this->pdf->setXY(150, 25);
			$this->pdf->Cell(4, 4, utf8_decode($work24), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 28);
			$this->pdf->Cell(10, 8, utf8_decode('Minería'), 0, 0, 'R');
			$this->pdf->setXY(85, 30);
			$this->pdf->Cell(4, 4, utf8_decode($work18), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(105, 28);
			$this->pdf->Cell(10, 8, utf8_decode('Transporte o mecánica'), 0, 0, 'R');
			$this->pdf->setXY(115, 30);
			$this->pdf->Cell(4, 4, utf8_decode($work21), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(140, 28);
			$this->pdf->Cell(10, 8, utf8_decode('Ayudante en el Hogar'), 0, 0, 'R');
			$this->pdf->setXY(150, 30);
			$this->pdf->Cell(4, 4, utf8_decode($work23), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 33);
			$this->pdf->Cell(10, 8, utf8_decode('Construcción'), 0, 0, 'R');
			$this->pdf->setXY(85, 35);
			$this->pdf->Cell(4, 4, utf8_decode($work17), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(105, 33);
			$this->pdf->Cell(10, 8, utf8_decode('Lustra Botas'), 0, 0, 'R');
			$this->pdf->setXY(115, 35);
			$this->pdf->Cell(4, 4, utf8_decode($work22), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(140, 33);
			$this->pdf->Cell(10, 8, utf8_decode('Otro trabajo'), 0, 0, 'R');
			$this->pdf->setXY(150, 35);
			$this->pdf->Cell(4, 4, utf8_decode($work26), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(125, 38);
			$this->pdf->Cell(10, 8, utf8_decode('(Especifique)'), 0, 0, 'R');
			$this->pdf->setXY(135, 40);
			$this->pdf->Cell(19, 4, utf8_decode($trabajo->otrotrabajo), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(155, 14);
			$this->pdf->Cell(50, 8, utf8_decode('4.5.3 ¿En qué turno trabajo el estudiante?'), 0, 0, 'L');
			$this->pdf->setXY(160, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Mañana'), 0, 0, 'R');
			$this->pdf->setXY(170, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work27), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(172, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Tarde'), 0, 0, 'R');
			$this->pdf->setXY(182, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work28), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 18);
			$this->pdf->Cell(10, 8, utf8_decode('Noche'), 0, 0, 'R');
			$this->pdf->setXY(195, 20);
			$this->pdf->Cell(4, 4, utf8_decode($work29), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(155, 22);
			$this->pdf->Cell(50, 8, utf8_decode('4.5.4 ¿Con que frecuencia trabajo?'), 0, 0, 'L');
			$this->pdf->setXY(160, 26);
			$this->pdf->Cell(10, 8, utf8_decode('Todos dias'), 0, 0, 'R');
			$this->pdf->setXY(170, 28);
			$this->pdf->Cell(4, 4, utf8_decode($work30), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(172, 26);
			$this->pdf->Cell(10, 8, utf8_decode('FinSe'), 0, 0, 'R');
			$this->pdf->setXY(182, 28);
			$this->pdf->Cell(4, 4, utf8_decode($work31), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 26);
			$this->pdf->Cell(10, 8, utf8_decode('Feriad'), 0, 0, 'R');
			$this->pdf->setXY(195, 28);
			$this->pdf->Cell(4, 4, utf8_decode($work32), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(160, 31);
			$this->pdf->Cell(10, 8, utf8_decode('Dia Habil'), 0, 0, 'R');
			$this->pdf->setXY(170, 33);
			$this->pdf->Cell(4, 4, utf8_decode($work33), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(172, 31);
			$this->pdf->Cell(10, 8, utf8_decode('Event'), 0, 0, 'R');
			$this->pdf->setXY(182, 33);
			$this->pdf->Cell(4, 4, utf8_decode($work34), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 31);
			$this->pdf->Cell(10, 8, utf8_decode('Vacaci'), 0, 0, 'R');
			$this->pdf->setXY(195, 33);
			$this->pdf->Cell(4, 4, utf8_decode($work35), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(155, 35);
			$this->pdf->Cell(50, 8, utf8_decode('4.5.4 ¿Recibió algún pago?'), 0, 0, 'L');
			$this->pdf->setXY(152, 39);
			$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
			$this->pdf->setXY(162, 41);
			$this->pdf->Cell(4, 4, utf8_decode($work40), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(160, 39);
			$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
			$this->pdf->setXY(170, 41);
			$this->pdf->Cell(4, 4, utf8_decode($work36), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(172, 39);
			$this->pdf->Cell(10, 8, utf8_decode('Espec'), 0, 0, 'R');
			$this->pdf->setXY(182, 41);
			$this->pdf->Cell(4, 4, utf8_decode($work38), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 39);
			$this->pdf->Cell(10, 8, utf8_decode('Dinero'), 0, 0, 'R');
			$this->pdf->setXY(195, 41);
			$this->pdf->Cell(4, 4, utf8_decode($work39), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 45);
			$this->pdf->Cell(50, 8, utf8_decode('4.6 MEDIO DE TRANSPORTE PARA LLEGAR A LA UNIDAD EDUCATIVA'), 0, 0, 'L');
			$this->pdf->setXY(10, 51);
			$this->pdf->Cell(80, 30, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(10, 49);
			$this->pdf->Cell(10, 8, utf8_decode('4.6.1 ¿Cómo llega el estudiante a UE?'), 0, 0, 'L');
			$this->pdf->setXY(55, 49);
			$this->pdf->Cell(10, 8, utf8_decode('4.6.2 ¿En que tiempo llega?'), 0, 0, 'L');
			$this->pdf->setXY(30, 53);
			$this->pdf->Cell(10, 8, utf8_decode('A pie'), 0, 0, 'R');
			$this->pdf->setXY(40, 55);
			$this->pdf->Cell(4, 4, utf8_decode($transp1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 53);
			$this->pdf->Cell(10, 8, utf8_decode('Menos de Media Hora'), 0, 0, 'R');
			$this->pdf->setXY(85, 55);
			$this->pdf->Cell(4, 4, utf8_decode($transp5), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(30, 58);
			$this->pdf->Cell(10, 8, utf8_decode('En vehículo terrestre'), 0, 0, 'R');
			$this->pdf->setXY(40, 60);
			$this->pdf->Cell(4, 4, utf8_decode($transp2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 58);
			$this->pdf->Cell(10, 8, utf8_decode('Entre media hora y una hora'), 0, 0, 'R');
			$this->pdf->setXY(85, 60);
			$this->pdf->Cell(4, 4, utf8_decode($transp6), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(30, 63);
			$this->pdf->Cell(10, 8, utf8_decode('Fluvial'), 0, 0, 'R');
			$this->pdf->setXY(40, 65);
			$this->pdf->Cell(4, 4, utf8_decode($transp3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 63);
			$this->pdf->Cell(10, 8, utf8_decode('Entre una a dos horas'), 0, 0, 'R');
			$this->pdf->setXY(85, 65);
			$this->pdf->Cell(4, 4, utf8_decode($transp7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(30, 68);
			$this->pdf->Cell(10, 8, utf8_decode('Otro (especifique)'), 0, 0, 'R');
			$this->pdf->setXY(40, 70);
			$this->pdf->Cell(4, 4, utf8_decode($transp4), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(75, 68);
			$this->pdf->Cell(10, 8, utf8_decode('Más de dos horas'), 0, 0, 'R');
			$this->pdf->setXY(85, 70);
			$this->pdf->Cell(4, 4, utf8_decode($transp8), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(19, 75);
			$this->pdf->Cell(25, 4, utf8_decode($transp->otrollega), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(90, 45);
			$this->pdf->Cell(50, 8, utf8_decode('4.7 ABANDONO ECOLAR CORRESPONDIENTE A LA GESTIÓN ANTERIOR'), 0, 0, 'L');
			$this->pdf->setXY(91, 51);
			$this->pdf->Cell(110, 30, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(91, 49);
			$this->pdf->Cell(15, 8, utf8_decode('4.7.1 ¿El estudiante abandonó la Unidad Educativa el año pasado?'), 0, 0, 'L');
			$this->pdf->setXY(160, 50);
			$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
			$this->pdf->setXY(170, 52);
			$this->pdf->Cell(4, 4, utf8_decode($aban1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(170, 50);
			$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
			$this->pdf->setXY(180, 52);
			$this->pdf->Cell(4, 4, utf8_decode($aban2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(91, 53);
			$this->pdf->Cell(15, 8, utf8_decode('4.7.2 ¿Cuál o cuales fueron las raones de abandono escolar?'), 0, 0, 'L');
			$this->pdf->setXY(115, 57);
			$this->pdf->Cell(10, 8, utf8_decode('Tuvo que ayudar a sus padres'), 0, 0, 'R');
			$this->pdf->setXY(125, 59);
			$this->pdf->Cell(4, 4, utf8_decode($aban3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(150, 57);
			$this->pdf->Cell(10, 8, utf8_decode('Tuvo trabajo renumerado'), 0, 0, 'R');
			$this->pdf->setXY(160, 59);
			$this->pdf->Cell(4, 4, utf8_decode($aban4), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 57);
			$this->pdf->Cell(10, 8, utf8_decode('Falta de dinero'), 0, 0, 'R');
			$this->pdf->setXY(195, 59);
			$this->pdf->Cell(4, 4, utf8_decode($aban5), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(115, 62);
			$this->pdf->Cell(10, 8, utf8_decode('Precodidad / Rezago'), 0, 0, 'R');
			$this->pdf->setXY(125, 64);
			$this->pdf->Cell(4, 4, utf8_decode($aban6), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(150, 62);
			$this->pdf->Cell(10, 8, utf8_decode('La UE era distante'), 0, 0, 'R');
			$this->pdf->setXY(160, 64);
			$this->pdf->Cell(4, 4, utf8_decode($aban7), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 62);
			$this->pdf->Cell(10, 8, utf8_decode('Labores de casa'), 0, 0, 'R');
			$this->pdf->setXY(195, 64);
			$this->pdf->Cell(4, 4, utf8_decode($aban8), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(115, 67);
			$this->pdf->Cell(10, 8, utf8_decode('Embarazo o paternidad'), 0, 0, 'R');
			$this->pdf->setXY(125, 69);
			$this->pdf->Cell(4, 4, utf8_decode($aban9), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(150, 67);
			$this->pdf->Cell(10, 8, utf8_decode('Enfermedad/Accid/Discap'), 0, 0, 'R');
			$this->pdf->setXY(160, 69);
			$this->pdf->Cell(4, 4, utf8_decode($aban10), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(185, 67);
			$this->pdf->Cell(10, 8, utf8_decode('Viaje o Traslado'), 0, 0, 'R');
			$this->pdf->setXY(195, 69);
			$this->pdf->Cell(4, 4, utf8_decode($aban11), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(115, 72);
			$this->pdf->Cell(10, 8, utf8_decode('Falta de Interés'), 0, 0, 'R');
			$this->pdf->setXY(125, 74);
			$this->pdf->Cell(4, 4, utf8_decode($aban12), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(150, 72);
			$this->pdf->Cell(10, 8, utf8_decode('Bullying o descriminación'), 0, 0, 'R');
			$this->pdf->setXY(160, 74);
			$this->pdf->Cell(4, 4, utf8_decode($aban13), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(165, 72);
			$this->pdf->Cell(10, 8, utf8_decode('Otra'), 0, 0, 'R');
			$this->pdf->setXY(175, 74);
			$this->pdf->Cell(24, 4, utf8_decode($abandono->otrarazon), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 80);
			$this->pdf->Cell(50, 8, utf8_decode('V. DATOS DEL PADRE, MADRE O TUTOR (A) DE LA O EL ESTUDIANTE'), 0, 0, 'L');
			$this->pdf->setXY(10, 86);
			$this->pdf->Cell(192, 8, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(11, 86);
			$this->pdf->Cell(50, 8, utf8_decode('5.1 LA O EL ESTUDIANTE VIVE HABITUALMENTE CON: '), 0, 0, 'L');

			$this->pdf->setXY(80, 86);
			$this->pdf->Cell(10, 8, utf8_decode('Padre y Madre'), 0, 0, 'R');
			$this->pdf->setXY(90, 88);
			$this->pdf->Cell(4, 4, utf8_decode($vivecon1), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(100, 86);
			$this->pdf->Cell(10, 8, utf8_decode('Solo Padre'), 0, 0, 'R');
			$this->pdf->setXY(110, 88);
			$this->pdf->Cell(4, 4, utf8_decode($vivecon2), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(120, 86);
			$this->pdf->Cell(10, 8, utf8_decode('Solo Madre'), 0, 0, 'R');
			$this->pdf->setXY(130, 88);
			$this->pdf->Cell(4, 4, utf8_decode($vivecon3), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(140, 86);
			$this->pdf->Cell(10, 8, utf8_decode('Tutor'), 0, 0, 'R');
			$this->pdf->setXY(150, 88);
			$this->pdf->Cell(4, 4, utf8_decode($vivecon4), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(160, 86);
			$this->pdf->Cell(10, 8, utf8_decode('Solo(a)'), 0, 0, 'R');
			$this->pdf->setXY(170, 88);
			$this->pdf->Cell(4, 4, utf8_decode($vivecon5), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 95);
			$this->pdf->Cell(95, 46, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(11, 94);
			$this->pdf->Cell(50, 8, utf8_decode('5.2 DATOS DEL PADRE: '), 0, 0, 'L');
			$this->pdf->setXY(106, 95);
			$this->pdf->Cell(96, 46, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(107, 94);
			$this->pdf->Cell(50, 8, utf8_decode('5.3 DATOS DE LA MADRE: '), 0, 0, 'L');

			$this->pdf->setXY(10, 98);
			$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
			$this->pdf->setXY(42, 100);
			$this->pdf->Cell(35, 4, utf8_decode($padre->ci), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(80, 100);
			$this->pdf->Cell(10, 4, utf8_decode($padre->complemento), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(93, 100);
			$this->pdf->Cell(10, 4, utf8_decode($padre->extendido), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 103);
			$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
			$this->pdf->setXY(40, 105);
			$this->pdf->Cell(63, 4, utf8_decode($padre->appat), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 108);
			$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
			$this->pdf->setXY(40, 110);
			$this->pdf->Cell(63, 4, utf8_decode($padre->apmat), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 113);
			$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
			$this->pdf->setXY(40, 115);
			$this->pdf->Cell(63, 4, utf8_decode($padre->nombres), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 118);
			$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
			$this->pdf->setXY(40, 120);
			$this->pdf->Cell(63, 4, utf8_decode($padre->idioma), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 123);
			$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
			$this->pdf->setXY(40, 125);
			$this->pdf->Cell(63, 4, utf8_decode($padre->ocupacion), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 128);
			$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
			$this->pdf->setXY(40, 130);
			$this->pdf->Cell(63, 4, utf8_decode($padre->grado), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 133);
			$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
			$this->pdf->setXY(40, 133);
			$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
			$this->pdf->setXY(50, 135);
			$this->pdf->Cell(10, 4, utf8_decode($padre->fnacdia), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(60, 133);
			$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
			$this->pdf->setXY(70, 135);
			$this->pdf->Cell(10, 4, utf8_decode($padre->fnacmes), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(80, 133);
			$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
			$this->pdf->setXY(90, 135);
			$this->pdf->Cell(13, 4, utf8_decode($padre->fnacanio), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(108, 98);
			$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
			$this->pdf->setXY(140, 100);
			$this->pdf->Cell(35, 4, utf8_decode($madre->ci), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(178, 100);
			$this->pdf->Cell(10, 4, utf8_decode($madre->complemento), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(190, 100);
			$this->pdf->Cell(10, 4, utf8_decode($madre->extendido), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 103);
			$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
			$this->pdf->setXY(137, 105);
			$this->pdf->Cell(63, 4, utf8_decode($madre->appat), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 108);
			$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
			$this->pdf->setXY(137, 110);
			$this->pdf->Cell(63, 4, utf8_decode($madre->apmat), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 113);
			$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
			$this->pdf->setXY(137, 115);
			$this->pdf->Cell(63, 4, utf8_decode($madre->nombres), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 118);
			$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
			$this->pdf->setXY(137, 120);
			$this->pdf->Cell(63, 4, utf8_decode($madre->idioma), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 123);
			$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
			$this->pdf->setXY(137, 125);
			$this->pdf->Cell(63, 4, utf8_decode($madre->ocupacion), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 128);
			$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
			$this->pdf->setXY(137, 130);
			$this->pdf->Cell(63, 4, utf8_decode($madre->grado), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(107, 133);
			$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
			$this->pdf->setXY(140, 133);
			$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
			$this->pdf->setXY(150, 135);
			$this->pdf->Cell(10, 4, utf8_decode($madre->fnacdia), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(158, 133);
			$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
			$this->pdf->setXY(168, 135);
			$this->pdf->Cell(10, 4, utf8_decode($madre->fnacmes), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(177, 133);
			$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
			$this->pdf->setXY(187, 135);
			$this->pdf->Cell(13, 4, utf8_decode($madre->fnacanio), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 142);
			$this->pdf->Cell(95, 49, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
			$this->pdf->setXY(11, 141);
			$this->pdf->Cell(50, 8, utf8_decode('5.4 DATOS DEL TUTOR: '), 0, 0, 'L');
			$this->pdf->setXY(10, 143);
			$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
			$this->pdf->setXY(42, 145);
			$this->pdf->Cell(35, 4, utf8_decode($tutor->ci), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(80, 145);
			$this->pdf->Cell(10, 4, utf8_decode($tutor->complemento), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(93, 145);
			$this->pdf->Cell(10, 4, utf8_decode($tutor->extendido), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 149);
			$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
			$this->pdf->setXY(40, 150);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->appat), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 154);
			$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
			$this->pdf->setXY(40, 155);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->apmat), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 158);
			$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
			$this->pdf->setXY(40, 160);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->nombres), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 163);
			$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
			$this->pdf->setXY(40, 165);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->idioma), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 168);
			$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
			$this->pdf->setXY(40, 170);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->ocupacion), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 173);
			$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
			$this->pdf->setXY(40, 175);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->grado), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(10, 178);
			$this->pdf->Cell(30, 8, utf8_decode('¿Cuál es el parentesco?'), 0, 0, 'R');
			$this->pdf->setXY(40, 180);
			$this->pdf->Cell(63, 4, utf8_decode($tutor->parentesco), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(10, 183);
			$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
			$this->pdf->setXY(40, 183);
			$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
			$this->pdf->setXY(50, 185);
			$this->pdf->Cell(10, 4, utf8_decode($tutor->fnacdia), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(60, 183);
			$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
			$this->pdf->setXY(70, 185);
			$this->pdf->Cell(10, 4, utf8_decode($tutor->fnacmes), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(80, 183);
			$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
			$this->pdf->setXY(90, 185);
			$this->pdf->Cell(13, 4, utf8_decode($tutor->fnacanio), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(100, 145);
			$this->pdf->Cell(20, 8, utf8_decode('Lugar:'), 0, 0, 'R');
			$this->pdf->setXY(125, 147);
			$this->pdf->Cell(30, 4, utf8_decode('CHUQUISACA'), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(102, 153);
			$this->pdf->Cell(20, 8, utf8_decode('Fecha:'), 0, 0, 'R');
			$this->pdf->setXY(120, 153);
			$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
			$this->pdf->setXY(130, 155);
			$this->pdf->Cell(10, 4, utf8_decode($inscripcion->insdia), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(140, 153);
			$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
			$this->pdf->setXY(150, 155);
			$this->pdf->Cell(10, 4, utf8_decode($inscripcion->insmes), 'TBLR', 0, 'L', '0');
			$this->pdf->setXY(160, 153);
			$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
			$this->pdf->setXY(170, 155);
			$this->pdf->Cell(13, 4, utf8_decode($inscripcion->insanio), 'TBLR', 0, 'L', '0');

			$this->pdf->setXY(110, 180);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setXY(110, 184);
			$this->pdf->Cell(40, 5, utf8_decode('Firma del Padre/ Madre/ Tutor'), 0, 0, 'C');

			$this->pdf->setXY(160, 180);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setXY(160, 184);
			$this->pdf->Cell(40, 5, utf8_decode('Firma del Director de la UE'), 0, 0, 'C');
		}



		$this->pdf->Output("Inscrip - RUDE .pdf", 'I');

		ob_end_flush();
	}
	public function print_rude7($idins)
	{

		$unidadeduca = $this->estud->get_data_unidadeduca($idins);
		$nacimiento = $this->estud->get_data_nacimiento($idins);
		$estudiante = $this->estud->get_data_estudiantes($nacimiento->idest);
		if ($estudiante->genero == 'M') $genero1 = 'X';
		else $genero1 = '';
		if ($estudiante->genero == 'F') $genero2 = 'X';
		else $genero2 = '';
		if ($estudiante->vivecon == 'Padre y Madre') $vivecon1 = 'X';
		else $vivecon1 = '';
		if ($estudiante->vivecon == 'Solo Padre') $vivecon2 = 'X';
		else $vivecon2 = '';
		if ($estudiante->vivecon == 'Solo Madre') $vivecon3 = 'X';
		else $vivecon3 = '';
		if ($estudiante->vivecon == 'Tutor') $vivecon4 = 'X';
		else $vivecon4 = '';
		if ($estudiante->vivecon == 'Solo') $vivecon5 = 'X';
		else $vivecon5 = '';

		$discap = $this->estud->get_data_discap($idins);
		if ($discap->discap == 'si') $discap1 = 'X';
		else $discap1 = '';
		if ($discap->discap == 'no') $discap2 = 'X';
		else $discap2 = '';
		if ($discap->tipo == 'psiquica') $discapt1 = 'X';
		else $discapt1 = '';
		if ($discap->tipo == 'autismo') $discapt2 = 'X';
		else $discapt2 = '';
		if ($discap->tipo == 'down') $discapt3 = 'X';
		else $discapt3 = '';
		if ($discap->tipo == 'instelectual') $discapt4 = 'X';
		else $discapt4 = '';
		if ($discap->tipo == 'auditiva') $discapt5 = 'X';
		else $discapt5 = '';
		if ($discap->tipo == 'fisica-motora') $discapt6 = 'X';
		else $discapt6 = '';
		if ($discap->tipo == 'sordoceguera') $discapt7 = 'X';
		else $discapt7 = '';
		if ($discap->tipo == 'multiple') $discapt8 = 'X';
		else $discapt8 = '';
		if ($discap->tipo == 'visual') $discapt9 = 'X';
		else $discapt9 = '';
		if ($discap->gradodiscap == 'Leve') $discapg1 = 'X';
		else $discapg1 = '';
		if ($discap->gradodiscap == 'Moderado') $discapg2 = 'X';
		else $discapg2 = '';
		if ($discap->gradodiscap == 'Grave') $discapg3 = 'X';
		else $discapg3 = '';
		if ($discap->gradodiscap == 'Muy Grave') $discapg4 = 'X';
		else $discapg4 = '';
		if ($discap->gradodiscap == 'ceguera total') $discapg5 = 'X';
		else $discapg5 = '';
		if ($discap->gradodiscap == 'baja vision') $discapg6 = 'X';
		else $discapg6 = '';
		$local = $this->estud->get_data_local($idins);
		$idioma = $this->estud->get_data_idioma($idins);
		if ($idioma->nacion == 'Ninguno') $nacion1 = 'X';
		else $nacion1 = '';
		if ($idioma->nacion == 'Araona') $nacion2 = 'X';
		else $nacion2 = '';
		if ($idioma->nacion == 'Aymara') $nacion3 = 'X';
		else $nacion3 = '';
		if ($idioma->nacion == 'Baure') $nacion4 = 'X';
		else $nacion4 = '';
		if ($idioma->nacion == 'Bésiro') $nacion5 = 'X';
		else $nacion5 = '';
		if ($idioma->nacion == 'Canichana') $nacion6 = 'X';
		else $nacion6 = '';
		if ($idioma->nacion == 'Cavineño') $nacion7 = 'X';
		else $nacion7 = '';
		if ($idioma->nacion == 'Cayubaba') $nacion8 = 'X';
		else $nacion8 = '';
		if ($idioma->nacion == 'Chacobo') $nacion9 = 'X';
		else $nacion9 = '';
		if ($idioma->nacion == 'Chiman') $nacion10 = 'X';
		else $nacion10 = '';
		if ($idioma->nacion == 'Ese Ejja') $nacion11 = 'X';
		else $nacion11 = '';
		if ($idioma->nacion == 'Guaraní') $nacion12 = 'X';
		else $nacion12 = '';
		if ($idioma->nacion == 'Guarasuawe') $nacion13 = 'X';
		else $nacion13 = '';
		if ($idioma->nacion == 'Guarayo') $nacion14 = 'X';
		else $nacion14 = '';
		if ($idioma->nacion == 'Itonoma') $nacion15 = 'X';
		else $nacion15 = '';
		if ($idioma->nacion == 'Leco') $nacion16 = 'X';
		else $nacion16 = '';
		if ($idioma->nacion == 'Machajuyai-Kallawaya') $nacion17 = 'X';
		else $nacion17 = '';
		if ($idioma->nacion == 'Machineru') $nacion18 = 'X';
		else $nacion18 = '';
		if ($idioma->nacion == 'Maropa') $nacion19 = 'X';
		else $nacion19 = '';
		if ($idioma->nacion == 'Mojeño-Ignaciano') $nacion20 = 'X';
		else $nacion20 = '';
		if ($idioma->nacion == 'Mojeño-Trinitario') $nacion21 = 'X';
		else $nacion21 = '';
		if ($idioma->nacion == 'Moré') $nacion22 = 'X';
		else $nacion22 = '';
		if ($idioma->nacion == 'Mosetén') $nacion23 = 'X';
		else $nacion23 = '';
		if ($idioma->nacion == 'Movima') $nacion24 = 'X';
		else $nacion24 = '';
		if ($idioma->nacion == 'Tacawara') $nacion25 = 'X';
		else $nacion25 = '';
		if ($idioma->nacion == 'Puquina') $nacion26 = 'X';
		else $nacion26 = '';
		if ($idioma->nacion == 'Quechua') $nacion27 = 'X';
		else $nacion27 = '';
		if ($idioma->nacion == 'Sirionó') $nacion28 = 'X';
		else $nacion28 = '';
		if ($idioma->nacion == 'Tacana') $nacion29 = 'X';
		else $nacion29 = '';
		if ($idioma->nacion == 'Tapiete') $nacion30 = 'X';
		else $nacion30 = '';
		if ($idioma->nacion == 'Toromona') $nacion31 = 'X';
		else $nacion31 = '';
		if ($idioma->nacion == 'Uru-Chipaya') $nacion32 = 'X';
		else $nacion32 = '';
		if ($idioma->nacion == 'Weenhayek') $nacion33 = 'X';
		else $nacion33 = '';
		if ($idioma->nacion == 'Yaminawa') $nacion34 = 'X';
		else $nacion34 = '';
		if ($idioma->nacion == 'Yuki') $nacion35 = 'X';
		else $nacion35 = '';
		if ($idioma->nacion == 'Yuracaré') $nacion36 = 'X';
		else $nacion36 = '';
		if ($idioma->nacion == 'Zamuco') $nacion37 = 'X';
		else $nacion37 = '';
		if ($idioma->nacion == 'Afroboliviano') $nacion38 = 'X';
		else $nacion38 = '';
		$salud = $this->estud->get_data_salud($idins);
		if ($salud->posta == 'si') $salud1 = 'X';
		else $salud1 = '';
		if ($salud->posta == 'no') $salud2 = 'X';
		else $salud2 = '';
		if ($salud->visitaposta1 == 'si') $salud3 = 'X';
		else $salud3 = '';
		if ($salud->visitaposta2 == 'si') $salud4 = 'X';
		else $salud4 = '';
		if ($salud->visitaposta3 == 'si') $salud5 = 'X';
		else $salud5 = '';
		if ($salud->visitaposta4 == 'si') $salud6 = 'X';
		else $salud6 = '';
		if ($salud->visitaposta5 == 'si') $salud7 = 'X';
		else $salud7 = '';
		if ($salud->visitaposta6 == 'si') $salud8 = 'X';
		else $salud8 = '';
		if ($salud->veces == '1 a 2') $salud9 = 'X';
		else $salud9 = '';
		if ($salud->veces == '3 a 5') $salud10 = 'X';
		else $salud10 = '';
		if ($salud->veces == '6 o +') $salud11 = 'X';
		else $salud11 = '';
		if ($salud->veces == 'ninguna') $salud12 = 'X';
		else $salud12 = '';
		if ($salud->seguro == 'si') $salud13 = 'X';
		else $salud13 = '';
		if ($salud->seguro == 'no') $salud14 = 'X';
		else $salud14 = '';
		$servicios = $this->estud->get_data_servicios($idins);
		if ($servicios->agua == 'si') $serv1 = 'X';
		else $serv1 = '';
		if ($servicios->agua == 'no') $serv2 = 'X';
		else $serv2 = '';
		if ($servicios->banio == 'si') $serv3 = 'X';
		else $serv3 = '';
		if ($servicios->banio == 'no') $serv4 = 'X';
		else $serv4 = '';
		if ($servicios->alcanta == 'si') $serv5 = 'X';
		else $serv5 = '';
		if ($servicios->alcanta == 'no') $serv6 = 'X';
		else $serv6 = '';
		if ($servicios->energia == 'si') $serv7 = 'X';
		else $serv7 = '';
		if ($servicios->energia == 'no') $serv8 = 'X';
		else $serv8 = '';
		if ($servicios->basura == 'si') $serv9 = 'X';
		else $serv9 = '';
		if ($servicios->basura == 'no') $serv10 = 'X';
		else $serv10 = '';
		if ($servicios->hogar == 'Propia') $serv11 = 'X';
		else $serv11 = '';
		if ($servicios->hogar == 'Alquilada') $serv12 = 'X';
		else $serv12 = '';
		if ($servicios->hogar == 'Anticretico') $serv13 = 'X';
		else $serv13 = '';
		if ($servicios->hogar == 'Cedida') $serv14 = 'X';
		else $serv14 = '';
		if ($servicios->hogar == 'prestada') $serv15 = 'X';
		else $serv15 = '';
		if ($servicios->hogar == 'cttomixto') $serv16 = 'X';
		else $serv16 = '';
		$net = $this->estud->get_data_net($idins);
		if ($net->netvivienda == 'si') $net1 = 'X';
		else $net1 = '';
		if ($net->netunidadedu == 'si') $net2 = 'X';
		else $net2 = '';
		if ($net->netpublic == 'si') $net3 = 'X';
		else $net3 = '';
		if ($net->netcelu == 'si') $net4 = 'X';
		else $net4 = '';
		if ($net->nonet == 'si') $net5 = 'X';
		else $net5 = '';
		if ($net->frecuencia == 'Diariamente') $net6 = 'X';
		else $net6 = '';
		if ($net->frecuencia == 'Una vez a la semana') $net7 = 'X';
		else $net7 = '';
		if ($net->frecuencia == 'Mas de una vez a la semana') $net8 = 'X';
		else $net8 = '';
		if ($net->frecuencia == 'Una vez al mes') $net9 = 'X';
		else $net9 = '';
		$trabajo = $this->estud->get_data_trabajo($idins);
		if ($trabajo->trabajo == 'si') $work1 = 'X';
		else $work1 = '';
		if ($trabajo->trabajo == 'no') $work2 = 'X';
		else $work2 = '';
		if ($trabajo->ene == 'si') $work3 = 'X';
		else $work3 = '';
		if ($trabajo->feb == 'si') $work4 = 'X';
		else $work4 = '';
		if ($trabajo->mar == 'si') $work5 = 'X';
		else $work5 = '';
		if ($trabajo->abr == 'si') $work6 = 'X';
		else $work6 = '';
		if ($trabajo->may == 'si') $work7 = 'X';
		else $work7 = '';
		if ($trabajo->jun == 'si') $work8 = 'X';
		else $work8 = '';
		if ($trabajo->jul == 'si') $work9 = 'X';
		else $work9 = '';
		if ($trabajo->ago == 'si') $work10 = 'X';
		else $work10 = '';
		if ($trabajo->sep == 'si') $work11 = 'X';
		else $work11 = '';
		if ($trabajo->oct == 'si') $work12 = 'X';
		else $work12 = '';
		if ($trabajo->nov == 'si') $work13 = 'X';
		else $work13 = '';
		if ($trabajo->dic == 'si') $work14 = 'X';
		else $work14 = '';
		if ($trabajo->actividad == 'trabajo agricultura') $work15 = 'X';
		else $work15 = '';
		if ($trabajo->actividad == 'ayudo agricultura') $work16 = 'X';
		else $work16 = '';
		if ($trabajo->actividad == 'ayudo hogar') $work17 = 'X';
		else $work17 = '';
		if ($trabajo->actividad == 'trabajo mineria') $work18 = 'X';
		else $work18 = '';
		if ($trabajo->actividad == 'trabajo dependiente') $work19 = 'X';
		else $work19 = '';
		if ($trabajo->actividad == 'Vendedor por cuenta propia') $work20 = 'X';
		else $work20 = '';
		if ($trabajo->actividad == 'Transporte o mecánica') $work21 = 'X';
		else $work21 = '';
		if ($trabajo->actividad == 'Lustrabotas') $work22 = 'X';
		else $work22 = '';
		if ($trabajo->actividad == 'Trabajador del hogar o niñero') $work23 = 'X';
		else $work23 = '';
		if ($trabajo->actividad == 'Ayudante familiar') $work24 = 'X';
		else $work24 = '';
		if ($trabajo->actividad == 'Ayudante de venta o comercio') $work25 = 'X';
		else $work25 = '';
		if ($trabajo->actividad == 'otro') $work26 = 'X';
		else $work26 = '';
		if ($trabajo->turnoman == 'si') $work27 = 'X';
		else $work27 = '';
		if ($trabajo->turnotar == 'si') $work28 = 'X';
		else $work28 = '';
		if ($trabajo->turnonoc == 'si') $work29 = 'X';
		else $work29 = '';
		if ($trabajo->frecuencia == 'Todos los dias') $work30 = 'X';
		else $work30 = '';
		if ($trabajo->frecuencia == 'Fines de semana') $work31 = 'X';
		else $work31 = '';
		if ($trabajo->frecuencia == 'Dias Festivos') $work32 = 'X';
		else $work32 = '';
		if ($trabajo->frecuencia == 'Dias habiles') $work33 = 'X';
		else $work33 = '';
		if ($trabajo->frecuencia == 'Eventual / esporádico') $work34 = 'X';
		else $work34 = '';
		if ($trabajo->frecuencia == 'En vacaciones') $work35 = 'X';
		else $work35 = '';
		if ($trabajo->pago == 'si') $work36 = 'X';
		else $work36 = '';
		if ($trabajo->pago == 'no') $work40 = 'X';
		else $work40 = '';
		if ($trabajo->tipopago == 'nopago') $work37 = 'X';
		else $work37 = '';
		if ($trabajo->tipopago == 'En especie') $work38 = 'X';
		else $work38 = '';
		if ($trabajo->tipopago == 'Dinero') $work39 = 'X';
		else $work39 = '';
		$transp = $this->estud->get_data_transporte($idins);
		if ($transp->llega == 'pie') $transp1 = 'X';
		else $transp1 = '';
		if ($transp->llega == 'vehiculo') $transp2 = 'X';
		else $transp2 = '';
		if ($transp->llega == 'fluvial') $transp3 = 'X';
		else $transp3 = '';
		if ($transp->llega == 'otro') $transp4 = 'X';
		else $transp4 = '';
		if ($transp->tiempo == 'Menos de media hora') $transp5 = 'X';
		else $transp5 = '';
		if ($transp->tiempo == 'Entre media hora y una hora') $transp6 = 'X';
		else $transp6 = '';
		if ($transp->tiempo == 'Entre una a dos horas') $transp7 = 'X';
		else $transp7 = '';
		if ($transp->tiempo == 'Dos horas o mas') $transp8 = 'X';
		else $transp8 = '';
		$abandono = $this->estud->get_data_abandono($idins);
		if ($abandono->abandono == 'si') $aban1 = 'X';
		else $aban1 = '';
		if ($abandono->abandono == 'no') $aban2 = 'X';
		else $aban2 = '';
		if ($abandono->razon0 == 'si') $aban3 = 'X';
		else $aban3 = '';
		if ($abandono->razon1 == 'si') $aban4 = 'X';
		else $aban4 = '';
		if ($abandono->razon2 == 'si') $aban5 = 'X';
		else $aban5 = '';
		if ($abandono->razon3 == 'si') $aban6 = 'X';
		else $aban6 = '';
		if ($abandono->razon4 == 'si') $aban7 = 'X';
		else $aban7 = '';
		if ($abandono->razon5 == 'si') $aban8 = 'X';
		else $aban8 = '';
		if ($abandono->razon6 == 'si') $aban9 = 'X';
		else $aban9 = '';
		if ($abandono->razon7 == 'si') $aban10 = 'X';
		else $aban10 = '';
		if ($abandono->razon8 == 'si') $aban11 = 'X';
		else $aban11 = '';
		if ($abandono->razon9 == 'si') $aban12 = 'X';
		else $aban12 = '';
		if ($abandono->razon10 == 'si') $aban13 = 'X';
		else $aban13 = '';
		if ($abandono->razon11 == 'si') $aban14 = 'X';
		else $aban14 = '';
		$padre = $this->estud->get_data_padre($idins);
		$madre = $this->estud->get_data_madre($idins);
		$tutor = $this->estud->get_data_tutor($idins);
		$inscripcion = $this->estud->get_data_inscrip($idins);


		$this->load->library('ins_pdf');

		ob_start();
		$this->pdf = new Ins_pdf('Letter');
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle("Formulario RUDE");
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('FORMULARIO DE INSCRIPCION/ACTUALIZACION'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('REGISTRO UNICO DE ESTUDIANTES'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->SetFont('Arial', 'B', 6);
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('Resolución Ministerial N° 1298/2018'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('LA INFORMACION RECABADA POR EL RUDE SERÁ UTILIZADA Y EXCLUSIVAMENTE PARA'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('FINES DE DISEÑO Y EJECUCIÓN DE POLÍTICAS PÚBLICAS EDUCATIVAS Y SOCIALES'), 0, 0, 'C');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setXY(10, 27);
		$this->pdf->Cell(135, 8, utf8_decode('Importante:El formulario debe ser llenado por el padre, madre o tutor(a)'), 0, 0, 'L');
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('DATOS DE LA UNIDAD EDUCATIVA'), 0, 0, 'L');
		$this->pdf->setXY(120, 30);
		$this->pdf->Cell(80, 5, utf8_decode('CÓDIGO SIE DE LA UNIDAD EDUCATIVA: ' . $unidadeduca->sie), 'TBLR', 0, 'L', '0'); //SIE
		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('II DATOS DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->Cell(190, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 39);
		$this->pdf->Cell(135, 8, utf8_decode('2.1 APELLIDO(S) Y NOMBRE(S):'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->Cell(135, 8, utf8_decode('Apellido Paterno:'), 0, 0, 'L');
		$this->pdf->setXY(35, 45);
		$this->pdf->Cell(80, 4, utf8_decode($estudiante->appaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(12);
		$this->pdf->Cell(135, 8, utf8_decode('Apellido Materno:'), 0, 0, 'L');
		$this->pdf->setXY(35, 50);
		$this->pdf->Cell(80, 4, utf8_decode($estudiante->apmaterno), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(12);
		$this->pdf->Cell(135, 8, utf8_decode('Nombre(s):'), 0, 0, 'L');
		$this->pdf->setXY(35, 55);
		$this->pdf->Cell(80, 4, utf8_decode($estudiante->nombres), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('2.2. LUGAR DE NACIMIENTO:'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->Cell(30, 8, utf8_decode('País:'), 0, 0, 'L');
		$this->pdf->setXY(25, 64);
		$this->pdf->Cell(40, 4, utf8_decode($nacimiento->pais), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(65, 62);
		$this->pdf->Cell(30, 8, utf8_decode('Depto:'), 0, 0, 'L');
		$this->pdf->setXY(78, 64);
		$this->pdf->Cell(37, 4, utf8_decode($nacimiento->dpto), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->Cell(30, 8, utf8_decode('Provincia:'), 0, 0, 'L');
		$this->pdf->setXY(25, 69);
		$this->pdf->Cell(40, 4, utf8_decode($nacimiento->provincia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(65, 67);
		$this->pdf->Cell(35, 8, utf8_decode('Localidad:'), 0, 0, 'L');
		$this->pdf->setXY(78, 69);
		$this->pdf->Cell(37, 4, utf8_decode($nacimiento->localidad), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(10);
		$this->pdf->Cell(50, 8, utf8_decode('2.3. CERTIFICADO DE NACIMIENTO:'), 0, 0, 'L');
		$this->pdf->setX(75);
		$this->pdf->Cell(40, 8, utf8_decode('2.4. FECHA DE NACIMIENTO:'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(12);
		$this->pdf->setXY(12, 78);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->oficialia), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(28);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->libro), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(44);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->partida), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(60);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->folio), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(78);
		$this->pdf->Cell(10, 4, utf8_decode($nacimiento->dia), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(89);
		$this->pdf->Cell(10, 4, utf8_decode($nacimiento->mes), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(100);
		$this->pdf->Cell(15, 4, utf8_decode($nacimiento->anio), 'TBLR', 0, 'C', '0');
		$this->pdf->Ln('2');
		$this->pdf->setX(10);
		$this->pdf->setX(12);
		$this->pdf->Cell(20, 8, utf8_decode('Oficialia N°'), 0, 0, 'L');
		$this->pdf->setX(30);
		$this->pdf->Cell(20, 8, utf8_decode('Libro N°'), 0, 0, 'L');
		$this->pdf->setX(45);
		$this->pdf->Cell(20, 8, utf8_decode('Partida N°'), 0, 0, 'L');
		$this->pdf->setX(63);
		$this->pdf->Cell(20, 8, utf8_decode('Folio N°'), 0, 0, 'L');
		$this->pdf->setX(80);
		$this->pdf->Cell(20, 8, utf8_decode('Dia'), 0, 0, 'L');
		$this->pdf->setX(90);
		$this->pdf->Cell(20, 8, utf8_decode('Mes'), 0, 0, 'L');
		$this->pdf->setX(103);
		$this->pdf->Cell(20, 8, utf8_decode('Año'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setX(10);
		$this->pdf->Cell(50, 8, utf8_decode('2.5. DOCUMENTO DE INDENTIFICACION:'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->setX(12);
		$this->pdf->Cell(30, 4, utf8_decode($estudiante->ci), 'TBLR', 0, 'C', '0');
		$this->pdf->setX(50);
		$this->pdf->Cell(10, 4, utf8_decode($estudiante->complemento), 'TBLR', 0, 'L', '0');
		$this->pdf->setX(65);
		$this->pdf->Cell(10, 4, utf8_decode($estudiante->extension), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('2');
		$this->pdf->setX(16);
		$this->pdf->Cell(30, 8, utf8_decode('Carnet de Identidad'), 0, 0, 'L');
		$this->pdf->setX(50);
		$this->pdf->Cell(20, 8, utf8_decode('Compleo'), 0, 0, 'L');
		$this->pdf->setX(66);
		$this->pdf->Cell(20, 8, utf8_decode('Exten'), 0, 0, 'L');
		$this->pdf->Ln('4');
		$this->pdf->setXY(120, 39);
		$this->pdf->Cell(50, 8, utf8_decode('2.6. CODIGO RUDE (Código automático generado por el Sistema)'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->setX(120);
		$this->pdf->Cell(75, 4, utf8_decode($estudiante->rude), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('3');
		$this->pdf->setX(120);
		$this->pdf->Cell(50, 8, utf8_decode('2.7. SEXO:'), 0, 0, 'L');
		$this->pdf->setX(132);
		$this->pdf->Cell(10, 8, utf8_decode('Masculino'), 0, 0, 'L');
		$this->pdf->setXY(144, 50);
		$this->pdf->Cell(4, 4, utf8_decode($genero1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(132, 53);
		$this->pdf->Cell(10, 8, utf8_decode('Femenino'), 0, 0, 'L');
		$this->pdf->setXY(144, 55);
		$this->pdf->Cell(4, 4, utf8_decode($genero2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 48);
		$this->pdf->Cell(50, 8, utf8_decode('2.8. ¿ESTUDIANTE TIENE DISCAPACIDAD?'), 0, 0, 'L');
		$this->pdf->setXY(158, 53);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'L');
		$this->pdf->setXY(162, 55);
		$this->pdf->Cell(4, 4, utf8_decode($discap1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(173, 53);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'L');
		$this->pdf->setXY(178, 55);
		$this->pdf->Cell(4, 4, utf8_decode($discap2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(120, 59);
		$this->pdf->Cell(50, 8, utf8_decode('2.9. N° DE REGISTRO DE DISCAPACIDAD O IBC:'), 0, 0, 'L');
		$this->pdf->setXY(171, 61);
		$this->pdf->Cell(24, 4, utf8_decode($discap->registro), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(120, 65);
		$this->pdf->Cell(50, 8, utf8_decode('2.10. TIPO DE DISCAPACIDAD:'), 0, 0, 'L');
		$this->pdf->setXY(158, 65);
		$this->pdf->Cell(50, 8, utf8_decode('2.11. GRADO DE DISCAPACIDAD:'), 0, 0, 'L');
		$this->pdf->setXY(129, 70);
		$this->pdf->Cell(10, 8, utf8_decode('Psíquica'), 0, 0, 'L');
		$this->pdf->setXY(140, 72);
		$this->pdf->Cell(4, 4, utf8_decode($discapt1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(152, 70);
		$this->pdf->Cell(10, 8, utf8_decode('Auditiva'), 0, 0, 'L');
		$this->pdf->setXY(162, 72);
		$this->pdf->Cell(4, 4, utf8_decode($discapt5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(174, 70);
		$this->pdf->Cell(10, 8, utf8_decode('Leve'), 0, 0, 'R');
		$this->pdf->setXY(184, 72);
		$this->pdf->Cell(4, 4, utf8_decode($discapg1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(129, 75);
		$this->pdf->Cell(10, 8, utf8_decode('Autismo'), 0, 0, 'L');
		$this->pdf->setXY(140, 77);
		$this->pdf->Cell(4, 4, utf8_decode($discapt2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(152, 75);
		$this->pdf->Cell(10, 8, utf8_decode('Física-Motora'), 0, 0, 'R');
		$this->pdf->setXY(162, 77);
		$this->pdf->Cell(4, 4, utf8_decode($discapt6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(174, 75);
		$this->pdf->Cell(10, 8, utf8_decode('Moderado'), 0, 0, 'R');
		$this->pdf->setXY(184, 77);
		$this->pdf->Cell(4, 4, utf8_decode($discapg2), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(129, 80);
		$this->pdf->Cell(10, 8, utf8_decode('Sindrome de Down'), 0, 0, 'R');
		$this->pdf->setXY(140, 82);
		$this->pdf->Cell(4, 4, utf8_decode($discapt3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(152, 80);
		$this->pdf->Cell(10, 8, utf8_decode('Sordocequera'), 0, 0, 'R');
		$this->pdf->setXY(162, 82);
		$this->pdf->Cell(4, 4, utf8_decode($discapt4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(174, 80);
		$this->pdf->Cell(10, 8, utf8_decode('Grave'), 0, 0, 'R');
		$this->pdf->setXY(184, 82);
		$this->pdf->Cell(4, 4, utf8_decode($discapg3), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(129, 85);
		$this->pdf->Cell(10, 8, utf8_decode('Intelectual'), 0, 0, 'R');
		$this->pdf->setXY(140, 87);
		$this->pdf->Cell(4, 4, utf8_decode($discapt4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(152, 85);
		$this->pdf->Cell(10, 8, utf8_decode('Multiple'), 0, 0, 'R');
		$this->pdf->setXY(162, 87);
		$this->pdf->Cell(4, 4, utf8_decode($discapt8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(174, 85);
		$this->pdf->Cell(10, 8, utf8_decode('Muy Grave'), 0, 0, 'R');
		$this->pdf->setXY(184, 87);
		$this->pdf->Cell(4, 4, utf8_decode($discapg4), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(152, 90);
		$this->pdf->Cell(10, 8, utf8_decode('Visual'), 0, 0, 'R');
		$this->pdf->setXY(162, 92);
		$this->pdf->Cell(4, 4, utf8_decode($discapt9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(174, 90);
		$this->pdf->Cell(10, 8, utf8_decode('Ceguera total'), 0, 0, 'R');
		$this->pdf->setXY(184, 92);
		$this->pdf->Cell(4, 4, utf8_decode($discapg5), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(174, 95);
		$this->pdf->Cell(10, 8, utf8_decode('Baja Visión'), 0, 0, 'R');
		$this->pdf->setXY(184, 97);
		$this->pdf->Cell(4, 4, utf8_decode($discapg6), 'TBLR', 0, 'L', '0');

		$this->pdf->Ln('3');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('III DIRECCIÓN ACTUAL DE LA O EL ESTUDIANTE (Información para uso exclusivo de la Unidad educativa)'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->Cell(190, 18, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 106);
		$this->pdf->Cell(25, 8, utf8_decode('Departamento:'), 0, 0, 'R');
		$this->pdf->setXY(36, 107);
		$this->pdf->Cell(40, 4, utf8_decode($local->departamento), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(70, 106);
		$this->pdf->Cell(25, 8, utf8_decode('Provincia:'), 0, 0, 'R');
		$this->pdf->setXY(95, 107);
		$this->pdf->Cell(40, 4, utf8_decode($local->provincia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(130, 106);
		$this->pdf->Cell(25, 8, utf8_decode('Municipio:'), 0, 0, 'R');
		$this->pdf->setXY(155, 107);
		$this->pdf->Cell(40, 4, utf8_decode($local->municipio), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 111);
		$this->pdf->Cell(25, 8, utf8_decode('Localidad:'), 0, 0, 'R');
		$this->pdf->setXY(36, 112);
		$this->pdf->Cell(40, 4, utf8_decode($local->localidad), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(70, 111);
		$this->pdf->Cell(25, 8, utf8_decode('Zona/Villa:'), 0, 0, 'R');
		$this->pdf->setXY(95, 112);
		$this->pdf->Cell(40, 4, utf8_decode($local->zona), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(130, 111);
		$this->pdf->Cell(25, 8, utf8_decode('Av./Calle:'), 0, 0, 'R');
		$this->pdf->setXY(155, 112);
		$this->pdf->Cell(40, 4, utf8_decode($local->calle), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 116);
		$this->pdf->Cell(25, 8, utf8_decode('N° Vivienda:'), 0, 0, 'R');
		$this->pdf->setXY(36, 117);
		$this->pdf->Cell(40, 4, utf8_decode($local->num), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(70, 116);
		$this->pdf->Cell(25, 8, utf8_decode('Télefono Fijo:'), 0, 0, 'R');
		$this->pdf->setXY(95, 117);
		$this->pdf->Cell(40, 4, utf8_decode($local->fono), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(130, 116);
		$this->pdf->Cell(25, 8, utf8_decode('Celular:'), 0, 0, 'R');
		$this->pdf->setXY(155, 117);
		$this->pdf->Cell(40, 4, utf8_decode($local->celular), 'TBLR', 0, 'L', '0');
		$this->pdf->Ln('5');
		$this->pdf->Cell(30);
		$this->pdf->setX(10);
		$this->pdf->Cell(135, 8, utf8_decode('IV ASPECTOS SOCIOECONÓMICOS DE LA O EL ESTUDIANTE'), 0, 0, 'L');
		$this->pdf->setXY(10, 125);
		$this->pdf->Cell(50, 8, utf8_decode('4.1 IDIOMA Y PERTENENCIA CULTURAL DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->Ln('6');
		$this->pdf->Cell(35, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 130);
		$this->pdf->Cell(50, 8, utf8_decode('4.1.1 ¿Cuál es el idioma natal?:'), 0, 0, 'L');
		$this->pdf->setXY(13, 137);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idiomanatal), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 140);
		$this->pdf->Cell(50, 8, utf8_decode('4.1.2 ¿Qué idiomas habla?:'), 0, 0, 'L');
		$this->pdf->setXY(13, 147);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(13, 152);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(13, 157);
		$this->pdf->Cell(30, 4, utf8_decode($idioma->idioma3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(46, 131);
		$this->pdf->Cell(98, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(48, 130);
		$this->pdf->Cell(50, 8, utf8_decode('4.1.3 ¿Pertenece a una nación, pueblo indígena originario?:'), 0, 0, 'L');
		$this->pdf->setXY(51, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Ninguno'), 0, 0, 'R');
		$this->pdf->setXY(66, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Chimán'), 0, 0, 'R');
		$this->pdf->setXY(93, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Mojeño-Agraciano'), 0, 0, 'R');
		$this->pdf->setXY(119, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion20), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 134);
		$this->pdf->Cell(15, 8, utf8_decode('Tapiete'), 0, 0, 'R');
		$this->pdf->setXY(138, 136);
		$this->pdf->Cell(4, 4, utf8_decode($nacion30), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(51, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Afroboliviano'), 0, 0, 'R');
		$this->pdf->setXY(66, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion38), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Ese Ejja'), 0, 0, 'R');
		$this->pdf->setXY(93, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Mojeño-Trinitario'), 0, 0, 'R');
		$this->pdf->setXY(119, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion21), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 139);
		$this->pdf->Cell(15, 8, utf8_decode('Toromona'), 0, 0, 'R');
		$this->pdf->setXY(138, 141);
		$this->pdf->Cell(4, 4, utf8_decode($nacion31), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(51, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Araona'), 0, 0, 'R');
		$this->pdf->setXY(66, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Guaraní'), 0, 0, 'R');
		$this->pdf->setXY(93, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Moré'), 0, 0, 'R');
		$this->pdf->setXY(119, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion22), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 144);
		$this->pdf->Cell(15, 8, utf8_decode('Uru-Chipaya'), 0, 0, 'R');
		$this->pdf->setXY(138, 146);
		$this->pdf->Cell(4, 4, utf8_decode($nacion32), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Aymara'), 0, 0, 'R');
		$this->pdf->setXY(66, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Guarasuawe'), 0, 0, 'R');
		$this->pdf->setXY(93, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Mosetén'), 0, 0, 'R');
		$this->pdf->setXY(119, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion23), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 149);
		$this->pdf->Cell(15, 8, utf8_decode('Weebhayek'), 0, 0, 'R');
		$this->pdf->setXY(138, 151);
		$this->pdf->Cell(4, 4, utf8_decode($nacion33), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Baure'), 0, 0, 'R');
		$this->pdf->setXY(66, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Guarayo'), 0, 0, 'R');
		$this->pdf->setXY(93, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion14), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Movima'), 0, 0, 'R');
		$this->pdf->setXY(119, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion24), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 154);
		$this->pdf->Cell(15, 8, utf8_decode('Yaminawa'), 0, 0, 'R');
		$this->pdf->setXY(138, 156);
		$this->pdf->Cell(4, 4, utf8_decode($nacion34), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Bésiro'), 0, 0, 'R');
		$this->pdf->setXY(66, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Itonoma'), 0, 0, 'R');
		$this->pdf->setXY(93, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion15), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Tacaware'), 0, 0, 'R');
		$this->pdf->setXY(119, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion25), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 159);
		$this->pdf->Cell(15, 8, utf8_decode('Yuki'), 0, 0, 'R');
		$this->pdf->setXY(138, 161);
		$this->pdf->Cell(4, 4, utf8_decode($nacion35), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Canichana'), 0, 0, 'R');
		$this->pdf->setXY(66, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Leco'), 0, 0, 'R');
		$this->pdf->setXY(93, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion16), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Puquina'), 0, 0, 'R');
		$this->pdf->setXY(119, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion26), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 164);
		$this->pdf->Cell(15, 8, utf8_decode('Yuracaré'), 0, 0, 'R');
		$this->pdf->setXY(138, 166);
		$this->pdf->Cell(4, 4, utf8_decode($nacion36), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Cavineño'), 0, 0, 'R');
		$this->pdf->setXY(66, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Machajuyai-Kallawaya'), 0, 0, 'R');
		$this->pdf->setXY(93, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion17), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Quechua'), 0, 0, 'R');
		$this->pdf->setXY(119, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion27), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(123, 169);
		$this->pdf->Cell(15, 8, utf8_decode('Zamuco'), 0, 0, 'R');
		$this->pdf->setXY(138, 171);
		$this->pdf->Cell(4, 4, utf8_decode($nacion37), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 174);
		$this->pdf->Cell(15, 8, utf8_decode('Cayubaba'), 0, 0, 'R');
		$this->pdf->setXY(66, 176);
		$this->pdf->Cell(4, 4, utf8_decode($nacion8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 174);
		$this->pdf->Cell(15, 8, utf8_decode('Machineri'), 0, 0, 'R');
		$this->pdf->setXY(93, 176);
		$this->pdf->Cell(4, 4, utf8_decode($nacion18), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 174);
		$this->pdf->Cell(15, 8, utf8_decode('Sironó'), 0, 0, 'R');
		$this->pdf->setXY(119, 176);
		$this->pdf->Cell(4, 4, utf8_decode($nacion28), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(51, 179);
		$this->pdf->Cell(15, 8, utf8_decode('Chacobo'), 0, 0, 'R');
		$this->pdf->setXY(66, 181);
		$this->pdf->Cell(4, 4, utf8_decode($nacion9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(78, 179);
		$this->pdf->Cell(15, 8, utf8_decode('Maropa'), 0, 0, 'R');
		$this->pdf->setXY(93, 181);
		$this->pdf->Cell(4, 4, utf8_decode($nacion19), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(104, 179);
		$this->pdf->Cell(15, 8, utf8_decode('Tacana'), 0, 0, 'R');
		$this->pdf->setXY(119, 181);
		$this->pdf->Cell(4, 4, utf8_decode($nacion29), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(145, 131);
		$this->pdf->Cell(56, 63, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(146, 125);
		$this->pdf->Cell(50, 8, utf8_decode('4.2 SALUD DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->setXY(146, 130);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.1 ¿Existe algún Centro de Salud/Posta?:'), 0, 0, 'L');
		$this->pdf->setXY(147, 135);
		$this->pdf->Cell(15, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(162, 137);
		$this->pdf->Cell(4, 4, utf8_decode($salud1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(158, 135);
		$this->pdf->Cell(15, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(173, 137);
		$this->pdf->Cell(4, 4, utf8_decode($salud2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(146, 140);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.2 ¿E que centro de Salud fue atendido?:'), 0, 0, 'L');
		$this->pdf->setXY(162, 144);
		$this->pdf->Cell(15, 8, utf8_decode('1. Caja o seguro de salud'), 0, 0, 'R');
		$this->pdf->setXY(178, 146);
		$this->pdf->Cell(4, 4, utf8_decode($salud3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 149);
		$this->pdf->Cell(15, 8, utf8_decode('2. Centros de salud públicos'), 0, 0, 'R');
		$this->pdf->setXY(178, 151);
		$this->pdf->Cell(4, 4, utf8_decode($salud4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 154);
		$this->pdf->Cell(15, 8, utf8_decode('3. Centros de salud privados'), 0, 0, 'R');
		$this->pdf->setXY(178, 156);
		$this->pdf->Cell(4, 4, utf8_decode($salud5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 159);
		$this->pdf->Cell(15, 8, utf8_decode('4. En su vivienda'), 0, 0, 'R');
		$this->pdf->setXY(178, 161);
		$this->pdf->Cell(4, 4, utf8_decode($salud6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 163);
		$this->pdf->Cell(15, 8, utf8_decode('5. Medicina Tradicional'), 0, 0, 'R');
		$this->pdf->setXY(178, 166);
		$this->pdf->Cell(4, 4, utf8_decode($salud7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(162, 168);
		$this->pdf->Cell(15, 8, utf8_decode('6. Farmacia sin receta'), 0, 0, 'R');
		$this->pdf->setXY(178, 171);
		$this->pdf->Cell(4, 4, utf8_decode($salud8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(146, 174);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.3 ¿Cuantas veces fue al centro de salud?:'), 0, 0, 'L');
		$this->pdf->setXY(143, 178);
		$this->pdf->Cell(15, 8, utf8_decode('1 a 2 veces'), 0, 0, 'R');
		$this->pdf->setXY(158, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 178);
		$this->pdf->Cell(15, 8, utf8_decode('3 a 5 veces'), 0, 0, 'R');
		$this->pdf->setXY(175, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(176, 178);
		$this->pdf->Cell(10, 8, utf8_decode('6 ó +'), 0, 0, 'R');
		$this->pdf->setXY(186, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(190, 178);
		$this->pdf->Cell(6, 8, utf8_decode('ning'), 0, 0, 'R');
		$this->pdf->setXY(196, 180);
		$this->pdf->Cell(4, 4, utf8_decode($salud12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(146, 183);
		$this->pdf->Cell(50, 8, utf8_decode('4.2.3 ¿Tiene seguro de salud?:'), 0, 0, 'L');
		$this->pdf->setXY(150, 187);
		$this->pdf->Cell(15, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(165, 189);
		$this->pdf->Cell(4, 4, utf8_decode($salud13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(161, 187);
		$this->pdf->Cell(15, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(176, 189);
		$this->pdf->Cell(4, 4, utf8_decode($salud14), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 192);
		$this->pdf->Cell(50, 8, utf8_decode('4.3 ACCESO DE LA O EL ESTUDIANTE A SEVICIOS BASICOS:'), 0, 0, 'L');
		$this->pdf->setXY(10, 198);
		$this->pdf->Cell(191, 33, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO

		$this->pdf->setXY(12, 197);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.1 ¿Tiene acceso a agua por cañeria de red?:'), 0, 0, 'L');
		$this->pdf->setXY(12, 202);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(22, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(24, 202);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(34, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv2), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(62, 197);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.4 ¿Una energía eléctrica en su vivienda?:'), 0, 0, 'L');
		$this->pdf->setXY(62, 202);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(72, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(74, 202);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(84, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv8), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(120, 197);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.6 La vivienda que ocupa el hogar es:'), 0, 0, 'L');
		$this->pdf->setXY(132, 202);
		$this->pdf->Cell(10, 8, utf8_decode('Propia'), 0, 0, 'R');
		$this->pdf->setXY(142, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(179, 202);
		$this->pdf->Cell(10, 8, utf8_decode('Cedida por Servicios'), 0, 0, 'R');
		$this->pdf->setXY(189, 204);
		$this->pdf->Cell(4, 4, utf8_decode($serv14), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(12, 207);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.2 ¿Tiene Baño su vivienda?:'), 0, 0, 'L');
		$this->pdf->setXY(12, 212);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(22, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(24, 212);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(34, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv4), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(62, 207);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.5 ¿Cuenta con servicio de recojo de Basura?:'), 0, 0, 'L');
		$this->pdf->setXY(62, 212);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(72, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(74, 212);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(84, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv10), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(132, 207);
		$this->pdf->Cell(10, 8, utf8_decode('Alquilada'), 0, 0, 'R');
		$this->pdf->setXY(142, 209);
		$this->pdf->Cell(4, 4, utf8_decode($serv12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(179, 207);
		$this->pdf->Cell(10, 8, utf8_decode('Prestada por parientes o amigos'), 0, 0, 'R');
		$this->pdf->setXY(189, 209);
		$this->pdf->Cell(4, 4, utf8_decode($serv15), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(12, 217);
		$this->pdf->Cell(50, 8, utf8_decode('4.3.2 ¿Tiene red de alcantarillado?:'), 0, 0, 'L');
		$this->pdf->setXY(12, 222);
		$this->pdf->Cell(10, 8, utf8_decode('SI'), 0, 0, 'R');
		$this->pdf->setXY(22, 224);
		$this->pdf->Cell(4, 4, utf8_decode($serv5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(24, 222);
		$this->pdf->Cell(10, 8, utf8_decode('NO'), 0, 0, 'R');
		$this->pdf->setXY(34, 224);
		$this->pdf->Cell(4, 4, utf8_decode($serv6), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(132, 212);
		$this->pdf->Cell(10, 8, utf8_decode('Anticrético'), 0, 0, 'R');
		$this->pdf->setXY(142, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(179, 212);
		$this->pdf->Cell(10, 8, utf8_decode('Contrato mixto (alquile o anticrético)'), 0, 0, 'R');
		$this->pdf->setXY(189, 214);
		$this->pdf->Cell(4, 4, utf8_decode($serv16), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 229);
		$this->pdf->Cell(50, 8, utf8_decode('4.4 ACCESO A INTERNET DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->setXY(10, 235);
		$this->pdf->Cell(191, 16, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO

		$this->pdf->setXY(12, 234);
		$this->pdf->Cell(50, 8, utf8_decode('4.4.1 El estudiante accede a internet en:'), 0, 0, 'L');
		$this->pdf->setXY(120, 234);
		$this->pdf->Cell(50, 8, utf8_decode('4.4.2 ¿Con que frecuencia usa Internet?'), 0, 0, 'L');
		$this->pdf->setXY(22, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Su vivienda'), 0, 0, 'R');
		$this->pdf->setXY(33, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(52, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Lugares Públicos'), 0, 0, 'R');
		$this->pdf->setXY(63, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(82, 238);
		$this->pdf->Cell(10, 8, utf8_decode('No accede a Internet'), 0, 0, 'R');
		$this->pdf->setXY(93, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(135, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Diariamente'), 0, 0, 'R');
		$this->pdf->setXY(145, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(177, 238);
		$this->pdf->Cell(10, 8, utf8_decode('Mas de una vez a la semana'), 0, 0, 'R');
		$this->pdf->setXY(187, 240);
		$this->pdf->Cell(4, 4, utf8_decode($net8), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(22, 243);
		$this->pdf->Cell(10, 8, utf8_decode('La unidad Educativa'), 0, 0, 'R');
		$this->pdf->setXY(33, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(52, 243);
		$this->pdf->Cell(10, 8, utf8_decode('Teléfono Célular'), 0, 0, 'R');
		$this->pdf->setXY(63, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net4), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(135, 243);
		$this->pdf->Cell(10, 8, utf8_decode('Una vez a la semana'), 0, 0, 'R');
		$this->pdf->setXY(145, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(177, 243);
		$this->pdf->Cell(10, 8, utf8_decode('Una vez al mes'), 0, 0, 'R');
		$this->pdf->setXY(187, 245);
		$this->pdf->Cell(4, 4, utf8_decode($net9), 'TBLR', 0, 'L', '0');
		$this->pdf->AddPage();
		$this->pdf->setXY(10, 8);
		$this->pdf->Cell(50, 8, utf8_decode('4.5 ACTIVIDAD LABORAL DE LA O EL ESTUDIANTE:'), 0, 0, 'L');
		$this->pdf->setXY(10, 14);
		$this->pdf->Cell(191, 33, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(12, 14);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.1 ¿En la pasada gestion ¿El estudiante trabajo?'), 0, 0, 'L');
		$this->pdf->setXY(15, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
		$this->pdf->setXY(25, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(25, 18);
		$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
		$this->pdf->setXY(35, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work2), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Ene'), 0, 0, 'R');
		$this->pdf->setXY(20, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(20, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Feb'), 0, 0, 'R');
		$this->pdf->setXY(30, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Mar'), 0, 0, 'R');
		$this->pdf->setXY(40, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(40, 22);
		$this->pdf->Cell(10, 8, utf8_decode('Abr'), 0, 0, 'R');
		$this->pdf->setXY(50, 24);
		$this->pdf->Cell(4, 4, utf8_decode($work6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 27);
		$this->pdf->Cell(10, 8, utf8_decode('May'), 0, 0, 'R');
		$this->pdf->setXY(20, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(20, 27);
		$this->pdf->Cell(10, 8, utf8_decode('Jun'), 0, 0, 'R');
		$this->pdf->setXY(30, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 27);
		$this->pdf->Cell(10, 8, utf8_decode('Jul'), 0, 0, 'R');
		$this->pdf->setXY(40, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(40, 27);
		$this->pdf->Cell(10, 8, utf8_decode('Ago'), 0, 0, 'R');
		$this->pdf->setXY(50, 29);
		$this->pdf->Cell(4, 4, utf8_decode($work10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Sep'), 0, 0, 'R');
		$this->pdf->setXY(20, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work11), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(20, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Oct'), 0, 0, 'R');
		$this->pdf->setXY(30, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Nov'), 0, 0, 'R');
		$this->pdf->setXY(40, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(40, 32);
		$this->pdf->Cell(10, 8, utf8_decode('Dic'), 0, 0, 'R');
		$this->pdf->setXY(50, 34);
		$this->pdf->Cell(4, 4, utf8_decode($work14), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(72, 14);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.2 ¿En la pasada gestion ¿El estudiante trabajo?'), 0, 0, 'L');
		$this->pdf->setXY(75, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Agricultura'), 0, 0, 'R');
		$this->pdf->setXY(85, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work15), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Vendedor dependiente'), 0, 0, 'R');
		$this->pdf->setXY(115, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work19), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Trabajo del hogar o niñero'), 0, 0, 'R');
		$this->pdf->setXY(150, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work23), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 23);
		$this->pdf->Cell(10, 8, utf8_decode('Ganaderia o pesca'), 0, 0, 'R');
		$this->pdf->setXY(85, 25);
		$this->pdf->Cell(4, 4, utf8_decode($work16), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 23);
		$this->pdf->Cell(10, 8, utf8_decode('Vendedor cuenta propia'), 0, 0, 'R');
		$this->pdf->setXY(115, 25);
		$this->pdf->Cell(4, 4, utf8_decode($work20), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 23);
		$this->pdf->Cell(10, 8, utf8_decode('Ayudante familiar'), 0, 0, 'R');
		$this->pdf->setXY(150, 25);
		$this->pdf->Cell(4, 4, utf8_decode($work24), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 28);
		$this->pdf->Cell(10, 8, utf8_decode('Minería'), 0, 0, 'R');
		$this->pdf->setXY(85, 30);
		$this->pdf->Cell(4, 4, utf8_decode($work18), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 28);
		$this->pdf->Cell(10, 8, utf8_decode('Transporte o mecánica'), 0, 0, 'R');
		$this->pdf->setXY(115, 30);
		$this->pdf->Cell(4, 4, utf8_decode($work21), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 28);
		$this->pdf->Cell(10, 8, utf8_decode('Ayudante en el Hogar'), 0, 0, 'R');
		$this->pdf->setXY(150, 30);
		$this->pdf->Cell(4, 4, utf8_decode($work23), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 33);
		$this->pdf->Cell(10, 8, utf8_decode('Construcción'), 0, 0, 'R');
		$this->pdf->setXY(85, 35);
		$this->pdf->Cell(4, 4, utf8_decode($work17), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(105, 33);
		$this->pdf->Cell(10, 8, utf8_decode('Lustra Botas'), 0, 0, 'R');
		$this->pdf->setXY(115, 35);
		$this->pdf->Cell(4, 4, utf8_decode($work22), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 33);
		$this->pdf->Cell(10, 8, utf8_decode('Otro trabajo'), 0, 0, 'R');
		$this->pdf->setXY(150, 35);
		$this->pdf->Cell(4, 4, utf8_decode($work26), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(125, 38);
		$this->pdf->Cell(10, 8, utf8_decode('(Especifique)'), 0, 0, 'R');
		$this->pdf->setXY(135, 40);
		$this->pdf->Cell(19, 4, utf8_decode($trabajo->otrotrabajo), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(155, 14);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.3 ¿En qué turno trabajo el estudiante?'), 0, 0, 'L');
		$this->pdf->setXY(160, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Mañana'), 0, 0, 'R');
		$this->pdf->setXY(170, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work27), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Tarde'), 0, 0, 'R');
		$this->pdf->setXY(182, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work28), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 18);
		$this->pdf->Cell(10, 8, utf8_decode('Noche'), 0, 0, 'R');
		$this->pdf->setXY(195, 20);
		$this->pdf->Cell(4, 4, utf8_decode($work29), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(155, 22);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.4 ¿Con que frecuencia trabajo?'), 0, 0, 'L');
		$this->pdf->setXY(160, 26);
		$this->pdf->Cell(10, 8, utf8_decode('Todos dias'), 0, 0, 'R');
		$this->pdf->setXY(170, 28);
		$this->pdf->Cell(4, 4, utf8_decode($work30), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 26);
		$this->pdf->Cell(10, 8, utf8_decode('FinSe'), 0, 0, 'R');
		$this->pdf->setXY(182, 28);
		$this->pdf->Cell(4, 4, utf8_decode($work31), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 26);
		$this->pdf->Cell(10, 8, utf8_decode('Feriad'), 0, 0, 'R');
		$this->pdf->setXY(195, 28);
		$this->pdf->Cell(4, 4, utf8_decode($work32), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 31);
		$this->pdf->Cell(10, 8, utf8_decode('Dia Habil'), 0, 0, 'R');
		$this->pdf->setXY(170, 33);
		$this->pdf->Cell(4, 4, utf8_decode($work33), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 31);
		$this->pdf->Cell(10, 8, utf8_decode('Event'), 0, 0, 'R');
		$this->pdf->setXY(182, 33);
		$this->pdf->Cell(4, 4, utf8_decode($work34), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 31);
		$this->pdf->Cell(10, 8, utf8_decode('Vacaci'), 0, 0, 'R');
		$this->pdf->setXY(195, 33);
		$this->pdf->Cell(4, 4, utf8_decode($work35), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(155, 35);
		$this->pdf->Cell(50, 8, utf8_decode('4.5.4 ¿Recibió algún pago?'), 0, 0, 'L');
		$this->pdf->setXY(152, 39);
		$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
		$this->pdf->setXY(162, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work40), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 39);
		$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
		$this->pdf->setXY(170, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work36), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(172, 39);
		$this->pdf->Cell(10, 8, utf8_decode('Espec'), 0, 0, 'R');
		$this->pdf->setXY(182, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work38), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 39);
		$this->pdf->Cell(10, 8, utf8_decode('Dinero'), 0, 0, 'R');
		$this->pdf->setXY(195, 41);
		$this->pdf->Cell(4, 4, utf8_decode($work39), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 45);
		$this->pdf->Cell(50, 8, utf8_decode('4.6 MEDIO DE TRANSPORTE PARA LLEGAR A LA UNIDAD EDUCATIVA'), 0, 0, 'L');
		$this->pdf->setXY(10, 51);
		$this->pdf->Cell(80, 30, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(10, 49);
		$this->pdf->Cell(10, 8, utf8_decode('4.6.1 ¿Cómo llega el estudiante a UE?'), 0, 0, 'L');
		$this->pdf->setXY(55, 49);
		$this->pdf->Cell(10, 8, utf8_decode('4.6.2 ¿En que tiempo llega?'), 0, 0, 'L');
		$this->pdf->setXY(30, 53);
		$this->pdf->Cell(10, 8, utf8_decode('A pie'), 0, 0, 'R');
		$this->pdf->setXY(40, 55);
		$this->pdf->Cell(4, 4, utf8_decode($transp1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 53);
		$this->pdf->Cell(10, 8, utf8_decode('Menos de Media Hora'), 0, 0, 'R');
		$this->pdf->setXY(85, 55);
		$this->pdf->Cell(4, 4, utf8_decode($transp5), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 58);
		$this->pdf->Cell(10, 8, utf8_decode('En vehículo terrestre'), 0, 0, 'R');
		$this->pdf->setXY(40, 60);
		$this->pdf->Cell(4, 4, utf8_decode($transp2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 58);
		$this->pdf->Cell(10, 8, utf8_decode('Entre media hora y una hora'), 0, 0, 'R');
		$this->pdf->setXY(85, 60);
		$this->pdf->Cell(4, 4, utf8_decode($transp6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 63);
		$this->pdf->Cell(10, 8, utf8_decode('Fluvial'), 0, 0, 'R');
		$this->pdf->setXY(40, 65);
		$this->pdf->Cell(4, 4, utf8_decode($transp3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 63);
		$this->pdf->Cell(10, 8, utf8_decode('Entre una a dos horas'), 0, 0, 'R');
		$this->pdf->setXY(85, 65);
		$this->pdf->Cell(4, 4, utf8_decode($transp7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(30, 68);
		$this->pdf->Cell(10, 8, utf8_decode('Otro (especifique)'), 0, 0, 'R');
		$this->pdf->setXY(40, 70);
		$this->pdf->Cell(4, 4, utf8_decode($transp4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(75, 68);
		$this->pdf->Cell(10, 8, utf8_decode('Más de dos horas'), 0, 0, 'R');
		$this->pdf->setXY(85, 70);
		$this->pdf->Cell(4, 4, utf8_decode($transp8), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(19, 75);
		$this->pdf->Cell(25, 4, utf8_decode($transp->otrollega), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(90, 45);
		$this->pdf->Cell(50, 8, utf8_decode('4.7 ABANDONO ECOLAR CORRESPONDIENTE A LA GESTIÓN ANTERIOR'), 0, 0, 'L');
		$this->pdf->setXY(91, 51);
		$this->pdf->Cell(110, 30, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(91, 49);
		$this->pdf->Cell(15, 8, utf8_decode('4.7.1 ¿El estudiante abandonó la Unidad Educativa el año pasado?'), 0, 0, 'L');
		$this->pdf->setXY(160, 50);
		$this->pdf->Cell(10, 8, utf8_decode('Si'), 0, 0, 'R');
		$this->pdf->setXY(170, 52);
		$this->pdf->Cell(4, 4, utf8_decode($aban1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(170, 50);
		$this->pdf->Cell(10, 8, utf8_decode('No'), 0, 0, 'R');
		$this->pdf->setXY(180, 52);
		$this->pdf->Cell(4, 4, utf8_decode($aban2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(91, 53);
		$this->pdf->Cell(15, 8, utf8_decode('4.7.2 ¿Cuál o cuales fueron las raones de abandono escolar?'), 0, 0, 'L');
		$this->pdf->setXY(115, 57);
		$this->pdf->Cell(10, 8, utf8_decode('Tuvo que ayudar a sus padres'), 0, 0, 'R');
		$this->pdf->setXY(125, 59);
		$this->pdf->Cell(4, 4, utf8_decode($aban3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 57);
		$this->pdf->Cell(10, 8, utf8_decode('Tuvo trabajo renumerado'), 0, 0, 'R');
		$this->pdf->setXY(160, 59);
		$this->pdf->Cell(4, 4, utf8_decode($aban4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 57);
		$this->pdf->Cell(10, 8, utf8_decode('Falta de dinero'), 0, 0, 'R');
		$this->pdf->setXY(195, 59);
		$this->pdf->Cell(4, 4, utf8_decode($aban5), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(115, 62);
		$this->pdf->Cell(10, 8, utf8_decode('Precodidad / Rezago'), 0, 0, 'R');
		$this->pdf->setXY(125, 64);
		$this->pdf->Cell(4, 4, utf8_decode($aban6), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 62);
		$this->pdf->Cell(10, 8, utf8_decode('La UE era distante'), 0, 0, 'R');
		$this->pdf->setXY(160, 64);
		$this->pdf->Cell(4, 4, utf8_decode($aban7), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 62);
		$this->pdf->Cell(10, 8, utf8_decode('Labores de casa'), 0, 0, 'R');
		$this->pdf->setXY(195, 64);
		$this->pdf->Cell(4, 4, utf8_decode($aban8), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(115, 67);
		$this->pdf->Cell(10, 8, utf8_decode('Embarazo o paternidad'), 0, 0, 'R');
		$this->pdf->setXY(125, 69);
		$this->pdf->Cell(4, 4, utf8_decode($aban9), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 67);
		$this->pdf->Cell(10, 8, utf8_decode('Enfermedad/Accid/Discap'), 0, 0, 'R');
		$this->pdf->setXY(160, 69);
		$this->pdf->Cell(4, 4, utf8_decode($aban10), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(185, 67);
		$this->pdf->Cell(10, 8, utf8_decode('Viaje o Traslado'), 0, 0, 'R');
		$this->pdf->setXY(195, 69);
		$this->pdf->Cell(4, 4, utf8_decode($aban11), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(115, 72);
		$this->pdf->Cell(10, 8, utf8_decode('Falta de Interés'), 0, 0, 'R');
		$this->pdf->setXY(125, 74);
		$this->pdf->Cell(4, 4, utf8_decode($aban12), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(150, 72);
		$this->pdf->Cell(10, 8, utf8_decode('Bullying o descriminación'), 0, 0, 'R');
		$this->pdf->setXY(160, 74);
		$this->pdf->Cell(4, 4, utf8_decode($aban13), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(165, 72);
		$this->pdf->Cell(10, 8, utf8_decode('Otra'), 0, 0, 'R');
		$this->pdf->setXY(175, 74);
		$this->pdf->Cell(24, 4, utf8_decode($abandono->otrarazon), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 80);
		$this->pdf->Cell(50, 8, utf8_decode('V. DATOS DEL PADRE, MADRE O TUTOR (A) DE LA O EL ESTUDIANTE'), 0, 0, 'L');
		$this->pdf->setXY(10, 86);
		$this->pdf->Cell(192, 8, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(11, 86);
		$this->pdf->Cell(50, 8, utf8_decode('5.1 LA O EL ESTUDIANTE VIVE HABITUALMENTE CON: '), 0, 0, 'L');

		$this->pdf->setXY(80, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Padre y Madre'), 0, 0, 'R');
		$this->pdf->setXY(90, 88);
		$this->pdf->Cell(4, 4, utf8_decode($vivecon1), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(100, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Solo Padre'), 0, 0, 'R');
		$this->pdf->setXY(110, 88);
		$this->pdf->Cell(4, 4, utf8_decode($vivecon2), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(120, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Solo Madre'), 0, 0, 'R');
		$this->pdf->setXY(130, 88);
		$this->pdf->Cell(4, 4, utf8_decode($vivecon3), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Tutor'), 0, 0, 'R');
		$this->pdf->setXY(150, 88);
		$this->pdf->Cell(4, 4, utf8_decode($vivecon4), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 86);
		$this->pdf->Cell(10, 8, utf8_decode('Solo(a)'), 0, 0, 'R');
		$this->pdf->setXY(170, 88);
		$this->pdf->Cell(4, 4, utf8_decode($vivecon5), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 95);
		$this->pdf->Cell(95, 46, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(11, 94);
		$this->pdf->Cell(50, 8, utf8_decode('5.2 DATOS DEL PADRE: '), 0, 0, 'L');
		$this->pdf->setXY(106, 95);
		$this->pdf->Cell(96, 46, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(107, 94);
		$this->pdf->Cell(50, 8, utf8_decode('5.3 DATOS DE LA MADRE: '), 0, 0, 'L');

		$this->pdf->setXY(10, 98);
		$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
		$this->pdf->setXY(42, 100);
		$this->pdf->Cell(35, 4, utf8_decode($padre->ci), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 100);
		$this->pdf->Cell(10, 4, utf8_decode($padre->complemento), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(93, 100);
		$this->pdf->Cell(10, 4, utf8_decode($padre->extendido), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 103);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
		$this->pdf->setXY(40, 105);
		$this->pdf->Cell(63, 4, utf8_decode($padre->appat), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 108);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
		$this->pdf->setXY(40, 110);
		$this->pdf->Cell(63, 4, utf8_decode($padre->apmat), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 113);
		$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
		$this->pdf->setXY(40, 115);
		$this->pdf->Cell(63, 4, utf8_decode($padre->nombres), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 118);
		$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
		$this->pdf->setXY(40, 120);
		$this->pdf->Cell(63, 4, utf8_decode($padre->idioma), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 123);
		$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
		$this->pdf->setXY(40, 125);
		$this->pdf->Cell(63, 4, utf8_decode($padre->ocupacion), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 128);
		$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
		$this->pdf->setXY(40, 130);
		$this->pdf->Cell(63, 4, utf8_decode($padre->grado), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 133);
		$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
		$this->pdf->setXY(40, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(50, 135);
		$this->pdf->Cell(10, 4, utf8_decode($padre->fnacdia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(60, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(70, 135);
		$this->pdf->Cell(10, 4, utf8_decode($padre->fnacmes), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(90, 135);
		$this->pdf->Cell(13, 4, utf8_decode($padre->fnacanio), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(108, 98);
		$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
		$this->pdf->setXY(140, 100);
		$this->pdf->Cell(35, 4, utf8_decode($madre->ci), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(178, 100);
		$this->pdf->Cell(10, 4, utf8_decode($madre->complemento), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(190, 100);
		$this->pdf->Cell(10, 4, utf8_decode($madre->extendido), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 103);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
		$this->pdf->setXY(137, 105);
		$this->pdf->Cell(63, 4, utf8_decode($madre->appat), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 108);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
		$this->pdf->setXY(137, 110);
		$this->pdf->Cell(63, 4, utf8_decode($madre->apmat), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 113);
		$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
		$this->pdf->setXY(137, 115);
		$this->pdf->Cell(63, 4, utf8_decode($madre->nombres), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 118);
		$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
		$this->pdf->setXY(137, 120);
		$this->pdf->Cell(63, 4, utf8_decode($madre->idioma), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 123);
		$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
		$this->pdf->setXY(137, 125);
		$this->pdf->Cell(63, 4, utf8_decode($madre->ocupacion), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 128);
		$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
		$this->pdf->setXY(137, 130);
		$this->pdf->Cell(63, 4, utf8_decode($madre->grado), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(107, 133);
		$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
		$this->pdf->setXY(140, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(150, 135);
		$this->pdf->Cell(10, 4, utf8_decode($madre->fnacdia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(158, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(168, 135);
		$this->pdf->Cell(10, 4, utf8_decode($madre->fnacmes), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(177, 133);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(187, 135);
		$this->pdf->Cell(13, 4, utf8_decode($madre->fnacanio), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 142);
		$this->pdf->Cell(95, 49, utf8_decode(''), 'TBLR', 0, 'L', '0'); //CUADRO
		$this->pdf->setXY(11, 141);
		$this->pdf->Cell(50, 8, utf8_decode('5.4 DATOS DEL TUTOR: '), 0, 0, 'L');
		$this->pdf->setXY(10, 143);
		$this->pdf->Cell(30, 8, utf8_decode('CI'), 0, 0, 'R');
		$this->pdf->setXY(42, 145);
		$this->pdf->Cell(35, 4, utf8_decode($tutor->ci), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 145);
		$this->pdf->Cell(10, 4, utf8_decode($tutor->complemento), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(93, 145);
		$this->pdf->Cell(10, 4, utf8_decode($tutor->extendido), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 149);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Paterno'), 0, 0, 'R');
		$this->pdf->setXY(40, 150);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->appat), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 154);
		$this->pdf->Cell(30, 8, utf8_decode('Apellido Materno'), 0, 0, 'R');
		$this->pdf->setXY(40, 155);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->apmat), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 158);
		$this->pdf->Cell(30, 8, utf8_decode('Nombre(s)'), 0, 0, 'R');
		$this->pdf->setXY(40, 160);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->nombres), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 163);
		$this->pdf->Cell(30, 8, utf8_decode('Idioma'), 0, 0, 'R');
		$this->pdf->setXY(40, 165);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->idioma), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 168);
		$this->pdf->Cell(30, 8, utf8_decode('Ocupación Laboral'), 0, 0, 'R');
		$this->pdf->setXY(40, 170);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->ocupacion), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 173);
		$this->pdf->Cell(30, 8, utf8_decode('Mayor grado de Instrucción'), 0, 0, 'R');
		$this->pdf->setXY(40, 175);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->grado), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(10, 178);
		$this->pdf->Cell(30, 8, utf8_decode('¿Cuál es el parentesco?'), 0, 0, 'R');
		$this->pdf->setXY(40, 180);
		$this->pdf->Cell(63, 4, utf8_decode($tutor->parentesco), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(10, 183);
		$this->pdf->Cell(30, 8, utf8_decode('Fecha de Nacimiento'), 0, 0, 'R');
		$this->pdf->setXY(40, 183);
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(50, 185);
		$this->pdf->Cell(10, 4, utf8_decode($tutor->fnacdia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(60, 183);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(70, 185);
		$this->pdf->Cell(10, 4, utf8_decode($tutor->fnacmes), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(80, 183);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(90, 185);
		$this->pdf->Cell(13, 4, utf8_decode($tutor->fnacanio), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(100, 145);
		$this->pdf->Cell(20, 8, utf8_decode('Lugar:'), 0, 0, 'R');
		$this->pdf->setXY(125, 147);
		$this->pdf->Cell(30, 4, utf8_decode('CHUQUISACA'), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(102, 153);
		$this->pdf->Cell(20, 8, utf8_decode('Fecha:'), 0, 0, 'R');
		$this->pdf->setXY(120, 153);
		$this->pdf->Cell(10, 8, utf8_decode('(Día)'), 0, 0, 'R');
		$this->pdf->setXY(130, 155);
		$this->pdf->Cell(10, 4, utf8_decode($inscripcion->insdia), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(140, 153);
		$this->pdf->Cell(10, 8, utf8_decode('(Mes)'), 0, 0, 'R');
		$this->pdf->setXY(150, 155);
		$this->pdf->Cell(10, 4, utf8_decode($inscripcion->insmes), 'TBLR', 0, 'L', '0');
		$this->pdf->setXY(160, 153);
		$this->pdf->Cell(10, 8, utf8_decode('(Año)'), 0, 0, 'R');
		$this->pdf->setXY(170, 155);
		$this->pdf->Cell(13, 4, utf8_decode($inscripcion->insanio), 'TBLR', 0, 'L', '0');

		$this->pdf->setXY(110, 180);
		$this->pdf->SetFont('Arial', 'BU', 10);
		$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
		$this->pdf->Ln(6);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->setXY(110, 184);
		$this->pdf->Cell(40, 5, utf8_decode('Firma del Padre/ Madre/ Tutor'), 0, 0, 'C');

		$this->pdf->setXY(160, 180);
		$this->pdf->SetFont('Arial', 'BU', 10);
		$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
		$this->pdf->Ln(6);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->setXY(160, 184);
		$this->pdf->Cell(40, 5, utf8_decode('Firma del Director de la UE'), 0, 0, 'C');





		$this->pdf->Output("Inscrip - RUDE " . $idins . ".pdf", 'I');

		ob_end_flush();
	}

	public function print_ctto($cod)
	{
		//print_r($idins);
		$ids = explode('-', $cod, -1);
		//print_r($ids);
		//exit();
		$gestion = $ids[0];
		$id_est = $ids[1];
		// print_r($ids); 
		// exit();
		$nombre = "";
		$carnet = "";
		$fono = "";
		$contrato = $this->estud->get_data_contrato($gestion, $id_est);
		foreach ($contrato as $contratos) {
			$id_padre = $contratos->id_padre;
			$codigo = $contratos->codigo;
		}

		$padre = $this->estud->get_data_padres($id_padre);

		$nombre = $padre->appaterno . " " . $padre->apmaterno . " " . $padre->nombre;
		$carnet = $padre->ci . " " . $padre->com . " " . $padre->ex;
		$cel = $padre->celular;
		$fono = $padre->telefono;

		$estudiante1 = $this->estud->get_data_estudiante($id_est);
		$estudiante = $estudiante1->appaterno . " " . $estudiante1->apmaterno . " " . $estudiante1->nombre;
		$ci_est = $estudiante1->ci;
		$ext_est = $estudiante1->extension;


		$inscripcion = $this->estud->get_data_inscrips($id_est, $gestion);
		$cod_curso = $inscripcion->curso;
		$cod_nivel = $inscripcion->nivel;
		$fecha = $inscripcion->fecha . "-";
		$fechas = explode('-', $fecha, -1);

		$curso1 = $this->estud->get_data_cursos($cod_curso);
		foreach ($curso1 as $cursos) {
			$curso = $cursos->nombre;
		}
		$nivel1 = $this->estud->get_data_niveles($cod_nivel);
		foreach ($nivel1 as $niveles) {
			$nivel = $niveles->nivel . " " . $niveles->turno;
			$nivels = $niveles->nivel;
			$turno = $niveles->turno;
		}


		$direcion = $this->estud->get_data_direccion($gestion, $id_est);
		foreach ($direcion as $direciones) {
			$calle = $direciones->calle;
			$num = $direciones->nro;
			$zona = $direciones->zona;
		}

		$factura = $this->estud->get_data_factura($gestion, $id_est);
		foreach ($factura as $facturacion) {
			$nitnombre = $facturacion->nombre;
			$nit = $facturacion->nit;
			$nitcorreo = $facturacion->correo;
		}

		$this->load->library('ins_pdf');

		if ($turno == 'MAÑANA') {

			//print_r($nivels); 
			//exit();	
			if (($cod_curso == '5A' | $cod_curso == '5B' | $cod_curso == '5C' | $cod_curso == '6A' | $cod_curso == '6B' | $cod_curso == '6C') & ($nivels == 'SECUNDARIA')) {

				ob_start();
				$this->pdf = new Ins_pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Contrato");


				$this->pdf->SetFont('Arial', 'BU', 8);
				$this->pdf->Cell(30);
				$this->pdf->Cell(135, 8, utf8_decode('CONTRATO DE PRESTACION DE
SERVICIOS EDUCATIVOS
'), 0, 0, 'C');
				// $this->pdf->Image('assets/images/firma.png', 130, 240, 40, 0);
				$this->pdf->Image('assets/images/SELLO.png', 150, 280, 50, 0);
				$this->pdf->setXY(145, 10);
				$this->pdf->SetFont('Arial', '', 7);
				$this->pdf->Cell(50, 8, utf8_decode($codigo), 0, 0, 'R');
				$this->pdf->SetFont('Arial', '', 6);
				$this->pdf->Ln('6');


				$this->pdf->setXY(11, 20);
				$this->pdf->MultiCell(185, 4, utf8_decode('Conste por el presente documento privado, que reconocido tendrá el valor de documento público que le asignan los Art. 519 y 1 .297 del Código Civil, un contrato de prestación de servicios educativos, de acuerdo a las cláusulas siguientes:

				PRIMERA. - DE LAS PARTES CONTRATANTES. - Intervienen en el presente contrato de una parte Sociedad Salesiana – Unidad Educativa Técnica Humanística Don Bosco de la ciudad de Sucre, Autorizado por la Resolución Administrativa U.A.J. N°55-A/2018 SIE N° 80480218, representado legalmente por su Director General el Reverendo Padre Lic. Antonio Gonzalo Tórrez Luque con C.I. 3775284 Cbba. facultado mediante poder N° 666/2021 otorgado ante Notaria Zhenia Jheney Calis Arambulo N° 25, para fines del presente documento en adelante se denominará la UNIDAD EDUCATIVA.

Por otra parte, el Sr. (a)' . $nombre . ' CI' . $carnet . ' en su calidad de padre, madre o apoderado legal del/la estudiante ' . $estudiante . ' del curso ' . $curso . ' NIVEL ' . $nivel . ' TURNO: ' . $turno . ' con domicilio en la calle ' . $calle . ' Nº ' . $num . ' Telf.: ' . $fono . ' y/o celular N° ' . $cel . ' , quien además declara tener los derechos y obligaciones en todos los ámbitos respecto al estudiante, en adelante denominado (a) simplemente el (a) RESPONSABLE.

SEGUNDA.- ANTECEDENTES.- Sociedad Salesiana se constituye en una Asociación Privada de Fieles, sin fines de lucro, perteneciente a la Iglesia Católica, de naturaleza jurídica eclesial con presencia a nivel nacional y tiene por objeto primordial promover el desarrollo integral de las personas bolivianas, sobre todo de los estudiantes a través de obras de carácter educativo. El Ministerio de Educación, como ente rector del sector de la educación en el país, emite en fecha 03 de enero del 2022, la Resolución Ministerial No 001/2022 denominada " SUBSISTEMA DE EDUCACIÓN REGULAR " la cual constituye la base legal de este Contrato de servicios educativos privados.

TERCERA.-DEL OBJETO.- Por el presente contrato de servicios educativos la UNIDAD EDUCATIVA y el RESPONSABLE acuerdan conformar una relación obligacional entre ambas partes, el RESPONSABLE contrata los servicios educativos de carácter privado de la UNIDAD EDUCATIVA; por su parte ésta se compromete a otorgar al estudiante durante la gestión escolar 2022 los servicios de Enseñanza - Aprendizaje, sea en la modalidad a distancia, presencial, semi presencial, siempre y cuando esté establecida y permitida por el Ministerio de Educación, servicio que se desarrollará con parámetros de calidad y exigencia basados en los programas oficiales del Estado Plurinacional de Bolivia y programas complementarios de la institución, aplicando sistemas modernos, manejo adecuado de las Tecnologías de Información y Comunicación, plataformas educativas, teniendo como pilar fundamental la educación cristiana acorde a la moral y las normas de la Iglesia Católica, poniendo en práctica el Sistema Preventivo de Don Bosco, formación en valores morales y respetando ante todo la libertad religiosa y de culto.

CUARTA.- DEL PRECIO Y FORMA DE PAGO DE LOS SERVICIOS EDUCATIVOS, CONSTITUCIÓN DE MORA.- Por la prestación de servicios educativos EL RESPONSABLE se obliga a cancelar a la UNIDAD EDUCATIVA un MONTO TOTAL de Bs. 7.000.- (Siete Mil 00/100 Bolivianos) Por la gestión escolar 2022, que puede ser cancelado en un solo pago al inicio o en 10 cuotas mensuales de Bs. 700.- hasta el día 10 de cada mes de forma anticipada. Los pagos deberán realizarse en la forma que establezca la UNIDAD EDUCATIVA, la cual emitirá la factura correspondiente a nombre del RESPONSABLE firmante de este contrato con N. de NIT/CI: ' . $nit . '

En el caso que las Autoridades competentes determinen porcentajes de incremento a las pensiones escolares, el RESPONSABLE se obliga a cancelar el mismo a la UNIDAD EDUCATIVA de manera retroactiva al primer mes de prestación de servicio educativo.

En el entendido que el Gobierno a través del Ministerio de Educación apruebe el bachillerato técnico – Humanístico, o se añadan otras actividades extracurriculares, el RESPONSABLE se compromete a cubrir el nuevo precio a establecer con los padres de familia por las nuevas materias y actividades.

En el caso que la unidad educativa hubiese prestado los servicios educativos al estudiante y el RESPONSABLE no cumpliese con la obligación de realizar los pagos mensuales, vencido el plazo establecido en la presente cláusula, el RESPONSABLE se constituirá en mora automática y se obligará al cumplimiento total de la deuda en la vía ejecutiva.

Así mismo el RESPONSABLE autoriza a la SOCIEDAD SALESIANA, representada por P. Reinaldo Villazón Chávez, a solicitar, verificar y reportar su información financiera, judicial y comercial registrada en el buró de información crediticia INFOCENTER S.A., mientras dure su relación contractual con SOCIEDAD SALESIANA.

QUINTA.- DE LA DECLARACIÓN ESENCIAL DEL RESPONSABLE.- EL RESPONSABLE, declara conocer el contenido del Reglamento Interno de la Unidad Educativa, así como las disposiciones contempladas en la Ley 070 Avelino Siñani Elizardo Pérez, del Código del Niño (a) y adolescente y de la Resolución Ministerial No 001/2022, en virtud de esta declaración, de su libre y espontánea voluntad, toma y acepta la prestación de los Servicios Educativos de la Unidad Educativa, para la educación del estudiante. En tal sentido admite y acepta que el estudiante pueda ser separado (a) del Unidad Educativa por las causas siguientes:

1.	Desacato e infracción de las normas contempladas en el Reglamento de la Unidad Educativa.
2.	Cometer faltas graves sancionadas con expulsión, de acuerdo a Reglamento y lo establecido por la Resolución Ministerial 001/2022

SEXTA.- DE LA OBLIGACIÓN DEL RESPONSABLE.- Como RESPONSABLE principal de la educación del/la estudiante se compromete a concurrir a la Unidad Educativa las veces que se le cita ya sea de manera presencial o virtual o por lo menos una vez al trimestre, a objeto de informarse sobre el trabajo escolar o tomar debido conocimiento de algunas observaciones que se pudieran hacer sobre la conducta del/la estudiante. Así mismo debe controlar que el/la estudiante no lleve al establecimiento objetos de valor. La Unidad Educativa no se responsabiliza por las eventuales pérdidas de dichos objetos.

SEPTIMA.- DE LA ACEPTACIÓN.- Ambas partes, al momento de firmar el presente contrato, manifiestan su plena conformidad con todas y cada una de las cláusulas precedentes estipuladas.

                                                                                                                      Sucre, ............... de ...................... del 2022

'), 0);
				$this->pdf->setXY(40, 255);
				$this->pdf->SetFont('Arial', 'BU', 10);
				$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
				$this->pdf->Ln(6);
				$this->pdf->SetFont('Arial', 'B', 6);
				$this->pdf->setXY(40, 260);
				$this->pdf->Cell(40, 5, utf8_decode('EL RESPONSABLE'), 0, 0, 'C');
				$this->pdf->setXY(40, 265);
				$this->pdf->Cell(40, 5, utf8_decode('Sr./Sra: ' . $nombre), 0, 0, 'C');
				$this->pdf->setXY(40, 270);
				$this->pdf->Cell(40, 5, utf8_decode('C.I.: ' . $carnet), 0, 0, 'C');

				$this->pdf->setXY(130, 255);
				$this->pdf->SetFont('Arial', 'BU', 10);
				$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
				$this->pdf->Ln(6);
				$this->pdf->SetFont('Arial', 'B', 6);
				$this->pdf->setXY(130, 260);
				$this->pdf->Cell(40, 5, utf8_decode('LA UNIDAD EDUCATIVA'), 0, 0, 'C');
				$this->pdf->setXY(130, 265);
				$this->pdf->Cell(40, 5, utf8_decode('R.P. Lic. Antonio Gonzalo Tórrez Luque'), 0, 0, 'C');
				$this->pdf->setXY(130, 270);
				$this->pdf->Cell(40, 5, utf8_decode('C.I. 3775284 Cbba. '), 0, 0, 'C');

				$this->pdf->SetFont('Arial', '', 6);
				$this->pdf->setXY(15, 270);
				// $this->pdf->Cell(30,5,'Fecha de Impresion: '.date('Y-m-d'),0,0,'L');

				$this->pdf->Output("Inscrip - " . $codigo . ".pdf", 'I');

				ob_end_flush();
			} else {
				ob_start();
				$this->pdf = new Ins_pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Contrato");


				$this->pdf->SetFont('Arial', 'BU', 8);
				$this->pdf->Cell(30);

				//$this->pdf->Image('assets/images/firma.png', 130, 240, 40, 0);
				$this->pdf->Image('assets/images/SELLO.png', 150, 280, 50, 0);
				$this->pdf->Cell(135, 8, utf8_decode('CONTRATO DE PRESTACION DE
SERVICIOS EDUCATIVOS
'), 0, 0, 'C');
				$this->pdf->setXY(145, 10);
				$this->pdf->SetFont('Arial', '', 7);
				$this->pdf->Cell(50, 8, utf8_decode($codigo), 0, 0, 'R');

				$this->pdf->SetFont('Arial', '', 6);
				$this->pdf->Ln('6');
				//$this->Image('assets/images/logo.png',20,4,17,0);

				$this->pdf->setXY(11, 20);
				$this->pdf->MultiCell(185, 4, utf8_decode('Conste por el presente documento privado, que reconocido tendrá el valor de documento público que le asignan los Art. 519 y 1 .297 del Código Civil, un contrato de prestación de servicios educativos, de acuerdo a las cláusulas siguientes: 

PRIMERA. - DE LAS PARTES CONTRATANTES. - Intervienen en el presente contrato de una parte Sociedad Salesiana – Unidad Educativa Técnica Humanística Don Bosco de la ciudad de Sucre, Autorizado por la Resolución Administrativa U.A.J. N°55-A/2018 SIE N° 80480218, representado legalmente por su Director General el Reverendo Padre Lic. Antonio Gonzalo Tórrez Luque con C.I. 3775284 Cbba. facultado mediante poder N° 666/2021 otorgado ante Notaria Zhenia Jheney Calis Arambulo N° 25, para fines del presente documento en adelante se denominará la UNIDAD EDUCATIVA.

Por otra parte, el Sr. (a) ' . $nombre . ' CI ' . $carnet . ' en su calidad de padre, madre o apoderado legal del/la estudiante ' . $estudiante . ' del curso ' . $curso . ' NIVEL ' . $nivel . '  TURNO: ' . $turno . ' con domicilio en la calle ' . $calle . ' Nº ' . $num . ' Telf.: ' . $fono . ' y/o celular N° ' . $cel . ', quien además declara tener los derechos y obligaciones en todos los ámbitos respecto al estudiante, en adelante denominado (a) simplemente el (a) RESPONSABLE.

SEGUNDA.- ANTECEDENTES.- Sociedad Salesiana se constituye en una Asociación Privada de Fieles, sin fines de lucro, perteneciente a la Iglesia Católica, de naturaleza jurídica eclesial con presencia a nivel nacional y tiene por objeto primordial promover el desarrollo integral de las personas bolivianas, sobre todo de los estudiantes a través de obras de carácter educativo. El Ministerio de Educación, como ente rector del sector de la educación en el país, emite en fecha 03 de enero del 2022, la Resolución Ministerial No 001/2022 denominada " SUBSISTEMA DE EDUCACIÓN REGULAR " la cual constituye la base legal de este Contrato de servicios educativos privados.

TERCERA.-DEL OBJETO.- Por el presente contrato de servicios educativos la UNIDAD EDUCATIVA y el RESPONSABLE acuerdan conformar una relación obligacional entre ambas partes, el RESPONSABLE contrata los servicios educativos de carácter privado de la UNIDAD EDUCATIVA; por su parte ésta se compromete a otorgar al estudiante durante la gestión escolar 2022 los servicios de Enseñanza - Aprendizaje, sea en la modalidad a distancia, presencial, semi presencial, siempre y cuando esté establecida y permitida por el Ministerio de Educación, servicio que se desarrollará con parámetros de calidad y exigencia basados en los programas oficiales del Estado Plurinacional de Bolivia y programas complementarios de la institución, aplicando sistemas modernos, manejo adecuado de las Tecnologías de Información y Comunicación, plataformas educativas, teniendo como pilar fundamental la educación cristiana acorde a la moral y las normas de la Iglesia Católica, poniendo en práctica el Sistema Preventivo de Don Bosco, formación en valores morales y respetando ante todo la libertad religiosa y de culto.

CUARTA.- DEL PRECIO Y FORMA DE PAGO DE LOS SERVICIOS EDUCATIVOS, CONSTITUCIÓN DE MORA.- Por la prestación de servicios educativos EL RESPONSABLE se obliga a cancelar a la UNIDAD EDUCATIVA un MONTO TOTAL de Bs. 6.915.- (Seis mil novecientos quince 00/100 Bolivianos) Por la gestión escolar 2022, que puede ser cancelado en un solo pago al inicio o en 10 cuotas mensuales de Bs. 691,50.- hasta el día 10 de cada mes de forma anticipada. Los pagos deberán realizarse en la forma que establezca la UNIDAD EDUCATIVA, la cual emitirá la factura correspondiente a nombre del RESPONSABLE firmante de este contrato con N. de NIT/CI: ' . $nit . '

En el caso que las Autoridades competentes determinen porcentajes de incremento a las pensiones escolares, el RESPONSABLE se obliga a cancelar el mismo a la UNIDAD EDUCATIVA de manera retroactiva al primer mes de prestación de servicio educativo.

En el entendido que el Gobierno a través del Ministerio de Educación apruebe el bachillerato técnico – Humanístico, o se añadan otras actividades extracurriculares, el RESPONSABLE se compromete a cubrir el nuevo precio a establecer con los padres de familia por las nuevas materias y actividades.

En el caso que la unidad educativa hubiese prestado los servicios educativos al estudiante y el RESPONSABLE no cumpliese con la obligación de realizar los pagos mensuales, vencido el plazo establecido en la presente cláusula, el RESPONSABLE se constituirá en mora automática y se obligará al cumplimiento total de la deuda en la vía ejecutiva. 

Así mismo el RESPONSABLE autoriza a la SOCIEDAD SALESIANA, representada por P. Reinaldo Villazón Chávez, a solicitar, verificar y reportar su información financiera, judicial y comercial registrada en el buró de información crediticia INFOCENTER S.A., mientras dure su relación contractual con SOCIEDAD SALESIANA.

QUINTA.- DE LA DECLARACIÓN ESENCIAL DEL RESPONSABLE.- EL RESPONSABLE, declara conocer el contenido del Reglamento Interno de la Unidad Educativa, así como las disposiciones contempladas en la Ley 070 Avelino Siñani Elizardo Pérez, del Código del Niño (a) y adolescente y de la Resolución Ministerial No 001/2022, en virtud de esta declaración, de su libre y espontánea voluntad, toma y acepta la prestación de los Servicios Educativos de la Unidad Educativa, para la educación del estudiante. En tal sentido admite y acepta que el estudiante pueda ser separado (a) del Unidad Educativa por las causas siguientes:

1.	Desacato e infracción de las normas contempladas en el Reglamento de la Unidad Educativa.  
2.	Cometer faltas graves sancionadas con expulsión, de acuerdo a Reglamento y lo establecido por la Resolución Ministerial 001/2022

SEXTA.- DE LA OBLIGACIÓN DEL RESPONSABLE.- Como RESPONSABLE principal de la educación del/la estudiante se compromete a concurrir a la Unidad Educativa las veces que se le cita ya sea de manera presencial o virtual o por lo menos una vez al trimestre, a objeto de informarse sobre el trabajo escolar o tomar debido conocimiento de algunas observaciones que se pudieran hacer sobre la conducta del/la estudiante. Así mismo debe controlar que el/la estudiante no lleve al establecimiento objetos de valor. La Unidad Educativa no se responsabiliza por las eventuales pérdidas de dichos objetos.

SEPTIMA.- DE LA ACEPTACIÓN.- Ambas partes, al momento de firmar el presente contrato, manifiestan su plena conformidad con todas y cada una de las cláusulas precedentes estipuladas. 


                                                                                                                    Sucre, ............... de ...................... del 2022

'), 0);
				$this->pdf->setXY(40, 255);
				$this->pdf->SetFont('Arial', 'BU', 10);
				$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
				$this->pdf->Ln(6);
				$this->pdf->SetFont('Arial', 'B', 6);
				$this->pdf->setXY(40, 260);
				$this->pdf->Cell(40, 5, utf8_decode('EL RESPONSABLE'), 0, 0, 'C');
				$this->pdf->setXY(40, 265);
				$this->pdf->Cell(40, 5, utf8_decode('Sr./Sra: ' . $nombre), 0, 0, 'C');
				$this->pdf->setXY(40, 270);
				$this->pdf->Cell(40, 5, utf8_decode('C.I: ' . $carnet), 0, 0, 'C');

				$this->pdf->setXY(130, 255);
				$this->pdf->SetFont('Arial', 'BU', 10);
				$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
				$this->pdf->Ln(6);
				$this->pdf->SetFont('Arial', 'B', 6);
				$this->pdf->setXY(130, 260);
				$this->pdf->Cell(40, 5, utf8_decode('LA UNIDAD EDUCATIVA'), 0, 0, 'C');
				$this->pdf->setXY(130, 265);
				$this->pdf->Cell(40, 5, utf8_decode('R.P. Lic. Antonio Gonzalo Tórrez Luque'), 0, 0, 'C');
				$this->pdf->setXY(130, 270);
				$this->pdf->Cell(40, 5, utf8_decode('C.I. 3775284 Cbba. '), 0, 0, 'C');

				$this->pdf->SetFont('Arial', '', 6);
				$this->pdf->setXY(15, 270);
				// $this->pdf->Cell(30,5,'Fecha de Impresion: '.date('Y-m-d'),0,0,'L');

				$this->pdf->Output("Inscrip - " . $codigo . ".pdf", 'I');

				ob_end_flush();
			}
		}


		if ($turno == 'TARDE') {
			ob_start();
			$this->pdf = new Ins_pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Contrato");

			// $this->pdf->Image('assets/images/firma.png', 130, 240, 40, 0);
			$this->pdf->Image('assets/images/SELLO.png', 150, 280, 50, 0);
			$this->pdf->SetFont('Arial', 'BU', 8);
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('MANIFESTACIÓN DE VOLUNTADES Nº ' . $codigo . '/2022'), 0, 0, 'C');
			$this->pdf->Ln('4');
			$this->pdf->Cell(180, 8, utf8_decode('UNIDAD EDUCATIVA TÉCNICO HUMANÍSTICO DON BOSCO A-B'), 0, 0, 'C');
			$this->pdf->setXY(140, 14);
			$this->pdf->SetFont('Arial', '', 7);

			$this->pdf->setXY(11, 20);

			$this->pdf->MultiCell(180, 4, utf8_decode('Conste por el presente documento de manifestación de voluntades, que establecen acuerdos voluntarios para llevar adelante la gestión escolar 2022 de la UNIDAD EDUCATIVA TÉCNICO HUMANÍSTICO DON BOSCO A-B turno tarde, de acuerdo a las cláusulas siguientes:

PRIMERA.- DE LAS PARTES CONSENSUANTES.- Intervienen en el presente convenio por una parte UNIDAD EDUCATIVA TÉCNICO HUMANÍSTICO DON BOSCO A-B,  de la ciudad de Sucre, administrada por el Instituto Religioso Sociedad San Francisco de Sales (SFS), con  Resolución Administrativa D.D.E.CH.-U.A.J. No 055-B/2020 Y 642/2012   SIE 80480192 - 80480261, representada legalmente por su Director General el Padre Lic. Antonio Gonzalo Tórrez Luque sdb, con C.I. 3775284 Exp. En Cochabamba, facultado mediante poder Nº 665/2021, otorgado ante Notaria N° 25 Dra. Zhenia Jheney Celis Arambulo, para fines del presente documento en adelante se denominará LA UNIDAD EDUCATIVA.

Por otra parte, el Sr. (a) ' . $nombre . ' CI ' . $carnet . ' exp. En en su calidad de padre, madre o apoderado del/la  estudiante ' . $estudiante . ' con CI Nº  ' . $ci_est . ' Exp ' . $ext_est . ' del curso ' . $curso . ' NIVEL ' . $nivel . '  TURNO: ' . $turno . ', con domicilio en la calle ' . $calle . ' Nº ' . $num . ' Telf.: ' . $fono . ' y/o celular N° ' . $cel . ', quien además declara contener los derechos y obligaciones en todos los ámbitos respecto al estudiante en adelante denominado (a) simplemente  el (a) RESPONSABLE.

SEGUNDA.- ANTECEDENTES.- Por el Convenio Marco suscrito entre Iglesia - Estado de fecha 28 de noviembre de 2011 y ampliado en vigencia del Convenio Sectorial de Cooperación Interinstitucional en el ámbito de la educación suscrito entre la Iglesia Católica en Bolivia y el Ministerio de Educación de Bolivia el 21 de julio de 2016 en el que se garantiza y respeta la administración, dirección y evaluación que la Iglesia lleva sobre sus obras educativas de Convenio, la Congregación SFS a través de LA UNIDAD EDUCATIVA de Sucre, tiene por objeto primordial promover el desarrollo integral de las personas, sobre todo de los jóvenes a través de obras de carácter educativo.
Considerando la vigencia del convenio de cooperación institucional entre la Sociedad San Francisco de Sales y la Sociedad Salesiana por la que ésta última concede el uso gratuito de sus activos fijos para el funcionamiento de la Unidad Educativa.
El Ministerio de Educación, como ente rector del sector de la educación en el país, emite en fecha 03 de enero del 2022, la Resolución Ministerial No 001/2022 denominada SUBSISTEMA DE EDUCACIÓN REGULAR la cual constituye la base legal del presente Convenio.

TERCERA.-DEL OBJETO.- Por el presente documento ambas partes  UNIDAD EDUCATIVA y RESPONSABLE  acuerdan  que LA UNIDAD EDUCATIVA  se compromete a otorgar  al estudiante durante la gestión escolar 2022 los servicios de Enseñanza - Aprendizaje, sea en la modalidad a distancia, presencial, semi presencial, siempre y cuando esté establecida y permitida por el Ministerio de Educación, basados en los programas oficiales del Estado Plurinacional de Bolivia y programas complementarios de la institución, aplicando sistemas modernos, manejo adecuado de las Tecnologías de Información y Comunicación, plataformas educativas, teniendo como pilar fundamental la educación cristiana acorde a la moral y las normas de la Iglesia Católica, poniendo en práctica el Sistema Preventivo de Don Bosco, formación en valores morales y respetando ante todo la libertad religiosa y de culto.

Por su parte el RESPONSABLE reconociendo la labor que realiza LA UNIDAD EDUCATIVA se compromete de voluntad propia sin que medie presión alguna a realizar un aporte económico voluntario aprobado en Asamblea General, que responda a las necesidades y requerimientos indispensables de un proyecto para la Unidad Educativa de Convenio, por otra parte, de manera expresa delega la administración de los aportes económicos al Director General como representante de la SFS.

CUARTA.- DE LA DECLARACIÓN ESENCIAL  DEL RESPONSABLE.- EL RESPONSABLE, declara conocer el contenido y se obliga a cumplir con el Reglamento de LA UNIDAD EDUCATIVA, así como las disposiciones  contempladas en la Ley 070 Avelino Siñani Elizardo Pérez y del Código del Niño (a) y adolescente, Resolución Ministerial N° 001/2022 de fecha 3 de enero del 2022, Protocolo de Bioseguridad de Retorno a Clases. En virtud de esta declaración, de su libre y espontánea voluntad, acepta los lineamientos establecidos por LA UNIDAD EDUCATIVA, para la educación del /la Estudiante. En tal sentido admite y acepta que el /la Estudiante pueda ser separado (a) de LA UNIDAD EDUCATIVA por cometer faltas graves sancionadas con expulsión, de acuerdo a lo establecido por la Resolución Ministerial N° 001/2022
Tanto la UNIDAD EDUCATIVA como el RESPONSABLE reconocer y aceptar que la naturaleza y el espíritu de la educación de convenio Salesiana es ofrecer educación de calidad a las familias de más escasos recursos. En este sentido, ambos se comprometen a garantizar y aceptar las políticas institucionales encaminadas a garantizar la atención a los destinatarios privilegiados de la misión salesiana, esto es los niños y jóvenes más pobres.

QUINTA.- DE LA OBLIGACIÓN DEL RESPONSABLE.- Como RESPONSABLE principal de la educación del estudiante se compromete a concurrir a la UNIDAD EDUCATIVA las veces que se le cite y dar seguimiento a la avance académico del Estudiante, a objeto de informarse sobre el trabajo escolar o tomar debido conocimiento de algunas observaciones que se pudieran hacer sobre la conducta del estudiante. Así mismo debe controlar que éste último  no lleve al establecimiento objetos de valor. LA UNIDAD EDUCATIVA no se responsabiliza por las eventuales pérdidas de dichos objetos.

SEXTA.- DE LA ACEPTACIÓN.-  Ambas partes, al momento de firmar el presente convenio, manifiestan su plena conformidad con todas y cada una de las cláusulas precedentemente estipuladas.


                                                                                                                      Sucre, ............... de ...................... del 2022

'), 0);
			$this->pdf->setXY(40, 255);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 6);
			$this->pdf->setXY(40, 260);
			$this->pdf->Cell(40, 5, utf8_decode('EL RESPONSABLE'), 0, 0, 'C');
			$this->pdf->setXY(40, 265);
			$this->pdf->Cell(40, 5, utf8_decode('Sr./Sra: ' . $nombre), 0, 0, 'C');
			$this->pdf->setXY(40, 270);
			$this->pdf->Cell(40, 5, utf8_decode('C.I: ' . $carnet), 0, 0, 'C');

			$this->pdf->setXY(130, 255);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 6);
			$this->pdf->setXY(130, 260);
			$this->pdf->Cell(40, 5, utf8_decode('LA UNIDAD EDUCATIVA'), 0, 0, 'C');
			$this->pdf->setXY(130, 265);
			$this->pdf->Cell(40, 5, utf8_decode('R.P. Lic. Antonio Gonzalo Tórrez Luque'), 0, 0, 'C');
			$this->pdf->setXY(130, 270);
			$this->pdf->Cell(40, 5, utf8_decode('C.I. 3775284 Cbba. '), 0, 0, 'C');

			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(15, 270);



			$this->pdf->Output("Inscrip - " . $codigo . ".pdf", 'I');

			ob_end_flush();
		}
	}
	public function print_ctto7($idins)
	{
		//print_r($idins);
		$nombre = "";
		$carnet = "";
		$fono = "";
		$inscripcion = $this->estud->get_data_inscrip($idins);
		$estudiante = $this->estud->get_data_estudiantes($inscripcion->idest);
		$padre = $this->estud->get_data_padre($idins);
		$madre = $this->estud->get_data_madre($idins);
		$tutor = $this->estud->get_data_tutor($idins);
		$local = $this->estud->get_data_local($idins);
		if ($estudiante->vivecon == "Padre y Madre") {
			$nombre = $padre->appat . " " . $padre->apmat . " " . $padre->nombres;
			$carnet = $padre->ci . " " . $padre->complemento . " " . $padre->extendido;
			$fono = $padre->celular;
		}
		if ($estudiante->vivecon == "Solo Madre") {
			$nombre = $madre->appat . " " . $madre->apmat . " " . $madre->nombres;
			$carnet = $madre->ci . " " . $madre->complemento . " " . $madre->extendido;
			$fono = $madre->celular;
		}
		if ($estudiante->vivecon == "Tutor") {
			$nombre = $tutor->appat . " " . $tutor->apmat . " " . $tutor->nombres;
			$carnet = $tutor->ci . " " . $tutor->complemento . " " . $tutor->extendido;
			$fono = $tutor->celular;
		}
		if ($estudiante->vivecon == "Solo Padre") {
			$nombre = $padre->appat . " " . $padre->apmat . " " . $padre->nombres;
			$carnet = $padre->ci . " " . $padre->complemento . " " . $padre->extendido;
			$fono = $padre->celular;
		}

		$estudiante = $estudiante->appaterno . " " . $estudiante->apmaterno . " " . $estudiante->nombres;
		//$carnet2=$estudiante->ci." ".$estudiante->complemento." ".$estudiante->extendido;
		$curso = $inscripcion->curso;
		$nivel = $inscripcion->nivel;
		$calle = $local->calle;
		$num = $local->num;
		$zona = $local->zona;
		//$carnet2=$estudiante->ci." ".$estudiante->complemento." ".$estudiante->extendido;




		$this->load->library('ins_pdf');

		if ($inscripcion->turno == 'MAÑANA') {

			ob_start();
			$this->pdf = new Ins_pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Contrato");


			$this->pdf->SetFont('Arial', 'BU', 8);
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('CONTRATO PRIVADO DE SERVICIO EDUCATIVO 2019'), 0, 0, 'C');
			$this->pdf->setXY(145, 10);
			$this->pdf->SetFont('Arial', '', 7);
			$this->pdf->Cell(50, 8, utf8_decode($inscripcion->numctto . "/" . $inscripcion->gestion), 0, 0, 'R');
			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(60, 18);
			$this->pdf->Cell(50, 8, utf8_decode($nombre), 0, 0, 'L');
			$this->pdf->setXY(145, 18);
			$this->pdf->Cell(50, 8, utf8_decode($carnet), 0, 0, 'R');
			$this->pdf->setXY(59, 22);
			$this->pdf->Cell(50, 8, utf8_decode($estudiante), 0, 0, 'R');
			$this->pdf->setXY(84, 22);
			$this->pdf->Cell(50, 8, utf8_decode($curso), 0, 0, 'R');
			$this->pdf->setXY(113, 22);
			$this->pdf->SetFont('Arial', '', 5);
			$this->pdf->Cell(50, 8, utf8_decode($nivel), 0, 0, 'R');
			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(12, 26);
			$this->pdf->Cell(50, 8, utf8_decode($calle), 0, 0, 'L');
			$this->pdf->setXY(51, 26);
			$this->pdf->Cell(50, 8, utf8_decode($num), 0, 0, 'L');
			$this->pdf->setXY(70, 26);
			$this->pdf->Cell(50, 8, utf8_decode($zona), 0, 0, 'L');
			$this->pdf->setXY(115, 26);
			$this->pdf->Cell(30, 8, utf8_decode($fono), 0, 0, 'L');
			$this->pdf->setXY(65, 218);
			$this->pdf->Cell(30, 8, utf8_decode($inscripcion->insdia), 0, 0, 'L');
			$this->pdf->setXY(90, 218);
			$this->pdf->Cell(30, 8, utf8_decode($inscripcion->insmes), 0, 0, 'L');

			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->Ln('6');

			$this->pdf->setXY(11, 16);
			$this->pdf->MultiCell(185, 4, utf8_decode('Conste por el presente documento privado que podrá ser elevado a instrumento público a sólo requerimiento de partes y reconocimiento de firmas, que suscribimos de conformidad al Artículo 519 y 1219 del Código Civil, entre el señor(a) ................................................................................ , mayor de edad, hábil por derecho con carnét de identidad Nro ............................, PADRE/MADRE o APODERADO(A) del estudiante .......................................................................... del curso ................. del nivel .............................., domiciliado en la calle/avenida .......................................................... Nro ........... de la zona ................................................... con teléfono Nro ..........................por una parte.
Por otra parte Sociedad Salesiana – Unidad Educativa Técnico Humanístico Don Bosco de la ciudad de Sucre, Autorizado por la Resolución Administrativa No 640/2012 SIE Nº 8048128, representado legalmente por su Director General el Padre Alberto Vásquez Cáceres con C.I. 3122876 Cbba. quien cuenta con las facultades suficientes de acuerdo al poder adjunto al presente contrato, que como partes intervinientes del presente documento contractual y para interpretación, se denominaran como, PADRE DE FAMILIA y UNIDAD EDUCATIVA respectivamente, al tenor de las siguientes cláusulas:
PRIMERA. Por el presente contrato de servicios educativos la UNIDAD EDUCATIVA y el PADRE DE FAMILIA acuerdan conformar una relación obligacional entre ambas partes, el PADRE DE FAMILIA contrata el servicio educativo de carácter privado de la UNIDAD EDUCATIVA, por su parte ésta se compromete a otorgar al estudiante durante la gestión escolar 2019 los servicios de Enseñanza - Aprendizaje, de calidad y eficiencia basados en los programas oficiales del Estado Plurinacional de Bolivia y aplicando sistemas modernos, teniendo como pilar fundamental la educación cristiana acorde a la moral y las normas de la Iglesia Católica, poniendo en práctica el Sistema Preventivo de Don Bosco. 
SEGUNDA. Yo, PADRE DE FAMILIA declaro conocer que la UNIDAD EDUCATIVA , es una asociación Privada de Fieles sin fines de lucro,  perteneciente a la IGLESIA CATOLICA , declaro conocer también el Reglamento Interno que contiene la VISIÓN, MISIÓN, OBJETIVOS Y FINALIDAD de la institución educativa. Conozco también que en los casos de infracciones se aplicará el régimen disciplinario vigente y que las faltas que constituyen delitos serán derivados ante las instancias públicas competentes
TERCERA. Yo, PADRE DE FAMILIA acepto la Prohibición del uso de Celulares, Equipos de Sonido y otros dentro de la Unidad Educativa para fines diferentes al académico, siendo el caso de necesidad de uso de estos aparatos deberán ser autorizados por personal Docente y Dirección, de acuerdo al Art.120 de la Resolución Ministerial 01/2019, en caso de infringir mi hijo esta norma, conozco y acepto que será pasible a sanciones de acuerdo a Reglamento, además acepto que la Unidad Educativa NO se responsabiliza por la pérdida o sustracción de los mismos.  De igual forma conozco y acepto que la mochila, bolso u otro que mi hijo(a) porta al ingresar a la Unidad Educativa, es un instrumento que coadyuva en el trabajo educativo, por lo que en caso de ser necesario la Dirección del Colegio podrá pedir la revisión del mismo, en presencia de mi hijo y de su profesor guía, sin que esto signifique violación a la intimidad del alumno. (Señor PADRE DE FAMILIA se le ruega tomar debida nota de esta Cláusula). De igual forma conozco que es una falta grave hacer un mal uso de grupos creados por redes sociales por los estudiantes, padres de familia y maestros como medio de comunicación escolar que tenga un efecto negativo en la comunidad educativa. 
CUARTA. Yo, PADRE DE FAMILIA como principal responsable de la educación de mi hijo(a), me comprometo a concurrir a la Unidad Educativa cuantas veces sea necesario a entrevistas con la Directora y/o Profesores(as), a fin de informarme sobre el trabajo escolar, recoger y firmar el boletín y libreta en las fechas indicadas, tomar debida nota de las observaciones disciplinarias y de aprovechamiento de mi hijo(a). Es de mi conocimiento también que de acuerdo a lo establecido por el Reglamento de evaluación de Desarrollo Curricular, mi persona tiene responsabilidades de apoyo y seguimiento permanente y continuo en las dificultades que presentara mi hijo. Es de mi conocimiento también que las reuniones con validez en determinaciones e información son las convocadas por Dirección o con autorización de ésta. 
QUINTA. Yo, PADRE DE FAMILIA me comprometo a cancelar un COSTO ANUAL Bs. 6.177,6 pagaderos en  10 (diez) Mensualidades cada una de Bs.617,76 (Seis cientos diecisiete con 76/100 bolivianos), de febrero a noviembre, hasta el diez de cada mes en curso, en forma puntual, sin mora ni excusa alguna, cancelando la primera Mensualidad en el periodo de Inscripciones, sin perjuicio de que el monto anual sea pagado en un solo pago, en caso de interrupción o suspensión del año escolar por factores ajenos a la Unidad Educativa, me obligo, de la misma manera, a cubrir la totalidad de las cuotas pactadas. Tengo conocimiento y acepto que en caso de incumplimiento de mensualidades, el colegio publicará mi nombre en listas de morosos en lugar público del establecimiento, como primera medida de coacción para cumplir mi obligación.
Los pagos deberán realizarse a la Cuenta Nº 4010765389 del Banco Mercantil Santa Cruz S.A. por los pagos recibidos la UNIDAD EDUCATIVA emitirá las facturas correspondientes por los pagos recibidos, a través del Banco.
En el caso que el Gobierno Central determine porcentajes de incremento a las pensiones escolares, el PADRE DE FAMILIA se obliga a cancelar el mismo a la UNIDAD EDUCATIVA de manera retroactiva al primer mes de prestación del Servicio Educativo.
En el entendido que el Gobierno a través del Ministerio de Educación apruebe el bachillerato técnico – Humanístico, o se añadan otras actividades extracurriculares, el PADRE DE FAMILIA se compromete a cubrir el nuevo costo a establecer con los padres de familia por las nuevas materias y actividades.
SEXTA. En caso que a la finalización de la Gestión Educativa 2019 el PADRE DE FAMILIA no hubiera pagado en su totalidad las Mensualidades, a más de aplicar lo establecido en el Art.92 (inc. I) y Art.95 de la Resolución Ministerial Nº 01/2019, las PARTES aceptan expresamente que, al ser el presente contrato de naturaleza civil, se constituirá  en título ejecutivo suficiente para el cobro de los montos pendientes de pago por la vía ejecutiva, ante la existencia de suma líquida, exigible y plazo vencido, sobre la base de los montos adeudados con mantenimiento de valor más el interés legal aplicable conforme la Legislación Civil.
SEPTIMA. Yo PADRE DE FAMILIA me comprometo a apoyar y cumplir con las actividades propias de la Unidad Educativa que ayuden a la formación integral de mi Hijo(a) (Viaje de Misión), en todos los niveles y en las fechas que se establezca en la Planificación Institucional.
OCTAVA. Yo, PADRE DE FAMILIA, garantizo un normal desarrollo de Labores, con excepción de las interrupciones que puedan ser provocadas por causas de pública notoriedad; del mismo modo una educación basada en sistemas modernos y el respeto de la persona y dignidad del educando.
NOVENA. El presente contrato quedará resuelto de pleno derecho en el momento que el PADRE DE FAMILIA de manera voluntaria determine retirar a su hijo (a) o pupilo (a) de la Unidad Educativa, antes de la finalización de la gestión escolar regular, esta determinación deberá comunicarla por escrito mediante carta dirigida al Director General de la Unidad Educativa con 5 días de anticipación, acción que procederá previo cumplimiento de sus obligaciones económicas contractuales, incluyendo el mes que el PADRE DE FAMILIA toma la determinación de retirar a su hijo (a) o pupilo (a). Caso contrario la Unidad Educativa tendrá facultades para exigir el resarcimiento de los daños y perjuicios ocasionados.
DÉCIMA. Nosotros PADRE DE FAMILIA y UNIDAD EDUCATIVA, manifestamos nuestra absoluta conformidad con todas y cada una de las cláusulas precedentes, por lo que suscribimos para su fiel y estricto cumplimiento, en la ciudad de Sucre a los ............ días del mes  ....................... del año 2019.
'), 0);
			$this->pdf->setXY(40, 255);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setXY(40, 260);
			$this->pdf->Cell(40, 5, utf8_decode('Firma del Padre/ Madre/ Tutor'), 0, 0, 'C');
			$this->pdf->setXY(40, 265);
			$this->pdf->Cell(40, 5, utf8_decode('C.I.................................. '), 0, 0, 'C');

			$this->pdf->setXY(130, 255);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setXY(130, 260);
			$this->pdf->Cell(40, 5, utf8_decode('R.P. Alberto Vásquez Cáceres'), 0, 0, 'C');
			$this->pdf->setXY(130, 265);
			$this->pdf->Cell(40, 5, utf8_decode('C.I. 3122876 Cbba. '), 0, 0, 'C');

			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(15, 270);
			$this->pdf->Cell(30, 5, 'Fecha de Impresion: ' . date('Y-m-d'), 0, 0, 'L');

			$this->pdf->Output("Inscrip - CTTO " . $idins . ".pdf", 'I');

			ob_end_flush();
		}


		if ($inscripcion->turno == 'TARDE') {
			ob_start();
			$this->pdf = new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Contrato");
			$this->pdf->SetFont('Arial', 'BU', 8);
			$this->pdf->Cell(30);
			$this->pdf->Cell(135, 8, utf8_decode('MANIFESTACIÓN DE VOLUNTADES'), 0, 0, 'C');
			$this->pdf->Ln('4');
			$this->pdf->Cell(180, 8, utf8_decode('UNIDAD EDUCATIVA DE CONVENIO TÉCNICO HUMANÍSTICO DON BOSCO A - B  EPDB'), 0, 0, 'C');
			$this->pdf->setXY(140, 14);
			$this->pdf->SetFont('Arial', '', 7);
			$this->pdf->Cell(50, 8, utf8_decode($inscripcion->numctto . "/" . $inscripcion->gestion), 0, 0, 'R');
			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(44, 42);
			$this->pdf->Cell(50, 8, utf8_decode($nombre), 0, 0, 'L');
			$this->pdf->setXY(67, 42);
			$this->pdf->Cell(50, 8, utf8_decode($carnet), 0, 0, 'R');

			$this->pdf->setXY(22, 44);
			$this->pdf->Cell(52, 10, utf8_decode($estudiante), 0, 0, 'L');
			$this->pdf->setXY(74, 45);
			$this->pdf->Cell(50, 8, utf8_decode($curso), 0, 0, 'R');
			$this->pdf->setXY(113, 45);
			$this->pdf->SetFont('Arial', '', 5);
			$this->pdf->Cell(50, 8, utf8_decode($nivel), 0, 0, 'R');
			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(55, 49);
			$this->pdf->Cell(50, 8, utf8_decode($calle), 0, 0, 'L');
			$this->pdf->setXY(85, 50);
			$this->pdf->Cell(50, 8, utf8_decode($num), 0, 0, 'L');
			$this->pdf->setXY(20, 50);
			$this->pdf->Cell(50, 8, utf8_decode($zona), 0, 0, 'L');
			$this->pdf->setXY(94, 50);
			$this->pdf->Cell(25, 8, utf8_decode($fono), 0, 0, 'L');
			//$this->pdf->setXY(72,230);
			//$this->pdf->Cell(30,8,utf8_decode($inscripcion->insdia),0,0,'L');
			//$this->pdf->setXY(100,230);
			//$this->pdf->Cell(30,8,utf8_decode($inscripcion->insmes),0,0,'L');

			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->Ln('6');

			$this->pdf->setXY(11, 20);

			$this->pdf->MultiCell(180, 4, utf8_decode('Conste por el presente documento de manifestación de voluntades, que establecen acuerdos para llevar adelante la gestión escolar 2019 de la Unidad Educativa de Convenio Técnico Humanístico Don Bosco “A”–“B” EPDB, turno tarde, de acuerdo a las cláusulas siguientes:
PRIMERA.- DE LAS PARTES CONSENSUANTES.- Intervienen en el presente convenio por una parte la Unidad Educativa de Convenio Técnico Humanístico Don Bosco “A”-“B”, de la ciudad de Sucre, administrada a la Sociedad San Francisco de Sales (SFS), con Resolución Administrativa No 641/2012 -642/2012  SIE 8048092 – 80480261 “A”-“B” representada legalmente por su Director General el Padre Alberto Vásquez Cáceres sdb, con C.I. 3122876 Cbba. Quien cuenta con las facultades suficientes de acuerdo a mandato que parte adjunta e indisoluble del presente acuerdo, para fines del presente documento en adelante se denominará UNIDAD EDUCATIVA.  
Por otra parte, el Sr. (a)............................................................................................. CI..................................... en su calidad de padre, madre o apoderado del/la estudiante............................................................................................................................del curso ........................................... NIVEL ..................................., con domicilio en Zona ........................................................... Av/Calle ........................................... Nº.......... Telf..................... o celular ......................, quien además declara contener los derechos y obligaciones en todos los ámbitos respecto al estudiante en adelante denominado (a) simplemente el (la) PADRE DE FAMILIA.
SEGUNDA.- Por el Convenio Marco suscrito entre Iglesia - Estado en el que se garantiza y respeta la administración, dirección y evaluación que la Iglesia lleva sobre sus obras educativas de Convenio, la Congregación SFS a través de la UNIDAD EDUCATIVA de Convenio DON BOSCO A - B, tiene por objeto primordial promover el desarrollo integral de las personas, sobre todo de los jóvenes a través de obras de carácter educativo.
Considerando el convenio de cooperación institucional entre la Sociedad San Francisco de Sales y la Sociedad Salesiana por la que ésta última concede el uso gratuito de sus activos fijos para el funcionamiento de la Unidad Educativa de Convenio “A”-“B”  EPDB.
El Ministerio de Educación, como ente rector del sector de la educación en el país, emite en fecha 02 de enero del 2019, la Resolución Ministerial No 001/2019 "NORMAS GENERALES PARA LA GESTION EDUCATIVA Y ESCOLAR  2019" la cual constituye la base legal del presente Convenio. 
TERCERA.- Por el presente documento ambas partes UNIDAD EDUCATIVA y PADRE DE FAMILIA  acuerdan que la UNIDAD EDUCATIVA se compromete a otorgar al estudiante durante la gestión escolar 2019 en el turno de la Tarde una Enseñanza - Aprendizaje, de calidad y eficiencia basados en los programas oficiales del Estado Plurinacional de Bolivia y aplicando sistemas modernos, teniendo como pilar fundamental la educación cristiana acorde a la moral y las normas de la Iglesia Católica, poniendo en práctica el Sistema Preventivo de Don Bosco, formación en valores morales y respetando la libertad religiosa y de culto. 
Por su parte el PADRE DE FAMILIA reconociendo la labor que realiza el Colegio se compromete de voluntad propia sin que medie presión alguna a realizar un aporte voluntario determinado por la Comunidad Educativa, delegando la administración de los aportes económicos al Director General como representante de la SFS.
CUARTA.- Yo, PADRE DE FAMILIA declaro conocer que la UNIDAD EDUCATIVA , es una entidad  perteneciente a la IGLESIA CATOLICA, declaro conocer también el Reglamento Interno que contiene la VISIÓN, MISIÓN, OBJETIVOS Y FINALIDAD de la institución educativa. Conozco también que en los casos de infracciones se aplicará el régimen disciplinario vigente y que las faltas que constituyen delitos serán derivados ante las instancias públicas competentes
QUINTA. Yo, PADRE DE FAMILIA acepto la Prohibición del uso de Celulares, Equipos de Sonido y otros dentro de la Unidad Educativa para fines diferentes al académico, siendo el caso de necesidad de uso de estos aparatos deberán ser autorizados por personal Docente y Dirección, de acuerdo al Art.120 de la Resolución Ministerial 01/2019, en caso de infringir mi hijo esta norma, conozco y acepto que será pasible a sanciones de acuerdo a Reglamento, además acepto que la Unidad Educativa NO se responsabiliza por la pérdida o sustracción de los mismos.  De igual forma conozco y acepto que la mochila, bolso u otro que mi hijo(a) porta al ingresar a la Unidad Educativa, es un instrumento que coadyuva en el trabajo educativo, por lo que en caso de ser necesario la Dirección del Colegio podrá pedir la revisión del mismo, en presencia de mi hijo y de su profesor guía, sin que esto signifique violación a la intimidad del alumno. (Señor PADRE DE FAMILIA se le ruega tomar debida nota de esta Cláusula). De igual forma conozco que es una falta grave hacer un mal uso de grupos creados por redes sociales por los estudiantes, padres de familia y maestros como medio de comunicación escolar que tenga un efecto negativo en la comunidad educativa. 
SEXTA. Yo, PADRE DE FAMILIA como principal responsable de la educación de mi hijo(a), me comprometo a concurrir a la Unidad Educativa cuantas veces sea necesario a entrevistas con la Directora y/o Profesores(as), a fin de informarme sobre el trabajo escolar, recoger y firmar el boletín y libreta en las fechas indicadas, tomar debida nota de las observaciones disciplinarias y de aprovechamiento de mi hijo(a). Es de mi conocimiento también que de acuerdo a lo establecido por el Reglamento de evaluación de Desarrollo Curricular, mi persona tiene responsabilidades de apoyo y seguimiento permanente y continuo en las dificultades que presentara mi hijo. Es de mi conocimiento también que las reuniones con validez en determinaciones e información son las convocadas por Dirección o con autorización de ésta. 
SÉPTIMA.  Ambas partes, al momento de firmar el presente convenio, manifiestan su plena conformidad con todas y cada una de las cláusulas precedentemente estipuladas.
'), 0);

			$this->pdf->setXY(40, 255);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setXY(40, 260);
			$this->pdf->Cell(40, 5, utf8_decode('Firma del Padre/ Madre/ Tutor'), 0, 0, 'C');
			$this->pdf->setXY(40, 265);
			$this->pdf->Cell(40, 5, utf8_decode('C.I.................................. '), 0, 0, 'C');

			$this->pdf->setXY(130, 255);
			$this->pdf->SetFont('Arial', 'BU', 10);
			$this->pdf->Cell(45, 5, '                                                ', 0, 0, 'R');
			$this->pdf->Ln(6);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->setXY(130, 260);
			$this->pdf->Cell(40, 5, utf8_decode('R.P. Alberto Vásquez Cáceres'), 0, 0, 'C');
			$this->pdf->setXY(130, 265);
			$this->pdf->Cell(40, 5, utf8_decode('C.I. 3122876 Cbba. '), 0, 0, 'C');

			$this->pdf->SetFont('Arial', '', 6);
			$this->pdf->setXY(15, 270);
			$this->pdf->Cell(30, 5, 'Fecha de Impresion: ' . date('Y-m-d'), 0, 0, 'L');



			$this->pdf->Output("Inscrip - CTTO " . $idins . ".pdf", 'I');

			ob_end_flush();
		}
	}

	//****************************************************************************************************************************************************
	public function ajax_list()
	{

		$list = $this->estud->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);

		foreach ($list as $estudiante) {
			$no++;
			$row = array();
			$row[] = $estudiante->idest;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			$row[] = $estudiante->idcurso;
			$row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="Edit" onclick="edit_estud(' . "'" . $estudiante->idest . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Inscribir</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->estud->count_all(),
			"recordsFiltered" => $this->estud->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function ajax_edit_estud($id)
	{
		//print_r($id);
		$data = $this->estud->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_get_curso1()
	{
		$tablecur = $this->input->post('TablaCur');
		$nivel = $this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list = $this->estud->get_rows_curso($tablecur, $nivel);

		$data = array();
		foreach ($list as $curso) {
			$data[] = $curso->curso;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
	}


	public function ajax_get_level()
	{
		$table = $this->input->post('table'); //recibe
		$lvl = $this->input->post('lvl');


		$list = $this->estud->get_rows_level($table, $lvl); //envia

		$data = array();
		foreach ($list as $level) {
			$data[] = $level->gestion;
			$data[] = $level->colegio;
			$data[]	= $level->idcurso;
			$data[]	= $level->curso;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);
		echo json_encode($output);
	}




	public function ajax_get_idcurso()
	{


		$nivel = $this->input->post('EstNivel');
		$curso = $this->input->post('EstCurso');

		$list = $this->estud->get_idcurso($nivel, $curso);

		$data = array();
		foreach ($list as $idcur) {
			$data[] = $idcur->idcurso;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);

		echo json_encode($output);
		//print_r($nivel."-".$gestion."-".$colegio."-".$curso);	
	}

	public function ajax_list_idcurso($id)
	{
		$list = $this->estud->get_datatables_by_idcur($id);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $estudiante) {
			//$no++;
			$row = array();
			$row[] = $estudiante->idest;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			$row[] = $estudiante->idcurso;
			$row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="Edit" onclick="edit_estud(' . "'" . $estudiante->idest . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Inscribir</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->estud->count_all(),
			"recordsFiltered" => $this->estud->count_filtered_byid($id),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function print_report($id)
	{
		$this->load->library('pdf');
		$list = $this->estud->get_print_estud_pdf($id);
		$curso = $this->estud->get_print_curso_pdf($id);

		ob_start();
		$this->pdf = new Pdf('Letter');
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle("LISTA DE ALUMNOS");
		$this->pdf->SetFont('Arial', 'BU', 15);
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('LISTA DE ALUMNOS'), 0, 0, 'C');
		$this->pdf->Ln('15');
		$this->pdf->Cell(30);
		$this->pdf->setXY(15, 45);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(35, 5, utf8_decode('ID CURSO: '), 0, 0, 'L');
		$this->pdf->setXY(35, 45);
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->Cell(15, 5, utf8_decode($id), 0, 0, 'L');
		$this->pdf->setX(55);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(45, 5, utf8_decode('NOMBRE: '), 0, 0, 'L');
		$this->pdf->setX(75);
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->Cell(15, 5, utf8_decode($curso->curso), 0, 0, 'L');
		$this->pdf->SetX(97);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(55, 5, utf8_decode('GESTION:'), 0, 0, 'C');
		$this->pdf->SetX(115);
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->Cell(55, 5, utf8_decode($curso->gestion), 0, 0, 'C');
		$this->pdf->Ln('6');
		$this->pdf->setX(15);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(30, 5, utf8_decode('CURSO: '), 0, 0, 'L');
		$this->pdf->setX(35);
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->Cell(30, 5, utf8_decode($curso->corto), 0, 0, 'L');
		$this->pdf->setX(55);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(60, 5, utf8_decode('NIVEL: '), 0, 0, 'L');
		$this->pdf->setX(75);
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->Cell(60, 5, utf8_decode($curso->nivel), 0, 0, 'L');
		$this->pdf->SetX(115);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(65, 5, utf8_decode('COLEGIO:'), 0, 0, 'L');
		$this->pdf->SetX(138);
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->Cell(65, 5, utf8_decode($curso->colegio), 0, 0, 'L');
		$this->pdf->Ln('3');

		$this->pdf->SetLeftMargin(15);
		$this->pdf->SetRightMargin(15);
		$this->pdf->SetFillColor(192, 192, 192);
		$this->pdf->SetFont('Arial', 'B', 8);
		$this->pdf->Ln(5);
		$this->pdf->Cell(10, 7, 'NUM', 'TBL', 0, 'L', '1');
		$this->pdf->Cell(34, 7, 'RUDE', 'TBL', 0, 'C', '1');
		$this->pdf->Cell(25, 7, 'CI', 'TBL', 0, 'C', '1');
		$this->pdf->Cell(10, 7, 'COD', 'TBLR', 0, 'L', '1');
		$this->pdf->Cell(30, 7, 'PATERNO', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(30, 7, 'MATERNO', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(38, 7, 'NOMBRES', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(10, 7, 'GEN', 'TBLR', 0, 'L', '1');

		$this->pdf->Ln(7);

		$this->pdf->SetFont('Arial', '', 8);
		$x = 1;
		foreach ($list as $estud) {
			$this->pdf->Cell(10, 5, $x++, 'TBL', 0, 'C', 0);
			// Se imprimen los datos de cada alumno
			//$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
			$this->pdf->Cell(34, 5, $estud->rude, 'TBL', 0, 'L', 0);
			$this->pdf->Cell(25, 5, $estud->ci, 'TBLR', 0, 'L', 0);
			$this->pdf->Cell(10, 5, $estud->codigo, 'TBLR', 0, 'L', 0);
			$this->pdf->Cell(30, 5, utf8_decode(strtoupper($estud->appaterno)), 'TBLR', 0, 'L', 0);
			$this->pdf->Cell(30, 5, utf8_decode(strtoupper($estud->apmaterno)), 'TBLR', 0, 'L', 0);
			$this->pdf->Cell(38, 5, utf8_decode(strtoupper($estud->nombres)), 'TBLR', 0, 'L', 0);
			$this->pdf->Cell(10, 5, $estud->genero, 'TBLR', 0, 'C', 0);

			//Se agrega un salto de linea
			$this->pdf->Ln(5);
		}
		$this->pdf->Ln(40);

		$this->pdf->Output("Lista Alumnos -" . $curso->corto . "- " . $curso->nivel . " -" . $curso->gestion . ".pdf", 'I');

		ob_end_flush();
	}
}
