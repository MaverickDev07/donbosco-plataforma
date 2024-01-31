<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	var $table = 'usuario';
	var $column_order = array('usuario','clave','nombre','tipo'); //set column field database for datatable orderable
	var $column_search = array('usuario','clave','nombre','tipo'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idusuario' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function validar($us,$pwd)
	{
		

		$this->db->from($this->table);
		$this->db->where('usuario',$us);
		$this->db->where('clave',$pwd);
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row(); 
		}
		else{
			return false;
		}
		
	}
	public function validarRoot($us)
	{
		$this->db->from($this->table);
		$this->db->where('usuario',$us);
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row(); 
		}
		else{
			return false;
		}
		
	}

	public function validarp($us)
	{
		$sql="SELECT * FROM estudiantes 
				WHERE ci='".$us."'";
		$query  = $this->db->query($sql);
		return $query->result();

	} 

	// public function validarPadre($us)
	// {
	// 	$sql="SELECT estudiante.nombres FROM estudiante
	// 		INNER JOIN inscrip_padre ON estudiante.idest=inscrip_padre.idest
	// 		INNER JOIN inscrip_madre ON estudiante.idest=inscrip_madre.idest
	// 		INNER JOIN inscrip_tutor ON estudiante.idest=inscrip_tutor.idest
	// 		WHERE (inscrip_padre.ci=".$us." OR inscrip_madre.ci=".$us." OR inscrip_tutor.ci=".$us.")";
	// 	$query  = $this->db->query($sql);
	// 	return $query->result();
		
	// }
	public function validarPadre($inscrip,$cl,$ce)
	{
		$sql="SELECT ".$inscrip.".* FROM ".$inscrip."
			INNER JOIN estudiante ON estudiante.idest=".$inscrip.".idest
			WHERE ".$inscrip.".ci='".$cl."' AND estudiante.ci='".$ce."'"  ;
		$query  = $this->db->query($sql);
		return $query->result();
		
	}

	

}
