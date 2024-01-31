<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Not_notas_model extends CI_Model {

	var $tabla='nota';
	var $column= array('idnota','appat','apmat','nombres','ser1','ser2','ser3','promser','sab1','sab2','sab3','sab4','sab5','sab6','promsab','hac1','hac2','hac3','hac4','hac5','hac6','promhac','dec1','dec2','dec3','promdec','autoser','autodec','final'); //set column field database for datatable orderable
	var $column_search = array('idnota','appat','apmat','nombres'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('appat' => 'asc','apmat'=>'asc','nombres'=>'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
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
		$this->db->select('idnota');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_idcod_table2($table,$id)
	{
		$this->db->select($id);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_idcod_table3($table)
	{
		$this->db->select('idindi');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_rows2($table,$id)
	{				
		$this->db->select("temasbi.idtemabi");
		$this->db->from("temasbi");
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_count_rows($table)
	{
		$this->db->select('idnota');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_count_rows3($table)
	{
		$this->db->select('idindi');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_prof_row($p,$m,$n,$t)
	{
		$this->db->from($t);
		$this->db->where('appaterno',$p);
		$this->db->where('apmaterno',$m);
		$this->db->where('nombres',$n);
		$query = $this->db->get();
		return $query->result();
	}

	public function save_nota($data)
	{		
		$this->db->insert('nota',$data);
		return $this->db->insert_id();
	}

	public function save_indi($table,$data)
	{		
		$this->db->insert($table,$data);
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

	public function indi_if($table,$idmat,$bimes,$gestion)
	{
		$this->db->from($table);
		$this->db->where('idmat',$idmat);
		$this->db->where('bimestre',$bimes);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();

	}

	public function indi_if_exit($table,$idmat,$bimes,$gestion)
	{
		$this->db->from($table);
		$this->db->where('idmat',$idmat);
		$this->db->where('bimestre',$bimes);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function update($where,$data)
	{
		$this->db->update($this->tabla,$data,$where);
		return $this->db->affected_rows();
	}

	public function get_rows_nivel($table,$idp)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->distinct();
		$this->db->select('nivel');
		$this->db->from($table);
		$this->db->where('idprof',$idp);
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

	public function get_rows_curso($table,$idp,$level)
	{
		$this->db->distinct();
		$this->db->select('curso');
		$this->db->from($table);
		$this->db->where('idprof',$idp);
		$this->db->where('nivel',$level);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_materia($table,$idprof,$nivel,$curso)
	{
		$this->db->select('materia');
		$this->db->from($table);
		$this->db->where('idprof',$idprof);
		$this->db->where('nivel',$nivel);
		$this->db->where('curso',$curso);
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

	public function get_idmat($table,$idprof,$idcurso,$nivel,$materia)
	{
		$this->db->select('idmat');
		$this->db->from($table);
		$this->db->where('nivel',$nivel);
		$this->db->where('idprof',$idprof);
		$this->db->where('idcurso',$idcurso);
		$this->db->where('materia',$materia);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_estud($table,$idcurso,$gestion)
	{
		$this->db->from($table);
		$this->db->where('gestion',$gestion);
		$this->db->where('idcurso',$idcurso);
		$query = $this->db->get();
		return $query->result();
	}

	public function if_exit_nota($table,$idest,$idmat,$bimestre)
	{
		$this->db->from($table);
		$this->db->where('idest',$idest);
		$this->db->where('idmat',$idmat);
		$this->db->where('bimestre',$bimestre);
		$query=$this->db->get();
		return $query->num_rows();
		
	}



	public function get_datatables_by_all($idmat,$idcur,$idprof,$gestion,$bimestre)
	{
		
		$this->_get_datatables_all($idmat,$idcur,$idprof,$gestion,$bimestre);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();

	}

	//busqueda
	private function _get_datatables_all($idmat,$idcur,$idprof,$gestion,$bimestre)
	{

		$this->db->from($this->tabla);
		$this->db->where('idmat',$idmat);
		$this->db->where('idcurso',$idcur);
		$this->db->where('idprof',$idprof);
		$this->db->where('gestion',$gestion);
		$this->db->where('bimestre',$bimestre);

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

	public function count_filtered_byid($idmat,$idcur,$idprof,$gestion,$bimestre)
	{
		$this->_get_datatables_all($idmat,$idcur,$idprof,$gestion,$bimestre);
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

	//impresion

	public function get_all_idmat($idmat)
	{
		//print_r($idmat);
		
		$this->db->from('materia');
		$this->db->where('idmat',$idmat);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_all_idcur($idcurso)
	{
		$this->db->select('colegio');
		$this->db->from('curso');
		$this->db->where('idcurso',$idcurso);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_prof($idprof)
	{
		$this->db->from('profesor');
		$this->db->where('idprof',$idprof);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_lista($idmat,$bi,$gestion)
	{
		$this->db->from('nota');
		$this->db->where('idmat',$idmat);
		$this->db->where('bimestre',$bi);
		$this->db->where('gestion',$gestion);
		$this->db->order_by("appat", "asc");
		$this->db->order_by("apmat", "asc");
		$this->db->order_by("nombres", "asc");
		$query=$this->db->get();
		return $query->result();
	}

	public function get_estadistic($idmat,$bi,$gestion)
	{
		$this->db->from('final');
		$this->db->where('idmat',$idmat);
		$this->db->where('bimestre',$bi);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}
	
	public function update_indi($where,$table,$data)
	{
		$this->db->update($table,$data,$where);
		return $this->db->affected_rows();
	}

	public function get_indik($table,$bi,$idmat,$gestion)
	{
		$this->db->from($table);
		$this->db->where('idmat',$idmat);
		$this->db->where('bimestre',$bi);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->result();
	}

}
