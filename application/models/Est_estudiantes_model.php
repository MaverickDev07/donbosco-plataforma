<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Est_estudiantes_model extends CI_Model {

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
	public function get_data_contrato($gestion,$id_est)
	{

		$this->db->from('reguistro_contrato');
		$this->db->where('gestion',$gestion);
		$this->db->where('id_est',$id_est);
		$query = $this->db->get();
		return $query->row();
	}	
	public function get_compromiso($gestion,$id_est)
	{

		$this->db->from('compomiso');
		$this->db->where('gestion',$gestion);
		$this->db->where('id_est',$id_est);
		$query = $this->db->get();
		return $query->row();
	}	
	public function get_data_cursos($cod)
	{ 
		$this->db->from('cursos');
		$this->db->where('codigo',$cod);
		$query = $this->db->get();
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

	public function get_data_direccion($gestion,$id_est)
	{

		$this->db->from('reguistro_direccion');
		$this->db->where('gestion',$gestion);
		$this->db->where('id_est',$id_est);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_data_niveles($cod)
	{

		$this->db->from('niveles');
		$this->db->where('codigo',$cod);
		$query = $this->db->get();
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
	public function get_data_estudiantes($id)
	{
		$this->db->from('estudiante');
		$this->db->where('idest',$id);
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
	public function get_data_padres($id)
	{
		$this->db->from('padres');
		$this->db->where('id',$id);
		$query=$this->db->get();
		return $query->row();
	}
	
	public function boletin($id_est)
	{
		$this->db->from("boletin");
		$this->db->where('id_est',$id_est);
		$query=$this->db->get();
		return $query->row();
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

	function get_count_rows_est($idcamp,$table)
	{
		$this->db->select($idcamp);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_idcod_table_est($idcamp,$table)
	{
		$this->db->select($idcamp);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	public function save_curso($data)
	{		
		$this->db->insert($this->tabla,$data);
		return $this->db->insert_id();
	}

	public function get_idinscrip($id)
	{
		$this->db->select('idinscrip');
		$this->db->from('inscripcion');
		$this->db->where('idest',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_print_padre($idest)
	{		
		$this->db->from('inscrip_padre');
		$this->db->where('idest',$idest);
		$query=$this->db->get();
		if($query->num_rows()>0)
			return $query->row();
		else
			return 'vacio';
	}
	public function get_print_padres($id_padre)
	{		
		$this->db->from('padres');
		$this->db->where('id',$id_padre);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_print_tutores($tipo,$idest)
	{		
		$this->db->from('reguistro_tutor');
		$this->db->where('tipo',$tipo);
		$this->db->where('id_est',$idest);
		$query=$this->db->get();
		return $query->row();
	}
	public function get_studiante($id)
	{
		$this->db->from('estudiantes');
		$this->db->where('id_est',$id);
		$query=$this->db->get();
		return $query->row();
	}

	public function get_cur_student($id_est,$ges)
	{
		$this->db->select('estudiantes.*,nota_prom.codigo as cod_curso,nota_prom.id_curso as  id_curso,nota_prom.debe,nota_prom.repitente,nota_prom.cpse,nota_prom.boletin');   
		$this->db->from("nota_prom");
		$this->db->join('estudiantes', 'estudiantes.id_est = nota_prom.id_est','inner');
		$this->db->where('nota_prom.gestion',$ges);
		$this->db->where('nota_prom.id_est',$id_est);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_print_madre($idest)
	{		
		$this->db->from('inscrip_madre');
		$this->db->where('idest',$idest);
		$query=$this->db->get();
		if($query->num_rows()>0)
			return $query->row();
		else
			return 'vacio';
	}

	public function get_print_tutor($idest)
	{		
		$this->db->from('inscrip_tutor');
		$this->db->where('idest',$idest);
		$query=$this->db->get();
		if($query->num_rows()>0)
			return $query->row();
		else
			return 'vacio';
	}



	public function delete_by_id($idest,$idins)
	{		
	    $this->db->where('idest',$idest);
	    $this->db->delete('estudiante');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscripcion');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_abandono');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_discap');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_factura');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_idioma');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_internet');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_localizacion');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_madre');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_nacimiento');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_padre');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_salud');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_servicios');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_trabajo');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_transporte');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_tutor');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_unidadeduca');

	    $this->db->where('idinscrip',$idins);
	    $this->db->delete('inscrip_usuario');

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

	public function save_estud_new($data)
	{
		$this->db->insert($this->tabla,$data);
		return $this->db->insert_id();
	}

	public function save_estud_news($tabla,$data)
	{
		$this->db->insert($tabla,$data);
		return $this->db->insert_id();
	}

	public function get_rows_nivel($table)//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		//$this->db->select('colegio');
		$this->db->from($table);
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

	public function get_datatables_by_idcur($ges,$idcur,$nivel)
	{
		
		$this->_get_datatables_idcur($ges,$idcur,$nivel);
		if(isset($_POST['length'])) {
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		}
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
		$this->db->order_by("estudiantes.appaterno asc, estudiantes.apmaterno asc, estudiantes.nombre asc");
		$i = 0;
	
		foreach ($this->column as $item) // loop column 
		{
			if(isset($_POST['search']['value'])) // if datatable send POST for search
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
		$this->db->order_by("appaterno asc, apmaterno asc, nombres asc");
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
	public function get_nivel_colegio($nivel)
	{
		$this->db->select('niveles.codigo,niveles.turno,niveles.nivel,colegio.nombre,niveles.id_nivel');   
		$this->db->from("niveles");
		$this->db->join('colegio', 'colegio.id_col = niveles.id_col','inner');
		$this->db->where('niveles.codigo',$nivel);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_nacimiento($id_est)
	{
		$this->db->select('reguistro_nacimiento.*,YEAR(fnacimiento) as año');   
		$this->db->from("reguistro_nacimiento");
		$this->db->where('id_est',$id_est);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_direccion($id_est,$gestion)
	{  
		$this->db->from("reguistro_direccion");
		$this->db->where('id_est',$id_est);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_internet($id_est,$gestion)
	{  
		$this->db->from("reguistro_internet");
		$this->db->where('id_est',$id_est);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_servicio($id_est,$gestion)
	{
		$this->db->select("reguistro_servicios_basicos.*");  
		$this->db->from("reguistro_servicios_basicos");
		//$this->db->join('reguistro_internet', 'reguistro_internet.id_est = reguistro_servicios_basicos.id_est','inner');
		$this->db->where('reguistro_servicios_basicos.id_est',$id_est);
		$this->db->where('reguistro_servicios_basicos.gestion',$gestion);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_nivel_curso($id_nivel)
	{  
		$this->db->from("nivel_curso");
		$this->db->where('id_nivel',$id_nivel);
		$this->db->where('activo',true);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_rows_cursos($nivel)
	{
		$this->db->select('cursos.codigo, cursos.nombre');
		$this->db->from('nivel_curso');
		$this->db->join('cursos', 'cursos.codigo = nivel_curso.cod_curso','inner');
		$this->db->where('nivel_curso.activo',true);
		$this->db->like('nivel_curso.codigo', $nivel, 'both');
		$this->db->order_by("nivel_curso.cod_curso", "asc");
		$query = $this->db->get();
		return $query->result();
	}
	public function get_ant_colegio($gestion,$id_est)
	{  
		$this->db->from("reguistro_colegio");
		$this->db->where('id_est',$id_est);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_cantidad_genero($gestion,$genero,$nivel,$curso)
	{  
		$this->db->select('COUNT(nota_prom.id_est) as cantidad');
		$this->db->from('nota_prom');
		$this->db->join('estudiantes', 'estudiantes.id_est = nota_prom.id_est','inner');
		$this->db->where('nota_prom.gestion',$gestion);
		$this->db->where('estudiantes.genero',$genero);
		$d=$curso.'-'.$nivel;
	    $this->db->like('nota_prom.codigo', $d);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_padres($id_est,$tipo)
	{  
		$this->db->select('padres.*');
		$this->db->from("reguistro_tutor");
		$this->db->join('padres', 'padres.id = reguistro_tutor.id_padre','inner');
		$this->db->where('reguistro_tutor.id_est',$id_est);
		$this->db->where('reguistro_tutor.tipo',$tipo);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_print_curso_pdf($id)
	{
		$this->db->from('curso');
		$this->db->where('idcurso',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_cursos($id)
	{
		$this->db->from('cursos');
		$this->db->where('codigo',$id);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_print_genero_pdf($genero,$nivel,$colegio,$gestion)
	{
		
    	$this->db->select('COUNT(idest) as maximo');
		$this->db->from('estudiante');
		$this->db->where('genero',$genero);
		$this->db->where('nivel',$nivel);
		$this->db->where('colegio',$colegio);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		$cnt = $query->row_array(); 
		return $cnt['maximo']; 
	}

	public function get_print_fecha_pdf($idest)
	{
		
    	$this->db->select('*');
		$this->db->from('inscrip_nacimiento');
		$this->db->where('idest',$idest);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		}
		else{
			return '0';
		}
	}

	public function get_print_generoCurso_pdf($genero,$nivel,$colegio,$gestion,$curso)
	{
		
    	$this->db->select('COUNT(idest) as maximo');
		$this->db->from('estudiante');
		$this->db->where('genero',$genero);
		$this->db->where('nivel',$nivel);
		$this->db->where('idcurso',$curso);
		$this->db->where('colegio',$colegio);
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		$cnt = $query->row_array(); 
		return $cnt['maximo']; 
	}
	
	public function get_colegio()
	{
		$this->db->from('colegios');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_nivel_estudiante($gestion)
	{
		$this->db->DISTINCT('nivel');
		$this->db->from('estudiante');
		$this->db->where('gestion',$gestion);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_curso_estudiante($gestion,$colegio)
	{
		$this->db->DISTINCT('cu.corto,cu.idcurso,cu.curso');
		$this->db->from('estudiante as est');
		$this->db->join('curso cu','cu.idcurso=est.idcurso');
		$this->db->where('gestion',$gestion);
		$this->db->group_by('cu.corto');
		$query = $this->db->get();
		return $query->row();
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
	public function get_idinscrip_localizacion($idest)
	{
		$this->db->select('*');
		$this->db->from('inscrip_localizacion');
		$this->db->where('idest',$idest);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		}
		else{
			return '0';
		}
		
	} 

	public function get_promedio_nota($nivel,$gestion)
	{
		$sql="select estudiante.idest,estudiante.appaterno,estudiante.apmaterno,estudiante.nombres, estudiante.genero,
				( select (SUM(nota.final)/(COUNT(nota.idnota))) as nombres 
					from nota 
						where estudiante.idest=nota.idest AND 
						nota.gestion=".$gestion." AND 
						nota.final!='' ) as nota,
				( select curso.corto as curso 
						from curso 
							where estudiante.idcurso=curso.idcurso ) as curso  
				from estudiante 
				WHERE estudiante.nivel='".$nivel."' 
				AND estudiante.gestion=".$gestion;

				/*$sql="select
				( select (SUM(nota.final)/(COUNT(nota.idnota))) as nombres ,
					from nota 
						where estudiante.idest=nota.idest AND 
						nota.gestion=".$gestion." AND 
						nota.final!='' ) as nota
				from estudiante 
				WHERE estudiante.nivel='".$nivel."' 
				AND estudiante.gestion=".$gestion;*/
		$query  = $this->db->query($sql);
		return $query->result();

	}
	public function get_estudiante_telefono($idest)
	{
		$this->db->select('inscrip_localizacion.fono as foest,inscrip_localizacion.celular as ceest,inscrip_madre.ofifono as fma,inscrip_madre.celular as cma,inscrip_padre.ofifono as fpa,inscrip_padre.celular as cpa,inscrip_tutor.ofifono as ftu,inscrip_tutor.celular as ctu');
		$this->db->from('inscrip_localizacion');
		$this->db->join('inscrip_madre','inscrip_localizacion.idest=inscrip_madre.idest');
		$this->db->join('inscrip_tutor','inscrip_tutor.idest=inscrip_madre.idest');
		$this->db->join('inscrip_padre','inscrip_padre.idest=inscrip_madre.idest');
		$this->db->where('inscrip_localizacion.idest',$idest);
		$query = $this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		}
		else{
			return '0';
		}
	}

	


	
}
