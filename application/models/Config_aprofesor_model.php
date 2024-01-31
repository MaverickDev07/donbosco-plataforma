<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_aprofesor_model extends CI_Model {

	var $tabla='asiginar_profesorm';
	var $column= array('asiginar_profesorm.id_asg_cur','profesores.appaterno', 'profesores.apmaterno','profesores.nombre','asiginar_profesorm.codigo','cursos.nombre','niveles.turno','niveles.nivel','colegio.nombre','materias.nombre'); //set column field database for datatable orderable
	var $column_search = array('idmat','materia','idprof','area','campo','idprof','gestion'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('profesores.appaterno' => 'asc','profesores.apmaterno' => 'asc','profesores.nombre' => 'asc'); // default order 

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
		$this->db->select('asiginar_profesorm.id_asg_prof as id,profesores.appaterno, profesores.apmaterno,profesores.nombre,asiginar_profesorm.codigo,cursos.nombre as curso,niveles.turno,niveles.nivel,colegio.nombre as colegio,asiginar_profesorm.gestion,materias.nombre as materia');
		$this->db->from($this->tabla);
		$this->db->join('profesores', 'profesores.id_prof = asiginar_profesorm.id_prof','inner');
		$this->db->join('asiginar_materiacu', 'asiginar_materiacu.id_asg_mate = asiginar_profesorm.id_asg_mate','inner');
		$this->db->join('asiginar_curso', 'asiginar_curso.id_asg_cur = asiginar_materiacu.id_asg_cur','inner');
		$this->db->join('cursos', 'cursos.id_curso = asiginar_curso.id_curso','inner');
		$this->db->join('niveles', 'niveles.id_nivel = asiginar_curso.id_nivel','inner');
		$this->db->join('colegio', 'colegio.id_col = niveles.id_col','inner');
		$this->db->join('materias', 'materias.id_mat = asiginar_materiacu.id_mat','inner');
		$cod=$curso."-".$nivel;
		$this->db->like('asiginar_profesorm.codigo', $cod, 'after');
		$this->db->where('asiginar_profesorm.gestion',$gestion);

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
	public function get_curso($id_curso)
	{
		$this->db->select('cursos.*');
		$this->db->from("asiginar_curso");
		$this->db->join('cursos', 'cursos.id_curso = asiginar_curso.id_curso','inner');
		$this->db->where('asiginar_curso.id_asg_cur', $id_curso);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_ag_profesor($gestion,$materia,$idprof)
	{
		$this->db->from("asiginar_profesorm");
		$this->db->where('gestion', $gestion);
		$this->db->where('id_asg_mate', $materia);
		$this->db->where('id_prof', $idprof);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_rows_curso_materia($nivel,$curso)
	{
		$this->db->select('asiginar_materiacu.*,materias.nombre as materias');
		$this->db->from("asiginar_curso");
		$this->db->join('asiginar_materiacu', 'asiginar_materiacu.id_asg_cur = asiginar_curso.id_asg_cur','inner');
		$this->db->join('materias', 'materias.id_mat = asiginar_materiacu.id_mat','inner');
		$cod=$curso."-".$nivel."-";
		$this->db->like('asiginar_curso.codigo', $cod, 'match');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_trimestre()
	{
		$this->db->from("bimestre");
		$cod="T";
		$this->db->like('codigo', $cod, 'match');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_estudiantes($curso,$gestion,$nivel)
	{
		$this->db->select('estudiantes.*');
		$this->db->from("estudiantes");
		$this->db->join('nota_prom', 'nota_prom.id_est = estudiantes.id_est','inner');
		$this->db->where('nota_prom.gestion', $gestion);
		$cod=$curso."-".$nivel."-";
		$this->db->like('nota_prom.codigo', $cod, 'match');
		$query=$this->db->get();
		return $query->result();
	}
	public function rows_curso_materia($id_curso)
	{
		$this->db->select('asiginar_materiacu.*,materias.nombre as materia');
		$this->db->from("asiginar_materiacu");
		$this->db->join('materias', 'materias.id_mat = asiginar_materiacu.id_mat','inner');
		$this->db->where('asiginar_materiacu.id_asg_cur', $id_curso);
		$query=$this->db->get();
		return $query->result();
	}
	function get_materiass($id_mat)
	{
		$this->db->from("asiginar_materiacu");
		$this->db->where('id_asg_mate',$id_mat);
		$query = $this->db->get();
		return $query->row();
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

	function count_filtered_gestion($curso,$nivel,$gestion)
	{
		$this->_get_datatables_query_gestion($curso,$nivel,$gestion);
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

	public function get_rows_profe()
	{
		$this->db->select('*');
		$this->db->from('profesores');
		$this->db->order_by('appaterno', 'asc');
		$this->db->order_by('apmaterno', 'asc');
		$this->db->order_by('nombre', 'asc');
		$query=$this->db->get();
		return $query->result();
	}
	public function get_materia($id)
	{
		$this->db->select('*');
		$this->db->from('asiginar_materiacu');
		$this->db->where('id_asg_mate',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_rows_profe2($table,$idprof)
	{
		$this->db->select('appaterno,apmaterno,nombres');
		$this->db->from($table);
		$this->db->where('idprof',$idprof);
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

	function save($data,$tabla)
	{
		$this->db->insert($tabla,$data);
		return $this->db->insert_id();
	}

	function delete_by_id($id)
	{
		$this->db->where('id_asg_prof',$id);
		$this->db->delete($this->tabla);
	}

	function get_by_id($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('id_asg_prof',$id);
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
