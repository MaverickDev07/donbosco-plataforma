<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karde_model extends CI_Model {

	var $tabla='estudiantes';
	var $column= array('rude','ci','complemento','appaterno','apmaterno','nombre'); //set column field database for datatable orderable
	var $column_search = array('id_est','rude','ci','appaterno','apmaterno','nombre','genero'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $column_kar=array('fecha','categoria','materia','descripcion');
	var $order = array('appaterno' => 'asc','apmaterno' => 'asc','nombre' => 'asc'); // default order 
	var $order_fecha = array('fecha' => 'asc');
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
	public function get_curso_est($idest)
	{
		$this->db->SELECT('cu.*');
		$this->db->from('estudiante as est');
		$this->db->join('curso cu','cu.idcurso=est.idcurso');
		$this->db->where('est.idest',$idest);
		$this->db->LIMIT(1);
		$query = $this->db->get();
		return $query->row();
	}

	//otras funciones

	public function get_rows_gestion($table)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$this->db->order_by("gestion","desc");
		$query = $this->db->get();
		return $query->result();
	}

	public function get_idcod_table($table)
	{
		$this->db->select('idkar');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_rows($table)
	{
		$this->db->select('idkar');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function save_curso($data)
	{		
		$this->db->insert($this->tabla,$data);
		return $this->db->insert_id();
	}


	function get_studiante($id)
	{
		$this->db->from('estudiantes');
		$this->db->where('id_est',$id);
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
	public function get_rows_niveles()//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		//$this->db->select('colegio');
		$this->db->from('niveles');
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
	public function get_curso_student($ges,$curso,$nivel)
	{
		$codigo=$curso."-".$nivel;
		$this->db->select('estudiantes.*');   
		$this->db->from("nota_prom");
		$this->db->join('estudiantes', 'estudiantes.id_est = nota_prom.id_est','inner');
		$this->db->where('nota_prom.gestion',$ges);
		$this->db->where('nota_prom.retirado', false);
	    $d=$curso.'-'.$nivel;
	    $this->db->like('nota_prom.codigo', $d);
		$this->db->order_by("estudiantes.appaterno asc, estudiantes.apmaterno asc, estudiantes.nombre asc");
		$query = $this->db->get();
		return $query->result();
	}
	public function get_cursos($id)
	{
		$this->db->from('cursos');
		$this->db->where('codigo',$id);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_nivel_colegio($nivel)
	{
		$this->db->select('niveles.codigo,niveles.turno,niveles.nivel,colegio.nombre,niveles.id_nivel');   
		$this->db->from("niveles");
		$this->db->join('colegio', 'colegio.id_col = niveles.id_col','inner');
		$this->db->where('niveles.codigo',$nivel);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_trimestre($id_tri)
	{ 
		$this->db->from("bimestre");
		$this->db->where('id_bi',$id_tri);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_trimestres()
	{ 
		$this->db->from("bimestre");
		$this->db->where('activo',true);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_all_trimestres()
	{ 
		$this->db->from("bimestre");
		$this->db->like('nombre','TRIMESTRE');
		$this->db->order_by('id_bi', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_rows_colegio($level)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		$this->db->select('colegio.*');
		$this->db->from('niveles');
		$this->db->join('colegio', 'colegio.id_col = niveles.id_col');
		$this->db->where('niveles.codigo',$level);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_kardex($tri,$gestion,$id_est)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		$this->db->select('reguistro_kardex.*,categoria.nombre as cat');
		$this->db->from('reguistro_kardex');
		$this->db->join('categoria', 'categoria.id = reguistro_kardex.id_categoria');
		$this->db->where('reguistro_kardex.gestion',$gestion);
		$this->db->where('reguistro_kardex.id_bi',$tri);
		$this->db->where('reguistro_kardex.id_est',$id_est);
		$this->db->order_by("reguistro_kardex.fecha asc");
		$query = $this->db->get();
		return $query->result();
	}
	public function get_kardex1($gestion,$id_est)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		$this->db->select('reguistro_kardex.*,categoria.nombre as cat');
		$this->db->from('reguistro_kardex');
		$this->db->join('categoria', 'categoria.id = reguistro_kardex.id_categoria');
		$this->db->where('reguistro_kardex.gestion',$gestion);
		$this->db->where('reguistro_kardex.id_est',$id_est);
		$this->db->order_by("reguistro_kardex.fecha asc");
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
	public function get_rows_cursos($nivel)
	{
		$this->db->select('cursos.codigo, cursos.nombre');
		$this->db->from('nivel_curso');
		$this->db->join('cursos', 'cursos.codigo = nivel_curso.cod_curso');
		$this->db->where('nivel_curso.activo',true);
		$this->db->like('nivel_curso.codigo', $nivel, 'both');
		$this->db->order_by("nivel_curso.cod_curso", "asc");
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

	public function get_datatables_by_idcur($ges,$idcur,$nivel)
	{
		
		$this->_get_datatables_idcur($ges,$idcur,$nivel);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	//busqueda
	private function _get_datatables_idcur($ges,$idcur,$nivel)
	{
		$this->db->select('estudiantes.*');
		$this->db->from('estudiantes');
		$this->db->join('nota_prom', 'nota_prom.id_est = estudiantes.id_est');
		$cod=$idcur."-".$nivel;
		$this->db->like('nota_prom.codigo', $cod, 'after');
		$this->db->where('nota_prom.gestion',$ges);

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

	public function count_filtered_byid($ges,$idcur,$nivel)
	{
		$this->_get_datatables_idcur($ges,$idcur,$nivel);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_print_estud_pdf($ges,$idcur)
	{
		$this->db->from($this->tabla);
		$this->db->where('idcurso',$idcur);
		$this->db->where('gestion',$ges);
		$this->db->order_by("appaterno","asc");
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

	public function save_kardex($data)
	{		
		$this->db->insert('reguistro_kardex',$data);
		return $this->db->insert_id();
	}

	public function _get_datatables_kar($idest,$bimes,$gestion)
	{

		$this->db->from('reguistro_kardex');
		$this->db->where('id_est',$idest);
		$this->db->where('id_bi',$bimes);
		$this->db->where('gestion',$gestion);

		$i = 0;
	
		foreach ($this->column_kar as $item) // loop column 
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

				if(count($this->column_kar) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order_fecha))
		{
			$order = $this->order_fecha;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function get_datatables_kar($idest,$bimes,$gestion)
	{
		$this->_get_datatables_kar($idest,$bimes,$gestion);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	public function count_all_kar()
	{
		$this->db->from('kardex');
		return $this->db->count_all_results();
	}

	public function count_filtered_kar($idest,$bimes,$gestion)
	{
		$this->_get_datatables_kar($idest,$bimes,$gestion);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_print_estud_kar_pdf($idest)
	{
		$this->db->from($this->tabla);
		$this->db->where('idest',$idest);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_categoria($id)
	{
		$this->db->from('categoria');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row();
	}


	public function get_print_curso_kar_pdf($idcur)
	{
		$this->db->from('curso');
		$this->db->where('idcurso',$idcur);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_print_kar_pdf($idest,$bimes,$gestion)
	{
		$this->db->from('kardex');
		$this->db->where('idest',$idest);
		$this->db->where('bimestre',$bimes);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_print_estudiante($idcur,$gestion)
	{
		$this->db->from($this->tabla);
		$this->db->where('idcurso',$idcur);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id',$id);
		$this->db->delete('reguistro_kardex');
	}
	
}

