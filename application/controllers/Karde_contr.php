<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karde_contr extends CI_Controller
{



	public function __construct()
	{
		parent::__construct();
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->model('Karde_model', 'estud');
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
		$this->load->view('Karde_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_get_gestion()
	{
		$table = $this->input->post('table'); //recibe
		$list = $this->estud->get_rows_gestion($table); //envia
		$data = array();
		foreach ($list as $gestion) {
			$data[] = $gestion->gestion;
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
		$table = $this->input->post('table');
		$cod = $this->input->post('cod');


		$codgen = '';
		$num_rows = $this->estud->get_count_rows($table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->estud->get_idcod_table($table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->idkar;
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

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu = 'http://' . $_SERVER['HTTP_HOST'] . '/donbosco/';
		header("Location: " . $bu);
		//echo json_encode(array("status" => TRUE));

	}


	public function ajax_list()
	{

		$list = $this->estud->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i = 0;
		foreach ($list as $estudiante) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			$curso_ = $this->estud->get_curso_est($estudiante->idest);
			//$row[] = $estudiante->idcurso;	
			$row[] = $curso_->curso;
			$row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-grey-800" href="javascript:void(0)" title="Edit" onclick="edit_estud(' . "'" . $estudiante->idest . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Lista Negra</a>';

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


	public function ajax_delete($id)
	{
		$this->estud->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit_estud($id)
	{
		//print_r($id);
		$data = $this->estud->get_studiante($id);
		echo json_encode($data);
	}

	public function ajax_save_kardex()
	{
		//valores enviados 
		$data = array(

			'fecha' => $this->input->post('fecha'),
			'descripcion' => $this->input->post('descrip'),
			'id_categoria' => $this->input->post('categoria'),
			'materia' => $this->input->post('materia'),
			'gestion' => $this->input->post('gestion'),
			'id_bi' => $this->input->post('bimestre'),
			'id_est' => $this->input->post('idest'),
			'id_user' => $this->session->userdata('id1')
		);

		//print_r($data);
		$this->estud->save_kardex($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_get_nivel()
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
	public function ajax_get_niveles()
	{
		// $table=$this->input->post('table');//recibe
		$list = $this->estud->get_rows_niveles(); //envia
		$data = array();
		$data1 = array();
		foreach ($list as $nivel) {
			$data[] = $nivel->nivel . " " . $nivel->turno;
			$data1[] = $nivel->codigo;
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

	public function ajax_get_colegio()
	{
		$table = $this->input->post('table'); //recibe
		$lvl = $this->input->post('lvl');


		$list = $this->estud->get_rows_colegio($lvl); //envia

		$data = array();
		foreach ($list as $level) {
			$data[] = $level->nombre;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function ajax_get_curso()
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

	public function ajax_get_cursos()
	{
		$tablecur = $this->input->post('TablaCur');
		$nivel = $this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list = $this->estud->get_rows_cursos($nivel);

		$data = array();
		$data1 = array();
		foreach ($list as $curso) {
			$data[] = $curso->nombre;
			$data1[] = $curso->codigo;
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
			"data1" => $data1,
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

		//print_r($id);
		$ids = explode('-', $id . "-", -1);
		//print_r($ids);
		//exit();
		$curso = $ids[0];
		$nivel = $ids[1];
		$gestion = $ids[2];

		//print_r($id1."?".$id2);
		$list = $this->estud->get_datatables_by_idcur($gestion, $curso, $nivel);

		$data = array();
		$no = $_POST['start'];
		$i = 0;
		foreach ($list as $estudiante) {
			//$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombre;
			$row[] = $estudiante->genero;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-grey-800" href="javascript:void(0)" title="Edit" onclick="edit_estud(' . "'" . $estudiante->id_est . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Lista Negra</a>';
			$row[] = '<a class="btn btn-sm bg-danger-800" href="javascript:void(0)" title="Imprimir" onclick="print_kar_studien(' . "'" . $estudiante->id_est . "'" . ')"><i class="icon icon-file-pdf"></i>Imprimir</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->estud->count_all(),
			"recordsFiltered" => $this->estud->count_filtered_byid($gestion, $curso, $nivel),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_load_sql()
	{

		$idest = $this->input->post('idest');
		$bimes = $this->input->post('bimes');
		$gestion = $this->input->post('gestion');


		$list = $this->estud->get_datatables_kar($idest, $bimes, $gestion);
		// print_r($list);
		// exit();
		$data = array();
		$i = 0;
		foreach ($list as $kardex) {
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $kardex->fecha;
			$cate = $this->estud->get_categoria($kardex->id_categoria);
			$row[] = $cate->nombre;
			$row[] = $kardex->materia;
			$row[] = $kardex->descripcion;

			//add html for action
			$row[] = '<a class="btn btn-sm bg-danger-300" href="javascript:void(0)" title="Edit" onclick="deletekar(' . "'" . $kardex->id . "'" . ')"><i class="icon icon-eraser2"></i> Eliminar</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->estud->count_all_kar(),
			"recordsFiltered" => $this->estud->count_filtered_kar($idest, $bimes, $gestion),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function deletekar($idkar)
	{
		$this->estud->delete_by_id($idkar);
		echo json_encode(array("status" => TRUE));
	}

	public function printEstud($id)
	{
		$n = strlen($id);
		$id1 = substr($id, 0, 4); //gestion
		$id2 = substr($id, 4, $n); //idcur

		$this->load->library('pdf');
		$list = $this->estud->get_print_estud_pdf($id1, $id2);
		$curso = $this->estud->get_print_curso_pdf($id2);

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
		$this->pdf->Cell(15, 5, utf8_decode($id2), 0, 0, 'L');
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
		$this->pdf->Cell(55, 5, utf8_decode($id1), 0, 0, 'C');
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

	public function printKar($id)
	{
		$ids = explode('-', $id . "-", -1);
		// print_r($ids);
		// exit();
		$curso = $ids[0];
		$nivel = $ids[1];
		$gestion = $ids[2];
		$id_est = $ids[3];
		$tri = $ids[4];

		$this->load->library('Ins_pdf');
		$estudiante = $this->estud->get_studiante($id_est);
		$cursos = $this->estud->get_cursos($curso);
		$niveles = $this->estud->get_nivel_colegio($nivel);
		$trimestre = $this->estud->get_trimestre($tri);

		$this->Ins_pdf = new Ins_pdf('letter', 'P');
		$this->Ins_pdf->AddPage("L");
		$this->Ins_pdf->AliasNbPages();
		$this->Ins_pdf->SetTitle("LISTA DE ALUMNOS");
		$this->Ins_pdf->SetFont('Arial', 'BU', 15);
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->Cell(250, 8, utf8_decode('KARDEX DEL ' . $trimestre->nombre), 0, 0, 'C');
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->setXY(15, 45);
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('NIVEL: '), 0, 0, 'L');
		$this->Ins_pdf->setX(30);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode($niveles->nivel . " " . $niveles->turno), 0, 0, 'L');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode('U.E.:'), 0, 0, 'L');
		$this->Ins_pdf->SetX(200);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode($niveles->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetFont('Arial', '', 10);
		// $this->Ins_pdf->Cell(55,5,utf8_decode($bimes.'°'),0,0,'C');
		$this->Ins_pdf->Ln('6');
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode('CURSO: '), 0, 0, 'L');
		$this->Ins_pdf->setX(33);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode($cursos->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetX(160);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('GESTION:'), 0, 0, 'C');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(55, 5, utf8_decode($gestion), 0, 0, 'C');
		$this->Ins_pdf->Line(10, 58, 285, 58);
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetLeftMargin(10);
		$this->Ins_pdf->SetRightMargin(10);
		$this->Ins_pdf->SetFillColor(200, 235, 255);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Ln(5);
		$this->Ins_pdf->Cell(275, 7, utf8_decode($estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre), 'TBLR', 0, 'C', '1');
		$this->Ins_pdf->SetFont('Arial', 'B', 9);
		$this->Ins_pdf->Ln(5);
		$this->Ins_pdf->Cell(10, 7, 'NUM', 'TBL', 0, 'L', '1');
		$this->Ins_pdf->Cell(25, 7, 'FECHA', 'TBL', 0, 'C', '1');
		$this->Ins_pdf->Cell(68, 7, 'MATERIA', 'TBL', 0, 'C', '1');
		$this->Ins_pdf->Cell(72, 7, 'CATEGORIA', 'TBL', 0, 'C', '1');
		$this->Ins_pdf->Cell(100, 7, 'DESCRIPCION', 'TBLR', 0, 'C', '1');
		$this->Ins_pdf->Ln(7);
		$kardex = $this->estud->get_kardex($tri, $gestion, $estudiante->id_est);
		$x = 1;
		foreach ($kardex as $kar) {
			$this->Ins_pdf->SetFont('Arial', '', 9);
			$this->Ins_pdf->SetLeftMargin(10);
			$this->Ins_pdf->SetRightMargin(10);
			$this->Ins_pdf->SetFillColor(239, 251, 255);
			$this->Ins_pdf->Cell(10, 7, $x++, 'TBL', 0, 'L', '1');
			$this->Ins_pdf->Cell(25, 7, $kar->fecha, 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(68, 7, $kar->materia, 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(72, 7, $kar->cat, 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(100, 7, utf8_decode($kar->descripcion), 'TBLR', 0, 'C', '1');
			$this->Ins_pdf->Ln(7);
		}



		$this->Ins_pdf->Ln(40);

		$this->Ins_pdf->Output("Kardex - " . $estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre . "-" . $gestion . ".pdf", 'I');

		ob_end_flush();
	}
	public function export_pdf($id)
	{
		$ids = explode('-', $id . "-", -1);
		// print_r($ids);
		// exit();
		$curso = $ids[0];
		$nivel = $ids[1];
		$gestion = $ids[2];

		$this->load->library('Ins_pdf');
		$list = $this->estud->get_curso_student($gestion, $curso, $nivel);
		$cursos = $this->estud->get_cursos($curso);
		$niveles = $this->estud->get_nivel_colegio($nivel);

		$this->Ins_pdf = new Ins_pdf('letter', 'P');
		$this->Ins_pdf->AddPage("L");
		$this->Ins_pdf->AliasNbPages();
		$this->Ins_pdf->SetTitle("LISTA DE ALUMNOS");
		$this->Ins_pdf->SetFont('Arial', 'BU', 15);
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->Cell(250, 8, utf8_decode('KARDEX DE ALUMNOS'), 0, 0, 'C');
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->setXY(15, 45);
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('NIVEL: '), 0, 0, 'L');
		$this->Ins_pdf->setX(30);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode($niveles->nivel . " " . $niveles->turno), 0, 0, 'L');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode('U.E.:'), 0, 0, 'L');
		$this->Ins_pdf->SetX(200);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode($niveles->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetFont('Arial', '', 10);
		// $this->Ins_pdf->Cell(55,5,utf8_decode($bimes.'°'),0,0,'C');
		$this->Ins_pdf->Ln('6');
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode('CURSO: '), 0, 0, 'L');
		$this->Ins_pdf->setX(33);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode($cursos->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetX(160);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('GESTION:'), 0, 0, 'C');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(55, 5, utf8_decode($gestion), 0, 0, 'C');
		$this->Ins_pdf->Line(10, 58, 285, 58);
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->setX(15);


		$x = 1;
		foreach ($list as $key => $estudiante) {
			if ($key !== 0)
				$this->Ins_pdf->AddPage("L");
			$this->Ins_pdf->SetLeftMargin(10);
			$this->Ins_pdf->SetRightMargin(10);
			$this->Ins_pdf->SetFillColor(200, 235, 255);
			$this->Ins_pdf->SetFont('Arial', 'B', 10);
			$this->Ins_pdf->Ln(5);
			$this->Ins_pdf->Cell(275, 7, utf8_decode($estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre), 'TBLR', 0, 'C', '1');
			$this->Ins_pdf->SetFont('Arial', 'B', 9);
			$this->Ins_pdf->Ln(5);
			$this->Ins_pdf->Cell(10, 7, 'NUM', 'TBL', 0, 'L', '1');
			$this->Ins_pdf->Cell(25, 7, 'FECHA', 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(68, 7, 'MATERIA', 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(72, 7, 'CATEGORIA', 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(100, 7, 'DESCRIPCION', 'TBLR', 0, 'C', '1');
			$this->Ins_pdf->Ln(7);
			$this->Ins_pdf->SetFont('Arial', '', 9);
			$this->Ins_pdf->SetLeftMargin(10);
			$this->Ins_pdf->SetRightMargin(10);
			$this->Ins_pdf->SetFillColor(239, 251, 255);
			$kardex = $this->estud->get_kardex1($gestion, $estudiante->id_est);
			$x = 1;
			foreach ($kardex as $kar) {

				$this->Ins_pdf->Cell(10, 7, $x++, 'TBL', 0, 'L', '1');
				$this->Ins_pdf->Cell(25, 7, $kar->fecha, 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(68, 7, $kar->materia, 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(72, 7, $kar->cat, 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(100, 7, utf8_decode($kar->descripcion), 'TBLR', 0, 'C', '1');

				//Se agrega un salto de linea
				$this->Ins_pdf->Ln(7);
			}
		}

		$this->Ins_pdf->Ln(40);

		$this->Ins_pdf->Output("Kardex - " . $cursos->nombre . "-  -" . $gestion . ".pdf", 'I');

		ob_end_flush();
	}
	public function printKar_curso($id)
	{

		$ids = explode('-', $id . "-", -1);
		// print_r($ids);
		// exit();
		$curso = $ids[0];
		$nivel = $ids[1];
		$gestion = $ids[2];
		$tri = $ids[3];

		$this->load->library('Ins_pdf');
		$list = $this->estud->get_curso_student($gestion, $curso, $nivel);
		$cursos = $this->estud->get_cursos($curso);
		$niveles = $this->estud->get_nivel_colegio($nivel);
		$trimestre = $this->estud->get_trimestre($tri);

		$this->Ins_pdf = new Ins_pdf('letter', 'P');
		$this->Ins_pdf->AddPage("L");
		$this->Ins_pdf->AliasNbPages();
		$this->Ins_pdf->SetTitle("LISTA DE ALUMNOS");
		$this->Ins_pdf->SetFont('Arial', 'BU', 15);
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->Cell(250, 8, utf8_decode('KARDEX DE ALUMNOS DEL ' . $trimestre->nombre), 0, 0, 'C');
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->setXY(15, 45);
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('NIVEL: '), 0, 0, 'L');
		$this->Ins_pdf->setX(30);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode($niveles->nivel . " " . $niveles->turno), 0, 0, 'L');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode('U.E.:'), 0, 0, 'L');
		$this->Ins_pdf->SetX(200);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode($niveles->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetFont('Arial', '', 10);
		// $this->Ins_pdf->Cell(55,5,utf8_decode($bimes.'°'),0,0,'C');
		$this->Ins_pdf->Ln('6');
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode('CURSO: '), 0, 0, 'L');
		$this->Ins_pdf->setX(33);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode($cursos->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetX(160);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('GESTION:'), 0, 0, 'C');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(55, 5, utf8_decode($gestion), 0, 0, 'C');
		$this->Ins_pdf->Line(10, 58, 285, 58);
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->setX(15);


		$x = 1;
		foreach ($list as $estudiante) {

			$this->Ins_pdf->SetLeftMargin(10);
			$this->Ins_pdf->SetRightMargin(10);
			$this->Ins_pdf->SetFillColor(200, 235, 255);
			$this->Ins_pdf->SetFont('Arial', 'B', 10);
			$this->Ins_pdf->Ln(5);
			$this->Ins_pdf->Cell(275, 7, utf8_decode($estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre), 'TBLR', 0, 'C', '1');
			$this->Ins_pdf->SetFont('Arial', 'B', 9);
			$this->Ins_pdf->Ln(5);
			$this->Ins_pdf->Cell(10, 7, 'NUM', 'TBL', 0, 'L', '1');
			$this->Ins_pdf->Cell(25, 7, 'FECHA', 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(68, 7, 'MATERIA', 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(72, 7, 'CATEGORIA', 'TBL', 0, 'C', '1');
			$this->Ins_pdf->Cell(100, 7, 'DESCRIPCION', 'TBLR', 0, 'C', '1');
			$this->Ins_pdf->Ln(7);
			$this->Ins_pdf->SetFont('Arial', '', 9);
			$this->Ins_pdf->SetLeftMargin(10);
			$this->Ins_pdf->SetRightMargin(10);
			$this->Ins_pdf->SetFillColor(239, 251, 255);
			$kardex = $this->estud->get_kardex($tri, $gestion, $estudiante->id_est);
			$x = 1;
			foreach ($kardex as $kar) {

				$this->Ins_pdf->Cell(10, 7, $x++, 'TBL', 0, 'L', '1');
				$this->Ins_pdf->Cell(25, 7, $kar->fecha, 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(68, 7, $kar->materia, 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(72, 7, $kar->cat, 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(100, 7, utf8_decode($kar->descripcion), 'TBLR', 0, 'C', '1');

				//Se agrega un salto de linea
				$this->Ins_pdf->Ln(7);
			}
		}

		$this->Ins_pdf->Ln(40);

		$this->Ins_pdf->Output("Kardex - " . $cursos->nombre . "- " . $trimestre->codigo . " -" . $gestion . ".pdf", 'I');

		ob_end_flush();
	}
	public function printKar_studien($id)
	{
		$ids = explode('-', $id . "-", -1);
		$curso = $ids[0];
		$nivel = $ids[1];
		$gestion = $ids[2];
		$id_est = $ids[3];

		$this->load->library('Ins_pdf');
		$estudiante = $this->estud->get_studiante($id_est);
		$cursos = $this->estud->get_cursos($curso);
		$niveles = $this->estud->get_nivel_colegio($nivel);

		$this->Ins_pdf = new Ins_pdf('letter', 'P');
		$this->Ins_pdf->AddPage("L");
		$this->Ins_pdf->AliasNbPages();
		$this->Ins_pdf->SetTitle("KARDEX - " . $estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre);
		$this->Ins_pdf->SetFont('Arial', 'BU', 15);
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->Cell(250, 8, utf8_decode('KARDEX DE ALUMNO'), 0, 0, 'C');
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->Cell(30);
		$this->Ins_pdf->setXY(15, 45);
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('NIVEL: '), 0, 0, 'L');
		$this->Ins_pdf->setX(30);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode($niveles->nivel . " " . $niveles->turno), 0, 0, 'L');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode('U.E.:'), 0, 0, 'L');
		$this->Ins_pdf->SetX(200);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(65, 5, utf8_decode($niveles->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetFont('Arial', '', 10);

		$this->Ins_pdf->Ln('6');
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode('CURSO: '), 0, 0, 'L');
		$this->Ins_pdf->setX(33);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(30, 5, utf8_decode($cursos->nombre), 0, 0, 'L');
		$this->Ins_pdf->SetX(160);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Cell(60, 5, utf8_decode('GESTION:'), 0, 0, 'C');
		$this->Ins_pdf->SetX(180);
		$this->Ins_pdf->SetFont('Arial', '', 10);
		$this->Ins_pdf->Cell(55, 5, utf8_decode($gestion), 0, 0, 'C');
		$this->Ins_pdf->Line(10, 58, 285, 58);
		$this->Ins_pdf->Ln('15');
		$this->Ins_pdf->setX(15);
		$this->Ins_pdf->SetLeftMargin(10);
		$this->Ins_pdf->SetRightMargin(10);
		$this->Ins_pdf->SetFillColor(200, 235, 255);
		$this->Ins_pdf->SetFont('Arial', 'B', 10);
		$this->Ins_pdf->Ln(5);
		$this->Ins_pdf->Cell(275, 7, utf8_decode($estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre), 'TBLR', 0, 'C', '1');


		$trimestre = $this->estud->get_all_trimestres();
		$enabledTrimestre = true;

		foreach ($trimestre as $trim) {
			if ($enabledTrimestre) {
				$this->Ins_pdf->SetLeftMargin(10);
				$this->Ins_pdf->SetRightMargin(10);
				$this->Ins_pdf->SetFillColor(200, 235, 255);
				$this->Ins_pdf->SetFont('Arial', 'B', 10);
				$this->Ins_pdf->Ln(5);
				$this->Ins_pdf->Cell(275, 7, utf8_decode($trim->nombre), 'TBLR', 0, 'C', '1');
				$this->Ins_pdf->SetFont('Arial', 'B', 9);
				$this->Ins_pdf->Ln(5);
				$this->Ins_pdf->Cell(10, 7, 'NUM', 'TBL', 0, 'L', '1');
				$this->Ins_pdf->Cell(25, 7, 'FECHA', 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(68, 7, 'MATERIA', 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(72, 7, 'CATEGORIA', 'TBL', 0, 'C', '1');
				$this->Ins_pdf->Cell(100, 7, 'DESCRIPCION', 'TBLR', 0, 'C', '1');
				$this->Ins_pdf->Ln(7);
				$kardex = $this->estud->get_kardex($trim->id_bi, $gestion, $estudiante->id_est);
				$x = 1;
				foreach ($kardex as $kar) {
					$this->Ins_pdf->SetFont('Arial', '', 9);
					$this->Ins_pdf->SetLeftMargin(10);
					$this->Ins_pdf->SetRightMargin(10);
					$this->Ins_pdf->SetFillColor(239, 251, 255);
					$this->Ins_pdf->Cell(10, 7, $x++, 'TBL', 0, 'L', '1');
					$this->Ins_pdf->Cell(25, 7, $kar->fecha, 'TBL', 0, 'C', '1');
					$this->Ins_pdf->Cell(68, 7, $kar->materia, 'TBL', 0, 'C', '1');
					$this->Ins_pdf->Cell(72, 7, $kar->cat, 'TBL', 0, 'C', '1');
					$this->Ins_pdf->Cell(100, 7, utf8_decode($kar->descripcion), 'TBLR', 0, 'C', '1');
					$this->Ins_pdf->Ln(7);
				}
			}
			/*SI ESTA ACTIVO, EL SIGUIENTE TRIMESTRE YA NO SE MUESTRA*/
			/*if($trim->activo == 1){ // Mostrar solo un trimestre
				$enabledTrimestre = false;
			}*/
		}



		$this->Ins_pdf->Ln(40);
		$this->Ins_pdf->Output("Kardex - " . $estudiante->appaterno . ' ' . $estudiante->apmaterno . ' ' . $estudiante->nombre . "-" . $gestion . ".pdf", 'I');
		ob_end_flush();
	}


	public function printxls($id)
	{
		/*
		require_once APPPATH."/third_party/phpexcel/phpexcel.php";
		require_once APPPATH."/third_party/phpexcel/PHPExcel/Writer/Excel2007.php";
		$list=$this->estud->get_print_estud_pdf($id);
		$curso=$this->estud->get_print_curso_pdf($id);

		//print_r($curso->corto);

		
		$this->objPHPExcel=new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("");
		$this->objPHPExcel->getProperties()->setLastModifieldBy("");
		$this->objPHPExcel->getProperties()->setTitle("");
		$this->objPHPExcel->getProperties()->setSubject("");
		$this->objPHPExcel->getProperties()->setDescription("");
		
		
		$this->objPHPExcel->setActiveSheetIndex(0);

		$this->objPHPExcel->getActiveSheet()->SetCellValue('A1','IDEST');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('B1','RUDE');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('C1','CI');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('D1','CODIGO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('E1','PATERNO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('F1','MATERNO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('G1','NOMBRES');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('H1','GENERO');

		$row=2;

		foreach ($list as $estud) {
			
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->rude);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->ci);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->codigo);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->appaterno);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->apmaterno);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->nombres);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->genero);

			$row++;
		}

		$filename="Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".xlsx";
		$this->objPHPExcel->getActiveSheet()->setTitle("Lista Alumnos ".$curso->corto);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$writer->save('php://output');
		exit;
		*/
	}
}
