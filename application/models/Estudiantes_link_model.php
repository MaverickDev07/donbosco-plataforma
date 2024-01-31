<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudiantes_link_model extends CI_Model {
 
	var $tabla='clase_virtual';
	var $column= array('clase_virtual.id','materias.nombre','temas.tema','profesores.appaterno','profesores.apmaterno','profesores.nombre'); //set column field database for datatable orderable
	var $column1= array('tareas.id','materias.nombre','temas.tema','profesores.appaterno','profesores.apmaterno','profesores.nombre');
	var $column_search = array('idmat','materia','idprof','area','campo','idprof','gestion'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order1 = array('tareas.fecha' => 'asc'); 
	var $order = array('clase_virtual.dia' => 'asc'); // default order 

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
	

	private function _get_datatables_query_gestion($curso,$nivel,$gestion)
	{		
		$this->db->select('clase_virtual.*,materias.nombre as materia,profesores.appaterno,profesores.apmaterno,profesores.nombre');
		$this->db->from("clase_virtual");
		$this->db->join('profesores', 'profesores.id_prof = clase_virtual.id_prof','inner');
		$this->db->join('materias', 'materias.id_mat = clase_virtual.id_mat','inner');
		$this->db->where('clase_virtual.gestion',$gestion);
		$this->db->where('clase_virtual.cod_curso',$curso);
		$this->db->where('clase_virtual.cod_nivel',$nivel);

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
	

	function count_filtered_gestion($curso,$nivel,$gestion)
	{
		$this->_get_datatables_query_gestion($curso,$nivel,$gestion);
		$query = $this->db->get();
		return $query->num_rows();
	}
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	function get_datatables_gestion($curso,$nivel,$gestion)
	{
		$this->_get_datatables_query_gestion($curso,$nivel,$gestion);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	function count_filtered()
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
	
	public function Estudiante($id_est,$gestion)
	{
		$this->db->from("nota_prom");
		$this->db->where('id_est',$id_est);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_gestion()
	{
		$this->db->select("max(gestion) as gestion");
		$this->db->from("gestion");
		$query=$this->db->get();
		return $query->row();
	}
	public function get_rows_curso_materia($nivel,$curso,$gestion)
	{
		$this->db->distinct();
		$this->db->select("materias.nombre,materias.id_mat");
		$this->db->from("temas");
		$this->db->join('materias', 'materias.id_mat = temas.id_mat','inner');
		$this->db->where('cod_curso',$curso);
		$this->db->where('cod_nivel',$nivel);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}
	public function Get_curso($cod_curso)
	{
		$this->db->from("cursos");
		$this->db->where('codigo',$cod_curso);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_nivel($nivel)
	{
		$this->db->from("niveles");
		$this->db->where('codigo', $nivel);
		$query=$this->db->get();
		return $query->row();
	}

}
