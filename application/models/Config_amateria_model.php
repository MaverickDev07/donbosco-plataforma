<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_amateria_model extends CI_Model {

	var $tabla='asiginar_materiacu';
	var $column= array('materias.nombre','cursos.nombre','niveles.nivel', 'niveles.turno'); //set column field database for datatable orderable
	var $column_search = array('idmat','materia','idprof','area','campo','idprof','gestion'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('asiginar_materiacu.id_asg_mate' => 'asc'); // default order 

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

	private function _get_datatables_query_gestion($nivel,$curso)
	{		
		$this->db->select('asiginar_materiacu.id_asg_mate as id,materias.nombre as materia,cursos.nombre as curso,niveles.nivel, niveles.turno');
		$this->db->from("asiginar_materiacu");
		$this->db->join('asiginar_curso', 'asiginar_curso.id_asg_cur = asiginar_materiacu.id_asg_cur','inner');
		$this->db->join('cursos', 'cursos.id_curso = asiginar_curso.id_curso','inner');
		$this->db->join('niveles', 'niveles.id_nivel = asiginar_curso.id_nivel','inner');
		$this->db->join('materias', 'materias.id_mat = asiginar_materiacu.id_mat','inner');
	    $d=$curso.'-'.$nivel;
	    $this->db->like('asiginar_materiacu.codigo', $d);
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	function get_datatables_gestion($nivel,$curso)
	{
		$this->_get_datatables_query_gestion($nivel,$curso);
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
	function get_materias_area($id_mat)
	{
		$this->db->select('areas.*');   
		$this->db->from("materias");
		$this->db->join('areas', 'materias.id_area = areas.id_area','inner');
		$this->db->where('materias.id_mat',$id_mat);
		$query = $this->db->get();
		return $query->row();
	}
	
	function count_filtered_gestion($nivel,$curso)
	{
		$this->_get_datatables_query_gestion($nivel,$curso);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->tabla);
		return $this->db->count_all_results();
	}

	//otras funciones
	public function get_rows_nivel($table)
	{
		$this->db->distinct();
		$this->db->select('nivel');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_rows_cole($table,$nivel)
	{
		$this->db->distinct();
		$this->db->select('gestion,colegio,curso');
		$this->db->from($table);
		$this->db->where('nivel',$nivel);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rows_curso($table,$nivel)
	{
		$this->db->select('curso');
		$this->db->from($table);
		$this->db->where('nivel',$nivel);
		$this->db->order_by('corto', 'asc');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_rows_curso_nivel($nivel)
	{
		$this->db->select('asiginar_curso.*,cursos.nombre as curso');
		$this->db->from("asiginar_curso");
		$this->db->join('cursos', 'cursos.id_curso = asiginar_curso.id_curso','inner');
		$cod="-".$nivel."-";
		$this->db->like('asiginar_curso.codigo', $cod, 'match');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_curso_nivels($id_curso)
	{
		// $this->db->select('*');
		$this->db->from("asiginar_curso");
		$this->db->where('id_asg_cur',$id_curso);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_rows_curso2($table,$idcur)
	{
		$this->db->from($table);
		$this->db->where('idcurso',$idcur);	
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rows_corto($table,$curso,$nivel)
	{
		$this->db->distinct();
		$this->db->select('corto,idcurso');
		$this->db->from($table);
		$this->db->where('curso',$curso);
		$this->db->where('nivel',$nivel);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rows_profe($table)
	{
		$this->db->select('appaterno,apmaterno,nombres');
		$this->db->from($table);
		$this->db->order_by('appaterno', 'asc');
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rows_profe2($table,$idprof)
	{
		$this->db->select('appaterno,apmaterno,nombres');
		$this->db->from($table);
		$this->db->where('idprof',$idprof);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_rows_materia()
	{
		$this->db->from('materias');
		$query=$this->db->get();
		return $query->result();
	}

	public function get_rows_idprofe($table,$appat,$apmat,$nomb)
	{
		$this->db->select('idprof');
		$this->db->from($table);
		$this->db->where('appaterno',$appat);
		$this->db->where('apmaterno',$apmat);
		$this->db->where('nombres',$nomb);
		$query=$this->db->get();
		return $query->result();
	}

	function get_idcod_table($table)
	{
		$this->db->select('idmat');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_rows($table)
	{
		$this->db->select('idmat');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function save_mat($data)
	{
		$this->db->insert($this->tabla,$data);
		return $this->db->insert_id();
	}

	function delete_by_id($id)
	{
		$this->db->where('id_asg_mate',$id);
		$this->db->delete($this->tabla);
	}

	function get_by_id($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('id_asg_mate',$id);
		$query=$this->db->get();
		return $query->row();
	}


	public function update($where,$data)
	{
		$this->db->update($this->tabla,$data,$where);
		return $this->db->affected_rows();
	}

	public function get_print_prof_pdf()
	{
		$this->db->from($this->tabla);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_gestion($table)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$this->db->order_by("gestion","desc");
		$query = $this->db->get();
		return $query->result();
	}

}
