<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profesor_pe_model extends CI_Model {
 
	var $tabla='temas';
	var $column= array('pregunta.id','cursos.nombre','pregunta.pregunta','materias.nombre','temas.tema','estudiantes.appaterno','estudiantes.apmaterno','estudiantes.nombre'); //set column field database for datatable orderable
	var $column1= array('tareas.id','cursos.nombre','niveles.turno','niveles.nivel','temas.gestion','materias.nombre','temas.tema');
	var $column_search = array('idmat','materia','idprof','area','campo','idprof','gestion'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order1 = array('estudiantes.appaterno'=> 'asc','estudiantes.apmaterno'=> 'asc','estudiantes.nombre'=> 'asc' ); 
	var $order = array('estudiantes.appaterno'=> 'asc','estudiantes.apmaterno'=> 'asc','estudiantes.nombre'=> 'asc'); // default order 

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
	
	private function _get_datatables_query_gestion1($curso,$nivel,$gestion,$materia,$id_prof)
	{		
		$this->db->select('tareas.id as id,cursos.nombre as curso,niveles.turno,niveles.nivel,temas.gestion,materias.nombre as materia,temas.tema as tema');
		$this->db->from("tareas");
		$this->db->join('temas', 'temas.id = tareas.id_tema','inner');
		$this->db->join('profesores', 'profesores.id_prof = temas.id_prof','inner');
		$this->db->join('cursos', 'cursos.codigo = temas.cod_curso','inner');
		$this->db->join('niveles', 'niveles.codigo = temas.cod_nivel','inner');
		$this->db->join('materias', 'materias.id_mat = temas.id_mat','inner');
		$this->db->where('temas.gestion',$gestion);
		$this->db->where('temas.cod_curso',$curso);
		$this->db->where('temas.cod_nivel',$nivel);
		$this->db->where('temas.id_mat',$materia);
		$this->db->where('temas.id_prof',$id_prof);

		$i = 0;
	
		foreach ($this->column1 as $item) // loop column 
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

				if(count($this->column1) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column1[$i]=$item;
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order1;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	private function _get_datatables_query_gestion($gestion,$id_tema)
	{		
		$this->db->select('pregunta.id as id,cursos.nombre as curso,materias.nombre as materia,temas.tema as tema,pregunta.pregunta,estudiantes.appaterno,estudiantes.apmaterno,estudiantes.nombre');
		$this->db->from("pregunta");
		$this->db->join('temas', 'temas.id = pregunta.id_tema','inner');
		$this->db->join('cursos', 'cursos.codigo = temas.cod_curso','inner');
		$this->db->join('materias', 'materias.id_mat = temas.id_mat','inner');
		$this->db->join('estudiantes', 'pregunta.id_est = estudiantes.id_est','inner');
		$this->db->where('temas.gestion',$gestion);
		$this->db->where('temas.id',$id_tema);

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

	function get_datatables_gestion($gestion,$id_tema)
	{
		$this->_get_datatables_query_gestion($gestion,$id_tema);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	

	function get_preguntas($gestion,$id_tema)
	{		
		$this->db->select('pregunta.id as id,pregunta.pregunta,estudiantes.appaterno,estudiantes.apmaterno,estudiantes.nombre');
		$this->db->from("pregunta");
		$this->db->join('temas', 'temas.id = pregunta.id_tema','inner');
		$this->db->join('estudiantes', 'pregunta.id_est = estudiantes.id_est','inner');
		$this->db->where('temas.gestion',$gestion);
		$this->db->where('temas.id',$id_tema);
		$query=$this->db->get();
		return $query->result();
	}

	function get_datatables_gestion1($curso,$nivel,$gestion,$materia,$id_prof)
	{
		$this->_get_datatables_query_gestion1($curso,$nivel,$gestion,$materia,$id_prof);
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
	public function get_rows_nota_prom($nivel,$curso,$gestion)
	{
		$this->db->from("nota_prom");
		$cod=$curso."-".$nivel."-";
		$this->db->like('codigo', $cod, 'match');
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_temas($materia,$curso,$nivel,$idprof,$gestion)
	{
		$this->db->from("temas");
		$this->db->where('id_mat',$materia);
		$this->db->where('cod_curso',$curso);
		$this->db->where('cod_nivel',$nivel);
		$this->db->where('id_prof',$idprof);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
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
	function insert($data,$tabla)
	{
		$this->db->insert($tabla,$data);
		return $this->db->insert_id();
	}
	public function rows_curso_materia($nivel,$curso)
	{
		$this->db->select('asiginar_materiacu.*,materias.nombre as materia');
		$this->db->from("asiginar_materiacu");
		$this->db->join('materias', 'materias.id_mat = asiginar_materiacu.id_mat','inner');
		$cod=$curso."-".$nivel."-";
		$this->db->like('asiginar_materiacu.codigo', $cod, 'match');
		$query=$this->db->get();
		return $query->result();
	}
	public function nota_materia($gestion,$id_est,$id_mat)
	{
		$this->db->from("nota_materia");
		$this->db->where('gestion', $gestion);
		$this->db->where('id_est', $id_est);
		$this->db->where('id_mat', $id_mat);
		$query=$this->db->get();
		return $query->result();
	}
	public function nota_area($gestion,$id_est,$id_area)
	{
		$this->db->from("nota_area");
		$this->db->where('gestion', $gestion);
		$this->db->where('id_est', $id_est);
		$this->db->where('id_area', $id_area);
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
	public function get_nivel($nivel)
	{
		$this->db->from("niveles");
		$this->db->where('codigo', $nivel);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_tema($tema)
	{
		$this->db->from("temas");
		$this->db->where('id', $tema);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_materia($materia)
	{
		$this->db->from("materias");
		$this->db->where('id_mat', $materia);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_curso($curso)
	{
		$this->db->from("cursos");
		$this->db->where('codigo', $curso);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_area_primaria()
	{
		$sql="SELECT * FROM areas WHERE id_area=1 OR id_area=3 OR id_area=4 OR id_area=5 OR id_area=6 OR id_area=7 OR id_area=8 OR id_area=11 OR id_area=15";
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_area_secundaria()
	{
		$sql="SELECT * FROM areas WHERE id_area=1 OR id_area=2 OR id_area=3 OR id_area=4 OR id_area=5 OR id_area=6 OR 
		id_area=7 OR id_area=9 OR id_area=10 OR id_area=11 OR id_area=12 OR id_area=13 OR id_area=14 OR id_area=15";
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_materia_primaria()
	{
		$sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=3 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR 
		id_mat=12 OR id_mat=13 OR id_mat=20 OR id_mat=25";
		$query=$this->db->query($sql);
		return $query->result();
	}
	public function get_materia_secundaria($curso)
	{
		if($curso=="1A" OR $curso=="1B" OR $curso=="2A" OR $curso=="2B"){
			$sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=2 OR id_mat=4 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR 
			id_mat=11 OR id_mat=12 OR id_mat=14 OR id_mat=15 OR id_mat=16 OR id_mat=21 OR id_mat=24 OR id_mat=25";
		}
		if($curso=="3A" OR $curso=="3B"){
			$sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=2 OR id_mat=4 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR 
			id_mat=11 OR id_mat=12 OR id_mat=14 OR id_mat=18 OR id_mat=19 OR id_mat=21 OR id_mat=22 OR id_mat=23 OR id_mat=24 OR id_mat=25";
		}
		if($curso=="4A" OR $curso=="4B"){
			$sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=4 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR 
			id_mat=12 OR id_mat=14 OR id_mat=20 OR id_mat=22 OR id_mat=23 OR id_mat=24 OR id_mat=25";
		}
		if($curso=="5A" OR $curso=="5B" OR $curso=="5C" OR $curso=="6A" OR $curso=="6B" OR $curso=="6C"){
			$sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=4 OR id_mat=5 OR id_mat=6 OR id_mat=7 OR id_mat=8 OR 
			id_mat=9 OR id_mat=10 OR id_mat=11 OR id_mat=12 OR id_mat=18 OR id_mat=19 OR id_mat=21 OR id_mat=22 OR id_mat=23 OR id_mat=24 OR id_mat=25 OR id_mat=26";
		}
		$query=$this->db->query($sql);
		return $query->result();
	}

	function count_filtered_gestion($gestion,$id_tema)
	{
		$this->_get_datatables_query_gestion($gestion,$id_tema);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_filtered_gestion1($curso,$nivel,$gestion,$materia,$id_prof)
	{
		$this->_get_datatables_query_gestion1($curso,$nivel,$gestion,$materia,$id_prof);
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

	function delete_by_id($id,$tabla)
	{
		$this->db->where('id',$id);
		$this->db->delete($tabla);
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
		$this->db->from($table);
		$this->db->order_by("gestion","desc");
		$query = $this->db->get();
		return $query->result();
	}

}
