<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profesor_de_contr extends CI_Controller {
  
	 
  
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Profesor_de_model','mat');
		$this->load->helper(array('url', 'form', 'download' , 'html'));
		
		$this->load->library('excel');
		$this->load->library('drawing'); 
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
		$this->load->view('Profesor_de_view');
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
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
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

	public function ajax_get_level()
	{
		$table=$this->input->post('table');//recibe

		$list=$this->mat->get_rows_nivel($table); //envia
		$data=array();
		foreach ($list as $curso) {			
			$data[] =$curso->nivel;					 
		}
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
	public function ajax_materias()
	{
		$nivel=$this->input->post('nivel');
		$curso=$this->input->post('curso');
		$list=$this->mat->get_rows_curso_materia($nivel,$curso);
		// print_r($list);
		// exit();
		$data=array();
		$data1=array();
		foreach ($list as $materias ) {
			$data[]=$materias->materias;
			$data1[]=$materias->id_asg_mate;
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
		$nombre_carpeta='./public/planillas/tareas';
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
		if($_POST['categoria']=="A"){

			$data=array(
				'tema'=>$_POST['tema'],
				'id_mat'=>$_POST['materia'],
				//'nombre'=>$_POST['Fprofe'],
				'url'=>$url,
				'nombre'=>$nombre,
				'id_prof'=>$_POST['Fidprofe'],
				'cod_nivel'=>$_POST['Fnivel'],
				'cod_curso'=>$_POST['Fcurso'],
				'gestion'=>$_POST['Fgestion']
			);		

			//print_r($data);
			$insert=$this->mat->insert($data,"temas");
		}else
		{
			$data=array(
				'nombre'=>$nombre,
				'url'=>$url,
				'fechaentrega'=>$_POST['fecha'],
				'gestion'=>$_POST['Fgestion'],
				'id_tema'=>$_POST['ttema']
			);		

			//print_r($data);
			$insert=$this->mat->insert($data,"tareas");
		}
		
	echo "<script type='text/javascript'>alert('Se Subio');</script>";
	redirect(base_url().'Subir_tarea_contr/', 'refresh');

	//echo '</table>';
	//alert("se inserto correctamente");

	}

	public function export_exel($id)
	{
		$ids=explode('-', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$id_tema=$ids[1];
		$cursos=$ids[2];
		$materia=$ids[3];
		$niveles=$ids[4];
		$list=$this->mat->get_bitacora_estudiante($gestion,$id_tema);
		$nivel=$this->mat->get_nivel($niveles);
		$curso=$this->mat->get_cursos($cursos);
		$materias=$this->mat->get_materia($materia);
		$temas=$this->mat->get_tema($id_tema);
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('lista');
        
        $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

        $contador = 1;
       
        $estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '045aaa')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $estilobor = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
        )
	);

        
		$contador=2;
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

       // $this->excel->getActiveSheet()->getDrawingCollection() ; 
      	//$this->excel->getActiveSheet()->getHeaderFooter()->getImages();
      	//$this->excel->getDrawingCollection()->setImageResource('images/logo.png');


       // $gdImage = $this->excel->imagecreatefrompng('images/logo.png');
        //$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();

	$this->drawing->setName('Logotipo1');
	$this->drawing->setDescription('Logotipo1');
	$img = imagecreatefrompng('assets/images/logo.png');
	$this->drawing->setImageResource($img);
	$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$this->drawing->setHeight(100);
	$this->drawing->setCoordinates('A1');
	$this->drawing->setWorksheet($this->excel->getActiveSheet());

        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":E".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'LISTA DE ESTUDIANTES');
       
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $nivel->nivel." ".$nivel->turno);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'AÑO DE ESCOR.');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $curso->nombre);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $gestion);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'TEMAS');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $temas->tema);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERIAS');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $materias->nombre);
        $contador++;$contador++;

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:E{$contador}")->applyFromArray($estilo);

       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'FECHA');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador++;
		 
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:E{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
        	$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombre);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->fecha);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$cursos}_{$niveles}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}
	public function ajax_list_temas($id)
	{		
		$ids=explode('-', $id."-", -1);
		$gestion=$ids[0];
		$id_tema=$ids[1];
		$list=$this->mat->get_datatables_gestion($gestion,$id_tema);	
		// print_r($list);
		// exit();
		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $temas) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $temas->curso;
			$row[] = $temas->appaterno.' '.$temas->apmaterno.' '.$temas->nombre;
			$row[] = $temas->materia;
			$row[] = $temas->tema;
			$row[] =$temas->fecha;
			$row[] = $temas->hora;
			$row[] = '<a class="btn btn-sm btn-primary"  title="Descargar " href="'.base_url().'Profesor_de_contr/descarga_tarea/'.$temas->id.'"><i class="glyphicon glyphicon-download"></i>Descargar</a>';	
			$ar="'".$temas->archivo."'";
			if($temas->estado){
				
				$row[] ='<button class="btn btn-warning" onclick="editar('.$ar.','.$temas->id.')"><i class="glyphicon glyphicon-edit"></i>Editar</button>';
			}else{
				$row[] ='<button class="btn btn-success" onclick="calificar('.$ar.','.$temas->id.')"><i class="glyphicon glyphicon-ok"></i>Calificar</button>';
			}

			$data[] = $row;
 
		}
		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->mat->count_all(),
				"recordsFiltered" => $this->mat->count_filtered_gestion($gestion,$id_tema),
				"data" => $data,
		);	
		
		echo json_encode($output);

	}

	public function editnota()
	{
		$id_tarea=$this->session->userdata("id_tema");
		$tarea=$this->mat->get_tarea($id_tarea);
		$tema=$this->mat->get_tema($tarea->id_tema);
		$nota_tareas=$this->mat->get_nota_tareas($id_tarea);
		$update=array(
			'nota'=>$this->input->post('nota'),
			'descrip'=>$this->input->post('descrip')
		);
		$where=array(
			'id'=>$nota_tareas->id);
		$updates=$this->mat->updates($where,$update,"nota_tareas");
		$materias=$this->mat->rows_curso_materias($tema->cod_nivel,$tema->cod_curso,$tema->id_mat,$tarea->gestion);	
		if($nota_tareas->tipo==="H"){
			$saber=array("hacer1","hacer2","hacer3","hacer4","hacer5","hacer6","hacer7","hacer8","hacer9");	
		}	
		if($nota_tareas->tipo==="S"){
			$saber=array("saber1","saber2","saber3","saber4","saber5","saber6","saber7","saber8","saber9");	
		}	
		$update=array(
			'estado'=>true
		);
		$where=array(
			'id'=>$tarea->id);
		$updates=$this->mat->updates($where,$update,"tarea_subir");
		$update=array(
			$saber[$nota_tareas->contar]=>$this->input->post('nota')
		);
		$where=array(
			'id_asg_prof'=>$materias->id,
			'id_est'=>$tarea->id_est,
			'gestion'=>$tarea->gestion,
			'id_bi'=>6); // dev_test: el trimestre esta quemado
		$updates=$this->mat->updates($where,$update,"nota_bimestre");
		$data=array();		
		$data[0] ="";
		$output = array(
						"status" => TRUE,
						"data" => $where,
				);
		echo json_encode($output);
	}

	public function calnota()
	{
		$trimestre_actual = $this->mat->get_trimestre_current();
		$id_tarea=$this->session->userdata("id_tema");
		$tarea=$this->mat->get_tarea($id_tarea);
		$tema=$this->mat->get_tema($tarea->id_tema);
		$nota_tareas=$this->mat->get_nota_contar($tarea->gestion,$this->input->post('id_prof'),$tarea->id_est,$this->input->post('tipo'));
		$data=array(
			'nota'=>$this->input->post('nota'),
			'descrip'=>$this->input->post('descrip'),
			'contar'=>$nota_tareas->contar,
			'id_tarea'=>$tarea->id,
			'id_tema'=>$tarea->id_tema,
			'id_mat'=>$tema->id_mat,
			'id_est '=>$tarea->id_est,
			'id_prof'=>$this->input->post('id_prof'),
			'gestion'=>$tarea->gestion,
			'tipo'=>$this->input->post('tipo'),
			'id_bi'=>$trimestre_actual->id_bi,
		);
		$materias=$this->mat->rows_curso_materias($tema->cod_nivel,$tema->cod_curso,$tema->id_mat,$tarea->gestion);
		$insert=$this->mat->insert($data,"nota_tareas");
		/*if($this->input->post('tipo')==="S"){
			$saber=array("saber1","saber2","saber3","saber4","saber5","saber6","saber7","saber8","saber9");	
		}
		if($this->input->post('tipo')==="H"){
			$saber=array("hacer1","hacer2","hacer3","hacer4","hacer5","hacer6","hacer7","hacer8","hacer9");	
		}	*/
		$update=array(
			'estado'=> true
		);
		$where=array('id'=>$tarea->id);
		$updates=$this->mat->updates($where,$update,"tarea_subir");
		$data=array();		
		$data[0] ="";
		$output = array(
						"status" => TRUE,
						"data" => $where,
				);
		echo json_encode($output);
	}

	public function editar()
	{
		$tarea=$this->input->post('tarea');
		$data=[
			"id_tema"=>$this->input->post('id'),//el id es id de la tarea subida
		];	
		$this->session->set_userdata($data);

		$tarea = $this->mat->get_tarea1($this->input->post('id'));
		$data = array();		
		$data[0] = $tarea->url.'/'.$tarea->nombre;

		$get_text_url = explode('./', $data[0]);

		if(count($get_text_url) == 2){
			$data[0] = $get_text_url[1];
		}

		$nota=$this->mat->get_nota_tareas($this->input->post('id'));

		$data[1] =$nota->nota;	
		$data[2] =$nota->descrip;
		$data[3] =$nota->tipo;
		$output = array(
				"status" => TRUE,
				"data" => $data,
		);
		echo json_encode($output);
	}

	public function calificar()
	{
		$tarea=$this->input->post('tarea');
		$data=[
			"id_tema"=>$this->input->post('id'),//el id es id de la tarea subida
		];	
		$this->session->set_userdata($data);
		$tarea = $this->mat->get_tarea1($this->input->post('id'));
		$data = array();		
		$data[0] = $tarea->url.'/'.$tarea->nombre;

		$get_text_url = explode('./', $data[0]);

		if(count($get_text_url) == 2){
			$data[0] = $get_text_url[1];
		}

		//print_r($data);exit();
		$output = array(
			"status" => TRUE,
			"data" => $data,
		);
		echo json_encode($output);
	}
	public function descarga_tarea($id)
	{		
		$tarea=$this->mat->get_tarea1($id);
		$nombre_carpeta=$tarea->url;
		$file = file_get_contents($nombre_carpeta.'/'.$tarea->nombre);
		force_download($tarea->nombre, $file);
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

}
