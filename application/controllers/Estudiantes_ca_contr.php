<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estudiantes_ca_contr extends CI_Controller {
 
	
  
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Estudiantes_ca_model','mat');
		$this->load->helper(array('url', 'form', 'download' , 'html'));
		if(!$this->session->userdata("login"))
		{
			$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		 	header("Location: ".$bu);
		} 
	}
  
	public function index()
	{
		$this->load->helper('url');
		$this->vista();		
	}

	public function vista()
	{
		$this->load->view('layouts/inicio');
		$this->load->view('layouts/encabezado');
		$this->load->view('Estudiantes_ca_view');
		$this->load->view('layouts/fin');
	}

	public function ajax_get_id()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');

		$codgen='';
		$num_rows=$this->mat->get_count_rows($table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->mat->get_idcod_table($table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idmat;//considerar nombre del id;				
				$newcod=substr($idcod,$n,strlen($idcod));
		        if($newcod>=$may)
		        {
		            $may=$newcod;    
		        }
			}
			$may=$may+1;
			$codgen=$cod."".$may;
		}
		else
		{
			$codgen=$cod.'1';	
		}
		
		echo json_encode(array("status"=>TRUE,"codgen"=>$codgen));
	}

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/LoginE';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}

	public function ajax_get_gestion()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->mat->get_rows_gestion($table); //envia
		$data=array();
		foreach ($list as $gestion) {			
			$data[] =$gestion->gestion;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function Get_estu()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$cursos=$this->mat->Get_curso($cod_curso);
		$niveles=$this->mat->get_nivel($cod_nivel);
		$data=array();
		$data[] =$appat." ".$apmat." ".$name;	
		$data[] =$id_est;	
		$data[] =$gest;	
		$data[] =$niveles->nivel." ".$niveles->turno;	
		$data[] =$cursos->nombre;	
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}


	public function ajax_get_cole()
	{
		$table=$this->input->post('table');
		$nivel=$this->input->post('nivel');

		$list=$this->mat->get_rows_cole($table,$nivel);
		$data=array();
		foreach ($list as $cole ) {
			$data[]=$cole->colegio;
			$data[]=$cole->gestion;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_curso()
	{
		$nivel=$this->input->post('nivel');

		$list=$this->mat->get_rows_curso_nivel($nivel);
		// print_r($list);
		// exit();
		$data=array();
		$data1=array();
		foreach ($list as $curso ) {
			$data[]=$curso->curso;
			$data1[]=$curso->id_asg_cur;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_temas()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$id_mat=$nivel=$this->input->post('id_mat');
		$id_prof=$nivel=$this->input->post('id_prof');
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$list=$this->mat->get_temas($cod_nivel,$cod_curso,$gest,$id_mat,$id_prof);
		
		//print_r($list);
		//exit();
		$data=array();
		$data1=array();
		foreach ($list as $temas ) {
			$data[]=$temas->tema;
			$data1[]=$temas->id;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_profesor()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		$id_mat=$nivel=$this->input->post('id_mat');
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$list=$this->mat->get_profesor($cod_nivel,$cod_curso,$gest,$id_mat);
		
		$data=array();
		$data1=array();
		foreach ($list as $profesor ) {
			$data[]=$profesor->appaterno." ".$profesor->apmaterno." ".$profesor->nombre;
			$data1[]=$profesor->id_prof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_materias()
	{
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$cod_curso=$ids[0]; 
		$cod_nivel=$ids[1];
		$list=$this->mat->get_rows_curso_materia($cod_nivel,$cod_curso,$gest);
		
		$data=array();
		$data1=array();
		foreach ($list as $materias ) {
			$data[]=$materias->nombre;
			$data1[]=$materias->id_mat;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_get_area()
	{
		$id_mat=$this->input->post('id_mat');
		$materia=$this->mat->get_materiass($id_mat);
		// print_r($id_mat);
		// exit();
		$area=$this->mat->get_materias_area($materia->id_mat);
		$data=array();
		$data[]=$area->nombre;
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}
	public function ajax_get_materia()
	{
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$list=$this->mat->rows_curso_materia($nivel,$curso);
		// print_r($list);
		// exit();
		$data=array();
		$data1=array();
		foreach ($list as $materia ) {
			$data[]=$materia->materia;
			$data1[]=$materia->id_mat;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);

	}
	public function ajax_get_curso2()
	{
		$table=$this->input->post('table');
		$idcur=$this->input->post('idcur');

		$list=$this->mat->get_rows_curso2($table,$idcur);

		$data=array();
		foreach ($list as $curso ) {
			$data[]=$curso->curso;
			$data[]=$curso->corto;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);

	}

	public function ajax_get_corto()
	{
		$table=$this->input->post('table');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');

		$list=$this->mat->get_rows_corto($table,$curso,$nivel);
		$data=array();
		foreach ($list as $curso ) {
			$data[]=$curso->corto;
			$data[]=$curso->idcurso;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_profe()
	{
		$list=$this->mat->get_rows_profe();
		$data=array();
		$data1=array();
		foreach ($list as $profe ) {
			$data[]=$profe->appaterno." ".$profe->apmaterno." ".$profe->nombre;
			$data1[]=$profe->id_prof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);
	}
	public function ajax_get_profe2()
	{
		$table=$this->input->post('table');
		$idprof=$this->input->post('idprof');
		$list=$this->mat->get_rows_profe2($table,$idprof);
		$data=array();
		foreach ($list as $profe ) {
			$data[]=$profe->appaterno." ".$profe->apmaterno." ".$profe->nombres;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_get_idprofe()
	{
		$table=$this->input->post('table');
		$txt=$this->input->post('profe');

		$n=strpos($txt," ");
		$appat=substr($txt,0,$n);
		$txt2=substr($txt,$n+1,strlen($txt));

		$n2=strpos($txt2," ");
		$apmat=substr($txt2,0,$n2);

		$nomb=substr($txt2,$n2+1,strlen($txt2));

		//print_r($appat."-".$apmat."-".$nomb);

		$list=$this->mat->get_rows_idprofe($table,$appat,$apmat,$nomb);
		$data=array();
		foreach ($list as $profe ) {
			$data[]=$profe->idprof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function ajax_set_profesor()
	{ 

		$materia=$this->input->post('materia');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$idprof=$this->input->post('idprof');
		$gestion=$this->input->post('gestion');
		$materias=$this->mat->get_materia($materia);
		// print_r($materias);
		// exit();
		$data=array(
			'codigo'=>$materias->codigo."-".$materia."-".$idprof,
			'gestion'=>$gestion,
			'id_asg_mate'=>$materia,
			'id_prof'=>$idprof,			
		);
		
		$insert=$this->mat->save_mat($data);
		
		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_list()
	{
		
		$list=$this->mat->get_datatables();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		
		foreach ($list as $materia) {
			$no++;
			$row = array();
			$row[] = $materia->idmat;
			$row[] = $materia->materia;
			$row[] = $materia->area;
			$row[] = $materia->campo;
			$row[] = $materia->idcurso;
			$row[] = $materia->curso;
			$row[] = $materia->nivel;
			$row[] = $materia->idprof;
			$row[] = $materia->gestion;
						
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_materia('."'".$materia->idmat."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_materia('."'".$materia->idmat."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';
		
			$data[] = $row;

			//print_r($data);
		}

		//print_r($data);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mat->count_all(),
						"recordsFiltered" => $this->mat->count_filtered(),
						"data" => $data,
				);
		
		echo json_encode($output);

	}

	public function subir()
	{ 
		 //$excel=$_FILES['planilla'];
		 //$excel['name'];
		 //print_r($excel['name']);
		//exit();
		 //print_r($_POST['Fprofe']);
		 //exit();
		$nombre_carpeta='./public/planillas/Subirtareas';
		if(!is_dir($nombre_carpeta)){
			@mkdir($nombre_carpeta, 0700);
			}
		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('planilla')){
			echo 'no subio';
		}
		else{
			$excel = $this->upload->data();
		}

		$url=$excel['full_path'];
		$nombre=$excel['file_name'];

		try {
    		//$inputFileType = PHPExcel_IOFactory::identify($nombre);
    		//$objReader = PHPExcel_IOFactory::createReader($inputFileType);
    		//$objPHPExcel = $objReader->load($nombre);
		} catch(Exception $e) {
   			 die('Error loading file "'.pathinfo($nombre,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		$data=array(
			'url'=>$url,
			'nombre'=>$nombre,
			'id_tema'=>$_POST['tema'],
			'id_est'=>$_POST['id_est']
		);		
			//print_r($data);
		$insert=$this->mat->insert($data,"tarea_subir");
		
	echo "<script type='text/javascript'>alert('Se Subio');</script>";
	redirect(base_url().'Estudiantes_su_contr/', 'refresh');

	//echo '</table>';
	//alert("se inserto correctamente");

	}

	public function ajax_list1()
	{	
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$curso=$ids[0];	
		$nivel=$ids[1];
		$gestion=$gest;
		$list=$this->mat->get_datatables_gestion1($curso,$nivel,$gestion);	
		// print_r($list);
		// exit();
		$meses = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $temas) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $temas->materia;
			$row[] = $temas->appaterno." ".$temas->apmaterno." ".$temas->nombre;
			$row[] = $meses[$temas->mes-1];
			$row[] = '<a class="btn btn-sm btn-primary"  title="Descargar " href="'.base_url().'Profesor_ca_contr/descarga_calendario/'.$temas->id.'"><i class="glyphicon glyphicon-pencil"></i> Descargar</a>';	
			//add html for action
		
			$data[] = $row;

		}
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->mat->count_all(),
				"recordsFiltered" => $this->mat->count_filtered_gestion1($curso,$nivel,$gestion),
				"data" => $data,
		);	
		
		echo json_encode($output);

	}
	public function ajax_list_temas($id)
	{	
		$appat=$this->session->userdata("appat");
		$apmat=$this->session->userdata("apmat");
		$name=$this->session->userdata("name");
		$id_est=$this->session->userdata("id");
		//print_r($id_est);
		//exit();
		$gestion=$this->mat->get_gestion(); 
		
		$gest=$gestion->gestion;
		$estudiante=$this->mat->Estudiante($id_est,$gest); //envia
		//print_r($estudiante);
		//exit();
		$ids=explode('-', $estudiante->codigo."-", -1);
		$curso=$ids[0];	
		$nivel=$ids[1];
		$ids=explode('-', $id."-", -1);
		$gestion=$gest;
		$materia=$ids[0];
		$id_prof=$ids[1];
		$list=$this->mat->get_datatables_gestion($curso,$nivel,$gestion,$materia,$id_prof);	
		// print_r($list);
		// exit();
		$meses = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $temas) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $temas->materia;
			$row[] = $temas->appaterno." ".$temas->apmaterno." ".$temas->nombre;
			$row[] = $meses[$temas->mes-1];
			$row[] = '<a class="btn btn-sm btn-primary"  title="Descargar " href="'.base_url().'Profesor_ca_contr/descarga_calendario/'.$temas->id.'"><i class="glyphicon glyphicon-pencil"></i> Descargar </a>';	
			//add html for action
		
			$data[] = $row;

		}
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->mat->count_all(),
				"recordsFiltered" => $this->mat->count_filtered_gestion($curso,$nivel,$gestion,$materia,$id_prof),
				"data" => $data,
		);	
		
		echo json_encode($output);

	}

	public function descarga_tarea($id)
	{
	     //$nombre_carpeta='./public/planillas/tareas';
	     $nombre_carpeta='/home/ARCHIVOS/PROFESORES/CALENDARIO';
	     if(!is_dir($nombre_carpeta)){
			@mkdir($nombre_carpeta, 0700);
			}
            $file = file_get_contents($nombre_carpeta.'/'.$id);
            force_download($id, $file);
	}
	public function descarga_tarea1($id)
	{
	     //$nombre_carpeta='./public/planillas/tareas';
	     $nombre_carpeta='/home/ARCHIVOS/PROFESORES';
	     if(!is_dir($nombre_carpeta)){
			@mkdir($nombre_carpeta, 0700);
			}
            $file = file_get_contents($nombre_carpeta.'/'.$id);
            //download file from director
            force_download($id, $file);
	}

	public function ajax_delete_temas($id)
	{
		$this->mat->delete_by_id($id,"temas");
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_delete_tareas($id)
	{
		$this->mat->delete_by_id($id,"tareas");
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_profesor($id)
	{
		$data=$this->mat->get_by_id($id);
		//print_r($data);
		echo json_encode($data);
	}

	public function ajax_update_profeso()
	{
		$id=$this->input->post('id');
		$materia=$this->input->post('materia');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$idprof=$this->input->post('idprof');
		$gestion=$this->input->post('gestion');
		$materias=$this->mat->get_materia($materia);
		// print_r($materias);
		// exit();
		$data=array(
			'codigo'=>$materias->codigo."-".$materia."-".$idprof,
			'gestion'=>$gestion,
			'id_asg_mate'=>$materia,
			'id_prof'=>$idprof,			
		);			
		
		$this->mat->update(array('id_asg_prof'=>$id),$data);

		echo json_encode(array("status"=>TRUE));
	}

	public function temas()
	{
		$id=$this->input->post('id');
		$materia=$this->input->post('materia');
		$curso=$this->input->post('curso');
		$nivel=$this->input->post('nivel');
		$idprof=$this->input->post('id_prof');
		$gestion=$this->input->post('gestion');
		$tema=$this->mat->get_temas($materia,$curso,$nivel,$idprof,$gestion);
		$data=array();
		$data1=array();
		foreach ($tema as $temas) {
			$data[]=$temas->id;
			$data1[]=$temas->tema;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
			"data1"=>$data1,
		);

		echo json_encode($output);
	}

	public function generar($id)
	{
		$ids=explode('-', $id."-", -1);
		// print_r($ids);
		// exit();
		$curso=$ids[2]; 
		$nivel=$ids[1];
		$gestion=$ids[0];
		$niveles=$this->mat->get_nivel($nivel);
		$estudiante=$this->mat->get_rows_nota_prom($nivel,$curso,$gestion);
		print_r($niveles);
		exit();
		foreach ($estudiante as $estudiantes ) {
			$materia=$this->mat->get_materia_secundaria($curso);
			$area_estu=$this->mat->nota_area($gestion,$estudiantes->id_est);
			foreach ($materia as $materias ) {
				$mat_estu=$this->mat->nota_materia($gestion,$estudiantes->id_est,$materias->id_mat);
				if($mat_estu=null)
				{
				}
			}
			
			if($area_estu=null)
			{
			}
		}
		$list=$this->mat->get_rows_idprofe($table,$appat,$apmat,$nomb);
		$data=array();
		foreach ($list as $profe ) {
			$data[]=$profe->idprof;
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

/*
	public function print()
	{
		$this->load->library('pdf');
		//$list=$this->mat->get_print_prof_pdf();
		
		

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE PROFESORES");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(15,7,'ID','TBL',0,'C','1');
    		$this->pdf->Cell(15,7,'ITEM','TBL',0,'C','1');
    		$this->pdf->Cell(15,7,'CI','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'DIRECCION','TBLR',0,'C','1');
    		$this->pdf->Cell(15,7,'FONO','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $prof) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$prof->idest,'TBL',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->idprof,'TBL',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->item,'TBLR',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->ci,'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,strtoupper($prof->appaterno),'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,strtoupper($prof->apmaterno),'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,strtoupper($prof->nombres),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,$prof->direccion,'TBLR',0,'L',0);
		      $this->pdf->Cell(15,5,$prof->telefono,'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$prof->genero,'TBLR',0,'L',0);
		     // $this->pdf->Cell(10,5, $this->Image($prof->foto,180,8,15,0),'TBLR',0,'C',0);
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Profesores.pdf", 'I');

		    ob_end_flush();


	}
*/

}
