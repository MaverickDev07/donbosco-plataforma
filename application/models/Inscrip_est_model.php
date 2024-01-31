<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Inscrip_est_model extends CI_Model {

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

	//otras funciones

	public function get_idunidad($id)
	{
		
		$this->db->from('estudiante');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_idunidad1($id)
	{
		
		$this->db->from('estudiante');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_padre($id)
	{
		
		$this->db->from('inscrip_padre');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}
	public function count_contrato($id)
	{
		 $this->db->select('COUNT(reguistro_contrato.id) as numero');
		$this->db->from('reguistro_contrato');
		$this->db->where('gestion',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_madre($id)
	{
		
		$this->db->from('inscrip_madre');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_tutor($id)
	{
		
		$this->db->from('inscrip_tutor');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_antidunidad($id)
	{
		
		$this->db->from('inscripcion');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_antUnidadEducativa($unidadEdu)
	{
		$this->db->from('colegios');
		$this->db->where('colegio', $unidadEdu);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_discapacida($id)
	{
		
		$this->db->from('inscrip_discap');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_nivel($table)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_direccion($id)
	{
		
		$this->db->from('inscrip_localizacion');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_idioma($id)
	{
		
		$this->db->from('inscrip_idioma');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_nacimiento($id)
	{
		
		$this->db->from('inscrip_nacimiento');
		$this->db->where('idest',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_idunid($id)
	{

		$this->db->from('curso');
		$this->db->where('idcurso',$id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_idunidAll()
	{
		$this->db->select('colegio');
		$this->db->from('colegios');
		$query = $this->db->get();
		return $query->result();
	}
 
	public function get_data_cole($cole)
	{

		$this->db->from('colegios');
		$this->db->where('colegio',$cole);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_contrato($gestion,$id_est)
	{

		$this->db->from('reguistro_contrato');
		$this->db->where('gestion',$gestion);
		$this->db->where('id_est',$id_est);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_direccion($gestion,$id_est)
	{

		$this->db->from('reguistro_direccion');
		$this->db->where('gestion',$gestion);
		$this->db->where('id_est',$id_est);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_factura($gestion, $id_est)
	{

		$this->db->from('reguistro_factura');
		$this->db->where('gestion', $gestion);
		$this->db->where('id_est', $id_est);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_data_nivel($col)
	{

		$this->db->from('nivel');
		$this->db->where('colegio',$col);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_data_nivel1($col)
	{

		$this->db->from('niveles');
		$this->db->where('id_col',$col);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_data_niveles($cod)
	{

		$this->db->from('niveles');
		$this->db->where('codigo',$cod);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_curso($col,$lev)
	{
		$this->db->select('curso');
		$this->db->from('curso');
		$this->db->where('colegio',$col);
		$this->db->where('nivel',$lev);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_data_cursos($cod)
	{
		$this->db->from('cursos');
		$this->db->where('codigo',$cod);
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_rows($idcamp,$table)
	{
		$this->db->select($idcamp);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_idcod_table($idcamp,$table)
	{
		$this->db->select($idcamp);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_data_idcurso($nivel,$curso,$inscole)
	{
		$this->db->select('idcurso');
		$this->db->from('curso');
		$this->db->where('curso',$curso);
		$this->db->where('nivel',$nivel);
		$this->db->where('colegio',$inscole);
		$query = $this->db->get();
		return $query->result();
	}
	public function save_usuario($data)
	{
		$this->db->insert('inscrip_usuario',$data);
		return $this->db->insert_id();
	}

	public function save_unidadeduca($data)
	{		
		$this->db->insert('inscrip_unidadeduca',$data);
		return $this->db->insert_id();
	}

	public function save_estudiante($data)
	{		
		$this->db->insert('estudiante',$data);
		return $this->db->insert_id();
	}

	public function save_inscripcion($data)
	{		
		$this->db->insert('inscripcion',$data);
		return $this->db->insert_id();
	}

	public function save_contrato($data)
	{		
		$this->db->insert('reguistro_contrato',$data);
		return $this->db->insert_id();
	}

	public function save_nota_prom($data)
	{		
		$this->db->insert('nota_prom',$data);
		return $this->db->insert_id();
	}

	public function save_reg_tutor($data)
	{		
		$this->db->insert('reguistro_tutor',$data);
		return $this->db->insert_id();
	}

	public function get_estu($app,$ama,$name)
	{		
		$this->db->from('estudiantes');
		$this->db->where('appaterno',$app);
		$this->db->where('apmaterno',$ama);
		$this->db->where('nombre',$name);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_estu1($app,$ama,$name)
	{		
		$this->db->from('estudiantes');
		$this->db->where('appaterno',$app);
		$this->db->where('apmaterno',$ama);
		$this->db->where('nombre',$name);
		$query = $this->db->get();
		return $query->row();
	}

	public function buscar_padres($app,$ama,$name)
	{		
		$this->db->from('padres');
		$this->db->where('appaterno',$app);
		$this->db->where('apmaterno',$ama);
		$this->db->where('nombre',$name);
		$query = $this->db->get();
		return $query->result();
	}

	public function save_reguistro_incripccion($data)
	{		
		$this->db->insert('reguistro_incripccion',$data);
		return $this->db->insert_id();
	}

	public function save_reguistro_pandemia($data)
	{		
		$this->db->insert('reguistro_pandemia',$data);
		return $this->db->insert_id();
	}

	public function save_abandonos($data)
	{		
		$this->db->insert('reguistro_abandono',$data);
		return $this->db->insert_id();
	}

	public function save_mes($data)
	{		
		$this->db->insert('reguistro_mes',$data);
		return $this->db->insert_id();
	}

	public function save_factura($data)
	{		
		$this->db->insert('reguistro_factura',$data);
		return $this->db->insert_id();
	}

	public function save_reguistro_colegio($data)
	{		
		$this->db->insert('reguistro_colegio',$data);
		return $this->db->insert_id();
	}

	public function save_facturacion($data)
	{		
		$this->db->insert('inscrip_factura',$data);
		return $this->db->insert_id();
	}

	public function save_nacimiento($data)
	{		
		$this->db->insert('inscrip_nacimiento',$data);
		return $this->db->insert_id();
	}

	public function save_cultura($data)
	{		
		$this->db->insert('reguistro_cultura',$data);
		return $this->db->insert_id();
	}

	public function save_discapacidad($data)
	{		
		$this->db->insert('reguistro_discapacidad',$data);
		return $this->db->insert_id();
	}

	public function save_direccion($data)
	{		
		$this->db->insert('reguistro_direccion',$data);
		return $this->db->insert_id();
	}

	public function save_nacimientos($data)
	{		
		$this->db->insert('reguistro_nacimiento',$data);
		return $this->db->insert_id();
	}

	public function save_discap($data)
	{		
		$this->db->insert('inscrip_discap',$data);
		return $this->db->insert_id();
	}

	public function save_localizacion($data)
	{		
		$this->db->insert('inscrip_localizacion',$data);
		return $this->db->insert_id();
	}

	public function save_idioma($data)
	{		
		$this->db->insert('inscrip_idioma',$data);
		return $this->db->insert_id();
	}

	public function save_salud($data)
	{		
		$this->db->insert('inscrip_salud',$data);
		return $this->db->insert_id();
	}

	public function save_internet1($data)
	{		
		$this->db->insert('reguistro_internet',$data);
		return $this->db->insert_id();
	}

	public function save_padres($data)
	{		
		$this->db->insert('padres',$data);
		return $this->db->insert_id();
	}

	public function save_trabajos($data)
	{		
		$this->db->insert('reguistro_trabajo',$data);
		return $this->db->insert_id();
	}

	public function save_transportes($data)
	{		
		$this->db->insert('reguistro_transporte',$data);
		return $this->db->insert_id();
	}

	public function save_servicios_basico($data)
	{		
		$this->db->insert('reguistro_servicios_basicos',$data);
		return $this->db->insert_id();
	}

	public function save_salud1($data)
	{		
		$this->db->insert('reguistro_salud',$data);
		return $this->db->insert_id();
	}

	public function save_visitaposta($data)
	{		
		$this->db->insert('reguistro_hospital',$data);
		return $this->db->insert_id();
	}
	
	public function save_servicios($data)
	{		
		$this->db->insert('inscrip_servicios',$data);
		return $this->db->insert_id();
	}

	public function save_internet($data)
	{		
		$this->db->insert('inscrip_internet',$data);
		return $this->db->insert_id();
	}

	public function save_trabajo($data)
	{		
		$this->db->insert('inscrip_trabajo',$data);
		return $this->db->insert_id();
	}

	public function save_transporte($data)
	{		
		$this->db->insert('inscrip_transporte',$data);
		return $this->db->insert_id();
	}

	public function save_abandono($data)
	{		
		$this->db->insert('inscrip_abandono',$data);
		return $this->db->insert_id();
	}

	public function save_padre($data)
	{		
		$this->db->insert('inscrip_padre',$data);
		return $this->db->insert_id();
	}

	public function save_madre($data)
	{		
		$this->db->insert('inscrip_madre',$data);
		return $this->db->insert_id();
	}

	public function save_tutor($data)
	{		
		$this->db->insert('inscrip_tutor',$data);
		return $this->db->insert_id();
	}

	public function get_data_unidadeduca($id)
	{
		$this->db->from('inscrip_unidadeduca');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_colegio($id)
	{
		$this->db->from('colegio');
		$this->db->where('id_col',$id);
		$query=$this->db->get();
		return $query->row();
	}
	
	public function cursosp($curso,$nivel)
	{
		$sql="SELECT inscripcion.idinscrip AS idins FROM estudiante
			INNER JOIN estudiantes ON estudiante.nombres=estudiantes.nombre
			INNER JOIN inscripcion ON inscripcion.idest=estudiante.idest
			INNER JOIN nota_prom ON nota_prom.id_est=estudiantes.id_est
			WHERE estudiantes.appaterno=estudiante.appaterno AND
				estudiante.apmaterno=estudiantes.apmaterno AND nota_prom.codigo LIKE '".$curso."%".$nivel."%'
			 ORDER BY estudiantes.appaterno ASC, estudiantes.apmaterno,estudiantes.nombre";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_data_nacimiento($id)
	{
		$this->db->from('inscrip_nacimiento');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_nacimientos($id)
	{
		$this->db->from('reguistro_nacimiento');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_estudiantes($id)
	{
		$this->db->from('estudiante');
		$this->db->where('idest',$id);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_data_estudiantes1($id)
	{
		$this->db->from('estudiantes');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_nota_prom1($id)
	{
		$this->db->from('nota_prom');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_estudiante($id)
	{
		$this->db->from('estudiantes');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_discap($id)
	{
		$this->db->from('inscrip_discap');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_discap1($id)
	{
		$this->db->from('reguistro_discapacidad');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_local($id)
	{
		$this->db->from('inscrip_localizacion');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_direccion1($id_est,$gestion)
	{
		$this->db->from('reguistro_direccion');
		$this->db->where('gestion',$gestion);
		$this->db->where('id_est',$id_est);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_idioma($id)
	{
		$this->db->from('inscrip_idioma');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_idioma1($id)
	{
		$this->db->from('reguistro_cultura');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_salud($id)
	{
		$this->db->from('inscrip_salud');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_salud1($id,$gestion)
	{
		$this->db->from('reguistro_salud');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_hospital($id,$gestion)
	{
		$this->db->from('reguistro_hospital');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_data_internet($id,$gestion)
	{
		$this->db->from('reguistro_internet');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_data_mes($id,$gestion)
	{
		$this->db->from('reguistro_mes');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_data_abandono1($id,$gestion)
	{
		$this->db->from('reguistro_abandono');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_data_tutores($id)
	{
		$this->db->from('reguistro_tutor');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->result();
	}
	public function get_data_servicios($id)
	{
		$this->db->from('inscrip_servicios');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_servicios1($id,$gestion)
	{
		$this->db->from('reguistro_servicios_basicos');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_net($id)
	{
		$this->db->from('inscrip_internet');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_trabajo($id)
	{
		$this->db->from('inscrip_trabajo');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_trabajo1($id,$gestion)
	{
		$this->db->from('reguistro_trabajo');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_transporte($id)
	{
		$this->db->from('inscrip_transporte');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_transporte1($id,$gestion)
	{
		$this->db->from('reguistro_transporte');
		$this->db->where('id_est',$id);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_abandono($id)
	{
		$this->db->from('inscrip_abandono');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_padre($id)
	{
		$this->db->from('inscrip_padre');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_padres($id)
	{
		$this->db->from('padres');
		$this->db->where('id',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_madre($id)
	{
		$this->db->from('inscrip_madre');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_tutor($id)
	{
		$this->db->from('inscrip_tutor');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_data_inscrip($id)
	{
		$this->db->from('inscripcion');
		$this->db->where('idinscrip',$id);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_data_inscrips($id_est,$gestion)
	{
		$this->db->from('reguistro_incripccion');
		$this->db->where('id_est',$id_est);
		$this->db->where('gestion',$gestion);
		$query=$this->db->get();
		return $query->row();
	}

	//********

	

	


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

	public function updates($tabla,$where,$data)
	{
		$this->db->update($tabla,$data,$where);
		return $this->db->affected_rows();
	}

	public function update_estudiantes($id,$data)
	{
		$this->db->where('id_est',$id);
		$this->db->update('estudiantes',$data);
	}
	// public function get_rows_nivel($table)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	// {
	// 	//$this->db->select('colegio');
	// 	$this->db->from($table);
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }

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

	
}

