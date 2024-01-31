<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Not_notas_subir_contr extends CI_Controller
{

	public $_idnota = "";

	public function __construct()
	{
		parent::__construct();
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Est_estudiante_model', 'estud');
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
		// dev_test : la condición evita el render de la página (para que funcione deshabilitar la condicion)
		if ($this->session->userdata("access") == "157f3261a72f2650e451ccb84887de31746d6c6c") {
			$this->load->helper('url');
			$this->vista();
		}
	}
	public function ajax_list()
	{

		$gestion = 2019;
		$bime = 3;
		$b = $this->nota->get_bimestre($bime);
		foreach ($b as $bs) {
			$bimestre = $bs->sigla;
		}
		$table = 'profesor';
		$appat = $this->session->userdata("appat");
		$apmat = $this->session->userdata("apmat");
		$name = $this->session->userdata("name");
		$list = $this->nota->get_prof1($appat, $apmat, $name);
		foreach ($list as $prof) {
			$idprof = $prof->id_prof;
		}
		$nivel = $this->nota->get_nivel($idprof); //envia
		$cod = array();
		foreach ($nivel as $nivels) {
			$id = $nivels->codigo . '-';
			$ids = explode('-', $id, -1);
			$cod1[] = $ids[1];
		}

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
						$mat = $this->nota->prof_materias($idprof, $codcur[$c]); //envia
						$mate = array();
						foreach ($mat as $mats) {
							$id = $mats->codigo . '-';
							$ids = explode('-', $id, -1);
							$codmate1[] = $ids[5];
						}
						$mates = array_values(array_unique($codmate1));
						$m = 0;
						foreach ($mates as $matess) {
							$materia = $this->nota->materiass($mates[$m]);
							$m++;
							foreach ($materia as $materias) {
								$id_materia = $materias->id_mat;
								$materias = $materias->nombre;
							}
							$no++;
							$i++;
							$row = array();
							$row[] = $i;
							$row[] = $list_cursos->nombre;
							$row[] = $materias;
							$row[] = $niveles->turno . " " . $niveles->nivel;
							$row[] = $niveles->colegio;
							$row[] = $bimestre;
							$id = $gestion . "W" . $codcur[$c] . "W" . $idprof . "W" . $id_materia . "W" . $bime . "W" . $niveles->id_nivel . "W";
							$row[] = '<a class="btn btn-success" href="javascript:void(0)" title="Edit" onclick="d_planilla(' . "'" . $id . "','" . $codcur[$c] . "'" . ')"><i class="glyphicon glyphicon-cloud-download"></i>PLANILLA</a>';
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
		$this->load->view('Not_notas_subir_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_usuario()
	{
		$table = 'profesor';
		$appat = $this->session->userdata("appat");
		$apmat = $this->session->userdata("apmat");
		$name = $this->session->userdata("name");

		//$list=$this->nota->get_prof_row($appat,$apmat,$name,$table);
		$list = $this->nota->get_prof1($appat, $apmat, $name);
		$data = array();
		if ($list) {
			foreach ($list as $prof) {
				$data[] = $prof->id_prof;
				$data[] = $prof->appaterno . " " . $prof->apmaterno . " " . $prof->nombre;
			}
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
		$list = $this->nota->get_nivel($idprof); //envia
		$cod = array();
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

		$estudiantes = $this->nota->list_estudiante(2019);

		$bimestre = $this->nota->bimestre_list();
		$areass = $this->nota->areas_list();
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
	/* Function para gestion 2019/2020 */
	public function subir()
	{
		$appat = $this->session->userdata("appat");
		$apmat = $this->session->userdata("apmat");
		$name = $this->session->userdata("name");
		$nombre_carpeta = $appat . "_" . $apmat . "_" . $name;
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
		$objPHPExcel->setActiveSheetIndex(1);

		$numRows = $objPHPExcel->setActiveSheetIndex(1)->getHighestRow();
		$gestion = $objPHPExcel->getActiveSheet()->getCell('A1')->getCalculatedValue();
		$id_prof = $objPHPExcel->getActiveSheet()->getCell('A2')->getCalculatedValue();
		$id_mat = $objPHPExcel->getActiveSheet()->getCell('A3')->getCalculatedValue();
		$bi = $objPHPExcel->getActiveSheet()->getCell('A4')->getCalculatedValue();
		$cod_nivel = $objPHPExcel->getActiveSheet()->getCell('A5')->getCalculatedValue();
		$id_area = $objPHPExcel->getActiveSheet()->getCell('A6')->getCalculatedValue();
		$cod_curso = $objPHPExcel->getActiveSheet()->getCell('A7')->getCalculatedValue();
		$id_asg_ma = $objPHPExcel->getActiveSheet()->getCell('A8')->getCalculatedValue();

		$c = 0;
		for ($i = 13; $i <= $numRows; $i++) {
			$id = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
			$saber1 = $objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue();
			$saber2 = $objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue();
			$saber3 = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
			$saber4 = $objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();
			$saber5 = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
			$saber6 = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
			$saber7 = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
			$saber8 = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
			$saber9 = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
			$saberprom = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
			$hacer1 = $objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue();
			$hacer2 = $objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue();
			$hacer3 = $objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue();
			$hacer4 = $objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue();
			$hacer5 = $objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue();
			$hacer6 = $objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue();
			$hacer7 = $objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue();
			$hacer8 = $objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue();
			$hacer9 = $objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue();
			$hacerprom = $objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue();
			$serdec1 = $objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue();
			$serdec2 = $objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();
			$pmserdec = $objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue();
			$final = $objPHPExcel->getActiveSheet()->getCell('AB' . $i)->getCalculatedValue();
			$this->db->query("call insertar_(" . $id . "," . $saber1 . "," . $saber2 . "," . $saber3 . "," . $saber4 . "," . $saber5 . "," . $saber6 . "," . $saber7 . "," . $saber8 . "," . $saber9 . "," . $saberprom . "," . $hacer1 . "," . $hacer2 . "," . $hacer3 . "," . $hacer4 . "," . $hacer5 . "," . $hacer6 . "," . $hacer7 . "," . $hacer8 . "," . $hacer9 . "," . $hacerprom . "," . $serdec1 . "," . $serdec2 . "," . $pmserdec . "," . $final . "," . $bi . "," . $gestion . ");");
		}
		echo "<script type='text/javascript'>alert('Se Subio');</script>";
		redirect(base_url() . 'Not_notas_subir_contr/', 'refresh');
	}

	/* Function para gestion 2022 */
	public function subir_notas()
	{
		/* Acceso Admin */
		if (isset($_POST['name'])) {
			$pwd_access = '';
		} else {
			$pwd_access = $this->session->userdata("access"); // dev_test: agrega acceso root
		}
		// if($pwd_access !== "157f3261a72f2650e451ccb84887de31746d6c6c") {
		// 	/* Validación Limite de tiempo */
		// 	echo json_encode(array(
		// 		"title" => 'FECHA LIMITE',
		// 		"content" => 'Sus notas no se registraron, la fecha limite era hasta el 17 de Sep. a horas 06:00 AM.',
		// 		"success" => FALSE,
		// 	));
		// 	exit();
		// }

		$file_name = explode(".", $_FILES['planilla']['name']);

		if (isset($_POST['name'])) {
			$appat = $_POST["appat"];
			$apmat = $_POST["apmat"];
			$name = $_POST["name"];
			$nivel = $_POST["nivel"];
		} else {
			$appat = $this->session->userdata("appat");
			$apmat = $this->session->userdata("apmat");
			$name = $this->session->userdata("name");
			$nivel = 'PMT_SMT';
			if (strpos($file_name[0], '_PM_'))
				$nivel = 'PM';
			if (strpos($file_name[0], '_SM_'))
				$nivel = 'SM';
			if (strpos($file_name[0], '_PT_'))
				$nivel = 'PT';
			if (strpos($file_name[0], '_ST_'))
				$nivel = 'ST';
		}

		$nombre_carpeta = './upload/teacher/2022/' . $nivel . '/T3/' . $appat . ' ' . $apmat . ' ' . $name . '/NOTAS'; // MODIFICAR POR TRIMESTRE !IMPORTANTE
		if (!file_exists($nombre_carpeta)) {
			mkdir($nombre_carpeta, 0755, true);
		}
		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = 'xlsx|xls';

		/* Archivos unicos */
		date_default_timezone_set('America/La_Paz');
		$fecha = getdate();
		$today = $fecha['mday'] . '-' . $fecha['mon'] . '-' . $fecha['year'] . '-' . $fecha['hours'] . '-' . $fecha['minutes'] . '-' . $fecha['seconds'];

		if ($pwd_access !== "157f3261a72f2650e451ccb84887de31746d6c6c") {
			$config['file_name'] = 'TERCER_TRIMESTRE_' . $file_name[0] . '_' . $today . '.' . $file_name[1]; // MODIFICAR POR TRIMESTRE !IMPORTANTE
		} else {
			$config['file_name'] = 'MODIFICACION_TRIMESTRE_' . $file_name[0] . '_' . $today . '.' . $file_name[1];
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('planilla')) {
			echo json_encode(array(
				"title" => 'ERROR AL SUBIR',
				"content" => 'No se pudo subir su archivo excel',
				"success" => FALSE,
			));
			exit();
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
		$objPHPExcel->setActiveSheetIndex(0);

		$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		$gestion = $objPHPExcel->getActiveSheet()->getCell('AE1')->getCalculatedValue(); //yes
		$id_prof = $objPHPExcel->getActiveSheet()->getCell('AE2')->getCalculatedValue(); //yes
		$id_mat = $objPHPExcel->getActiveSheet()->getCell('AE3')->getCalculatedValue(); //yes
		$id_bi = $objPHPExcel->getActiveSheet()->getCell('AE4')->getCalculatedValue(); //yes
		$cod_nivel = $objPHPExcel->getActiveSheet()->getCell('AE5')->getCalculatedValue(); //yes
		$id_area = $objPHPExcel->getActiveSheet()->getCell('AE6')->getCalculatedValue(); //yes
		$cod_curso = $objPHPExcel->getActiveSheet()->getCell('AE7')->getCalculatedValue(); //yes
		$id_asg_prof = $objPHPExcel->getActiveSheet()->getCell('AE8')->getCalculatedValue(); //yes


		/****** ZONA DE VALIDACIÓN ******/
		if ($pwd_access !== "157f3261a72f2650e451ccb84887de31746d6c6c") {
			// Validación del Tercer Trimestre
			if ($id_bi == 5 || $id_bi == 6) { // $id_bi == 6 || 
				if ($id_bi == 5) {
					$trimestre_current = "Primer Trimestre";
				}
				if ($id_bi == 6) {
					$trimestre_current = "Segundo Trimestre";
				}
				if ($id_bi == 7) {
					$trimestre_current = "Tercer Trimestre";
				}
				echo json_encode(array(
					"title" => 'Acceso denegado',
					"content" => 'Solo se permiten Notas de Tercer Trimestre, usted esta subiendo notas del ' . $trimestre_current . ', revise por favor.',
					"success" => FALSE,
				));
				exit();
			}

			// Cierra sistema para las promociones SM y ST
			if ($pwd_access !== "157f3261a72f2650e451ccb84887de31746d6c6c") {
				if ($cod_nivel == "ST" || $cod_nivel == "SM") {
					if ($cod_curso == "6A" || $cod_curso == "6B" || $cod_curso == "6C") {
						echo json_encode(array(
							"title" => 'Tiempo limite',
							"content" => 'Ya termino el tiempo de recepción de notas de la promoción, si usted necesita modificar comuniquese con su Director.',
							"success" => FALSE,
						));
						exit();
					}
				}
			}

			// Cierra sistema por niveles, añadiendo y quitando cod_nivel los OR
			if ($pwd_access !== "157f3261a72f2650e451ccb84887de31746d6c6c") {
				//if ( !(($cod_nivel == "SM") && ($cod_curso == "6A" || $cod_curso == "6B" || $cod_curso == "6C")) ) {
				  if ($cod_nivel == "PT" || $cod_nivel == "PM" || $cod_nivel == "ST" || $cod_nivel == "SM") { // || $cod_nivel == "SM" || $cod_nivel == "ST" || $cod_nivel == "PT"
				  	echo json_encode(array(
				  		"title" => 'Tiempo limite',
				  		"content" => 'Ya termino el tiempo de recepción de notas, si usted necesita modificar comuniquese con su Director.',
				  		"success" => FALSE,
				  	));
					  exit();
          }
				//}
			}
		}
		/****** FIN ZONA DE VALIDACIÓN ******/

		$notas_rows =  $numRows - 13; // - 13 espacios del cuadro estadistico y firmas
		$query = "";
		for ($i = 10; $i <= $notas_rows; $i++) {
			$id_est = $objPHPExcel->getActiveSheet()->getCell('AE' . $i)->getCalculatedValue();
			// NOTAS SER
			$ser1 = trim($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
			$ser2 = trim($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue());
			$ser3 = trim($objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue());
			// NOTAS SABER
			$saber1 = trim($objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue());
			$saber2 = trim($objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue());
			$saber3 = trim($objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue());
			$saber4 = trim($objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue());
			$saber5 = trim($objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue());
			$saber6 = trim($objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue());
			$saber7 = trim($objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue());
			// NOTAS HACER			
			$hacer1 = trim($objPHPExcel->getActiveSheet()->getCell('P' . $i)->getCalculatedValue());
			$hacer2 = trim($objPHPExcel->getActiveSheet()->getCell('Q' . $i)->getCalculatedValue());
			$hacer3 = trim($objPHPExcel->getActiveSheet()->getCell('R' . $i)->getCalculatedValue());
			$hacer4 = trim($objPHPExcel->getActiveSheet()->getCell('S' . $i)->getCalculatedValue());
			$hacer5 = trim($objPHPExcel->getActiveSheet()->getCell('T' . $i)->getCalculatedValue());
			$hacer6 = trim($objPHPExcel->getActiveSheet()->getCell('U' . $i)->getCalculatedValue());
			$hacer7 = trim($objPHPExcel->getActiveSheet()->getCell('V' . $i)->getCalculatedValue());
			// NOTAS DECIDIR
			$decidir1 = trim($objPHPExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue());
			$decidir2 = trim($objPHPExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue());
			$decidir3 = trim($objPHPExcel->getActiveSheet()->getCell('Z' . $i)->getCalculatedValue());
			// NOTAS SER Y DECIDIR ESTUDIANTE
			$ser_estudiante = trim($objPHPExcel->getActiveSheet()->getCell('AB' . $i)->getCalculatedValue());
			$decidir_estudiante = trim($objPHPExcel->getActiveSheet()->getCell('AC' . $i)->getCalculatedValue());

			// PROMEDIOS
			$pm_ser = trim($objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue());
			$pm_saber = trim($objPHPExcel->getActiveSheet()->getCell('O' . $i)->getCalculatedValue());
			$pm_hacer = trim($objPHPExcel->getActiveSheet()->getCell('W' . $i)->getCalculatedValue());
			$pm_decidir = trim($objPHPExcel->getActiveSheet()->getCell('AA' . $i)->getCalculatedValue());
			$total = trim($objPHPExcel->getActiveSheet()->getCell('AD' . $i)->getCalculatedValue());

			// VALORES NULOS
			if (is_null($ser1) || $ser1 === "") {
				$ser1 = 'NULL';
			}
			if (is_null($ser2) || $ser2 === "") {
				$ser2 = 'NULL';
			}
			if (is_null($ser3) || $ser3 === "") {
				$ser3 = 'NULL';
			}

			if (is_null($saber1) || $saber1 === "") {
				$saber1 = 'NULL';
			}
			if (is_null($saber2) || $saber2 === "") {
				$saber2 = 'NULL';
			}
			if (is_null($saber3) || $saber3 === "") {
				$saber3 = 'NULL';
			}
			if (is_null($saber4) || $saber4 === "") {
				$saber4 = 'NULL';
			}
			if (is_null($saber5) || $saber5 === "") {
				$saber5 = 'NULL';
			}
			if (is_null($saber6) || $saber6 === "") {
				$saber6 = 'NULL';
			}
			if (is_null($saber7) || $saber7 === "") {
				$saber7 = 'NULL';
			}

			if (is_null($hacer1) || $hacer1 === "") {
				$hacer1 = 'NULL';
			}
			if (is_null($hacer2) || $hacer2 === "") {
				$hacer2 = 'NULL';
			}
			if (is_null($hacer3) || $hacer3 === "") {
				$hacer3 = 'NULL';
			}
			if (is_null($hacer4) || $hacer4 === "") {
				$hacer4 = 'NULL';
			}
			if (is_null($hacer5) || $hacer5 === "") {
				$hacer5 = 'NULL';
			}
			if (is_null($hacer6) || $hacer6 === "") {
				$hacer6 = 'NULL';
			}
			if (is_null($hacer7) || $hacer7 === "") {
				$hacer7 = 'NULL';
			}

			if (is_null($decidir1) || $decidir1 === "") {
				$decidir1 = 'NULL';
			}
			if (is_null($decidir2) || $decidir2 === "") {
				$decidir2 = 'NULL';
			}
			if (is_null($decidir3) || $decidir3 === "") {
				$decidir3 = 'NULL';
			}

			if (is_null($ser_estudiante) || $ser_estudiante === "") {
				$ser_estudiante = 'NULL';
			}
			if (is_null($decidir_estudiante) || $decidir_estudiante === "") {
				$decidir_estudiante = 'NULL';
			}

			if (is_null($pm_ser) || $pm_ser === "") {
				$pm_ser = 'NULL';
			}
			if (is_null($pm_saber) || $pm_saber === "") {
				$pm_saber = 'NULL';
			}
			if (is_null($pm_hacer) || $pm_hacer === "") {
				$pm_hacer = 'NULL';
			}
			if (is_null($pm_decidir) || $pm_decidir === "") {
				$pm_decidir = 'NULL';
			}
			if (is_null($total) || $total === "") {
				$total = 'NULL';
			}

			$search_sql = "SELECT *  FROM nota_trimestre
				WHERE gestion = $gestion AND
				id_bi = $id_bi AND
				id_area = $id_area AND
				id_mat = $id_mat AND
				id_prof = $id_prof AND
				id_est = $id_est AND
				cod_nivel LIKE '$cod_nivel' AND
				id_asg_prof = $id_asg_prof;";

			$search_query = $this->db->query($search_sql);
			$result = $search_query->result();

			if ($search_query->num_rows() > 0) {
				// UPDATE
				$id_nota_trimestre = $result[0]->id_nota_trimestre;
				$query = "UPDATE nota_trimestre SET
					`ser1` = $ser1,
					`ser2` = $ser2,
					`ser3` = $ser3,

					`saber1` = $saber1,
					`saber2` = $saber2,
					`saber3` = $saber3,
					`saber4` = $saber4,
					`saber5` = $saber5,
					`saber6` = $saber6,
					`saber7` = $saber7,

					`hacer1` = $hacer1,
					`hacer2` = $hacer2,
					`hacer3` = $hacer3,
					`hacer4` = $hacer4,
					`hacer5` = $hacer5,
					`hacer6` = $hacer6,
					`hacer7` = $hacer7,

					`decidir1` = $decidir1,
					`decidir2` = $decidir2,
					`decidir3` = $decidir3,

					`ser_estudiante` = $ser_estudiante,
					`decidir_estudiante` = $decidir_estudiante,
					`hacer3` = $hacer3,

					`pm_ser` = $pm_ser,
					`pm_saber` = $pm_saber,
					`pm_hacer` = $pm_hacer,
					`pm_decidir` = $pm_decidir,

					`total` = $total

					WHERE `id_nota_trimestre` = $id_nota_trimestre;
				";
			} else {
				// INSERT
				$query = "INSERT INTO nota_trimestre VALUES (
					NULL,
					0,
					$ser1, $ser2, $ser3,
					$saber1, $saber2, $saber3, $saber4, $saber5, $saber6, $saber7,
					$hacer1, $hacer2, $hacer3, $hacer4, $hacer5, $hacer6, $hacer7,
					$decidir1, $decidir2, $decidir3,
					$ser_estudiante, $decidir_estudiante,
					$pm_ser, $pm_saber, $pm_hacer, $pm_decidir, $total,
					$gestion, $id_bi, $id_area, $id_mat, $id_prof, $id_est, '$cod_curso', '$cod_nivel', $id_asg_prof,
					CURRENT_TIMESTAMP
				);";
			}
			$this->db->query($query);
		}

		/* Validacion de salida para el endpoint */
		if (isset($_POST['name'])) {
			$materia_current = $this->nota->get_materia_data($id_mat);
			if ($cod_nivel == "PT") {
				$nivel_nombre = "PRIMARIA TARDE";
			}
			if ($cod_nivel == "ST") {
				$nivel_nombre = "SECUNDARIA TARDE";
			}
			if ($cod_nivel == "PM") {
				$nivel_nombre = "PRIMARIA MAÑANA";
			}
			if ($cod_nivel == "SM") {
				$nivel_nombre = "SECUNDARIA MAÑANA";
			}
			echo json_encode(array(
				"title" => "Notas Subidas",
				"content" => $materia_current->nombre . ', ' . $cod_curso . ' ' . $nivel_nombre,
				"success" => TRUE,
			));
		} else {
			echo "<script type='text/javascript'>alert('Se Subio');</script>";
			redirect(base_url() . 'Not_notas_subir_contr/', 'refresh');
		}
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
	public function d_planilla($id)
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
		foreach ($list as $estud) {
			$contador1++;
			$not++;
			$notamateria = $this->nota->notamateria($gestion, $estud->id_est, $materia);
			foreach ($notamateria as $notamaterias) {
				$n1 = $notamaterias->notabi1;
				$n2 = $notamaterias->notabi2;
				$n3 = $notamaterias->notabi3;
				$n4 = $notamaterias->notabi4;
			}
			if ($bimestre == 1) {
				$n1 = "-";
				$n2 = "-";
				$n3 = "-";
				$n4 = "-";
				$final = "-";;
			}
			if ($bimestre == 2) {
				$n2 = "-";
				$n3 = "-";
				$n4 = "-";
				$final = ($n1);
			}
			if ($bimestre == 3) {
				$n3 = "-";
				$n4 = "-";
				$final = ($n1 + $n2) / ($bimestre - 1);
			}
			if ($bimestre == 4) {
				$n4 = "-";
				$final = ($n1 + $n2 + $n3) / ($bimestre - 1);
			}

			$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->getStyle("A{$contador1}")->applyFromArray($titulo_n);
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", $not);
			$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud->nombres);
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

			if ($n4 <= 50) {
				$estilo3 = $rojo;
			}
			if ($n4 >= 51) {
				$estilo3 = $naranja;
			}
			if ($n4 >= 70) {
				$estilo3 = $amarillo;
			}
			if ($n4 >= 90) {
				$estilo3 = $verde;
			}
			if ($n4 == '-') {
				$estilo3 = $notas;
			}
			$this->excel->getActiveSheet()->getStyle("F{$contador1}")->applyFromArray($estilo3);
			$this->excel->getActiveSheet()->setCellValue("F{$contador1}", $n4);

			if ($final <= 50) {
				$estilo3 = $rojo;
			}
			if ($final >= 51) {
				$estilo3 = $naranja;
			}
			if ($final >= 70) {
				$estilo3 = $amarillo;
			}
			if ($final >= 90) {
				$estilo3 = $verde;
			}
			if ($final == '-') {
				$estilo3 = $notas;
			}
			$this->excel->getActiveSheet()->getStyle("G{$contador1}")->applyFromArray($estilo3);
			$this->excel->getActiveSheet()->setCellValue("G{$contador1}", $final);
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
