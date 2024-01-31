<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_registro_contr extends CI_Controller {

	public $_idnota="";
	
	public function __construct()
	{
		parent::__construct();		
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('Est_estudiante_model','estud');
		$this->load->model('Rep_registro_model','nota');

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
		$this->load->view('Rep_registro_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_usuario()
	{
		$table='profesor';
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");

	    $list=$this->nota->get_prof_row($appat,$apmat,$name,$table);

	    $data=array();
	    if ($list)
	    {
	    	foreach ($list as $prof) {
		    	$data[]=$prof->idprof;
		    	$data[]=$prof->appaterno." ".$prof->apmaterno." ".$prof->nombres;	    
	    	}
	    	$output = array(
						"status" => TRUE,
						"data" => $data,
				);
	    }
	    else
	    {
	    	$output = array(
						"status" => FALSE,
				);
	    }
	    
		
		echo json_encode($output);
	}

	//------------------------


	public function ajax_get_id()
	{
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');
		$colum="idtemabi";
				
		$codgen='';
		$num_rows=$this->nota->get_count_rows2($table,$colum);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->nota->get_idcod_table2($table,$colum);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idavance;//considerar nombre del id;				
				$newcod=substr($idcod,$n,strlen($idcod));
		        if($newcod>=$may)
		        {
		            $may=$newcod;    
		        }
			}
			$may=$may+1;
			$codgen=$cod."".$may;
		}
		else
		{
			$codgen=$cod.'1';	
		}
		
		$output = array(
						"status" => TRUE,
						"data" => $codgen,
				);
		echo json_encode($output);
	}

	public function ajax_get_idindi()
	{
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');
		//print_r($table."-".$cod);

		$codgen='';
		$num_rows=$this->nota->get_count_rows3($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->nota->get_idcod_table3($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idindi;//considerar nombre del id;				
				$newcod=substr($idcod,$n,strlen($idcod));
		        if($newcod>=$may)
		        {
		            $may=$newcod;    
		        }
			}
			$may=$may+1;
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
	public function ajax_get_level()
	{
		$table=$this->input->post('table');//recibe
		$lvl=$this->input->post('lvl');

		
		$list=$this->estud->get_rows_level($table,$lvl); //envia

		$data=array();
		foreach ($list as $level) {			
			$data[] =$level->gestion;					 
			$data[] =$level->colegio;
			$data[]	=$level->idcurso;
			$data[]	=$level->curso;				 				 
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
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_estud($id)
	{
		//print_r($id);
		$data=$this->estud->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update_nota()
	{
		
		$data=array(
				'ser1'=>$this->input->post('ser1'),
				'ser2'=>$this->input->post('ser2'),
				'ser3'=>$this->input->post('ser3'),
				'promser'=>$this->input->post('promser'),
				'sab1'=>$this->input->post('sab1'),
				'sab2'=>$this->input->post('sab2'),
				'sab3'=>$this->input->post('sab3'),
				'sab4'=>$this->input->post('sab4'),
				'sab5'=>$this->input->post('sab5'),
				'sab6'=>$this->input->post('sab6'),
				'promsab'=>$this->input->post('promsab'),
				'hac1'=>$this->input->post('hac1'),
				'hac2'=>$this->input->post('hac2'),
				'hac3'=>$this->input->post('hac3'),
				'hac4'=>$this->input->post('hac4'),
				'hac5'=>$this->input->post('hac5'),
				'hac6'=>$this->input->post('hac6'),
				'promhac'=>$this->input->post('promhac'),
				'dec1'=>$this->input->post('dec1'),
				'dec2'=>$this->input->post('dec2'),
				'dec3'=>$this->input->post('dec3'),
				'promdec'=>$this->input->post('promdec'),
				'autoser'=>$this->input->post('autoser'),
				'autodec'=>$this->input->post('autodec'),
				'final'=>$this->input->post('final')
			);
			

		$this->nota->update(array('idnota'=>$this->input->post('idnota')),$data);

		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_get_nivel()
	{
		$table=$this->input->post('table');//recibe
		$idprof=$this->input->post('idprof');
		$list=$this->nota->get_rows_nivel($table,$idprof); //envia
		$data=array();
		foreach ($list as $nivel) {			
			$data[] =$nivel->nivel;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	


	public function ajax_get_curso()
	{
		$table=$this->input->post('tabla');
		$idprof=$this->input->post("idprof");
		$nivel=$this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list=$this->nota->get_rows_curso($table,$idprof,$nivel);

		$data=array();
		foreach ($list as $materia){
			$data[]=$materia->curso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
	}

	public function ajax_get_mat()
	{
		$table=$this->input->post('tabla');
		$idprof=$this->input->post('idprof');
		$nivel=$this->input->post('nivel');
		$curso=$this->input->post('curso');

		$list=$this->nota->get_materia($table,$idprof,$nivel,$curso);

		$data=array();
		foreach($list as $materia)
		{
			$data[]=$materia->materia;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);

	}


	public function ajax_get_idcurso()
	{		
		$table2='nota';
		//

		$data=array();

		$nivel=$this->input->post('nivel');
		$curso=$this->input->post('cur');
		$idprof=$this->input->post('idprof');
		$gestion=$this->input->post('gestion');
		$bimestre=$this->input->post('bimestre');
		$materia=$this->input->post('materia');

		$list=$this->estud->get_idcurso($nivel,$curso);
		
		foreach($list as $idcur)
		{
			$idcurso=$idcur->idcurso;
		}

		//REVISAR LA MATERIA
		$table='materia';
		$list1=$this->nota->get_idmat($table,$idprof,$idcurso,$nivel,$materia);
		foreach($list1 as $mat)
		{
			$idmat=$mat->idmat;
		}


		//SELECT * FROM donbosco.estudiante where idcurso='CUR-14' and nivel='SECUNDARIA MAÑANA' and gestion='2018'

		$table1='estudiante';
		$list2=$this->nota->get_estud($table1,$idcurso,$gestion);
		//$table2='nota';
		foreach ($list2 as $est) {
			
			$idest = $est->idest;
			$appat = $est->appaterno;
			$apmat = $est->apmaterno;
			$nomb = $est->nombres;
			$list3=$this->nota->if_exit_nota($table2,$idest,$idmat,$bimestre);
			//print_r($list3."<br>");
			if($list3==0)
			{
				
				$cod='NOT-';
				$codgen='';
				$num_rows=$this->nota->get_count_rows($table2);
				if($num_rows!=0)
				{
					$n=strlen($cod);		
					$list = $this->nota->get_idcod_table($table2);
					$may=0;
					foreach ($list as $codigo) {	
						$idcod=$codigo->idnota;//considerar nombre del id;				
						$newcod=substr($idcod,$n,strlen($idcod));
				        if($newcod>=$may)
				        {
				            $may=$newcod;    
				        }
					}
					$may=$may+1;
					$codgen=$cod."".$may;
				}
				else
				{
					$codgen=$cod.'1';	
				}
				$idnota=$codgen;
				
				//valores enviados 
				$data=array(
					'idnota'=>$idnota,
					'idest'=>$idest,
					'idmat'=>$idmat,
					'idcurso'=>$idcurso,
					'idprof'=>$idprof,
					'appat'=>$appat,
					'apmat'=>$apmat,
					'nombres'=>$nomb,
					'bimestre'=>$bimestre,
					'gestion'=>$gestion,
				);		

				//print_r($data);
				$insert=$this->nota->save_nota($data);
				//ajax_list_mat($table2,$idest,$idmat,$bimestre);
			}
			
		}
		$dataEnvio=array(
			'idmat'=>$idmat,
			'idcurso'=>$idcurso,
			'idprof'=>$idprof,
			'gestion'=>$gestion,
			'bimestre'=>$bimestre,
		);

		$output=array(
			"status"=>TRUE,
			"data"=>$dataEnvio,
		);

		echo json_encode($output);
	}


	public function ajax_list_alum()
	{

		$idmat=$this->input->post('idmat');
		$idcur=$this->input->post('idcur');
		$idprof=$this->input->post('idprof');
		$gestion=$this->input->post('gestion');
		$bimestre=$this->input->post('bimestre');
		
		$list=$this->nota->get_datatables_by_all($idmat,$idcur,$idprof,$gestion,$bimestre);
		$data = array();
		$no = $_POST['start'];
		
		foreach ($list as $notas) {
			$_idnota=$notas->idnota;
			$no++;
			$row = array();
			$row[] = "<td>".$notas->idnota."</td>";
			$row[] = "<td >".$notas->appat."</td>";
			$row[] = "<td >".$notas->apmat."</td>";
			$row[] = "<td >".$notas->nombres."</td>";
			$row[] = "<td ><input  type='number' name='ser1".$notas->idnota."' id='ser1".$notas->idnota."' class='form-control'  value='".$notas->ser1."' min = '1'
max = '10' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promser(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='ser2".$notas->idnota."' id='ser2".$notas->idnota."' class='form-control'  value='".$notas->ser2."' min = '1'
max = '10' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promser(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='ser3".$notas->idnota."' id='ser3".$notas->idnota."' class='form-control'  value='".$notas->ser3."' min = '1'
max = '10' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promser(this.value,this.id)'></td>";
			$row[] = " <td ><input  type='number' name='promser".$notas->idnota."' id='promser".$notas->idnota."' class='form-control bg-primary-300' style='color:black;' value='".$notas->promser."' size='6' readonly='true'></td>";

			$row[] = "<td><input  type='number' name='sab1".$notas->idnota."' id='sab1".$notas->idnota."' class='form-control'  value='".$notas->sab1."' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='sab2".$notas->idnota."' id='sab2".$notas->idnota."' class='form-control'  value='".$notas->sab2."' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";			
			$row[] = "<td><input  type='number' name='sab3".$notas->idnota."' id='sab3".$notas->idnota."' class='form-control'  value='".$notas->sab3."' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='sab4".$notas->idnota."' id='sab4".$notas->idnota."' class='form-control '  value='".$notas->sab4."' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)' ></td>";
			$row[] = "<td><input  type='number' name='sab5".$notas->idnota."' id='sab5".$notas->idnota."' class='form-control'  value='".$notas->sab5."' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)' ></td>";
			$row[] = "<td><input  type='number' name='sab6".$notas->idnota."' id='sab6".$notas->idnota."' class='form-control'  value='".$notas->sab6."' min = '1'
max = '35' style='width: 60px; border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promsaber(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td ><input  type='number' name='promsab".$notas->idnota."' id='promsab".$notas->idnota."' class='form-control bg-primary-300' style='color:black; ' value='".$notas->promsab."' size='6' readonly='true'></td>";


			$row[] = "<td><input  type='number' name='hac1".$notas->idnota."' id='hac1".$notas->idnota."' class='form-control'  value='".$notas->hac1."' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac2".$notas->idnota."' id='hac2".$notas->idnota."' class='form-control'  value='".$notas->hac2."' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac3".$notas->idnota."' id='hac3".$notas->idnota."' class='form-control'  value='".$notas->hac3."' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac4".$notas->idnota."' id='hac4".$notas->idnota."' class='form-control'  value='".$notas->hac4."' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac5".$notas->idnota."' id='hac5".$notas->idnota."' class='form-control'  value='".$notas->hac5."' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td><input  type='number' name='hac6".$notas->idnota."' id='hac6".$notas->idnota."' class='form-control'  value='".$notas->hac6."' min = '1'
max = '35' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,35);promhacer(this.value,this.id);valindik(this.id)'></td>";
			$row[] = "<td ><input  type='number' name='promhac".$notas->idnota."' id='promhac".$notas->idnota."' class='form-control bg-primary-300' style='color:black;'  value='".$notas->promhac."' size='6' readonly='true'></td>";

			$row[] = "<td><input  type='number' name='dec1".$notas->idnota."' id='dec1".$notas->idnota."' class='form-control'  value='".$notas->dec1."' min = '1'
max = '10' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promdec(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='dec2".$notas->idnota."' id='dec2".$notas->idnota."' class='form-control'  value='".$notas->dec2."' min = '1'
max = '10' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promdec(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='dec3".$notas->idnota."' id='dec3".$notas->idnota."' class='form-control'  value='".$notas->dec3."' min = '1'
max = '10' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,10);promdec(this.value,this.id)'></td>";
			$row[] = "<td ><input  type='number' name='promdec".$notas->idnota."' id='promdec".$notas->idnota."' class='form-control bg-primary-300' style='color:black;' value='".$notas->promdec."' size='6' readonly='true'></td>";

			$row[] = "<td><input  type='number' name='autoser".$notas->idnota."' id='autoser".$notas->idnota."' class='form-control'  value='".$notas->autoser."' min = '1'
max = '5' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,5);autoser(this.value,this.id)'></td>";
			$row[] = "<td><input  type='number' name='autodec".$notas->idnota."' id='autodec".$notas->idnota."' class='form-control'  value='".$notas->autodec."' min = '1'
max = '5' style='width: 60px;  border:1px solid #FFB74D;' onchange='val10(this.value,this.id,5);autodec(this.value,this.id)'></td>";
			$row[] = "<td ><input  type='number' name='final".$notas->idnota."' id='final".$notas->idnota."' class='form-control bg-primary-300' style='color:black;'  value='".$notas->final."' size='10' readonly='true'></td>";



			//add html for action
			$row[] = '<a class="btn btn-sm bg-brown-300" href="javascript:void(0)" title="Edit" id="btn'.$notas->idnota.'" onclick="guardar_nota('."'".$notas->idnota."'".')"><i class="glyphicon glyphicon-ok"></i> Guardar</a>';
			
			$data[] = $row;


		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->nota->count_all(),
						"recordsFiltered" => $this->nota->count_filtered_byid($idmat,$idcur,$idprof,$gestion,$bimestre),
						"data" => $data,
				);

		echo json_encode($output);

	}

	public function ajax_get_estadis($var)
	{
		$bimestre="";
		$bi=substr($var,0,1);
		$idmat=substr($var,2,strlen($var));

		$list=$this->nota->get_all_idmat($idmat);
		$gestion =$list->gestion;

		$list2=$this->nota->get_estadistic($idmat,$bi,$gestion);
		foreach($list2 as $final)
		{
			$notafinal=$final->final;
		}

	}

	public function ajax_set_indi()
	{
		$table=$this->input->post('table');
		$idindi=$this->input->post('idindi');
		$idmat=$this->input->post('idmat');
		$bimes=$this->input->post('bimes');
		$gestion=$this->input->post('gestion');
		$inpsab1=$this->input->post('inpsab1');
		$inpsab2=$this->input->post('inpsab2');
		$inpsab3=$this->input->post('inpsab3');
		$inpsab4=$this->input->post('inpsab4');
		$inpsab5=$this->input->post('inpsab5');
		$inpsab6=$this->input->post('inpsab6');
		$inphac1=$this->input->post('inphac1');
		$inphac2=$this->input->post('inphac2');
		$inphac3=$this->input->post('inphac3');
		$inphac4=$this->input->post('inphac4');
		$inphac5=$this->input->post('inphac5');
		$inphac6=$this->input->post('inphac6');
		

		$data=array(
					'idindi'=>$idindi,
					'idmat'=>$idmat,
					'bimestre'=>$bimes,					
					'gestion'=>$gestion,
					'sab1'=>$inpsab1,
					'sab2'=>$inpsab2,
					'sab3'=>$inpsab3,
					'sab4'=>$inpsab4,
					'sab5'=>$inpsab5,
					'sab6'=>$inpsab6,
					'hac1'=>$inphac1,
					'hac2'=>$inphac2,
					'hac3'=>$inphac3,
					'hac4'=>$inphac4,
					'hac5'=>$inphac5,
					'hac6'=>$inphac6,
				);		

		$insert=$this->nota->save_indi($table,$data);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_if_indi()
	{
		$table=$this->input->post('table');	
		$idmat=$this->input->post('idmat');	
		$bimes=$this->input->post('bimes');		
		$gestion=$this->input->post('gestion');
		
		$data = $this->nota->indi_if($table,$idmat,$bimes,$gestion);
		echo json_encode($data);

	}

	public function ajax_if_exit()
	{
		$existe=false;
		$table=$this->input->post('table');	
		$idmat=$this->input->post('idmat');	
		$bimes=$this->input->post('bimes');		
		$gestion=$this->input->post('gestion');
		
		$num_rows = $this->nota->indi_if_exit($table,$idmat,$bimes,$gestion);
		if($num_rows>0)
		{
			$existe=true;
		}
		else
			$existe=false;
		
		$output = array(
						"status" => TRUE,
						"data" => $existe,
				);
		echo json_encode($output);

	}

	public function ajax_update_indi()
	{
		$table=$this->input->post('table');
		$idindi=$this->input->post('idindi');
		$idmat=$this->input->post('idmat');
		$bimes=$this->input->post('bimes');
		$gestion=$this->input->post('gestion');
		$inpsab1=$this->input->post('inpsab1');
		$inpsab2=$this->input->post('inpsab2');
		$inpsab3=$this->input->post('inpsab3');
		$inpsab4=$this->input->post('inpsab4');
		$inpsab5=$this->input->post('inpsab5');
		$inpsab6=$this->input->post('inpsab6');
		$inphac1=$this->input->post('inphac1');
		$inphac2=$this->input->post('inphac2');
		$inphac3=$this->input->post('inphac3');
		$inphac4=$this->input->post('inphac4');
		$inphac5=$this->input->post('inphac5');
		$inphac6=$this->input->post('inphac6');
		

		$data=array(					
					'idmat'=>$idmat,
					'bimestre'=>$bimes,					
					'gestion'=>$gestion,
					'sab1'=>$inpsab1,
					'sab2'=>$inpsab2,
					'sab3'=>$inpsab3,
					'sab4'=>$inpsab4,
					'sab5'=>$inpsab5,
					'sab6'=>$inpsab6,
					'hac1'=>$inphac1,
					'hac2'=>$inphac2,
					'hac3'=>$inphac3,
					'hac4'=>$inphac4,
					'hac5'=>$inphac5,
					'hac6'=>$inphac6,
				);		
			

		$this->nota->update_indi(array('idindi'=>$this->input->post('idindi')),$table,$data);

		echo json_encode(array("status"=>TRUE));
	}

	public function printnotas($var)
	{
		//print_r("-".$idmat);
		$bimestre="";
		$bi=substr($var,0,1);
		$idmat=substr($var,2,strlen($var));

		if($bi=='1') $bimestre='PRIMER BIMESTRE';
		if($bi=='2') $bimestre='SEGUNDO BIMESTRE';
		if($bi=='3') $bimestre='TERCER BIMESTRE';
		if($bi=='4') $bimestre='CUARTO BIMESTRE';

		
		$list=$this->nota->get_all_idmat($idmat);
		
		$materia=$list->materia;
		$idcurso =$list->idcurso;
		$curso	=$list->curso;
		$nivel	=$list->nivel;
		$idprof =$list->idprof;
		$gestion =$list->gestion;

		$list1=$this->nota->get_all_idcur($idcurso);	
		$colegio=$list1->colegio;

		$list2=$this->nota->get_prof($idprof);
		$profesor=$list2->appaterno." ".$list2->apmaterno." ".$list2->nombres;

		$list3=$this->nota->get_lista($idmat,$bi,$gestion);

		$table="indicador";		
		$list4=$this->nota->get_indik($table,$bi,$idmat,$gestion);
		foreach($list4 as $indi)
		{
			$sab1=$indi->sab1;
			$sab2=$indi->sab2;
			$sab3=$indi->sab3;
			$sab4=$indi->sab4;
			$sab5=$indi->sab5;
			$sab6=$indi->sab6;
			$hac1=$indi->hac1;
			$hac2=$indi->hac2;
			$hac3=$indi->hac3;
			$hac4=$indi->hac4;
			$hac5=$indi->hac5;
			$hac6=$indi->hac6;
		}

		


		$this->load->library('pdf');

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("REGISTRO DOCENTE");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('REGISTRO DOCENTE BIMESTRALIZADO '),0,0,'C');
            $this->pdf->Ln('15');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('UNID. EDU: '),0,0,'L');
            $this->pdf->setXY(105,45);
            $this->pdf->Cell(65,5,utf8_decode('BIMESTRE: '),0,0,'L');
            $this->pdf->setXY(165,45);
            $this->pdf->Cell(65,5,utf8_decode('GESTION: '),0,0,'L');
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->setXY(35,45);
            $this->pdf->Cell(35,5,utf8_decode($colegio),0,0,'L');
            $this->pdf->setXY(125,45);
            $this->pdf->Cell(65,5,utf8_decode($bimestre),0,0,'L');
            $this->pdf->setXY(183,45);
            $this->pdf->Cell(65,5,utf8_decode($gestion),0,0,'L');
            $this->pdf->Ln('15'); 
            $this->pdf->setXY(15,52);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(105,52);
            $this->pdf->Cell(65,5,utf8_decode('MATERIA: '),0,0,'L');            
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->setXY(35,52);
            $this->pdf->Cell(35,5,utf8_decode($nivel),0,0,'L');
            $this->pdf->setXY(125,52);
            $this->pdf->Cell(65,5,utf8_decode($materia),0,0,'L');            
            $this->pdf->Ln('15'); 
            $this->pdf->setXY(15,59);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('CURSO: '),0,0,'L');
            $this->pdf->setXY(105,59);
            $this->pdf->Cell(65,5,utf8_decode('PROFESOR: '),0,0,'L');            
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->setXY(35,59);
            $this->pdf->Cell(35,5,utf8_decode($curso),0,0,'L');
            $this->pdf->setXY(127,59);
            $this->pdf->Cell(65,5,utf8_decode($profesor),0,0,'L');  
            $this->pdf->setXY(10,62);

    		$this->pdf->SetLeftMargin(10);
    		$this->pdf->SetRightMargin(10);
    		$this->pdf->SetFillColor(255,255,255);
    		$this->pdf->SetFont('Arial', 'B', 7);
    		$this->pdf->Ln(5);
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,49,'','TBL',0,'L','1');
    		$this->pdf->Cell(55,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(110,7,'EVALUACION DEL MAESTRO','TBLR',0,'C','1'); 
    		$this->pdf->Cell(10,7,'A.EVAL','TBLR',0,'C','1');
    		$this->pdf->Cell(10,49,'FINAL','TBLR',0,'C','1');   		
    		$this->pdf->Ln(7);
    		$this->pdf->setX(70);
    		$this->pdf->Cell(20,7,'SER 10pt','TBLR',0,'C','1');
    		$this->pdf->Cell(35,7,'SABER 35pt','TBLR',0,'C','1');
		    $this->pdf->Cell(35,7,'HACER 35pt','TBLR',0,'C','1');
		    $this->pdf->Cell(20,7,'DECIDIR 10pt','TBLR',0,'C','1');
		    $this->pdf->Cell(5,7,'SE','TBLR',0,'C','1');
		    $this->pdf->Cell(5,7,'DE','TBLR',0,'C','1');
		    $this->pdf->Ln(7);
		    $this->pdf->setX(70);
		    $this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,7,'10','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'10','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'10','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,7,'PR','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,7,'PR','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'35','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,7,'PR','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,7,'10','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'10','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'10','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,7,'PR','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,7,'5','TBLR',0,'C','1');
    		$this->pdf->Cell(5,7,'5','TBLR',0,'C','1');
    		$this->pdf->Ln(7);
		    $this->pdf->setX(70);
		    $this->pdf->SetFillColor(255,247,185);
		    $this->pdf->Cell(5,28,'','TBLR',0,'C','1');
		    $this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');    		
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');    		
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1'); 
    		$this->pdf->SetFillColor(255,247,185);   		
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');    		
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(189,215,238);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->SetFillColor(255,247,185);
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->Cell(5,28,'','TBLR',0,'C','1');
    		$this->pdf->TextWithDirection(14,115,'NUMERO','U');
		    $this->pdf->TextWithDirection(74,115,'DISCIPLINA','U');
		    $this->pdf->TextWithDirection(79,115,'RESPON. PUNTUAL','U');
    		$this->pdf->TextWithDirection(84,115,'HONESTIDAD','U');
    		$this->pdf->TextWithDirection(89,115,'PROMEDIO SER','U');
    		$this->pdf->TextWithDirection(94,115,$sab1,'U');
    		$this->pdf->TextWithDirection(99,115,$sab2,'U');
    		$this->pdf->TextWithDirection(104,115,$sab3,'U');
    		$this->pdf->TextWithDirection(109,115,$sab4,'U');
    		$this->pdf->TextWithDirection(113,115,$sab5,'U');
    		$this->pdf->TextWithDirection(118,115,$sab6,'U');
    		$this->pdf->TextWithDirection(124,115,'PROMEDIO SABER','U');
    		$this->pdf->TextWithDirection(129,115,$hac1,'U');
    		$this->pdf->TextWithDirection(134,115,$hac2,'U');
    		$this->pdf->TextWithDirection(139,115,$hac3,'U');
    		$this->pdf->TextWithDirection(143,115,$hac4,'U');
    		$this->pdf->TextWithDirection(148,115,$hac5,'U');
    		$this->pdf->TextWithDirection(153,115,$hac6,'U');
    		$this->pdf->TextWithDirection(159,115,'PROMEDIO HACER','U');
    		$this->pdf->TextWithDirection(164,115,'PARTICIPACION','U');
    		$this->pdf->TextWithDirection(169,115,'SOLID. Y HONEST.','U');
    		$this->pdf->TextWithDirection(174,115,'COMUNICACION','U');
    		$this->pdf->TextWithDirection(179,115,'PROMEDIO DECIDIR','U');
    		$this->pdf->SetFillColor(255,247,185);
    		$x = 1;
    		$this->pdf->SetFont('Arial', 'B', 7);
    		$this->pdf->setXY(10,116);
		    foreach ($list3 as $mat) {
		      $this->pdf->SetFillColor(255,255,255);
		      $this->pdf->Cell(5,5,$x++,'TBL',0,'C',0);
		      $this->pdf->Cell(55,5,utf8_decode(strtoupper($mat->appat)." ".strtoupper($mat->apmat)." ".strtoupper($mat->nombres)),'TBLR',0,'L',0);
		      $this->pdf->SetFillColor(255,247,185);
		      $this->pdf->Cell(5,5,$mat->ser1,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->ser2,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->ser3,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(189,215,238);
		      $this->pdf->Cell(5,5,$mat->promser,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(255,247,185);
		      $this->pdf->Cell(5,5,$mat->sab1,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->sab2,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->sab3,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->sab4,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->sab5,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->sab6,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(189,215,238);
		      $this->pdf->Cell(5,5,$mat->promsab,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(255,247,185);
		      $this->pdf->Cell(5,5,$mat->hac1,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->hac2,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->hac3,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->hac4,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->hac5,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->hac6,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(189,215,238);
		      $this->pdf->Cell(5,5,$mat->promhac,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(255,247,185);
			  $this->pdf->Cell(5,5,$mat->dec1,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->dec2,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->dec3,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(189,215,238);
		      $this->pdf->Cell(5,5,$mat->promdec,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(255,247,185);
		      $this->pdf->Cell(5,5,$mat->autoser,'TBLR',0,'R',1);
		      $this->pdf->Cell(5,5,$mat->autodec,'TBLR',0,'R',1);
		      $this->pdf->SetFillColor(189,215,238);
		      $this->pdf->Cell(10,5,$mat->final,'TBLR',0,'R',1);

		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);
		    
		    $this->pdf->Ln(100);
    		$this->pdf->setXY(100,150);
			$this->pdf->SetFont('Arial','BU',11);
			$this->pdf->Cell(80,5,'                                                     ',0,0,'R');			
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial','B',10);
			$this->pdf->setXY(80,155);
			$this->pdf->Cell(100,5,utf8_decode($profesor),0,0,'R');
			$this->pdf->Ln(5);
			$this->pdf->setXY(110,160);
            $this->pdf->Cell(50,5,utf8_decode('FIRMA '),0,0,'R');
			
		    $this->pdf->Output("NOTAS-".$curso."-".$bimestre.".pdf", 'I');

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