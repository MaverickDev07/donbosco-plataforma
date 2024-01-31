<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_curso_model extends CI_Model {

	var $tabla='asiginar_curso';
	var $column= array('cursos.codigo','cursos.nombre','niveles.turno','niveles.nivel','colegio.nombre'); //set column field database for datatable orderable
	var $column_search = array('idcurso','corto','curso','nivel'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('cursos.codigo' => 'asc','niveles.id_nivel' => 'asc',); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	} 

	private function _get_datatables_query()
	{
		
		$this->db->select('asiginar_curso.id_asg_cur as id,asiginar_curso.codigo,cursos.nombre as curso,niveles.turno,niveles.nivel,colegio.nombre as colegio');
		$this->db->from('asiginar_curso');
		$this->db->join('cursos', 'cursos.id_curso = asiginar_curso.id_curso','inner');
		$this->db->join('niveles', 'niveles.id_nivel = asiginar_curso.id_nivel','inner');
		$this->db->join('colegio', 'colegio.id_col = niveles.id_col','inner');
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
		$this->db->select('idcurso');
		$this->db->from('curso');
		$query = $this->db->get();
		return $query->result();
	}
	function ajax_get_cursos()
	{
		$this->db->from('cursos');
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_rows($table)
	{
		$this->db->select('idcurso');
		$this->db->from('curso');
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function save_curso($data)
	{		
		$this->db->insert("asiginar_curso",$data);
		return $this->db->insert_id();
	}

	function delete_by_id($id)
	{
		$this->db->where('id_asg_cur',$id);
		$this->db->delete($this->tabla);
	}

	function get_by_id($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('id_asg_cur',$id);
		$query=$this->db->get();
		return $query->row();
	}
	function get_curso($codigo)
	{
		$this->db->from("cursos");
		$this->db->where('codigo',$codigo);
		$query=$this->db->get();
		return $query->row();
	}
	function get_nivel($codigo)
	{
		$this->db->from("niveles");
		$this->db->where('codigo',$codigo);
		$query=$this->db->get();
		return $query->row();
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

	public function get_rows_level($table,$nivel,$turno)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$this->db->where('nivel',$nivel);
		$this->db->where('turno',$turno);
		$query = $this->db->get();
		return $query->result();
	}
}
