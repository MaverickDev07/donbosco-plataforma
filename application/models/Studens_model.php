<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Studens_model extends CI_Model
{

	var $table = 'usuario';
	var $column_order = array('usuario', 'clave', 'nombre', 'tipo'); //set column field database for datatable orderable
	var $column_search = array('usuario', 'clave', 'nombre', 'tipo'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idusuario' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/*public function validar($pwd)
	{
		
		$this->db->from("estudiantes");
		$this->db->where('ci',$pwd);
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row(); 
		}
		else{
			return false;
		}
		
	}
*/
	public function estudiante($pwd)
	{
		$this->db->select('*');
		$this->db->from("estudiantes");
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->where('estudiantes.preinscripcion', true);
		$query = $this->db->get();
		return $query->row();
	}
	public function estudiantes($pwd)
	{
		$this->db->select('estudiantes.*,nota_prom.codigo,nota_prom.debe');
		$this->db->from("estudiantes");
		$this->db->join('nota_prom ', 'estudiantes.id_est=nota_prom.id_est');
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->where('nota_prom.gestion', 2020);
		$query = $this->db->get();
		return $query->row();
	}
	public function validar($pwd)
	{

		$this->db->from("estudiantes");
		$this->db->join('nota_prom', 'estudiantes.id_est=nota_prom.id_est');
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->where('nota_prom.gestion', 2023);
		$this->db->where('nota_prom.debe', false);
		//$this->db->where('nota_prom.boletin',false);
		//$this->db->like('nota_prom.codigo', 'PT', 'both'); 
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function validar2($pwd)
	{

		$this->db->from("estudiantes");
		$this->db->join('nota_prom', 'estudiantes.id_est=nota_prom.id_est');
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->where('nota_prom.gestion', 2023);
		$this->db->where('nota_prom.debe', false);
		$this->db->where('nota_prom.boletin', false);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}

	public function validar3($pwd)
	{

		$this->db->from("estudiantes");
		$this->db->join('nota_prom ', 'estudiantes.id_est=nota_prom.id_est');
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->like('nota_prom.codigo', '5A-SM', 'both');
		$this->db->where('nota_prom.gestion', 2023);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function validar4($pwd)
	{

		$this->db->from("estudiantes");
		$this->db->join('nota_prom ', 'estudiantes.id_est=nota_prom.id_est');
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->where('nota_prom.gestion', 2020);
		$this->db->like('nota_prom.codigo', '5B-SM', 'both');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function validar5($pwd)
	{

		$this->db->from("estudiantes");
		$this->db->join('nota_prom ', 'estudiantes.id_est=nota_prom.id_est');
		$this->db->where('estudiantes.ci', $pwd);
		$this->db->like('nota_prom.codigo', '5C-SM', 'both');
		$this->db->where('nota_prom.gestion', 2020);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return false;
		}
	}
	public function validarp($us)
	{
		$sql = "SELECT * FROM estudiantes 
				WHERE ci='" . $us . "'";
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
	public function validarPadre($inscrip, $cl, $ce)
	{
		$sql = "SELECT " . $inscrip . ".* FROM " . $inscrip . "
			INNER JOIN estudiante ON estudiante.idest=" . $inscrip . ".idest
			WHERE " . $inscrip . ".ci='" . $cl . "' AND estudiante.ci='" . $ce . "'";
		$query  = $this->db->query($sql);
		return $query->result();
	}
}
