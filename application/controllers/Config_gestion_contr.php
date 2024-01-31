<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_gestion_contr extends CI_Controller {

	
 
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Config_gestion_model','gest');

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
		$this->load->view('config_gestion_view');
		$this->load->view('layouts/fin');
	}
	
	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}

	public function ajax_set_gestion()
	{
		//valores enviados 
		$data=array(
			'gestion'=>$this->input->post('gestion'),
		);		

		$insert=$this->gest->save_gest($data);

		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_list()
	{
		
		$list=$this->gest->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		
		foreach ($list as $gestion) {
			$no++;
			$row = array();
			$row[] = $gestion->gestion;
			//$row[] = "<img src='".$gestion->logo."' width='100' height='100'>";

			//add html for action
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_gestion('."'".$gestion->gestion."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;
		}

		//print_r($data);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->gest->count_all(),
						"recordsFiltered" => $this->gest->count_filtered(),
						"data" => $data,
				);
		//output to json format
		
		
		echo json_encode($output);

	}


	public function ajax_delete($id)
	{
		$this->gest->delete_by_id($id);
		echo json_encode(array("status"=>TRUE));
	}

	public function get_idnota($table,$cod)
	{		
		$codgen='';
		$num_rows=$this->gest->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->gest->get_idcod_table($table);
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
		
		return $idnota;
	}

//genera lista
	public function ajax_genlist()
	{
		$table='nota';
		$cod='NOT-';
		$idnota=$this->get_idnota($table,$cod);
		$newcod=substr($idnota,4,strlen($idnota));//valor de numero idnota

		$nivel=$this->input->post('nivel');
		$colegio=$this->input->post('colegio');
		$curso=$this->input->post('curso');
		$gestion=$this->input->post('gestion');
		$bimestre=$this->input->post('bimestre');

		$listcurso=$this->gest->get_idcurso($nivel,$colegio,$curso);
		foreach ($listcurso as $curso) {
			
			$idcurso =$curso->idcurso;
			//print_r($curso->idcurso." <br>");

			$listestud=$this->gest->get_estud($idcurso,$gestion);
			foreach($listestud as $estudiante)
			{
				$idest=$estudiante->idest;
				$appat=$estudiante->appaterno;
				$apmat=$estudiante->apmaterno;
				$nombres=$estudiante->nombres;

				//print_r($idest." ".$appat." ".$apmat." ".$nombres."<br>");

				$listmat=$this->gest->get_mat($idcurso,$gestion);
				foreach ($listmat as $materia) {
					$idmat=$materia->idmat;
					$idprof=$materia->idprof;
					$existe=$this->gest->ifestud($idest,$idmat,$idprof,$gestion,$bimestre);
					if(!$existe)
					{
						$newcod=$newcod+1;
						$idnota='NOT-'.$newcod;

						$data=array(
							'idnota'=>$idnota,
							'idest'=>$idest,
							'idmat'=>$idmat,
							'idcurso'=>$idcurso,
							'idprof'=>$idprof,
							'appat'=>$appat,
							'apmat'=>$apmat,
							'nombres'=>$nombres,
							'bimestre'=>$bimestre,
							'gestion'=>$gestion,
						);
						$insert=$this->gest->save_nota($data);
					}

				}

			}

		}

		echo json_encode(array("status"=>TRUE));


	}



}