<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_centralizador_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->model('Rep_centralizador_model','estud');
		//$this->load->model('Config_curso_model','curso');

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
		$this->load->view('Rep_centralizador_view');
		$this->load->view('layouts/fin');
	}
	// public function ajax_get_id()
	// {
	// 	//valores enviados 
	// 	$table=$this->input->post('table');
	// 	$cod=$this->input->post('cod');

	// 	$codgen='';
	// 	$num_rows=$this->curso->get_count_rows($table);
	// 	if($num_rows!=0)
	// 	{
	// 		$n=strlen($cod);		
	// 		$list = $this->curso->get_idcod_table($table);
	// 		$may=0;
				
	// 		foreach ($list as $codigo) {	
	// 			$idcod=$codigo->idcurso;//considerar nombre del id;				
	// 			$newcod=substr($idcod,$n,strlen($idcod));
	// 	        if($newcod>=$may)
	// 	        {
	// 	            $may=$newcod;    
	// 	        }
	// 		}
	// 		$may=$may+1;
	// 		$codgen=$cod."".$may;
	// 	}
	// 	else
	// 	{
	// 		$codgen=$cod.'1';	
	// 	}
		
	// 	echo json_encode(array("status"=>TRUE,"codgen"=>$codgen));
	// }

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}
	
	/*
	public function ajax_list()
	{
		
		$list=$this->estud->get_datatables();
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
			$row[] = '<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
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
*/

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

	public function ajax_update_estud()
	{
		//valores enviados 
		$data=array(
			//'idest'=>$this->input->post('idest'),
			'rude'=>$this->input->post('rude'),
			'ci'=>$this->input->post('ci'),
			'appaterno'=>$this->input->post('appaterno'),
			'apmaterno'=>$this->input->post('apmaterno'),
			'nombres'=>$this->input->post('nombre'),
			'genero'=>$this->input->post('genero'),
			'codigo'=>$this->input->post('codigo'),
			'foto'=>$this->input->post('foto'),
		);

		
		$this->estud->update(array('idest'=>$this->input->post('idest')),$data);

		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_get_nivel()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->estud->get_rows_nivel($table); //envia
		$data=array();
		foreach ($list as $nivel) {			
			$data[] =$nivel->nivel." ".$nivel->turno;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
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


	public function ajax_get_curso()
	{
		$tablecur=$this->input->post('TablaCur');
		$nivel=$this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list=$this->estud->get_rows_curso($tablecur,$nivel);

		$data=array();
		foreach ($list as $curso){
			$data[]=$curso->curso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
	}

	public function ajax_get_idcurso()
	{		
		$nivel=$this->input->post('nivel');
		$curso=$this->input->post('curso');

		$list=$this->estud->get_idcurso($nivel,$curso);

		$data=array();
		foreach($list as $idcur)
		{
			$data[]=$idcur->idcurso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
		//print_r($nivel."-".$gestion."-".$colegio."-".$curso);	
	}



	public function ajax_load_sql()
	{

		$notlimit=$this->input->post('notlimit');
		$idcur=$this->input->post('idcur');
		$bimes=$this->input->post('bimes');
		$gestion=$this->input->post('gestion');
		$nivel=$this->input->post('nivel');
		$curso=$this->input->post('curso');

		if(($nivel=='PRIMARIA MAÑANA')OR($nivel=='PRIMARIA TARDE'))
		{
			$idsql="primaria";
		
			$list=$this->estud->ejec_sql($notlimit,$idcur,$bimes,$gestion,$idsql,$curso);
		
			$data = array();
					
			foreach ($list as $centr) {

				$row = array();

				$row[] = $centr->idest;
				$row[] = $centr->appat;
				$row[] = $centr->apmat;
				$row[] = $centr->nombres;
				$row[] = "<input  type='text' class='form-control'  value='".$centr->LENGUAJE."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->INGLES."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".round((($centr->LENGUAJE+$centr->INGLES)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";			
				$row[] = "<input  type='text' class='form-control'  value='".$centr->SOCIALES."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->EDUFISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->MUSICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->ARTPLAST."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->MATEMATICAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->INFORMATICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->CIENCIAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".$centr->RELIGION."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
				$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->LENGUAJE+$centr->INGLES)/2),0)+$centr->SOCIALES+$centr->EDUFISICA+ $centr->MUSICA+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+$centr->RELIGION)/9),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

				$data[] = $row;

			}
			$output=array(
						"status"=>TRUE,
						"data"=>$data,
					);
			echo json_encode($output);
		}
		if(($nivel=='SECUNDARIA MAÑANA')OR($nivel=='SECUNDARIA TARDE'))
		{
			$idsql="secundaria";

			if(($curso=='PRIMERO A')OR($curso=='PRIMERO B')OR($curso=='SEGUNDO A')OR($curso=='SEGUNDO B'))
			{		
				$list=$this->estud->ejec_sql($notlimit,$idcur,$bimes,$gestion,$idsql,$curso);				

				$data = array();
						
				foreach ($list as $centr) {

					$row = array();

					$row[] = $centr->idest;
					$row[] = $centr->appat;
					$row[] = $centr->apmat;
					$row[] = $centr->nombres;
					$row[] = "<input  type='text' class='form-control'  value='".$centr->LENGUAJE."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->QUECHUA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->INGLES1+$centr->INGLES2)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";		
					$row[] = "<input  type='text' class='form-control'  value='".$centr->SOCIALES."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->EDUFISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->MUSICA1+$centr->MUSICA2)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->ARTPLAST."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2)+$centr->ARTPLAST)/6),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->MATEMATICAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->INFORMATICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->MATEMATICAS+$centr->INFORMATICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->CIENCIAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->FISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->QUIMICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->FISICA+$centr->QUIMICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->CIENCIAS)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->COSMO."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->RELIGION."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->COSMO+$centr->RELIGION)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$row[] = "<input  type='text' class='form-control'  value='".round((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2)+$centr->ARTPLAST+$centr->MATEMATICAS+round((($centr->INFORMATICA+$centr->FISICA+$centr->QUIMICA)/3),0)+$centr->CIENCIAS+$centr->COSMO+$centr->RELIGION)/11,0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$data[] = $row;					

				}
				$output=array(
						"status"=>TRUE,
						"data"=>$data,
					);
				echo json_encode($output);
			}

			if(($curso=='TERCERO A')OR($curso=='TERCERO B'))
			{		
				$list=$this->estud->ejec_sql($notlimit,$idcur,$bimes,$gestion,$idsql,$curso);				

				$data = array();
						
				foreach ($list as $centr) {

					$row = array();

					$row[] = $centr->idest;
					$row[] = $centr->appat;
					$row[] = $centr->apmat;
					$row[] = $centr->nombres;
					$row[] = "<input  type='text' class='form-control'  value='".$centr->LENGUAJE."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->QUECHUA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->INGLES1+$centr->INGLES2)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";		
					$row[] = "<input  type='text' class='form-control'  value='".$centr->SOCIALES."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->EDUFISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->ARTPLAST."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST)/6),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->MATEMATICAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->INFORMATICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->MATEMATICAS+$centr->INFORMATICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->CIENCIAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->FISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->QUIMICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->FISICA+$centr->QUIMICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->CIENCIAS)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->COSMO."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->RELIGION."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->COSMO+$centr->RELIGION)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$row[] = "<input  type='text' class='form-control'  value='".round((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$data[] = $row;					

				}
				$output=array(
						"status"=>TRUE,
						"data"=>$data,
					);
				echo json_encode($output);
			}
			if(($curso=='CUARTO A')OR($curso=='CUARTO B'))
			{		
				$list=$this->estud->ejec_sql($notlimit,$idcur,$bimes,$gestion,$idsql,$curso);				

				$data = array();
						
				foreach ($list as $centr) {

					$row = array();

					$row[] = $centr->idest;
					$row[] = $centr->appat;
					$row[] = $centr->apmat;
					$row[] = $centr->nombres;
					$row[] = "<input  type='text' class='form-control'  value='".$centr->LENGUAJE."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->INGLES1+$centr->INGLES2)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";		
					$row[] = "<input  type='text' class='form-control'  value='".$centr->SOCIALES."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->EDUFISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->ARTPLAST."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST)/6),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->MATEMATICAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->INFORMATICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->MATEMATICAS+$centr->INFORMATICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->CIENCIAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->FISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->QUIMICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->FISICA+$centr->QUIMICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->CIENCIAS)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->COSMO."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->RELIGION."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->COSMO+$centr->RELIGION)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$row[] = "<input  type='text' class='form-control'  value='".$notafinal=round(($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$data[] = $row;					

				}
				$output=array(
						"status"=>TRUE,
						"data"=>$data,
					);
				echo json_encode($output);
			}
			if(($curso=='QUINTO A')OR($curso=='QUINTO B')OR($curso=='SEXTO A')OR($curso=='SEXTO B'))
			{		
				$list=$this->estud->ejec_sql($notlimit,$idcur,$bimes,$gestion,$idsql,$curso);				

				$data = array();
						
				foreach ($list as $centr) {

					$row = array();

					$row[] = $centr->idest;
					$row[] = $centr->appat;
					$row[] = $centr->apmat;
					$row[] = $centr->nombres;
					$row[] = "<input  type='text' class='form-control'  value='".$centr->LENGUAJE."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->INGLES1+$centr->INGLES2)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";		
					$row[] = "<input  type='text' class='form-control'  value='".$centr->HISTORIA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->CIVICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->HISTORIA+$centr->CIVICA)/2),0)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->EDUFISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->ARTPLAST."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+round((($centr->HISTORIA+$centr->CIVICA)/2),0)+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST)/6),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->MATEMATICAS."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".($centr->INFORMATICA1+$centr->INFORMATICA2)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->MATEMATICAS+($centr->INFORMATICA1+$centr->INFORMATICA2))/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->BIOLOGIA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->GEOGRAFIA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->BIOLOGIA+$centr->GEOGRAFIA)/2),0)."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->FISICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->QUIMICA."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->FISICA+$centr->QUIMICA)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round(((round((($centr->FISICA+$centr->QUIMICA)/2),0)+round((($centr->BIOLOGIA+$centr->GEOGRAFIA)/2),0))/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->COSMO."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".$centr->RELIGION."' style='width: 60px; border:1px solid #FFB74D;' readonly='true'>";
					$row[] = "<input  type='text' class='form-control'  value='".round((($centr->COSMO+$centr->RELIGION)/2),0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					$row[] = "<input  type='text' class='form-control'  value='".round(($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+(($centr->HISTORIA+$centr->CIVICA)/2)+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+($centr->INFORMATICA1+$centr->INFORMATICA2)+(($centr->FISICA+$centr->QUIMICA)/2)+(($centr->BIOLOGIA+$centr->GEOGRAFIA)/2)+$centr->COSMO+$centr->RELIGION)/12,0)."'  style='width: 60px; border:1px solid #64B5F6;' readonly='true'>";

					
					$data[] = $row;					

				}
				$output=array(
						"status"=>TRUE,
						"data"=>$data,
					);
				echo json_encode($output);
			}
		}

	}
	/****************************CENTRALIZADOR  ***************************************/

	public function printcentr($id) //impresion centralizador
	{
			$notlimit=substr($id,0,strpos($id,"."));
			$bi2=substr($id,strpos($id,".")+1,1);
			$id=substr($id,strpos($id,".")+3,strlen($id));
			print_r($notlimit."-".$bi2."-".$id);
						
			$curso=$this->estud->get_print_curso_pdf($id);
			
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			if($bi2==1) $bimes="PRIMER BIMESTRE";
			if($bi2==2) $bimes="SEGUNDO BIMESTRE";
			if($bi2==3) $bimes="TERCER BIMESTRE";
			if($bi2==4) $bimes="CUARTO BIMESTRE";

			$this->load->library('pdf');
			$idsql="primaria";
		
		if(($nivel=='PRIMARIA MAÑANA')OR($nivel=='PRIMARIA TARDE'))
		{
			$i=0;
		
			$list2=$this->estud->ejec_sql($notlimit,$idcur,$bi2,$gestion,$idsql,$curso1);
			
			$num=0;
			ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->SetAutoPageBreak(false);//rompe el documento en nueva pagina
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
            $this->pdf->Ln('15');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
            $this->pdf->SetX(145);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode($bimes),0,0,'C');
            $this->pdf->Ln('6'); 

            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
    		$this->pdf->setX(40);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(68); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode('PRIMARIA COMUN. VOC.'),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
            
            //encabezado de tabla
            $this->pdf->Line(10,60,205,60);		            
            $this->pdf->Ln('12');
            $this->pdf->SetLeftMargin(10);
    		$this->pdf->SetRightMargin(10);
    		$this->pdf->SetFillColor(189,215,238); //azul
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		
    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
    		$this->pdf->Cell(84,7,'CENTRALIZADOR','TBLR',0,'C','1');
    		$this->pdf->Ln(7);
    		$this->pdf->SetX(77);
    		
    		$this->pdf->Cell(7,8,'COL','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'IN','TBLR',0,'C','1');
    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'M','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'TT','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'VER','TBLR',0,'C','1');    		
    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
    		$this->pdf->Ln(8);

    		$this->pdf->SetX(77);    		
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->SetX(98);
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

    		$this->pdf->TextWithDirection(15,116,'NUMERO','U');
    		$this->pdf->TextWithDirection(82,116,'LENGUAJE','U');
    		$this->pdf->TextWithDirection(89,116,'INGLES','U');
    		$this->pdf->TextWithDirection(96,116,'PROMEDIO COM-LEN','U');
    		$this->pdf->TextWithDirection(103,116,'CIENCIAS SOCIALES','U');
    		$this->pdf->TextWithDirection(110,116,'EDU.FISICA DEPORT','U');
    		$this->pdf->TextWithDirection(117,116,'EDU. MUSICAL','U');
    		$this->pdf->TextWithDirection(124,116,'ART. PLAST. VISUALES','U');
    		$this->pdf->TextWithDirection(131,116,'MATEMATICAS','U');
    		$this->pdf->TextWithDirection(138,116,'TECN. TECNOLOGICA','U');
    		$this->pdf->TextWithDirection(145,116,'CIENCIAS NATURALES','U');
    		$this->pdf->TextWithDirection(152,116,'VAL./ ESPIRITUALIDAD','U');
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->TextWithDirection(159,116,'PROMEDIO BIMESTRAL','U');
    		$this->pdf->Ln(34);
    		
			$row = array();
				
			foreach ($list2 as $centr) {
				$i++;
				$notafinal=round(((round((($centr->LENGUAJE+$centr->INGLES)/2),0)+$centr->SOCIALES+$centr->EDUFISICA+ $centr->MUSICA+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+$centr->RELIGION)/9),0);//nota final centralizador
				$nf_honor=round(((round((($centr->LENGUAJE+$centr->INGLES)/2),0)+$centr->SOCIALES+$centr->EDUFISICA+ $centr->MUSICA+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+$centr->RELIGION)/9),2);//nota final para cuadro de honor
				$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres))							
						);
				if($i==23)
				{
					 //encabezado de tabla
					$this->pdf->AddPage();
					$this->pdf->SetAutoPageBreak(false);
		    		$this->pdf->SetFillColor(189,215,238); //azul
		    		$this->pdf->SetFont('Arial', 'B', 8);
		    		$this->pdf->Ln(5);
		    		
		    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
		    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
		    		$this->pdf->Cell(84,7,'CENTRALIZADOR','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(77);
		    		
		    		$this->pdf->Cell(7,8,'COL','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'IN','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'M','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'TT','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,8,'VER','TBLR',0,'C','1');    		
		    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
		    		$this->pdf->Ln(8);

		    		$this->pdf->SetX(77);    		
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->SetX(98);
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

		    		$this->pdf->TextWithDirection(15,86,'NUMERO','U');
		    		$this->pdf->TextWithDirection(82,86,'LENGUAJE','U');
		    		$this->pdf->TextWithDirection(89,86,'INGLES','U');
		    		$this->pdf->TextWithDirection(96,86,'PROMEDIO COL','U');
		    		$this->pdf->TextWithDirection(103,86,'CIENCIAS SOCIALES','U');
		    		$this->pdf->TextWithDirection(110,86,'EDU.FISICA DEPORT','U');
		    		$this->pdf->TextWithDirection(117,86,'EDU. MUSICAL','U');
		    		$this->pdf->TextWithDirection(124,86,'ART. PLAST. VISUALES','U');
		    		$this->pdf->TextWithDirection(131,86,'MATEMATICAS','U');
		    		$this->pdf->TextWithDirection(138,86,'TECN. TECNOLOGICA','U');
		    		$this->pdf->TextWithDirection(145,86,'CIENCIAS NATURALES','U');
		    		$this->pdf->TextWithDirection(152,86,'VAL./ ESPIRITUALIDAD','U');
		    		$this->pdf->SetFont('Arial', 'B', 8);
		    		$this->pdf->TextWithDirection(159,86,'PROMEDIO BIMESTRAL','U');
		    		$this->pdf->Ln(34);
				}
	
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255);  //blanco
				$num=$num+1;
				$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
				$this->pdf->Cell(60,8,strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres)),'TBLR',0,'L','1');
				$this->pdf->SetFillColor(255,255,205); //amalelo 
				if ($centr->LENGUAJE<51){$this->pdf->SetFillColor(250,104,102);} elseif (($centr->LENGUAJE<61)and($centr->LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->LENGUAJE<76)and($centr->LENGUAJE>60)){$this->pdf->SetFillColor(198,255,175);}
		        $this->pdf->Cell(7,8,$centr->LENGUAJE,'TBLR',0,'C','1');
		        $this->pdf->SetFillColor(255,255,205); //amalelo 
		        if ($centr->INGLES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->INGLES<61)and($centr->INGLES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->INGLES<76)and($centr->INGLES>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->INGLES,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(189,215,238); //azul
    			$this->pdf->Cell(7,8,round((($centr->LENGUAJE+$centr->INGLES)/2),0),'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->SOCIALES<61)and($centr->SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->SOCIALES<76)and($centr->SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->SOCIALES,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->EDUFISICA<61)and($centr->EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->EDUFISICA<76)and($centr->EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
    			
    			$this->pdf->Cell(7,8,$centr->EDUFISICA,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->MUSICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->MUSICA<61)and($centr->MUSICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->MUSICA<76)and($centr->MUSICA>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->MUSICA,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->ARTPLAST<61)and($centr->ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->ARTPLAST<76)and($centr->ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
    			
    			$this->pdf->Cell(7,8,$centr->ARTPLAST,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->MATEMATICAS<61)and($centr->MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->MATEMATICAS<76)and($centr->MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->MATEMATICAS,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->INFORMATICA<61)and($centr->INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->INFORMATICA<76)and($centr->INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->INFORMATICA,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->CIENCIAS<61)and($centr->CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->CIENCIAS<76)and($centr->CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->CIENCIAS,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(255,255,205); //amalelo 
    			if ($centr->RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->RELIGION<61)and($centr->RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->RELIGION<76)and($centr->RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}
    			$this->pdf->Cell(7,8,$centr->RELIGION,'TBLR',0,'C','1');
    			$this->pdf->SetFillColor(189,215,238); //azul
    			$this->pdf->SetFont('Arial', 'B', 8);
    			$this->pdf->Cell(7,8,$notafinal,'TBLR',0,'C','1');


			    $this->pdf->Ln(7);

			}
			$j=0;
			$this->pdf->AddPage();
			$this->pdf->SetAutoPageBreak(false);			
			$this->pdf->SetFont('Arial','BU',12);			
            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Ln(7);
            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
            $this->pdf->Ln(5);
            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
            $this->pdf->Ln(5);
            $this->pdf->Cell(200,5,utf8_decode($bimes),0,0,'C');
			$this->pdf->Ln(14);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
			$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
			$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->SetFillColor(255,255,255); 
			$this->pdf->Ln(7);	
			
			arsort($row);
			foreach ($row as $honor)
			{
				$j++;
				if($j<=3)
				{
					$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
					$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
					$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
					$this->pdf->Ln(7);		
				}
				
			}
	
			$this->pdf->Ln(40);
			$this->pdf->Output("Centra -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
			ob_end_flush();
			
		}
		
		if(($nivel=='SECUNDARIA MAÑANA')OR($nivel=='SECUNDARIA TARDE'))
		{
			$idsql="secundaria";

			$curso=$this->estud->get_print_curso_pdf($id);

			
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			
			if(($curso1=='PRIMERO A')OR($curso1=='PRIMERO B')OR($curso1=='SEGUNDO A')OR($curso1=='SEGUNDO B'))
			{		
				$i=0;
				$this->load->library('pdf');							
				$list3=$this->estud->ejec_sql($notlimit,$idcur,$bi2,$gestion,$idsql,$curso1);

				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);//rompe el documento en nueva pagina
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->SetX(145);
            	$this->pdf->SetFont('Arial','B',10);
            	$this->pdf->Cell(55,5,utf8_decode($bimes),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(21,8,'TTG','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(93);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(163);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'QUECHUA','U');	    		
	    		$this->pdf->TextWithDirection(91,116,'PROMEDIO COL','U');
	    		$this->pdf->TextWithDirection(98,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(105,116,'CIENCIAS SOCIALES','U');
	    		$this->pdf->TextWithDirection(112,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(119,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(126,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(133,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(140,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(147,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(154,116,'QUIMICA','U');
	    		$this->pdf->TextWithDirection(161,116,'TEC. TECNOL. GENERAL','U');
	    		$this->pdf->TextWithDirection(168,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(175,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(182,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(189,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);
				
	    		$row = array();
				foreach ($list3 as $centr) {
					
					$notafinal=round((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2)+$centr->ARTPLAST+$centr->MATEMATICAS+round((($centr->INFORMATICA+$centr->FISICA+$centr->QUIMICA)/3),0)+$centr->CIENCIAS+$centr->COSMO+$centr->RELIGION)/11,0);

					$nf_honor=round((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2)+$centr->ARTPLAST+$centr->MATEMATICAS+round((($centr->INFORMATICA+$centr->FISICA+$centr->QUIMICA)/3),0)+$centr->CIENCIAS+$centr->COSMO+$centr->RELIGION)/11,2);
					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres))							
						);
					$MUSICA=($centr->MUSICA1+$centr->MUSICA2);
					$i++;

					if($i==23)
					{
						
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(21,8,'TTG','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(93);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(163);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'QUECHUA','U');	    		
			    		$this->pdf->TextWithDirection(91,86,'PROMEDIO COL','U');
			    		$this->pdf->TextWithDirection(98,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(105,86,'CIENCIAS SOCIALES','U');
			    		$this->pdf->TextWithDirection(112,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(119,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(126,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(133,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(140,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(147,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(154,86,'QUIMICA','U');
			    		$this->pdf->TextWithDirection(161,86,'TEC. TECNOL. GENERAL','U');
			    		$this->pdf->TextWithDirection(168,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(175,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(182,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(189,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);																				
					}
					
					$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);  //blanco
					$num=$num+1;
					$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
					$this->pdf->Cell(60,8,strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres)),'TBLR',0,'L','1');
					$this->pdf->SetFillColor(255,255,205); //amalelo 
					if ($centr->LENGUAJE<51) {$this->pdf->SetFillColor(250,255,255);} elseif (($centr->LENGUAJE<61)and($centr->LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->LENGUAJE<76)and($centr->LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);}
			        $this->pdf->Cell(7,8,$centr->LENGUAJE,'TBLR',0,'C','1');
			        $this->pdf->SetFillColor(255,255,205); //amalelo 
			        if ($centr->QUECHUA<51) {$this->pdf->SetFillColor(250,255,255);} elseif (($centr->QUECHUA<61)and($centr->QUECHUA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->QUECHUA<76)and($centr->QUECHUA>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->QUECHUA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->LENGUAJE+$centr->QUECHUA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			 if (($centr->INGLES1+$centr->INGLES2)<51) {$this->pdf->SetFillColor(255,255,255);} elseif ((($centr->INGLES1+$centr->INGLES2)<61)and(($centr->INGLES1+$centr->INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->INGLES1+$centr->INGLES2)<76)and(($centr->INGLES1+$centr->INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);}elseif(($centr->INGLES1+$centr->INGLES2)==0){$this->pdf->SetFillColor(255,255,255);}	
	    			$this->pdf->Cell(7,8,($centr->INGLES1+$centr->INGLES2),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->SOCIALES<61)and($centr->SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->SOCIALES<76)and($centr->SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->SOCIALES,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->EDUFISICA<61)and($centr->EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->EDUFISICA<76)and($centr->EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->EDUFISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($MUSICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MUSICA<61)and($MUSICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MUSICA<76)and($MUSICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$MUSICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->ARTPLAST<61)and($centr->ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->ARTPLAST<76)and($centr->ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->ARTPLAST,'TBLR',0,'C','1');	
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->MATEMATICAS<61)and($centr->MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->MATEMATICAS<76)and($centr->MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}    			
	    			$this->pdf->Cell(7,8,$centr->MATEMATICAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->INFORMATICA<61)and($centr->INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->INFORMATICA<76)and($centr->INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->INFORMATICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->FISICA<61)and($centr->FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->FISICA<76)and($centr->FISICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->FISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->QUIMICA<61)and($centr->QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->QUIMICA<76)and($centr->QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->QUIMICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->INFORMATICA+$centr->FISICA+$centr->QUIMICA)/3),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->CIENCIAS<61)and($centr->CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->CIENCIAS<76)and($centr->CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->CIENCIAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->COSMO<61)and($centr->COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->COSMO<76)and($centr->COSMO>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->COSMO,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 	
	    			if ($centr->RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->RELIGION<61)and($centr->RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->RELIGION<76)and($centr->RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}    			
	    			$this->pdf->Cell(7,8,$centr->RELIGION,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(189,215,238); //azul
	    			$this->pdf->SetFont('Arial', 'B', 8);
	    			$this->pdf->Cell(14,8,$notafinal,'TBLR',0,'C','1');
					

				    $this->pdf->Ln(7);

				}

				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($bimes),0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}

				$this->pdf->Ln(40);
				$this->pdf->Output("Centra -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}

			if(($curso1=='TERCERO A')OR($curso1=='TERCERO B'))
			{		
				$i=0;		
				$this->load->library('pdf');
							
				$list4=$this->estud->ejec_sql($notlimit,$idcur,$bi2,$gestion,$idsql,$curso1);
			
				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->SetX(145);
            	$this->pdf->SetFont('Arial','B',10);
            	$this->pdf->Cell(55,5,utf8_decode($bimes),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(93);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(156);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'QUECHUA','U');	    		
	    		$this->pdf->TextWithDirection(91,116,'PROMEDIO COL','U');
	    		$this->pdf->TextWithDirection(98,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(105,116,'CIENCIAS SOCIALES','U');
	    		$this->pdf->TextWithDirection(112,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(119,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(126,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(133,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(140,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(147,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(154,116,'PROM FIS-QUI','U');
	    		$this->pdf->TextWithDirection(161,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(168,116,'QUIMICA','U');	    	
	    		$this->pdf->TextWithDirection(175,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(182,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(189,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);
				
				$row = array();	
				foreach ($list4 as $centr) {
					$notafinal=round((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,0);
					$nf_honor=round((round((($centr->LENGUAJE+$centr->QUECHUA)/2),0)+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,2);
					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres))							
						);
					$i++;
					if($i==23)
					{
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(93);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(156);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'QUECHUA','U');	    		
			    		$this->pdf->TextWithDirection(91,86,'PROMEDIO COL','U');
			    		$this->pdf->TextWithDirection(98,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(105,86,'CIENCIAS SOCIALES','U');
			    		$this->pdf->TextWithDirection(112,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(119,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(126,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(133,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(140,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(147,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(154,86,'PROM FIS-QUI','U');
			    		$this->pdf->TextWithDirection(161,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(168,86,'QUIMICA','U');	    	
			    		$this->pdf->TextWithDirection(175,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(182,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(189,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);
					}
					
					$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);  //blanco
					$num=$num+1;
					$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
					$this->pdf->Cell(60,8,strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres)),'TBLR',0,'L','1');
					$this->pdf->SetFillColor(255,255,205); //amalelo 
					if ($centr->LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->LENGUAJE<61)and($centr->LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->LENGUAJE<76)and($centr->LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);}
			        $this->pdf->Cell(7,8,$centr->LENGUAJE,'TBLR',0,'C','1');
			        $this->pdf->SetFillColor(255,255,205); //amalelo 
			        if ($centr->QUECHUA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->QUECHUA<61)and($centr->QUECHUA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->QUECHUA<76)and($centr->QUECHUA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->QUECHUA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->LENGUAJE+$centr->QUECHUA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			 if (($centr->INGLES1+$centr->INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->INGLES1+$centr->INGLES2)<61)and(($centr->INGLES1+$centr->INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->INGLES1+$centr->INGLES2)<76)and(($centr->INGLES1+$centr->INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,($centr->INGLES1+$centr->INGLES2),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 	    			
	    			if ($centr->SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->SOCIALES<61)and($centr->SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->SOCIALES<76)and($centr->SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->SOCIALES,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			$this->pdf->Cell(7,8,$centr->EDUFISICA,'TBLR',0,'C','1');
	    			if ($centr->EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->EDUFISICA<61)and($centr->EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->EDUFISICA<76)and($centr->EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if (($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<61)and(($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<76)and(($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->ARTPLAST<61)and($centr->ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->ARTPLAST<76)and($centr->ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->ARTPLAST,'TBLR',0,'C','1');	
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->MATEMATICAS<61)and($centr->MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->MATEMATICAS<76)and($centr->MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}	    			
	    			$this->pdf->Cell(7,8,$centr->MATEMATICAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->INFORMATICA<61)and($centr->INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->INFORMATICA<76)and($centr->INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->INFORMATICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->CIENCIAS<61)and($centr->CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->CIENCIAS<76)and($centr->CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->CIENCIAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->FISICA+$centr->QUIMICA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->FISICA<61)and($centr->FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->FISICA<76)and($centr->FISICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->FISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->QUIMICA<61)and($centr->QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->QUIMICA<76)and($centr->QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->QUIMICA,'TBLR',0,'C','1');
	    			
	    			
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->COSMO<61)and($centr->COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->COSMO<76)and($centr->COSMO>60)) {$this->pdf->SetFillColor(198,255,175);}	
	    			$this->pdf->Cell(7,8,$centr->COSMO,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 	    			
	    			$this->pdf->Cell(7,8,$centr->RELIGION,'TBLR',0,'C','1');
	    			if ($centr->RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->RELIGION<61)and($centr->RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->RELIGION<76)and($centr->RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->SetFillColor(189,215,238); //azul
	    			$this->pdf->SetFont('Arial', 'B', 8);
	    			$this->pdf->Cell(14,8,$notafinal,'TBLR',0,'C','1');
					

				    $this->pdf->Ln(7);

				}

				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($bimes),0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}


				$this->pdf->Ln(40);
				$this->pdf->Output("Centra -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
				
			}
			if(($curso1=='CUARTO A')OR($curso1=='CUARTO B'))
			{		
				$i=0;

				$this->load->library('pdf');
							
				$list5=$this->estud->ejec_sql($notlimit,$idcur,$bi2,$gestion,$idsql,$curso1);
			
				$this->load->library('pdf');
							
							
				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->SetX(145);
            	$this->pdf->SetFont('Arial','B',10);
            	$this->pdf->Cell(55,5,utf8_decode($bimes),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(112,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(142);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    			    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(91,116,'CIENCIAS SOCIALES','U');
	    		$this->pdf->TextWithDirection(98,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(105,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(112,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(119,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(126,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(133,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(140,116,'PROMEDIO FIS-QUI','U');
	    		$this->pdf->TextWithDirection(147,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(154,116,'QUIMICA','U');	    		
	    		$this->pdf->TextWithDirection(161,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(168,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(175,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);		

				$row = array();						
				foreach ($list5 as $centr) {
				
					$notafinal=round(($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,0);
					$nf_honor=round(($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+$centr->SOCIALES+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+$centr->INFORMATICA+$centr->CIENCIAS+round((($centr->FISICA+$centr->QUIMICA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,2);
					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres))							
						);
					$i++;
					if($i==23)
					{
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(112,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(142);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(91,86,'CIENCIAS SOCIALES','U');
			    		$this->pdf->TextWithDirection(98,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(105,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(112,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(119,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(126,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(133,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(140,86,'PROMEDIO FIS-QUI','U');
			    		$this->pdf->TextWithDirection(147,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(154,86,'QUIMICA','U');	    		
			    		$this->pdf->TextWithDirection(161,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(168,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(175,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);		
					}

					$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);  //blanco
					$num=$num+1;
					$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
					$this->pdf->Cell(60,8,strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres)),'TBLR',0,'L','1');
					$this->pdf->SetFillColor(255,255,205); //amalelo 
					if ($centr->LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->LENGUAJE<61)and($centr->LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->LENGUAJE<76)and($centr->LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);}
			        $this->pdf->Cell(7,8,$centr->LENGUAJE,'TBLR',0,'C','1');	
			        $this->pdf->SetFillColor(255,255,205); //amalelo 
			        if (($centr->INGLES1+$centr->INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->INGLES1+$centr->INGLES2)<61)and(($centr->INGLES1+$centr->INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->INGLES1+$centr->INGLES2)<76)and(($centr->INGLES1+$centr->INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);}    			
	    			$this->pdf->Cell(7,8,($centr->INGLES1+$centr->INGLES2),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->SOCIALES<61)and($centr->SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->SOCIALES<76)and($centr->SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->SOCIALES,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->EDUFISICA<61)and($centr->EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->EDUFISICA<76)and($centr->EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->EDUFISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if (($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<61)and(($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<76)and(($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->ARTPLAST<61)and($centr->ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->ARTPLAST<76)and($centr->ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->ARTPLAST,'TBLR',0,'C','1');	
	    			$this->pdf->SetFillColor(255,255,205); //amalelo   
	    			if ($centr->MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->MATEMATICAS<61)and($centr->MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->MATEMATICAS<76)and($centr->MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}  			
	    			$this->pdf->Cell(7,8,$centr->MATEMATICAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo   
	    			if ($centr->INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->INFORMATICA<61)and($centr->INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->INFORMATICA<76)and($centr->INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);} 	    			 
	    			$this->pdf->Cell(7,8,$centr->INFORMATICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->CIENCIAS<61)and($centr->CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->CIENCIAS<76)and($centr->CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->CIENCIAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->FISICA+$centr->QUIMICA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->FISICA<61)and($centr->FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->FISICA<76)and($centr->FISICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->FISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->QUIMICA<61)and($centr->QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->QUIMICA<76)and($centr->QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->QUIMICA,'TBLR',0,'C','1');	    				    			
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->COSMO<61)and($centr->COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->COSMO<76)and($centr->COSMO>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->COSMO,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->RELIGION<61)and($centr->RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->RELIGION<76)and($centr->RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);} 	    			
	    			$this->pdf->Cell(7,8,$centr->RELIGION,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(189,215,238); //azul
	    			$this->pdf->SetFont('Arial', 'B', 8);
	    			$this->pdf->Cell(14,8,$notafinal,'TBLR',0,'C','1');
					
				    $this->pdf->Ln(7);

				}

				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($bimes),0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}
				
				$this->pdf->Ln(40);
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}
			if(($curso1=='QUINTO A')OR($curso1=='QUINTO B')OR($curso1=='SEXTO A')OR($curso1=='SEXTO B'))
			{		
				$i=0;
				$this->load->library('pdf');
							
				$list6=$this->estud->ejec_sql($notlimit,$idcur,$bi2,$gestion,$idsql,$curso1);
			
											
				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->SetX(145);
            	$this->pdf->SetFont('Arial','B',10);
            	$this->pdf->Cell(55,5,utf8_decode($bimes),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(136,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'HIS-CIV','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(14,8,'BIO-GEO','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'FIS-QUI','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(10,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(107);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->SetX(170);    		   			    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->SetX(184);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(91,116,'HISTORIA','U');
	    		$this->pdf->TextWithDirection(98,116,'CIVICA','U');
	    		$this->pdf->TextWithDirection(105,116,'PROMD CIENCIAS SOC','U');
	    		$this->pdf->TextWithDirection(112,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(119,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(126,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(133,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(140,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(147,116,'BIOLOGIA','U');
	    		$this->pdf->TextWithDirection(154,116,'GEOGRAFIA','U');
	    		$this->pdf->TextWithDirection(161,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(168,116,'PROMD FIS-QUI','U');
	    		$this->pdf->TextWithDirection(175,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(182,116,'QUIMICA','U');	    		
	    		$this->pdf->TextWithDirection(189,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(196,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(203,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);	

				$row = array();	
				foreach ($list6 as $centr) {
					$notafinal=round(($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+(($centr->HISTORIA+$centr->CIVICA)/2)+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+($centr->INFORMATICA1+$centr->INFORMATICA2)+(($centr->FISICA+$centr->QUIMICA)/2)+(($centr->BIOLOGIA+$centr->GEOGRAFIA)/2)+$centr->COSMO+$centr->RELIGION)/12,0);
					
					$nf_honor=round(($centr->LENGUAJE+($centr->INGLES1+$centr->INGLES2)+round((($centr->HISTORIA+$centr->CIVICA)/2),0)+$centr->EDUFISICA+($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)+$centr->ARTPLAST+$centr->MATEMATICAS+($centr->INFORMATICA1+$centr->INFORMATICA2)+round((($centr->FISICA+$centr->QUIMICA)/2),0)+round((($centr->BIOLOGIA+$centr->GEOGRAFIA)/2),0)+$centr->COSMO+$centr->RELIGION)/12,2);

					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres))							
						);

					$i++;
					if($i==23)
					{
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(136,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'HIS-CIV','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(14,8,'BIO-GEO','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'FIS-QUI','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(10,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(107);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->SetX(170);    		   			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->SetX(184);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(91,86,'HISTORIA','U');
			    		$this->pdf->TextWithDirection(98,86,'CIVICA','U');
			    		$this->pdf->TextWithDirection(105,86,'PROMD CIENCIAS SOC','U');
			    		$this->pdf->TextWithDirection(112,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(119,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(126,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(133,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(140,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(147,86,'BIOLOGIA','U');
			    		$this->pdf->TextWithDirection(154,86,'GEOGRAFIA','U');
			    		$this->pdf->TextWithDirection(161,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(168,86,'PROMD FIS-QUI','U');
			    		$this->pdf->TextWithDirection(175,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(182,86,'QUIMICA','U');	    		
			    		$this->pdf->TextWithDirection(189,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(196,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(203,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);	
					}
													
				    $this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);  //blanco
					$num=$num+1;
					$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
					$this->pdf->Cell(60,8,strtoupper(utf8_decode($centr->appat." ".$centr->apmat." ".$centr->nombres)),'TBLR',0,'L','1');
					$this->pdf->SetFillColor(255,255,205); //amalelo 
					if ($centr->LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->LENGUAJE<61)and($centr->LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->LENGUAJE<76)and($centr->LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);} 	
			        $this->pdf->Cell(7,8,$centr->LENGUAJE,'TBLR',0,'C','1');	    			
			        $this->pdf->SetFillColor(255,255,205); //amalelo 
			        if (($centr->INGLES1+$centr->INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->INGLES1+$centr->INGLES2)<61)and(($centr->INGLES1+$centr->INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->INGLES1+$centr->INGLES2)<76)and(($centr->INGLES1+$centr->INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,($centr->INGLES1+$centr->INGLES2),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->HISTORIA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->HISTORIA<61)and($centr->HISTORIA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->HISTORIA<76)and($centr->HISTORIA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->HISTORIA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->CIVICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->CIVICA<61)and($centr->CIVICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->CIVICA<76)and($centr->CIVICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->CIVICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->HISTORIA+$centr->CIVICA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if ($centr->EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->EDUFISICA<61)and($centr->EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->EDUFISICA<76)and($centr->EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,$centr->EDUFISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if (($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<61)and(($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)<76)and(($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3)>60)) {$this->pdf->SetFillColor(198,255,175);} 
	    			$this->pdf->Cell(7,8,($centr->MUSICA1+$centr->MUSICA2+$centr->MUSICA3),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if ($centr->ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->ARTPLAST<61)and($centr->ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->ARTPLAST<76)and($centr->ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->ARTPLAST,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if ($centr->MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->MATEMATICAS<61)and($centr->MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->MATEMATICAS<76)and($centr->MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}	    				    			
	    			$this->pdf->Cell(7,8,$centr->MATEMATICAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if (($centr->INFORMATICA1+$centr->INFORMATICA2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($centr->INFORMATICA1+$centr->INFORMATICA2)<61)and(($centr->INFORMATICA1+$centr->INFORMATICA2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($centr->INFORMATICA1+$centr->INFORMATICA2)<76)and(($centr->INFORMATICA1+$centr->INFORMATICA2)>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,($centr->INFORMATICA1+$centr->INFORMATICA2),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if ($centr->BIOLOGIA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->BIOLOGIA<61)and($centr->BIOLOGIA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->BIOLOGIA<76)and($centr->BIOLOGIA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->BIOLOGIA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo
	    			if ($centr->GEOGRAFIA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->GEOGRAFIA<61)and($centr->GEOGRAFIA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->GEOGRAFIA<76)and($centr->GEOGRAFIA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->GEOGRAFIA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->BIOLOGIA+$centr->GEOGRAFIA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(221,235,247); //azul
	    			$this->pdf->Cell(7,8,round((($centr->FISICA+$centr->QUIMICA)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->FISICA<61)and($centr->FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->FISICA<76)and($centr->FISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->FISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->QUIMICA<61)and($centr->QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->QUIMICA<76)and($centr->QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->QUIMICA,'TBLR',0,'C','1');
	    			
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->COSMO<61)and($centr->COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->COSMO<76)and($centr->COSMO>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$centr->COSMO,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($centr->RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($centr->RELIGION<61)and($centr->RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($centr->RELIGION<76)and($centr->RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}	    			
	    			$this->pdf->Cell(7,8,$centr->RELIGION,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(189,215,238); //azul
	    			$this->pdf->SetFont('Arial', 'B', 8);
	    			$this->pdf->Cell(10,8,$notafinal,'TBLR',0,'C','1');
					
				    $this->pdf->Ln(7);					

				}
				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($bimes),0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}
				$this->pdf->Ln(40);
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
			}
		}
	

	}

	//****************************** ACUMULADO ****************************************************************************** ACUMULADOS *************************

	public function printacumulado($id) //impresion centralizador
	{
			
			$curso=$this->estud->get_print_curso_pdf($id);
			$numbi=4;  //numero de bimestres
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;

			
			$this->load->library('pdf');
			$idsql="primaria";
		
		if(($nivel=='PRIMARIA MAÑANA')OR($nivel=='PRIMARIA TARDE'))
		{
			$i=0;
			
			$num=0;
			ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->SetAutoPageBreak(false);//rompe el documento en nueva pagina
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO DE 4 BIMESTRES'),0,0,'C');
            $this->pdf->Ln('15');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');

            $this->pdf->Ln('6'); 

            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
    		$this->pdf->setX(40);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(68); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode('PRIMARIA COMUN. VOC.'),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
            
            //encabezado de tabla
            $this->pdf->Line(10,60,205,60);		            
            $this->pdf->Ln('12');
            $this->pdf->SetLeftMargin(10);
    		$this->pdf->SetRightMargin(10);
    		$this->pdf->SetFillColor(189,215,238); //azul
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		
    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
    		$this->pdf->Cell(84,7,'CENTRALIZADOR','TBLR',0,'C','1');
    		$this->pdf->Ln(7);
    		$this->pdf->SetX(77);
    		
    		$this->pdf->Cell(7,8,'COL','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'IN','TBLR',0,'C','1');
    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'M','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'TT','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
    		$this->pdf->Cell(7,8,'VER','TBLR',0,'C','1');    		
    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
    		$this->pdf->Ln(8);

    		$this->pdf->SetX(77);    		
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->SetX(98);
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

    		$this->pdf->TextWithDirection(15,116,'NUMERO','U');
    		$this->pdf->TextWithDirection(82,116,'LENGUAJE','U');
    		$this->pdf->TextWithDirection(89,116,'INGLES','U');
    		$this->pdf->TextWithDirection(96,116,'PROMEDIO COM-LEN','U');
    		$this->pdf->TextWithDirection(103,116,'CIENCIAS SOCIALES','U');
    		$this->pdf->TextWithDirection(110,116,'EDU.FISICA DEPORT','U');
    		$this->pdf->TextWithDirection(117,116,'EDU. MUSICAL','U');
    		$this->pdf->TextWithDirection(124,116,'ART. PLAST. VISUALES','U');
    		$this->pdf->TextWithDirection(131,116,'MATEMATICAS','U');
    		$this->pdf->TextWithDirection(138,116,'TECN. TECNOLOGICA','U');
    		$this->pdf->TextWithDirection(145,116,'CIENCIAS NATURALES','U');
    		$this->pdf->TextWithDirection(152,116,'VAL./ ESPIRITUALIDAD','U');
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->TextWithDirection(159,116,'PROMEDIO 3 BIMESTRES','U');
    		$this->pdf->Ln(34);

			$estudiante=$this->estud->ejec_sql_estudiante($idcur);

			
			foreach ($estudiante as $list) 
			{			
				$idest=$list->idest;
				
				$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);
				
					$i++;
					
					$LENGUAJE=round((($notabi->LENGUAJE1+$notabi->LENGUAJE2+$notabi->LENGUAJE3+$notabi->LENGUAJE4)/$numbi),0);
					$INGLES=round((($notabi->INGLES1+$notabi->INGLES2+$notabi->INGLES3+$notabi->INGLES4)/$numbi),0);
					$SOCIALES=round((($notabi->SOCIALES1+$notabi->SOCIALES2+$notabi->SOCIALES3+$notabi->SOCIALES4)/$numbi),0);
					$EDUFISICA=round((($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/$numbi),0);
					$MUSICA=round((($notabi->MUSICA1+$notabi->MUSICA2+$notabi->MUSICA3+$notabi->MUSICA4)/$numbi),0);
					$ARTPLAST=round((($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/$numbi),0);
					$MATEMATICAS=round((($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/$numbi),0);
					$INFORMATICA=round((($notabi->INFORMATICA1+$notabi->INFORMATICA2+$notabi->INFORMATICA3+$notabi->INFORMATICA4)/$numbi),0);
					$CIENCIAS=round((($notabi->CIENCIAS1+$notabi->CIENCIAS2+$notabi->CIENCIAS3+$notabi->CIENCIAS4)/$numbi),0);
					$RELIGION=round((($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/$numbi),0);

					$notafinal=round(((round((($LENGUAJE+$INGLES)/2),0)+$SOCIALES+$EDUFISICA+ $MUSICA+$ARTPLAST+$MATEMATICAS+$INFORMATICA+$CIENCIAS+$RELIGION)/9),0);//nota final centralizador

					$comunidad=round((round((($LENGUAJE+$INGLES)/2),0)+$SOCIALES+$EDUFISICA+ $MUSICA+$ARTPLAST)/5,0);
					$ciencia=round(($MATEMATICAS+$INFORMATICA)/2,0);
					$vida=$CIENCIAS;
					$cosmos=$RELIGION;


					$est[]=array(
						"comunidad"=>$comunidad,
						"ciencia"=>$ciencia,
						"vida"=>$vida,
						"cosmos"=>$cosmos
					);



					$nf_honor=round(((round((($LENGUAJE+$INGLES)/2),0)+$SOCIALES+$EDUFISICA+ $MUSICA+$ARTPLAST+$MATEMATICAS+$INFORMATICA+$CIENCIAS+$RELIGION)/9),2);//nota final centralizador

					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres))							
						);


					if($i==23)
					{
						 //encabezado de tabla
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(84,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(77);
			    		
			    		$this->pdf->Cell(7,8,'COL','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'IN','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'M','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'TT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VER','TBLR',0,'C','1');    		
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(77);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(98);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(15,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(82,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(89,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(96,86,'PROMEDIO COL','U');
			    		$this->pdf->TextWithDirection(103,86,'CIENCIAS SOCIALES','U');
			    		$this->pdf->TextWithDirection(110,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(117,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(124,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(131,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(138,86,'TECN. TECNOLOGICA','U');
			    		$this->pdf->TextWithDirection(145,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(152,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(159,86,'PROMEDIO 3 BIMESTRES','U');
			    		$this->pdf->Ln(34);
					}
		
					$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);  //blanco
					$num=$num+1;
					$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
					$this->pdf->Cell(60,8,strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres)),'TBLR',0,'L','1');
					$this->pdf->SetFillColor(255,255,205); //amalelo 
					if ($LENGUAJE<51){$this->pdf->SetFillColor(250,104,102);} elseif (($LENGUAJE<61)and($LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($LENGUAJE<76)and($LENGUAJE>60)){$this->pdf->SetFillColor(198,255,175);}
			        $this->pdf->Cell(7,8,$LENGUAJE,'TBLR',0,'C','1');
			        $this->pdf->SetFillColor(255,255,205); //amalelo 

			        if ($INGLES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($INGLES<61)and($INGLES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($INGLES<76)and($INGLES>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$INGLES,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(189,215,238); //azul

	    			$this->pdf->Cell(7,8,round((($LENGUAJE+$INGLES)/2),0),'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 

	    			if ($SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($SOCIALES<61)and($SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($SOCIALES<76)and($SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$SOCIALES,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($EDUFISICA<61)and($EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($EDUFISICA<76)and($EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			
	    			$this->pdf->Cell(7,8,$EDUFISICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($MUSICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MUSICA<61)and($MUSICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MUSICA<76)and($MUSICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$MUSICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 
	    			if ($ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($ARTPLAST<61)and($ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($ARTPLAST<76)and($ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}	    			
	    			$this->pdf->Cell(7,8,$ARTPLAST,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 

	    			if ($MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MATEMATICAS<61)and($MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MATEMATICAS<76)and($MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$MATEMATICAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 

	    			if ($INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($INFORMATICA<61)and($INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($INFORMATICA<76)and($INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$INFORMATICA,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 

	    			if ($CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($CIENCIAS<61)and($CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($CIENCIAS<76)and($CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$CIENCIAS,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(255,255,205); //amalelo 

	    			if ($RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($RELIGION<61)and($RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($RELIGION<76)and($RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}
	    			$this->pdf->Cell(7,8,$RELIGION,'TBLR',0,'C','1');
	    			$this->pdf->SetFillColor(189,215,238); //azul

	    			$this->pdf->SetFont('Arial', 'B', 8);
	    			$this->pdf->Cell(7,8,$notafinal,'TBLR',0,'C','1');


				    $this->pdf->Ln(7);
			}	

			$j=0;
			$this->pdf->AddPage();
			$this->pdf->SetAutoPageBreak(false);			
			$this->pdf->SetFont('Arial','BU',12);			
            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Ln(7);
            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
            $this->pdf->Ln(5);
            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
            $this->pdf->Ln(5);
            $this->pdf->Cell(200,5,'4 bimestres',0,0,'C');
			$this->pdf->Ln(14);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
			$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
			$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->SetFillColor(255,255,255); 
			$this->pdf->Ln(7);	
			
			arsort($row);
			foreach ($row as $honor)
			{
				$j++;
				if($j<=3)
				{
					$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
					$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
					$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
					$this->pdf->Ln(7);		
				}
				
			}

			$this->pdf->Ln(20);

				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('PROMEDIO POR CAMPOS DE SABERES'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);

	            $this->pdf->SetFillColor(189,215,238); //azul
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CAMPO DE SABERES Y CONOCIMIENTOS','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				$k=0;
				$sc1=0;$sc2=0;$sc3=0;$sc4=0;								
				foreach ($est as $campo)
				{
					$k++;
					$sc1=$sc1+$campo['comunidad'];
					$sc2=$sc2+$campo['ciencia'];
					$sc3=$sc3+$campo['vida'];
					$sc4=$sc4+$campo['cosmos'];				
					
				}

				$this->pdf->Cell(12,8,'1','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COMUNIDAD Y SOCIEDAD','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc1/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);	
				$this->pdf->Cell(12,8,'2','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CIENCIA TECNOLOGIA Y PRODUCCION','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc2/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'3','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'VIDA TIERRA TERRITORIO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc3/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'4','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COSMOS Y PENSAMIENTO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc4/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);


			$this->pdf->Ln(40);
			$this->pdf->Output("Centra -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
			ob_end_flush();

		
		}


		if(($nivel=='SECUNDARIA MAÑANA')OR($nivel=='SECUNDARIA TARDE'))
		{
			$idsql="secundaria";

			$curso=$this->estud->get_print_curso_pdf($id);
			
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			$this->load->library('pdf');

		
			
			if(($curso1=='PRIMERO A')OR($curso1=='PRIMERO B')OR($curso1=='SEGUNDO A')OR($curso1=='SEGUNDO B'))
			{	
				$i=0;										

				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);//rompe el documento en nueva pagina
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO DE 4 BIMESTRES'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');;
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(21,8,'TTG','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(93);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(163);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'QUECHUA','U');	    		
	    		$this->pdf->TextWithDirection(91,116,'PROMEDIO COL','U');
	    		$this->pdf->TextWithDirection(98,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(105,116,'CIENCIAS SOCIALES','U');
	    		$this->pdf->TextWithDirection(112,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(119,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(126,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(133,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(140,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(147,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(154,116,'QUIMICA','U');
	    		$this->pdf->TextWithDirection(161,116,'TEC. TECNOL. GENERAL','U');
	    		$this->pdf->TextWithDirection(168,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(175,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(182,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(189,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);
				
	    		$estudiante=$this->estud->ejec_sql_estudiante($idcur);

				foreach ($estudiante as $list)
				{
					$idest=$list->idest;				
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);

					$i++;

					$LENGUAJE=round((($notabi->LENGUAJE1+$notabi->LENGUAJE2+$notabi->LENGUAJE3+$notabi->LENGUAJE4)/$numbi),0);
					$QUECHUA=round((($notabi->QUECHUA1+$notabi->QUECHUA2+$notabi->QUECHUA3+$notabi->QUECHUA4)/$numbi),0);
					$INGLES1=round((($notabi->INGLES11+$notabi->INGLES12+$notabi->INGLES13+$notabi->INGLES14)/$numbi),0);
					$INGLES2=round((($notabi->INGLES21+$notabi->INGLES22+$notabi->INGLES23+$notabi->INGLES24)/$numbi),0);
					$SOCIALES=round((($notabi->SOCIALES1+$notabi->SOCIALES2+$notabi->SOCIALES3+$notabi->SOCIALES4)/$numbi),0);
					$EDUFISICA=round((($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/$numbi),0);
					$MUSICA1=round((($notabi->MUSICA11+$notabi->MUSICA12+$notabi->MUSICA13+$notabi->MUSICA14)/$numbi),0);
					$MUSICA2=round((($notabi->MUSICA21+$notabi->MUSICA22+$notabi->MUSICA23+$notabi->MUSICA24)/$numbi),0);
					$MUSICA=$MUSICA1+$MUSICA2;
					$ARTPLAST=round((($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/$numbi),0);
					$MATEMATICAS=round((($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/$numbi),0);
					$INFORMATICA=round((($notabi->INFORMATICA1+$notabi->INFORMATICA2+$notabi->INFORMATICA3+$notabi->INFORMATICA4)/$numbi),0);
					$FISICA=round((($notabi->FISICA1+$notabi->FISICA2+$notabi->FISICA3+$notabi->FISICA4)/3),0);
					$QUIMICA=round((($notabi->QUIMICA1+$notabi->QUIMICA2+$notabi->QUIMICA3+$notabi->QUIMICA4)/3),0);
					$CIENCIAS=round((($notabi->CIENCIAS1+$notabi->CIENCIAS2+$notabi->CIENCIAS3+$notabi->CIENCIAS4)/$numbi),0);
					$COSMO=round((($notabi->COSMO1+$notabi->COSMO2+$notabi->COSMO3+$notabi->COSMO4)/$numbi),0);
					$RELIGION=round((($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/$numbi),0);

					$notafinal=round((round((($LENGUAJE+$QUECHUA)/2),0)+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2)+$ARTPLAST+$MATEMATICAS+round((($INFORMATICA+$FISICA+$QUIMICA)/3),0)+$CIENCIAS+$COSMO+$RELIGION)/11,0);

					$comunidad=round(( round((($LENGUAJE+$QUECHUA)/2),0)+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2)+$ARTPLAST)/6,0);
					$ciencia=round(($MATEMATICAS+round((($INFORMATICA+$FISICA+$QUIMICA)/3),0))/2,0);
					$vida=$CIENCIAS;
					$cosmos=round(($COSMO+$RELIGION)/2,0);

					$nf_honor=round((round((($LENGUAJE+$QUECHUA)/2),0)+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2)+$ARTPLAST+$MATEMATICAS+round((($INFORMATICA+$FISICA+$QUIMICA)/3),0)+$CIENCIAS+$COSMO+$RELIGION)/11,2);

					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres))							
						);

					$est[]=array(
						"comunidad"=>$comunidad,
						"ciencia"=>$ciencia,
						"vida"=>$vida,
						"cosmos"=>$cosmos
					);

					



						if($i==23)
						{
							
							$this->pdf->AddPage();
							$this->pdf->SetAutoPageBreak(false);
				    		$this->pdf->SetFillColor(189,215,238); //azul
				    		$this->pdf->SetFont('Arial', 'B', 8);
				    		$this->pdf->Ln(5);
				    		
				    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
				    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
				    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
				    		$this->pdf->Ln(7);
				    		$this->pdf->SetX(72);
				    		
				    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
				    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
				    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
				    		$this->pdf->Cell(21,8,'TTG','TBLR',0,'C','1');	    	
				    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
				    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

				    		$this->pdf->Ln(8);

				    		$this->pdf->SetX(72);    		
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->SetX(93);
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->SetX(163);
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
				    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

				    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
				    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
				    		$this->pdf->TextWithDirection(84,86,'QUECHUA','U');	    		
				    		$this->pdf->TextWithDirection(91,86,'PROMEDIO COL','U');
				    		$this->pdf->TextWithDirection(98,86,'INGLES','U');
				    		$this->pdf->TextWithDirection(105,86,'CIENCIAS SOCIALES','U');
				    		$this->pdf->TextWithDirection(112,86,'EDU.FISICA DEPORT','U');
				    		$this->pdf->TextWithDirection(119,86,'EDU. MUSICAL','U');
				    		$this->pdf->TextWithDirection(126,86,'ART. PLAST. VISUALES','U');
				    		$this->pdf->TextWithDirection(133,86,'MATEMATICAS','U');
				    		$this->pdf->TextWithDirection(140,86,'INFORMATICA','U');
				    		$this->pdf->TextWithDirection(147,86,'FISICA','U');
				    		$this->pdf->TextWithDirection(154,86,'QUIMICA','U');
				    		$this->pdf->TextWithDirection(161,86,'TEC. TECNOL. GENERAL','U');
				    		$this->pdf->TextWithDirection(168,86,'CIENCIAS NATURALES','U');
				    		$this->pdf->TextWithDirection(175,86,'COSMOS,FILOSOFIA..','U');
				    		$this->pdf->TextWithDirection(182,86,'VAL./ ESPIRITUALIDAD','U');
				    		$this->pdf->SetFont('Arial', 'B', 8);
				    		$this->pdf->TextWithDirection(189,86,'PROMEDIO BIMESTRAL','U');
				    		$this->pdf->Ln(34);																				
						}
						/*
						if(($LENGUAJE<51)or($QUECHUA<51)or(($INGLES1+$INGLES2)<51)or($SOCIALES<51)or($EDUFISICA<51)or($MUSICA<51)or($ARTPLAST<51)or($MATEMATICAS<51)or($INFORMATICA<51)or($FISICA<51)or($QUIMICA<51)or($CIENCIAS<51)or($COSMO<51)or($RELIGION<51))
						{*/
					
							$this->pdf->SetFont('Arial', '', 8);
							$this->pdf->SetFillColor(255,255,255);  //blanco
							$num=$num+1;
							$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
							$this->pdf->Cell(60,8,strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres)),'TBLR',0,'L','1');
							$this->pdf->SetFillColor(255,255,205); //amalelo 
							if ($LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($LENGUAJE<61)and($LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($LENGUAJE<76)and($LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);}
					        $this->pdf->Cell(7,8,$LENGUAJE,'TBLR',0,'C','1');
					        $this->pdf->SetFillColor(255,255,205); //amalelo 
					        if ($QUECHUA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($QUECHUA<61)and($QUECHUA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($QUECHUA<76)and($QUECHUA>60)) {$this->pdf->SetFillColor(198,255,175);}	
			    			$this->pdf->Cell(7,8,$QUECHUA,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(221,235,247); //azul
			    			$this->pdf->Cell(7,8,round((($LENGUAJE+$QUECHUA)/2),0),'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			 if (($INGLES1+$INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($INGLES1+$INGLES2)<61)and(($INGLES1+$INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($INGLES1+$INGLES2)<76)and(($INGLES1+$INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);}	
			    			$this->pdf->Cell(7,8,($INGLES1+$INGLES2),'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($SOCIALES<61)and($SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($SOCIALES<76)and($SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}	
			    			$this->pdf->Cell(7,8,$SOCIALES,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($EDUFISICA<61)and($EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($EDUFISICA<76)and($EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
			    			$this->pdf->Cell(7,8,$EDUFISICA,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($MUSICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MUSICA<61)and($MUSICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MUSICA<76)and($MUSICA>60)) {$this->pdf->SetFillColor(198,255,175);}
			    			$this->pdf->Cell(7,8,$MUSICA,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($ARTPLAST<61)and($ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($ARTPLAST<76)and($ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
			    			$this->pdf->Cell(7,8,$ARTPLAST,'TBLR',0,'C','1');	
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MATEMATICAS<61)and($MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MATEMATICAS<76)and($MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}    			
			    			$this->pdf->Cell(7,8,$MATEMATICAS,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($INFORMATICA<61)and($INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($INFORMATICA<76)and($INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
			    			$this->pdf->Cell(7,8,$INFORMATICA,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($FISICA<61)and($FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($FISICA<76)and($FISICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
			    			$this->pdf->Cell(7,8,$FISICA,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($QUIMICA<61)and($QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($QUIMICA<76)and($QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
			    			$this->pdf->Cell(7,8,$QUIMICA,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(221,235,247); //azul
			    			$this->pdf->Cell(7,8,round((($INFORMATICA+$FISICA+$QUIMICA)/3),0),'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($CIENCIAS<61)and($CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($CIENCIAS<76)and($CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);} 
			    			$this->pdf->Cell(7,8,$CIENCIAS,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 
			    			if ($COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($COSMO<61)and($COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($COSMO<76)and($COSMO>60)) {$this->pdf->SetFillColor(198,255,175);}
			    			$this->pdf->Cell(7,8,$COSMO,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(255,255,205); //amalelo 	
			    			if ($RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($RELIGION<61)and($RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($RELIGION<76)and($RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}    			
			    			$this->pdf->Cell(7,8,$RELIGION,'TBLR',0,'C','1');
			    			$this->pdf->SetFillColor(189,215,238); //azul
			    			$this->pdf->SetFont('Arial', 'B', 8);
			    			$this->pdf->Cell(14,8,$notafinal,'TBLR',0,'C','1');
							

						    $this->pdf->Ln(7);
						//}
				}
			
				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,'4 bimestres',0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}

				$this->pdf->Ln(20);

				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('PROMEDIO POR CAMPOS DE SABERES'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);

				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CAMPO DE SABERES Y CONOCIMIENTOS','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				$k=0;
				$sc1=0;$sc2=0;$sc3=0;$sc4=0;								
				foreach ($est as $campo)
				{
					$k++;
					$sc1=$sc1+$campo['comunidad'];
					$sc2=$sc2+$campo['ciencia'];
					$sc3=$sc3+$campo['vida'];
					$sc4=$sc4+$campo['cosmos'];				
					
				}

				$this->pdf->Cell(12,8,'1','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COMUNIDAD Y SOCIEDAD','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc1/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);	
				$this->pdf->Cell(12,8,'2','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CIENCIA TECNOLOGIA Y PRODUCCION','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc2/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'3','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'VIDA TIERRA TERRITORIO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc3/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'4','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COSMOS Y PENSAMIENTO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc4/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);


				
				$this->pdf->Output("Centra -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
			}

			if(($curso1=='TERCERO A')OR($curso1=='TERCERO B'))
			{		
				$i=0;		
			
				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO DE 4 BIMESTRES'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(93);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(156);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'QUECHUA','U');	    		
	    		$this->pdf->TextWithDirection(91,116,'PROMEDIO COL','U');
	    		$this->pdf->TextWithDirection(98,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(105,116,'CIENCIAS SOCIALES','U');
	    		$this->pdf->TextWithDirection(112,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(119,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(126,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(133,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(140,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(147,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(154,116,'PROM FIS-QUI','U');
	    		$this->pdf->TextWithDirection(161,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(168,116,'QUIMICA','U');	    	
	    		$this->pdf->TextWithDirection(175,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(182,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(189,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);
				
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);

				foreach ($estudiante as $list)
				{
					$idest=$list->idest;				
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);

					$i++;

					$LENGUAJE=round((($notabi->LENGUAJE1+$notabi->LENGUAJE2+$notabi->LENGUAJE3+$notabi->LENGUAJE4)/$numbi),0);
					$QUECHUA=round((($notabi->QUECHUA1+$notabi->QUECHUA2+$notabi->QUECHUA3+$notabi->QUECHUA4)/$numbi),0);
					$INGLES1=round((($notabi->INGLES11+$notabi->INGLES12+$notabi->INGLES13+$notabi->INGLES14)/$numbi),0);
					$INGLES2=round((($notabi->INGLES21+$notabi->INGLES22+$notabi->INGLES23+$notabi->INGLES24)/$numbi),0);
					$SOCIALES=round((($notabi->SOCIALES1+$notabi->SOCIALES2+$notabi->SOCIALES3+$notabi->SOCIALES4)/$numbi),0);
					$EDUFISICA=round((($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/$numbi),0);
					$MUSICA1=round((($notabi->MUSICA11+$notabi->MUSICA12+$notabi->MUSICA13+$notabi->MUSICA14)/$numbi),0);
					$MUSICA2=round((($notabi->MUSICA21+$notabi->MUSICA22+$notabi->MUSICA23+$notabi->MUSICA24)/$numbi),0);
					$MUSICA3=round((($notabi->MUSICA31+$notabi->MUSICA32+$notabi->MUSICA33+$notabi->MUSICA34)/$numbi),0);
					$ARTPLAST=round((($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/$numbi),0);
					$MATEMATICAS=round((($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/$numbi),0);
					$INFORMATICA=round((($notabi->INFORMATICA1+$notabi->INFORMATICA2+$notabi->INFORMATICA3+$notabi->INFORMATICA4)/$numbi),0);
					$FISICA=round((($notabi->FISICA1+$notabi->FISICA2+$notabi->FISICA3+$notabi->FISICA4)/$numbi),0);
					$QUIMICA=round((($notabi->QUIMICA1+$notabi->QUIMICA2+$notabi->QUIMICA3+$notabi->QUIMICA4)/$numbi),0);
					$CIENCIAS=round((($notabi->CIENCIAS1+$notabi->CIENCIAS2+$notabi->CIENCIAS3+$notabi->CIENCIAS4)/$numbi),0);
					$COSMO=round((($notabi->COSMO1+$notabi->COSMO2+$notabi->COSMO3+$notabi->COSMO4)/$numbi),0);
					$RELIGION=round((($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/$numbi),0);


					$notafinal=round((round((($LENGUAJE+$QUECHUA)/2),0)+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST+$MATEMATICAS+$INFORMATICA+round((($FISICA+$QUIMICA)/2),0)+$CIENCIAS+$COSMO+$RELIGION)/12,0);

					$nf_honor=round((round((($LENGUAJE+$QUECHUA)/2),0)+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST+$MATEMATICAS+$INFORMATICA+round((($FISICA+$QUIMICA)/2),0)+$CIENCIAS+$COSMO+$RELIGION)/12,2);


					$comunidad=round((round((($LENGUAJE+$QUECHUA)/2),0)+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST)/6,0);
					$ciencia=round(($MATEMATICAS+$INFORMATICA)/2,0);
					$vida=round((round((($FISICA+$QUIMICA)/2),0)+$CIENCIAS)/2,0);
					$cosmos=round(($COSMO+$RELIGION)/2,0);

					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres))							
						);


					$est[]=array(
						"comunidad"=>$comunidad,
						"ciencia"=>$ciencia,
						"vida"=>$vida,
						"cosmos"=>$cosmos
					);



					if($i==23)
					{
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(126,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(14,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(93);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(156);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'QUECHUA','U');	    		
			    		$this->pdf->TextWithDirection(91,86,'PROMEDIO COL','U');
			    		$this->pdf->TextWithDirection(98,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(105,86,'CIENCIAS SOCIALES','U');
			    		$this->pdf->TextWithDirection(112,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(119,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(126,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(133,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(140,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(147,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(154,86,'PROM FIS-QUI','U');
			    		$this->pdf->TextWithDirection(161,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(168,86,'QUIMICA','U');	    	
			    		$this->pdf->TextWithDirection(175,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(182,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(189,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);
					}
					
					/*if(($LENGUAJE<51)or($QUECHUA<51)or(($INGLES1+$INGLES2)<51)or($SOCIALES<51)or($EDUFISICA<51)or(($MUSICA1+$MUSICA2+$MUSICA3)<51)or($ARTPLAST<51)or($MATEMATICAS<51)or($INFORMATICA<51)or($FISICA<51)or($QUIMICA<51)or($CIENCIAS<51)or($COSMO<51)or($RELIGION<51))
						{*/


						$this->pdf->SetFont('Arial', '', 8);
						$this->pdf->SetFillColor(255,255,255);  //blanco
						$num=$num+1;
						$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
						$this->pdf->Cell(60,8,strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres)),'TBLR',0,'L','1');
						$this->pdf->SetFillColor(255,255,205); //amalelo 
						if ($LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($LENGUAJE<61)and($LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($LENGUAJE<76)and($LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);}
				        $this->pdf->Cell(7,8,$LENGUAJE,'TBLR',0,'C','1');
				        $this->pdf->SetFillColor(255,255,205); //amalelo 
				        if ($QUECHUA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($QUECHUA<61)and($QUECHUA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($QUECHUA<76)and($QUECHUA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$QUECHUA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(221,235,247); //azul
		    			$this->pdf->Cell(7,8,round((($LENGUAJE+$QUECHUA)/2),0),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			 if (($INGLES1+$INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($INGLES1+$INGLES2)<61)and(($INGLES1+$INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($INGLES1+$INGLES2)<76)and(($INGLES1+$INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,($INGLES1+$INGLES2),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 	    			
		    			if ($SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($SOCIALES<61)and($SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($SOCIALES<76)and($SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$SOCIALES,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			$this->pdf->Cell(7,8,$EDUFISICA,'TBLR',0,'C','1');
		    			if ($EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($EDUFISICA<61)and($EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($EDUFISICA<76)and($EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if (($MUSICA1+$MUSICA2+$MUSICA3)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($MUSICA1+$MUSICA2+$MUSICA3)<61)and(($MUSICA1+$MUSICA2+$MUSICA3)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($MUSICA1+$MUSICA2+$MUSICA3)<76)and(($MUSICA1+$MUSICA2+$MUSICA3)>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,($MUSICA1+$MUSICA2+$MUSICA3),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 			
		    			if ($ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($ARTPLAST<61)and($ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($ARTPLAST<76)and($ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$ARTPLAST,'TBLR',0,'C','1');	
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MATEMATICAS<61)and($MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MATEMATICAS<76)and($MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}	    			
		    			$this->pdf->Cell(7,8,$MATEMATICAS,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($INFORMATICA<61)and($INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($INFORMATICA<76)and($INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
		    			$this->pdf->Cell(7,8,$INFORMATICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($CIENCIAS<61)and($CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($CIENCIAS<76)and($CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);}	
		    			$this->pdf->Cell(7,8,$CIENCIAS,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(221,235,247); //azul
		    			$this->pdf->Cell(7,8,round((($FISICA+$QUIMICA)/2),0),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($FISICA<61)and($FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($FISICA<76)and($FISICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
		    			$this->pdf->Cell(7,8,$FISICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($QUIMICA<61)and($QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($QUIMICA<76)and($QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);}	
		    			$this->pdf->Cell(7,8,$QUIMICA,'TBLR',0,'C','1');
		    			
		    			
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($COSMO<61)and($COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($COSMO<76)and($COSMO>60)) {$this->pdf->SetFillColor(198,255,175);}	
		    			$this->pdf->Cell(7,8,$COSMO,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 	    			
		    			$this->pdf->Cell(7,8,$RELIGION,'TBLR',0,'C','1');
		    			if ($RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($RELIGION<61)and($RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($RELIGION<76)and($RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->SetFillColor(189,215,238); //azul
		    			$this->pdf->SetFont('Arial', 'B', 8);
		    			$this->pdf->Cell(14,8,$notafinal,'TBLR',0,'C','1');
					

				    	$this->pdf->Ln(7);
						//}

				}

				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,'4 bimestres',0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}


				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('PROMEDIO POR CAMPOS DE SABERES'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);

				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CAMPO DE SABERES Y CONOCIMIENTOS','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(20);	

				$k=0;
				$sc1=0;$sc2=0;$sc3=0;$sc4=0;								
				foreach ($est as $campo)
				{
					$k++;
					$sc1=$sc1+$campo['comunidad'];
					$sc2=$sc2+$campo['ciencia'];
					$sc3=$sc3+$campo['vida'];
					$sc4=$sc4+$campo['cosmos'];				
					
				}

				$this->pdf->Cell(12,8,'1','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COMUNIDAD Y SOCIEDAD','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc1/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);	
				$this->pdf->Cell(12,8,'2','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CIENCIA TECNOLOGIA Y PRODUCCION','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc2/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'3','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'VIDA TIERRA TERRITORIO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc3/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'4','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COSMOS Y PENSAMIENTO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc4/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);

				$this->pdf->Ln(40);
				$this->pdf->Output("Centra -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
				
			}

			if(($curso1=='CUARTO A')OR($curso1=='CUARTO B'))
			{		
				$i=0;			
							
				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO DE 4 BIMESTRES'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(112,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(142);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    			    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(91,116,'CIENCIAS SOCIALES','U');
	    		$this->pdf->TextWithDirection(98,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(105,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(112,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(119,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(126,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(133,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(140,116,'PROMEDIO FIS-QUI','U');
	    		$this->pdf->TextWithDirection(147,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(154,116,'QUIMICA','U');	    		
	    		$this->pdf->TextWithDirection(161,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(168,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(175,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);		

				$estudiante=$this->estud->ejec_sql_estudiante($idcur);

				foreach ($estudiante as $list)
				{
					$idest=$list->idest;				
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);

					$LENGUAJE=round((($notabi->LENGUAJE1+$notabi->LENGUAJE2+$notabi->LENGUAJE3+$notabi->LENGUAJE4)/$numbi),0);
					$INGLES1=round((($notabi->INGLES11+$notabi->INGLES12+$notabi->INGLES13+$notabi->INGLES14)/$numbi),0);
					$INGLES2=round((($notabi->INGLES21+$notabi->INGLES22+$notabi->INGLES23+$notabi->INGLES24)/$numbi),0);
					$SOCIALES=round((($notabi->SOCIALES1+$notabi->SOCIALES2+$notabi->SOCIALES3+$notabi->SOCIALES4)/$numbi),0);
					$EDUFISICA=round((($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/$numbi),0);
					$MUSICA1=round((($notabi->MUSICA11+$notabi->MUSICA12+$notabi->MUSICA13+$notabi->MUSICA14)/$numbi),0);
					$MUSICA2=round((($notabi->MUSICA21+$notabi->MUSICA22+$notabi->MUSICA23+$notabi->MUSICA24)/$numbi),0);
					$MUSICA3=round((($notabi->MUSICA31+$notabi->MUSICA32+$notabi->MUSICA33+$notabi->MUSICA34)/$numbi),0);
					$ARTPLAST=round((($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/$numbi),0);
					$MATEMATICAS=round((($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/$numbi),0);
					$INFORMATICA=round((($notabi->INFORMATICA1+$notabi->INFORMATICA2+$notabi->INFORMATICA3+$notabi->INFORMATICA4)/$numbi),0);
					$FISICA=round((($notabi->FISICA1+$notabi->FISICA2+$notabi->FISICA3+$notabi->FISICA4)/$numbi),0);
					$QUIMICA=round((($notabi->QUIMICA1+$notabi->QUIMICA2+$notabi->QUIMICA3+$notabi->QUIMICA4)/$numbi),0);
					$CIENCIAS=round((($notabi->CIENCIAS1+$notabi->CIENCIAS2+$notabi->CIENCIAS3+$notabi->CIENCIAS4)/$numbi),0);
					$COSMO=round((($notabi->COSMO1+$notabi->COSMO2+$notabi->COSMO3+$notabi->COSMO4)/$numbi),0);
					$RELIGION=round((($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/$numbi),0);


					$notafinal=round(($LENGUAJE+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST+$MATEMATICAS+$INFORMATICA+$CIENCIAS+round((($FISICA+$QUIMICA)/2),0)+$COSMO+$RELIGION)/12,0);

					$nf_honor=round(($LENGUAJE+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST+$MATEMATICAS+$INFORMATICA+$CIENCIAS+round((($FISICA+$QUIMICA)/2),0)+$COSMO+$RELIGION)/12,2);

					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres))							
						);

					$comunidad=round(($LENGUAJE+($INGLES1+$INGLES2)+$SOCIALES+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST)/6,0);
					$ciencia=round(($MATEMATICAS+$INFORMATICA)/2,0);
					$vida=round(($CIENCIAS+round((($FISICA+$QUIMICA)/2),0)/2),0);
					$cosmos=round(($COSMO+$RELIGION)/2,0);					

					$est[]=array(
						"comunidad"=>$comunidad,
						"ciencia"=>$ciencia,
						"vida"=>$vida,
						"cosmos"=>$cosmos
					);

					
					$i++;
					if($i==23)
					{
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(112,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'CN','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'FQ','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(14,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(142);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(91,86,'CIENCIAS SOCIALES','U');
			    		$this->pdf->TextWithDirection(98,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(105,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(112,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(119,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(126,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(133,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(140,86,'PROMEDIO FIS-QUI','U');
			    		$this->pdf->TextWithDirection(147,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(154,86,'QUIMICA','U');	    		
			    		$this->pdf->TextWithDirection(161,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(168,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(175,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);		
					}
					/*
					if(($LENGUAJE<51)or(($INGLES1+$INGLES2)<51)or($SOCIALES<51)or($EDUFISICA<51)or(($MUSICA1+$MUSICA2+$MUSICA3)<51)or($ARTPLAST<51)or($MATEMATICAS<51)or($INFORMATICA<51)or($FISICA<51)or($QUIMICA<51)or($CIENCIAS<51)or($COSMO<51)or($RELIGION<51))
					{*/
					
						$this->pdf->SetFont('Arial', '', 8);
						$this->pdf->SetFillColor(255,255,255);  //blanco
						$num=$num+1;
						$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
						$this->pdf->Cell(60,8,strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres)),'TBLR',0,'L','1');
						$this->pdf->SetFillColor(255,255,205); //amalelo 
						if ($LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($LENGUAJE<61)and($LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($LENGUAJE<76)and($LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);}
				        $this->pdf->Cell(7,8,$LENGUAJE,'TBLR',0,'C','1');	
				        $this->pdf->SetFillColor(255,255,205); //amalelo 
				        if (($INGLES1+$INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($INGLES1+$INGLES2)<61)and(($INGLES1+$INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($INGLES1+$INGLES2)<76)and(($INGLES1+$INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);}    			
		    			$this->pdf->Cell(7,8,($INGLES1+$INGLES2),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($SOCIALES<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($SOCIALES<61)and($SOCIALES>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($SOCIALES<76)and($SOCIALES>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$SOCIALES,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($EDUFISICA<61)and($EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($EDUFISICA<76)and($EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$EDUFISICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if (($MUSICA1+$MUSICA2+$MUSICA3)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($MUSICA1+$MUSICA2+$MUSICA3)<61)and(($MUSICA1+$MUSICA2+$MUSICA3)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($MUSICA1+$MUSICA2+$MUSICA3)<76)and(($MUSICA1+$MUSICA2+$MUSICA3)>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,($MUSICA1+$MUSICA2+$MUSICA3),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($ARTPLAST<61)and($ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($ARTPLAST<76)and($ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$ARTPLAST,'TBLR',0,'C','1');	
		    			$this->pdf->SetFillColor(255,255,205); //amalelo   
		    			if ($MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MATEMATICAS<61)and($MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MATEMATICAS<76)and($MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}  			
		    			$this->pdf->Cell(7,8,$MATEMATICAS,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo   
		    			if ($INFORMATICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($INFORMATICA<61)and($INFORMATICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($INFORMATICA<76)and($INFORMATICA>60)) {$this->pdf->SetFillColor(198,255,175);} 	    			 
		    			$this->pdf->Cell(7,8,$INFORMATICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($CIENCIAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($CIENCIAS<61)and($CIENCIAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($CIENCIAS<76)and($CIENCIAS>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,$CIENCIAS,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(221,235,247); //azul
		    			$this->pdf->Cell(7,8,round((($FISICA+$QUIMICA)/2),0),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($FISICA<61)and($FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($FISICA<76)and($FISICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,$FISICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($QUIMICA<61)and($QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($QUIMICA<76)and($QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,$QUIMICA,'TBLR',0,'C','1');	    				    			
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($COSMO<61)and($COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($COSMO<76)and($COSMO>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,$COSMO,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($RELIGION<61)and($RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($RELIGION<76)and($RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);} 	    			
		    			$this->pdf->Cell(7,8,$RELIGION,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(189,215,238); //azul
		    			$this->pdf->SetFont('Arial', 'B', 8);
		    			$this->pdf->Cell(14,8,$notafinal,'TBLR',0,'C','1');
						
					    $this->pdf->Ln(7);

					//}
				}
					
				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,'4 bimestres',0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}

				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('PROMEDIO POR CAMPOS DE SABERES'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);

				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CAMPO DE SABERES Y CONOCIMIENTOS','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(20);	

				$k=0;
				$sc1=0;$sc2=0;$sc3=0;$sc4=0;								
				foreach ($est as $campo)
				{
					$k++;
					$sc1=$sc1+$campo['comunidad'];
					$sc2=$sc2+$campo['ciencia'];
					$sc3=$sc3+$campo['vida'];
					$sc4=$sc4+$campo['cosmos'];				
					
				}

				$this->pdf->Cell(12,8,'1','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COMUNIDAD Y SOCIEDAD','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc1/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);	
				$this->pdf->Cell(12,8,'2','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CIENCIA TECNOLOGIA Y PRODUCCION','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc2/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'3','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'VIDA TIERRA TERRITORIO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc3/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'4','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COSMOS Y PENSAMIENTO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc4/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);

				$this->pdf->Ln(40);




				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}
			
			if(($curso1=='QUINTO A')OR($curso1=='QUINTO B')OR($curso1=='SEXTO A')OR($curso1=='SEXTO B'))
			{		
				$i=0;
										
				$num=0;
				ob_start();
				$this->pdf=new Pdf('Letter');
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO DE 4 BIMESTRES'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
	            $this->pdf->setX(75);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(75); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
	            $this->pdf->SetLeftMargin(5);
	    		$this->pdf->SetRightMargin(5);
	    		$this->pdf->SetFillColor(189,215,238); //azul
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		
	    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
	    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(136,7,'CENTRALIZADOR','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(72);
	    		
	    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'HIS-CIV','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');	    	
	    		$this->pdf->Cell(14,8,'BIO-GEO','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(14,8,'FIS-QUI','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
	    		$this->pdf->Cell(10,42,'','TBLR',0,'C','1');

	    		$this->pdf->Ln(8);

	    		$this->pdf->SetX(72);    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->SetX(107);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->SetX(170);    		   			    		
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->SetX(184);
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
	    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

	    		$this->pdf->TextWithDirection(10,116,'NUMERO','U');
	    		$this->pdf->TextWithDirection(77,116,'LENGUAJE','U');
	    		$this->pdf->TextWithDirection(84,116,'INGLES','U');
	    		$this->pdf->TextWithDirection(91,116,'HISTORIA','U');
	    		$this->pdf->TextWithDirection(98,116,'CIVICA','U');
	    		$this->pdf->TextWithDirection(105,116,'PROMD CIENCIAS SOC','U');
	    		$this->pdf->TextWithDirection(112,116,'EDU.FISICA DEPORT','U');
	    		$this->pdf->TextWithDirection(119,116,'EDU. MUSICAL','U');
	    		$this->pdf->TextWithDirection(126,116,'ART. PLAST. VISUALES','U');
	    		$this->pdf->TextWithDirection(133,116,'MATEMATICAS','U');
	    		$this->pdf->TextWithDirection(140,116,'INFORMATICA','U');
	    		$this->pdf->TextWithDirection(147,116,'BIOLOGIA','U');
	    		$this->pdf->TextWithDirection(154,116,'GEOGRAFIA','U');
	    		$this->pdf->TextWithDirection(161,116,'CIENCIAS NATURALES','U');
	    		$this->pdf->TextWithDirection(168,116,'PROMD FIS-QUI','U');
	    		$this->pdf->TextWithDirection(175,116,'FISICA','U');
	    		$this->pdf->TextWithDirection(182,116,'QUIMICA','U');	    		
	    		$this->pdf->TextWithDirection(189,116,'COSMOS,FILOSOFIA..','U');
	    		$this->pdf->TextWithDirection(196,116,'VAL./ ESPIRITUALIDAD','U');
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->TextWithDirection(203,116,'PROMEDIO BIMESTRAL','U');
	    		$this->pdf->Ln(34);	

				$estudiante=$this->estud->ejec_sql_estudiante($idcur);

				foreach ($estudiante as $list)
				{
					$idest=$list->idest;					
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);

					$LENGUAJE=round((($notabi->LENGUAJE1+$notabi->LENGUAJE2+$notabi->LENGUAJE3+$notabi->LENGUAJE4)/$numbi),0);
					$INGLES1=round((($notabi->INGLES11+$notabi->INGLES12+$notabi->INGLES13+$notabi->INGLES14)/$numbi),0);
					$INGLES2=round((($notabi->INGLES21+$notabi->INGLES22+$notabi->INGLES23+$notabi->INGLES24)/$numbi),0);
					$HISTORIA=round((($notabi->HISTORIA1+$notabi->HISTORIA2+$notabi->HISTORIA3+$notabi->HISTORIA4)/$numbi),0);
					$CIVICA=round((($notabi->CIVICA1+$notabi->CIVICA2+$notabi->CIVICA3+$notabi->CIVICA4)/$numbi),0);					
					$EDUFISICA=round((($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/$numbi),0);
					$MUSICA1=round((($notabi->MUSICA11+$notabi->MUSICA12+$notabi->MUSICA13+$notabi->MUSICA14)/$numbi),0);
					$MUSICA2=round((($notabi->MUSICA21+$notabi->MUSICA22+$notabi->MUSICA23+$notabi->MUSICA24)/$numbi),0);
					$MUSICA3=round((($notabi->MUSICA31+$notabi->MUSICA32+$notabi->MUSICA33+$notabi->MUSICA34)/$numbi),0);
					$ARTPLAST=round((($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/$numbi),0);
					$MATEMATICAS=round((($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/$numbi),0);
					$INFORMATICA1=round((($notabi->INFORMATICA11+$notabi->INFORMATICA12+$notabi->INFORMATICA13+$notabi->INFORMATICA14)/$numbi),0);
					$INFORMATICA2=round((($notabi->INFORMATICA21+$notabi->INFORMATICA22+$notabi->INFORMATICA23+$notabi->INFORMATICA24)/$numbi),0);
					$FISICA=round((($notabi->FISICA1+$notabi->FISICA2+$notabi->FISICA3+$notabi->FISICA4)/$numbi),0);
					$QUIMICA=round((($notabi->QUIMICA1+$notabi->QUIMICA2+$notabi->QUIMICA3+$notabi->QUIMICA4)/$numbi),0);
					$BIOLOGIA=round((($notabi->BIOLOGIA1+$notabi->BIOLOGIA2+$notabi->BIOLOGIA3+$notabi->BIOLOGIA4)/$numbi),0);
					$GEOGRAFIA=round((($notabi->GEOGRAFIA1+$notabi->GEOGRAFIA2+$notabi->GEOGRAFIA3+$notabi->GEOGRAFIA4)/$numbi),0);					
					$COSMO=round((($notabi->COSMO1+$notabi->COSMO2+$notabi->COSMO3+$notabi->COSMO4)/$numbi),0);
					$RELIGION=round((($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/$numbi),0);

					$notafinal=round(($LENGUAJE+($INGLES1+$INGLES2)+(($HISTORIA+$CIVICA)/2)+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST+$MATEMATICAS+($INFORMATICA1+$INFORMATICA2)+(($FISICA+$QUIMICA)/2)+(($BIOLOGIA+$GEOGRAFIA)/2)+$COSMO+$RELIGION)/12,0);

					$nf_honor=round(($LENGUAJE+($INGLES1+$INGLES2)+(($HISTORIA+$CIVICA)/2)+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST+$MATEMATICAS+($INFORMATICA1+$INFORMATICA2)+(($FISICA+$QUIMICA)/2)+(($BIOLOGIA+$GEOGRAFIA)/2)+$COSMO+$RELIGION)/12,2);

					$row[] = array(
							"nota"=>$nf_honor,
							"nomb"=>strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres))							
						);

					$comunidad=round(($LENGUAJE+($INGLES1+$INGLES2)+(($HISTORIA+$CIVICA)/2)+$EDUFISICA+($MUSICA1+$MUSICA2+$MUSICA3)+$ARTPLAST)/6,0);
					$ciencia=round(($MATEMATICAS+($INFORMATICA1+$INFORMATICA2))/2,0);
					$vida=round((round(($FISICA+$QUIMICA)/2,0)+round(($BIOLOGIA+$GEOGRAFIA)/2,0))/2,0);
					$cosmos=round(($COSMO+$RELIGION)/2,0);					

					$est[]=array(
						"comunidad"=>$comunidad,
						"ciencia"=>$ciencia,
						"vida"=>$vida,
						"cosmos"=>$cosmos
					);


					$i++;
					if($i==23)
					{
						$this->pdf->AddPage();
						$this->pdf->SetAutoPageBreak(false);
			    		$this->pdf->SetFillColor(189,215,238); //azul
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->Ln(5);
			    		
			    		$this->pdf->Cell(7,49,'','TBL',0,'L','1');		    		
			    		$this->pdf->Cell(60,49,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(136,7,'CENTRALIZADOR','TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    		$this->pdf->SetX(72);
			    		
			    		$this->pdf->Cell(7,8,'LCO','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'LEX','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'HIS-CIV','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EFD','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'EM','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'APV','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(7,8,'MAT','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'INF','TBLR',0,'C','1');	    	
			    		$this->pdf->Cell(14,8,'BIO-GEO','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,42,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(14,8,'FIS-QUI','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,8,'COS','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,8,'VAL','TBLR',0,'C','1');	    			    	
			    		$this->pdf->Cell(10,42,'','TBLR',0,'C','1');

			    		$this->pdf->Ln(8);

			    		$this->pdf->SetX(72);    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->SetX(107);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->SetX(170);    		   			    		
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->SetX(184);
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');	
			    		$this->pdf->Cell(7,34,'','TBLR',0,'C','1');

			    		$this->pdf->TextWithDirection(10,86,'NUMERO','U');
			    		$this->pdf->TextWithDirection(77,86,'LENGUAJE','U');
			    		$this->pdf->TextWithDirection(84,86,'INGLES','U');
			    		$this->pdf->TextWithDirection(91,86,'HISTORIA','U');
			    		$this->pdf->TextWithDirection(98,86,'CIVICA','U');
			    		$this->pdf->TextWithDirection(105,86,'PROMD CIENCIAS SOC','U');
			    		$this->pdf->TextWithDirection(112,86,'EDU.FISICA DEPORT','U');
			    		$this->pdf->TextWithDirection(119,86,'EDU. MUSICAL','U');
			    		$this->pdf->TextWithDirection(126,86,'ART. PLAST. VISUALES','U');
			    		$this->pdf->TextWithDirection(133,86,'MATEMATICAS','U');
			    		$this->pdf->TextWithDirection(140,86,'INFORMATICA','U');
			    		$this->pdf->TextWithDirection(147,86,'BIOLOGIA','U');
			    		$this->pdf->TextWithDirection(154,86,'GEOGRAFIA','U');
			    		$this->pdf->TextWithDirection(161,86,'CIENCIAS NATURALES','U');
			    		$this->pdf->TextWithDirection(168,86,'PROMD FIS-QUI','U');
			    		$this->pdf->TextWithDirection(175,86,'FISICA','U');
			    		$this->pdf->TextWithDirection(182,86,'QUIMICA','U');	    		
			    		$this->pdf->TextWithDirection(189,86,'COSMOS,FILOSOFIA..','U');
			    		$this->pdf->TextWithDirection(196,86,'VAL./ ESPIRITUALIDAD','U');
			    		$this->pdf->SetFont('Arial', 'B', 8);
			    		$this->pdf->TextWithDirection(203,86,'PROMEDIO BIMESTRAL','U');
			    		$this->pdf->Ln(34);	
					}
					/*
					if(($LENGUAJE<51)or(($INGLES1+$INGLES2)<51)or($HISTORIA<51)or($CIVICA<51)or($EDUFISICA<51)or(($MUSICA1+$MUSICA2+$MUSICA3)<51)or($ARTPLAST<51)or($MATEMATICAS<51)or(($INFORMATICA1+$INFORMATICA2)<51)or($BIOLOGIA<51)or($GEOGRAFIA<51)or($FISICA<51)or($QUIMICA<51)or($COSMO<51)or($RELIGION<51))
					{*/
															
					    $this->pdf->SetFont('Arial', '', 8);
						$this->pdf->SetFillColor(255,255,255);  //blanco
						$num=$num+1;
						$this->pdf->Cell(7,8,$num,'TBLR',0,'L','1');
						$this->pdf->Cell(60,8,strtoupper(utf8_decode($notabi->appat." ".$notabi->apmat." ".$notabi->nombres)),'TBLR',0,'L','1');
						$this->pdf->SetFillColor(255,255,205); //amalelo 
						if ($LENGUAJE<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($LENGUAJE<61)and($LENGUAJE>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($LENGUAJE<76)and($LENGUAJE>60)) {$this->pdf->SetFillColor(198,255,175);} 	
				        $this->pdf->Cell(7,8,$LENGUAJE,'TBLR',0,'C','1');	    			
				        $this->pdf->SetFillColor(255,255,205); //amalelo 
				        if (($INGLES1+$INGLES2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($INGLES1+$INGLES2)<61)and(($INGLES1+$INGLES2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($INGLES1+$INGLES2)<76)and(($INGLES1+$INGLES2)>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,($INGLES1+$INGLES2),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($HISTORIA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($HISTORIA<61)and($HISTORIA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($HISTORIA<76)and($HISTORIA>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,$HISTORIA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($CIVICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($CIVICA<61)and($CIVICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($CIVICA<76)and($CIVICA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$CIVICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(221,235,247); //azul
		    			$this->pdf->Cell(7,8,round((($HISTORIA+$CIVICA)/2),0),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if ($EDUFISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($EDUFISICA<61)and($EDUFISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($EDUFISICA<76)and($EDUFISICA>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,$EDUFISICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if (($MUSICA1+$MUSICA2+$MUSICA3)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($MUSICA1+$MUSICA2+$MUSICA3)<61)and(($MUSICA1+$MUSICA2+$MUSICA3)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($MUSICA1+$MUSICA2+$MUSICA3)<76)and(($MUSICA1+$MUSICA2+$MUSICA3)>60)) {$this->pdf->SetFillColor(198,255,175);} 
		    			$this->pdf->Cell(7,8,($MUSICA1+$MUSICA2+$MUSICA3),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if ($ARTPLAST<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($ARTPLAST<61)and($ARTPLAST>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($ARTPLAST<76)and($ARTPLAST>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$ARTPLAST,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if ($MATEMATICAS<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($MATEMATICAS<61)and($MATEMATICAS>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($MATEMATICAS<76)and($MATEMATICAS>60)) {$this->pdf->SetFillColor(198,255,175);}	    				    			
		    			$this->pdf->Cell(7,8,$MATEMATICAS,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if (($INFORMATICA1+$INFORMATICA2)<51) {$this->pdf->SetFillColor(250,104,102);} elseif ((($INFORMATICA1+$INFORMATICA2)<61)and(($INFORMATICA1+$INFORMATICA2)>50)){$this->pdf->SetFillColor(221,200,255);}elseif ((($INFORMATICA1+$INFORMATICA2)<76)and(($INFORMATICA1+$INFORMATICA2)>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,($INFORMATICA1+$INFORMATICA2),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if ($BIOLOGIA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($BIOLOGIA<61)and($BIOLOGIA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($BIOLOGIA<76)and($BIOLOGIA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$BIOLOGIA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo
		    			if ($GEOGRAFIA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($GEOGRAFIA<61)and($GEOGRAFIA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($GEOGRAFIA<76)and($GEOGRAFIA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$GEOGRAFIA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(221,235,247); //azul
		    			$this->pdf->Cell(7,8,round((($BIOLOGIA+$GEOGRAFIA)/2),0),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(221,235,247); //azul
		    			$this->pdf->Cell(7,8,round((($FISICA+$QUIMICA)/2),0),'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($FISICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($FISICA<61)and($FISICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($FISICA<76)and($FISICA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$FISICA,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($QUIMICA<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($QUIMICA<61)and($QUIMICA>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($QUIMICA<76)and($QUIMICA>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$QUIMICA,'TBLR',0,'C','1');
		    			
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($COSMO<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($COSMO<61)and($COSMO>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($COSMO<76)and($COSMO>60)) {$this->pdf->SetFillColor(198,255,175);}
		    			$this->pdf->Cell(7,8,$COSMO,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(255,255,205); //amalelo 
		    			if ($RELIGION<51) {$this->pdf->SetFillColor(250,104,102);} elseif (($RELIGION<61)and($RELIGION>50)){$this->pdf->SetFillColor(221,200,255);}elseif (($RELIGION<76)and($RELIGION>60)) {$this->pdf->SetFillColor(198,255,175);}	    			
		    			$this->pdf->Cell(7,8,$RELIGION,'TBLR',0,'C','1');
		    			$this->pdf->SetFillColor(189,215,238); //azul
		    			$this->pdf->SetFont('Arial', 'B', 8);
		    			$this->pdf->Cell(10,8,$notafinal,'TBLR',0,'C','1');
						
					    $this->pdf->Ln(7);
					//}				

				}

				$j=0;
				$this->pdf->AddPage();
				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Ln(7);
	            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
	            $this->pdf->Ln(5);
	            $this->pdf->Cell(200,5,'4 bimestres',0,0,'C');
				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(7);	
				
				arsort($row);
				foreach ($row as $honor)
				{
					$j++;
					if($j<=3)
					{
						$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
						$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
						$this->pdf->Cell(14,8,$honor['nota'],'TBLR',0,'R','1');
						$this->pdf->Ln(7);		
					}
					
				}

				$this->pdf->SetAutoPageBreak(false);			
				$this->pdf->SetFont('Arial','BU',12);			
	            $this->pdf->Cell(200,8,utf8_decode('PROMEDIO POR CAMPOS DE SABERES'),0,0,'C');            
	            $this->pdf->SetFont('Arial','B',10);

				$this->pdf->Ln(14);
				$this->pdf->SetFont('Arial', 'B', 8);
				$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CAMPO DE SABERES Y CONOCIMIENTOS','TBLR',0,'C','1');
				$this->pdf->Cell(14,8,'NOTA','TBLR',0,'C','1');
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255); 
				$this->pdf->Ln(20);	

				$k=0;
				$sc1=0;$sc2=0;$sc3=0;$sc4=0;								
				foreach ($est as $campo)
				{
					$k++;
					$sc1=$sc1+$campo['comunidad'];
					$sc2=$sc2+$campo['ciencia'];
					$sc3=$sc3+$campo['vida'];
					$sc4=$sc4+$campo['cosmos'];				
					
				}

				$this->pdf->Cell(12,8,'1','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COMUNIDAD Y SOCIEDAD','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc1/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);	
				$this->pdf->Cell(12,8,'2','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'CIENCIA TECNOLOGIA Y PRODUCCION','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc2/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'3','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'VIDA TIERRA TERRITORIO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc3/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				$this->pdf->Cell(12,8,'4','TBLR',0,'L','1');
				$this->pdf->Cell(80,8,'COSMOS Y PENSAMIENTO','TBLR',0,'L','1');
				$this->pdf->Cell(14,8,round($sc4/$k,0),'TBLR',0,'R','1');
				$this->pdf->Ln(7);
				
				
				$this->pdf->Ln(40);
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
			}

		}
	

	}
/*******************BOLETINES******************************/
	public function printboletin($id)
	{
			//print_r($id);
			$curso=$this->estud->get_print_curso_pdf($id);
			$notlimit=101;
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			$bi2='2';

			$this->load->library('pdf');
			$idsql="primaria";
		
			
		
		if(($nivel=='PRIMARIA MAÑANA')OR($nivel=='PRIMARIA TARDE'))
		{			
			$estudiante=$this->estud->ejec_sql_estudiante($idcur);
			ob_start();
			$this->pdf=new Pdf('Letter');
			foreach ($estudiante as $list) 
			{
				
				$idest=$list->idest;
				
				$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);	
				
				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
	            $this->pdf->setX(55);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
	            $this->pdf->setX(78);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
	            $this->pdf->SetX(97);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
	    		$this->pdf->setX(38);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
	            $this->pdf->setX(70); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode("PRIMARIA COMUN. VOC."),0,0,'L');	
	            $this->pdf->SetX(115);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
	            $this->pdf->SetX(138);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('AP. PATERNO: '),0,0,'L');
	    		$this->pdf->setX(55);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,strtoupper(utf8_decode($list->appat)),0,0,'L');
	    		$this->pdf->Ln('6');
	            $this->pdf->setX(15); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('AP. MATERNO: '),0,0,'L');		            
	            $this->pdf->setX(55); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,strtoupper(utf8_decode($list->apmat)),0,0,'L');	
	            $this->pdf->Ln('6');
	            $this->pdf->SetX(15);
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(15,5,utf8_decode('NOMBRES:'),0,0,'L');
	            $this->pdf->SetX(55);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(65,5,strtoupper(utf8_decode($list->nombres)),0,0,'L');
	            $this->pdf->Ln('6');

	            $this->pdf->SetLeftMargin(15);
	    		$this->pdf->SetRightMargin(15);
	    		$this->pdf->SetFillColor(192,192,192);
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		$this->pdf->Cell(8,21,'NUM','TBL',0,'L','1');
	    		$this->pdf->Cell(12,21,'SIGLA','TBL',0,'C','1');
	    		$this->pdf->Cell(60,21,utf8_decode('ÁREA'),'TBL',0,'C','1');
	    		$this->pdf->Cell(98,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(95);
	    		$this->pdf->Cell(80,7,'BIMESTRE','TBLR',0,'C','1');
	    		$this->pdf->Cell(18,14,'PRO ANUAL','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetX(95);
	    		$this->pdf->Cell(10,7,'1ER','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'2DO','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'3RO','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'4TO','TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->SetFillColor(255,255,255);
	    		$this->pdf->SetFont('Arial', '', 8);
	    		$this->pdf->Cell(8,7,'1','TBL',0,'L','1');
	    		$this->pdf->Cell(12,14,'LCO','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE1+$notabi->INGLES1)/2),0),'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE2+$notabi->INGLES2)/2),0),'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE3+$notabi->INGLES3)/2),0),'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->LENGUAJE4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE4+$notabi->INGLES4)/2),0),'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,14,round((round((($notabi->LENGUAJE1+$notabi->INGLES1)/2),0)+round((($notabi->LENGUAJE2+$notabi->INGLES2)/2),0)+round((($notabi->LENGUAJE3+$notabi->INGLES3)/2),0)+round((($notabi->LENGUAJE4+$notabi->INGLES4)/2)))/4,0),'TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'2','TBLR',0,'L','1');
	    		$this->pdf->SetX(35);
	    		$this->pdf->Cell(60,7,'INGLES','TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->INGLES1,'TBLR',0,'C','1');
	    		$this->pdf->SetX(115);
	    		$this->pdf->Cell(10,7,$notabi->INGLES2,'TBLR',0,'C','1');
	    		$this->pdf->SetX(135);
	    		$this->pdf->Cell(10,7,$notabi->INGLES3,'TBLR',0,'C','1');
	    		$this->pdf->SetX(155);
	    		$this->pdf->Cell(10,7,$notabi->INGLES4,'TBLR',0,'C','1');
	    		$this->pdf->SetX(175);
	    		$this->pdf->SetX(195);
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'3','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'LEX','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->SOCIALES4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->SOCIALES1+$notabi->SOCIALES2+$notabi->SOCIALES3+$notabi->SOCIALES4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'4','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'CS','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN FÍSICA Y DEPORTES'),'TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'5','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'EFD','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN MUSICAL'),'TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MUSICA4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->MUSICA1+$notabi->MUSICA2+$notabi->MUSICA3+$notabi->MUSICA4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'6','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'EM','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,utf8_decode('ARTES PLÁSTICAS Y VISUALES'),'TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'7','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'APV','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,utf8_decode('MATEMÁTICAS'),'TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'8','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'MAT','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,utf8_decode('TÉCNICA TECNOLÓGICA'),'TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->INFORMATICA4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->INFORMATICA1+$notabi->INFORMATICA2+$notabi->INFORMATICA3+$notabi->INFORMATICA4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'9','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'TTG','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,utf8_decode('CIENCIAS NATURALES'),'TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->CIENCIAS4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->CIENCIAS1+$notabi->CIENCIAS2+$notabi->CIENCIAS3+$notabi->CIENCIAS4)/4,0),'TBLR',0,'C','1');
	    		
	    		$this->pdf->Ln(7);
	    		$this->pdf->Cell(8,7,'10','TBLR',0,'L','1');
	    		$this->pdf->Cell(12,7,'VER','TBLR',0,'C','1');
	    		$this->pdf->Cell(60,7,'VALORES, ESPIRITUALIDAD Y RELIGIONES','TBL',0,'L','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
	    		$this->pdf->Cell(18,7,round(($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/4,0),'TBLR',0,'C','1');

			    $this->pdf->Ln(40);

			}
			$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
			ob_end_flush();
			
		}
		
		if(($nivel=='SECUNDARIA MAÑANA')OR($nivel=='SECUNDARIA TARDE'))
		{
			$idsql="secundaria";

			$curso=$this->estud->get_print_curso_pdf($id);

			$notlimit=101;
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			
			if(($curso1=='PRIMERO A')OR($curso1=='PRIMERO B')OR($curso1=='SEGUNDO A')OR($curso1=='SEGUNDO B'))
			{		
				
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				ob_start();
				$this->pdf=new Pdf('Letter');
				foreach ($estudiante as $list) 
				{
				
					$idest=$list->idest;					
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);			

					$this->pdf->AddPage();
					$this->pdf->AliasNbPages();
					$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
					$this->pdf->SetFont('Arial','BU',15);
					$this->pdf->Cell(30);
		            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
		            $this->pdf->Ln('15');            
		            $this->pdf->Cell(30);            
					$this->pdf->setXY(15,45);
					$this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
		            $this->pdf->setXY(35,45);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
		            $this->pdf->setX(55);  
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(45,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		            $this->pdf->setX(75);  
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
		            $this->pdf->SetX(97);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
		            $this->pdf->Ln('6'); 

		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		    		$this->pdf->setX(35);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
		            $this->pdf->setX(75); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
		            $this->pdf->SetX(138);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
		            
		            $this->pdf->Line(10,60,205,60);		            
		            $this->pdf->Ln('12');
		            
		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AP. PATERNO: '),0,0,'L');
		    		$this->pdf->setX(55);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($list->appat),0,0,'L');
		    		$this->pdf->Ln('6');
		            $this->pdf->setX(15); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('AP. MATERNO: '),0,0,'L');		            
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($list->apmat),0,0,'L');	
		            $this->pdf->Ln('6');
		            $this->pdf->SetX(15);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(15,5,utf8_decode('NOMBRES:'),0,0,'L');
		            $this->pdf->SetX(55);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($list->nombres),0,0,'L');
		            $this->pdf->Ln('6');
		            
		    		$this->pdf->SetLeftMargin(15);
		    		$this->pdf->SetRightMargin(15);
		    		$this->pdf->SetFillColor(192,192,192);
		    		$this->pdf->SetFont('Arial', 'B', 8);
		    		$this->pdf->Ln(5);
		    		$this->pdf->Cell(8,21,'NUM','TBL',0,'L','1');
		    		$this->pdf->Cell(12,21,'SIGLA','TBL',0,'C','1');
		    		$this->pdf->Cell(60,21,utf8_decode('ÁREA'),'TBL',0,'C','1');
		    		$this->pdf->Cell(98,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(80,7,'BIMESTRE','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,'PRO ANUAL','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(10,7,'1ER','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'2DO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'3RO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'4TO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetFillColor(255,255,255);
		    		$this->pdf->SetFont('Arial', '', 8);
		    		$this->pdf->Cell(8,7,'1','TBL',0,'L','1');
		    		$this->pdf->Cell(12,14,'LCO','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE1+$notabi->QUECHUA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE2+$notabi->QUECHUA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE3+$notabi->QUECHUA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE4+$notabi->QUECHUA4)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,round((round((($notabi->LENGUAJE1+$notabi->QUECHUA1)/2),0)+round((($notabi->LENGUAJE2+$notabi->QUECHUA2)/2),0)+round((($notabi->LENGUAJE3+$notabi->QUECHUA3)/2),0)+round((($notabi->LENGUAJE4+$notabi->QUECHUA4)/2),0))/4,0),'TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'2','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,'QUECHUA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA4,'TBLR',0,'C','1');
		    		$this->pdf->SetX(175);		    	
		    		$this->pdf->SetX(195);

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'3','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'LEX','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUA EXTRANJERA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES14+$notabi->INGLES24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES14+$notabi->INGLES24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'4','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'5','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EFD','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN FÍSICA Y DEPORTES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'6','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EM','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN MUSICAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA14+$notabi->MUSICA24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA14+$notabi->MUSICA24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'7','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'APV','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('ARTES PLÁSTICAS Y VISUALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'8','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'MAT','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('MATEMÁTICAS'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'9','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,21,'TTG','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('TÉCNICA TECNOLÓGICA GENERAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,21,round((($notabi->INFORMATICA1+$notabi->INFORMATICA1+$notabi->INFORMATICA1)/3),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,21,round((($notabi->FISICA2+$notabi->QUIMICA2+$notabi->INFORMATICA2)/3),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,21,round((($notabi->FISICA3+$notabi->QUIMICA3+$notabi->INFORMATICA3)/3),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,21,round((($notabi->FISICA4+$notabi->QUIMICA4+$notabi->INFORMATICA4)/3),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    				    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'10','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,utf8_decode('FISICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->FISICA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->FISICA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);		    		
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');		    		
		    		$this->pdf->Ln(7);

		    		$this->pdf->Cell(8,7,'11','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,utf8_decode('QUIMICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);

		    		$this->pdf->Cell(8,7,'12','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CN','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('CIENCIAS NATURALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'13','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CFS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('COSMOVISIONES, FILOSOFIA Y PSICOLOGIA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'14','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'VER','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'VALORES, ESPIRITUALIDAD Y RELIGIONES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    				    		

				    $this->pdf->Ln(40);

				}
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
				
			}

			if(($curso1=='TERCERO A')OR($curso1=='TERCERO B'))
			{		
												
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				ob_start();
				$this->pdf=new Pdf('Letter');
				foreach ($estudiante as $list) 
				{				
					$idest=$list->idest;					
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);	
					
					$this->pdf->AddPage();
					$this->pdf->AliasNbPages();
					$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
					$this->pdf->SetFont('Arial','BU',15);
					$this->pdf->Cell(30);
		            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
		            $this->pdf->Ln('15');            
		            $this->pdf->Cell(30);            
					$this->pdf->setXY(15,45);
					$this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
		            $this->pdf->setXY(35,45);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
		            $this->pdf->setX(55);  
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(45,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		            $this->pdf->setX(75);  
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
		            $this->pdf->SetX(97);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
		            $this->pdf->Ln('6'); 

		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		    		$this->pdf->setX(35);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
		            $this->pdf->setX(75); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
		            $this->pdf->SetX(138);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
		            
		            $this->pdf->Line(10,60,205,60);		            
		            $this->pdf->Ln('12');
		            
		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AP. PATERNO: '),0,0,'L');
		    		$this->pdf->setX(55);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($list->appat),0,0,'L');
		    		$this->pdf->Ln('6');
		            $this->pdf->setX(15); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('AP. MATERNO: '),0,0,'L');		            
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($list->apmat),0,0,'L');	
		            $this->pdf->Ln('6');
		            $this->pdf->SetX(15);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(15,5,utf8_decode('NOMBRES:'),0,0,'L');
		            $this->pdf->SetX(55);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($list->nombres),0,0,'L');
		            $this->pdf->Ln('6');

		            
		    		$this->pdf->SetLeftMargin(15);
		    		$this->pdf->SetRightMargin(15);
		    		$this->pdf->SetFillColor(192,192,192);
		    		$this->pdf->SetFont('Arial', 'B', 8);
		    		$this->pdf->Ln(5);
		    		$this->pdf->Cell(8,21,'NUM','TBL',0,'L','1');
		    		$this->pdf->Cell(12,21,'AREA','TBL',0,'C','1');
		    		$this->pdf->Cell(60,21,'MATERIA','TBL',0,'C','1');
		    		$this->pdf->Cell(98,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(80,7,'BIMESTRE','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,'PRO ANUAL','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(10,7,'1ER','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'2DO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'3RO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'4TO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetFillColor(255,255,255);
		    		$this->pdf->SetFont('Arial', '', 8);
		    		$this->pdf->Cell(8,7,'1','TBL',0,'L','1');
		    		$this->pdf->Cell(12,14,'LCO','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE1+$notabi->QUECHUA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE2+$notabi->QUECHUA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->LENGUAJE3+$notabi->QUECHUA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,'','TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'2','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,'QUECHUA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->QUECHUA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'3','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'LEX','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUA EXTRANJERA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'4','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'5','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EFD','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN FÍSICA Y DEPORTES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'6','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EM','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN MUSICAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'7','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'APV','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('ARTES PLÁSTICAS Y VISUALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'8','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'MAT','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('MATEMÁTICAS'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'9','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'TTG','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('TÉCNICA TECNOLÓGICA GENERAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    				    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'10','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,14,'FQ','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('FISICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA1+$notabi->QUIMICA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA2+$notabi->QUIMICA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA3,'TBL|R',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA3+$notabi->QUIMICA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'11','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,utf8_decode('QUÍMICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');


		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'12','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CN','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('CIENCIAS NATURALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'13','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CFS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('COSMOVISIONES, FILOSOFIA Y PSICOLOGIA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'14','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'VER','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'VALORES, ESPIRITUALIDAD Y RELIGIONES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

				    $this->pdf->Ln(40);		

				}
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
				
			}
			if(($curso1=='CUARTO A')OR($curso1=='CUARTO B'))
			{		
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				ob_start();
				$this->pdf=new Pdf('Letter');
				foreach ($estudiante as $list) 
				{				
					$idest=$list->idest;					
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);
					
					
					$this->pdf->AddPage();
					$this->pdf->AliasNbPages();
					$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
					$this->pdf->SetFont('Arial','BU',15);
					$this->pdf->Cell(30);
		            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
		            $this->pdf->Ln('15');            
		            $this->pdf->Cell(30);            
					$this->pdf->setXY(15,45);
					$this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
		            $this->pdf->setXY(35,45);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
		            $this->pdf->setX(55);  
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(45,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		            $this->pdf->setX(75);  
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
		            $this->pdf->SetX(97);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
		            $this->pdf->Ln('6'); 

		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		    		$this->pdf->setX(35);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
		            $this->pdf->setX(75); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
		            $this->pdf->SetX(138);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
		            
		            $this->pdf->Line(10,60,205,60);		            
		            $this->pdf->Ln('12');
		            
		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AP. PATERNO: '),0,0,'L');
		    		$this->pdf->setX(55);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($list->appat),0,0,'L');
		    		$this->pdf->Ln('6');
		            $this->pdf->setX(15); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('AP. MATERNO: '),0,0,'L');		            
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($list->apmat),0,0,'L');	
		            $this->pdf->Ln('6');
		            $this->pdf->SetX(15);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(15,5,utf8_decode('NOMBRES:'),0,0,'L');
		            $this->pdf->SetX(55);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($list->nombres),0,0,'L');
		            $this->pdf->Ln('6');

		            
		    		$this->pdf->SetLeftMargin(15);
		    		$this->pdf->SetRightMargin(15);
		    		$this->pdf->SetFillColor(192,192,192);
		    		$this->pdf->SetFont('Arial', 'B', 8);
		    		$this->pdf->Ln(5);
		    		$this->pdf->Cell(8,21,'NUM','TBL',0,'L','1');
		    		$this->pdf->Cell(12,21,'SIGLA','TBL',0,'C','1');
		    		$this->pdf->Cell(60,21,utf8_decode('ÁREA'),'TBL',0,'C','1');
		    		$this->pdf->Cell(98,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(80,7,'BIMESTRE','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,'PRO ANUAL','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(10,7,'1ER','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'2DO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'3RO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'4TO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetFillColor(255,255,255);
		    		$this->pdf->SetFont('Arial', '', 8);
		    		$this->pdf->Cell(8,7,'1','TBL',0,'L','1');
		    		$this->pdf->Cell(12,7,'LCO','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,'','TBLR',0,'C','1');

		    		

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'2','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'LEX','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUA EXTRANJERA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES14+$notabi->INGLES24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES14+$notabi->INGLES24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'3','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'CIENCIAS SOCIALES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->SOCIALES4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'4','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EFD','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN FÍSICA Y DEPORTES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'5','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EM','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN MUSICAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31),'TBLR',0,'C','1');//$notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA14+$notabi->MUSICA24+$notabi->MUSICA34),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA14+$notabi->MUSICA24+$notabi->MUSICA34),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'6','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'APV','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('ARTES PLÁSTICAS Y VISUALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'7','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'MAT','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('MATEMÁTICAS'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'8','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'TTG','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('TÉCNICA TECNOLÓGICA GENERAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->INFORMATICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    				    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'9','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,14,'FQ','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('FISICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA1+$notabi->QUIMICA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA2+$notabi->QUIMICA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA3+$notabi->QUIMICA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA4+$notabi->QUIMICA4)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'10','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,utf8_decode('QUÍMICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);		    
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA4,'TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');


		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'11','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CN','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('CIENCIAS NATURALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->CIENCIAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'12','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CFS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('COSMOVISIONES, FILOSOFIA Y PSICOLOGIA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');

		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'13','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'VER','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'VALORES, ESPIRITUALIDAD Y RELIGIONES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,'','TBLR',0,'C','1');
		    				    	
				    $this->pdf->Ln(40);	
					
				}
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}
			if(($curso1=='QUINTO A')OR($curso1=='QUINTO B')OR($curso1=='SEXTO A')OR($curso1=='SEXTO B'))
			{		
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				ob_start();
				$this->pdf=new Pdf('Letter');
				foreach ($estudiante as $list) 
				{				
					$idest=$list->idest;					
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);
				
					$this->pdf->AddPage();
					$this->pdf->AliasNbPages();
					$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
					$this->pdf->SetFont('Arial','BU',15);
					$this->pdf->Cell(30);
		            $this->pdf->Cell(135,8,utf8_decode('RENDIMIENTO ACADÉMICO'),0,0,'C');
		            $this->pdf->Ln('15');            
		            $this->pdf->Cell(30);            
					$this->pdf->setXY(15,45);
					$this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
		            $this->pdf->setXY(35,45);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
		            $this->pdf->setX(55);  
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(45,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		            $this->pdf->setX(75);  
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
		            $this->pdf->SetX(97);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
		            $this->pdf->Ln('6'); 

		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AÑO ESCOL: '),0,0,'L');
		    		$this->pdf->setX(35);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
		            $this->pdf->setX(75); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
		            $this->pdf->SetX(115);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(65,5,utf8_decode('UNID EDU:'),0,0,'L');
		            $this->pdf->SetX(138);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
		            
		            $this->pdf->Line(10,60,205,60);		            
		            $this->pdf->Ln('12');
		            
		            $this->pdf->setX(15);
		            $this->pdf->SetFont('Arial','B',10);
		    		$this->pdf->Cell(30,5,utf8_decode('AP. PATERNO: '),0,0,'L');
		    		$this->pdf->setX(55);
		    		$this->pdf->SetFont('Arial','',10);
		    		$this->pdf->Cell(30,5,utf8_decode($list->appat),0,0,'L');
		    		$this->pdf->Ln('6');
		            $this->pdf->setX(15); 
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(60,5,utf8_decode('AP. MATERNO: '),0,0,'L');		            
		            $this->pdf->setX(55); 
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(60,5,utf8_decode($list->apmat),0,0,'L');	
		            $this->pdf->Ln('6');
		            $this->pdf->SetX(15);
		            $this->pdf->SetFont('Arial','B',10);
		            $this->pdf->Cell(15,5,utf8_decode('NOMBRES:'),0,0,'L');
		            $this->pdf->SetX(55);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->Cell(65,5,utf8_decode($list->nombres),0,0,'L');
		            $this->pdf->Ln('6');

		            
		    		$this->pdf->SetLeftMargin(15);
		    		$this->pdf->SetRightMargin(15);
		    		$this->pdf->SetFillColor(192,192,192);
		    		$this->pdf->SetFont('Arial', 'B', 8);
		    		$this->pdf->Ln(5);
		    		$this->pdf->Cell(8,21,'NUM','TBL',0,'L','1');
		    		$this->pdf->Cell(12,21,'SIGLA','TBL',0,'C','1');
		    		$this->pdf->Cell(60,21,'AREA','TBL',0,'C','1');
		    		$this->pdf->Cell(98,7,'VALORACION CUANTITATIVA','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(80,7,'BIMESTRE','TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,'PRO ANUAL','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetX(95);
		    		$this->pdf->Cell(10,7,'1ER','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'2DO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'3RO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'4TO','TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,'P.A.','TBLR',0,'C','1');
		    		$this->pdf->Ln(7);
		    		$this->pdf->SetFillColor(255,255,255);
		    		$this->pdf->SetFont('Arial', '', 8);
		    		$this->pdf->Cell(8,7,'1','TBL',0,'L','1');
		    		$this->pdf->Cell(12,7,'LCO','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUAJE','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->LENGUAJE4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round(($notabi->LENGUAJE1+$notabi->LENGUAJE2+$notabi->LENGUAJE3+$notabi->LENGUAJE4)/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'2','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'LEX','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'LENGUA EXTRANJERA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES11+$notabi->INGLES21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES12+$notabi->INGLES22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES13+$notabi->INGLES23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES14+$notabi->INGLES24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INGLES14+$notabi->INGLES24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round((($notabi->INGLES11+$notabi->INGLES21)+($notabi->INGLES12+$notabi->INGLES22)+($notabi->INGLES13+$notabi->INGLES23)+($notabi->INGLES14+$notabi->INGLES24))/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'3','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,14,'HC','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'HISTORIA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->HISTORIA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->HISTORIA1+$notabi->CIVICA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->HISTORIA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->HISTORIA2+$notabi->CIVICA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->HISTORIA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->HISTORIA3+$notabi->CIVICA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->HISTORIA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->HISTORIA4+$notabi->CIVICA4)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,round((round((($notabi->HISTORIA1+$notabi->CIVICA1)/2),0)+round((($notabi->HISTORIA2+$notabi->CIVICA2)/2),0)+round((($notabi->HISTORIA3+$notabi->CIVICA3)/2),0)+round((($notabi->HISTORIA4+$notabi->CIVICA4)/2),0))/4,0),'TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'4','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,'CIVICA','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->CIVICA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->CIVICA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->CIVICA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,$notabi->CIVICA4,'TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->SetX(195);
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'5','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EFD','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN FÍSICA Y DEPORTES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->EDUFISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round(($notabi->EDUFISICA1+$notabi->EDUFISICA2+$notabi->EDUFISICA3+$notabi->EDUFISICA4)/4,0),'TBLR',0,'C','1');
		    		
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'6','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'EM','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('EDUCACIÓN MUSICAL'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31),'TBLR',0,'C','1');//$notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31  
		    		$this->pdf->Cell(10,7,($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA14+$notabi->MUSICA24+$notabi->MUSICA34),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->MUSICA14+$notabi->MUSICA24+$notabi->MUSICA34),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round((($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31)+($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32)+($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33)+($notabi->MUSICA14+$notabi->MUSICA24+$notabi->MUSICA34))/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'7','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'APV','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('ARTES PLÁSTICAS Y VISUALES'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->ARTPLAST4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round(($notabi->ARTPLAST1+$notabi->ARTPLAST2+$notabi->ARTPLAST3+$notabi->ARTPLAST4)/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'8','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'MAT','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('MATEMÁTICAS'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->MATEMATICAS4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round(($notabi->MATEMATICAS1+$notabi->MATEMATICAS2+$notabi->MATEMATICAS3+$notabi->MATEMATICAS4)/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'9','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'TTE','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('TÉCNICA TECNOLÓGICA ESPECIALIZADA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA11+$notabi->INFORMATICA21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA11+$notabi->INFORMATICA21),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA12+$notabi->INFORMATICA22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA12+$notabi->INFORMATICA22),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA13+$notabi->INFORMATICA23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA13+$notabi->INFORMATICA23),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA14+$notabi->INFORMATICA24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,($notabi->INFORMATICA14+$notabi->INFORMATICA24),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round((($notabi->INFORMATICA11+$notabi->INFORMATICA21)+($notabi->INFORMATICA12+$notabi->INFORMATICA22)+($notabi->INFORMATICA13+$notabi->INFORMATICA23)+($notabi->INFORMATICA14+$notabi->INFORMATICA24))/4,0),'TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'10','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,14,'BG','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('BIOLOGIA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->BIOLOGIA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->BIOLOGIA1+$notabi->GEOGRAFIA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->BIOLOGIA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->BIOLOGIA2+$notabi->GEOGRAFIA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->BIOLOGIA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->BIOLOGIA3+$notabi->GEOGRAFIA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->BIOLOGIA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->BIOLOGIA4+$notabi->GEOGRAFIA4)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,round((round((($notabi->BIOLOGIA1+$notabi->GEOGRAFIA1)/2),0)+round((($notabi->BIOLOGIA2+$notabi->GEOGRAFIA2)/2),0)+round((($notabi->BIOLOGIA3+$notabi->GEOGRAFIA3)/2),0)+round((($notabi->BIOLOGIA4+$notabi->GEOGRAFIA4)/2),0))/4,0),'TBLR',0,'C','1');

		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'11','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,utf8_decode('GEOGRAFIA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->GEOGRAFIA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->GEOGRAFIA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->GEOGRAFIA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,$notabi->GEOGRAFIA4,'TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->SetX(195);
		    				    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'12','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,14,'FQ','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('FISICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA1+$notabi->QUIMICA1)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA2+$notabi->QUIMICA2)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA3+$notabi->QUIMICA3)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->FISICA4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,14,round((($notabi->FISICA4+$notabi->QUIMICA4)/2),0),'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,14,round((round((($notabi->FISICA1+$notabi->QUIMICA1)/2),0)+round((($notabi->FISICA2+$notabi->QUIMICA2)/2),0)+round((($notabi->FISICA3+$notabi->QUIMICA3)/2),0)+round((($notabi->FISICA4+$notabi->QUIMICA4)/2),0))/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'13','TBLR',0,'L','1');
		    		$this->pdf->SetX(35);
		    		$this->pdf->Cell(60,7,utf8_decode('QUÍMICA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA1,'TBLR',0,'C','1');
		    		$this->pdf->SetX(115);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA2,'TBLR',0,'C','1');
		    		$this->pdf->SetX(135);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA3,'TBLR',0,'C','1');
		    		$this->pdf->SetX(155);
		    		$this->pdf->Cell(10,7,$notabi->QUIMICA4,'TBLR',0,'C','1');
		    		$this->pdf->SetX(175);
		    		$this->pdf->SetX(195);
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'14','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'CFS','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,utf8_decode('COSMOVISIONES, FILOSOFIA Y PSICOLOGIA'),'TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->COSMO4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round(($notabi->COSMO1+$notabi->COSMO2+$notabi->COSMO3+$notabi->COSMO4)/4,0),'TBLR',0,'C','1');
		    		
		    		$this->pdf->Ln(7);
		    		$this->pdf->Cell(8,7,'15','TBLR',0,'L','1');
		    		$this->pdf->Cell(12,7,'VER','TBLR',0,'C','1');
		    		$this->pdf->Cell(60,7,'VALORES, ESPIRITUALIDAD Y RELIGIONES','TBL',0,'L','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION1,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION2,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION3,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(10,7,$notabi->RELIGION4,'TBLR',0,'C','1');
		    		$this->pdf->Cell(18,7,round(($notabi->RELIGION1+$notabi->RELIGION2+$notabi->RELIGION3+$notabi->RELIGION4)/4,0),'TBLR',0,'C','1');		    

				    $this->pdf->Ln(40);	
					
				}
				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}
		}
	}

/****************** BONO JUANCITO PINTO ******************************/
	public function printjuancito($id)
	{
			
			$curso=$this->estud->get_print_curso_pdf($id);
			$notlimit=101;
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			//$bi2='2';
			
			$this->load->library('pdf');
			$idsql="primaria";
		
		if(($nivel=='PRIMARIA MAÑANA')OR($nivel=='PRIMARIA TARDE'))
		{			
			$i=0;
			$estudiante=$this->estud->ejec_sql_estudiante($idcur);
			$sie=$this->estud->get_print_sie($curso->colegio);
			$siecol=$sie->SIE;

			ob_start();
			$this->pdf=new Pdf('Letter');

			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('REGISTRO DE CALIFICACIONES BIMESTRALES'),0,0,'C');           
            $this->pdf->Ln('15');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('UNID EDU: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->colegio),0,0,'L');
            $this->pdf->setX(115);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('SIE: '),0,0,'L');
            $this->pdf->setX(125);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,$siecol,0,0,'L');
           
            $this->pdf->Ln('6'); 

            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('NIVEL: '),0,0,'L');
    		$this->pdf->setX(35);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode("PRIMARIA COMUNITARIA VOCACIONAL"),0,0,'L');
            $this->pdf->setX(115); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('GRADO: '),0,0,'L');
            $this->pdf->setX(135); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($curso->curso)." ".utf8_decode($curso->nivel),0,0,'L');	
            
            $this->pdf->Line(10,60,205,60);		            
            $this->pdf->Ln('12');

            $this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
    		$this->pdf->Ln(7);
    			

			foreach ($estudiante as $list) 
			{	
				$i++;
				if($i==28)
				{
					$this->pdf->SetFont('Arial', 'B', 8);
					$this->pdf->AddPage();
					$this->pdf->SetLeftMargin(15);
    				$this->pdf->SetRightMargin(15);
					$this->pdf->SetFillColor(192,192,192);
					$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
		    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
		    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
		    		$this->pdf->Ln(7);

				}
				$this->pdf->SetFont('Arial', '', 8);
				$this->pdf->SetFillColor(255,255,255);	

				$idest=$list->idest;
				$estobserv=$this->estud->get_print_observ($idest);
				//$observ=$estobserv->observ;				
				$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);	

				$prom1=round((round((($notabi->LENGUAJE1+$notabi->INGLES1)/2),0)+$notabi->SOCIALES1+$notabi->EDUFISICA1+$notabi->MUSICA1+$notabi->ARTPLAST1+$notabi->MATEMATICAS1+$notabi->INFORMATICA1+$notabi->CIENCIAS1+$notabi->RELIGION1)/9,0);
				$prom2=round((round((($notabi->LENGUAJE2+$notabi->INGLES2)/2),0)+$notabi->SOCIALES2+$notabi->EDUFISICA2+$notabi->MUSICA2+$notabi->ARTPLAST2+$notabi->MATEMATICAS2+$notabi->INFORMATICA2+$notabi->CIENCIAS2+$notabi->RELIGION2)/9,0);
				$prom3=round((round((($notabi->LENGUAJE3+$notabi->INGLES3)/2),0)+$notabi->SOCIALES3+$notabi->EDUFISICA3+$notabi->MUSICA3+$notabi->ARTPLAST3+$notabi->MATEMATICAS3+$notabi->INFORMATICA3+$notabi->CIENCIAS3+$notabi->RELIGION3)/9,0);

				$nf=round((($prom1+$prom2+$prom3)/3),3);

				$row[]=array(
						"nota"=>$nf,
						"nomb"=>strtoupper(utf8_decode($list->appat." ".$list->apmat." ".$list->nombres))
				);

	    		$this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
	    		$this->pdf->Cell(70,7,strtoupper(utf8_decode($list->appat))." ".strtoupper(utf8_decode($list->apmat))." ".strtoupper(utf8_decode($list->nombres)),'TBL',0,'L','1');
	    		$this->pdf->Cell(20,7,$prom1,'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,$prom2,'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,$prom3,'TBL',0,'C','1');
	    		$this->pdf->Cell(30,7,"",'TBLR',0,'C','1');
	    		$this->pdf->Ln(7);
			    
			}
			$this->pdf->Ln(40);

    		//$this->pdf->setXY(100,150);
			$this->pdf->SetFont('Arial','BU',11);
			$this->pdf->Cell(70,5,'                                                     ',0,0,'R');
			$this->pdf->SetFont('Arial','B',11);	
			$this->pdf->Cell(10,5,'                                                     ',0,0,'R');
			$this->pdf->SetFont('Arial','BU',11);
			$this->pdf->Cell(70,5,'                                                     ',0,0,'R');			
			$this->pdf->Ln(5);
			$this->pdf->SetFont('Arial','B',10);				
			$this->pdf->Cell(50,5,utf8_decode("DIRECTOR(A)"),0,0,'R');
			$this->pdf->SetFont('Arial','B',10);	
			$this->pdf->Cell(20,5,'                                                     ',0,0,'R');
			$this->pdf->Cell(60,5,utf8_decode("MAESTRO(A)"),0,0,'R');
			$this->pdf->Ln(5);
            $this->pdf->Cell(45,5,utf8_decode('FIRMA '),0,0,'R');
            $this->pdf->Cell(30,5,'                                                     ',0,0,'R');
            $this->pdf->Cell(50,5,utf8_decode('FIRMA '),0,0,'R');
            $this->pdf->SetFont('Arial','',8);


            $j=0;
			$this->pdf->AddPage();
			$this->pdf->SetAutoPageBreak(false);			
			$this->pdf->SetFont('Arial','BU',12);			
            $this->pdf->Cell(200,8,utf8_decode('CUADRO DE HONOR DE CURSO'),0,0,'C');            
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Ln(7);
            $this->pdf->Cell(200,5,utf8_decode($curso->colegio),0,0,'C');            
            $this->pdf->Ln(5);
            $this->pdf->Cell(200,5,utf8_decode($curso->curso.", ".$curso->nivel),0,0,'C');
            $this->pdf->Ln(5);
            $this->pdf->Cell(200,5,utf8_decode("PROMEDIO DE LOS BIMESTRES"),0,0,'C');
			$this->pdf->Ln(14);
			$this->pdf->SetFont('Arial', 'B', 8);
			$this->pdf->Cell(12,8,"NUM",'TBLR',0,'L','1');
			$this->pdf->Cell(80,8,'APELLIDOS Y NOMBRES','TBLR',0,'C','1');
			$this->pdf->Cell(35,8,'PROMEDIO BIMESTRE','TBLR',0,'C','1');
			$this->pdf->SetFont('Arial', '', 8);
			$this->pdf->SetFillColor(255,255,255); 
			$this->pdf->Ln(7);	
			
			arsort($row);

			foreach ($row as $honor)
			{
				$j++;
				if($j<=5)
				{
					$this->pdf->Cell(12,8,$j,'TBLR',0,'L','1');
					$this->pdf->Cell(80,8,$honor['nomb'],'TBLR',0,'L','1');
					$this->pdf->Cell(35,8,$honor['nota'],'TBLR',0,'R','1');
					$this->pdf->Ln(7);		
				}
				
			}

            $this->pdf->Ln(40);
			$this->pdf->Output("cuadro -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
			ob_end_flush();
			
		}
		
		if(($nivel=='SECUNDARIA MAÑANA')OR($nivel=='SECUNDARIA TARDE'))
		{
			
			$idsql="secundaria";

			$curso=$this->estud->get_print_curso_pdf($id);
			$sie=$this->estud->get_print_sie($curso->colegio);
			$siecol=$sie->SIE;
			$notlimit=101;
			$idcur=$id;		
			$gestion=$curso->gestion;
			$nivel=$curso->nivel;
			$curso1=$curso->curso;
			
			if(($curso1=='PRIMERO A')OR($curso1=='PRIMERO B')OR($curso1=='SEGUNDO A')OR($curso1=='SEGUNDO B'))
			{		
				$i=0;
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				ob_start();
				$this->pdf=new Pdf('Letter');

				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('REGISTRO DE CALIFICACIONES BIMESTRALES'),0,0,'C');
	            $this->pdf->Ln('15');            
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('UNID EDU: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->colegio),0,0,'L');
	            $this->pdf->setX(115);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('SIE: '),0,0,'L');
	            $this->pdf->setX(125);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,$siecol,0,0,'L');
	           
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('NIVEL: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode("SECUNDARIA COMUNITARIA PRODUCTIVA"),0,0,'L');
	            $this->pdf->setX(115); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('GRADO: '),0,0,'L');
	            $this->pdf->setX(135); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->curso)." ".utf8_decode($curso->nivel),0,0,'L');	
	           
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');

	            $this->pdf->SetLeftMargin(15);
	    		$this->pdf->SetRightMargin(15);
	    		$this->pdf->SetFillColor(192,192,192);
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
	    		$this->pdf->Ln(7);

				foreach ($estudiante as $list) 
				{
					$i++;
					if($i==28)
					{
						$this->pdf->SetFont('Arial', 'B', 8);
						$this->pdf->AddPage();
						$this->pdf->SetLeftMargin(15);
	    				$this->pdf->SetRightMargin(15);
						$this->pdf->SetFillColor(192,192,192);
						$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
			    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
			    		$this->pdf->Ln(7);

					}
					$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);	

					$idest=$list->idest;					
					$estobserv=$this->estud->get_print_observ($idest);
					$observ=$estobserv->observ;
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);			

					$prom1=round(($notabi->LENGUAJE1+$notabi->QUECHUA1+($notabi->INGLES11+$notabi->INGLES21)+$notabi->SOCIALES1+$notabi->EDUFISICA1+$notabi->MUSICA1+$notabi->ARTPLAST1+$notabi->MATEMATICAS1+$notabi->INFORMATICA1+$notabi->INFORMATICA1+$notabi->INFORMATICA1+$notabi->CIENCIAS1+$notabi->COSMO1+$notabi->RELIGION1)/14,0);

					$prom2=round(($notabi->LENGUAJE2+$notabi->QUECHUA2+($notabi->INGLES12+$notabi->INGLES22)+$notabi->SOCIALES2+$notabi->EDUFISICA2+$notabi->MUSICA2+$notabi->ARTPLAST2+$notabi->MATEMATICAS2+$notabi->FISICA2+$notabi->QUIMICA2+$notabi->INFORMATICA2+$notabi->CIENCIAS2+$notabi->COSMO2+$notabi->RELIGION2)/14,0);

					$prom3=round(($notabi->LENGUAJE3+$notabi->QUECHUA3+($notabi->INGLES13+$notabi->INGLES23)+$notabi->SOCIALES3+$notabi->EDUFISICA3+$notabi->MUSICA3+$notabi->ARTPLAST3+$notabi->MATEMATICAS3+$notabi->FISICA3+$notabi->QUIMICA3+$notabi->INFORMATICA3+$notabi->CIENCIAS3+$notabi->COSMO3+$notabi->RELIGION3)/14,0);

							            
			        $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
		    		$this->pdf->Cell(70,7,strtoupper(utf8_decode($list->appat))." ".strtoupper(utf8_decode($list->apmat))." ".strtoupper(utf8_decode($list->nombres)),'TBL',0,'L','1');
		    		$this->pdf->Cell(20,7,$prom1,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom2,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom3,'TBL',0,'C','1');
		    		$this->pdf->Cell(30,7,$observ,'TBLR',0,'C','1');
		    		$this->pdf->Ln(7);		            

				}

				$this->pdf->Ln(40);
	    		//$this->pdf->setXY(100,150);
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','B',11);	
				$this->pdf->Cell(10,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');			
				$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial','B',10);				
				$this->pdf->Cell(50,5,utf8_decode("DIRECTOR(A)"),0,0,'R');
				$this->pdf->SetFont('Arial','B',10);	
				$this->pdf->Cell(20,5,'                                                     ',0,0,'R');
				$this->pdf->Cell(60,5,utf8_decode("MAESTRO(A)"),0,0,'R');
				$this->pdf->Ln(5);
	            $this->pdf->Cell(45,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->Cell(30,5,'                                                     ',0,0,'R');
	            $this->pdf->Cell(50,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->SetFont('Arial','',8);

				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
				
			}
			
			if(($curso1=='TERCERO A')OR($curso1=='TERCERO B'))
			{		
				$i=0;							
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				$sie=$this->estud->get_print_sie($curso->colegio);
				$siecol=$sie->SIE;

				ob_start();
				$this->pdf=new Pdf('Letter');

				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);
	            $this->pdf->Cell(135,8,utf8_decode('REGISTRO DE CALIFICACIONES BIMESTRALES'),0,0,'C');
	            $this->pdf->Ln('15');                        
	            $this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('UNID EDU: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->colegio),0,0,'L');
	            $this->pdf->setX(115);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('SIE: '),0,0,'L');
	            $this->pdf->setX(125);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,$siecol,0,0,'L');
	           
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('NIVEL: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode("SECUNDARIA COMUNITARIA PRODUCTIVA"),0,0,'L');
	            $this->pdf->setX(115); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('GRADO: '),0,0,'L');
	            $this->pdf->setX(135); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->curso)." ".utf8_decode($curso->nivel),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');

				$this->pdf->SetLeftMargin(15);
	    		$this->pdf->SetRightMargin(15);
	    		$this->pdf->SetFillColor(192,192,192);
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
	    		$this->pdf->Ln(7);

				foreach ($estudiante as $list) 
				{				
					$i++;
					if($i==28)
					{
						$this->pdf->SetFont('Arial', 'B', 8);
						$this->pdf->AddPage();
						$this->pdf->SetLeftMargin(15);
	    				$this->pdf->SetRightMargin(15);
						$this->pdf->SetFillColor(192,192,192);
						$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
			    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
			    		$this->pdf->Ln(7);

					}
					$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);	

					$idest=$list->idest;					
					$estobserv=$this->estud->get_print_observ($idest);
					$observ=$estobserv->observ;	
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);	
												            
					$prom1=round(($notabi->LENGUAJE1+$notabi->QUECHUA1+($notabi->INGLES11+$notabi->INGLES21)+$notabi->SOCIALES1+$notabi->EDUFISICA1+($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31)+$notabi->ARTPLAST1+$notabi->MATEMATICAS1+$notabi->INFORMATICA1+$notabi->FISICA1+$notabi->QUIMICA1+$notabi->CIENCIAS1+$notabi->COSMO1+$notabi->RELIGION1)/14,0);

					$prom2=round(($notabi->LENGUAJE2+$notabi->QUECHUA2+($notabi->INGLES12+$notabi->INGLES22)+$notabi->SOCIALES2+$notabi->EDUFISICA2+($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32)+$notabi->ARTPLAST2+$notabi->MATEMATICAS2+$notabi->INFORMATICA2+$notabi->FISICA2+$notabi->QUIMICA2+$notabi->CIENCIAS2+$notabi->COSMO2+$notabi->RELIGION2)/14,0);

					$prom3=round(($notabi->LENGUAJE3+$notabi->QUECHUA3+($notabi->INGLES13+$notabi->INGLES23)+$notabi->SOCIALES3+$notabi->EDUFISICA3+($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33)+$notabi->ARTPLAST3+$notabi->MATEMATICAS3+$notabi->INFORMATICA3+$notabi->FISICA3+$notabi->QUIMICA3+$notabi->CIENCIAS3+$notabi->COSMO3+$notabi->RELIGION3)/14,0);

		            $this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
		    		$this->pdf->Cell(70,7,strtoupper(utf8_decode($list->appat))." ".strtoupper(utf8_decode($list->apmat))." ".strtoupper(utf8_decode($list->nombres)),'TBL',0,'L','1');
		    		$this->pdf->Cell(20,7,$prom1,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom2,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom3,'TBL',0,'C','1');
		    		$this->pdf->Cell(30,7,$observ,'TBLR',0,'C','1');
		    		$this->pdf->Ln(7);	

				}
				$this->pdf->Ln(40);
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','B',11);	
				$this->pdf->Cell(10,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');			
				$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial','B',10);				
				$this->pdf->Cell(50,5,utf8_decode("DIRECTOR(A)"),0,0,'R');
				$this->pdf->SetFont('Arial','B',10);	
				$this->pdf->Cell(20,5,'                                                     ',0,0,'R');
				$this->pdf->Cell(60,5,utf8_decode("MAESTRO(A)"),0,0,'R');
				$this->pdf->Ln(5);
	            $this->pdf->Cell(45,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->Cell(30,5,'                                                     ',0,0,'R');
	            $this->pdf->Cell(50,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->SetFont('Arial','',8);

				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();
				
			}

			
			if(($curso1=='CUARTO A')OR($curso1=='CUARTO B'))
			{		
				$i=0;
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				$sie=$this->estud->get_print_sie($curso->colegio);
				$siecol=$sie->SIE;

				ob_start();
				$this->pdf=new Pdf('Letter');

				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);         
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('UNID EDU: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->colegio),0,0,'L');
	            $this->pdf->setX(115);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('SIE: '),0,0,'L');
	            $this->pdf->setX(125);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,$siecol,0,0,'L');
	           
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('NIVEL: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode("SECUNDARIA COMUNITARIA PRODUCTIVA"),0,0,'L');
	            $this->pdf->setX(115); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('GRADO: '),0,0,'L');
	            $this->pdf->setX(135); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->curso)." ".utf8_decode($curso->nivel),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');
			
				$this->pdf->SetLeftMargin(15);
	    		$this->pdf->SetRightMargin(15);
	    		$this->pdf->SetFillColor(192,192,192);
	    		$this->pdf->SetFont('Arial', 'B', 8);
	    		$this->pdf->Ln(5);
	    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
	    		$this->pdf->Ln(7);

				foreach ($estudiante as $list) 
				{				
					$i++;
					if($i==28)
					{

						$this->pdf->SetFont('Arial', 'B', 8);
						$this->pdf->AddPage();
						$this->pdf->SetLeftMargin(15);
	    				$this->pdf->SetRightMargin(15);
						$this->pdf->SetFillColor(192,192,192);
						$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
			    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    	}
			    	$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);	

					$idest=$list->idest;
					$estobserv=$this->estud->get_print_observ($idest);
					$observ=$estobserv->observ;					
					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);

					$prom1=round(($notabi->LENGUAJE1+($notabi->INGLES11+$notabi->INGLES21)+$notabi->SOCIALES1+$notabi->EDUFISICA1+($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31)+$notabi->ARTPLAST1+$notabi->MATEMATICAS1+$notabi->INFORMATICA1+$notabi->FISICA1+$notabi->QUIMICA1+$notabi->CIENCIAS1+$notabi->COSMO1+$notabi->RELIGION1)/13,0);

					$prom2=round(($notabi->LENGUAJE2+($notabi->INGLES12+$notabi->INGLES22)+$notabi->SOCIALES2+$notabi->EDUFISICA2+($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32)+$notabi->ARTPLAST2+$notabi->MATEMATICAS2+$notabi->INFORMATICA2+$notabi->FISICA2+$notabi->QUIMICA2+$notabi->CIENCIAS2+$notabi->COSMO2+$notabi->RELIGION2)/13,0);

					$prom3=round(($notabi->LENGUAJE3+($notabi->INGLES13+$notabi->INGLES23)+$notabi->SOCIALES3+$notabi->EDUFISICA3+($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33)+$notabi->ARTPLAST3+$notabi->MATEMATICAS3+$notabi->INFORMATICA3+$notabi->FISICA3+$notabi->QUIMICA3+$notabi->CIENCIAS3+$notabi->COSMO3+$notabi->RELIGION3)/13,0);


					$this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
		    		$this->pdf->Cell(70,7,strtoupper(utf8_decode($list->appat))." ".strtoupper(utf8_decode($list->apmat))." ".strtoupper(utf8_decode($list->nombres)),'TBL',0,'L','1');
		    		$this->pdf->Cell(20,7,$prom1,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom2,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom3,'TBL',0,'C','1');
		    		$this->pdf->Cell(30,7,$observ,'TBLR',0,'C','1');
		    		$this->pdf->Ln(7);	
				}
				$this->pdf->Ln(40);
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','B',11);	
				$this->pdf->Cell(10,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');			
				$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial','B',10);				
				$this->pdf->Cell(50,5,utf8_decode("DIRECTOR(A)"),0,0,'R');
				$this->pdf->SetFont('Arial','B',10);	
				$this->pdf->Cell(20,5,'                                                     ',0,0,'R');
				$this->pdf->Cell(60,5,utf8_decode("MAESTRO(A)"),0,0,'R');
				$this->pdf->Ln(5);
	            $this->pdf->Cell(45,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->Cell(30,5,'                                                     ',0,0,'R');
	            $this->pdf->Cell(50,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->SetFont('Arial','',8);

				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}

			
			if(($curso1=='QUINTO A')OR($curso1=='QUINTO B')OR($curso1=='SEXTO A')OR($curso1=='SEXTO B'))
			{		
				$i=0;
				$estudiante=$this->estud->ejec_sql_estudiante($idcur);
				$sie=$this->estud->get_print_sie($curso->colegio);
				$siecol=$sie->SIE;

				ob_start();
				$this->pdf=new Pdf('Letter');

				$this->pdf->AddPage();
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle("Boletin de Notas - Don Bosco");
				$this->pdf->SetFont('Arial','BU',15);
				$this->pdf->Cell(30);            
				$this->pdf->setXY(15,45);
				$this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(35,5,utf8_decode('UNID EDU: '),0,0,'L');
	            $this->pdf->setXY(35,45);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,utf8_decode($curso->colegio),0,0,'L');
	            $this->pdf->setX(115);  
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(45,5,utf8_decode('SIE: '),0,0,'L');
	            $this->pdf->setX(125);  
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(15,5,$siecol,0,0,'L');
	           
	            $this->pdf->Ln('6'); 

	            $this->pdf->setX(15);
	            $this->pdf->SetFont('Arial','B',10);
	    		$this->pdf->Cell(30,5,utf8_decode('NIVEL: '),0,0,'L');
	    		$this->pdf->setX(35);
	    		$this->pdf->SetFont('Arial','',10);
	    		$this->pdf->Cell(30,5,utf8_decode("SECUNDARIA COMUNITARIA PRODUCTIVA"),0,0,'L');
	            $this->pdf->setX(115); 
	            $this->pdf->SetFont('Arial','B',10);
	            $this->pdf->Cell(60,5,utf8_decode('GRADO: '),0,0,'L');
	            $this->pdf->setX(135); 
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(60,5,utf8_decode($curso->curso)." ".utf8_decode($curso->nivel),0,0,'L');
	            
	            $this->pdf->Line(10,60,205,60);		            
	            $this->pdf->Ln('12');

	            $this->pdf->SetFont('Arial', 'B', 8);			
				$this->pdf->SetLeftMargin(15);
				$this->pdf->SetRightMargin(15);
				$this->pdf->SetFillColor(192,192,192);
				$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
	    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
	    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
	    		$this->pdf->Ln(7);

				foreach ($estudiante as $list) 
				{				
					$i++;
					if($i==28)
					{

						$this->pdf->SetFont('Arial', 'B', 8);
						$this->pdf->AddPage();
						$this->pdf->SetLeftMargin(15);
	    				$this->pdf->SetRightMargin(15);
						$this->pdf->SetFillColor(192,192,192);
						$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
			    		$this->pdf->Cell(70,7,'APELLIDOS Y NOMBRES','TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('1er BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('2do BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(20,7,utf8_decode('3ro BIM'),'TBL',0,'C','1');
			    		$this->pdf->Cell(30,7,utf8_decode('OBSERVACIONES'),'TBLR',0,'C','1');
			    		$this->pdf->Ln(7);
			    	}
			    	$this->pdf->SetFont('Arial', '', 8);
					$this->pdf->SetFillColor(255,255,255);	

					$idest=$list->idest;
					$estobserv=$this->estud->get_print_observ($idest);

					$observ=$estobserv->observ;

					$notabi=$this->estud->ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso1);

					$prom1=round(($notabi->LENGUAJE1+($notabi->INGLES11+$notabi->INGLES21)+(($notabi->HISTORIA1+$notabi->CIVICA1)/2)+$notabi->EDUFISICA1+($notabi->MUSICA11+$notabi->MUSICA21+$notabi->MUSICA31)+$notabi->ARTPLAST1+$notabi->MATEMATICAS1+($notabi->INFORMATICA11+$notabi->INFORMATICA21)+(($notabi->FISICA1+$notabi->QUIMICA1)/2)+(($notabi->BIOLOGIA1+$notabi->GEOGRAFIA1)/2)+$notabi->COSMO1+$notabi->RELIGION1)/12,0);

					$prom2=round(($notabi->LENGUAJE2+($notabi->INGLES12+$notabi->INGLES22)+(($notabi->HISTORIA2+$notabi->CIVICA2)/2)+$notabi->EDUFISICA2+($notabi->MUSICA12+$notabi->MUSICA22+$notabi->MUSICA32)+$notabi->ARTPLAST2+$notabi->MATEMATICAS2+($notabi->INFORMATICA12+$notabi->INFORMATICA22)+(($notabi->FISICA2+$notabi->QUIMICA2)/2)+(($notabi->BIOLOGIA2+$notabi->GEOGRAFIA2)/2)+$notabi->COSMO2+$notabi->RELIGION2)/12,0);

					$prom3=round(($notabi->LENGUAJE3+($notabi->INGLES13+$notabi->INGLES23)+(($notabi->HISTORIA3+$notabi->CIVICA3)/2)+$notabi->EDUFISICA3+($notabi->MUSICA13+$notabi->MUSICA23+$notabi->MUSICA33)+$notabi->ARTPLAST3+$notabi->MATEMATICAS3+($notabi->INFORMATICA13+$notabi->INFORMATICA23)+(($notabi->FISICA3+$notabi->QUIMICA3)/2)+(($notabi->BIOLOGIA3+$notabi->GEOGRAFIA3)/2)+$notabi->COSMO3+$notabi->RELIGION3)/12,0);

					
					$this->pdf->Cell(10,7,$i,'TBL',0,'L','1');
		    		$this->pdf->Cell(70,7,strtoupper(utf8_decode($list->appat))." ".strtoupper(utf8_decode($list->apmat))." ".strtoupper(utf8_decode($list->nombres)),'TBL',0,'L','1');
		    		$this->pdf->Cell(20,7,$prom1,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom2,'TBL',0,'C','1');
		    		$this->pdf->Cell(20,7,$prom3,'TBL',0,'C','1');
		    		$this->pdf->Cell(30,7,$observ,'TBLR',0,'C','1');
		    		$this->pdf->Ln(7);	
				
			   					
				}
				$this->pdf->Ln(40);
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','B',11);	
				$this->pdf->Cell(10,5,'                                                     ',0,0,'R');
				$this->pdf->SetFont('Arial','BU',11);
				$this->pdf->Cell(70,5,'                                                     ',0,0,'R');			
				$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial','B',10);				
				$this->pdf->Cell(50,5,utf8_decode("DIRECTOR(A)"),0,0,'R');
				$this->pdf->SetFont('Arial','B',10);	
				$this->pdf->Cell(20,5,'                                                     ',0,0,'R');
				$this->pdf->Cell(60,5,utf8_decode("MAESTRO(A)"),0,0,'R');
				$this->pdf->Ln(5);
	            $this->pdf->Cell(45,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->Cell(30,5,'                                                     ',0,0,'R');
	            $this->pdf->Cell(50,5,utf8_decode('FIRMA '),0,0,'R');
	            $this->pdf->SetFont('Arial','',8);

				$this->pdf->Output("Boletines -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
				ob_end_flush();

			}
		}
		
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