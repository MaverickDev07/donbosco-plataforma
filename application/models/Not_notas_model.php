<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Not_notas_model extends CI_Model
{

	var $tabla = 'nota';
	var $column = array('idnota', 'appat', 'apmat', 'nombres', 'ser1', 'ser2', 'ser3', 'promser', 'sab1', 'sab2', 'sab3', 'sab4', 'sab5', 'sab6', 'promsab', 'hac1', 'hac2', 'hac3', 'hac4', 'hac5', 'hac6', 'promhac', 'dec1', 'dec2', 'dec3', 'promdec', 'autoser', 'autodec', 'final'); //set column field database for datatable orderable
	var $column_search = array('idnota', 'appat', 'apmat', 'nombres'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('appat' => 'asc', 'apmat' => 'asc', 'nombres' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function count_filtered()
	{
		//$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_nivel_colegio($nivel)
	{
		$this->db->select('niveles.codigo,niveles.turno,niveles.nivel,colegio.nombre,niveles.id_nivel');
		$this->db->from("niveles");
		$this->db->join('colegio', 'colegio.id_col = niveles.id_col', 'inner');
		$this->db->where('niveles.id_nivel', $nivel);
		$query = $this->db->get();
		return $query->row();
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

	function get_idcod_table2($table, $id)
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

	function get_count_rows2($table, $id)
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

	function get_prof_row($p, $m, $n, $t)
	{
		$this->db->from($t);
		$this->db->where('appaterno', $p);
		$this->db->where('apmaterno', $m);
		$this->db->where('nombres', $n);
		$query = $this->db->get();
		return $query->result();
	}
	function get_prof1($p, $m, $n)
	{
		$this->db->from('profesores');
		$this->db->where('appaterno', $p);
		$this->db->where('apmaterno', $m);
		$this->db->where('nombre', $n);
		$query = $this->db->get();
		return $query->row();
	}
	public function save_nota($data)
	{
		$this->db->insert('nota', $data);
		return $this->db->insert_id();
	}

	public function save_indi($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	function delete_by_id($id)
	{
		$this->db->where('idest', $id);
		$this->db->delete($this->tabla);
	}

	function get_by_id($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('idest', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function indi_if($table, $idmat, $bimes, $gestion)
	{
		$this->db->from($table);
		$this->db->where('idmat', $idmat);
		$this->db->where('bimestre', $bimes);
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->row();
	}

	public function indi_if_exit($table, $idmat, $bimes, $gestion)
	{
		$this->db->from($table);
		$this->db->where('idmat', $idmat);
		$this->db->where('bimestre', $bimes);
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function update($where, $data)
	{
		$this->db->update($this->tabla, $data, $where);
		return $this->db->affected_rows();
	}

	public function get_nivel($idp, $gestion) //DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->select('*');
		$this->db->from('asiginar_profesorm');
		$this->db->where('id_prof', $idp);
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_cursos($idp) //DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->select('*');
		$this->db->from('cursos');
		$this->db->where('codigo', $idp);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_cursos1($idp) //DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->select('*');
		$this->db->from('cursos');
		$this->db->where('codigo', $idp);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_rows_nivel($table, $idp) //DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->distinct();
		$this->db->select('nivel');
		$this->db->from($table);
		$this->db->where('idprof', $idp);
		$query = $this->db->get();
		return $query->result();
	}
	public function niveles($idp) //DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->select('*');
		$this->db->from('niveles');
		$this->db->where('id_nivel', $idp);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_tema_tarea($id_prof, $cod_nivel, $cod_curso, $gestion, $id_mat)
	{
		$this->db->select('tareas.id as id_tarea, tareas.id_tema,tareas.fechaentrega as fecha,temas.tema');
		$this->db->from("temas");
		$this->db->join('tareas ', 'tareas.id_tema=temas.id');
		$this->db->where('temas.id_prof', $id_prof);
		$this->db->where('temas.cod_nivel', $cod_nivel);
		$this->db->where('temas.cod_curso', $cod_curso);
		$this->db->where('temas.gestion', $gestion);
		$this->db->where('temas.id_mat', $id_mat);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_nota_tarea($id_tema, $id_tarea, $id_est, $gestion, $tipo, $bi)
	{
		$this->db->select('nota_tareas.*');
		$this->db->from("nota_tareas");
		$this->db->join('tarea_subir ', 'tarea_subir.id=nota_tareas.id_tarea');
		$this->db->where('tarea_subir.id_tema', $id_tema);
		$this->db->where('tarea_subir.id_tarea', $id_tarea);
		$this->db->where('tarea_subir.id_est', $id_est);
		$this->db->where('tarea_subir.gestion', $gestion);
		$this->db->where('nota_tareas.id_bi', $bi);
		//$this->db->where('tarea_subir.estado',1);
		$this->db->where('nota_tareas.tipo', $tipo);
		$this->db->order_by("nota_tareas.id", "DESC");
		$query = $this->db->get();
		return $query->row();
	}
	public function get_nivel_cod($cod)
	{
		$sql = "SELECT colegio.nombre AS colegio,colegio.id_col AS idcol,niveles.id_nivel,niveles.nivel,niveles.turno FROM niveles
			INNER JOIN colegio ON niveles.id_col=colegio.id_col
			WHERE codigo='" . $cod . "'";
		$query  = $this->db->query($sql);
		return $query->result();
	}


	public function get_cole($idp) //DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->select('*');
		$this->db->from('colegio');
		$this->db->where('id_col', $idp);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_level($table, $level) //DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$this->db->where('nivel', $level);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_curso($table, $idp, $level)
	{
		$this->db->distinct();
		$this->db->select('idcurso,curso');
		$this->db->from($table);
		$this->db->where('idprof', $idp);
		$this->db->where('nivel', $level);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_materia($table, $idprof, $nivel, $curso)
	{
		$this->db->select('idmat,materia');
		$this->db->from($table);
		$this->db->where('idprof', $idprof);
		$this->db->where('nivel', $nivel);
		$this->db->where('idcurso', $curso);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_idcurso($nivel, $curso)
	{

		//print_r($nivel."-".$curso);

		$this->db->select('idcurso');
		$this->db->from('curso');
		$this->db->where('nivel', $nivel);
		$this->db->where('curso', $curso);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_idmat($table, $idprof, $idcurso, $nivel, $materia)
	{
		$this->db->select('idmat');
		$this->db->from($table);
		$this->db->where('nivel', $nivel);
		$this->db->where('idprof', $idprof);
		$this->db->where('idcurso', $idcurso);
		$this->db->where('materia', $materia);

		$query = $this->db->get();
		return $query->result();
	}

	public function get_estud($table, $idcurso, $gestion)
	{
		$this->db->from($table);
		$this->db->where('gestion', $gestion);
		$this->db->where('idcurso', $idcurso);
		$query = $this->db->get();
		return $query->result();
	}

	public function if_exit_nota($table, $idest, $idmat, $bimestre)
	{
		$this->db->from($table);
		$this->db->where('idest', $idest);
		$this->db->where('idmat', $idmat);
		$this->db->where('bimestre', $bimestre);
		$query = $this->db->get();
		return $query->num_rows();
	}



	public function get_datatables_by_all($idmat, $idcur, $idprof, $gestion, $bimestre)
	{

		$this->_get_datatables_all($idmat, $idcur, $idprof, $gestion, $bimestre);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}

	//busqueda
	private function _get_datatables_all($idmat, $idcur, $idprof, $gestion, $bimestre)
	{

		$this->db->from($this->tabla);
		$this->db->where('idmat', $idmat);
		$this->db->where('idcurso', $idcur);
		$this->db->where('idprof', $idprof);
		$this->db->where('gestion', $gestion);
		$this->db->where('bimestre', $bimestre);

		$i = 0;

		foreach ($this->column as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item;
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	public function count_filtered_byid($idmat, $idcur, $idprof, $gestion, $bimestre)
	{
		$this->_get_datatables_all($idmat, $idcur, $idprof, $gestion, $bimestre);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_print_estud_pdf($id)
	{
		$this->db->from($this->tabla);
		$this->db->where('idcurso', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_print_curso_pdf($id)
	{
		$this->db->from('curso');
		$this->db->where('idcurso', $id);
		$query = $this->db->get();
		return $query->row();
	}

	//impresion

	public function get_all_idmat($idmat)
	{
		//print_r($idmat);

		$this->db->from('materia');
		$this->db->where('idmat', $idmat);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_all_idcur($idcurso)
	{
		$this->db->select('colegio');
		$this->db->from('curso');
		$this->db->where('idcurso', $idcurso);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_prof($idprof)
	{
		$this->db->from('profesor');
		$this->db->where('idprof', $idprof);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_lista($idmat, $bi, $gestion)
	{
		$this->db->from('nota');
		$this->db->where('idmat', $idmat);
		$this->db->where('bimestre', $bi);
		$this->db->where('gestion', $gestion);
		$this->db->order_by("appat", "asc");
		$this->db->order_by("apmat", "asc");
		$this->db->order_by("nombres", "asc");
		$query = $this->db->get();
		return $query->result();
	}
	public function get_bimestre($id)
	{
		$this->db->from('bimestre');
		$this->db->where('id_bi', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_estadistic($idmat, $bi, $gestion)
	{
		$this->db->from('final');
		$this->db->where('idmat', $idmat);
		$this->db->where('bimestre', $bi);
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function update_indi($where, $table, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function get_indik($table, $bi, $idmat, $gestion)
	{
		$this->db->from($table);
		$this->db->where('idmat', $idmat);
		$this->db->where('bimestre', $bi);
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function materia_lis($gestion)
	{
		$this->db->select('idmat, idprof,idcurso,gestion');
		$this->db->from('materia');
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function generar_estudiante($gestion)
	{
		$this->db->select('idest, idcurso, nivel,gestion');
		$this->db->from('estudiante');
		$this->db->where('gestion', $gestion);
		$this->db->order_by("appaterno asc, apmaterno asc, nombres asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function insertar_asig_mate($idcurso, $idmat, $idprof, $idgestion)
	{
		$data = array(
			'id_curso' => $idcurso,
			'id_mat' => $idmat,
			'id_prof' => $idprof,
			'id_gestion' => $idgestion,
		);
		$this->db->insert('asignar_mate', $data);
		return $this->db->insert_id();
	}
	public function insertar_asig_estu($idest, $idcurso, $idnivel, $idgestion)
	{
		$data = array(
			'debe' => '',
			'repitente' => '',
			'vive' => '',
			'id_est' => $idest,
			'id_curso' => $idcurso,
			'id_gestion' => $idgestion,
			'id_nivel' => $idnivel,
		);
		$this->db->insert('asignar_estu', $data);
		return $this->db->insert_id();
	}
	public function get_prof_estudiante($gestion, $idprof, $idmat, $idcurso)
	{
		$this->db->SELECT('estudiante.idest as id');
		$this->db->SELECT("CONCAT(estudiante.appaterno ,' ',estudiante.apmaterno,' ',estudiante.nombres) as nombres", false);
		$this->db->from('asignar_estu');
		$this->db->join('asignar_mate ', 'asignar_estu.id_curso=asignar_mate.id_curso');
		$this->db->join('estudiante', 'estudiante.idest=asignar_estu.id_est');
		$this->db->where('asignar_estu.id_gestion', $gestion);
		$this->db->where('asignar_mate.id_prof', $idprof);
		$this->db->where('asignar_mate.id_mat', $idmat);
		$this->db->where('asignar_mate.id_curso', $idcurso);
		$this->db->group_by('estudiante.appaterno', 'ASC', 'estudiante.apmaterno', 'ASC', 'estudiante.nombres', 'ASC');
		$query = $this->db->get();
		return $query->result();
		/*$sql="SELECT estudiante.idest as id,
		 CONCAT(estudiante.appaterno ,' ',estudiante.apmaterno,' ',estudiante.nombres) as nombres
		FROM asignar_estu INNER JOIN 
			asignar_mate on asignar_estu.id_curso=asignar_mate.id_curso INNER JOIN 
			estudiante on estudiante.idest=asignar_estu.id_est 
			WHERE asignar_mate.id_prof='".$idprof."'  AND 
			asignar_mate.id_mat='".$idmat."' ' AND 
			asignar_mate.id_curso='".$idcurso."'  AND 
			asignar_estu.id_gestion=".$gestion."  
			ORDER BY estudiante.appaterno ASC , estudiante.apmaterno ASC, estudiante.nombres ASC";
		$query  = $this->db->query($sql);
		return $query->result();*/
	}
	public function get_curso($curso)
	{

		$this->db->from('curso');
		$this->db->where('idcurso', $curso);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_materias($materia)
	{

		$this->db->from('materia');
		$this->db->where('idmat', $materia);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_profe($pro)
	{
		$this->db->SELECT('ci');
		$this->db->SELECT("CONCAT(appaterno ,' ',apmaterno,' ',nombres) as nombres", false);
		$this->db->from('profesor');
		$this->db->where('idprof', $pro);

		$query = $this->db->get();
		return $query->result();
	}
	public function get_profes($pro)
	{
		$this->db->SELECT('ci');
		$this->db->SELECT("CONCAT(appaterno ,' ',apmaterno,' ',nombre) as nombres", false);
		$this->db->from('profesores');
		$this->db->where('id_prof', $pro);

		$query = $this->db->get();
		return $query->row();
	}
	public function profesor_list()
	{
		$this->db->select('*');
		$this->db->from('profesor');
		$query = $this->db->get();
		return $query->result();
	}
	public function estudiante_list($gestion)
	{
		$this->db->select('*');
		$this->db->from('estudiante');
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}
	public function materias_lis()
	{
		$this->db->select('*');
		$this->db->from('materias');
		$query = $this->db->get();
		return $query->result();
	}
	public function areas($id)
	{
		$this->db->select('areas.nombre as area,campo.nombre as campo, materias.nombre as materia,areas.id_area');
		$this->db->from('areas');
		$this->db->join('materias ', 'materias.id_area=areas.id_area');
		$this->db->join('campo ', 'campo.id_campo=areas.id_campo');
		$this->db->where('materias.id_mat', $id);
		$query = $this->db->get();
		return $query->row();
	}
	public function asiginar_curso_lis()
	{
		$this->db->select('*');
		$this->db->from('asiginar_curso');
		$query = $this->db->get();
		return $query->result();
	}
	public function materiass($id)
	{
		$this->db->select('*');
		$this->db->from('materias');
		$this->db->where('id_mat', $id);
		$query = $this->db->get();
		return $query->row();
	}
	public function bimestre_list()
	{
		$this->db->select('*');
		$this->db->from('bimestre');
		$query = $this->db->get();
		return $query->result();
	}
	public function areas_list()
	{
		$this->db->select('*');
		$this->db->from('areas');
		$query = $this->db->get();
		return $query->result();
	}
	public function insertar_estudiantes($rude, $ci, $extension, $appaterno, $apmaterno, $nombre, $genero, $codigo, $foto)
	{
		$data = array(
			'rude' => $rude,
			'ci' => $ci,
			'extension' => $extension,
			'appaterno' => $appaterno,
			'apmaterno' => $apmaterno,
			'nombre' => $nombre,
			'genero' => $genero,
			'codigo' => $codigo,
			'foto' => $foto,
		);
		$this->db->insert('estudiantes', $data);
		return $this->db->insert_id();
	}
	public function insertar_profesores($item, $ci, $extension, $appaterno, $apmaterno, $nombre, $direccion, $telefono, $celular, $genero, $foto)
	{
		$data = array(
			'item' => $item,
			'ci' => $ci,
			'extension' => $extension,
			'appaterno' => $appaterno,
			'apmaterno' => $apmaterno,
			'nombre' => $nombre,
			'direccion' => $direccion,
			'telefono' => $telefono,
			'celular' => $celular,
			'genero' => $genero,
			'foto' => $foto,
		);
		$this->db->insert('profesores', $data);
		return $this->db->insert_id();
	}


	public function insertar_notas($codigo, $notabi1, $notabi2, $notabi3, $notabi4, $notafinal, $gestion, $id_area, $id_bi, $id_est)
	{
		$data = array(
			'codigo' => $codigo,
			'notabi1' => $notabi1,
			'notabi2' => $notabi2,
			'notabi3' => $notabi3,
			'notabi4' => $notabi4,
			'notafinal' => $notafinal,
			'gestion' => $gestion,
			'id_area' => $id_area,
			'id_bi' => $id_bi,
			'id_est' => $id_est,
		);
		$this->db->insert('notas', $data);
		return $this->db->insert_id();
	}
	public function insertar_asiginar_materiacu($codigo, $id_asg_cur, $id_mat)
	{
		$data = array(
			'codigo' => $codigo,
			'id_asg_cur' => $id_asg_cur,
			'id_mat' => $id_mat,
		);
		$this->db->insert('asiginar_materiacu', $data);
		return $this->db->insert_id();
	}
	public function list_estudiante($gestion)
	{
		$this->db->SELECT('estudiante.*,cursos.codigo as cod');
		$this->db->from('estudiante');
		$this->db->join('curso ', 'curso.idcurso=estudiante.idcurso');
		$this->db->join('cursos', 'cursos.nombre=curso.curso');
		$this->db->where('estudiante.gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}
	public function nota_temas($gestion, $contar, $id_prof, $tipo)
	{
		$this->db->SELECT('temas.*');
		$this->db->from('nota_tareas');
		$this->db->join('temas ', 'temas.id=nota_tareas.id_tema');
		$this->db->where('nota_tareas.gestion', $gestion);
		$this->db->where('nota_tareas.contar', $contar);
		//$this->db->where('nota_tareas.id_est',$id_est);
		$this->db->where('nota_tareas.id_prof', $id_prof);
		$this->db->where('nota_tareas.tipo', $tipo);
		$query = $this->db->get();
		return $query->row();
	}
	public function list_col($id)
	{
		$this->db->SELECT('colegio.nombre as col');
		$this->db->from('niveles');
		$this->db->join('colegio ', 'colegio.id_col=niveles.id_col');
		$this->db->where('niveles.id_nivel', $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function getNotasTrimestre($id_trimestre, $id_asg_prof, $gestion=2021) {
    $sql = "SELECT
      ES.id_est,
      ES.nombre,
      ES.appaterno,
      ES.apmaterno,
      ES.ci,
      ES.genero,
      NT.*
      FROM nota_trimestre as NT
      INNER JOIN estudiantes as ES
      ON ES.id_est = NT.id_est
      WHERE
      NT.id_asg_prof = $id_asg_prof AND
      NT.id_bi = $id_trimestre AND
      NT.gestion = $gestion AND
      NT.total > 0
      ORDER BY ES.appaterno, ES.apmaterno, ES.nombre ASC";
    $query = $this->db->query($sql);

    return $query->result();
  }

	//public function insertar_nota_bi($codigo,$estado,$ser1,$ser2,$ser3,$pmser,$saber1,$saber2,$saber3,$saber4,$saber5,$saber6,$saberac,$pmsaber,$hacer1,$hacer2,$hacer3,$hacer4,$hacer5,$hacer6,$hacerac,$pmhacer,$dec1,$dec2,$dec3,$pmdecidir,$ser_est,$dec_est,$final,$gestion,$id_asg_prof,$id_bi,$id_est)
	public function insertar_nota_bi($codigo, $gestion, $id_asg_prof, $id_bi, $id_est)
	{
		$data = array(
			'codigo' => $codigo,
			'gestion' => $gestion,
			'id_asg_prof' => $id_asg_prof,
			'id_bi' => $id_bi,
			'id_est' => $id_est,
		);
		$this->db->insert('nota_bimestre', $data);
		return $this->db->insert_id();
	}
	public function insertar_nota_prom($codigo, $debe, $repitente, $vive, $gestion, $id_est)
	{
		$data = array(
			'codigo' => $codigo,
			'debe' => $debe,
			'repitente' => $repitente,
			'vive' => $vive,
			'gestion' => $gestion,
			'id_est' => $id_est,
		);
		$this->db->insert('nota_prom', $data);
		return $this->db->insert_id();
	}
	public function profmate($cu, $ni)
	{
		/*$id=$cu.'-%'.$ni.'%';
		$this->db->SELECT('*');
		$this->db->from('asiginar_materiacu');
		$this->db->like('codigo','1%-SM%');
		$query = $this->db->get();
		return $query->result();*/
		$sql = "SELECT * FROM asiginar_profesorm WHERE codigo LIKE '" . $cu . '-' . $ni . "%'";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function p_materias($id, $cu)
	{
		$sql = "SELECT * FROM asiginar_profesorm WHERE id_prof=" . $id . " and  codigo LIKE '" . $cu . "%'";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function prof_materias($id, $cu, $gestion)
	{
		$sql = "SELECT * FROM asiginar_profesorm WHERE id_prof=" . $id . " and  codigo LIKE '" . $cu . "%' AND gestion=" . $gestion;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function p_cursos($id, $n)
	{
		$sql = "SELECT * FROM asiginar_profesorm WHERE id_prof=" . $id . " and  codigo LIKE '%" . $n . "%' ORDER BY codigo ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function prof_cursos($id, $n, $gestion)
	{
		$sql = "SELECT * FROM asiginar_profesorm WHERE id_prof=" . $id . " and  codigo LIKE '%-" . $n . "-%-%-%-%-%-%' AND gestion=" . $gestion . " ORDER BY codigo ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function estudiante_curso($gestion, $bimestre, $id)
	{
		$sql = "SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres, estudiantes.id_est,  nota_bimestre.id_not_bi as id, nota_bimestre.id_asg_prof as idprof FROM nota_bimestre INNER JOIN estudiantes ON  estudiantes.id_est=nota_bimestre.id_est WHERE nota_bimestre.gestion=" . $gestion . " and nota_bimestre.id_bi= " . $bimestre . " and nota_bimestre.id_asg_prof=" . $id . "  ORDER BY estudiantes.appaterno ASC,estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function list_cod_prof($gestion, $curso, $nivel, $materia, $id_prof)
	{
		$this->db->select('asiginar_profesorm.*');
		$this->db->from("asiginar_profesorm");
		$this->db->join('asiginar_materiacu', 'asiginar_materiacu.id_asg_mate = asiginar_profesorm.id_asg_mate', 'inner');
		$this->db->where('asiginar_materiacu.id_mat', $materia);
		$this->db->where('asiginar_profesorm.id_prof', $id_prof);
		$this->db->where('asiginar_profesorm.gestion', $gestion);
		$d = $curso . '-' . $nivel;
		$this->db->like('asiginar_profesorm.codigo', $d, 'after');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_curso_student($gestion, $bimestre, $id)
	{
		$this->db->select('estudiantes.appaterno ,estudiantes.apmaterno,estudiantes.nombre,nota_bimestre.id_not_bi as id,nota_bimestre.*,estudiantes.id_est as est');
		$this->db->from("estudiantes");
		$this->db->join('nota_prom', 'estudiantes.id_est = nota_prom.id_est', 'inner');
		$this->db->join('nota_bimestre', 'nota_bimestre.id_est = estudiantes.id_est', 'inner');
		$this->db->where('nota_bimestre.id_bi', $bimestre);
		$this->db->where('nota_bimestre.gestion', $gestion);
		$this->db->where('nota_prom.gestion', $gestion);
		$this->db->where('nota_prom.retirado', false);
		$this->db->where('nota_bimestre.id_asg_prof', $id);
		$this->db->order_by("estudiantes.appaterno asc, estudiantes.apmaterno asc, estudiantes.nombre asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function notamateria($gestion, $id_est, $id_mat)
	{
		$sql = "SELECT * FROM nota_materia
		 WHERE nota_materia.gestion=" . $gestion . " and nota_materia.id_est= " . $id_est . " and nota_materia.id_mat=" . $id_mat;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_idasp($gestion, $prof, $idmaterias)
	{
		//$sql="SELECT * FROM asiginar_profesorm  WHERE gestion=".$gestion." and id_prof=".$prof." and    (codigo LIKE '".$cursos."%".$nivel."%".$materia."%')";
		$sql = "SELECT * FROM asiginar_profesorm  WHERE gestion=" . $gestion . " and id_prof=" . $prof . " and id_asg_mate=" . $idmaterias;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_asig_materia($gestion, $materia, $cursos, $nivel, $idp)
	{
		$sql = "SELECT * FROM asiginar_materiacu  
			INNER JOIN asiginar_profesorm ON asiginar_materiacu.id_asg_mate=asiginar_profesorm.id_asg_mate
			WHERE asiginar_profesorm.id_prof=$idp AND asiginar_materiacu.codigo LIKE '" . $cursos . "-%-" . $nivel . "-%-%-" . $materia . "'";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function list_asig($cod, $mat, $id_prof)
	{
		$sql = "SELECT * FROM asiginar_profesorm  WHERE  codigo LIKE '" . $cod . "%-%-%-" . $mat . "-%' and id_prof=" . $id_prof;
		//$this->db->where('id_prof',$id_prof);
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function list_test($asg)
	{
		$sql = "SELECT * FROM test__teachers  WHERE  teacher_id=" . $asg;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function list_test_estu($id_test, $curso)
	{
		$sql = "SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,test__students.note as nota FROM test__students INNER JOIN estudiantes ON test__students.student_id=estudiantes.id_est INNER JOIN nota_prom ON nota_prom.id_est=estudiantes.id_est WHERE test__students.test_id=" . $id_test . " AND nota_prom.codigo LIKE '" . $curso . "%' ORDER BY estudiantes.appaterno ASC,estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function list_oction_estu($id_test)
	{
		$sql = "SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,question_estud.nota as nota  FROM question_estud INNER JOIN estudiantes ON question_estud.id_est=estudiantes.id_est WHERE question_estud.id_test_que=" . $id_test . "  ORDER BY estudiantes.appaterno ASC,estudiantes.apmaterno ASC, estudiantes.nombre ASC";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function list_oction_e($id_test)
	{
		$sql = "SELECT test_question.id as id FROM test_question INNER JOIN question ON question.id=test_question.id_ques
		 WHERE test_question.id_test=" . $id_test . " AND question.numero=0";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function update_notabimestre($id, $data)
	{
		$this->db->where('id_not_bi', $id);
		$this->db->update('nota_bimestre', $data);
	}
	public function get_estu_bi($id)
	{
		$sql = "SELECT *  FROM nota_bimestre WHERE id_not_bi=" . $id;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_nota_area($id, $area)
	{
		$sql = "SELECT *  FROM nota_area WHERE id_est=" . $id . " and id_area=" . $area;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_nota_prom($id)
	{
		$sql = "SELECT *  FROM nota_prom WHERE id_est=" . $id;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function update_nota_prom($id, $data)
	{
		$this->db->where('id_est', $id);
		$this->db->update('nota_prom', $data);
	}
	public function update_nota_area($id, $id_area, $data)
	{
		$this->db->where('id_est', $id);
		$this->db->where('id_area', $id_area);
		$this->db->update('nota_area', $data);
	}
	public function update_nota_materia($id, $id_mat, $data)
	{
		$this->db->where('id_est', $id);
		$this->db->where('id_mat', $id_mat);
		$this->db->update('nota_materia', $data);
	}

	public function get_trimestre_current()
	{
		$this->db->from("bimestre");
		$this->db->where('activo', 1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_current_temas($id_prof, $cod_nivel, $cod_curso, $materia, $bimestre, $gestion)
	{
		$this->db->select("temas.*");
		$this->db->from("temas");
		$this->db->where('id_prof', $id_prof);
		$this->db->where('cod_nivel', $cod_nivel);
		$this->db->where('cod_curso', $cod_curso);
		$this->db->where('id_mat', $materia);
		$this->db->where('id_bi', $bimestre);
		$this->db->where('gestion', $gestion);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_current_tareas($id_prof, $bimestre, $gestion)
	{
		$this->db->select('nota_tareas.*');
		$this->db->from("nota_tareas");
		$this->db->where('id_prof', $id_prof);
		$this->db->where('id_bi', $bimestre);
		$this->db->where('gestion', $gestion);
		$this->db->order_by("nota_tareas.id_tema asc");
		$query = $this->db->get();
		return $query->result();
	}

	public function get_materia_data($id_mat)
	{
		$this->db->from("materias");
		$this->db->where('id_mat', $id_mat);
		$query = $this->db->get();
		return $query->row();
	}
}
