<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_estadisticas_2019_model extends CI_Model {

	var $tabla='curso';
	var $column= array('idcurso','curso'); //set column field database for datatable orderable
	var $column_search = array('idcurso','curso','nivel'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idcurso' => 'asc'); // default order 
	
 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}  
  
	//otras funciones 

	function get_idcod_table($table)
	{
		$this->db->select('idest');
		$this->db->from($table);
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
	public function get_nivel()//DESDE NIVEL ES LLAMADO, PARA EL SELECT COELGIO
	{
		$this->db->from('niveles');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_rows_level($table,$level)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		//$this->db->select('colegio');
		$this->db->from($table);
		$this->db->where('nivel',$level);
		$this->db->order_by("curso", "asc");
		$query = $this->db->get();
		return $query->result();
	}
	public function colegio($niveles)//DESDE NIVEL ES LLAMADO, PARA EL SELECT nivel
	{
		$sql="SELECT colegio.nombre,niveles.nivel,niveles.turno FROM niveles 
			INNER JOIN colegio ON colegio.id_col=niveles.id_col
			WHERE niveles.codigo='".$niveles."' ";
			$query  = $this->db->query($sql);		
			return $query->result();
	}
	public function cursos()
	{
		$sql="SELECT codigo, curso,nombre  FROM cursos 
				WHERE id_curso=1 OR id_curso=2 OR id_curso=4 OR id_curso=5 
				OR id_curso=7 OR id_curso=8 OR id_curso=10 OR id_curso=11 OR id_curso>=13";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function cursosp()
	{
		$sql="SELECT codigo, curso,nombre  FROM cursos 
				WHERE id_curso=1 OR id_curso=2 OR id_curso=4 OR id_curso=5 
				OR id_curso=7 OR id_curso=8 OR id_curso=10 OR id_curso=11 OR (id_curso>=13 AND id_curso<=17 )";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function mxcurso($nivel,$cantidad,$bi)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom1 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%'
			ORDER BY nota_prom.prom1 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom2 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%'
			ORDER BY nota_prom.prom2 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom3 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%'
			ORDER BY nota_prom.prom3 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom4 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%'
			ORDER BY nota_prom.prom4 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function cxcurso1($nivel,$cantidad,$bi)
	{
		$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
		,nota_prom.promfinal AS nota 
		FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
		WHERE nota_prom.codigo LIKE '".$nivel."%'
		ORDER BY nota_prom.promfinal DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
		LIMIT ".$cantidad;

		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function axcurso($nivel,$bi)
	{
		if($bi==1){
			$sql="SELECT (SUM(nota_materia.notabi1))/COUNT(campo.id_campo) AS notas,nota_materia.codigo
				FROM nota_materia 
				INNER JOIN materias ON materias.id_mat=nota_materia.id_mat 
				INNER JOIN areas ON areas.id_area=materias.id_area 
				INNER JOIN campo ON campo.id_campo=areas.id_campo 
				WHERE nota_materia.codigo LIKE '".$nivel."%'  AND nota_materia.notabi1!=0 
				GROUP BY areas.id_campo";
		}
		if($bi==2){
			$sql="SELECT (SUM(nota_materia.notabi2))/COUNT(campo.id_campo) AS notas,nota_materia.codigo
				FROM nota_materia 
				INNER JOIN materias ON materias.id_mat=nota_materia.id_mat 
				INNER JOIN areas ON areas.id_area=materias.id_area 
				INNER JOIN campo ON campo.id_campo=areas.id_campo 
				WHERE nota_materia.codigo LIKE '".$nivel."%'  AND nota_materia.notabi1!=0 
				GROUP BY areas.id_campo";
		}
		if($bi==3){
			$sql="SELECT (SUM(nota_materia.notabi3))/COUNT(campo.id_campo) AS notas,nota_materia.codigo
				FROM nota_materia 
				INNER JOIN materias ON materias.id_mat=nota_materia.id_mat 
				INNER JOIN areas ON areas.id_area=materias.id_area 
				INNER JOIN campo ON campo.id_campo=areas.id_campo 
				WHERE nota_materia.codigo LIKE '".$nivel."%'  AND nota_materia.notabi1!=0 
				GROUP BY areas.id_campo";
		}
		if($bi==4){
			$sql="SELECT (SUM(nota_materia.notabi4))/COUNT(campo.id_campo) AS notas,nota_materia.codigo
				FROM nota_materia 
				INNER JOIN materias ON materias.id_mat=nota_materia.id_mat 
				INNER JOIN areas ON areas.id_area=materias.id_area 
				INNER JOIN campo ON campo.id_campo=areas.id_campo 
				WHERE nota_materia.codigo LIKE '".$nivel."%'  AND nota_materia.notabi1!=0 
				GROUP BY areas.id_campo";
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function cxcurso($nivel,$cantidad,$bi)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			nota_prom.prom1 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			(nota_prom.prom1+nota_prom.prom2)/2 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			(nota_prom.prom1+nota_prom.prom2+nota_prom.prom3)/3 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			(nota_prom.prom1+nota_prom.prom2+nota_prom.prom3+nota_prom.prom4)/4 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	// public function familia($nivel,$gestion)
	// {
	// 	$sql="SELECT DISTINCT estudiantes.appaterno as paterno, estudiantes.apmaterno as materno,inscrip_padre.ci AS padre,inscrip_madre.ci AS madre
	// 		FROM estudiante INNER JOIN estudiantes ON estudiante.nombres=estudiantes.nombre 
	// 		INNER JOIN inscrip_madre ON inscrip_madre.idest=estudiante.idest
	// 		INNER JOIN inscrip_padre ON inscrip_padre.idest=estudiante.idest 
	// 		INNER JOIN nota_prom ON nota_prom.id_est=estudiantes.id_est 
	// 		WHERE estudiantes.appaterno=estudiante.appaterno AND 
	// 		estudiante.apmaterno=estudiantes.apmaterno AND nota_prom.codigo LIKE '%".$nivel."%' AND 
	// 		nota_prom.gestion=".$gestion."
	// 		ORDER BY estudiantes.appaterno ASC, estudiantes.apmaterno ASC";
	// 	$query  = $this->db->query($sql);
	// 	return $query->result();
	// }
	public function familia($nivel,$gestion,$cantidad)
	{
		$sql="SELECT reguistro_tutor.id_padre,COUNT(reguistro_tutor.id_padre) as total
			FROM nota_prom INNER JOIN reguistro_tutor ON nota_prom.id_est=reguistro_tutor.id_est 
			WHERE reguistro_tutor.id_padre!=0 
			AND nota_prom.gestion=".$gestion." 
			AND nota_prom.codigo LIKE '%".$nivel."%'
			GROUP BY reguistro_tutor.id_padre
			HAVING COUNT(reguistro_tutor.id_padre) >= ".$cantidad;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function padres($id_padre)
	{
		$sql="SELECT *
			FROM padres 
			WHERE id=".$id_padre;
		$query  = $this->db->query($sql);
		return $query->row();
	}
	// public function afamilia($paterno,$materno,$madre,$padre,$nivel,$gestion)
	// {
	// 	$sql="SELECT estudiantes.appaterno AS paterno, estudiantes.apmaterno as materno, estudiantes.nombre, nota_prom.codigo 
	// 		FROM estudiante
	// 		INNER JOIN estudiantes ON estudiante.nombres=estudiantes.nombre
	// 		INNER JOIN inscrip_madre ON inscrip_madre.idest=estudiante.idest
	// 		INNER JOIN inscrip_padre ON inscrip_padre.idest=estudiante.idest
	// 		INNER JOIN nota_prom ON nota_prom.id_est=estudiantes.id_est
	// 		WHERE estudiantes.appaterno=estudiante.appaterno AND
	//   			  estudiante.apmaterno=estudiantes.apmaterno AND
	//   			  inscrip_padre.ci='".$padre."' AND inscrip_madre.ci ='".$madre."' AND
	// 		  	  estudiantes.appaterno='".$paterno."' AND 
	// 			  estudiantes.apmaterno='".$materno."'  AND
	// 			  nota_prom.codigo LIKE '%".$nivel."%'
	// 			  AND nota_prom.gestion=".$gestion."
	// 			  ORDER BY estudiantes.nombre";
	// 	$query  = $this->db->query($sql);
	// 	return $query->result();
	// }
	public function afamilia($id_padre,$gestion)
	{
		$sql="SELECT estudiantes.*,nota_prom.codigo
			FROM reguistro_tutor INNER JOIN estudiantes ON estudiantes.id_est=reguistro_tutor.id_est 
			INNER JOIN nota_prom ON nota_prom.id_est=reguistro_tutor.id_est
			WHERE reguistro_tutor.id_padre=".$id_padre."
			AND nota_prom.gestion=".$gestion;
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function bxcurso($nivel,$cantidad,$bi)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom1 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%' AND  nota_prom.prom1!=0
			ORDER BY nota_prom.prom1 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom2 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%' AND  nota_prom.prom2!=0
			ORDER BY nota_prom.prom2 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom3 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%' AND  nota_prom.prom3!=0
			ORDER BY nota_prom.prom3 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
			,nota_prom.prom4 AS nota 
			FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
			WHERE nota_prom.codigo LIKE '".$nivel."%' AND  nota_prom.prom4!=0
			ORDER BY nota_prom.prom4 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC  
			LIMIT ".$cantidad;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function mxcolegio($nivel,$cantidad,$bi)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom1 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' 
				ORDER BY nota_prom.prom1 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom2 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' 
				ORDER BY nota_prom.prom2 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom3 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' 
				ORDER BY nota_prom.prom3 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom4 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' 
				ORDER BY nota_prom.prom4 DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}

	public function cxcolegio($nivel,$cantidad,$bi)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			nota_prom.prom1 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '%".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			(nota_prom.prom1+nota_prom.prom2)/2 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '%".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			(nota_prom.prom1+nota_prom.prom2+nota_prom.prom3)/3 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '%".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
			(nota_prom.prom1+nota_prom.prom2+nota_prom.prom3+nota_prom.prom4)/4 AS nota,nota_prom.codigo AS codigo
			 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
			  WHERE nota_prom.codigo LIKE '%".$nivel."%'
			  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function cxcolegio1($nivel,$cantidad,$bi)
	{
		$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres ,
		nota_prom.promfinal AS nota,nota_prom.codigo AS codigo
		 FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
		  WHERE nota_prom.codigo LIKE '%".$nivel."%'
		  ORDER BY nota DESC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre   LIMIT ".$cantidad;
		
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function bxcolegio($nivel,$cantidad,$bi)
	{
		if($bi==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom1 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' AND  nota_prom.prom1!=0
				ORDER BY nota_prom.prom1 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		if($bi==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom2 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' AND  nota_prom.prom2!=0
				ORDER BY nota_prom.prom2 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		if($bi==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom3 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' AND  nota_prom.prom3!=0
				ORDER BY nota_prom.prom3 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		if($bi==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres
				,nota_prom.prom4 AS nota,nota_prom.codigo AS codigo 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE nota_prom.codigo LIKE  '%".$nivel."%' AND  nota_prom.prom4!=0
				ORDER BY nota_prom.prom4 ASC , estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre 
				ASC  LIMIT ".$cantidad;
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	
	public function reprobado_estudiantes($nivel,$curso,$genero,$bimestre)
	{
		if($bimestre==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi1>=1 AND nota_area.notabi1<=50) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi2>=1 AND nota_area.notabi2<=50) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi3>=1 AND nota_area.notabi3<=50) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi4>=1 AND nota_area.notabi4<=50) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function retirado($nivel,$curso,$genero,$bimestre)
	{
		if($bimestre==1){
			$sql="SELECT COUNT(nota_prom.id_est) as cantidad
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE (nota_prom.prom1=0) 
				AND nota_prom.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==2){
			$sql="SELECT COUNT(nota_prom.id_est) as cantidad 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE (nota_prom.prom2=0 or nota_prom.prom1=0) 
				AND nota_prom.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==3){
			$sql="SELECT COUNT(nota_prom.id_est) as cantidad 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE (nota_prom.prom2=0 or nota_prom.prom1=0 or nota_prom.prom3=0) 
				AND nota_prom.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==4){
			$sql="SELECT COUNT(nota_prom.id_est) as cantidad 
				FROM nota_prom INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est 
				WHERE (nota_prom.prom2=0 or nota_prom.prom1=0 or nota_prom.prom3=0 or nota_prom.prom4=0) 
				AND nota_prom.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	
	public function aprobado_estudiantes($nivel,$curso,$genero,$bimestre)
	{
		if($bimestre==1){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi1>=51) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==2){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi2>=51) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==3){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi3>=51 ) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		if($bimestre==4){
			$sql="SELECT CONCAT(estudiantes.appaterno ,' ',estudiantes.apmaterno,' ',estudiantes.nombre) as nombres,(estudiantes.id_est) 
				FROM nota_area INNER JOIN estudiantes ON estudiantes.id_est=nota_area.id_est 
				WHERE (nota_area.notabi4>=51 AND nota_area.notabi4<=100) 
				AND nota_area.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'
				GROUP BY estudiantes.id_est 
				order by estudiantes.appaterno ASC , estudiantes.apmaterno ASC, estudiantes.nombre ASC ";
		}
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function cantidad_genero($nivel,$curso,$genero)
	{
			$sql="SELECT COUNT(estudiantes.id_est) AS cantidad
				FROM nota_prom 
				INNER JOIN estudiantes ON estudiantes.id_est=nota_prom.id_est
				WHERE nota_prom.codigo LIKE '".$curso."%".$nivel."%' AND estudiantes.genero='".$genero."'";
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
	public function get_bimestre($id)
	{
		$sql="SELECT *
			FROM bimestre 
			WHERE id_bi=".$id;
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
	public function get_cursos($codigo)
	{
		$sql="SELECT *
			FROM cursos 
			WHERE codigo='".$codigo."'";
		$query  = $this->db->query($sql);
		return $query->result();
	}
	public function get_rows_curso($table,$nivel)
	{

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

	public function get_datatables_by_all($nivel)
	{		
		//print_r($nivel);
		$this->_get_datatables_by_all($nivel);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	public function _get_datatables_by_all($nivel)
	{
		$this->db->from($this->tabla);
		$this->db->where('nivel',$nivel);

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

	public function count_filtered($nivel)
	{
		$this->_get_datatables_by_all($nivel);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->tabla);
		return $this->db->count_all_results();
	}



	public function notasEst($idsql,$idcur,$bimes,$gestion,$genero)
	{
		
		if($idsql=='primaria')
		{
			$sql="select  DISTINCT n.idest,n.appat,n.apmat,n.nombres,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='LENGUAJE' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS LENGUAJE,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='INGLES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INGLES,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS SOCIALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS SOCIALES,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN FÍSICA Y DEPORTES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS EDUFISICA,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='EDUCACIÓN MUSICAL' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MUSICA,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='ARTES PLÁSTICAS Y VISUALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS ARTPLAST,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='MATEMÁTICAS' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS MATEMATICAS,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='TÉCNICA TECNOLÓGICA' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS INFORMATICA,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='CIENCIAS NATURALES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS CIENCIAS,
			(select n1.final from nota as n1,materia as m where n1.idmat=m.idmat and n.idest=n1.idest and m.materia='VALORES, ESPIRITUALIDAD Y RELIGIONES' and n1.final<101 and n.idcurso=m.idcurso and n1.bimestre=n.bimestre) AS RELIGION

			FROM nota as n, estudiante as es
			WHERE n.idcurso='$idcur' and n.bimestre='$bimes' and n.gestion='$gestion' and n.final<101 and n.idest=es.idest  and es.genero='$genero'

			ORDER BY appat,apmat,nombres asc";

			$query  = $this->db->query($sql);		
			return $query->result();
		}


		

	}




	
}
