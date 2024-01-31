<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_inscrip_edit_model extends CI_Model {

	var $tabla='estudiante';
	var $column= array('ci','appaterno','appaterno','apmaterno','nombre','genero'); //set column field database for datatable orderable
	var $column_search = array('id_est','rude','ci','appaterno','apmaterno','nombre'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('appaterno' => 'asc','appaterno' => 'asc','nombre' => 'asc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->tabla);

		$i = 0;
	
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i]=$item;
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->tabla);
		return $this->db->count_all_results();
	}

	//otras funciones

	function get_idcod_table($table)
	{
		$this->db->select('idest');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_rows($table)
	{
		$this->db->select('idcurso');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function save_curso($data)
	{		
		$this->db->insert($this->tabla,$data);
		return $this->db->insert_id();
	}

	function delete_by_id($id)
	{
		$this->db->where('idest',$id);
		$this->db->delete($this->tabla);
	}

	function get_by_id($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('idest',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_debe($id)
	{
		$this->db->from('estudiante');
		$this->db->where('idest',$id);
		$query=$this->db->get();
		return $query->result();
	}


	public function update($where,$data)
	{
		$this->db->update($this->tabla,$data,$where);
		return $this->db->affected_rows();
	}

	public function get_rows_nivel($table)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_level($table,$level)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$this->db->where('nivel',$level);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_curso($table,$nivel)
	{
		$this->db->select('curso');
		$this->db->from($table);
		$this->db->where('nivel',$nivel);
		$query = $this->db->get();
		return $query->result();
	}



	public function get_idcurso($nivel,$curso)
	{
		
		//print_r($nivel."-".$curso);

		$this->db->select('idcurso');
		$this->db->from('curso');
		$this->db->where('nivel',$nivel);
		$this->db->where('curso',$curso);

		$query = $this->db->get();
		return $query->result();
		
	}

	// public function get_datatables_by_idcur($id)
	// {
		
	// 	$this->_get_datatables_idcur($id);
	// 	if($_POST['length'] != -1)
	// 	$this->db->limit($_POST['length'], $_POST['start']);
	// 	$query = $this->db->get();

	// 	return $query->result();

	// }
	public function get_datatables_by_idcur($curso,$nivel,$gestion)
	{
		
		$this->_get_datatables_idcur($curso,$nivel,$gestion);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();

	}

	public function get_datatables_lista($curso,$nivel,$gestion)
	{
		$sql="SELECT estudiantes.appaterno ,estudiantes.apmaterno,estudiantes.nombre,estudiantes.id_est,estudiantes.ci,estudiantes.genero
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE '".$curso."-".$nivel."-%' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion."
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();

	}
	private function _get_datatables_idcur($curso,$nivel,$gestion)
	{

		// $this->db->from($this->tabla);
		// $this->db->where('idcurso',$id);
		// $this->db->where('inscrito',false);
		// $this->db->where('debe',false);
		$this->db->select('estudiantes.appaterno ,estudiantes.apmaterno,estudiantes.nombre,estudiantes.id_est,estudiantes.ci,estudiantes.genero');
	    $this->db->from('nota_prom');
	    $this->db->join('estudiantes', 'estudiantes.id_est=nota_prom.id_est','inner');
	    $this->db->where('nota_prom.retirado', false);
	    $this->db->where('nota_prom.debe', false);
	    $this->db->where('nota_prom.gestion', $gestion);
	    $d=$curso.'-'.$nivel;
	    $this->db->like('nota_prom.codigo', $d);

		$i = 0;
	
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i]=$item;
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	//busqueda
	// private function _get_datatables_idcur($id)
	// {

	// 	$this->db->from($this->tabla);
	// 	$this->db->where('idcurso',$id);
	// 	$this->db->where('inscrito',false);
	// 	$this->db->where('debe',false);

	// 	$i = 0;
	
	// 	foreach ($this->column as $item) // loop column 
	// 	{
	// 		if($_POST['search']['value']) // if datatable send POST for search
	// 		{
				
	// 			if($i===0) // first loop
	// 			{
	// 				$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
	// 				$this->db->like($item, $_POST['search']['value']);
	// 			}
	// 			else
	// 			{
	// 				$this->db->or_like($item, $_POST['search']['value']);
	// 			}

	// 			if(count($this->column) - 1 == $i) //last loop
	// 				$this->db->group_end(); //close bracket
	// 		}
	// 		$column[$i]=$item;
	// 		$i++;
	// 	}
		
	// 	if(isset($_POST['order'])) // here order processing
	// 	{
	// 		$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
	// 	} 
	// 	else if(isset($this->order))
	// 	{
	// 		$order = $this->order;
	// 		$this->db->order_by(key($order), $order[key($order)]);
	// 	}
	// }

	public function count_filtered_byid($curso,$nivel,$gestion)
	{
		$this->_get_datatables_idcur($curso,$nivel,$gestion);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_print_estud_pdf($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('idcurso',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_print_curso_pdf($id)
	{
		$this->db->from('curso');
		$this->db->where('idcurso',$id);
		$query = $this->db->get();
		return $query->row();
	}

	
}
