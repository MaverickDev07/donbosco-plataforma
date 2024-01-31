<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_centralizador_2019_model extends CI_Model {

	var $tabla='estudiante';
	var $column= array('idest','rude','ci','appaterno','apmaterno','nombres','genero','idcurso','nivel','gestion','colegio','codigo','foto'); //set column field database for datatable orderable
	var $column_search = array('idest','rude','ci','appaterno','apmaterno','nombres','idcurso','codigo'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idest' => 'asc'); // default order 
 
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


	public function get_rows_gestion($table)
	{
		$this->db->from($table);
		$this->db->order_by("gestion","desc");
		$query = $this->db->get();
		return $query->result();
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

	public function get_datatables_by_idcur($id)
	{
		
		$this->_get_datatables_idcur($id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();

	}


	public function ejec_sql_estudiante($idcur,$gestion)
	{		
		$sql="Select DISTINCT idest, appat, apmat, nombres FROM nota WHERE idcurso='$idcur' and gestion='$gestion' ORDER BY appat,apmat,nombres asc";
		$query  = $this->db->query($sql);		
		return $query->result();
	}



	public function ejec_sql_boletin($idcur,$idest,$gestion,$idsql,$curso)
	{		

		if($idsql=='primaria')
		{
			$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS LENGUAJE1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS LENGUAJE2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS LENGUAJE3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS LENGUAJE4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS SOCIALES1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS SOCIALES2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS SOCIALES3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS SOCIALES4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FÍSICA Y DEPORTES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS EDUFISICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FÍSICA Y DEPORTES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS EDUFISICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FÍSICA Y DEPORTES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS EDUFISICA3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FÍSICA Y DEPORTES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS EDUFISICA4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS ARTPLAST1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS ARTPLAST2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS ARTPLAST3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS ARTPLAST4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MATEMATICAS1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MATEMATICAS2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MATEMATICAS3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MATEMATICAS4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='TÉCNICA TECNOLÓGICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INFORMATICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='TÉCNICA TECNOLÓGICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INFORMATICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='TÉCNICA TECNOLÓGICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INFORMATICA3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='TÉCNICA TECNOLÓGICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INFORMATICA4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS CIENCIAS1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS CIENCIAS2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS CIENCIAS3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS CIENCIAS4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS RELIGION1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS RELIGION2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS RELIGION3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS RELIGION4


					FROM nota as n
					WHERE n.idcurso='$idcur' and n.gestion='$gestion' and n.final<101 and n.idest='$idest'

					ORDER BY n.appat,n.apmat,n.nombres asc";
			$query  = $this->db->query($sql);		
			return $query->row();
		}
		if($idsql=='secundaria')
		{
			if(($curso=='PRIMERO A')OR($curso=='PRIMERO B')OR($curso=='SEGUNDO A')OR($curso=='SEGUNDO B'))
			{
				
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS LENGUAJE1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS LENGUAJE2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS LENGUAJE3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS LENGUAJE4,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS QUECHUA1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS QUECHUA2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS QUECHUA3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS QUECHUA4,    
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES11,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES12,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES13,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES14,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES21,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES22,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES23,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES24,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS SOCIALES1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS SOCIALES2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS SOCIALES3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS SOCIALES4,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS EDUFISICA1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS EDUFISICA2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS EDUFISICA3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS EDUFISICA4,
				    					
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA24,			        
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS ARTPLAST1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS ARTPLAST2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS ARTPLAST3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS ARTPLAST4,
				        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MATEMATICAS1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MATEMATICAS2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MATEMATICAS3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MATEMATICAS4,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INFORMATICA1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INFORMATICA2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INFORMATICA3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INFORMATICA4,    
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS CIENCIAS1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS CIENCIAS2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS CIENCIAS3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS CIENCIAS4,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS FISICA1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS FISICA2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS FISICA3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS FISICA4,
				        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS QUIMICA1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS QUIMICA2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS QUIMICA3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS QUIMICA4,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS COSMO1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS COSMO2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS COSMO3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS COSMO4,
				    
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS RELIGION1,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS RELIGION2,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS RELIGION3,
				    (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS RELIGION4

					FROM nota as n
					WHERE n.idcurso='$idcur' and n.gestion='$gestion' and n.final<51 and n.idest='$idest'

					ORDER BY appat,apmat,nombres asc";
				$query  = $this->db->query($sql);		
				return $query->row();
			}	
			if(($curso=='TERCERO A')OR($curso=='TERCERO B'))
			{
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS LENGUAJE1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS LENGUAJE2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS LENGUAJE3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS LENGUAJE4,

					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS QUECHUA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS QUECHUA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS QUECHUA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS QUECHUA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS SOCIALES1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS SOCIALES2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS SOCIALES3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS SOCIALES4,
			        
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS EDUFISICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS EDUFISICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS EDUFISICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS EDUFISICA4,
			        
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA31,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA32,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA33,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA34,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS ARTPLAST1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS ARTPLAST2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS ARTPLAST3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS ARTPLAST4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MATEMATICAS1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MATEMATICAS2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MATEMATICAS3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MATEMATICAS4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INFORMATICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INFORMATICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INFORMATICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INFORMATICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS CIENCIAS1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS CIENCIAS2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS CIENCIAS3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS CIENCIAS4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS FISICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS FISICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS FISICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS FISICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS QUIMICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS QUIMICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS QUIMICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS QUIMICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS COSMO1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS COSMO2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS COSMO3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS COSMO4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS RELIGION1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS RELIGION2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS RELIGION3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS RELIGION4
			        				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.gestion='$gestion' and n.final<101 and n.idest='$idest'

					ORDER BY appat,apmat,nombres asc";
					$query  = $this->db->query($sql);		
					return $query->row();
			}
			if(($curso=='CUARTO A')OR($curso=='CUARTO B'))
			{
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS LENGUAJE1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS LENGUAJE2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS LENGUAJE3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS LENGUAJE4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS SOCIALES1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS SOCIALES2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS SOCIALES3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS SOCIALES4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS EDUFISICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS EDUFISICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS EDUFISICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS EDUFISICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA31,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA32,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA33,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA34,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS ARTPLAST1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS ARTPLAST2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS ARTPLAST3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS ARTPLAST4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MATEMATICAS1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MATEMATICAS2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MATEMATICAS3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MATEMATICAS4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INFORMATICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INFORMATICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INFORMATICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INFORMATICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS CIENCIAS1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS CIENCIAS2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS CIENCIAS3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS CIENCIAS4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS FISICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS FISICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS FISICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS FISICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS QUIMICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS QUIMICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS QUIMICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS QUIMICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS COSMO1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS COSMO2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS COSMO3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS COSMO4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS RELIGION1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS RELIGION2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS RELIGION3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS RELIGION4
				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.gestion='$gestion' and n.final<101 and n.idest='$idest'

					ORDER BY appat,apmat,nombres asc";
					$query  = $this->db->query($sql);		
					return $query->row();

			}	
			if(($curso=='QUINTO A')OR($curso=='QUINTO B')OR($curso=='SEXTO A')OR($curso=='SEXTO B'))
			{
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS LENGUAJE1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS LENGUAJE2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS LENGUAJE3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS LENGUAJE4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INGLES21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INGLES22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INGLES23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INGLES24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='HISTORIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS HISTORIA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='HISTORIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS HISTORIA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='HISTORIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS HISTORIA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='HISTORIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS HISTORIA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIVICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS CIVICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIVICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS CIVICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIVICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS CIVICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIVICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS CIVICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS EDUFISICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS EDUFISICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS EDUFISICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS EDUFISICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MUSICA31,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MUSICA32,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MUSICA33,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MUSICA34,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS ARTPLAST1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS ARTPLAST2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS ARTPLAST3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS ARTPLAST4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS MATEMATICAS1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS MATEMATICAS2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS MATEMATICAS3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS MATEMATICAS4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (SIS)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INFORMATICA11,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (SIS)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INFORMATICA12,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (SIS)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INFORMATICA13,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (SIS)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INFORMATICA14,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (ELECTRO)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS INFORMATICA21,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (ELECTRO)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS INFORMATICA22,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (ELECTRO)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS INFORMATICA23,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (ELECTRO)' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS INFORMATICA24,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='BIOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS BIOLOGIA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='BIOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS BIOLOGIA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='BIOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS BIOLOGIA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='BIOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS BIOLOGIA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='GEOGRAFIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS GEOGRAFIA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='GEOGRAFIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS GEOGRAFIA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='GEOGRAFIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS GEOGRAFIA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='GEOGRAFIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS GEOGRAFIA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS FISICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS FISICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS FISICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS FISICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS QUIMICA1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS QUIMICA2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS QUIMICA3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS QUIMICA4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS COSMO1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS COSMO2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS COSMO3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS COSMO4,
			        
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=1) AS RELIGION1,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=2) AS RELIGION2,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=3) AS RELIGION3,
			        (select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=4) AS RELIGION4
				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.gestion='$gestion' and n.final<101 and n.idest='$idest'

					ORDER BY appat,apmat,nombres asc";

					$query  = $this->db->query($sql);		
					return $query->row();
			}
		}

	}



	public function ejec_sql($notlimit,$idcur,$bimes,$gestion,$idsql,$curso)
	{		

		if($idsql=='primaria')
		{

			$sql="select  DISTINCT n.idest,appat,apmat,nombres,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS LENGUAJE,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS SOCIALES,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FÍSICA Y DEPORTES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS EDUFISICA,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS ARTPLAST,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MATEMATICAS,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='TÉCNICA TECNOLÓGICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS CIENCIAS,
				(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS RELIGION
			
				FROM nota as n
				WHERE n.idcurso='$idcur' and n.bimestre='$bimes' and n.gestion='$gestion' and n.final<$notlimit

				ORDER BY appat,apmat,nombres asc";

			$query  = $this->db->query($sql);		
			return $query->result();
			//print_r($safe_sql);
		}
		if($idsql=='secundaria')
		{
			
			if(($curso=='PRIMERO A')OR($curso=='PRIMERO B')OR($curso=='SEGUNDO A')OR($curso=='SEGUNDO B'))
			{
				
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS LENGUAJE,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS QUECHUA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS SOCIALES,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS EDUFISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS ARTPLAST,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MATEMATICAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS CIENCIAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS FISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS QUIMICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS COSMO,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS RELIGION
				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.bimestre='$bimes' and n.gestion='$gestion' and n.final<$notlimit

					ORDER BY appat,apmat,nombres asc";
			}
			if(($curso=='TERCERO A')OR($curso=='TERCERO B'))
			{
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS LENGUAJE,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUECHUA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS QUECHUA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS SOCIALES,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS EDUFISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS ARTPLAST,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MATEMATICAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS CIENCIAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS FISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS QUIMICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS COSMO,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS RELIGION
				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.bimestre='$bimes' and n.gestion='$gestion' and n.final<$notlimit

					ORDER BY appat,apmat,nombres asc";
			}
			
			if(($curso=='CUARTO A')OR($curso=='CUARTO B'))
			{
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS LENGUAJE,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS SOCIALES,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS EDUFISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS ARTPLAST,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MATEMATICAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS CIENCIAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS FISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS QUIMICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS COSMO,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS RELIGION
				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.bimestre='$bimes' and n.gestion='$gestion' and n.final<$notlimit

					ORDER BY appat,apmat,nombres asc";
			}
			if(($curso=='QUINTO A')OR($curso=='QUINTO B')OR($curso=='SEXTO A')OR($curso=='SEXTO B'))
			{
				$sql='';
				$sql="select  DISTINCT n.idest,appat,apmat,nombres,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS LENGUAJE,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (A)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES (B)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='HISTORIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS HISTORIA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIVICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS CIVICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS EDUFISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (FOLCKLORE)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (DANZA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL (BANDA)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA3,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS ARTPLAST,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MATEMATICAS,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (SIS)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA1,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INFORMATICA (ELECTRO)' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA2,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='BIOLOGIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS BIOLOGIA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='GEOGRAFIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS GEOGRAFIA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='FISICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS FISICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='QUIMICA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS QUIMICA,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='COSMOVISIONES, FILOSOFIA Y SICOLOGIA' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS COSMO,
					(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<$notlimit and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS RELIGION
				
					FROM nota as n
					WHERE n.idcurso='$idcur' and n.bimestre='$bimes' and n.gestion='$gestion' and n.final<$notlimit

					ORDER BY appat,apmat,nombres asc";
			}

			$query  = $this->db->query($sql);		
			return $query->result();

		}
		

	}

	//busqueda
	private function _get_datatables_idcur($id)
	{

		$this->db->from($this->tabla);
		$this->db->where('idcurso',$id);

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

	public function count_filtered_byid($id)
	{
		$this->_get_datatables_idcur($id);
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

	public function get_print_sie($id)
	{
		$this->db->from('colegios');
		$this->db->where('colegio',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_print_observ($id,$gestion)
	{
	
		$this->db->from('estudiante');
		$this->db->where('idest',$id);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->row();
	}
	public function Peor_estudiantes($codigo,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres, estudiantes.appaterno,estudiantes.apmaterno,estudiantes.nombre ,estudiantes.id_est
				FROM nota_materia INNER JOIN estudiantes ON estudiantes.id_est=nota_materia.id_est 
				WHERE (nota_materia.notabi1>=1 AND nota_materia.notabi1<=50) AND nota_materia.codigo LIKE '".$codigo."%' AND nota_materia.gestion=".$gestion."
				GROUP BY nota_materia.id_est
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres, estudiantes.appaterno,estudiantes.apmaterno,estudiantes.nombre ,estudiantes.id_est
				FROM nota_materia INNER JOIN estudiantes ON estudiantes.id_est=nota_materia.id_est 
				WHERE (nota_materia.notabi2>=1 AND nota_materia.notabi2<=50) AND nota_materia.codigo LIKE '".$codigo."%' AND nota_materia.gestion=".$gestion."
				GROUP BY nota_materia.id_est
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres, estudiantes.appaterno,estudiantes.apmaterno,estudiantes.nombre ,estudiantes.id_est
				FROM nota_materia INNER JOIN estudiantes ON estudiantes.id_est=nota_materia.id_est 
				WHERE (nota_materia.notabi3>=1 AND nota_materia.notabi3<=50) AND nota_materia.codigo LIKE '".$codigo."%' AND nota_materia.gestion=".$gestion."
				GROUP BY nota_materia.id_est
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres, estudiantes.appaterno,estudiantes.apmaterno,estudiantes.nombre ,estudiantes.id_est
				FROM nota_materia INNER JOIN estudiantes ON estudiantes.id_est=nota_materia.id_est 
				WHERE (nota_materia.notabi4>=1 AND nota_materia.notabi4<=50) AND nota_materia.codigo LIKE '".$codigo."%' AND nota_materia.gestion=".$gestion."
				GROUP BY nota_materia.id_est
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function gest_est($codigo,$gestion)
	{
		$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom1 as final, estudiantes.appaterno , estudiantes.apmaterno, estudiantes.nombre
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est  
			WHERE nota_prom.codigo LIKE '".$codigo."%' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion." 
			order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function gest_est_6($cur,$nivel,$gestion)
	{
		$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE '%-".$nivel."-%' AND nota_prom.id_curso='".$cur."' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion."
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function bonojp($codigo)
	{
		$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom1 as nota1,nota_prom.prom2 as nota2,nota_prom.prom3 as nota3,nota_prom.retirado as retirado
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$codigo."%' AND nota_prom.gestion=".$gestion."
			order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function bonojp1($nivel,$cur,$gestion)
	{
		$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom1 as nota1,nota_prom.prom2 as nota2,nota_prom.prom3 as nota3,nota_prom.retirado as retirado
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '%".$nivel."%' AND nota_prom.id_curso='".$cur."' AND nota_prom.gestion=".$gestion."
			order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function gest_est_5to_6to($cur,$nivel,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom1 as final,estudiantes.ci
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE '%-".$nivel."-%' AND nota_prom.id_curso='".$cur."' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion."
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom2 as final,estudiantes.ci
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE '%-".$nivel."-%' AND nota_prom.id_curso='".$cur."' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion."
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom3 as final,estudiantes.ci
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE '%-".$nivel."-%' AND nota_prom.id_curso='".$cur."' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion."
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,estudiantes.id_est,nota_prom.prom4 as final,estudiantes.ci
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE '%-".$nivel."-%' AND nota_prom.id_curso='".$cur."' AND nota_prom.retirado=false AND nota_prom.gestion=".$gestion."
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function materiasPrimarias($codigo)
	{
		$sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=3 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR id_mat=12 OR id_mat=13 OR id_mat=20 OR id_mat=25";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function materiasSecundarias1($codigo)
	{
		 $sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=2 OR id_mat=4 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR id_mat=12  OR id_mat=14 OR id_mat=15 OR id_mat=16 OR id_mat=20 OR id_mat=24 OR id_mat=25";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function materiasSecundarias3($codigo)
	{
		 $sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=2 OR id_mat=4 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR id_mat=12  OR id_mat=14 OR id_mat=20 OR id_mat=22 OR id_mat=23 OR id_mat=24 OR id_mat=25";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function materiasSecundarias4($codigo)
	{
		 $sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=4 OR id_mat=5 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR id_mat=12  OR id_mat=14 OR id_mat=20 OR id_mat=22 OR id_mat=23 OR id_mat=24 OR id_mat=25";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function materiasSecundarias5($codigo)
	{
		 $sql="SELECT * FROM materias WHERE id_mat=1 OR id_mat=4 OR id_mat=5 OR id_mat=6 OR id_mat=7 OR id_mat=8 OR id_mat=9 OR id_mat=10 OR id_mat=11 OR id_mat=12 OR id_mat=18 OR id_mat=19 OR id_mat=20 OR id_mat=22 OR id_mat=23 OR id_mat=24 OR id_mat=25 OR id_mat=26";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function update_nota_prom($id,$data)
	{
		$this->db->where('id_est',$id);
		$this->db->update('nota_prom',$data);
	}
	public function update_nota_area($id,$id_area,$data)
	{
		$this->db->where('id_est',$id);
		$this->db->where('id_area',$id_area);
		$this->db->update('nota_area',$data);
	}

	public function nota_materia($id_mate,$id_est)
	{
		$sql="SELECT *
			FROM nota_materia 
			WHERE id_mat=".$id_mate." and id_est=".$id_est;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function nota_area($id_area,$id_est)
	{
		$sql="SELECT *
			FROM nota_area 
			WHERE id_area=".$id_area." and id_est=".$id_est;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_niveles($codigo)
	{
		$sql="SELECT *
			FROM niveles 
			WHERE codigo='".$codigo."'";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_colegio($id)
	{
		$sql="SELECT *
			FROM colegio 
			WHERE id_col=".$id;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_bimestre($id)
	{
		$sql="SELECT *
			FROM bimestre 
			WHERE id_bi=".$id;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_cursos($codigo)
	{
		$sql="SELECT *
			FROM cursos 
			WHERE codigo='".$codigo."'";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function suma_area_cl($codigo,$mat)
	{
		$sql="SELECT ((SUM(notabi1)/".$mat.")/(COUNT(id_est)/".$mat.")) as cl 
			FROM nota_area 
			WHERE codigo LIKE '".$codigo."%' 
			AND (id_area=1 OR id_area=2 OR id_area=3 OR id_area=4 OR id_area=5 OR id_area=6)";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function suma_area_ctp($codigo)
	{
		$sql="SELECT ((SUM(notabi1)/2)/(COUNT(id_est)/3)) as ctp
			FROM nota_area 
			WHERE codigo LIKE '".$codigo."%' AND (id_area=7 OR id_area=8 OR id_area=9 OR id_area=10)";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function suma_area_vtt($codigo,$mat)
	{
		$sql="SELECT ((SUM(notabi1)/".$mat.")/(COUNT(id_est)/3)) as vtt
			FROM nota_area 
			WHERE codigo LIKE '".$codigo."%' AND (id_area=11 OR id_area=12 OR id_area=13)";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function suma_area_cp($codigo,$mat)
	{
		$sql="SELECT  ((SUM(notabi1)/".$mat.")/(COUNT(id_est)/".$mat.")) as cp 
			FROM nota_area 
			WHERE codigo LIKE '".$codigo."%' AND (id_area=14 OR id_area=15)";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function cursos()
	{
		$sql="SELECT codigo, curso  FROM cursos 
				WHERE id_curso=1 OR id_curso=2 OR id_curso=4 OR id_curso=5 
				OR id_curso=7 OR id_curso=8 OR id_curso=10 OR id_curso=11 OR id_curso>=13";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate7($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14)
	{
		$sql="SELECT estudiantes.id_est,
		(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
		(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS quechua,
		(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
		(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
		(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
		(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
		(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
		(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
		(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS infromatica,
		(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS fisica,
		(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS  quimica,
		(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
		(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
		(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
		from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
		where estudiantes.id_est=".$id_est." AND (nota_materia.notabi1>=0 AND nota_materia.notabi1<=50 )";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS infromatica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS infromatica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS infromatica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS infromatica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate4($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$mat15,$bi,$gestion)
	//3Ros 
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi2 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi3 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS quechua,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi4 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate5($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$mat15,$gestion)
	{
		$sql="SELECT estudiantes.id_est,
		(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
		(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS quechua,
		(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
		(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
		(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
		(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
		(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
		(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
		(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS sitemas,
		(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
		(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS fisica,
		(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS  quimica,
		(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
		(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
		(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
		from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
		where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate6($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$bi,$gestion)
	{
		//4tos 
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS  quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate1($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate3($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$mat15,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS religuion,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi1 ELSE 0 END) AS estadisticas
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS religuion,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi2 ELSE 0 END) AS estadisticas
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS religuion,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi3 ELSE 0 END) AS estadisticas
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS religuion,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi4 ELSE 0 END) AS estadisticas
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_mate2($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$mat15,$mat16,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi2 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi3 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS  fisica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi4 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}

public function get_mate_primaria($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS matematicas,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS informatica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS maturales,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS religion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS matematicas,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS informatica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS maturales,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS religion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS matematicas,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS informatica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS maturales,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS religion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS matematicas,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS informatica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS maturales,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS religion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function kardex($appat,$apmat,$name,$gestion,$bimestre)
	{
		$sql="SELECT kardex.fecha,kardex.categoria,kardex.descripcion 
FROM kardex INNER JOIN estudiante ON estudiante.idest=kardex.idest
WHERE estudiante.appaterno='".$appat."' and estudiante.apmaterno='".$apmat."' and estudiante.nombres='".$name."' AND estudiante.gestion=".$gestion." AND kardex.gestion=".$gestion." AND kardex.bimestre=".$bimestre." 
ORDER BY kardex.fecha";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function kardexn($appat,$apmat,$name,$gestion,$bimestre)
	{
		$sql="SELECT kardex.fecha,kardex.categoria,kardex.descripcion 
FROM kardex INNER JOIN estudiante ON estudiante.idest=kardex.idest
WHERE estudiante.appaterno='".$appat."' and estudiante.apmaterno='".$apmat."' and estudiante.nombres='".$name."' AND estudiante.gestion=".$gestion." AND kardex.gestion=".$gestion." AND kardex.bimestre<=".$bimestre." 
ORDER BY kardex.fecha";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function get_5to_6to($id_est,$mat1,$mat2,$mat3,$mat4,$mat5,$mat6,$mat7,$mat8,$mat9,$mat10,$mat11,$mat12,$mat13,$mat14,$mat15,$mat16,$mat17,$mat18,$bi,$gestion)
	{
		if($bi==1){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi1 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi1 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi1 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi1 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi1 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi1 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi1 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi1 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi1 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi1 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi1 ELSE 0 END) AS estadisticas,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi1 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi1 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi1 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi1 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi1 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat17." THEN nota_materia.notabi1 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat18." THEN nota_materia.notabi1 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==2){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi2 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi2 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi2 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi2 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi2 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi2 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi2 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi2 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi2 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi2 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi2 ELSE 0 END) AS estadisticas,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi2 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi2 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi2 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi2 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi2 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat17." THEN nota_materia.notabi2 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat18." THEN nota_materia.notabi2 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==3){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi3 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi3 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi3 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi3 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi3 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi3 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi3 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi3 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi3 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi3 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi3 ELSE 0 END) AS estadisticas,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi3 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi3 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi3 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi3 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi3 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat17." THEN nota_materia.notabi3 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat18." THEN nota_materia.notabi3 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		if($bi==4){
			$sql="SELECT estudiantes.id_est,
			(CASE WHEN nota_materia.id_mat =".$mat1." THEN nota_materia.notabi4 ELSE 0 END) AS lenguaje,
			(CASE WHEN nota_materia.id_mat =".$mat2." THEN nota_materia.notabi4 ELSE 0 END) AS ingles,
			(CASE WHEN nota_materia.id_mat =".$mat3." THEN nota_materia.notabi4 ELSE 0 END) AS sociales,
			(CASE WHEN nota_materia.id_mat =".$mat4." THEN nota_materia.notabi4 ELSE 0 END) AS historia,
			(CASE WHEN nota_materia.id_mat =".$mat5." THEN nota_materia.notabi4 ELSE 0 END) AS civica,
			(CASE WHEN nota_materia.id_mat =".$mat6." THEN nota_materia.notabi4 ELSE 0 END) AS geografia,
			(CASE WHEN nota_materia.id_mat =".$mat7." THEN nota_materia.notabi4 ELSE 0 END) AS edfisica,
			(CASE WHEN nota_materia.id_mat =".$mat8." THEN nota_materia.notabi4 ELSE 0 END) AS edmusica,
			(CASE WHEN nota_materia.id_mat =".$mat9." THEN nota_materia.notabi4 ELSE 0 END) AS artes,
			(CASE WHEN nota_materia.id_mat =".$mat10." THEN nota_materia.notabi4 ELSE 0 END) AS mate,
			(CASE WHEN nota_materia.id_mat =".$mat11." THEN nota_materia.notabi4 ELSE 0 END) AS estadisticas,
			(CASE WHEN nota_materia.id_mat =".$mat12." THEN nota_materia.notabi4 ELSE 0 END) AS sistemas,
			(CASE WHEN nota_materia.id_mat =".$mat13." THEN nota_materia.notabi4 ELSE 0 END) AS electronica,
			(CASE WHEN nota_materia.id_mat =".$mat14." THEN nota_materia.notabi4 ELSE 0 END) AS naturales,
			(CASE WHEN nota_materia.id_mat =".$mat15." THEN nota_materia.notabi4 ELSE 0 END) AS fisica,
			(CASE WHEN nota_materia.id_mat =".$mat16." THEN nota_materia.notabi4 ELSE 0 END) AS quimica,
			(CASE WHEN nota_materia.id_mat =".$mat17." THEN nota_materia.notabi4 ELSE 0 END) AS filosofia,
			(CASE WHEN nota_materia.id_mat =".$mat18." THEN nota_materia.notabi4 ELSE 0 END) AS religuion
			from estudiantes  INNER JOIN nota_materia ON estudiantes.id_est=nota_materia.id_est
			where estudiantes.id_est=".$id_est." AND nota_materia.gestion=".$gestion;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function update_nota_prom1($id,$data)
	{
		$this->db->where('id_est',$id);
		$this->db->update('nota_prom',$data);
	}

}
