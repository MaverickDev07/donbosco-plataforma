<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Not_notas_contr extends CI_Controller
{

	public $_idnota = "";

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Est_estudiante_model', 'estud');
		$this->load->model('Est_estudiantes_model', 'estudiantes');
		$this->load->model('Not_notas_model', 'nota');
		$this->load->library('excel');
		$this->load->library('drawing');
		//$this->load->library('Leer');

		// if(!$this->session->userdata("login"))
		// {
		// 	$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		// 	header("Location: ".$bu);
		// }
	}

	public function index()
	{
		// dev_test : se agrego una validacion para que solo pueda acceder el super usuario
		if ($this->session->userdata("access") == "157f3261a72f2650e451ccb84887de31746d6c6c") {
			$this->load->helper('url');
			$this->vista();
		}
	}
	public function ajax_list()
	{
		$gestion = 2022; // dev_test: la gestion esta quemada
		$bime = 5; // $this->nota->get_trimestre_current()->id_bi; dev_test: se esta cambiando a 1er trimestre porque seguimos con notas del primer trimestre
		$b = $this->nota->get_bimestre($bime);
		$bimestre = $b->sigla;

		$table = 'profesor';
		$appat = $this->session->userdata("appat");
		$apmat = $this->session->userdata("apmat");
		$name = $this->session->userdata("name");
		$prof = $this->nota->get_prof1($appat, $apmat, $name);
		$idprof = $prof->id_prof;
		$nivel = $this->nota->get_nivel($idprof, $gestion); //envia
		$cod = array();
		$cod1 = array();

		// var_dump(json_encode($nivel)); // dev_test delete_please

		foreach ($nivel as $nivels) {
			$id = $nivels->codigo . '-';
			$ids = explode('-', $id, -1);
			// if($ids[1]=='ST' or $ids[1]=='PT')
			// {
			$cod1[] = $ids[1];
			// }	
		}

		// var_dump($cod1); // dev_test delete_please

		$data = array();
		$data1 = array();
		$cod = array_values(array_unique($cod1));
		$data = array();
		$no = $_POST['start'];
		$i = 0;
		$n = -1;
		foreach ($cod as $cod2) {
			$n++;
			$aa = $this->nota->get_nivel_cod($cod[$n]);
			foreach ($aa as $niveles) {
				$p_curso = $this->nota->prof_cursos($idprof, $cod[$n], $gestion); //envia
				$codcur = array();
				$dd = array();
				foreach ($p_curso as $p_cursos) {
					$id = $p_cursos->codigo . '-';
					$ids = explode('-', $id, -1);
					$dd[] = $ids[0];
				}
				$codcur = array_values(array_unique($dd));
				$c = -1;

				foreach ($codcur as $codcurs) {
					$c++;
					$list_curso = $this->nota->get_cursos($codcur[$c]);
					foreach ($list_curso as $list_cursos) {
						$mat = $this->nota->prof_materias($idprof, $codcur[$c], $gestion); //envia
						$mate = array();
						$codmate1 = array();
						foreach ($mat as $mats) {
							$id = $mats->codigo . '-';
							$ids = explode('-', $id, -1);
							$codmate1[] = $ids[5];
						}
						$mates = array();
						$mates = array_values(array_unique($codmate1));
						$m = -1;
						foreach ($mates as $matess) {
							$m++;
							$materia = $this->nota->materiass($mates[$m]);

							$id_materia = $materia->id_mat;
							$materias = $materia->nombre;

							$no++;
							$i++;
							$row = array();
							$row[] = $i;
							$row[] = $list_cursos->nombre;
							$row[] = $materias;
							$row[] = $niveles->nivel . " " . $niveles->turno;
							$row[] = $niveles->colegio;
							$row[] = $bimestre;
							$id = $gestion . "W" . $codcur[$c] . "W" . $idprof . "W" . $id_materia . "W" . $bime . "W" . $niveles->id_nivel . "W";
							$row[] = '<a class="btn btn-success" href="javascript:void(0)" title="Edit" onclick="d_planilla_notas(' . "'" . $id . "','" . $codcur[$c] . "'" . ')"><i class="glyphicon glyphicon-cloud-download"></i>PLANILLA</a>';
							//$row[] = '<a class="btn btn-success" href="javascript:void(0)" title="Edit" onclick="d_planilla_notas('."'".$id."','".$codcur[$c]."'".')"><i class="glyphicon glyphicon-cloud-download"></i>PLANILLA</a>';
							$data[] = $row;
						}
					}
				}
			}
		}
		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	public function vista()
	{
		$this->load->view('layouts/inicio');
		$this->load->view('layouts/encabezado');
		$this->load->view('Not_notas_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_usuario()
	{
		$table = 'profesor';
		$appat = $this->session->userdata("appat");
		$apmat = $this->session->userdata("apmat");
		$name = $this->session->userdata("name");

		//$list=$this->nota->get_prof_row($appat,$apmat,$name,$table);
		$prof = $this->nota->get_prof1($appat, $apmat, $name);
		$data = array();
		if ($prof) {
			$data[] = $prof->id_prof;
			$data[] = $prof->appaterno . " " . $prof->apmaterno . " " . $prof->nombre;
			$output = array(
				"status" => TRUE,
				"data" => $data,
			);
		} else {
			$output = array(
				"status" => FALSE,
			);
		}


		echo json_encode($output);
	}

	//------------------------


	public function ajax_get_id()
	{
		$table = $this->input->post('table');
		$cod = $this->input->post('cod');
		$colum = "idtemabi";

		$codgen = '';
		$num_rows = $this->nota->get_count_rows2($table, $colum);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->nota->get_idcod_table2($table, $colum);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->idavance; //considerar nombre del id;				
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

		$output = array(
			"status" => TRUE,
			"data" => $codgen,
		);
		echo json_encode($output);
	}

	public function ajax_get_idindi()
	{
		$table = $this->input->post('table');
		$cod = $this->input->post('cod');
		//print_r($table."-".$cod);

		$codgen = '';
		$num_rows = $this->nota->get_count_rows3($table);
		if ($num_rows != 0) {
			$n = strlen($cod);
			$list = $this->nota->get_idcod_table3($table);
			$may = 0;

			foreach ($list as $codigo) {
				$idcod = $codigo->idindi; //considerar nombre del id;				
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
	public function ajax_get_level()
	{
		$table = $this->input->post('table'); //recibe
		$lvl = $this->input->post('lvl');


		//$list=$this->estud->get_rows_level($table,$lvl); //envia
		$nivel = $this->nota->niveles($lvl);

		foreach ($nivel as $niveles) {
			$id = $niveles->id_col;
		}
		$cole = $this->nota->get_cole($id);
		$data = array();
		foreach ($cole as $level) {
			$data[] = $level->nombre;
		}
		$output = array(
			"status" => TRUE,
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
		$data = $this->estud->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_nota()
	{

		$data = array(
			'ser1' => $this->input->post('ser1'),
			'ser2' => $this->input->post('ser2'),
			'ser3' => $this->input->post('ser3'),
			'promser' => $this->input->post('promser'),
			'sab1' => $this->input->post('sab1'),
			'sab2' => $this->input->post('sab2'),
			'sab3' => $this->input->post('sab3'),
			'sab4' => $this->input->post('sab4'),
			'sab5' => $this->input->post('sab5'),
			'sab6' => $this->input->post('sab6'),
			'promsab' => $this->input->post('promsab'),
			'hac1' => $this->input->post('hac1'),
			'hac2' => $this->input->post('hac2'),
			'hac3' => $this->input->post('hac3'),
			'hac4' => $this->input->post('hac4'),
			'hac5' => $this->input->post('hac5'),
			'hac6' => $this->input->post('hac6'),
			'promhac' => $this->input->post('promhac'),
			'dec1' => $this->input->post('dec1'),
			'dec2' => $this->input->post('dec2'),
			'dec3' => $this->input->post('dec3'),
			'promdec' => $this->input->post('promdec'),
			'autoser' => $this->input->post('autoser'),
			'autodec' => $this->input->post('autodec'),
			'final' => $this->input->post('final')
		);


		$this->nota->update(array('idnota' => $this->input->post('idnota')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_get_nivel()
	{
		$table = $this->input->post('table'); //recibe
		$idprof = $this->input->post('idprof');
		$list = $this->nota->get_nivel($idprof, 2021); //envia
		$cod = array();
		$cod1 = array();
		foreach ($list as $prof) {
			$id = $prof->codigo . '-';
			$ids = explode('-', $id, -1);
			$cod1[] = $ids[2];
		}
		$data = array();
		$data1 = array();
		$cod = array_values(array_unique($cod1));
		$i = 0;
		foreach ($cod as $cod2) {
			$nivel = $this->nota->niveles($cod[$i]);
			$i++;
			foreach ($nivel as $niveles) {
				$data[] = $niveles->id_nivel;
				$data1[] = $niveles->nivel . ' ' . $niveles->turno;
			}
		}

		$output = array(
			"status" => TRUE,
			"data" => $data,
			"data1" => $data1,
		);
		echo json_encode($output);
	}




	public function ajax_get_curso()
	{
		//$table=$this->input->post('tabla');

		$nivel = $this->input->post("nivel");
		$idprof = $this->input->post('idprof');
		//print_r($tablecur."-".$nivel);
		$n = $this->nota->niveles($nivel);
		foreach ($n as $ni) {
			$niv = $ni->codigo;
		}

		$list = $this->nota->p_cursos($idprof, $niv); //envia
		$cod = array();
		foreach ($list as $prof) {
			$id = $prof->codigo . '-';
			$ids = explode('-', $id, -1);
			$cod1[] = $ids[0];
		}
		$data = array();
		$data1 = array();
		$cod = array_values(array_unique($cod1));
		$i = 0;
		foreach ($cod as $cod2) {
			$nivel = $this->nota->get_cursos($cod[$i]);
			//$data[]=$cod2[$i];
			$i++;

			foreach ($nivel as $niveles) {
				$data[] = $niveles->codigo;
				$data1[] = $niveles->nombre;
			}
		}
		$output = array(
			"status" => TRUE,
			"data" => $data,
			"data1" => $data1,
		);

		echo json_encode($output);
	}

	public function ajax_get_mat()
	{
		$curso = $this->input->post("curso");
		$idprof = $this->input->post('idprof');
		//$list=$this->nota->get_nivel($idprof); //envia
		$list = $this->nota->p_materias($idprof, $curso); //envia
		$cod = array();
		foreach ($list as $prof) {
			$id = $prof->codigo . '-';
			$ids = explode('-', $id, -1);
			$cod1[] = $ids[5];
		}
		$data = array();
		$data1 = array();
		$cod = array_values(array_unique($cod1));
		$i = 0;
		foreach ($cod as $cod2) {
			$nivel = $this->nota->materiass($cod[$i]);
			//$data[]=$cod2[$i];
			$i++;

			foreach ($nivel as $niveles) {
				$data[] = $niveles->id_mat;
				$data1[] = $niveles->nombre;
			}
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
		$table2 = 'nota';
		//

		$data = array();

		$nivel = $this->input->post('nivel');
		$curso = $this->input->post('cur');
		$idprof = $this->input->post('idprof');
		$gestion = $this->input->post('gestion');
		$bimestre = $this->input->post('bimestre');
		$materia = $this->input->post('materia');

		$list = $this->estud->get_idcurso($nivel, $curso);

		foreach ($list as $idcur) {
			$idcurso = $idcur->idcurso;
		}

		//REVISAR LA MATERIA
		$table = 'materia';
		$list1 = $this->nota->get_idmat($table, $idprof, $idcurso, $nivel, $materia);
		foreach ($list1 as $mat) {
			$idmat = $mat->idmat;
		}


		//SELECT * FROM donbosco.estudiante where idcurso='CUR-14' and nivel='SECUNDARIA MAÑANA' and gestion='2018'

		$table1 = 'estudiante';
		$list2 = $this->nota->get_estud($table1, $idcurso, $gestion);
		//$table2='nota';
		foreach ($list2 as $est) {

			$idest = $est->idest;
			$appat = $est->appaterno;
			$apmat = $est->apmaterno;
			$nomb = $est->nombres;
			$list3 = $this->nota->if_exit_nota($table2, $idest, $idmat, $bimestre);
			//print_r($list3."<br>");
			if ($list3 == 0) {

				$cod = 'NOT-';
				$codgen = '';
				$num_rows = $this->nota->get_count_rows($table2);
				if ($num_rows != 0) {
					$n = strlen($cod);
					$list = $this->nota->get_idcod_table($table2);
					$may = 0;
					foreach ($list as $codigo) {
						$idcod = $codigo->idnota; //considerar nombre del id;				
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
				$idnota = $codgen;

				//valores enviados 
				$data = array(
					'idnota' => $idnota,
					'idest' => $idest,
					'idmat' => $idmat,
					'idcurso' => $idcurso,
					'idprof' => $idprof,
					'appat' => $appat,
					'apmat' => $apmat,
					'nombres' => $nomb,
					'bimestre' => $bimestre,
					'gestion' => $gestion,
				);

				//print_r($data);
				$insert = $this->nota->save_nota($data);
				//ajax_list_mat($table2,$idest,$idmat,$bimestre);
			}
		}
		$dataEnvio = array(
			'idmat' => $idmat,
			'idcurso' => $idcurso,
			'idprof' => $idprof,
			'gestion' => $gestion,
			'bimestre' => $bimestre,
		);

		$output = array(
			"status" => TRUE,
			"data" => $dataEnvio,
		);

		echo json_encode($output);
	}


	public function ajax_list_alum()
	{

		$idmat = $this->input->post('idmat');
		$idcur = $this->input->post('idcur');
		$idprof = $this->input->post('idprof');
		$gestion = $this->input->post('gestion');
		$bimestre = $this->input->post('bimestre');

		$list = $this->nota->get_datatables_by_all($idmat, $idcur, $idprof, $gestion, $bimestre);
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $notas) {
			$_idnota = $notas->idnota;
			$no++;
			$row = array();
			$row[] = "<td>" . $notas->idnota . "</td>";
			$row[] = "<td >" . $notas->appat . "</td>";
			$row[] = "<td >" . $notas->apmat . "</td>";
			$row[] = "<td >" . $notas->nombres . "</td>";
			$row[] = "<td ><input  type='number' name='ser1" . $notas->idnota . "' id='ser1" . $notas->idnota . "' class='form-control'  value='" . $notas->ser1 . "' min = '1'
max = '10' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promser(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='ser2" . $notas->idnota . "' id='ser2" . $notas->idnota . "' class='form-control'  value='" . $notas->ser2 . "' min = '1'
max = '10' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promser(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='ser3" . $notas->idnota . "' id='ser3" . $notas->idnota . "' class='form-control'  value='" . $notas->ser3 . "' min = '1'
max = '10' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promser(this.value,this.id)'></td>";
			$row[] = " <td ><input  type='number' name='promser" . $notas->idnota . "' id='promser" . $notas->idnota . "' class='form-control bg-primary-300' style='color:black;' value='" . $notas->promser . "' size='6' readonly='true'></td>";

			$row[] = "<td><input  type='number' name='sab1" . $notas->idnota . "' id='sab1" . $notas->idnota . "' class='form-control'  value='" . $notas->sab1 . "' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='sab2" . $notas->idnota . "' id='sab2" . $notas->idnota . "' class='form-control'  value='" . $notas->sab2 . "' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='sab3" . $notas->idnota . "' id='sab3" . $notas->idnota . "' class='form-control'  value='" . $notas->sab3 . "' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='sab4" . $notas->idnota . "' id='sab4" . $notas->idnota . "' class='form-control '  value='" . $notas->sab4 . "' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)' ></td>";
			$row[] = "<td><input  type='number' name='sab5" . $notas->idnota . "' id='sab5" . $notas->idnota . "' class='form-control'  value='" . $notas->sab5 . "' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)' ></td>";
			$row[] = "<td><input  type='number' name='sab6" . $notas->idnota . "' id='sab6" . $notas->idnota . "' class='form-control'  value='" . $notas->sab6 . "' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td ><input  type='number' name='promsab" . $notas->idnota . "' id='promsab" . $notas->idnota . "' class='form-control bg-primary-300' style='color:black; ' value='" . $notas->promsab . "' size='6' readonly='true'></td>";


			$row[] = "<td><input  type='number' name='hac1" . $notas->idnota . "' id='hac1" . $notas->idnota . "' class='form-control'  value='" . $notas->hac1 . "' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac2" . $notas->idnota . "' id='hac2" . $notas->idnota . "' class='form-control'  value='" . $notas->hac2 . "' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac3" . $notas->idnota . "' id='hac3" . $notas->idnota . "' class='form-control'  value='" . $notas->hac3 . "' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac4" . $notas->idnota . "' id='hac4" . $notas->idnota . "' class='form-control'  value='" . $notas->hac4 . "' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac5" . $notas->idnota . "' id='hac5" . $notas->idnota . "' class='form-control'  value='" . $notas->hac5 . "' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac6" . $notas->idnota . "' id='hac6" . $notas->idnota . "' class='form-control'  value='" . $notas->hac6 . "' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td ><input  type='number' name='promhac" . $notas->idnota . "' id='promhac" . $notas->idnota . "' class='form-control bg-primary-300' style='color:black;'  value='" . $notas->promhac . "' size='6' readonly='true'></td>";

			$row[] = "<td><input  type='number' name='dec1" . $notas->idnota . "' id='dec1" . $notas->idnota . "' class='form-control'  value='" . $notas->dec1 . "' min = '1'
max = '10' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promdec(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='dec2" . $notas->idnota . "' id='dec2" . $notas->idnota . "' class='form-control'  value='" . $notas->dec2 . "' min = '1'
max = '10' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promdec(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='dec3" . $notas->idnota . "' id='dec3" . $notas->idnota . "' class='form-control'  value='" . $notas->dec3 . "' min = '1'
max = '10' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promdec(this.value,this.id)'></td>";
			$row[] = "<td ><input  type='number' name='promdec" . $notas->idnota . "' id='promdec" . $notas->idnota . "' class='form-control bg-primary-300' style='color:black;' value='" . $notas->promdec . "' size='6' readonly='true'></td>";

			$row[] = "<td><input  type='number' name='autoser" . $notas->idnota . "' id='autoser" . $notas->idnota . "' class='form-control'  value='" . $notas->autoser . "' min = '1'
max = '5' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,5);autoser(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='autodec" . $notas->idnota . "' id='autodec" . $notas->idnota . "' class='form-control'  value='" . $notas->autodec . "' min = '1'
max = '5' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,5);autodec(this.value,this.id)'></td>";
			$row[] = "<td ><input  type='number' name='final" . $notas->idnota . "' id='final" . $notas->idnota . "' class='form-control bg-primary-300' style='color:black;'  value='" . $notas->final . "' size='10' readonly='true'></td>";

			//add html for action
			$row[] = '<a class="btn btn-sm bg-brown-300" href="javascript:void(0)" title="Edit" id="btn' . $notas->idnota . '" onclick="guardar_nota(' . "'" . $notas->idnota . "'" . ')"><i class="glyphicon glyphicon-ok"></i> Guardar</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->nota->count_all(),
			"recordsFiltered" => $this->nota->count_filtered_byid($idmat, $idcur, $idprof, $gestion, $bimestre),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function ajax_get_estadis($var)
	{
		$bimestre = "";
		$bi = substr($var, 0, 1);
		$idmat = substr($var, 2, strlen($var));

		$list = $this->nota->get_all_idmat($idmat);
		$gestion = $list->gestion;

		$list2 = $this->nota->get_estadistic($idmat, $bi, $gestion);
		foreach ($list2 as $final) {
			$notafinal = $final->final;
		}
	}

	public function ajax_set_indi()
	{
		$table = $this->input->post('table');
		$idindi = $this->input->post('idindi');
		$idmat = $this->input->post('idmat');
		$bimes = $this->input->post('bimes');
		$gestion = $this->input->post('gestion');
		$inpsab1 = $this->input->post('inpsab1');
		$inpsab2 = $this->input->post('inpsab2');
		$inpsab3 = $this->input->post('inpsab3');
		$inpsab4 = $this->input->post('inpsab4');
		$inpsab5 = $this->input->post('inpsab5');
		$inpsab6 = $this->input->post('inpsab6');
		$inphac1 = $this->input->post('inphac1');
		$inphac2 = $this->input->post('inphac2');
		$inphac3 = $this->input->post('inphac3');
		$inphac4 = $this->input->post('inphac4');
		$inphac5 = $this->input->post('inphac5');
		$inphac6 = $this->input->post('inphac6');


		$data = array(
			'idindi' => $idindi,
			'idmat' => $idmat,
			'bimestre' => $bimes,
			'gestion' => $gestion,
			'sab1' => $inpsab1,
			'sab2' => $inpsab2,
			'sab3' => $inpsab3,
			'sab4' => $inpsab4,
			'sab5' => $inpsab5,
			'sab6' => $inpsab6,
			'hac1' => $inphac1,
			'hac2' => $inphac2,
			'hac3' => $inphac3,
			'hac4' => $inphac4,
			'hac5' => $inphac5,
			'hac6' => $inphac6,
		);

		$insert = $this->nota->save_indi($table, $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_if_indi()
	{
		$table = $this->input->post('table');
		$idmat = $this->input->post('idmat');
		$bimes = $this->input->post('bimes');
		$gestion = $this->input->post('gestion');

		$data = $this->nota->indi_if($table, $idmat, $bimes, $gestion);
		echo json_encode($data);
	}

	public function ajax_if_exit()
	{
		$existe = false;
		$table = $this->input->post('table');
		$idmat = $this->input->post('idmat');
		$bimes = $this->input->post('bimes');
		$gestion = $this->input->post('gestion');

		$num_rows = $this->nota->indi_if_exit($table, $idmat, $bimes, $gestion);
		if ($num_rows > 0) {
			$existe = true;
		} else
			$existe = false;

		$output = array(
			"status" => TRUE,
			"data" => $existe,
		);
		echo json_encode($output);
	}

	public function gene_estu()
	{

		/*f$lista_est = $this->nota->generar_estudiante(2019);
		$lista_mate = $this->nota->materia_lis(2019);
		foreach ($lista_est as $estud)
		{
			$this->nota->insertar_asig_estu($estud->idest,$estud->idcurso,$estud->nivel,$estud->gestion);
		}
		foreach ($lista_mate as $mate)
		{
			$this->nota->insertar_asig_mate($mate->idcurso,$mate->idmat,$mate->idprof,$mate->gestion);
		}*/
		//$prosores = $this->nota->profesor_list();
		//$estudiantes = $this->nota->estudiante_list(2019);
		$estudiantes = $this->nota->list_estudiante(2019);
		//$materias = $this->nota->materias_lis();
		//$cursos = $this->nota->asiginar_curso_lis();

		$bimestre = $this->nota->bimestre_list();
		$areass = $this->nota->areas_list();
		/*foreach ($prosores as $prof)
		{
			$this->nota->insertar_profesores($prof->item,$prof->ci,'',$prof->appaterno,$prof->apmaterno,$prof->nombres,$prof->direccion,$prof->telefono,'',$prof->genero,'');
		}*/
		$idp = 0;
		foreach ($estudiantes as $estud) {
			$idp++;
			$n = '';
			$col = '';
			if ($estud->nivel == 'PRIMARIA MAÑANA') {
				$n = 'PM';
			}
			if ($estud->nivel == 'PRIMARIA TARDE') {
				$n = 'PT';
			}
			if ($estud->nivel == 'SECUNDARIA MAÑANA') {
				$n = 'SM';
			}
			if ($estud->nivel == 'SECUNDARIA TARDE') {
				$n = 'ST';
			}
			if ($estud->colegio == 'DON BOSCO A') {
				$col = '1';
			}
			if ($estud->colegio == 'DON BOSCO B') {
				$col = '2';
			}
			if ($estud->colegio == 'TECNICO HUMANISTICO DON BOSCO') {
				$col = '3';
			}
			$this->nota->insertar_estudiantes($estud->rude, $estud->ci, $estud->extension, $estud->appaterno, $estud->apmaterno, $estud->nombres, $estud->genero, $estud->codigo, '');
			$asig = $this->nota->profmate($estud->cod, $n);
			$cod = $estud->cod . '-' . $n . '-' . $col;
			$this->nota->insertar_nota_prom($cod, false, false, $estud->vivecon, 2019, $idp);

			foreach ($bimestre as $bi) {
				foreach ($areass as $ar) {
					$this->nota->insertar_notas($cod, 0, 0, 0, 0, 0, 2019, $ar->id_area, $bi->id_bi, $idp);
				}
			}
			foreach ($asig as $ag) {
				foreach ($bimestre as $bi) {
					if ($idp == 1) {
						$this->nota->insertar_nota_bi($ag->codigo, 2019, $ag->id_asg_prof, $bi->id_bi, $idp);
					}
				}
			}
		}
		/*foreach ($materias as $mat)
		{
			foreach ($cursos as $cur)
			{
				$id=$cur->codigo.'-'.$cur->id_asg_cur.'-'.$mat->id_mat;
				$this->nota->insertar_asiginar_materiacu($id,$cur->id_asg_cur,$mat->id_mat);
			}
		}*/
	}
	public function subir()
	{
		//$excel=$_FILES['planilla'];
		//$excel['name'];
		//print_r($excel['name']);
		//exit();
		//print_r($_POST);
		//exit();
		$nombre_carpeta = './public/planillas';
		if (!is_dir($nombre_carpeta)) {
			@mkdir($nombre_carpeta, 0700);
		}
		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = 'xlsx|xls';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('planilla')) {
			echo 'no subio';
		} else {
			$excel = $this->upload->data();
		}
		$nombre = $excel['full_path'];
		try {
			$inputFileType = PHPExcel_IOFactory::identify($nombre);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($nombre);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($nombre, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
		//$objPHPExcel = PHPEXCEL_IOFactory::load('salas.xls');


		$objPHPExcel->setActiveSheetIndex(0);

		$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

		//echo '<table border=1><tr><td>Producto</td><td>Precio</td><td>Existencia</td></tr>';
		$area = $objPHPExcel->getActiveSheet()->getCell('AE4')->getCalculatedValue();
		$prof = $objPHPExcel->getActiveSheet()->getCell('AE5')->getCalculatedValue();
		$matria = $objPHPExcel->getActiveSheet()->getCell('AE6')->getCalculatedValue();
		$bi = $objPHPExcel->getActiveSheet()->getCell('AE7')->getCalculatedValue();
		$curso = $objPHPExcel->getActiveSheet()->getCell('AE8')->getCalculatedValue();
		$gestion = $objPHPExcel->getActiveSheet()->getCell('AE9')->getCalculatedValue();
		$ni = $objPHPExcel->getActiveSheet()->getCell('AE10')->getCalculatedValue();
		//echo $area;
		//echo $matria;
		// $nota=$this->nota->get_estu_bi($id);
		// 	foreach ($nota as $notas) 
		//         {
		//         	$codigo=$notas->codigo;
		//         }
		//         $cod=explode('-', $codigo.'-', -1);
		//echo $cod[5]."</br>";

		// echo '<tr>';
		// echo '<td>'.$area.'</td>';
		// echo '<td>'.$prof.'</td>';
		// echo '<td>'.$matria.'</td>';
		// echo '<td>'.$bi.'</td>';
		// echo '</tr>';
		// echo '</table>';
		$c = 0;
		for ($i = 14; $i <= $numRows; $i++) {

			$id = $objPHPExcel->getActiveSheet()->getCell('AE' . $i)->getCalculatedValue();
			$ser1 = $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
			$ser2 = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
			$ser3 = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
			$serprom = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
			$saber1 = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
			$saber2 = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
			$saber3 = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
			$saber4 = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
			$saber5 = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
			$saberac1 = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
			$saberac2 = $objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue();
			$saberprom = $objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
			$hacer1 = $objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue();
			$hacer2 = $objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue();
			$hacer3 = $objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue();
			$hacer4 = $objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue();
			$hacer5 = $objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue();
			$hacerac1 = $objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue();
			$hacerac2 = $objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue();
			$hacerprom = $objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue();
			$decidir1 = $objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue();
			$decidir2 = $objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();
			$decidir3 = $objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue();
			$decidirprom = $objPHPExcel->getActiveSheet()->getCell('AA' . $i)->getCalculatedValue();
			$serau = $objPHPExcel->getActiveSheet()->getCell('AB' . $i)->getCalculatedValue();
			$decau = $objPHPExcel->getActiveSheet()->getCell('AC' . $i)->getCalculatedValue();
			$final = $objPHPExcel->getActiveSheet()->getCell('AD' . $i)->getCalculatedValue();

			if ($id == null and $c == 0) {

				$nan = $objPHPExcel->getActiveSheet()->getCell('K' . ($i + 3))->getCalculatedValue();
				$ava = $objPHPExcel->getActiveSheet()->getCell('K' . ($i + 4))->getCalculatedValue();
				$this->db->query("call insertar_tema('" . $ni . "','" . $curso . "'," . $prof . "," . $bi . "," . $nan . "," . $ava . "," . $matria . "," . $gestion . ");");
				// echo '<tr>';
				// echo '<td>'.$nan.'</td>';
				// echo '<td>'.$ava.'</td>';
				// echo '</tr>';
				$c = 1;
			}

			if ($id != null and $final != null) {
				// echo '<table border=1>';
				// echo '<tr>';
				// echo '<td>'.$id.'</td>';
				// echo '<td>'.$ser1.'</td>';
				// echo '<td>'.$ser2.'</td>';
				// echo '<td>'.$ser3.'</td>';
				// echo '<td>'.$serprom.'</td>';
				// echo '<td>'.$saber1.'</td>';
				// echo '<td>'.$saber2.'</td>';
				// echo '<td>'.$saber3.'</td>';
				// echo '<td>'.$saber4.'</td>';
				// echo '<td>'.$saber5.'</td>';
				// echo '<td>'.$saberac1.'</td>';
				// echo '<td>'.$saberac2.'</td>';
				// echo '<td>'.$saberprom.'</td>';
				// echo '<td>'.$hacer1.'</td>';
				// echo '<td>'.$hacer2.'</td>';
				// echo '<td>'.$hacer3.'</td>';
				// echo '<td>'.$hacer4.'</td>';
				// echo '<td>'.$hacer5.'</td>';
				// echo '<td>'.$hacerac1.'</td>';
				// echo '<td>'.$hacerac2.'</td>';
				// echo '<td>'.$hacerprom.'</td>';
				// echo '<td>'.$decidir1.'</td>';
				// echo '<td>'.$decidir2.'</td>';
				// echo '<td>'.$decidir3.'</td>';
				// echo '<td>'.$decidirprom.'</td>';
				// echo '<td>'.$serau.'</td>';
				// echo '<td>'.$decau.'</td>';
				// echo '<td>'.$final.'</td>';
				// echo '</tr>';
				// echo '</table>';
				if ($ser1 == null) {
					$ser1 = 0;
				}
				if ($ser2 == null) {
					$ser2 = 0;
				}
				if ($ser3 == null) {
					$ser3 = 0;
				}
				if ($saber1 == null) {
					$saber1 = 0;
				}
				if ($saber2 == null) {
					$saber2 = 0;
				}
				if ($saber3 == null) {
					$saber3 = 0;
				}
				if ($saber4 == null) {
					$saber4 = 0;
				}
				if ($saber5 == null) {
					$saber5 = 0;
				}
				if ($saberac1 == null) {
					$saberac1 = 0;
				}
				if ($saberac2 == null) {
					$saberac2 = 0;
				}
				if ($hacer1 == null) {
					$hacer1 = 0;
				}
				if ($hacer2 == null) {
					$hacer2 = 0;
				}
				if ($hacer3 == null) {
					$hacer3 = 0;
				}
				if ($hacer4 == null) {
					$hacer4 = 0;
				}
				if ($hacer5 == null) {
					$hacer5 = 0;
				}
				if ($hacerac1 == null) {
					$hacerac1 = 0;
				}
				if ($hacerac2 == null) {
					$hacerac2 = 0;
				}
				if ($decidir1 == null) {
					$decidir1 = 0;
				}
				if ($decidir2 == null) {
					$decidir2 = 0;
				}
				if ($decidir3 == null) {
					$decidir3 = 0;
				}

				$this->db->query("call insertar_(" . $id . "," . $ser1 . "," . $ser2 . "," . $ser3 . "," . $serprom . "," . $saber1 . "," . $saber2 . "," . $saber3 . "," . $saber4 . "," . $saber5 . ",0," . $saberac1 . "," . $saberac2 . "," . $saberprom . "," . $hacer1 . "," . $hacer2 . "," . $hacer3 . "," . $hacer4 . "," . $hacer5 . ",0," . $hacerac1 . "," . $hacerac2 . "," . $hacerprom . "," . $decidir1 . "," . $decidir2 . "," . $decidir3 . "," . $decidirprom . "," . $serau . "," . $decau . "," . $final . "," . $matria . "," . $bi . "," . $area . ");");

				// $notass=$this->nota->get_estu_bi($id);
				// foreach ($notass as $notas1) 
				//        {
				//        	$id_est=$notas1->id_est;
				//        }
				// $areas=$this->nota->areas($cod[5]);
				// foreach ($areas as $areas1) 
				//        {
				//        	$id_area=$areas1->id_area;
				//        }
				//        $areas_est=$this->nota->get_nota_area($id_est,$id_area);

				//        foreach ($areas_est as $areas_ests) 
				//        {
				//        	$nota1=$areas_ests->notabi1;
				//        }
				// $data_materia= array(
				// 	'notabi1'=>$final);
				// $this->nota->update_nota_materia($id_est,$cod[5],$data_materia);
				// $data_area= array(
				// 	'notabi1'=>($nota1+$final)/2);
				// $this->nota->update_nota_area($id_est,$id_area,$data_area);

				// $data = array(
				// 	'ser1'=>$ser1,
				// 	'ser2'=>$ser2,
				// 	'ser3'=>$ser3,
				// 	'pmser'=>$serprom,
				// 	'saber1'=>$saber1,
				// 	'saber2'=>$saber2,
				// 	'saber3'=>$saber3,
				// 	'saber4'=>$saber4,
				// 	'saber5'=>$saber5,
				// 	'saber6'=>"",
				// 	'saberac1'=>$saberac1,
				// 	'saberac2'=>$saberac2,
				// 	'pmsaber'=>$saberprom,
				// 	'hacer1'=>$hacer1,
				// 	'hacer2'=>$hacer2,
				// 	'hacer3'=>$hacer3,
				// 	'hacer4'=>$hacer4,
				// 	'hacer5'=>$hacer5,
				// 	'hacer6'=>"",
				// 	'hacerac1'=>$hacerac1,
				// 	'hacerac2'=>$hacerac2,
				// 	'pmhacer'=>$hacerprom,
				// 	'dec1'=>$decidir1,
				// 	'dec2'=>$decidir2,
				// 	'dec3'=>$decidir3,
				// 	'pmdecidir'=>$decidirprom,
				// 	'ser_est'=>$serau,
				// 	'dec_est'=>$decau,
				// 	'final'=>$final);
				// $this->nota->update_notabimestre($id,$data);


				//$sql = "INSERT INTO productos (nombre, precio, existencia) VALUE('$nombre','$precio','$existencia')";
				//$result = $mysqli->query($sql);
			}
		}
		echo "<script type='text/javascript'>alert('Se Subio');</script>";
		redirect(base_url() . 'Not_notas_contr/', 'refresh');
		//echo '</table>';
		//alert("se inserto correctamente");

	}
	public function notas_exel($id)
	{
		$id = str_replace("%20", " ", $id);
		$ids = explode('W', $id, -1);
		/* print_r($ids);
		exit();*/
		$gestion = $ids[0];
		$cursos = $ids[1];
		$c = $ids[1];
		$id_prof = $ids[2];
		$mat = $ids[3];
		$bimestre = $ids[4];
		$nivel = $ids[5];
		$mat1 = $this->nota->materiass($mat);
		$cur = $this->nota->get_cursos($cursos);
		$profe = $this->nota->get_profes($id_prof);
		$nive = $this->nota->niveles($nivel);

		if ($nivel == 2) {
			$cod = $cursos . "-ST-";
		}
		if ($nivel == 4) {
			$cod = $cursos . "-SM-";
		}
		if ($nivel == 1) {
			$cod = $cursos . "-PT-";
		}
		if ($nivel == 3) {
			$cod = $cursos . "-PM-";
		}

		$asig = $this->nota->list_asig($cod, $mat, $id_prof);

		foreach ($asig as $asigs) {
			$id_asg_prof = $asigs->id_asg_prof;
		}

		//$id_asg_prof=68;

		$test = $this->nota->list_test($id_asg_prof);

		foreach ($test as $tests) {
			$id_test = $tests->test_id;
		}
		foreach ($mat1 as $mat1S) {
			$materias = $mat1S->nombre;
		}
		foreach ($nive as $nives) {
			$niveles = $nives->nivel . " " . $nives->turno;
		}
		foreach ($profe as $profes) {
			$profesores = $profes->nombres;
		}
		foreach ($cur as $curs) {
			$cursos = $curs->nombre;
		}

		$list = $this->nota->list_test_estu($id_test, $cod);
		//print_r($id_asg_prof);
		//  //exit();

		//   print_r($test);
		// print_r($list);
		// exit();
		// print_r($list);
		// exit();
		//$examen=$this->nota->list_oction_e($id_test);




		//$list=$this->nota->list_oction_estu($examens->id);
		$estilo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '045aaa')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$estilobor = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			)
		);

		foreach ($test as $tests) {
			$contador1 = 3;
			//Le aplicamos ancho las columnas.
			$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
			$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
			$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);

			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($estilo);
			//$this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NIVEL');
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $niveles);
			$contador1++;
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($estilo);
			// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'GESTION');
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", " " . $gestion);
			$contador1++;
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($estilo);
			// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'MATERIA');
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $materias);
			$contador1++;
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($estilo);
			// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'CURSO');
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $cursos);
			$contador1++;
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($estilo);
			// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'PROFESOR');
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $profesores);
			// $contador1++;$contador1++;

			//Le aplicamos negrita a los títulos de la cabecera.
			$this->excel->getActiveSheet()->getStyle("A{$contador1}:C{$contador1}")->applyFromArray($estilo);


			//Definimos los títulos de la cabecera.
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
			$this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'NOTAS');
			$list = $this->nota->list_test_estu($tests->test_id, $cod);
			$x = 1;
			if ($list[1]->nota != 0) {
				foreach ($list as $estud) {
					$contador1++;
					$this->excel->getActiveSheet()->getStyle("A{$contador1}:C{$contador1}")->applyFromArray($estilobor);
					$this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
					$this->excel->getActiveSheet()->setCellValue("B{$contador1}", ' ' . $estud->nombres);
					$this->excel->getActiveSheet()->setCellValue("C{$contador1}", round($estud->nota));
					$x++;
				}
			}
		}
		//Le ponemos un nombre al archivo que se va a generar.


		$archivo = "{$cursos}_{$niveles}_{$gestion}_{$materias}.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $archivo . '"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');
	}

	public function buscar_nota_estudiante($notas, $id_est)
	{
		$nota = null;
		foreach ($notas as $key => $nt) {
			if ($nt->id_est == $id_est) {
				$nota = $nt->total;
				break;
			}
		}
		return $nota;
	}

	public function d_planilla_notas($id)
	{
		$ids = explode('W', $id, -1);
		$gestion = $ids[0];
		$curso = $ids[1];
		$prof = $ids[2];
		$materia = $ids[3];
		$bimestre = $ids[4];
		$nivel = $ids[5];
		$id_asg_prof = $ids[6];
		$cole = $this->nota->get_nivel_colegio($nivel);
		$list_cod_prof = $this->nota->list_cod_prof($gestion, $curso, $cole->codigo, $materia, $prof);
		//print_r($list_cod_prof);
		//exit();
		$cursos = $this->nota->get_cursos1($curso);
		$mat = $this->nota->materiass($materia);
		$profe = $this->nota->get_profes($prof);
		$are = $this->nota->areas($materia);
		$bim = $this->nota->get_bimestre($bimestre);
		$current_nivel = $this->nota->get_nivel_colegio($nivel);

		$list = $this->estudiantes->get_datatables_by_idcur($gestion, $curso, $current_nivel->codigo);
		$temas = $this->nota->get_current_temas($prof, $current_nivel->codigo, $curso, $materia, $bimestre, $gestion);
		$tareas = $this->nota->get_current_tareas($prof, $bimestre, $gestion);

		$notas_primer_trimestre = $this->nota->getNotasTrimestre(5, $id_asg_prof, $gestion);
		$notas_segundo_trimestre = $this->nota->getNotasTrimestre(6, $id_asg_prof, $gestion);
		//$notas_primer_trimestre = $this->nota->getNotasTrimestre(5, 2484, 2021);

		/*foreach ($notas_primer_trimestre as $key => $npt) {
			echo json_encode($npt);
			echo '<br><br>';
		}

		if (!$notas_primer_trimestre)
			echo 'oh siii vacio';
		else
			echo $this->buscar_nota_estudiante($notas_primer_trimestre, 1079);*/


		$generated_empty = false;

		if (isset($tareas)) {
			// Ejecutar algoritmo para llenar notas
			$tipos_notas = array(); // separar notas de haber y saber
			$student_notas = array(); // ordernar notas por temas
			foreach ($temas as $key => $tema) {
				$entry_tipo_nota = false;
				foreach ($tareas as $key => $tarea) {
					if ($tema->id == $tarea->id_tema) {
						// Verificamos el tipo de calificacion
						if (!$entry_tipo_nota) {
							$tipos_notas[$tema->id] = (object) ['tipo' => $tarea->tipo];
							$entry_tipo_nota = true;
						}
						// Validamos que la nota este 1 > nota < 35
						$nota = $tarea->nota;
						if ($tarea->nota > 35) {
							$nota = 35;
						}
						if ($tarea->nota <= 0) {
							$nota = 1;
						}
						$student_notas[$tarea->id_est][$tarea->id_tema] = (object) ['nota' => $nota];
						//var_dump($student_notas[2386][6546]->nota);id_est, id_tema
					}
				}

				// Verificamos el tipo de calificacion
				if (!$entry_tipo_nota) {
					$tipos_notas[$tema->id] = (object) ['tipo' => 'H'];
				}
			}
		} else {
			// Generar vacio
			$generated_empty = true;
		}

		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('notas');

		$contador = 1;

		$rojo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fa6737')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$naranja = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ffb748')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$amarillo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f9f966')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$verde = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '95e886')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$estilo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '045aaa')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$estilobor = array(
			'font'  => array(
				//'bold'  => true,
				'size'  => 9,
				//'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			)
		);


		$notas = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				//'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' =>  PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$punt = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdf7ee')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$act = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd1d1d1')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$titulo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 20,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$titulo2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);

		$titulo2Small = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);

		$titulo_n = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$titulo3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f2f2f2')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$ser = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c5ffe0')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$final = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c7fff6')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);

		$serStyle = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c6ffe0')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$saber = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'bbe1fe')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$hacer = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fedebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$extraStyle = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fc99ad')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$ac = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff6f6f')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$decir = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdfebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$est = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff99ad')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$esta1 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '00a3da')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$esta2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '90a8f9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$esta3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'face92')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$negra = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID
			)
		);

		$rowStyle = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		//Le aplicamos ancho las columnas.
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40); //NOMBRE ESTUDIANTE
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(3); //SER
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3); //SABER
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(3); //HACER
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(3); //DECIDIR
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(4); //DIMENSIONES (ESTUDIANTE)
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(4); //TRIMESTRES Y PROMEDIOS
		$this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(4);

		$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022');
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);

		//Titulo principal
		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($titulo);
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->getFont()->setBold(true);
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO PEDAGOGICO ' . $bim->nombre);
		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setWidth(58);
		$this->drawing->setHeight(68);
		$this->drawing->setCoordinates('AC1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());

		$contador++;


		//Asignar Codigos
		$this->excel->getActiveSheet()->getColumnDimension('AE')->setVisible(false); //OCULTAR
		$this->excel->getActiveSheet()->setCellValue("AE1", $gestion);
		$this->excel->getActiveSheet()->setCellValue("AE2", $prof);
		$this->excel->getActiveSheet()->setCellValue("AE3", $materia);
		$this->excel->getActiveSheet()->setCellValue("AE4", $bimestre);
		$this->excel->getActiveSheet()->setCellValue("AE5", $cole->codigo);
		$this->excel->getActiveSheet()->setCellValue("AE6", $are->id_area);
		$this->excel->getActiveSheet()->setCellValue("AE7", $curso);
		$this->excel->getActiveSheet()->setCellValue("AE8", $list_cod_prof->id_asg_prof);

		// Asignar Información de cabecera
		// $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'UNIDAD EDUCATIVA: '.$id);
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'UNIDAD EDUCATIVA: ');
		$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cole->nombre);
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'AREA: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", $are->area);

		$contador++;
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'AÑO DE ESCOLARIDAD: ');
		$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cursos->nombre);
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'MATERIA: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", $mat->nombre);

		$contador++;
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'MAESTRO (A): ');
		$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $profe->nombres);
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'GESTION: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", " " . $gestion);

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN MAESTRA (O)');

		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($titulo2Small);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'A. EST.');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("AB".($contador).":AA".($contador));

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'DIMENSIONES');

		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'DIM');

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":G" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:G{$contador}")->applyFromArray($serStyle);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'SER 10pt'); //SER

		$this->excel->setActiveSheetIndex(0)->mergeCells("H" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->getStyle("H{$contador}:O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'SABER 35pt'); //SABER

		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("P{$contador}:W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'HACER 35pt'); //HACER

		$this->excel->setActiveSheetIndex(0)->mergeCells("X" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("X{$contador}:AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'DECIDIR 10pt'); //DECIDIR

		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AB{$contador}")->applyFromArray($extraStyle);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'SER'); //SER (ESTUDIANTE)

		$this->excel->getActiveSheet()->getStyle("AC{$contador}:AC{$contador}")->applyFromArray($extraStyle);
		$this->excel->getActiveSheet()->setCellValue("AC{$contador}", 'DEC'); //DECIDIR (ESTUDIANTE)

		$contador++;
		//$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AC{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'VAR. EVAL');
		//$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($serStyle);
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'PM'); //SER
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("J{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("L{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("N{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'PM'); //SABER
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("U{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PM'); //HACER
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Z{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PM'); //DECIDIR

		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", '5'); //SER (ESTUDIANTE)
		$this->excel->getActiveSheet()->setCellValue("AC{$contador}", '5'); //DECIDIR (ESTUDIANTE)

		$contador++;
		//Le aplicamos negrita a los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($act);
		//Definimos los títulos de la cabecera.
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'ACTIVIDADES'); //ACTIVIDADES
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->getAlignment()->setTextRotation(90);
		//$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("H{$contador}:N{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("P{$contador}:V{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("X{$contador}:Z{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador - 4) . ":A" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A" . ($contador - 4) . ":A{$contador}")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->getStyle("A" . ($contador - 4) . ":B" . ($contador - 4))->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("A" . ($contador - 4), 'N');
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador - 4) . ":B" . ($contador));

		$this->excel->getActiveSheet()->setCellValue("B" . ($contador - 4), 'NOMBRES Y APELLIDOS');

		// Cambiar indicadores por trimestre y nivel
		if ($current_nivel->codigo == 'SM') {
			// SER
			$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'Responsabilidad');
			// DECIDIR
			$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'Honestidad');
		}
		if ($current_nivel->codigo == 'PM') {
			// SER
			$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'Respeto');
			$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'Responsabilidad');
			if ($bim->nombre == 'PRIMER TRIMESTRE') {
				// DECIDIR
				$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'Amabilidad');
				$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'Compromiso');
			}
			if ($bim->nombre == 'SEGUNDO TRIMESTRE') {
				// DECIDIR
				$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'Constancia');
				$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'Participación');
			}
			if ($bim->nombre == 'TERCER TRIMESTRE') {
				// DECIDIR
				$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'Disiplina');
				$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'Honestidad');
			}
		}
		// $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'Responsabilidad');
		// $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'Respeto');
		$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($serStyle);
		$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'PROMEDIO SER');
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'PROMEDIO SABER');
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PROMEDIO HACER');
		// $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'Compromiso');
		// $this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'Participación');
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PROMEDIO DECIDIR');

		$this->excel->setActiveSheetIndex(0)->mergeCells("AD" . ($contador - 4) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AD" . ($contador - 4))->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AD" . ($contador - 4) . ":AD{$contador}")->applyFromArray($final);
		$this->excel->getActiveSheet()->setCellValue("AD" . ($contador - 4), 'TOTAL TRIMESTRAL');

		// NOTAS TRIMESTRALES Y PROMEDIOS
		$this->excel->getActiveSheet()->setCellValue("AG{$contador}", '1er Trimestre');
		$this->excel->getActiveSheet()->getStyle("AG" . ($contador))->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AG{$contador}")->applyFromArray($extraStyle);
		$this->excel->getActiveSheet()->setCellValue("AH{$contador}", '2do Trimestre');
		$this->excel->getActiveSheet()->getStyle("AH" . ($contador))->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AH{$contador}")->applyFromArray($extraStyle);
		$this->excel->getActiveSheet()->setCellValue("AI{$contador}", '3er Trimestre');
		$this->excel->getActiveSheet()->getStyle("AI" . ($contador))->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AI{$contador}")->applyFromArray($extraStyle);
		$this->excel->getActiveSheet()->setCellValue("AJ{$contador}", 'Promedio');
		$this->excel->getActiveSheet()->getStyle("AJ" . ($contador))->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AJ{$contador}")->applyFromArray($extraStyle);

		$x = 1;

		foreach ($list as $estud) {
			$contador++;

			// Bordes generales por fila
			$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($rowStyle);
			$this->excel->getActiveSheet()->getStyle("AG{$contador}:AJ{$contador}")->applyFromArray($rowStyle);
			// SER
			$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			// SABER
			$this->excel->getActiveSheet()->getStyle("H{$contador}:N{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			// HACER
			$this->excel->getActiveSheet()->getStyle("P{$contador}:V{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			// DECIDIR
			$this->excel->getActiveSheet()->getStyle("X{$contador}:Z{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			// ESTUDIANTE
			$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

			// VALIDACION SER
			$validation_s = $this->excel->getActiveSheet()->getDataValidation("D{$contador}:F{$contador}");
			$validation_s->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$validation_s->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$validation_s->setAllowBlank(true);
			$validation_s->setShowInputMessage(true);
			$validation_s->setShowErrorMessage(true);
			$validation_s->setErrorTitle('Error de codificacion');
			$validation_s->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$validation_s->setPromptTitle('Validacion de datos');
			$validation_s->setPrompt('La nota Solo se permiten de 1 a 10');
			$validation_s->setFormula1('1');
			$validation_s->setFormula2('10');

			// VALIDACION NOTAS SABER/HACER
			$validation_sh = $this->excel->getActiveSheet()->getDataValidation("H{$contador}:V{$contador}");
			$validation_sh->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$validation_sh->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$validation_sh->setAllowBlank(true);
			$validation_sh->setShowInputMessage(true);
			$validation_sh->setShowErrorMessage(true);
			$validation_sh->setErrorTitle('Error de codificacion');
			$validation_sh->setError('La nota Solo se permiten de 1 a 35!!!!!!!!');
			$validation_sh->setPromptTitle('Validacion de datos');
			$validation_sh->setPrompt('La nota Solo se permiten de 1 a 35');
			$validation_sh->setFormula1('1');
			$validation_sh->setFormula2('35');

			// VALIDACION DECIDIR
			$validation_d = $this->excel->getActiveSheet()->getDataValidation("X{$contador}:Z{$contador}");
			$validation_d->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$validation_d->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$validation_d->setAllowBlank(true);
			$validation_d->setShowInputMessage(true);
			$validation_d->setShowErrorMessage(true);
			$validation_d->setErrorTitle('Error de codificacion');
			$validation_d->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$validation_d->setPromptTitle('Validacion de datos');
			$validation_d->setPrompt('La nota Solo se permiten de 1 a 10');
			$validation_d->setFormula1('1');
			$validation_d->setFormula2('10');

			// VALIDACION ESTUDIANTE
			$validation_e = $this->excel->getActiveSheet()->getDataValidation("AB{$contador}:AC{$contador}");
			$validation_e->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$validation_e->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$validation_e->setAllowBlank(true);
			$validation_e->setShowInputMessage(true);
			$validation_e->setShowErrorMessage(true);
			$validation_e->setErrorTitle('Error de codificacion');
			$validation_e->setError('La nota Solo se permiten de 1 a 5!!!!!!!!');
			$validation_e->setPromptTitle('Validacion de datos');
			$validation_e->setPrompt('La nota Solo se permiten de 1 a 5');
			$validation_e->setFormula1('1');
			$validation_e->setFormula2('5');

			// FORMULA SER
			$this->excel->getActiveSheet()->setCellValue("G{$contador}", "=ROUND(AVERAGE(D{$contador}:F{$contador}),0)");
			// FORMULA SABER
			$this->excel->getActiveSheet()->setCellValue("O{$contador}", "=ROUND(AVERAGE(H{$contador}:N{$contador}),0)");
			// FORMULA HACER
			$this->excel->getActiveSheet()->setCellValue("W{$contador}", "=ROUND(AVERAGE(P{$contador}:V{$contador}),0)");
			// FORMULA DECIDIR
			$this->excel->getActiveSheet()->setCellValue("AA{$contador}", "=ROUND(AVERAGE(X{$contador}:Z{$contador}),0)");
			// FORMULA TOTAL TRIMESTRAL
			$this->excel->getActiveSheet()->setCellValue("AD{$contador}", "=ROUND(SUM(G{$contador}+O{$contador}+W{$contador}+AA{$contador}+AB{$contador}+AC{$contador}),0)");

			// FORMULA TOTAL TRIMESTRAL HACIA PRIMER O SEGUNDO O TERCER TRIMESTRE
			if ($bim->nombre == 'PRIMER TRIMESTRE') {
				$this->excel->getActiveSheet()->setCellValue("AG{$contador}", "=ROUND(SUM(G{$contador}+O{$contador}+W{$contador}+AA{$contador}+AB{$contador}+AC{$contador}),0)");
			}
			if ($bim->nombre == 'SEGUNDO TRIMESTRE') {
				if ($notas_primer_trimestre) {
					$this->excel->getActiveSheet()->setCellValue("AG{$contador}", $this->buscar_nota_estudiante($notas_primer_trimestre, $estud->id_est));
				}
				$this->excel->getActiveSheet()->setCellValue("AH{$contador}", "=ROUND(SUM(G{$contador}+O{$contador}+W{$contador}+AA{$contador}+AB{$contador}+AC{$contador}),0)");
			}
			if ($bim->nombre == 'TERCER TRIMESTRE') {
				if ($notas_primer_trimestre) {
					$this->excel->getActiveSheet()->setCellValue("AG{$contador}", $this->buscar_nota_estudiante($notas_primer_trimestre, $estud->id_est));
				}
				if ($notas_segundo_trimestre) {
					$this->excel->getActiveSheet()->setCellValue("AH{$contador}", $this->buscar_nota_estudiante($notas_segundo_trimestre, $estud->id_est));
				}
				$this->excel->getActiveSheet()->setCellValue("AI{$contador}", "=ROUND(SUM(G{$contador}+O{$contador}+W{$contador}+AA{$contador}+AB{$contador}+AC{$contador}),0)");
			}







			// PROMEDIO DE LA GESTIÓN
			$this->excel->getActiveSheet()->setCellValue("AJ{$contador}", "=ROUND(AVERAGE(AG{$contador}:AI{$contador}), 0)");

			// $this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($notas);
			// $this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($estilobor);
			// $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($titulo_n);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":C" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
			$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno . " " . $estud->apmaterno . " " . $estud->nombre);

			if (!$generated_empty) { //Se genera con DATOS
				$saber_count = 1;
				$hacer_count = 1;
				foreach ($temas as $key => $tema) {
					if ($tipos_notas[$tema->id]->tipo == 'S') {
						if (isset($student_notas[$estud->id_est][$tema->id]->nota)) {
							if ($saber_count == 1) {
								$this->excel->getActiveSheet()->setCellValue("H9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("H{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($saber_count == 2) {
								$this->excel->getActiveSheet()->setCellValue("I9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("I{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($saber_count == 3) {
								$this->excel->getActiveSheet()->setCellValue("J9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("J{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($saber_count == 4) {
								$this->excel->getActiveSheet()->setCellValue("K9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("K{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($saber_count == 5) {
								$this->excel->getActiveSheet()->setCellValue("L9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("L{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($saber_count == 6) {
								$this->excel->getActiveSheet()->setCellValue("M9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("M{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($saber_count == 7) {
								$this->excel->getActiveSheet()->setCellValue("N9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("N{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
						}
						$saber_count++;
					}
					if ($tipos_notas[$tema->id]->tipo == 'H') {
						if (isset($student_notas[$estud->id_est][$tema->id]->nota)) {
							if ($hacer_count == 1) {
								$this->excel->getActiveSheet()->setCellValue("P9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("P{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($hacer_count == 2) {
								$this->excel->getActiveSheet()->setCellValue("Q9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("Q{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($hacer_count == 3) {
								$this->excel->getActiveSheet()->setCellValue("R9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("R{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($hacer_count == 4) {
								$this->excel->getActiveSheet()->setCellValue("S9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("S{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($hacer_count == 5) {
								$this->excel->getActiveSheet()->setCellValue("T9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("T{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($hacer_count == 6) {
								$this->excel->getActiveSheet()->setCellValue("U9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("U{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
							if ($hacer_count == 7) {
								$this->excel->getActiveSheet()->setCellValue("V9", $tema->tema); // fecha
								$this->excel->getActiveSheet()->setCellValue("V{$contador}", $student_notas[$estud->id_est][$tema->id]->nota);
							}
						}
						$hacer_count++;
					}
				}

				$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($serStyle);
				$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
				$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
				$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
				$this->excel->getActiveSheet()->getStyle("AD{$contador}")->applyFromArray($final);
				$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $estud->id_est);
				$x++;
			}
		}

		//cuadro estadistico
		$contador = $contador + 2;
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:AC{$contador}")->applyFromArray($esta1);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'CUADRO ESTADISTICO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":D" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:D{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'TEMAS TRIMESTRALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("E" . ($contador) . ":N" . ($contador));
		$this->excel->getActiveSheet()->getStyle("E{$contador}:N{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'TEMAS ANUALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:S{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'ESTUDIANTES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":Z" . ($contador));
		$this->excel->getActiveSheet()->getStyle("T{$contador}:Z{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", 'APRO. Y REPRO');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AA" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AA{$contador}:AC{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PROM. CURSO');
		$contador++;
		$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'Nº DE TEMAS TRIMESTRALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":D" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:D{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("C{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("E" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("E{$contador}:L{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'Nº DE TEMAS ANUALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":N" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("M{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":R" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:R{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'INSCRITOS');
		$this->excel->getActiveSheet()->getStyle("S{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", $x - 1);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("T{$contador}:W{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", '');
		$this->excel->getActiveSheet()->getStyle("X{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'Nº');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":Z" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:Z{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '%');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		$this->excel->getActiveSheet()->getStyle("AA" . ($contador) . ":AC" . ($contador + 2))->applyFromArray($notas);
		$this->excel->setActiveSheetIndex(0)->mergeCells("AA" . ($contador) . ":AC" . ($contador + 2));
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", "=ROUND(SUM(AD10:AD" . ($contador - 4) . ")/S" . ($contador + 2) . ",0)");

		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "ASAS");

		$contador++;
		$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'Nº DE TEMAS TRIMESTRALES AVANZADOS');
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":D" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:D{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("C{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("E" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("E{$contador}:L{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'Nº DE TEMAS AVANZADOS');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":N" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("M{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":R" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:R{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'RETIRADOS');
		$this->excel->getActiveSheet()->getStyle("S{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("T{$contador}:W{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", 'PROMOVIDOS');
		$this->excel->getActiveSheet()->getStyle("X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", '=COUNTIF(AD10:AD' . ($contador - 5) . ',">=51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":Z" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:Z{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '=ROUND((X' . ($contador) . '*100)/S' . ($contador + 1) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		$contador++;
		$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", '% DE AVANCE CURRICULAR TRIMESTRAL');
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":D" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:D{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", '=ROUND((C' . ($contador - 1) . '*100)/C' . ($contador - 2) . ',0)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("E" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("E{$contador}:L{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", '% DE AVANCE CURRICULAR');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":N" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", '=ROUND((M' . ($contador - 1) . '*100)/M' . ($contador - 2) . ',0)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":R" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:R{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'EFECTIVOS');
		$this->excel->getActiveSheet()->getStyle("S{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", '=S' . ($contador - 2) . '-S' . ($contador - 1));
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("T{$contador}:W{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", 'RETENIDOS');
		$this->excel->getActiveSheet()->getStyle("X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", '=COUNTIF(AD10:AD' . ($contador - 6) . ',"<51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":Z" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:Z{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '=ROUND((X' . ($contador) . '*100)/S' . ($contador) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador-2).":Y".($contador));  
		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AD14:AD".($contador-6).")/Q".($contador).",0)");
		$contador = $contador + 6;

		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "PROF (A) " . $profe->nombres);
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", "FIRMA Y SELLO DEL DIRECTOR (A)");
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "C.I. " . $profe->ci);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AB" . ($contador));

		// $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::VERTICAL_LANDSCAPE);
		$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

		try {
			$this->excel->getActiveSheet()->setBreak("A{$contador}", PHPExcel_Worksheet::BREAK_ROW);
			$this->excel->getActiveSheet()->getPageSetup()->setPrintArea("A1:AD{$contador}");
			//Le ponemos un nombre al archivo que se va a generar.
			$archivo = "{$profe->nombres}_{$curso}_{$cole->codigo}_{$bim->codigo}_{$gestion}.xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $archivo . '"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
			//Hacemos una salida al navegador con el archivo Excel.
			$objWriter->save('php://output');
			echo json_encode(array("status" => TRUE));
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	public function d_planilla1($id)
	{
		$ids = explode('W', $id, -1);

		$gestion = $ids[0];
		$curso = $ids[1];
		$prof = $ids[2];
		$materia = $ids[3];
		$bimestre = $ids[4];
		$nivel = $ids[5];
		$cole = $this->nota->get_nivel_colegio($nivel);
		$list_cod_prof = $this->nota->list_cod_prof($gestion, $curso, $cole->codigo, $materia, $prof);
		//print_r($list_cod_prof);
		//exit();
		$cursos = $this->nota->get_cursos1($curso);
		$mat = $this->nota->materiass($materia);
		$profe = $this->nota->get_profes($prof);
		$are = $this->nota->areas($materia);
		$bim = $this->nota->get_bimestre($bimestre);

		$list = $this->nota->get_curso_student($gestion, $bimestre, $list_cod_prof->id_asg_prof);
		//print_r($list);
		//exit();

		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('notas');

		$contador = 1;

		$rojo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fa6737')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$naranja = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ffb748')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$amarillo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f9f966')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$verde = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '95e886')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$estilo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '045aaa')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$estilobor = array(
			'font'  => array(
				//'bold'  => true,
				'size'  => 9,
				//'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			)
		);
		$notas = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				//'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' =>  PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$punt = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdf7ee')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$act = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd1d1d1')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);



		$titulo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 20,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$titulo_n = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f2f2f2')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$ser = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c5ffe0')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$final = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c7fff6')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$saber = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'bbe1fe')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$hacer = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fedebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$ac = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff6f6f')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$decir = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdfebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$est = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff99ad')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta1 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '00a3da')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '90a8f9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'face92')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$negra = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID

			)
		);


		//Le aplicamos ancho las columnas.
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(3); //MAESTRO
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(5);

		$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022');
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);
		$contador = $contador + 3;
		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($titulo);
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->getFont()->setBold(true);
		//asinar Codigos 
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setVisible(false); //OCULTAR
		$this->excel->getActiveSheet()->setCellValue("AD1", $gestion);
		$this->excel->getActiveSheet()->setCellValue("AD2", $prof);
		$this->excel->getActiveSheet()->setCellValue("AD3", $materia);
		$this->excel->getActiveSheet()->setCellValue("AD4", $bimestre);
		$this->excel->getActiveSheet()->setCellValue("AD5", $cole->codigo);
		$this->excel->getActiveSheet()->setCellValue("AD6", $are->id_area);
		$this->excel->getActiveSheet()->setCellValue("AD7", $curso);

		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO PEDAGÓGICO ');

		$contador++;
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'UNIDAD EDUCATIVA: ');
		$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cole->nombre);
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'AREA: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", $are->area);
		$contador++;
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'AÑO DE ESCOLARIDAD: ');
		$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cursos->nombre);
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'MATERIA: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", $mat->nombre);
		$contador++;

		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'MAESTRO (A): ');
		$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $profe->nombres);
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'GESTION: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", " " . $gestion);

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AB" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AB{$contador}")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN MAESTRA (O)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AB" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AB{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'DIMENSIONES');
		$contador++;

		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":M" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:M{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'SABER 45pt'); //saber
		$this->excel->setActiveSheetIndex(0)->mergeCells("N" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("N{$contador}:W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'HACER 45pt'); //HACER
		$this->excel->setActiveSheetIndex(0)->mergeCells("X" . ($contador) . ":AB" . ($contador));
		$this->excel->getActiveSheet()->getStyle("X{$contador}:AB{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'SER/DEC 10pt'); //HACER

		$contador++;
		//$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AC{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'VAR. EVAL');
		//$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("J{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("L{$contador}", '45');
		$this->excel->getActiveSheet()->getStyle("M{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'PM'); //SABER
		$this->excel->getActiveSheet()->setCellValue("N{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("U{$contador}", '45');
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '45');
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Z{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("AB{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'PM'); //DECIDIR
		$contador++;
		//Le aplicamos negrita a los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($act);



		//Definimos los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->getAlignment()->setTextRotation(90);
		//$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);


		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador - 4) . ":A" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A" . ($contador - 4) . ":A{$contador}")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->getStyle("A" . ($contador - 4) . ":B" . ($contador - 4))->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("A" . ($contador - 4), 'N');
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador - 4) . ":B" . ($contador));

		$this->excel->getActiveSheet()->setCellValue("B" . ($contador - 4), 'NOMBRES Y APELLIDOS');

		$this->excel->getActiveSheet()->getStyle("M{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->getStyle("AB{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'PROMEDIO SABER');
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PROMEDIO HACER');
		// $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'PARTICIPACION');//DECIDIR
		// $this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'SOLID. Y HONEST.');//DECIDIR
		// $this->excel->getActiveSheet()->setCellValue("Z{$contador}", 'COMUNICACION');//DECIDIR
		// $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'DISCIPLINA');
		// $this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'RESPON. PUNTUAL');
		// $this->excel->getActiveSheet()->setCellValue("Z{$contador}", 'HONESTIDAD');
		// $this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'PROMEDIO SER/DEC');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AC" . ($contador - 4) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AC" . ($contador - 4))->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AC" . ($contador - 4) . ":AC{$contador}")->applyFromArray($final);
		$this->excel->getActiveSheet()->setCellValue("AC" . ($contador - 4), 'TOTAL BIMESTRAL');



		$x = 1;

		foreach ($list as $estud) {
			$contador++;
			$this->excel->getActiveSheet()->getStyle("D{$contador}:L{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("N{$contador}:V{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("X{$contador}:AA{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

			$var5 = $this->excel->getActiveSheet()->getDataValidation("D{$contador}:V{$contador}");
			$var5->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var5->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var5->setAllowBlank(true);
			$var5->setShowInputMessage(true);
			$var5->setShowErrorMessage(true);
			$var5->setErrorTitle('Error de codificacion');
			$var5->setError('La nota Solo se permiten de 1 a 45!!!!!!!!');
			$var5->setPromptTitle('Validacion de datos');
			$var5->setPrompt('La nota Solo se permiten de 1 a 45');
			$var5->setFormula1('1');
			$var5->setFormula2('45');

			$var2 = $this->excel->getActiveSheet()->getDataValidation("X{$contador}:AB{$contador}");
			$var2->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var2->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var2->setAllowBlank(true);
			$var2->setShowInputMessage(true);
			$var2->setShowErrorMessage(true);
			$var2->setErrorTitle('Error de codificacion');
			$var2->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$var2->setPromptTitle('Validacion de datos');
			$var2->setPrompt('La nota Solo se permiten de 1 a 10');
			$var2->setFormula1('1');
			$var2->setFormula2('10');


			$this->excel->getActiveSheet()->setCellValue("M{$contador}", "=ROUND(AVERAGE(D{$contador}:L{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("W{$contador}", "=ROUND(AVERAGE(N{$contador}:V{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("AB{$contador}", "=ROUND(AVERAGE(X{$contador}:AA{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("AC{$contador}", "=ROUND(SUM(M{$contador}+W{$contador}+AB{$contador}),0)");

			$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($notas);
			$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($titulo_n);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":C" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
			$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno . " " . $estud->apmaterno . " " . $estud->nombre);

			$this->excel->getActiveSheet()->getStyle("M{$contador}")->applyFromArray($saber);
			$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
			$this->excel->getActiveSheet()->getStyle("AB{$contador}")->applyFromArray($decir);
			$this->excel->getActiveSheet()->getStyle("AC{$contador}")->applyFromArray($final);
			$this->excel->getActiveSheet()->setCellValue("AD{$contador}", $estud->id);
			$x++;
		}
		//cuadro estadistica
		$contador = $contador + 2;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AC{$contador}")->applyFromArray($esta1);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'CUADRO ESTADISTICO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:L{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'TEMA');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":Q" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:Q{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'ESTUDIANTES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:X{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'APRO. Y REPRO');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:AC{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'PROM. CURSO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Nº DE TEMAS ANUALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'INSCRITOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", $x - 1);
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", 'Nº');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '%');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		$this->excel->getActiveSheet()->getStyle("Y" . ($contador) . ":AC" . ($contador + 2))->applyFromArray($notas);
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":AC" . ($contador + 2));
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AC13:AC" . ($contador - 4) . ")/Q" . ($contador + 2) . ",0)");

		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "ASAS");

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Nº DE TEMAS AVANZADOS');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'RETIRADOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'PROMOVIDOS');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '=COUNTIF(AC13:AC' . ($contador - 5) . ',">=51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '=ROUND((V' . ($contador) . '*100)/Q' . ($contador + 1) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", '% DE AVANCE CURRICULAR');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '=ROUND((K' . ($contador - 1) . '*100)/K' . ($contador - 2) . ',0)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'EFECTIVOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '=Q' . ($contador - 2) . '-Q' . ($contador - 1));
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'RETENIDOS');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '=COUNTIF(AC13:AC' . ($contador - 6) . ',"<51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '=ROUND((V' . ($contador) . '*100)/Q' . ($contador) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador-2).":Y".($contador));  
		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AD14:AD".($contador-6).")/Q".($contador).",0)");
		$contador = $contador + 6;

		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "PROF (A) " . $profe->nombres);
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", "FIRMA Y SELLO DEL DIRECTOR (A)");
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "C.I. " . $profe->ci);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AB" . ($contador));

		//$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::VERTICAL_LANDSCAPE);
		//$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->excel->getActiveSheet()->setBreak("A{$contador}", PHPExcel_Worksheet::BREAK_ROW);
		$this->excel->getActiveSheet()->getPageSetup()->setPrintArea("A1:AC{$contador}");
		//Le ponemos un nombre al archivo que se va a generar.
		$archivo = "{$curso}_{$nivel}_{$gestion}.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $archivo . '"');
		header('Cache-Control: max-age=0');
		//$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');

		echo json_encode(array("status" => TRUE));
	}

	public function d_planilla($id) //planilla de la gestion 2019
	{
		$id = str_replace("%20", " ", $id);
		$ids = explode('W', $id, -1);
		print_r($ids);
		exit();
		$gestion = $ids[0];
		$cursos = $ids[1];
		$prof = $ids[2];
		$materia = $ids[3];
		$bimestre = $ids[4];
		$nivel = $ids[5];
		$mat = $this->nota->materiass($materia);
		$cur = $this->nota->get_cursos($cursos);
		$profe = $this->nota->get_profes($prof);
		$are = $this->nota->areas($materia);
		$nive = $this->nota->niveles($nivel);
		$bim = $this->nota->get_bimestre($bimestre);

		//$list=$this->nota->get_prof_estudiante($gestion,$prof,$materia,$cursos);
		$asigmateria = $this->nota->get_asig_materia($gestion, $materia, $cursos, $nivel, $prof);

		foreach ($asigmateria as $asigmateria1) {
			$idmaterias = $asigmateria1->id_asg_mate;
		}
		// print_r($asigmateria);
		$list1 = $this->nota->get_idasp($gestion, $prof, $idmaterias);
		// print_r($gestion."-");
		// print_r($idmaterias."-");
		// print_r($prof."-");
		// print_r($list1);
		// exit();
		foreach ($list1 as $idss) {
			$ida = $idss->id_asg_prof;
		}
		//print_r($ids);
		//print_r($asigmateria);
		//print_r($list1);
		//exit();
		$list = $this->nota->estudiante_curso($gestion, $bimestre, $ida);
		// print_r($gestion."-");
		// print_r($bimestre."-");
		// print_r($ida."-");
		// print_r($list);
		//      exit();
		$cole = $this->nota->list_col($nivel);

		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('notas');

		$contador = 1;

		$rojo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fa6737')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$naranja = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ffb748')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$amarillo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f9f966')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$verde = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '95e886')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$estilo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '045aaa')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$estilobor = array(
			'font'  => array(
				//'bold'  => true,
				'size'  => 9,
				//'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			)
		);
		$notas = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				//'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' =>  PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$punt = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdf7ee')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$act = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd1d1d1')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);



		$titulo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 20,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo_n = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f2f2f2')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$ser = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c5ffe0')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$final = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c7fff6')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$saber = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'bbe1fe')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$hacer = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fedebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$ac = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff6f6f')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$decir = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdfebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$est = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff99ad')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta1 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '00a3da')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '90a8f9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'face92')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$negra = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID

			)
		);


		//Le aplicamos ancho las columnas.
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(3); //MAESTRO
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(5);

		$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022');
		//$this->excel->getActiveSheet()->SetCellValue("AB7", "=IFERROR((D7/E7),0)");
		//$this->excel->getActiveSheet()->protectCells("G{$contador}", "PHP"); 
		//$this->excel->getActiveSheet()->getProtection()->setPassword('123'); 
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);
		$contador = $contador + 3;;
		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());

		//$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":AB".($contador));

		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($titulo);
		// $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->getFont()->setBold(true);
		foreach ($bim as $bii) {
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO PEDAGÓGICO ' . $bii->nombre);
			$bimess = $bii->id_bi;
		}
		//$this->excel->getActiveSheet()->freezePane('A14');

		$contador++;
		foreach ($cur as $cu) {
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'UNIDAD EDUCATIVA: ');
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
			foreach ($cole as $co) {
				$this->excel->getActiveSheet()->setCellValue("C{$contador}", $co->col);
				$cole = $co->col;
			}
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $prof);
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'CAMPO: ');
				$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("T{$contador}", $m->campo);

				$aresass = $m->id_area;
			}
			$this->excel->getActiveSheet()->setCellValue("AE4", $aresass);
			$contador++;
			foreach ($nive as $niv) {
				$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NIVEL: ');
				$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("C{$contador}", $niv->nombre);
				$ni = $niv->codigo;
				$this->excel->getActiveSheet()->setCellValue("AE10", $ni);
			}
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $materia);
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'AREA: ');
				$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("T{$contador}", $m->area);
			}
			$contador++;
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'AÑO DE ESCOLARIDAD: ');
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cu->nombre);
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $bimestre);
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'MATERIA: ');
				$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("T{$contador}", $m->materia);
				$materiass = $m->materia;
			}
			$c = $cu->curso;
		}

		$contador++;
		foreach ($profe as $p) {

			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'MAESTRO (A): ');
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("C{$contador}", $p->nombres);
			$nombrepro = $p->nombres;
			$cipro = $p->ci;
		}
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'GESTION: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", " " . $gestion);
		$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $cursos);
		$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $cursos);
		//id proferor materia
		$this->excel->getActiveSheet()->setCellValue("AE11", $ida);


		$contador++;
		$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $gestion);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->applyFromArray($titulo2);
		//$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN DEL MAESTRO '.$bimestre.' BIMESTRE');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN MAESTRA (O)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($titulo_n);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'A. EST.');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'DIMENSIONES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'DIM');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":G" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:G{$contador}")->applyFromArray($ser);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'SER 10pt'); //ser 
		$this->excel->setActiveSheetIndex(0)->mergeCells("H" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->getStyle("H{$contador}:O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'SABER 35pt'); //saber
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("P{$contador}:W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'HACER 35pt'); //HACER
		$this->excel->setActiveSheetIndex(0)->mergeCells("X" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("X{$contador}:AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'DECIDIR 10pt'); //HACER
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($est);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'SER');
		// $this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("AC{$contador}", 'DEC');
		$contador++;
		//$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AC{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'VAR. EVAL');
		//$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($ser);
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'PM'); //SER

		// $this->excel->getActiveSheet()->getStyle("H{$contador}:N{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("J{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("L{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($ac);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("N{$contador}", '35'); //SABER
		$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
		//$this->excel->getActiveSheet()->getStyle("O{$contador}:U{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'PM'); //SABER
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("U{$contador}:V{$contador}")->applyFromArray($ac);
		$this->excel->getActiveSheet()->setCellValue("U{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PM'); //HACER

		//$this->excel->getActiveSheet()->getStyle("V{$contador}:Y{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Z{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PM'); //DECIDIR

		//$this->excel->getActiveSheet()->getStyle("Z{$contador}:AA{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", '5');
		$this->excel->getActiveSheet()->setCellValue("AC{$contador}", '5');
		$contador++;
		//Le aplicamos negrita a los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($act);



		//Definimos los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->getAlignment()->setTextRotation(90);
		//$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);

		$this->excel->getActiveSheet()->getStyle("H{$contador}:M{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("O{$contador}:T{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("Z{$contador}:AA{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador - 4) . ":A" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A9:A13")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->getStyle("A9:B9")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("A9", 'N');
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador - 4) . ":B" . ($contador));

		$this->excel->getActiveSheet()->setCellValue("B9", 'NOMBRES Y APELLIDOS');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'ACTIVIDADES');
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'DISCIPLINA');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'RESPON. PUNTUAL');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", 'HONESTIDAD');
		$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($ser);
		$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'PROMEDIO SER');
		$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($ac); //ACC
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'ADAPTACIÓN C. 1');
		$this->excel->getActiveSheet()->setCellValue("N{$contador}", 'ADAPTACIÓN C. 2');
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'PROMEDIO SABER');
		$this->excel->getActiveSheet()->getStyle("U{$contador}:V{$contador}")->applyFromArray($ac); //AC
		$this->excel->getActiveSheet()->setCellValue("U{$contador}", 'ADAPTACIÓN C. 1');
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", 'ADAPTACIÓN C. 2');
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PROMEDIO HACER');
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'PARTICIPACION'); //DECIDIR
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'SOLID. Y HONEST.'); //DECIDIR
		$this->excel->getActiveSheet()->setCellValue("Z{$contador}", 'COMUNICACION'); //DECIDIR
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PROMEDIO DECIDIR');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AD" . ($contador - 4) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AD9")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AD9:AD13")->applyFromArray($final);
		$this->excel->getActiveSheet()->setCellValue("AD9", 'TOTAL BIMESTRAL');

		$this->excel->getActiveSheet()->getColumnDimension('AE')->setVisible(false); //OCULTAR


		//('P'.$i,'=IFERROR
		//    	(
		//    		VLOOKUP(A'.$i.'Dat_STATUS_EXP;2;FALSO);=IFERROR(ROUND(AVERAGE(D{$contador}:F{$contador}),0),0)
		//    	0)+Q'.$i)
		//$this->excel->getActiveSheet()->setCellValue("G{$contador}", "=SUM(D{$contador}:F{$contador})");
		// $this->excel->getActiveSheet()->getStyle("D{$contador}:AA{$contador}")->getNumberFormat()->setFormatCode('0');

		//$this->excel->getActiveSheet()->getCell("D{$contador}:AA{$contador}")->getDataType(PHPExcel_Cell_DataType::TYPE_NUMERIC);

		//=SI(Y(AB7>=51);VERDADERO; FALSO) 
		//$this->excel->getActiveSheet()->setCellValue("AC{$contador}", "=IF(AND(AB7>=51),APROBADO,REPROBADO)"); 
		//$this->excel->getActiveSheet()->setCellValue("AC{$contador}", "=COUNTIF"); 
		//$this->excel->getActiveSheet()->getStyle("A{$contador}:B{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED); 
		//valuidar las celdas 





		$x = 1;

		foreach ($list as $estud) {
			$contador++;
			$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("H{$contador}:N{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("P{$contador}:V{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("X{$contador}:Z{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->setCellValue("G{$contador}", "=ROUND(AVERAGE(D{$contador}:F{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("O{$contador}", "=ROUND(AVERAGE(H{$contador}:N{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("W{$contador}", "=ROUND(AVERAGE(P{$contador}:V{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("AA{$contador}", "=ROUND(AVERAGE(X{$contador}:Z{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("AD{$contador}", "=ROUND(SUM(G{$contador}+O{$contador}+W{$contador}+AA{$contador}+AB{$contador}+AC{$contador}),0)");


			//validacion 
			$var5 = $this->excel->getActiveSheet()->getDataValidation("AB{$contador}:AC{$contador}");
			$var5->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var5->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var5->setAllowBlank(true);
			$var5->setShowInputMessage(true);
			$var5->setShowErrorMessage(true);
			$var5->setErrorTitle('Error de codificacion');
			$var5->setError('La nota Solo se permiten de 1 a 5!!!!!!!!');
			$var5->setPromptTitle('Validacion de datos');
			$var5->setPrompt('La nota Solo se permiten de 1 a 5');
			$var5->setFormula1('1');
			$var5->setFormula2('5');
			//$sht5->setDataValidation("AB{$contador}:AC{$contador}", $var5);

			$var10 = $this->excel->getActiveSheet()->getDataValidation("D{$contador}:F{$contador}");
			$var10->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var10->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var10->setAllowBlank(true);
			$var10->setShowInputMessage(true);
			$var10->setShowErrorMessage(true);
			$var10->setErrorTitle('Error de codificacion');
			$var10->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$var10->setPromptTitle('Validacion de datos');
			$var10->setPrompt('La nota Solo se permiten de 1 a 10');
			$var10->setFormula1('1');
			$var10->setFormula2('10');

			$var10 = $this->excel->getActiveSheet()->getDataValidation("X{$contador}:Z{$contador}");
			$var10->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var10->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var10->setAllowBlank(true);
			$var10->setShowInputMessage(true);
			$var10->setShowErrorMessage(true);
			$var10->setErrorTitle('Error de codificacion');
			$var10->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$var10->setPromptTitle('Validacion de datos');
			$var10->setPrompt('La nota Solo se permiten de 1 a 10');
			$var10->setFormula1('1');
			$var10->setFormula2('10');
			// $sht10->setDataValidation("D{$contador}:F{$contador}", $var10);
			// $sht10->setDataValidation("X{$contador}:Z{$contador}", $var10);

			$var35 = $this->excel->getActiveSheet()->getDataValidation("H{$contador}:N{$contador}");
			$var35->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var35->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var35->setAllowBlank(true);
			$var35->setShowInputMessage(true);
			$var35->setShowErrorMessage(true);
			$var35->setErrorTitle('Error de codificacion');
			$var35->setError('La nota Solo se permiten de 1 a 35!!!!!!!!');
			$var35->setPromptTitle('Validacion de datos');
			$var35->setPrompt('La nota Solo se permiten de 1 a 35');
			$var35->setFormula1('1');
			$var35->setFormula2('35');

			$var35 = $this->excel->getActiveSheet()->getDataValidation("P{$contador}:V{$contador}");
			$var35->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var35->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var35->setAllowBlank(true);
			$var35->setShowInputMessage(true);
			$var35->setShowErrorMessage(true);
			$var35->setErrorTitle('Error de codificacion');
			$var35->setError('La nota Solo se permiten de 1 a 35!!!!!!!!');
			$var35->setPromptTitle('Validacion de datos');
			$var35->setPrompt('La nota Solo se permiten de 1 a 35');
			$var35->setFormula1('1');
			$var35->setFormula2('35');
			// $sht35->setDataValidation("H{$contador}:N{$contador}", $var35);
			// $sht35->setDataValidation("P{$contador}:V{$contador}", $var35);


			$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($notas);
			$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($titulo_n);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":C" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
			$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->nombres);
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $estud->id);
			$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($ser);

			$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($ac);
			$this->excel->getActiveSheet()->getStyle("U{$contador}:V{$contador}")->applyFromArray($ac);

			$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
			$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
			$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
			$this->excel->getActiveSheet()->getStyle("AD{$contador}")->applyFromArray($final);

			$x++;
		}
		$contador = $contador + 2;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AD{$contador}")->applyFromArray($esta1);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'CUADRO ESTADISTICO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:L{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'TEMA');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":Q" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:Q{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'ESTUDIANTES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:X{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'APRO. Y REPRO');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'PROM. DEL CURSO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Nº DE TEMAS ANUALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'INSCRITOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '' . ($contador - 17));
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", 'Nº');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '%');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		$this->excel->getActiveSheet()->getStyle("Y" . ($contador) . ":AD" . ($contador + 2))->applyFromArray($notas);
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":AD" . ($contador + 2));
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AD14:AD" . ($contador - 4) . ")/Q" . ($contador + 2) . ",0)");

		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "ASAS");

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Nº DE TEMAS AVANZADOS');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'RETIRADOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'PROMOVIDOS');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '=COUNTIF(AD14:AD' . ($contador - 5) . ',">=51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '=ROUND((V' . ($contador) . '*100)/Q' . ($contador + 1) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", '% DE AVANCE CURRICULAR');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '=ROUND((K' . ($contador - 1) . '*100)/K' . ($contador - 2) . ',0)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'EFECTIVOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '=Q' . ($contador - 2) . '-Q' . ($contador - 1));
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'RETENIDOS');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '=COUNTIF(AD14:AD' . ($contador - 6) . ',"<51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '=ROUND((V' . ($contador) . '*100)/Q' . ($contador) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador-2).":Y".($contador));  
		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AD14:AD".($contador-6).")/Q".($contador).",0)");
		$contador = $contador + 8;

		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "PROF (A) " . $nombrepro);
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", "FIRMA Y SELLO DEL DIRECTOR (A)");
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "C.I. " . $cipro);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AB" . ($contador));

		//$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::VERTICAL_LANDSCAPE);
		//$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->excel->getActiveSheet()->setBreak("A{$contador}", PHPExcel_Worksheet::BREAK_ROW);
		$this->excel->getActiveSheet()->getPageSetup()->setPrintArea("A1:AD{$contador}");
		$n = 1;

		$contador1 = 2;
		$this->excel->createSheet();
		$this->excel->setActiveSheetIndex($n);
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
		$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022');
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);

		// $this->drawing->setName('Logotipo1');
		// $this->drawing->setDescription('Logotipo1');
		// $img = imagecreatefrompng('assets/images/logo.png');
		// $this->drawing->setImageResource($img);
		// $this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		// $this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		// $this->drawing->setHeight(80);
		// $this->drawing->setCoordinates('A1');
		// $this->drawing->setWorksheet($this->excel->getActiveSheet());

		$this->excel->setActiveSheetIndex($n)->mergeCells("A" . ($contador1) . ":G" . ($contador1));
		$this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
		// $this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->getFont()->setBold(true);
		foreach ($bim as $bii) {
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NOTAS');
			$bimess = $bii->id_bi;
		}
		//$this->excel->getActiveSheet()->freezePane('A14');

		$contador1++;
		foreach ($cur as $cu) {
			foreach ($nive as $niv) {
				$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NIVEL: ');
				$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex($n)->mergeCells("C" . ($contador1) . ":G" . ($contador1));
				$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niv->nivel . " " . $niv->turno);
			}
			$contador1++;
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'AÑO DE ESCOLARIDAD: ');
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex($n)->mergeCells("C" . ($contador1) . ":G" . ($contador1));
			$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $cu->nombre);
			$contador1++;
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex($n)->mergeCells("A" . ($contador1) . ":B" . ($contador1));
				$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'MATERIA: ');
				$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex($n)->mergeCells("C" . ($contador1) . ":G" . ($contador1));
				$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $m->materia);
				$materiass = $m->materia;
			}
			$c = $cu->curso;
		}
		$contador1++;
		foreach ($profe as $p) {

			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'MAESTRO (A): ');
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex($n)->mergeCells("C" . ($contador1) . ":G" . ($contador1));
			$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $p->nombres);
			$nombrepro = $p->nombres;
			$cipro = $p->ci;
		}
		$contador1++;
		$this->excel->setActiveSheetIndex($n)->mergeCells("A" . ($contador1) . ":B" . ($contador1));
		$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'GESTION: ');
		$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex($n)->mergeCells("C" . ($contador1) . ":G" . ($contador1));
		$this->excel->getActiveSheet()->setCellValue("C{$contador1}", " " . $gestion);
		$contador1++;
		$contador1++;
		$this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo_n);
		$this->excel->setActiveSheetIndex($n)->mergeCells("C" . ($contador1) . ":G" . ($contador1));
		$this->excel->getActiveSheet()->setCellValue("C{$contador1}", " NOTAS");
		$contador1++;
		$this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo_n);
		$this->excel->setActiveSheetIndex($n)->mergeCells("A" . ($contador1 - 1) . ":A" . ($contador1));
		$this->excel->getActiveSheet()->setCellValue("A" . ($contador1 - 1), 'Nro');
		$this->excel->setActiveSheetIndex($n)->mergeCells("B" . ($contador1 - 1) . ":B" . ($contador1));
		$this->excel->getActiveSheet()->setCellValue("B" . ($contador1 - 1), 'NOMBRES Y APELLIDOS');
		$this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'BIM 1');
		$this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'BIM 2');
		$this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'BIM 3');
		$this->excel->getActiveSheet()->setCellValue("F{$contador1}", 'BIM 4');
		$this->excel->getActiveSheet()->setCellValue("G{$contador1}", 'FINAL');
		$not = 0;
		$not1 = 13;
		foreach ($list as $estud) {
			$contador1++;
			$not++;
			$not1++;
			$notamateria = $this->nota->notamateria($gestion, $estud->id_est, $materia);
			$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($titulo_n);
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", $not);
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud->nombres);
			//   print_r($gestion."-");
			//   print_r($estud->id_est."-");
			//   print_r($materia);
			// exit();
			foreach ($notamateria as $notamaterias) {

				$n1 = $notamaterias->notabi1;
				$n2 = $notamaterias->notabi2;
				$n3 = $notamaterias->notabi3;
				$n4 = $notamaterias->notabi4;
			}
			if ($bimestre == 1) {
				$this->excel->getActiveSheet()->getStyle("C{$contador1}:G{$contador1}")->applyFromArray($notas);
				$this->excel->getActiveSheet()->setCellValue("C{$contador1}", '=notas!AD' . ($not1) . '');
				$this->excel->getActiveSheet()->setCellValue("D{$contador1}", '-');
				$this->excel->getActiveSheet()->setCellValue("E{$contador1}", '-');
				$this->excel->getActiveSheet()->setCellValue("F{$contador1}", '-');
				$this->excel->getActiveSheet()->setCellValue("G{$contador1}", '=C' . ($not1) . '');
			}
			if ($bimestre == 2) {
				if ($n1 <= 50) {
					$estilo3 = $rojo;
				}
				if ($n1 >= 51) {
					$estilo3 = $naranja;
				}
				if ($n1 >= 70) {
					$estilo3 = $amarillo;
				}
				if ($n1 >= 90) {
					$estilo3 = $verde;
				}
				if ($n1 == '-') {
					$estilo3 = $notas;
				}
				$this->excel->getActiveSheet()->getStyle("C{$contador1}")->applyFromArray($estilo3);
				$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $n1);

				$this->excel->getActiveSheet()->getStyle("D{$contador1}:G{$contador1}")->applyFromArray($notas);
				$this->excel->getActiveSheet()->setCellValue("D{$contador1}", '=notas!AD' . ($not1) . '');
				$this->excel->getActiveSheet()->setCellValue("E{$contador1}", '-');
				$this->excel->getActiveSheet()->setCellValue("F{$contador1}", '-');
				$this->excel->getActiveSheet()->setCellValue("G{$contador1}", '=ROUND(AVERAGE(C' . ($contador1) . ':D' . ($contador1) . '),0)');
			}
			if ($bimestre == 3) {

				if ($n1 <= 50) {
					$estilo3 = $rojo;
				}
				if ($n1 >= 51) {
					$estilo3 = $naranja;
				}
				if ($n1 >= 70) {
					$estilo3 = $amarillo;
				}
				if ($n1 >= 90) {
					$estilo3 = $verde;
				}
				if ($n1 == '-') {
					$estilo3 = $notas;
				}
				$this->excel->getActiveSheet()->getStyle("C{$contador1}")->applyFromArray($estilo3);
				$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $n1);
				if ($n2 <= 50) {
					$estilo3 = $rojo;
				}
				if ($n2 >= 51) {
					$estilo3 = $naranja;
				}
				if ($n2 >= 70) {
					$estilo3 = $amarillo;
				}
				if ($n2 >= 90) {
					$estilo3 = $verde;
				}
				$this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo3);
				$this->excel->getActiveSheet()->setCellValue("D{$contador1}", $n2);

				$this->excel->getActiveSheet()->getStyle("E{$contador1}:G{$contador1}")->applyFromArray($notas);
				$this->excel->getActiveSheet()->setCellValue("E{$contador1}", '=notas!AD' . ($not1) . '');
				$this->excel->getActiveSheet()->setCellValue("F{$contador1}", '-');
				$this->excel->getActiveSheet()->setCellValue("G{$contador1}", '=ROUND(AVERAGE(C' . ($contador1) . ':E' . ($contador1) . '),0)');
			}
			if ($bimestre == 4) {
				if ($n1 <= 50) {
					$estilo3 = $rojo;
				}
				if ($n1 >= 51) {
					$estilo3 = $naranja;
				}
				if ($n1 >= 70) {
					$estilo3 = $amarillo;
				}
				if ($n1 >= 90) {
					$estilo3 = $verde;
				}
				if ($n1 == '-') {
					$estilo3 = $notas;
				}
				$this->excel->getActiveSheet()->getStyle("C{$contador1}")->applyFromArray($estilo3);
				$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $n1);
				if ($n2 <= 50) {
					$estilo3 = $rojo;
				}
				if ($n2 >= 51) {
					$estilo3 = $naranja;
				}
				if ($n2 >= 70) {
					$estilo3 = $amarillo;
				}
				if ($n2 >= 90) {
					$estilo3 = $verde;
				}
				if ($n2 == '-') {
					$estilo3 = $notas;
				}
				$this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo3);
				$this->excel->getActiveSheet()->setCellValue("D{$contador1}", $n2);
				if ($n3 <= 50) {
					$estilo3 = $rojo;
				}
				if ($n3 >= 51) {
					$estilo3 = $naranja;
				}
				if ($n3 >= 70) {
					$estilo3 = $amarillo;
				}
				if ($n3 >= 90) {
					$estilo3 = $verde;
				}
				if ($n3 == '-') {
					$estilo3 = $notas;
				}
				$this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilo3);
				$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $n3);

				$this->excel->getActiveSheet()->getStyle("F{$contador1}:G{$contador1}")->applyFromArray($notas);
				$this->excel->getActiveSheet()->setCellValue("F{$contador1}", '=notas!AD' . ($not1) . '');
				$this->excel->getActiveSheet()->setCellValue("G{$contador1}", '=ROUND(AVERAGE(C' . ($contador1) . ':F' . ($contador1) . '),0)');
			}
		}

		//Le ponemos un nombre al archivo que se va a generar.
		$archivo = "{$c}_{$materiass}_{$ni}_{$bimess}_{$prof}_{$gestion}.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $archivo . '"');
		header('Cache-Control: max-age=0');
		//$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');

		echo json_encode(array("status" => TRUE));
	}
	public function llenado_notas_exel($id)
	{
		$id = str_replace("%20", " ", $id);
		$ids = explode('W', $id, -1);
		// print_r($ids);
		// exit();
		$gestion = $ids[0];
		$cursos = $ids[1];
		$prof = $ids[2];
		$materia = $ids[3];
		$bimestre = $ids[4];
		$nivel = $ids[5];
		$mat = $this->nota->materiass($materia);
		$cur = $this->nota->get_cursos($cursos);
		$profe = $this->nota->get_profes($prof);
		$are = $this->nota->areas($materia);
		$nive = $this->nota->niveles($nivel);
		$bim = $this->nota->get_bimestre($bimestre);

		//$list=$this->nota->get_prof_estudiante($gestion,$prof,$materia,$cursos);
		$asigmateria = $this->nota->get_asig_materia($gestion, $materia, $cursos, $nivel);

		foreach ($asigmateria as $asigmateria1) {
			$idmaterias = $asigmateria1->id_asg_mate;
		}
		$list1 = $this->nota->get_idasp($gestion, $prof, $idmaterias);

		foreach ($list1 as $idss) {
			$ida = $idss->id_asg_prof;
		}
		//print_r($ids);
		//print_r($asigmateria);
		//print_r($list1);
		//exit();
		$list = $this->nota->estudiante_curso($gestion, $bimestre, $ida);
		$cole = $this->nota->list_col($nivel);

		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('notas');

		$contador = 1;

		$estilo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '045aaa')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$estilobor = array(
			'font'  => array(
				//'bold'  => true,
				'size'  => 9,
				//'color' => array('rgb' => 'ffffff'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
			)
		);
		$notas = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				//'color' => array('rgb' => 'd7eafa')
			),
			'alignment' => array(
				'horizontal' =>  PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$punt = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdf7ee')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$act = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'd1d1d1')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);



		$titulo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 20,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo_n = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '85b3ff')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$titulo3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'f2f2f2')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$ser = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c5ffe0')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$final = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'c7fff6')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$saber = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'bbe1fe')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$hacer = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fedebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$ac = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff6f6f')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$decir = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fdfebb')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$est = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ff99ad')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta1 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 12,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '00a3da')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta2 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 9,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '90a8f9')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$esta3 = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 8,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'face92')
			),
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

		$negra = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID

			)
		);


		//Le aplicamos ancho las columnas.
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(3); //MAESTRO
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(3);
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(5);

		$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022');
		//$this->excel->getActiveSheet()->SetCellValue("AB7", "=IFERROR((D7/E7),0)");
		//$this->excel->getActiveSheet()->protectCells("G{$contador}", "PHP"); 
		//$this->excel->getActiveSheet()->getProtection()->setPassword('123'); 
		$this->excel->getActiveSheet()->getProtection()->setSheet(true);
		$contador = $contador + 3;;
		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());

		//$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":AB".($contador));

		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($titulo);
		// $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->getFont()->setBold(true);
		foreach ($bim as $bii) {
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO PEDAGÓGICO ' . $bii->nombre);
			$bimess = $bii->id_bi;
		}
		$this->excel->getActiveSheet()->freezePane('A14');

		$contador++;
		foreach ($cur as $cu) {
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'UNIDAD EDUCATIVA: ');
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
			foreach ($cole as $co) {
				$this->excel->getActiveSheet()->setCellValue("C{$contador}", $co->col);
				$cole = $co->col;
			}
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $prof);
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'CAMPO: ');
				$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("T{$contador}", $m->campo);

				$aresass = $m->id_area;
			}
			$this->excel->getActiveSheet()->setCellValue("AE4", $aresass);
			$contador++;
			foreach ($nive as $niv) {
				$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NIVEL: ');
				$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("C{$contador}", $niv->nombre);
				$ni = $niv->codigo;
				$this->excel->getActiveSheet()->setCellValue("AE10", $ni);
			}
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $materia);
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'AREA: ');
				$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("T{$contador}", $m->area);
			}
			$contador++;
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'AÑO DE ESCOLARIDAD: ');
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cu->nombre);
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $bimestre);
			foreach ($are as $m) {
				$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'MATERIA: ');
				$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
				$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
				$this->excel->getActiveSheet()->setCellValue("T{$contador}", $m->materia);
				$materiass = $m->materia;
			}
			$c = $cu->curso;
		}

		$contador++;
		foreach ($profe as $p) {

			$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'MAESTRO (A): ');
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
			$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":O" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("C{$contador}", $p->nombres);
			$nombrepro = $p->nombres;
			$cipro = $p->ci;
		}
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":S" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'GESTION: ');
		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", " " . $gestion);
		$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $cursos);
		$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $cursos);


		$contador++;
		$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $gestion);
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->applyFromArray($titulo2);
		//$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN DEL MAESTRO '.$bimestre.' BIMESTRE');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN MAESTRA (O)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($titulo_n);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'A. EST.');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'DIMENSIONES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AB" . ($contador) . ":AC" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($titulo3);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'DIM');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":G" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:G{$contador}")->applyFromArray($ser);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'SER 10pt'); //ser 
		$this->excel->setActiveSheetIndex(0)->mergeCells("H" . ($contador) . ":O" . ($contador));
		$this->excel->getActiveSheet()->getStyle("H{$contador}:O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'SABER 35pt'); //saber
		$this->excel->setActiveSheetIndex(0)->mergeCells("P" . ($contador) . ":W" . ($contador));
		$this->excel->getActiveSheet()->getStyle("P{$contador}:W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'HACER 35pt'); //HACER
		$this->excel->setActiveSheetIndex(0)->mergeCells("X" . ($contador) . ":AA" . ($contador));
		$this->excel->getActiveSheet()->getStyle("X{$contador}:AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'DECIDIR 10pt'); //HACER
		$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->applyFromArray($est);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'SER');
		// $this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("AC{$contador}", 'DEC');
		$contador++;
		//$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AC{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'VAR. EVAL');
		//$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->applyFromArray($punt);
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($ser);
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'PM'); //SER

		// $this->excel->getActiveSheet()->getStyle("H{$contador}:N{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("J{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("L{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($ac);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("N{$contador}", '35'); //SABER
		$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
		//$this->excel->getActiveSheet()->getStyle("O{$contador}:U{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'PM'); //SABER
		$this->excel->getActiveSheet()->setCellValue("P{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("S{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("T{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("U{$contador}:V{$contador}")->applyFromArray($ac);
		$this->excel->getActiveSheet()->setCellValue("U{$contador}", '35');
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '35');
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PM'); //HACER

		//$this->excel->getActiveSheet()->getStyle("V{$contador}:Y{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", '10');
		$this->excel->getActiveSheet()->setCellValue("Z{$contador}", '10');
		$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PM'); //DECIDIR

		//$this->excel->getActiveSheet()->getStyle("Z{$contador}:AA{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("AB{$contador}", '5');
		$this->excel->getActiveSheet()->setCellValue("AC{$contador}", '5');
		$contador++;
		//Le aplicamos negrita a los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->applyFromArray($act);



		//Definimos los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:AD{$contador}")->getAlignment()->setTextRotation(90);
		//$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);

		$this->excel->getActiveSheet()->getStyle("H{$contador}:M{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("O{$contador}:T{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->getStyle("Z{$contador}:AA{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->setActiveSheetIndex(0)->mergeCells("A" . ($contador - 4) . ":A" . ($contador));
		$this->excel->getActiveSheet()->getStyle("A9:A13")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->getStyle("A9:B9")->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("A9", 'N');
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador - 4) . ":B" . ($contador));

		$this->excel->getActiveSheet()->setCellValue("B9", 'NOMBRES Y APELLIDOS');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'ACTIVIDADES');
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'DISCIPLINA');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'RESPON. PUNTUAL');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", 'HONESTIDAD');
		$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($ser);
		$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
		$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
		$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'PROMEDIO SER');
		$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($ac); //ACC
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'ADAPTACIÓN C. 1');
		$this->excel->getActiveSheet()->setCellValue("N{$contador}", 'ADAPTACIÓN C. 2');
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", 'PROMEDIO SABER');
		$this->excel->getActiveSheet()->getStyle("U{$contador}:V{$contador}")->applyFromArray($ac); //AC
		$this->excel->getActiveSheet()->setCellValue("U{$contador}", 'ADAPTACIÓN C. 1');
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", 'ADAPTACIÓN C. 2');
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PROMEDIO HACER');
		$this->excel->getActiveSheet()->setCellValue("X{$contador}", 'PARTICIPACION'); //DECIDIR
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'SOLID. Y HONEST.'); //DECIDIR
		$this->excel->getActiveSheet()->setCellValue("Z{$contador}", 'COMUNICACION'); //DECIDIR
		$this->excel->getActiveSheet()->setCellValue("AA{$contador}", 'PROMEDIO DECIDIR');
		$this->excel->setActiveSheetIndex(0)->mergeCells("AD" . ($contador - 4) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("AD9")->getAlignment()->setTextRotation(90);
		$this->excel->getActiveSheet()->getStyle("AD9:AD13")->applyFromArray($final);
		$this->excel->getActiveSheet()->setCellValue("AD9", 'TOTAL BIMESTRAL');

		$this->excel->getActiveSheet()->getColumnDimension('AE')->setVisible(false); //OCULTAR

		/*('P'.$i,'=IFERROR
        	(
        		VLOOKUP(A'.$i.'Dat_STATUS_EXP;2;FALSO);=IFERROR(ROUND(AVERAGE(D{$contador}:F{$contador}),0),0)
        	0)+Q'.$i)*/
		//$this->excel->getActiveSheet()->setCellValue("G{$contador}", "=SUM(D{$contador}:F{$contador})");
		// $this->excel->getActiveSheet()->getStyle("D{$contador}:AA{$contador}")->getNumberFormat()->setFormatCode('0');

		//$this->excel->getActiveSheet()->getCell("D{$contador}:AA{$contador}")->getDataType(PHPExcel_Cell_DataType::TYPE_NUMERIC);

		//=SI(Y(AB7>=51);VERDADERO; FALSO) 
		//$this->excel->getActiveSheet()->setCellValue("AC{$contador}", "=IF(AND(AB7>=51),APROBADO,REPROBADO)"); 
		//$this->excel->getActiveSheet()->setCellValue("AC{$contador}", "=COUNTIF"); 
		//$this->excel->getActiveSheet()->getStyle("A{$contador}:B{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED); 
		//valuidar las celdas 





		$x = 1;
		foreach ($list as $estud) {
			$contador++;
			$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("H{$contador}:N{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("P{$contador}:V{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("X{$contador}:Z{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->getStyle("AB{$contador}:AC{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$this->excel->getActiveSheet()->setCellValue("G{$contador}", "=ROUND(AVERAGE(D{$contador}:F{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("O{$contador}", "=ROUND(AVERAGE(H{$contador}:N{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("W{$contador}", "=ROUND(AVERAGE(P{$contador}:V{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("AA{$contador}", "=ROUND(AVERAGE(X{$contador}:Z{$contador}),0)");
			$this->excel->getActiveSheet()->setCellValue("AD{$contador}", "=ROUND(SUM(G{$contador}+O{$contador}+W{$contador}+AA{$contador}+AB{$contador}+AC{$contador}),0)");


			//validacion 
			$var5 = $this->excel->getActiveSheet()->getDataValidation("AB{$contador}:AC{$contador}");
			$var5->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var5->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var5->setAllowBlank(true);
			$var5->setShowInputMessage(true);
			$var5->setShowErrorMessage(true);
			$var5->setErrorTitle('Error de codificacion');
			$var5->setError('La nota Solo se permiten de 1 a 5!!!!!!!!');
			$var5->setPromptTitle('Validacion de datos');
			$var5->setPrompt('La nota Solo se permiten de 1 a 5');
			$var5->setFormula1('1');
			$var5->setFormula2('5');
			//$sht5->setDataValidation("AB{$contador}:AC{$contador}", $var5);

			$var10 = $this->excel->getActiveSheet()->getDataValidation("D{$contador}:F{$contador}");
			$var10->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var10->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var10->setAllowBlank(true);
			$var10->setShowInputMessage(true);
			$var10->setShowErrorMessage(true);
			$var10->setErrorTitle('Error de codificacion');
			$var10->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$var10->setPromptTitle('Validacion de datos');
			$var10->setPrompt('La nota Solo se permiten de 1 a 10');
			$var10->setFormula1('1');
			$var10->setFormula2('10');

			$var10 = $this->excel->getActiveSheet()->getDataValidation("X{$contador}:Z{$contador}");
			$var10->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var10->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var10->setAllowBlank(true);
			$var10->setShowInputMessage(true);
			$var10->setShowErrorMessage(true);
			$var10->setErrorTitle('Error de codificacion');
			$var10->setError('La nota Solo se permiten de 1 a 10!!!!!!!!');
			$var10->setPromptTitle('Validacion de datos');
			$var10->setPrompt('La nota Solo se permiten de 1 a 10');
			$var10->setFormula1('1');
			$var10->setFormula2('10');
			// $sht10->setDataValidation("D{$contador}:F{$contador}", $var10);
			// $sht10->setDataValidation("X{$contador}:Z{$contador}", $var10);

			$var35 = $this->excel->getActiveSheet()->getDataValidation("H{$contador}:N{$contador}");
			$var35->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var35->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var35->setAllowBlank(true);
			$var35->setShowInputMessage(true);
			$var35->setShowErrorMessage(true);
			$var35->setErrorTitle('Error de codificacion');
			$var35->setError('La nota Solo se permiten de 1 a 35!!!!!!!!');
			$var35->setPromptTitle('Validacion de datos');
			$var35->setPrompt('La nota Solo se permiten de 1 a 35');
			$var35->setFormula1('1');
			$var35->setFormula2('35');

			$var35 = $this->excel->getActiveSheet()->getDataValidation("P{$contador}:V{$contador}");
			$var35->setType(PHPExcel_Cell_DataValidation::TYPE_WHOLE);
			$var35->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_STOP);
			$var35->setAllowBlank(true);
			$var35->setShowInputMessage(true);
			$var35->setShowErrorMessage(true);
			$var35->setErrorTitle('Error de codificacion');
			$var35->setError('La nota Solo se permiten de 1 a 35!!!!!!!!');
			$var35->setPromptTitle('Validacion de datos');
			$var35->setPrompt('La nota Solo se permiten de 1 a 35');
			$var35->setFormula1('1');
			$var35->setFormula2('35');
			// $sht35->setDataValidation("H{$contador}:N{$contador}", $var35);
			// $sht35->setDataValidation("P{$contador}:V{$contador}", $var35);


			$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($notas);
			$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($titulo_n);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":C" . ($contador));
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
			$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->nombres);
			$this->excel->getActiveSheet()->setCellValue("AE{$contador}", $estud->id);
			$this->excel->getActiveSheet()->getStyle("G{$contador}")->applyFromArray($ser);

			$this->excel->getActiveSheet()->getStyle("M{$contador}:N{$contador}")->applyFromArray($ac);
			$this->excel->getActiveSheet()->getStyle("U{$contador}:V{$contador}")->applyFromArray($ac);

			$this->excel->getActiveSheet()->getStyle("O{$contador}")->applyFromArray($saber);
			$this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
			$this->excel->getActiveSheet()->getStyle("AA{$contador}")->applyFromArray($decir);
			$this->excel->getActiveSheet()->getStyle("AD{$contador}")->applyFromArray($final);

			$x++;
		}
		$contador = $contador + 2;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:AD{$contador}")->applyFromArray($esta1);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'CUADRO ESTADISTICO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:L{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'TEMA');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":Q" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:Q{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'ESTUDIANTES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:X{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'APRO. Y REPRO');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($esta2);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'PROM. DEL CURSO');
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Nº DE TEMAS ANUALES');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'INSCRITOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '' . ($contador - 17));
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", '');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", 'Nº');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '%');
		$this->excel->setActiveSheetIndex(0)->mergeCells("Y" . ($contador) . ":AD" . ($contador));
		$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AD14:AD" . ($contador - 4) . ")/Q" . ($contador + 2) . ",0)");

		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "ASAS");

		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'Nº DE TEMAS AVANZADOS');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'RETIRADOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '0');
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'PROMOVIDOS');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '=COUNTIF(AD14:AD' . ($contador - 5) . ',">=51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '=ROUND((V' . ($contador) . '*100)/Q' . ($contador + 1) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("C" . ($contador) . ":J" . ($contador));
		$this->excel->getActiveSheet()->getStyle("C{$contador}:J{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", '% DE AVANCE CURRICULAR');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K" . ($contador) . ":L" . ($contador));
		$this->excel->getActiveSheet()->getStyle("K{$contador}:L{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", '=ROUND((K' . ($contador - 1) . '*100)/K' . ($contador - 2) . ',0)');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M" . ($contador) . ":P" . ($contador));
		$this->excel->getActiveSheet()->getStyle("M{$contador}:P{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'EFECTIVOS');
		$this->excel->getActiveSheet()->getStyle("Q{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("Q{$contador}", '=Q' . ($contador - 2) . '-Q' . ($contador - 1));
		$this->excel->setActiveSheetIndex(0)->mergeCells("R" . ($contador) . ":U" . ($contador));
		$this->excel->getActiveSheet()->getStyle("R{$contador}:U{$contador}")->applyFromArray($esta3);
		$this->excel->getActiveSheet()->setCellValue("R{$contador}", 'RETENIDOS');
		$this->excel->getActiveSheet()->getStyle("V{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("V{$contador}", '=COUNTIF(AD14:AD' . ($contador - 6) . ',"<51")');
		$this->excel->setActiveSheetIndex(0)->mergeCells("W" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("W{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("W{$contador}", '=ROUND((V' . ($contador) . '*100)/Q' . ($contador) . ',0)');
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador).":AD".($contador));
		//$this->excel->getActiveSheet()->getStyle("Y{$contador}:AD{$contador}")->applyFromArray($notas);
		//$this->excel->setActiveSheetIndex(0)->mergeCells("Y".($contador-2).":Y".($contador));  
		//$this->excel->getActiveSheet()->setCellValue("Y{$contador}", "=ROUND(SUM(AD14:AD".($contador-6).")/Q".($contador).",0)");
		$contador = $contador + 8;

		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "PROF (A) " . $nombrepro);
		$this->excel->setActiveSheetIndex(0)->mergeCells("O" . ($contador) . ":X" . ($contador));
		$this->excel->getActiveSheet()->getStyle("O{$contador}:X{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("O{$contador}", "FIRMA Y SELLO DEL DIRECTOR (A)");
		$contador++;
		$this->excel->setActiveSheetIndex(0)->mergeCells("B" . ($contador) . ":E" . ($contador));
		$this->excel->getActiveSheet()->getStyle("B{$contador}:E{$contador}")->applyFromArray($notas);
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", "C.I. " . $cipro);
		$this->excel->setActiveSheetIndex(0)->mergeCells("T" . ($contador) . ":AB" . ($contador));

		//$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::VERTICAL_LANDSCAPE);
		//$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->excel->getActiveSheet()->setBreak("A{$contador}", PHPExcel_Worksheet::BREAK_ROW);
		$this->excel->getActiveSheet()->getPageSetup()->setPrintArea("A1:AD{$contador}");
		//Le ponemos un nombre al archivo que se va a generar.
		$archivo = "{$c}_{$materiass}_{$ni}_{$bimess}_{$prof}_{$gestion}.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $archivo . '"');
		header('Cache-Control: max-age=0');
		//$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_indi()
	{
		$table = $this->input->post('table');
		$idindi = $this->input->post('idindi');
		$idmat = $this->input->post('idmat');
		$bimes = $this->input->post('bimes');
		$gestion = $this->input->post('gestion');
		$inpsab1 = $this->input->post('inpsab1');
		$inpsab2 = $this->input->post('inpsab2');
		$inpsab3 = $this->input->post('inpsab3');
		$inpsab4 = $this->input->post('inpsab4');
		$inpsab5 = $this->input->post('inpsab5');
		$inpsab6 = $this->input->post('inpsab6');
		$inphac1 = $this->input->post('inphac1');
		$inphac2 = $this->input->post('inphac2');
		$inphac3 = $this->input->post('inphac3');
		$inphac4 = $this->input->post('inphac4');
		$inphac5 = $this->input->post('inphac5');
		$inphac6 = $this->input->post('inphac6');


		$data = array(
			'idmat' => $idmat,
			'bimestre' => $bimes,
			'gestion' => $gestion,
			'sab1' => $inpsab1,
			'sab2' => $inpsab2,
			'sab3' => $inpsab3,
			'sab4' => $inpsab4,
			'sab5' => $inpsab5,
			'sab6' => $inpsab6,
			'hac1' => $inphac1,
			'hac2' => $inphac2,
			'hac3' => $inphac3,
			'hac4' => $inphac4,
			'hac5' => $inphac5,
			'hac6' => $inphac6,
		);


		$this->nota->update_indi(array('idindi' => $this->input->post('idindi')), $table, $data);

		echo json_encode(array("status" => TRUE));
	}

	public function printnotas($var)
	{
		//print_r("-".$idmat);
		$bimestre = "";
		$bi = substr($var, 0, 1);
		$idmat = substr($var, 2, strlen($var));

		if ($bi == '1') $bimestre = 'PRIMER BIMESTRE';
		if ($bi == '2') $bimestre = 'SEGUNDO BIMESTRE';
		if ($bi == '3') $bimestre = 'TERCER BIMESTRE';
		if ($bi == '4') $bimestre = 'CUARTO BIMESTRE';


		$list = $this->nota->get_all_idmat($idmat);


		$materia = $list->materia;

		$idcurso = $list->idcurso;
		$curso	= $list->curso;
		$nivel	= $list->nivel;
		if ($nivel == 'PRIMARIA MAÑANA') $nivel = 'PRIMARIA COMUNITARIA VOCACIONAL';
		if ($nivel == 'PRIMARIA TARDE') $nivel = 'PRIMARIA COMUNITARIA VOCACIONAL';
		$idprof = $list->idprof;
		$gestion = $list->gestion;

		$list1 = $this->nota->get_all_idcur($idcurso);
		$colegio = $list1->colegio;

		$list2 = $this->nota->get_prof($idprof);
		$profesor = $list2->appaterno . " " . $list2->apmaterno . " " . $list2->nombres;

		$list3 = $this->nota->get_lista($idmat, $bi, $gestion);

		$table = "indicador";
		$list4 = $this->nota->get_indik($table, $bi, $idmat, $gestion);
		foreach ($list4 as $indi) {
			$sab1 = $indi->sab1;
			$sab2 = $indi->sab2;
			$sab3 = $indi->sab3;
			$sab4 = $indi->sab4;
			$sab5 = $indi->sab5;
			$sab6 = $indi->sab6;
			$hac1 = $indi->hac1;
			$hac2 = $indi->hac2;
			$hac3 = $indi->hac3;
			$hac4 = $indi->hac4;
			$hac5 = $indi->hac5;
			$hac6 = $indi->hac6;
		}

		$this->load->library('pdf');

		ob_start();
		$this->pdf = new Pdf('Legal');
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle("REGISTRO DOCENTE");
		$this->pdf->SetFont('Arial', 'BU', 15);
		$this->pdf->Cell(30);
		$this->pdf->Cell(135, 8, utf8_decode('REGISTRO DOCENTE BIMESTRALIZADO '), 0, 0, 'C');
		$this->pdf->Ln('15');
		$this->pdf->Cell(30);
		$this->pdf->setXY(15, 45);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(35, 5, utf8_decode('UNID. EDU: '), 0, 0, 'L');
		$this->pdf->setXY(105, 45);
		$this->pdf->Cell(65, 5, utf8_decode('BIMESTRE: '), 0, 0, 'L');
		$this->pdf->setXY(165, 45);
		$this->pdf->Cell(65, 5, utf8_decode('GESTION: '), 0, 0, 'L');
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->setXY(35, 45);
		$this->pdf->Cell(35, 5, utf8_decode($colegio), 0, 0, 'L');
		$this->pdf->setXY(125, 45);
		$this->pdf->Cell(65, 5, utf8_decode($bimestre), 0, 0, 'L');
		$this->pdf->setXY(183, 45);
		$this->pdf->Cell(65, 5, utf8_decode($gestion), 0, 0, 'L');
		$this->pdf->Ln('15');
		$this->pdf->setXY(15, 52);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(35, 5, utf8_decode('NIVEL: '), 0, 0, 'L');
		$this->pdf->setXY(105, 52);
		$this->pdf->Cell(65, 5, utf8_decode('ÁREA: '), 0, 0, 'L');
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->setXY(35, 52);
		$this->pdf->Cell(35, 5, utf8_decode($nivel), 0, 0, 'L');
		$this->pdf->setXY(125, 52);
		$this->pdf->Cell(65, 5, utf8_decode($materia), 0, 0, 'L');
		$this->pdf->Ln('15');
		$this->pdf->setXY(15, 59);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->Cell(35, 5, utf8_decode('AÑO ESCOLARIDAD: '), 0, 0, 'L');
		$this->pdf->setXY(105, 59);
		$this->pdf->Cell(65, 5, utf8_decode('MAESTRO(A): '), 0, 0, 'L');
		$this->pdf->SetFont('Arial', '', 10);
		$this->pdf->setXY(55, 59);
		$this->pdf->Cell(35, 5, utf8_decode($curso), 0, 0, 'L');
		$this->pdf->setXY(130, 59);
		$this->pdf->Cell(65, 5, utf8_decode($profesor), 0, 0, 'L');
		$this->pdf->setXY(10, 62);

		$this->pdf->SetLeftMargin(10);
		$this->pdf->SetRightMargin(10);
		$this->pdf->SetFillColor(255, 255, 255);
		$this->pdf->SetFont('Arial', 'B', 7);
		$this->pdf->Ln(5);
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 49, '', 'TBL', 0, 'L', '1');
		$this->pdf->Cell(55, 49, 'APELLIDOS Y NOMBRES', 'TBL', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(110, 7, 'EVALUACION DEL MAESTRO', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(10, 7, 'A.EVAL', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(10, 49, 'FINAL', 'TBLR', 0, 'C', '1');
		$this->pdf->Ln(7);
		$this->pdf->setX(70);
		$this->pdf->Cell(20, 7, 'SER 10pt', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(35, 7, 'SABER 35pt', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(35, 7, 'HACER 35pt', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(20, 7, 'DECIDIR 10pt', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, 'SE', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, 'DE', 'TBLR', 0, 'C', '1');
		$this->pdf->Ln(7);
		$this->pdf->setX(70);
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 7, '10', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '10', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '10', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 7, 'PR', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 7, 'PR', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '35', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 7, 'PR', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 7, '10', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '10', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '10', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 7, 'PR', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 7, '5', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 7, '5', 'TBLR', 0, 'C', '1');
		$this->pdf->Ln(7);
		$this->pdf->setX(70);
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(189, 215, 238);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->SetFillColor(255, 247, 185);
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->Cell(5, 28, '', 'TBLR', 0, 'C', '1');
		$this->pdf->TextWithDirection(14, 115, 'NUMERO', 'U');
		$this->pdf->TextWithDirection(74, 115, 'DISCIPLINA', 'U');
		$this->pdf->TextWithDirection(79, 115, 'RESPON. PUNTUAL', 'U');
		$this->pdf->TextWithDirection(84, 115, 'HONESTIDAD', 'U');
		$this->pdf->TextWithDirection(89, 115, 'PROMEDIO SER', 'U');
		$this->pdf->TextWithDirection(94, 115, $sab1, 'U');
		$this->pdf->TextWithDirection(99, 115, $sab2, 'U');
		$this->pdf->TextWithDirection(104, 115, $sab3, 'U');
		$this->pdf->TextWithDirection(109, 115, $sab4, 'U');
		$this->pdf->TextWithDirection(113, 115, $sab5, 'U');
		$this->pdf->TextWithDirection(118, 115, $sab6, 'U');
		$this->pdf->TextWithDirection(124, 115, 'PROMEDIO SABER', 'U');
		$this->pdf->TextWithDirection(129, 115, $hac1, 'U');
		$this->pdf->TextWithDirection(134, 115, $hac2, 'U');
		$this->pdf->TextWithDirection(139, 115, $hac3, 'U');
		$this->pdf->TextWithDirection(143, 115, $hac4, 'U');
		$this->pdf->TextWithDirection(148, 115, $hac5, 'U');
		$this->pdf->TextWithDirection(153, 115, $hac6, 'U');
		$this->pdf->TextWithDirection(159, 115, 'PROMEDIO HACER', 'U');
		$this->pdf->TextWithDirection(164, 115, 'PARTICIPACION', 'U');
		$this->pdf->TextWithDirection(169, 115, 'SOLID. Y HONEST.', 'U');
		$this->pdf->TextWithDirection(174, 115, 'COMUNICACION', 'U');
		$this->pdf->TextWithDirection(179, 115, 'PROMEDIO DECIDIR', 'U');
		$this->pdf->SetFillColor(255, 247, 185);
		$x = 1;
		$this->pdf->SetFont('Arial', 'B', 7);
		$this->pdf->setXY(10, 116);
		foreach ($list3 as $mat) {
			$this->pdf->SetFillColor(255, 255, 255);
			$this->pdf->Cell(5, 5, $x++, 'TBL', 0, 'C', 0);
			$this->pdf->Cell(55, 5, utf8_decode(strtoupper($mat->appat) . " " . strtoupper($mat->apmat) . " " . strtoupper($mat->nombres)), 'TBLR', 0, 'L', 0);
			$this->pdf->SetFillColor(255, 247, 185);
			$this->pdf->Cell(5, 5, $mat->ser1, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->ser2, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->ser3, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(189, 215, 238);
			$this->pdf->Cell(5, 5, $mat->promser, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(255, 247, 185);
			$this->pdf->Cell(5, 5, $mat->sab1, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->sab2, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->sab3, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->sab4, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->sab5, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->sab6, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(189, 215, 238);
			$this->pdf->Cell(5, 5, $mat->promsab, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(255, 247, 185);
			$this->pdf->Cell(5, 5, $mat->hac1, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->hac2, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->hac3, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->hac4, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->hac5, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->hac6, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(189, 215, 238);
			$this->pdf->Cell(5, 5, $mat->promhac, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(255, 247, 185);
			$this->pdf->Cell(5, 5, $mat->dec1, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->dec2, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->dec3, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(189, 215, 238);
			$this->pdf->Cell(5, 5, $mat->promdec, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(255, 247, 185);
			$this->pdf->Cell(5, 5, $mat->autoser, 'TBLR', 0, 'R', 1);
			$this->pdf->Cell(5, 5, $mat->autodec, 'TBLR', 0, 'R', 1);
			$this->pdf->SetFillColor(189, 215, 238);
			$this->pdf->Cell(10, 5, $mat->final, 'TBLR', 0, 'R', 1);

			$this->pdf->Ln(5);
		}
		$this->pdf->Ln(40);


		$this->pdf->Ln(100);
		$this->pdf->setXY(100, 150);
		$this->pdf->SetFont('Arial', 'BU', 11);
		$this->pdf->Cell(80, 5, '                                                     ', 0, 0, 'R');
		$this->pdf->Ln(5);
		$this->pdf->SetFont('Arial', 'B', 10);
		$this->pdf->setXY(80, 155);
		$this->pdf->Cell(100, 5, utf8_decode($profesor), 0, 0, 'R');
		$this->pdf->Ln(5);
		$this->pdf->setXY(110, 160);
		$this->pdf->Cell(50, 5, utf8_decode('FIRMA '), 0, 0, 'R');


		$this->pdf->Output("NOTAS-" . $curso . "-" . $bimestre . ".pdf", 'I');

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
