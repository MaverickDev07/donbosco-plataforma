<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Est_estudiantes_contr extends CI_Controller  {
 
	    
      
	public function __construct()  
	{
		parent::__construct();		   
		//$this->load->helper(array('url', 'form'));
		$this->load->helper(array('url', 'form', 'download' , 'html'));
		$this->load->model('Est_estudiantes_model','estud');

		$this->load->model('Estudiantes_su_model','mat');
		//$this->load->model('Config_curso_model','curso');
		$this->load->library('excel');
		$this->load->library('drawing'); 

		if(!$this->session->userdata("login"))
		{
			$bu='http://'.$_SERVER['HTTP_HOST'];			
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
		$this->load->view('Est_estudiantes_view');
		$this->load->view('layouts/fin');
	}
	// public function ajax_get_id()
	// {
	// 	//valores enviados 
	// 	$table=$this->input->post('table');
	// 	$cod=$this->input->post('cod');

	// 	$codgen='';
	// 	$num_rows=$this->curso->get_count_rows($table);
	// 	if($num_rows!=0)
	// 	{
	// 		$n=strlen($cod);		
	// 		$list = $this->curso->get_idcod_table($table);
	// 		$may=0;
				
	// 		foreach ($list as $codigo) {	
	// 			$idcod=$codigo->idcurso;//considerar nombre del id;				
	// 			$newcod=substr($idcod,$n,strlen($idcod));
	// 	        if($newcod>=$may)
	// 	        {
	// 	            $may=$newcod;    
	// 	        }
	// 		} 
	// 		$may=$may+1;
	// 		$codgen=$cod."".$may;
	// 	}
	// 	else
	// 	{ 
	// 		$codgen=$cod.'1';	
	// 	}
		
	// 	echo json_encode(array("status"=>TRUE,"codgen"=>$codgen));
	// }


	public function ajax_update_estud()
	{
		//valores enviados
		$id_est=$this->input->post('idest');
		$rude=$this->input->post('rude');
		$ci=$this->input->post('ci');
		$appaterno=$this->input->post('appaterno');
		$apmaterno=$this->input->post('apmaterno');
		$nombre=$this->input->post('nombre');
		$genero=$this->input->post('genero');
		$codigo=$this->input->post('codigo');
		$debe=$this->input->post('debe');
		$repite=$this->input->post('repite');
		$acurso=$this->input->post('acurso');
		$codcurso=$this->input->post('codcurso');
		$gestion=$this->input->post('gestion');
		$estudiante=$this->estud->get_cur_student($id_est,$gestion);
		$ids=explode('-', $estudiante->cod_curso."-", -1);
		$codigocurso=$acurso."-".$ids[1]."-".$ids[2];
		// print_r($codigocurso);
		// exit();
		$data=array(
			//'idest'=>$this->input->post('idest'),
			'rude'=>$rude,
			'ci'=>$ci,
			'appaterno'=>$appaterno,
			'apmaterno'=>$apmaterno,
			'nombre'=>$nombre,
			'genero'=>$genero,
			'codigo'=>$codigo
		);
		$data1=$this->estud->updates("estudiantes",array('id_est'=>$id_est),$data);

		$nota_prom=array(
			'repitente'=>$repite,
			'debe'=>$debe,
			'codigo'=>$codigocurso,
			'id_curso'=>$codcurso
		);
		$nota_prom1=$this->estud->updates("nota_prom",array('id_est'=>$id_est,'gestion'=>$gestion),$nota_prom);
		// echo "string".$nota_prom1;
		$nota_area=array(
			'codigo'=>$codigocurso
		);
		$nota_area1=$this->estud->updates("nota_area",array('id_est'=>$id_est,'gestion'=>$gestion),$nota_area);
		$nota_materia=array(
			'codigo'=>$codigocurso
		);
		$nota_materia1=$this->estud->updates("nota_materia",array('id_est'=>$id_est,'gestion'=>$gestion),$nota_materia);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}
	
	
	public function ajax_list()
	{
		
		$list=$this->estud->get_datatables();

		$data = array();
		$no = $_POST['start'];
		//print_r($list);
		$i=0;
		foreach ($list as $estudiante) {
			$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->ci;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombres;
			$row[] = $estudiante->genero;
			//$row[] = $estudiante->idcurso;
			$curso_=$this->estud->get_curso_est($estudiante->idest);	
			$row[] = $curso_->curso;
			$row[] = $estudiante->codigo;
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";
			if($this->session->userdata("appat")=='ARCIENEGA' AND $this->session->userdata("apmat")=='ZAMORANO' AND $this->session->userdata("name")=='WILSON')
			{	
				$row[]="";
			}else{
			//add html for action
				$row[] = '<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_estud('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
			}
			$row[] = '<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="EditRUDE" onclick="edit_rude('."'".$estudiante->idest."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Print RUDE" onclick="print_rude('."'".$estudiante->idest."'".')"><i class="icon icon-file-pdf"></i></a>
				<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Print CTTO" onclick="print_ctto('."'".$estudiante->idest."'".')"><i class="icon icon-file-pdf"></i></a>';



			$data[] = $row;

		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->estud->count_all(),
						"recordsFiltered" => $this->estud->count_filtered(),
						"data" => $data,
				);

		echo json_encode($output);

	}

	public function ajax_get_idins($id)
	{
		$list=$this->estud->get_idinscrip($id);
		$idins=$list->idinscrip;	
		
		echo json_encode(array("status"=>TRUE,"idins"=>$idins));	
	}




	public function ajax_delete($id)
	{
		$list=$this->estud->get_idinscrip($id);
		$idins=$list->idinscrip;		
		$this->estud->delete_by_id($id,$idins);
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_edit_estud($id)
	{
		//print_r($id);
		$ids=explode('-', $id."-", -1);
		$id_est=$ids[0]; 
		$gestion=$ids[1];
		$data=$this->estud->get_cur_student($id_est,$gestion);

		echo json_encode($data);
	}

	public function ajax_update_cpse()
	{
		//valores enviados
		$id_est=$this->input->post('idest');
		$ccc=$this->input->post('ccc');
		$gestion=$this->input->post('gestion');
		$boletin=$this->input->post('boletin');

		$nota_prom=array(
			'cpse '=>$ccc,
			'boletin'=>$boletin
		);
		$nota_prom1=$this->estud->updates("nota_prom",array('id_est'=>$id_est,'gestion'=>$gestion),$nota_prom);
		echo json_encode(array("status"=>TRUE));
	}


	public function ajax_save_estud()
	{

		//valores enviados 
		$data=array(
			
			// 'idest'=>$this->input->post('idest'),
			'rude'=>$this->input->post('rude'),
			'ci'=>$this->input->post('ci'),
			'appaterno'=>$this->input->post('appaterno'),
			'apmaterno'=>$this->input->post('apmaterno'),
			'nombre'=>$this->input->post('nombres'),
			'genero'=>$this->input->post('genero'),
			'codigo'=>$this->input->post('codigo'),
			'preinscripcion'=>true
			// 'idcurso'=>$this->input->post('idcurso'),
			// 'nivel'=>$this->input->post('nivel'),
			// 'gestion'=>$this->input->post('gestion'),
			// 'colegio'=>$this->input->post('colegio'),
			// 'codigo'=>$this->input->post('codigo')
		);
		$insert=$this->estud->save_estud_news("estudiantes",$data);
		
		echo json_encode(array("status"=>TRUE));
	}

	public function ajax_get_gestion()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->estud->get_rows_gestion($table); //envia
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

	public function ajax_get_nivel()
	{
		$table=$this->input->post('table');//recibe
		$list=$this->estud->get_rows_nivel($table); //envia
		$data=array();
		foreach ($list as $nivel) {			
			$data[] =$nivel->nivel." ".$nivel->turno;					 
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
		$lvl=$this->input->post('lvl');

		
		$list=$this->estud->get_rows_level($table,$lvl); //envia

		$data=array();
		foreach ($list as $level) {			
			$data[] =$level->gestion;					 
			$data[] =$level->colegio;
			$data[]	=$level->idcurso;
			$data[]	=$level->curso;				 				 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}


	public function ajax_get_curso()
	{
		$tablecur=$this->input->post('TablaCur');
		$nivel=$this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list=$this->estud->get_rows_curso($tablecur,$nivel);

		$data=array();
		foreach ($list as $curso){
			$data[]=$curso->curso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
	}

	public function ajax_get_idcurso()
	{

		
		$nivel=$this->input->post('EstNivel');
		$curso=$this->input->post('EstCurso');

		$list=$this->estud->get_idcurso($nivel,$curso);

		$data=array();
		foreach($list as $idcur)
		{
			$data[]=$idcur->idcurso;
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
		);

		echo json_encode($output);
		//print_r($nivel."-".$gestion."-".$colegio."-".$curso);	
	}

	public function subir()
	{ 
		 //$excel=$_FILES['planilla'];
		 //$excel['name'];
		//print_r($excel['name']);
		//print_r("tema:".$_POST['id_tema']);
		//print_r("tarea:".$_POST['id_tarea']);
		//exit();
		 //print_r($_POST['Fprofe']);
		 //exit();
		//$nombre_carpeta='./public/planillas/Subirtareas';
		///run/media/server/DATOS/ARCHIVOS/EC/2020/EC/ESTUDIANTE
		//$nombre_carpeta='/home/ARCHIVOS/ESTUDIANTES';
		$nombre_carpeta='/run/media/server/DATOS/ARCHIVOS/Boletin';
		if(!is_dir($nombre_carpeta)){
			@mkdir($nombre_carpeta, 0700);
			}
		$config['upload_path'] = $nombre_carpeta;
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('planilla')){
			echo 'no subio';
			echo $this->upload->display_errors();
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
			'url'=>$nombre_carpeta,
			'id_est'=>$_POST['id_est']
		);	 	
			//print_r($data);
		$insert=$this->mat->insert($data,"boletin");
		
	echo "<script type='text/javascript'>alert('TU BOLETIN SE SUBIÓ CORRECTAMENTE');</script>";
	redirect(base_url().'Est_estudiantes_contr/', 'refresh');
	//redirect(base_url().'Est_estudiantes_contr/', 'refresh');

	//echo '</table>';
	//alert("se inserto correctamente");

	}
	public function ajax_list_idcurso($id)
	{		
		$ids=explode('-', $id."-", -1);
		//print_r($ids);
		//exit();
		$curso=$ids[0]; 
		$nivel=$ids[1];
		$gestion=$ids[2];
        // print_r($ids);
		//print_r($id1."?".$id2);
		$list=$this->estud->get_datatables_by_idcur($gestion,$curso,$nivel);
		$data = array();
		$no = $_POST['start'];
		$i=0;
		$urls=base_url()."Est_estudiantes_contr/subir";
		foreach ($list as $estudiante) {
			//$no++;
			$i++;
			$row = array();
			$row[] = $i;
			$row[] = $estudiante->rude;
			$row[] = $estudiante->appaterno;
			$row[] = $estudiante->apmaterno;
			$row[] = $estudiante->nombre;
			$boletin=$this->estud->boletin($estudiante->id_est);
			if(is_null($boletin)){
				$row[] = '<form action="'.$urls.'" method="post" enctype="multipart/form-data">
					<input id="id_est" name="id_est" type="hidden" value="'.$estudiante->id_est.'">
					<input type="file" name="planilla" id="planilla" />
					<button class="btn btn-primary" onclick="'.$urls.'" id="bt2l"><i class="glyphicon glyphicon-cloud-upload"></i>SUBIR</button>
				</form>';
			}else{

				$row[] = "SUBIDO";
			}
			//$row[] = $estudiante->genero;

		$comppromiso=$this->estud->get_compromiso($gestion,$estudiante->id_est);
			//$row[] = "<img src='".$estudiante->foto."' width='100' height='100'>";
			if(is_null($comppromiso)){
				$descargar="";
			}else{

				$descargar='<a class="btn btn-sm btn-primary"  title="Descargar " href="http://181.115.156.38/donbosco/Est_estudiantes_contr/descarga_compromiso/'.$comppromiso->nombre.'"><i class="icon icon-file-pdf"></i></a>';
			}

			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Print RUDE" onclick="print_rude1('."'".$estudiante->id_est."'".')"><i class="icon icon-file-pdf"></i></a>
				<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Print CTTO" onclick="print_ctto1('."'".$estudiante->id_est."'".')"><i class="icon icon-file-pdf"></i></a>
				<a class="btn btn-sm bg-orange" href="javascript:void(0)" title="Print CPSE" onclick="print_cpse('."'".$estudiante->id_est."'".')"><i class="icon icon-file-pdf"></i></a>';
				//<a class="btn btn-sm bg-orange" href="javascript:void(0)" title="Print CPSE" onclick="print_cpse('."'".$estudiante->id_est."'".')"><i class="icon icon-file-pdf"></i></a>

			if(
				$this->session->userdata("id1")=='US-89' OR 
				$this->session->userdata("id1")=='US-1683' OR 
				$this->session->userdata("id1")=='US-66' OR
				$this->session->userdata("id1")=='US-67' OR
				$this->session->userdata("id1")=='US-68'
			) {	
				$row[] = '<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_estud('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm bg-orange-400" href="javascript:void(0)" title="EditRUDE" onclick="edit_rude('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_cpse('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i></a>'.$descargar;
			}else{
				//add html for action
				//$row[]='<a class="btn btn-sm bg-green-800" href="javascript:void(0)" title="Edit" onclick="edit_cpse('."'".$estudiante->id_est."'".')"><i class="glyphicon glyphicon-pencil"></i></a>'.$descargar;
				$row[]='<a class="btn btn-sm bg-green-400" href="javascript:void(0)"><i class="glyphicon glyphicon-heart-empty"></i></a>'; 
				
			}

			$data[] = $row;

		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->estud->count_all(),
						"recordsFiltered" => $this->estud->count_filtered_byid($gestion,$curso,$nivel),
						"data" => $data,
				);

		echo json_encode($output);

	}

	public function descarga_compromiso($id)
	{
	     //$nombre_carpeta='./public/planillas/tareas';
	     $nombre_carpeta='/run/media/server/DATOS/ARCHIVOS/COMPROMISO/2020';
            $file = file_get_contents($nombre_carpeta.'/'.$id);
            force_download($id, $file);
	}
	public function print_cpse($cod)
	{
		//print_r($idins);

      
		$ids=explode('-', $cod, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0]; 
		$id_est=$ids[1];
		// print_r($ids); 
		// exit();
		$nombre="";
		$carnet="";
		$fono="";
		$contrato=$this->estud->get_data_contrato($gestion,$id_est);
        $id_padre=$contrato->id_padre;
        $codigo=$contrato->codigo;
        $fechactt=$contrato->fecha;

        $padre=$this->estud->get_data_padres($id_padre);

     	$nombre=$padre->appaterno." ".$padre->apmaterno." ".$padre->nombre;
		$carnet=$padre->ci." ".$padre->com." ".$padre->ex;
		$fono=$padre->celular;

         
        $estudiante1=$this->estud->get_data_estudiante($id_est);
		$estudiante=$estudiante1->appaterno." ".$estudiante1->apmaterno." ".$estudiante1->nombre;
		$inscripcion=$this->estud->get_data_inscrips($id_est,$gestion);
		$cod_curso=$inscripcion->curso;
		$cod_nivel=$inscripcion->nivel;
		$fecha=$inscripcion->fecha."-";
        $fechas=explode('-', $fecha, -1);
        $curso1=$this->estud->get_data_cursos($cod_curso);
		$curso=$curso1->nombre;
        $nivel1=$this->estud->get_data_niveles($cod_nivel);
		$nivel=$nivel1->nivel." ".$nivel1->turno;
		$turno=$nivel1->turno;
        $direcion=$this->estud->get_data_direccion($gestion,$id_est);
        foreach ($direcion as $direciones) 
         {
			$calle=$direciones->calle;
			$num=$direciones->nro;
			$zona=$direciones->zona;
         }


		$this->load->library('Pdf');

		ob_start();
		$this->pdf=new Pdf('Letter');
		$this->pdf->AddPage();
		$this->pdf->AliasNbPages();
		$this->pdf->SetTitle($estudiante);
		

       	$this->pdf->SetFont('Arial','BU',10);
		$this->pdf->Cell(30);
    	$this->pdf->Cell(135,8,utf8_decode('ADENDA CONTRATO DE PRESTACION DE SERVICIO EDUCATIVO 
'),0,0,'C');
    	$this->pdf->setXY(40,15);
    	$this->pdf->Cell(135,8,utf8_decode('Nº  '.$codigo.'   /2020
'),0,0,'C');
    	$this->pdf->Image('assets/images/firma.png',40,210,50,0);

    	$this->pdf->SetFont('Arial','',10);
    	$this->pdf->Ln('6');

   		$this->pdf->setXY(11,35);
    	$this->pdf->MultiCell(185,4,utf8_decode('Conste por el presente documento privado, que reconocido tendrá el valor de documento público que le asignan los Art. 519 y 1.297 del Código Civil, consistente en una ADENDA indisoluble al contrato de prestación de servicios educativos N° '.$codigo.' , suscrito en fecha '.$fechactt.' , de acuerdo a las cláusulas siguientes:


PRIMERA. - ANTECEDENTES.- En fecha '.$fechactt.' UNIDAD EDUCATIVA y RESPONSABLE suscribieron un contrato de prestación de servicios educativos para la gestión 2020. Que, por determinaciones gubernamentales, debido a la pandemia del SARS -COV -2 (Covid -- 19) que aqueja a nuestra población, ha suspendido las actividades académicas presenciales. Que por DS 4260 el Ministerio de Educación ha homologado la validez de la educación virtual y debido a la situación económica por la que atraviesa la ciudadanía en general, se ve necesario arribar a un acuerdo voluntario que garanticen el cumplimiento del contrato, con la finalidad que los estudiantes continúen el proceso de enseñanza/aprendizaje acordado.


SEGUNDA.-DEL OBJETO DE LA ADENDA.- Por los antecedentes expuestos, ambas partes, UNIDAD EDUCATIVA y el RESPONSABLE determinan, en cuanto a la cláusula quinta del contrato principal, referente al precio y forma de pago por el servicio educativo, aplicar una reducción del 14% a las cuotas mensuales de abril, mayo, junio, etc, hasta que dure la suspensión de actividades presenciales de la gestión 2020. 


TERCERA.- DE LA ACEPTACIÓN.-  Ambas partes, al momento de firmar el presente contrato, manifiestan su plena conformidad con todas y cada una de las cláusulas precedentemente estipuladas y se ratifican, por lo demás en el contrato principal.






                                                           Sucre,............ de ............................ del 2020

'),0);
    	//$html='<b>Que el(a) contratista </b>, identificado(a) con Cédula de Ciudadanía No. presto(a) sus servicios mediante la siguiente orden:';
 
    $this->pdf->setXY(40,230);
//$this->pdf->WriteHTML($html);
    $this->pdf->setXY(40,235);
    $this->pdf->SetFont('Arial','BU',10);
	$this->pdf->Cell(45,5,'                                                ',0,0,'R');			
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',8);
	$this->pdf->setXY(40,240);
	$this->pdf->Cell(40,5,utf8_decode('LA UNIDAD EDUCATIVA'),0,0,'C');
	$this->pdf->setXY(40,245);
	$this->pdf->Cell(40,5,utf8_decode('P. ALBERTO VÁSQUEZ CÁCERES	'),0,0,'C');
	$this->pdf->setXY(40,250);
	$this->pdf->Cell(40,5,utf8_decode('C.I 3122876 CBBA	'),0,0,'C');
	
	$this->pdf->setXY(130,235);
    $this->pdf->SetFont('Arial','BU',10);
	$this->pdf->Cell(45,5,'                                                ',0,0,'R');			
	$this->pdf->Ln(6);
	$this->pdf->SetFont('Arial','B',8);
	$this->pdf->setXY(130,240);
	$this->pdf->Cell(40,5,utf8_decode('EL RESPONSABLE'),0,0,'C');
	$this->pdf->setXY(130,245);
	$this->pdf->Cell(40,5,utf8_decode('Sr./Sra. '.$nombre),0,0,'C');
	$this->pdf->setXY(130,250);
	$this->pdf->Cell(40,5,utf8_decode('C.I.'.$carnet),0,0,'C');

    $this->pdf->Output($estudiante.".pdf", 'I');


			
	}
	public function asistencia($id)
	{ 
		$ids=explode('W', $id, -1);
		// print_r($ids);
		// exit();
		$gestion=$ids[0]; 
		$curso=$ids[1];
		$nivel=$ids[2];
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);
		$cole=$this->estud->get_nivel_colegio($nivel);
		$cursos=$this->estud->get_cursos($curso);
		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('notas');

        $contador = 1;

         $rojo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fa6737')
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
         $naranja = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ffb748')
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
         $amarillo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'f9f966')
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
         $verde = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '95e886')
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
        'font'  => array(
        //'bold'  => true,
        'size'  => 9,
       //'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
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
        $notas = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THICK
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' =>  PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $punt = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fdf7ee')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $act = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd1d1d1')
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
         


        $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 20,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $titulo2 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 12,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '85b3ff')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $titulo_n = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '85b3ff')
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
        $titulo3 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'f2f2f2')
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
        $ser = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'c5ffe0')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THICK
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $final = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'c7fff6')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THICK
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $saber = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'bbe1fe')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THICK
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
         $hacer = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fedebb')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THICK
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
         $ac = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ff6f6f')
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
         $decir = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fdfebb')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          ),
          'outline' => array(
              'style' => PHPExcel_Style_Border::BORDER_THICK
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
          $est = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ff99ad')
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
          $esta1 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 12,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '00a3da')
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
          $esta2 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '90a8f9')
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
          $esta3 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'face92')
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

          $negra = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID
         
        )
	);

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
       	$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(3);
       	$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AO')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AP')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AQ')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AR')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AS')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AT')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AU')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AV')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AW')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AX')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AY')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AZ')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('BA')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('BB')->setWidth(3);
        // $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(100);
        $this->excel->getActiveSheet()->getColumnDimension('BC')->setWidth(3);
        // $this->excel->getActiveSheet()->getColumnDimension('BC')->setHeight(100);


		// $this->excel->getActiveSheet()->getProtection()->setSheet(true); 
		$contador=$contador+3;;
		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":BC".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}:BC{$contador}")->applyFromArray($titulo);
         $this->excel->getActiveSheet()->getStyle("A{$contador}:BC{$contador}")->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO DE ASISTENCIA - PRIMER TRIMESTRE');
    
        $contador++;
     	$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'UNIDAD EDUCATIVA: ');
     	$this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($negra);
     	// $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":O".($contador));
    	$this->excel->getActiveSheet()->setCellValue("M{$contador}", $cole->nombre);
 	   	$this->excel->setActiveSheetIndex(0)->mergeCells("AJ".($contador).":AM".($contador));
 		$this->excel->getActiveSheet()->setCellValue("AJ{$contador}", 'AREA: ');
 		$this->excel->getActiveSheet()->getStyle("AJ{$contador}")->applyFromArray($negra);
 		$this->excel->setActiveSheetIndex(0)->mergeCells("AN".($contador).":AW".($contador));
  		$this->excel->getActiveSheet()->setCellValue("AN{$contador}","................................");
      	 $contador++;
     	$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'AÑO DE ESCOLARIDAD: ');
     	$this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($negra);
     	// $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":O".($contador));
      	$this->excel->getActiveSheet()->setCellValue("M{$contador}",$cursos->nombre);
 	   	$this->excel->setActiveSheetIndex(0)->mergeCells("AJ".($contador).":AM".($contador));
 		$this->excel->getActiveSheet()->setCellValue("AJ{$contador}", 'MATERIA: ');
 		$this->excel->getActiveSheet()->getStyle("AJ{$contador}")->applyFromArray($negra);
 		$this->excel->setActiveSheetIndex(0)->mergeCells("AN".($contador).":AW".($contador));
  		$this->excel->getActiveSheet()->setCellValue("AN{$contador}","...............................");        
        $contador++;
  
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'MAESTRO (A): ');
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($negra);
        // $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":O".($contador));
        $this->excel->getActiveSheet()->setCellValue("M{$contador}",".......................................");
        $this->excel->setActiveSheetIndex(0)->mergeCells("AJ".($contador).":AM".($contador));
        $this->excel->getActiveSheet()->setCellValue("AJ{$contador}", 'GESTION: ');
        $this->excel->getActiveSheet()->getStyle("AJ{$contador}")->applyFromArray($negra);
        $this->excel->setActiveSheetIndex(0)->mergeCells("AN".($contador).":AW".($contador));
        $this->excel->getActiveSheet()->setCellValue("AN{$contador}"," ".$gestion);

        $contador++;
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":BC".($contador));
        $this->excel->getActiveSheet()->getStyle("C{$contador}:BC{$contador}")->applyFromArray($titulo2);
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'PRIMER TRIMESTRE');
        $this->excel->setActiveSheetIndex(0)->mergeCells("AB".($contador).":AC".($contador));
        
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C".($contador))->applyFromArray($punt);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", '   MES  :');
        $this->excel->setActiveSheetIndex(0)->mergeCells("D".($contador).":S".($contador));
        $this->excel->getActiveSheet()->getStyle("D".($contador).":S".($contador+2))->applyFromArray($saber);
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", '');//MES 
        $this->excel->setActiveSheetIndex(0)->mergeCells("T".($contador).":AI".($contador));
        $this->excel->getActiveSheet()->getStyle("T".($contador).":AI".($contador+2))->applyFromArray($hacer);
        $this->excel->getActiveSheet()->setCellValue("T{$contador}", '');//HACER
        $this->excel->setActiveSheetIndex(0)->mergeCells("AJ".($contador).":AY".($contador));
        $this->excel->getActiveSheet()->getStyle("AJ".($contador).":AY".($contador+2))->applyFromArray($decir);
        $this->excel->getActiveSheet()->setCellValue("AJ{$contador}", '');//HACER
       	
        $contador++;
       
        
        // $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'VAR. EVAL');
        //$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->applyFromArray($punt);
        // $this->excel->getActiveSheet()->getStyle("D{$contador}:S{$contador}")->applyFromArray($saber);
        // $this->excel->getActiveSheet()->getStyle("T{$contador}:AI{$contador}")->applyFromArray($hacer);
        // $this->excel->getActiveSheet()->getStyle("AJ{$contador}:AY{$contador}")->applyFromArray($decir);


         $contador++;
        //Le aplicamos negrita a los títulos de la cabecera.
        
       	$this->excel->getActiveSheet()->getStyle("C".($contador-1).":C".($contador))->applyFromArray($punt);
        $this->excel->getActiveSheet()->getStyle("C".($contador-1).":C".($contador))->getAlignment()->setTextRotation(90);
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador-1).":C".($contador));
        $this->excel->getActiveSheet()->setCellValue("C".($contador-1), 'DIA');
        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->getAlignment()->setTextRotation(90);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador-3).":A".($contador));
        $this->excel->getActiveSheet()->getStyle("A".($contador-3).":A{$contador}")->applyFromArray($titulo2);
        $this->excel->getActiveSheet()->getStyle("A".($contador-3).":B".($contador-3))->applyFromArray($titulo2);
        $this->excel->getActiveSheet()->setCellValue("A".($contador-3), 'N');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador-3).":B".($contador));
        
       	$this->excel->getActiveSheet()->setCellValue("B".($contador-3), 'NOMBRES Y APELLIDOS');

        // $this->excel->getActiveSheet()->getStyle("D{$contador}:S{$contador}")->applyFromArray($saber);
        // $this->excel->getActiveSheet()->getStyle("T{$contador}:AI{$contador}")->applyFromArray($hacer);
        // $this->excel->getActiveSheet()->getStyle("AJ{$contador}:AY{$contador}")->applyFromArray($decir);
        $this->excel->getActiveSheet()->getStyle("AZ".($contador-2).":BC".($contador))->applyFromArray($final);
        $this->excel->getActiveSheet()->getStyle("AZ".($contador-2).":BC".($contador))->getAlignment()->setTextRotation(90);
        $this->excel->setActiveSheetIndex(0)->mergeCells("AZ".($contador-2).":AZ".($contador));
        $this->excel->getActiveSheet()->setCellValue("AZ".($contador-2), 'A/ATRASO');
        $this->excel->setActiveSheetIndex(0)->mergeCells("BA".($contador-2).":BA".($contador));
        $this->excel->getActiveSheet()->setCellValue("BA".($contador-2), 'L/LICENCIA');
        $this->excel->setActiveSheetIndex(0)->mergeCells("BB".($contador-2).":BB".($contador));
        $this->excel->getActiveSheet()->setCellValue("BB".($contador-2), 'F/FALTA');
        $this->excel->setActiveSheetIndex(0)->mergeCells("BC".($contador-2).":BC".($contador));
        $this->excel->getActiveSheet()->setCellValue("BC".($contador-2), 'P/PRESENTE');
        

        $x = 0;

		 foreach ($list as $estud) {
		 	$contador++;

		 	$x++;
		 	$this->excel->getActiveSheet()->getStyle("B".($contador).":C".($contador))->applyFromArray($estilobor);
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($titulo_n);
		 	$this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":C".($contador));
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno." ".$estud->apmaterno." ".$estud->nombre);
        	
		 } 
		 $x--;
		$this->excel->getActiveSheet()->getStyle("D".($contador-$x).":S".($contador))->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("T".($contador-$x).":AI".($contador))->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("AJ".($contador-$x).":AY".($contador))->applyFromArray($notas);
		$this->excel->getActiveSheet()->getStyle("AZ".($contador-$x).":BC".($contador))->applyFromArray($notas);
		// $this->excel->getActiveSheet()->getStyle("A{$contador}:BC{$contador}")->applyFromArray($notas);     
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso}_{$nivel}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        //$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');

        echo json_encode(array("status"=>TRUE));


	}
	public function d_planilla($id)
	{ 
		$ids=explode('W', $id, -1);
		// print_r($ids);
		// exit();
		$gestion=$ids[0]; 
		$curso=$ids[1];
		$nivel=$ids[2];
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);
		$cole=$this->estud->get_nivel_colegio($nivel);
		$cursos=$this->estud->get_cursos($curso);
		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('notas');

        $contador = 1;

         $rojo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fa6737')
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
         $naranja = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ffb748')
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
         $amarillo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'f9f966')
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
         $verde = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '95e886')
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
        'font'  => array(
        //'bold'  => true,
        'size'  => 9,
       //'color' => array('rgb' => 'ffffff'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
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
        $notas = array(
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //'color' => array('rgb' => 'd7eafa')
        ),
		'alignment' => array(
            'horizontal' =>  PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
        $punt = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fdf7ee')
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
        $act = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'd1d1d1')
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
         


        $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 20,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $titulo2 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 12,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '85b3ff')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $titulo_n = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '85b3ff')
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
        $titulo3 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'f2f2f2')
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
        $ser = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'c5ffe0')
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
        $final = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'c7fff6')
        ),
		'borders' => array(
          'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $saber = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'bbe1fe')
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
         $hacer = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fedebb')
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
         $ac = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ff6f6f')
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
         $decir = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fdfebb')
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
          $est = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'ff99ad')
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
          $esta1 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 12,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '00a3da')
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
          $esta2 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 9,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '90a8f9')
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
          $esta3 = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 8,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'face92')
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

          $negra = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID
         
        )
	);

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
       	$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(4);
        $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(3);//MAESTRO
        $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(3);
        $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(4);
        $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(5);


		// $this->excel->getActiveSheet()->getProtection()->setSheet(true); 
		$contador=$contador+3;;
		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":AC".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($titulo);
         $this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->getFont()->setBold(true);
        
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO PEDAGÓGICO ');
    
        $contador++;
     	$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'UNIDAD EDUCATIVA: ');
     	$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
     	$this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":O".($contador));
    	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $cole->nombre);
 	   	$this->excel->setActiveSheetIndex(0)->mergeCells("P".($contador).":S".($contador));
 		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'AREA: ');
 		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
 		$this->excel->setActiveSheetIndex(0)->mergeCells("T".($contador).":AC".($contador));
  		$this->excel->getActiveSheet()->setCellValue("T{$contador}","................................");
      	 $contador++;
     	$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'AÑO DE ESCOLARIDAD: ');
     	$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
     	$this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":O".($contador));
      	$this->excel->getActiveSheet()->setCellValue("C{$contador}",$cursos->nombre);
 	   	$this->excel->setActiveSheetIndex(0)->mergeCells("P".($contador).":S".($contador));
 		$this->excel->getActiveSheet()->setCellValue("P{$contador}", 'MATERIA: ');
 		$this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
 		$this->excel->setActiveSheetIndex(0)->mergeCells("T".($contador).":AC".($contador));
  		$this->excel->getActiveSheet()->setCellValue("T{$contador}","...............................");        
        $contador++;
  
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'MAESTRO (A): ');
        $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($negra);
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":O".($contador));
        $this->excel->getActiveSheet()->setCellValue("C{$contador}",".......................................");
        $this->excel->setActiveSheetIndex(0)->mergeCells("P".($contador).":S".($contador));
        $this->excel->getActiveSheet()->setCellValue("P{$contador}", 'GESTION: ');
        $this->excel->getActiveSheet()->getStyle("P{$contador}")->applyFromArray($negra);
        $this->excel->setActiveSheetIndex(0)->mergeCells("T".($contador).":AC".($contador));
        $this->excel->getActiveSheet()->setCellValue("T{$contador}"," ".$gestion);

        $contador++;
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":AB".($contador));
        $this->excel->getActiveSheet()->getStyle("C{$contador}:AB{$contador}")->applyFromArray($titulo2);
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'EVALUACIÓN MAESTRA (O)');
        $this->excel->setActiveSheetIndex(0)->mergeCells("AB".($contador).":AC".($contador));
        $contador++;
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":AB".($contador));
        $this->excel->getActiveSheet()->getStyle("C{$contador}:AB{$contador}")->applyFromArray($titulo3);
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'DIMENSIONES');
        $contador++;
        
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador).":M".($contador));
        $this->excel->getActiveSheet()->getStyle("C{$contador}:M{$contador}")->applyFromArray($saber);
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'SABER 45pt');//saber
        $this->excel->setActiveSheetIndex(0)->mergeCells("N".($contador).":W".($contador));
        $this->excel->getActiveSheet()->getStyle("N{$contador}:W{$contador}")->applyFromArray($hacer);
        $this->excel->getActiveSheet()->setCellValue("P{$contador}", 'HACER 45pt');//HACER
        $this->excel->setActiveSheetIndex(0)->mergeCells("X".($contador).":AB".($contador));
        $this->excel->getActiveSheet()->getStyle("X{$contador}:AB{$contador}")->applyFromArray($decir);
        $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'SER/DEC 10pt');//HACER
       	
        $contador++;
        //$this->excel->getActiveSheet()->getStyle("C{$contador}:AA{$contador}")->getAlignment()->setTextRotation(90);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);
        $this->excel->getActiveSheet()->getStyle("C{$contador}:AC{$contador}")->applyFromArray($punt);
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'VAR. EVAL');
        //$this->excel->getActiveSheet()->getStyle("D{$contador}:F{$contador}")->applyFromArray($punt);
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("L{$contador}", '45');
        $this->excel->getActiveSheet()->getStyle("M{$contador}")->applyFromArray($saber);
        $this->excel->getActiveSheet()->setCellValue("M{$contador}", 'PM');//SABER
        $this->excel->getActiveSheet()->setCellValue("N{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("O{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("P{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("Q{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("R{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("S{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("T{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("U{$contador}", '45');
        $this->excel->getActiveSheet()->setCellValue("V{$contador}", '45');
        $this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
        $this->excel->getActiveSheet()->setCellValue("X{$contador}", '10');
        $this->excel->getActiveSheet()->setCellValue("Y{$contador}", '10');
        $this->excel->getActiveSheet()->setCellValue("Z{$contador}", '10');
        $this->excel->getActiveSheet()->setCellValue("AA{$contador}", '10');
        $this->excel->getActiveSheet()->getStyle("AB{$contador}")->applyFromArray($decir);
        $this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'PM');//DECIDIR
         $contador++;
        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($act);

       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->getAlignment()->setTextRotation(90);
        //$this->excel->getActiveSheet()->getStyle("C{$contador}")->getAlignment()->setTextRotation(90);

		
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador-4).":A".($contador));
        $this->excel->getActiveSheet()->getStyle("A".($contador-4).":A{$contador}")->applyFromArray($titulo2);
        $this->excel->getActiveSheet()->getStyle("A".($contador-4).":B".($contador-4))->applyFromArray($titulo2);
        $this->excel->getActiveSheet()->setCellValue("A".($contador-4), 'N');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador-4).":B".($contador));
        
       	$this->excel->getActiveSheet()->setCellValue("B".($contador-4), 'NOMBRES Y APELLIDOS');
        
        $this->excel->getActiveSheet()->getStyle("M{$contador}")->applyFromArray($saber);
        $this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
        $this->excel->getActiveSheet()->getStyle("AB{$contador}")->applyFromArray($decir);
        $this->excel->getActiveSheet()->setCellValue("M{$contador}", 'PROMEDIO SABER');
        $this->excel->getActiveSheet()->setCellValue("W{$contador}", 'PROMEDIO HACER');
        // $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'PARTICIPACION');//DECIDIR
        // $this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'SOLID. Y HONEST.');//DECIDIR
        // $this->excel->getActiveSheet()->setCellValue("Z{$contador}", 'COMUNICACION');//DECIDIR
        // $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'DISCIPLINA');
        // $this->excel->getActiveSheet()->setCellValue("Y{$contador}", 'RESPON. PUNTUAL');
        // $this->excel->getActiveSheet()->setCellValue("Z{$contador}", 'HONESTIDAD');
        // $this->excel->getActiveSheet()->setCellValue("AB{$contador}", 'PROMEDIO SER/DEC');
        $this->excel->setActiveSheetIndex(0)->mergeCells("AC".($contador-4).":AC".($contador));
        $this->excel->getActiveSheet()->getStyle("AC".($contador-4))->getAlignment()->setTextRotation(90);
        $this->excel->getActiveSheet()->getStyle("AC".($contador-4).":AC{$contador}")->applyFromArray($final);
        $this->excel->getActiveSheet()->setCellValue("AC".($contador-4), 'TOTAL BIMESTRAL');

        

        $x = 1;

		 foreach ($list as $estud) {
		 	$contador++;
	    	$this->excel->getActiveSheet()->setCellValue("M{$contador}", "=ROUND(AVERAGE(D{$contador}:L{$contador}),0)"); 
	    	$this->excel->getActiveSheet()->setCellValue("W{$contador}", "=ROUND(AVERAGE(N{$contador}:V{$contador}),0)"); 
	    	$this->excel->getActiveSheet()->setCellValue("AB{$contador}", "=ROUND(AVERAGE(X{$contador}:AA{$contador}),0)"); 
	    	$this->excel->getActiveSheet()->setCellValue("AC{$contador}", "=ROUND(SUM(M{$contador}+W{$contador}+AB{$contador}),0)");

		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:AC{$contador}")->applyFromArray($notas);
		 	$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($estilobor);
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($titulo_n);
		 	$this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":C".($contador));
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno." ".$estud->apmaterno." ".$estud->nombre);

	        $this->excel->getActiveSheet()->getStyle("M{$contador}")->applyFromArray($saber);
	        $this->excel->getActiveSheet()->getStyle("W{$contador}")->applyFromArray($hacer);
	        $this->excel->getActiveSheet()->getStyle("AB{$contador}")->applyFromArray($decir);
	        $this->excel->getActiveSheet()->getStyle("AC{$contador}")->applyFromArray($final);
        	$x++;
		 }      
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso}_{$nivel}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        //$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');

        echo json_encode(array("status"=>TRUE));


	}

	public function ajax_get_idestnew()
	{
		//valores enviados 
		$table=$this->input->post('table');
		$cod=$this->input->post('cod');
		$idcamp='idest';

		$codgen='';
		$num_rows=$this->estud->get_count_rows_est($idcamp,$table);
		if($num_rows!=0)
		{
			$n=strlen($cod);		
			$list = $this->estud->get_idcod_table_est($idcamp,$table);
			$may=0;
				
			foreach ($list as $codigo) {	
				$idcod=$codigo->idest;
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
//

	public function print_generoCursoColegio($id)
	{ 
		
		$gestion=$id;


		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE GENEROS'),0,0,'C');
            $this->pdf->Ln('5'); 


            $this->pdf->Cell(30); 
			$this->pdf->setXY(15,55);
			$this->pdf->SetFont('Arial','B',10);
            
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setXY(35,55);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');
            
            $this->pdf->setX(65);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(80);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($nivel),0,0,'L');

            

            $this->pdf->Cell(30); 
			$this->pdf->setX(115); 
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('CURSO: '),0,0,'L');
             $this->pdf->setX(130); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setXY(15,65);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('F',$nivel,$colegio,$gestion,$cursos)),0,0,'L');
    		$this->pdf->setXY(15,70);
           
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('M',$nivel,$colegio,$gestion,$cursos)),0,0,'L');

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_report_fecha_exel($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0]; 
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		

		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		
		$curso=$this->estud->get_print_curso_pdf($cursos);

		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');

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
        //Contador de filas
        $contador = 1;
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(45);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(65);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);


     $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	 //$this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $nivel);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'COLEGIO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $colegio);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $gestion);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", $curso->curso);
        $contador++;$contador++;

        //Le aplicamos negrita a los títulos de la cabecera.
        //$this->excel->getActiveSheet()->getStyle("A{$contador}:L{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("A{$contador}:Q{$contador}")->applyFromArray($estilo);
        /*$this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("G{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("H{$contador}")->getFont()->setBold(true);*/
       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'AP. PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'AP. MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'RUDE');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'FEC. NACIMIENTO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'C.I.');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'EDAD');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'GEN');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'PADRE/MADRE/TUTOR');
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'DIRECCION DEL ESTUDIANTE');
        $this->excel->getActiveSheet()->setCellValue("L{$contador}", 'TEl PADRE');
        $this->excel->getActiveSheet()->setCellValue("M{$contador}", 'CEL PADRE');
        $this->excel->getActiveSheet()->setCellValue("N{$contador}", 'TEl MADRE');
        $this->excel->getActiveSheet()->setCellValue("O{$contador}", 'CEl MADRE');
        $this->excel->getActiveSheet()->setCellValue("P{$contador}", 'TEl TUTOR');
        $this->excel->getActiveSheet()->setCellValue("Q{$contador}", 'CEl TUTOR');
        
    
        $x = 1;
		 foreach ($list as $estud) {
		$padre=$this->estud->get_print_padre($estud->idest);
		$madre=$this->estud->get_print_madre($estud->idest);
		$tutor=$this->estud->get_print_tutor($estud->idest);
		if($estud->vivecon=="Padre y Madre")
		{
			if($padre=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$padre->appat." ".$padre->apmat." ".$padre->nombres;
				$tel1=$padre->ofifono;
				$cel1=$padre->celular;
			}		
		}
		if($estud->vivecon=="Solo Madre")
		{
			if($madre=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$madre->appat." ".$madre->apmat." ".$madre->nombres;
				$tel1=$madre->ofifono;
				$cel1=$madre->celular;
			}			
		}
		if($estud->vivecon=="Tutor")
		{
			if($tutor=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$tutor->appat." ".$tutor->apmat." ".$tutor->nombres;
				$tel1=$tutor->ofifono;
				$cel1=$tutor->celular;
			}		
		}
		if($estud->vivecon=="Solo Padre")
		{
			if($padre=='vacio')
			{
				$nombrev='';
				$tel1='';
				$cel1='';
			}else
			{
				$nombrev=$padre->appat." ".$padre->apmat." ".$padre->nombres;
				$tel1=$padre->ofifono;
				$cel1=$padre->celular;
			}			
		}
		 	$contador++;
		 	if($this->estud->get_print_fecha_pdf($estud->idest)=='0')
		 	{
		 		$dia='Re';	
		 		$mes='Re';	
		 		$anio='Re';	
		 		
		 	}
		 	else {
		 		$fecha=$this->estud->get_print_fecha_pdf($estud->idest);

		 		$dia=$fecha->dia;	
		 		$mes=$fecha->mes;	
		 		$anio=$fecha->anio;
		 	 
		 	}
		 	if($this->estud->get_estudiante_telefono($estud->idest)=='0')
		 	{
		 		//$fe='Re';	
		 		$ce='Re';
		 		$fp='Re';	
		 		$cp='Re';
		 		$fm='Re';	
		 		$cm='Re';
		 		$ft='Re';	
		 		$ct='Re';
		 		
		 	}
		 	else {
		 		$f=$this->estud->get_estudiante_telefono($estud->idest);
		 		//$fe=$f->foest;	
		 		$ce=$f->ceest;
		 		$fp=$f->fpa;	
		 		$cp=$f->cpa;
		 		$fm=$f->fma;	
		 		$cm=$f->cma;
		 		$ft=$f->ftu;	
		 		$ct=$f->ctu;
		 	 
		 	}

		 	if($this->estud->get_idinscrip_localizacion($estud->idest)=='0')
		 	{
		 		$dire='Re';	
		 		
		 	}
		 	else{
		 	  $direct=$this->estud->get_idinscrip_localizacion($estud->idest);
		      $dire='Zona : '.$direct->zona.' calle : '.$direct->calle.' N:'.$direct->num;
		      $dire=str_replace("SIN NUMERO", "S/N", $dire);
		      $dire=str_replace("CALLE", "c/", $dire);
		 	}
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:Q{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
       	 	$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", ' '. $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", ' '.$estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", ' '.$estud->rude);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $dia.'/'.$mes.'/'.$anio);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador}", ' '.date('Y')-$anio);
        	$this->excel->getActiveSheet()->setCellValue("I{$contador}", $estud->genero);
        	$this->excel->getActiveSheet()->setCellValue("J{$contador}", ' '.$nombrev);
        	$this->excel->getActiveSheet()->setCellValue("K{$contador}", ' '.$dire);
        	$this->excel->getActiveSheet()->setCellValue("L{$contador}", ' '.$fp);
        	$this->excel->getActiveSheet()->setCellValue("M{$contador}", ' '.$cp);
        	$this->excel->getActiveSheet()->setCellValue("N{$contador}", ' '.$fm);
        	$this->excel->getActiveSheet()->setCellValue("O{$contador}", ' '.$cm);
        	$this->excel->getActiveSheet()->setCellValue("P{$contador}", ' '.$ft);
        	$this->excel->getActiveSheet()->setCellValue("Q{$contador}", ' '.$ct);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->curso}_{$curso->nivel}_{$curso->colegio}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}

	public function print_report_est_curso($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		
		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);


		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        //Contador de filas
        $contador1 = 2;
       
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

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
      
      	 $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	 //$this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $nivel);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'COLEGIO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $colegio);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $gestion);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($estilo); 
      	// $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($estilobor); 
      	 $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $curso->curso);
        $contador1++;$contador1++;

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:H{$contador1}")->applyFromArray($estilo);
        

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'RUDE');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'CI');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", 'GEN');
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", 'FECHA NAC.');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador1++;
		 	//$lugarNac='';
		 	if($this->estud->get_print_fecha_pdf($estud->idest)=='0')
		 	{
		 		$dia='Re';	
		 		$mes='Re';	
		 		$anio='Re';
		 		
		 		$fec='Re';	
		 		
		 	}
		 	else {
		 		$fecha=$this->estud->get_print_fecha_pdf($estud->idest);
		 		$dia=$fecha->dia;	
		 		$mes=$fecha->mes;
		 		$anio=$fecha->anio;
		 		$fec= $dia.'/'.$mes.'/'.$anio;
		 		//$fec= strtotime('September');
		 	 
		 	}

		 	$this->excel->getActiveSheet()->getStyle("A{$contador1}:H{$contador1}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador1}", ' '.$estud->rude);
		    $this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador1}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador1}", $estud->genero);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador1}", $fec);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->curso}_{$curso->nivel}_{$curso->colegio}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}

	public function print_report_lugar_nac($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		
		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);


		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        //Contador de filas
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

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(80);
        //$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
       /* $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(10);*/

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:J{$contador}")->applyFromArray($estilo);
        /*$this->excel->getActiveSheet()->getStyle("A{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("B{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("D{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("G{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("H{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("I{$contador}")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle("J{$contador}")->getFont()->setBold(true);
        //$this->excel->getActiveSheet()->getStyle("K{$contador}")->getFont()->setBold(true);*/
       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'CI');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'DIA');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'MES');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'AÑO');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'GEN');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'LUGAR DE NACIMIENTO');
        //$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'DIRECCION');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador++;
		 	//$lugarNac='';
		 	if($this->estud->get_print_fecha_pdf($estud->idest)=='0')
		 	{
		 		$dia='Re';	
		 		$mes='Re';	
		 		$anio='Re';
		 		$lugarNac='Re';	
		 		
		 	}
		 	else {
		 		$fecha=$this->estud->get_print_fecha_pdf($estud->idest);
		 		$lugarNac='Pais: '.$fecha->pais.' Departamento: '.$fecha->dpto.' lugar: '.$fecha->localidad;
		 		$dia=$fecha->dia;	
		 		$mes=$fecha->mes;
		 		$anio=$fecha->anio;
		 	 
		 	}

		 	if($this->estud->get_idinscrip_localizacion($estud->idest)=='0')
		 	{
		 		$dire='Re';	
		 		
		 	}
		 	else{
		 	  $direct=$this->estud->get_idinscrip_localizacion($estud->idest);
		      $dire='Zona : '.$direct->zona.' calle : '.$direct->calle.' N:'.$direct->num;
		      $dire=str_replace("SIN NUMERO", "S/N", $dire);
		      $dire=str_replace("CALLE", "c/", $dire);
		 	}
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:J{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $dia);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $mes);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador}", $anio);
        	$this->excel->getActiveSheet()->setCellValue("I{$contador}", $estud->genero);
        	$this->excel->getActiveSheet()->setCellValue("J{$contador}", $lugarNac);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->curso}_{$curso->nivel}_{$curso->colegio}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}

	public function print_report_fecha($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		

		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS'),0,0,'C');
            $this->pdf->Ln('5');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($cursos),0,0,'L');
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($gestion),0,0,'C');
            $this->pdf->Ln('6'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
    		$this->pdf->setX(35);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(75); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('COLEGIO:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		//$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		//$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		//$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(25,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(25,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'DIA','TBLR',0,'C','1');
    		$this->pdf->Cell(20,7,'MES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'A','TBLR',0,'C','1');
    		$this->pdf->Cell(55,7,'DIRECCION','TBLR',0,'C','1');
    		//$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');


    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		     // $this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      //$this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $fecha=$this->estud->get_print_fecha_pdf($estud->idest);
		      $direct=$this->estud->get_idinscrip_localizacion($estud->idest);
		      //$dire='Zona : '.$direct->zona.' calle : '.$direct->calle.' N:'.$direct->num;
		      $dire=''.$direct->calle.' N'.$direct->num;
		      $dire=str_replace("SIN NUMERO", "S/N", $dire);
		      $dire=str_replace("CALLE", "c/", $dire);
		      $this->pdf->Cell(25,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(25,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,utf8_decode(strtoupper($fecha->dia)),'TBLR',0,'L',0);
		      $this->pdf->Cell(20,5,utf8_decode(strtoupper($fecha->mes)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,utf8_decode(strtoupper($fecha->anio)),'TBLR',0,'L',0);
		      $this->pdf->Cell(55,5,utf8_decode(strtoupper($dire)),'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		    	      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_mejor_notas_pdf($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);;
		

		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);
		
		$list=$this->estud->get_promedio_nota($nivel,$gestion);
		//sort($list, SORT_NUMERIC);
//print_r($list);
	//exit();
		asort($list->nota);
		//var_export($list);


		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('MEJORES NOTAS'),0,0,'C');
            $this->pdf->Ln('5'); 


            $this->pdf->Cell(30); 
			$this->pdf->setXY(15,55);
			$this->pdf->SetFont('Arial','B',10);
            
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setXY(35,55);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');
            $this->pdf->Ln('6');
            $this->pdf->setXY(15,60); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(30);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($nivel),0,0,'L');

            

            $this->pdf->Cell(30); 
			$this->pdf->setX(90); 
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('GESTION: '),0,0,'L');
             $this->pdf->setX(110); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($gestion),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setXY(15,65);
            $this->pdf->SetFont('Arial','B',10);

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);

			$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		//$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		//$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		//$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(20,7,'CURSO','TBLR',0,'C','1');
    		//$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		      //$this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      //$this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(20,5,utf8_decode(strtoupper($estud->curso)),'TBLR',0,'L',0);
		      $this->pdf->Cell(20,5,utf8_decode(strtoupper($estud->nota)),'TBLR',0,'L',0);
		      //$this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		      /*if($x==4)
		      {
		      	$this->pdf->Ln(40);

		    	$this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
		    	 ob_end_flush();
		      }	*/	      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);

		    }
		    $this->pdf->Ln(40);

		    	$this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
		    	 ob_end_flush();

		   


	}

	public function print_mejor_notas_exel($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		

		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);
		
		$list=$this->estud->get_promedio_nota($nivel,$gestion);

			$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');

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

		
        //Le aplicamos ancho las columnas.
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
        //$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->applyFromArray($estilo);

       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NOTAS');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'GEN');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador++;
		 
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:G{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->nota);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $estud->curso);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $estud->genero);
        	
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$nivel}_{$colegio}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');


	}
	public function print_generoCurso($id)
	{ 
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$colegio=$ids[2];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);;
		

		$this->load->library('pdf');
		
		$curso=$this->estud->get_print_curso_pdf($cursos);


		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE GENEROS'),0,0,'C');
            $this->pdf->Ln('5'); 


            $this->pdf->Cell(30); 
			$this->pdf->setXY(15,55);
			$this->pdf->SetFont('Arial','B',10);
            
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setXY(35,55);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($colegio),0,0,'L');
            $this->pdf->Ln('6');
            $this->pdf->setXY(15,60); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(30);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($nivel),0,0,'L');

            

            $this->pdf->Cell(30); 
			$this->pdf->setX(90); 
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('CURSO: '),0,0,'L');
             $this->pdf->setX(110); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setXY(15,65);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('F',$nivel,$colegio,$gestion,$cursos)),0,0,'L');
    		$this->pdf->setXY(15,70);
           
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_generoCurso_pdf('M',$nivel,$colegio,$gestion,$cursos)),0,0,'L');

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_genero($id)
	{

		$n=strlen($id);
		$id1=substr($id,0,4);//gestion
		$id2=substr($id,4,$n);//idcur

		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($id1,$id2);
		$generos=$this->estud->get_print_genero_pdf('F','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019');
		$curso=$this->estud->get_print_curso_pdf($id2);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE GENEROS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE GENEROS'),0,0,'C');
            $this->pdf->Ln('5');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
			//primaria mañana
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('PRIMARIA MAÑANA'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('TECNICO HUMANISTICO DON BOSCO'),0,0,'L');
            
            $this->pdf->Ln('6'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,55);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')+$this->estud->get_print_genero_pdf('F','PRIMARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		//secundaria mañana
    		 $this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,65);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,65);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('SECUNDARIA MAÑANA'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('TECNICO HUMANISTICO DON BOSCO'),0,0,'L');
            
            $this->pdf->Ln('8'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')+$this->estud->get_print_genero_pdf('F','SECUNDARIA MAÑANA','TECNICO HUMANISTICO DON BOSCO','2019')),0,0,'L');

    		//primaria tarde 
    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,85);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,85);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('PRIMARIA TARDE'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('DON BOSCO A'),0,0,'L');
            
            $this->pdf->Ln('8'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','PRIMARIA TARDE','DON BOSCO A','2019')),0,0,'L');
            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA TARDE','DON BOSCO A','2019')),0,0,'L');

    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,98);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','PRIMARIA TARDE','DON BOSCO A','2019')+$this->estud->get_print_genero_pdf('F','PRIMARIA TARDE','DON BOSCO A','2019')),0,0,'L');


    		//secundaria tarde
    		 $this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,105);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setXY(28,105);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('SECUNDARIA TARDE'),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('COLEGIO: '),0,0,'L');
            $this->pdf->setX(100);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode('DON BOSCO B'),0,0,'L');
            $this->pdf->Ln('8'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE FEMENINO: '),0,0,'L');
    		$this->pdf->setX(65);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('F','SECUNDARIA TARDE','DON BOSCO B','2019')),0,0,'L');

            $this->pdf->setX(78);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CANTIDAD DE MASCULINO: '),0,0,'L');
    		$this->pdf->setX(130);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA TARDE','DON BOSCO B','2019')),0,0,'L');


    		$this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,120);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('TOTAL: '),0,0,'L');
    		$this->pdf->setX(30);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($this->estud->get_print_genero_pdf('M','SECUNDARIA TARDE','DON BOSCO B','2019')+$this->estud->get_print_genero_pdf('F','SECUNDARIA TARDE','DON BOSCO B','2019')),0,0,'L');

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		/*$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');*/
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		   /* foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		      $this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      $this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		      		      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }*/
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_report($id)
	{
		$n=strlen($id);
		$id1=substr($id,0,4);//gestion
		$id2=substr($id,4,$n);//idcur


		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($id1,$id2);
		$curso=$this->estud->get_print_curso_pdf($id2);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS'),0,0,'C');
            $this->pdf->Ln('5');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($id2),0,0,'L');
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('NOMBRE: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso->curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($id1),0,0,'C');
            $this->pdf->Ln('6'); 
            $this->pdf->setX(15);
            $this->pdf->SetFont('Arial','B',10);
    		$this->pdf->Cell(30,5,utf8_decode('CURSO: '),0,0,'L');
    		$this->pdf->setX(35);
    		$this->pdf->SetFont('Arial','',10);
    		$this->pdf->Cell(30,5,utf8_decode($curso->corto),0,0,'L');
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(75); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($curso->nivel),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('COLEGIO:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($curso->colegio),0,0,'L');
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(34,7,'RUDE','TBL',0,'C','1');
    		$this->pdf->Cell(25,7,'CI','TBL',0,'C','1');
    		$this->pdf->Cell(10,7,'COD','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');
    		$this->pdf->Cell(10,7,'GEN','TBLR',0,'L','1');
    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;
		    foreach ($list as $estud) {
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		      $this->pdf->Cell(34,5,$estud->rude,'TBL',0,'L',0);
		      $this->pdf->Cell(25,5,$estud->ci,'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->codigo,'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombres)),'TBLR',0,'L',0);
		      $this->pdf->Cell(10,5,$estud->genero,'TBLR',0,'C',0);
		      		      
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function print_report_fono($id)
	{
		$ids=explode('-', $id, -1);
		$gestion=$ids[0]; 
		$nivel=$ids[1];
		$curso=$ids[2];

		// if($nivel=="PT") {$nivel="PRIMARIA TARDE"; }
		// if($nivel=="ST") {$nivel="SECUNDARIA TARDE"; }
		// if($nivel=="PM") {$nivel="PRIMARIA MAÑANA"; }
		// if($nivel=="SM") {$nivel="SECUNDARIA MAÑANA"; }
		// if($curso=='1A'){$curso='PRIMERO A';}
		// if($curso=='1B'){$curso='PRIMERO B';}
		// if($curso=='2A'){$curso='SEGUNDO A';}
		// if($curso=='2B'){$curso='SEGUNDO B';}
		// if($curso=='3A'){$curso='TERCERO A';}
		// if($curso=='3B'){$curso='TERCERO B';}
		// if($curso=='4A'){$curso='CUARTO A';}
		// if($curso=='4B'){$curso='CUARTO B';}
		// if($curso=='5A'){$curso='QUINTO A';}
		// if($curso=='5B'){$curso='QUINTO B';}
		// if($curso=='5C'){$curso='QUINTO C';}
		// if($curso=='6A'){$curso='SEXTO A';}
		// if($curso=='6B'){$curso='SEXTO B';}
		// if($curso=='6C'){$curso='SEXTO C';}
		// print_r($ids);
		// exit();
		$this->load->library('pdf');
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);
		
		// $curso=$this->estud->get_print_curso_pdf($id2);
		/*print_r($list);
		print_r($curso);
		exit();*/

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS- TELEFONOS'),0,0,'C');
            $this->pdf->Ln('7');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->setX(55);  
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(45,5,utf8_decode('GRADO: '),0,0,'L');
            $this->pdf->setX(75);  
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($curso),0,0,'L');
            $this->pdf->SetX(97);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(55,5,utf8_decode('GESTION:'),0,0,'C');
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(55,5,utf8_decode($gestion),0,0,'C');
            $this->pdf->Ln('6'); 
            $this->pdf->setX(55); 
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(60,5,utf8_decode('NIVEL: '),0,0,'L');
            $this->pdf->setX(75); 
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(60,5,utf8_decode($nivel),0,0,'L');	
            $this->pdf->SetX(115);
            $this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(65,5,utf8_decode('COLEGIO:'),0,0,'L');
            $this->pdf->SetX(138);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(65,5,utf8_decode($ids[3]),0,0,'L');
            $this->pdf->Ln('3'); 

    		$this->pdf->SetLeftMargin(15);
    		$this->pdf->SetRightMargin(15);
    		$this->pdf->SetFillColor(192,192,192);
    		$this->pdf->SetFont('Arial', 'B', 8);
    		$this->pdf->Ln(5);
    		$this->pdf->Cell(10,7,'NUM','TBL',0,'L','1');
    		$this->pdf->Cell(20,7,'CEL PADRE','TBL',0,'C','1');
    		$this->pdf->Cell(20,7,'CEL MADRE','TBL',0,'C','1');
    		$this->pdf->Cell(18,7,'FONO FIJO','TBL',0,'C','1');
    		$this->pdf->Cell(20,7,'CEL TUTOR','TBLR',0,'L','1');
    		$this->pdf->Cell(30,7,'PATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(30,7,'MATERNO','TBLR',0,'C','1');
    		$this->pdf->Cell(38,7,'NOMBRES','TBLR',0,'C','1');

    		
    		$this->pdf->Ln(7);
    		
    		$this->pdf->SetFont('Arial', '', 8);
    		$x = 1;

		    
		    foreach ($list as $estud) {
		    	//print_r($estud->idest);
		    	
		      $tutores=$this->estud->get_print_tutores("PADRE",$estud->id_est);
		      // print_r($tutores);
		      // exit();
		      $padre=$this->estud->get_print_padres($tutores->id_padre);
		      $tutores=$this->estud->get_print_tutores("MADRE",$estud->id_est);
		      $madre=$this->estud->get_print_padres($tutores->id_padre);
		      $tutores=$this->estud->get_print_tutores("TUTOR",$estud->id_est);
		      $tutor=$this->estud->get_print_padres($tutores->id_padre);

		   
		      //print_r($fonopadre->celular."-");
		     
		     // print_r($fonotutor->celular."-");
		      $this->pdf->Cell(10,5,$x++,'TBL',0,'C',0);
		      // Se imprimen los datos de cada alumno
		      //$this->pdf->Cell(17,5,$estud->idest,'TBL',0,'L',0);
		     $this->pdf->Cell(20,5,utf8_decode($padre->celular),'TBLR',0,'C',0);
		     $this->pdf->Cell(20,5,utf8_decode($madre->celular),'TBLR',0,'C',0);
		     $this->pdf->Cell(18,5,utf8_decode($padre->telefono),'TBLR',0,'C',0);

		     $this->pdf->Cell(20,5,utf8_decode($tutor->celular),'TBLR',0,'C',0);		     
		      		      
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->appaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(30,5,utf8_decode(strtoupper($estud->apmaterno)),'TBLR',0,'L',0);
		      $this->pdf->Cell(38,5,utf8_decode(strtoupper($estud->nombre)),'TBLR',0,'L',0);
		     	
		      
		    
		  /*  if($x==2)
		    {
		    		$this->pdf->Output("Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".pdf", 'I');
		    	exit();
		    }	 */     
		      //Se agrega un salto de linea
		      $this->pdf->Ln(5);
		    }
		    $this->pdf->Ln(40);

		    $this->pdf->Output("Lista Alumnos -".$curso."- ".$nivel." -".$gestion.".pdf", 'I');

		    ob_end_flush();


	}

	public function books_report($id) {
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$nivel=$ids[1];
		$curso=$ids[2];
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);

		
		$cursos=$this->estud->get_cursos($curso);
		$niveles=$this->estud->get_nivel_colegio($nivel);

		// print_r($niveles);
		// exit();
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('direccion');
        
		$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

		$contador = 1;
       
		$estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
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
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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

		$titulo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 16,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
					// 'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		
		$negrita = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				// 'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
						// 'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

					
		$contador=2;

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5); // Num
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(8); // ID_EST
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(12); // Código
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(40); // Nombre Completo
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(8); // Curso
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(40); // Nombre del Padre
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20); // Contacto padre
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(40); // Nombre de la Madre
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20); // Contacto madre
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20); // Con quien vive
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30); // Contacto domicilio


		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(100);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());

		$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":H".($contador));
		$this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($titulo);
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'LISTADO DE ESTUDIANTES - CURSO '.$curso);
				
		$contador++;$contador++;$contador++;


					
		$contador++;
		$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'NIVEL');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nivel." ".$niveles->turno);
		$this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'U.E.');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", $niveles->nombre);
		$this->excel->getActiveSheet()->getStyle("H{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'AÑO DE ESCOR.');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", $cursos->nombre);
		$this->excel->getActiveSheet()->getStyle("J{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'GESTION: '.$gestion);

		$contador++;$contador++;
			//Definimos los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilo);
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'ID_EST');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'CODIGO');
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE COMPLETO (ESTUDIANTE)');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'CURSO');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", 'NOMBRE PPFF (PADRE)');
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'CONTACTO (PADRE)');
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'NOMBRE PPFF (MADRE)');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", 'CONTACTO (MADRE)');
		$this->excel->getActiveSheet()->setCellValue("J{$contador}", 'CON QUIEN VIVE');
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'CONTACTO DOMICILIO');
		$x = 1;
		foreach ($list as $estud) {
			$contador++;
			$direccion = $this->estud->get_direccion($estud->id_est, 2021); // dev_test : esta quemado el año
			$padre = $this->estud->get_padres($estud->id_est, 'PADRE');
			$madre = $this->estud->get_padres($estud->id_est, 'MADRE');
			$inscripcion = $this->estud->get_data_inscrips($estud->id_est, 2021); // dev_test : esta quemado el año
			
			$this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
			$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->id_est);
			$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->codigo);
			$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->appaterno.' '.$estud->apmaterno.' '.$estud->nombre);
			$this->excel->getActiveSheet()->setCellValue("E{$contador}", $curso);
			$this->excel->getActiveSheet()->setCellValue("F{$contador}", $padre->nombre.' '.$padre->appaterno.' '.$padre->apmaterno);
			$this->excel->getActiveSheet()->setCellValue("G{$contador}", $padre->celular);
			$this->excel->getActiveSheet()->setCellValue("H{$contador}", $madre->nombre.' '.$madre->appaterno.' '.$madre->apmaterno);
			$this->excel->getActiveSheet()->setCellValue("I{$contador}", $madre->celular);
			$this->excel->getActiveSheet()->setCellValue("J{$contador}", $inscripcion->vive);
			$this->excel->getActiveSheet()->setCellValue("K{$contador}", $direccion->celular);
			$x++;
		}

		//Le ponemos un nombre al archivo que se va a generar.
		$archivo = "{$cursos->curso}_{$niveles->nivel}_{$niveles->nombre}_{$gestion}.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');
	}

	public function filiacion_current($id) {
		$ids=explode('W', $id, -1);
		$gestion=$ids[0];
		$nivel=$ids[1];
		$curso=$ids[2];
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);

		
		$cursos=$this->estud->get_cursos($curso);
		$niveles=$this->estud->get_nivel_colegio($nivel);

		//$colegio=$this->estud->get_ant_colegio($gestion,$estud->id_est);
		// print_r($niveles);
		// exit();
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('direccion');
        
		$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

		$contador = 1;
       
		$estilo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => 'ffffff'),
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
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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

		$titulo = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 16,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
					// 'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		
		$negrita = array(
			'font'  => array(
				'bold'  => true,
				'size'  => 10,
				'color' => array('rgb' => '000000'),
				//'startcolor' => array('rgb' => '00B050'),
				'name'  => 'Verdana'
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				// 'color' => array('rgb' => 'fbfcf9')
			),
			'borders' => array(
				'allborders' => array(
						// 'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);

					
		$contador=2;

		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(25);


		$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(100);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());

		$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":L".($contador));
		$this->excel->getActiveSheet()->getStyle("A{$contador}:L{$contador}")->applyFromArray($titulo);
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO DE FILIACION DEL ESTUDIANTE');
				
		$contador++;$contador++;$contador++;


					
		$contador++;
		$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'NIVEL');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nivel." ".$niveles->turno);
		$this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'U.E.');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", $niveles->nombre);
		$this->excel->getActiveSheet()->getStyle("H{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'AÑO DE ESCOR.');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", $cursos->nombre);
		$this->excel->getActiveSheet()->getStyle("K{$contador}")->applyFromArray($negrita); 
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'GESTION: '.$gestion);

		$contador++;$contador++;
			//Definimos los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:M{$contador}")->applyFromArray($estilo);
		$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":A".($contador+1)); 
		$this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
		$this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":D".($contador));
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'APELLIDOS Y NOMBRES');
		// $this->excel->setActiveSheetIndex(0)->mergeCells("E".($contador).":E".($contador+1)); 
		// $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'CODIGO RUDE');
		$this->excel->setActiveSheetIndex(0)->mergeCells("E".($contador).":H".($contador));
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'DATOS DEL ESTUDIANTE');
		$this->excel->setActiveSheetIndex(0)->mergeCells("I".($contador).":J".($contador));
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", 'DATOS DEL PADRE');
		$this->excel->setActiveSheetIndex(0)->mergeCells("K".($contador).":L".($contador));
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'DATOS DE LA MADRE');
		$this->excel->setActiveSheetIndex(0)->mergeCells("M".($contador).":M".($contador+1));
		$this->excel->getActiveSheet()->setCellValue("M{$contador}", 'CON QUIEN VIVE');
		$contador++;
		//Le aplicamos negrita a los títulos de la cabecera.
		$this->excel->getActiveSheet()->getStyle("A{$contador}:M{$contador}")->applyFromArray($estilo);
		//Definimos los títulos de la cabecera.
		// $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
		$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
		$this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
		$this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
		// $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'CODIGO RUDE');
		$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'FECHA DE NACIMIENTO');
		$this->excel->getActiveSheet()->setCellValue("F{$contador}", 'EDAD');
		$this->excel->getActiveSheet()->setCellValue("G{$contador}", 'SEXO');
		$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'NRO. CELULAR');
		$this->excel->getActiveSheet()->setCellValue("I{$contador}", 'NOMBRE COMPLETO');
		$this->excel->getActiveSheet()->setCellValue("J{$contador}", 'NRO. CELULAR');
		$this->excel->getActiveSheet()->setCellValue("K{$contador}", 'NOMBRE COMPLETO');
		$this->excel->getActiveSheet()->setCellValue("L{$contador}", 'NRO. CELULAR');
		// $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'E.U. PROCEDENCIA'); //E.U. PROCEDENCIA
		// $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'OBSERVACION'); //OBSERVACION
			
		$x = 1;
		foreach ($list as $estud) {
			$contador++;
			$nacimiento=$this->estud->get_nacimiento($estud->id_est);
			$direccion = $this->estud->get_direccion($estud->id_est, 2021); // dev_test : esta quemado el año
			$padre = $this->estud->get_padres($estud->id_est, 'PADRE');
			$madre = $this->estud->get_padres($estud->id_est, 'MADRE');
			$inscripcion = $this->estud->get_data_inscrips($estud->id_est, 2021); // dev_test : esta quemado el año
			
			$this->excel->getActiveSheet()->getStyle("A{$contador}:M{$contador}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
			$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
			$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
			$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombre);
			$this->excel->getActiveSheet()->setCellValue("E{$contador}", $nacimiento->fnacimiento);
			$this->excel->getActiveSheet()->setCellValue("F{$contador}", $gestion-($nacimiento->año));
			$this->excel->getActiveSheet()->setCellValue("G{$contador}", ' '.$estud->genero);
			$this->excel->getActiveSheet()->setCellValue("H{$contador}", $direccion->celular);
			$this->excel->getActiveSheet()->setCellValue("I{$contador}", $padre->nombre.' '.$padre->appaterno.' '.$padre->apmaterno);
			$this->excel->getActiveSheet()->setCellValue("J{$contador}", $padre->celular);
			$this->excel->getActiveSheet()->setCellValue("K{$contador}", $madre->nombre.' '.$madre->appaterno.' '.$madre->apmaterno);
			$this->excel->getActiveSheet()->setCellValue("L{$contador}", $madre->celular);
			$this->excel->getActiveSheet()->setCellValue("M{$contador}", $inscripcion->vive);
			// $this->excel->getActiveSheet()->setCellValue("I{$contador}", $estud->genero);
			// $this->excel->getActiveSheet()->setCellValue("J{$contador}", $niveles->nombre);

			$x++;
		}

		//Le ponemos un nombre al archivo que se va a generar.
		$archivo = "{$cursos->curso}_{$niveles->nivel}_{$niveles->nombre}_{$gestion}.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');
	}

	public function filaccion($id)
	{ 
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$nivel=$ids[1];
		$curso=$ids[2];
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);
		
		$cursos=$this->estud->get_cursos($curso);
		$niveles=$this->estud->get_nivel_colegio($nivel);

		 	//$colegio=$this->estud->get_ant_colegio($gestion,$estud->id_est);
		// print_r($niveles);
		// exit();
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
         $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 16,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
         $negrita = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            // 'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);

        
		$contador=2;
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30);


	$this->drawing->setName('Logotipo1');
	$this->drawing->setDescription('Logotipo1');
	$img = imagecreatefrompng('assets/images/logo.png');
	$this->drawing->setImageResource($img);
	$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$this->drawing->setHeight(100);
	$this->drawing->setCoordinates('A1');
	$this->drawing->setWorksheet($this->excel->getActiveSheet());

        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":K".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($titulo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO DE FILIACION DEL ESTUDIANTE');
       
        	 $contador++;$contador++;$contador++;


        
        $contador++;
       	$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nivel." ".$niveles->turno);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'U.E.');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", $niveles->nombre);
        $this->excel->getActiveSheet()->getStyle("H{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'AÑO DE ESCOR.');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", $cursos->nombre);
        $this->excel->getActiveSheet()->getStyle("K{$contador}")->applyFromArray($negrita); 
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'GESTION: '.$gestion);

        $contador++;$contador++;
         //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":A".($contador+1)); 
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":D".($contador));
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'APELIIDOS Y NOMBRES');
        $this->excel->setActiveSheetIndex(0)->mergeCells("E".($contador).":E".($contador+1)); 
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'CODIGO RUDE');
        $this->excel->setActiveSheetIndex(0)->mergeCells("F".($contador).":K".($contador));
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'DATOS DEL ESTUDIANTE');
        $contador++;
        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilo);
        //Definimos los títulos de la cabecera.
        // $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
        // $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'CODIGO RUDE');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'LUGAR DE NACIMIENTO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'FECHA');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'EDAD');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'SEXO');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'E.U. PROCEDENCIA');
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'OBSERVACION');
    
        $x = 1;
		 foreach ($list as $estud) {
		 	$contador++;
		 	$nacimiento=$this->estud->get_nacimiento($estud->id_est);
		 	
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
        	$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombre);
		    $this->excel->getActiveSheet()->setCellValue("E{$contador}", ' '.$estud->rude);
		    $this->excel->getActiveSheet()->setCellValue("F{$contador}", $nacimiento->pais."-".$nacimiento->departamento."-".$nacimiento->provincia."-".$nacimiento->localidad);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $nacimiento->fnacimiento);
		    $this->excel->getActiveSheet()->setCellValue("H{$contador}", $gestion-($nacimiento->año));
		    $this->excel->getActiveSheet()->setCellValue("I{$contador}", $estud->genero);
		    $this->excel->getActiveSheet()->setCellValue("J{$contador}", $niveles->nombre);

        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$cursos->curso}_{$niveles->nivel}_{$niveles->nombre}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}

	public function generocurso($id)
	{
		$ids=explode('W', $id, -1);
		// print_r($ids);
		// exit();
		$gestion=$ids[0];
		$nivel=$ids[1];
		$list=$this->estud->get_rows_cursos($nivel);
		
		
		$niveles=$this->estud->get_nivel_colegio($nivel);
		// print_r($niveles);
		// exit();
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
         $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 11,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
         $negrita = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            // 'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);

        
		$contador=2;
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(13);


	$this->drawing->setName('Logotipo1');
	$this->drawing->setDescription('Logotipo1');
	$img = imagecreatefrompng('assets/images/logo.png');
	$this->drawing->setImageResource($img);
	$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$this->drawing->setHeight(80);
	$this->drawing->setCoordinates('A1');
	$this->drawing->setWorksheet($this->excel->getActiveSheet());

        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":D".($contador));
        $this->excel->getActiveSheet()->getStyle("B{$contador}:D{$contador}")->applyFromArray($titulo);
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'CANTIDAD DE GENERO');
       
        $contador++;$contador++;$contador++;

        $contador++;
       	$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nivel." ".$niveles->turno);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'U.E.');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nombre);
       //  $contador++;
       //  $this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
      	// $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'AÑO DE ESC.');
       //  $this->excel->getActiveSheet()->setCellValue("C{$contador}", $cursos->nombre);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'GESTION:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $gestion);
        
        $contador++;$contador++;
    	$contador++;
	 	$this->excel->getActiveSheet()->getStyle("A{$contador}:D{$contador}")->applyFromArray($estilo);
	    $this->excel->getActiveSheet()->setCellValue("A{$contador}", "NUM");
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'CURSO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'FEMENINO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'MASCULINO');
        $x = 1;
		 foreach ($list as $estud) {
		 	$masculino=$this->estud->get_cantidad_genero($gestion,"M",$nivel,$estud->codigo);
		 	$fenenino=$this->estud->get_cantidad_genero($gestion,"F",$nivel,$estud->codigo);
	        $contador++;
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:D{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
	        $this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->nombre);
	        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $masculino->cantidad);
	        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $fenenino->cantidad);

        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "estadistica de genero_{$niveles->nivel}_{$niveles->nombre}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}
	public function datosPPFFCurso($id)
	{
		$ids=explode('W', $id, -1);
		// print_r($ids);
		// exit();
		$gestion=$ids[0];
		$nivel=$ids[1];
		$curso=$ids[2];
		$list=$this->estud->get_curso_student($gestion,$curso,$nivel);
		
		$cursos=$this->estud->get_cursos($curso);
		$niveles=$this->estud->get_nivel_colegio($nivel);
		// print_r($niveles);
		// exit();
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
         $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 16,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
         $negrita = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            // 'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);

        
		$contador=2;
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(45);


	$this->drawing->setName('Logotipo1');
	$this->drawing->setDescription('Logotipo1');
	$img = imagecreatefrompng('assets/images/logo.png');
	$this->drawing->setImageResource($img);
	$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$this->drawing->setHeight(100);
	$this->drawing->setCoordinates('A1');
	$this->drawing->setWorksheet($this->excel->getActiveSheet());

        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":K".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($titulo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO DE P.P.F.F. DEL ESTUDIANTE');
       
        	 $contador++;$contador++;$contador++;


        
        $contador++;
       	$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nivel." ".$niveles->turno);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'U.E.');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", $niveles->nombre);
        $this->excel->getActiveSheet()->getStyle("H{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("H{$contador}", 'AÑO DE ESCOR.');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", $cursos->nombre);
        $this->excel->getActiveSheet()->getStyle("K{$contador}")->applyFromArray($negrita); 
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'GESTION: '.$gestion);

        $contador++;$contador++;
         //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":A".($contador+1)); 
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":D".($contador));
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'APELIIDOS Y NOMBRES');
        $this->excel->setActiveSheetIndex(0)->mergeCells("E".($contador).":F".($contador)); 
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'DATOS DEL PADRE');
        $this->excel->setActiveSheetIndex(0)->mergeCells("G".($contador).":H".($contador)); 
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'DATOS DEL MADRE');
        $this->excel->setActiveSheetIndex(0)->mergeCells("I".($contador).":J".($contador)); 
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'DATOS DEL TUTOR');
        $this->excel->setActiveSheetIndex(0)->mergeCells("K".($contador).":K".($contador+1)); 
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'DIRECCION');
        $contador++;
        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilo);
        //Definimos los títulos de la cabecera.
        // $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'CELULAR');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'CELULAR');
        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'NOMBRE');
        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'CELULAR');
        // $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'OBSERVACION');
    
        $x = 9;
		 foreach ($list as $estud) {
		 	$contador++;
		 	$direccion=$this->estud->get_direccion($estud->id_est,$gestion);
		 	$madre=$this->estud->get_padres($estud->id_est,'MADRE');
		 	$padre=$this->estud->get_padres($estud->id_est,'PADRE');
		 	$tutor=$this->estud->get_padres($estud->id_est,'TUTOR');
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
        	$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombre);
		    $this->excel->getActiveSheet()->setCellValue("E{$contador}", $padre->appaterno." ".$padre->apmaterno." ".$padre->nombre);
		    $this->excel->getActiveSheet()->setCellValue("F{$contador}", $padre->celular);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $madre->appaterno." ".$madre->apmaterno." ".$madre->nombre);
		    $this->excel->getActiveSheet()->setCellValue("H{$contador}", $madre->celular);
		    $this->excel->getActiveSheet()->setCellValue("I{$contador}", $tutor->appaterno." ".$tutor->apmaterno." ".$tutor->nombre);
		    $this->excel->getActiveSheet()->setCellValue("J{$contador}", $tutor->celular);
		    $this->excel->getActiveSheet()->setCellValue("K{$contador}", $direccion->zona."-".$direccion->calle."-".$direccion->nro);

        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$cursos->curso}_{$niveles->nivel}_{$niveles->nombre}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}
	public function datosPPFFcolegio($id)
	{
		$ids=explode('W', $id, -1);
		// print_r($ids);
		// exit();
		$gestion=$ids[0];
		$nivel=$ids[1];
		
		
		$niveles=$this->estud->get_nivel_colegio($nivel);
		$curso=$this->estud->get_nivel_curso($niveles->id_nivel);
		
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
         $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 16,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);
         $negrita = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 10,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            // 'color' => array('rgb' => 'fbfcf9')
        ),
		'borders' => array(
          'allborders' => array(
              // 'style' => PHPExcel_Style_Border::BORDER_THIN
          )
      ),
		'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
	);

        
		$contador=2;
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(11);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(12);


	$this->drawing->setName('Logotipo1');
	$this->drawing->setDescription('Logotipo1');
	$img = imagecreatefrompng('assets/images/logo.png');
	$this->drawing->setImageResource($img);
	$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$this->drawing->setHeight(100);
	$this->drawing->setCoordinates('A1');
	$this->drawing->setWorksheet($this->excel->getActiveSheet());

        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":K".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}:K{$contador}")->applyFromArray($titulo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'REGISTRO DE P.P.F.F. DEL ESTUDIANTE');
       
        	 $contador++;$contador++;$contador++;


        
        $contador++;
       	$this->excel->getActiveSheet()->getStyle("B{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("B{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", $niveles->nivel." ".$niveles->turno);
        $this->excel->getActiveSheet()->getStyle("E{$contador}")->applyFromArray($negrita); 
      	$this->excel->getActiveSheet()->setCellValue("E{$contador}", 'U.E.');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", $niveles->nombre);
        $this->excel->getActiveSheet()->getStyle("K{$contador}")->applyFromArray($negrita); 
        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'GESTION: '.$gestion);

        
        $contador++;
        //Le aplicamos negrita a los títulos de la cabecera.
        
        // $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'OBSERVACION');

    	foreach ($curso as $cursos) {		
			$list=$this->estud->get_curso_student($gestion,$cursos->cod_curso,$nivel);
			$cur=$this->estud->get_cursos($cursos->cod_curso);
		// 	print_r($list);
		// exit();
	        $x = 1;
	        $contador++; $contador++; $contador++;
	        $this->excel->getActiveSheet()->getStyle("A{$contador}:S{$contador}")->applyFromArray($estilo);
	        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":X".($contador));
	        $this->excel->getActiveSheet()->setCellValue("A{$contador}", $cur->nombre);
	        $contador++;
	         //Definimos los títulos de la cabecera.
	        $this->excel->getActiveSheet()->getStyle("A{$contador}:S{$contador}")->applyFromArray($estilo);
	        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":A".($contador+1)); 
	        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'CODIGO');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador).":D".($contador));
	        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'APELIIDOS Y NOMBRES');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("E".($contador).":H".($contador)); 
	        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'DATOS DEL PADRE');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("I".($contador).":L".($contador)); 
	        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'DATOS DEL MADRE');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("M".($contador).":O".($contador)); 
	        $this->excel->getActiveSheet()->setCellValue("P{$contador}", 'DATOS DEL TUTOR');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("Q".($contador).":Q".($contador+1)); 
	        $this->excel->getActiveSheet()->setCellValue("Q{$contador}", 'DIRECCION');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("R".($contador).":R".($contador+1)); 
	        $this->excel->getActiveSheet()->setCellValue("R{$contador}", 'CASA PROPIA');
	        $this->excel->setActiveSheetIndex(0)->mergeCells("S".($contador).":X".($contador)); 
	        $this->excel->getActiveSheet()->setCellValue("S{$contador}", 'SERVICIOS BASICOS');
	        $contador++;
	        $this->excel->getActiveSheet()->getStyle("A{$contador}:X{$contador}")->applyFromArray($estilo);
	        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'PATERNO');
	        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'MATERNO');
	        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'NOMBRE');
	        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'NOMBRE');
	        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'CELULAR');
	        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'OCUPACION');
	        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'LUGAR DE TRABJO');
	        $this->excel->getActiveSheet()->setCellValue("I{$contador}", 'NOMBRE');
	        $this->excel->getActiveSheet()->setCellValue("J{$contador}", 'CELULAR');
	        $this->excel->getActiveSheet()->setCellValue("K{$contador}", 'OCUPACION');
	        $this->excel->getActiveSheet()->setCellValue("L{$contador}", 'LUGAR DE TRABJO');
	        $this->excel->getActiveSheet()->setCellValue("M{$contador}", 'NOMBRE');
	        $this->excel->getActiveSheet()->setCellValue("N{$contador}", 'CELULAR');
	        $this->excel->getActiveSheet()->setCellValue("O{$contador}", 'OCUPACION');
	        $this->excel->getActiveSheet()->setCellValue("P{$contador}", 'LUGAR DE TRABJO');
	        $this->excel->getActiveSheet()->setCellValue("S{$contador}", 'CAÑERIA');
	        $this->excel->getActiveSheet()->setCellValue("T{$contador}", 'BAÑO');
	        $this->excel->getActiveSheet()->setCellValue("U{$contador}", 'ALCANTARILLADO');
	        $this->excel->getActiveSheet()->setCellValue("V{$contador}", 'ELECTRICIDAD');
	        $this->excel->getActiveSheet()->setCellValue("W{$contador}", 'RECOJEDOR DE BASURA');
	        $this->excel->getActiveSheet()->setCellValue("X{$contador}", 'INTERNET');
			foreach ($list as $estud) {
			 	
			 	$contador++;
			 	$direccion=$this->estud->get_direccion($estud->id_est,$gestion);
			 	$servicio=$this->estud->get_servicio($estud->id_est,$gestion);
			 	$internets=$this->estud->get_internet($estud->id_est,$gestion);
			 	$madre=$this->estud->get_padres($estud->id_est,'MADRE');
			 	$padre=$this->estud->get_padres($estud->id_est,'PADRE');
			 	$tutor=$this->estud->get_padres($estud->id_est,'TUTOR');
			 	$madres="-";
			 	$celmadre="-";
			 	$tramadre="-";
			 	$ocumadre="-";
			 	$padres="-";
			 	$celpadre="";
			 	$trapadre="";
			 	$ocupadre="";
			 	$tutores="-";
			 	$celtutor="-";
			 	$tratutor="-";
			 	$ocututor="-";
			 	$direcciones="-";
			 	if($madre!=null){
			 		$madres=$madre->appaterno." ".$madre->apmaterno." ".$madre->nombre;
			 		$celmadre=$madre->celular;
			 		$tramadre=$madre->lugartra;
			 		$ocumadre=$madre->ocupacion;
			 	}
			 	if($padre!=null){
			 		$padres=$padre->appaterno." ".$padre->apmaterno." ".$padre->nombre;
			 		$celpadre=$padre->celular;
			 		$trapadre=$padre->lugartra;
			 		$ocupadre=$padre->ocupacion;
			 	}
			 	if($tutor!=null){
			 		$tutores=$tutor->appaterno." ".$tutor->apmaterno." ".$tutor->nombre;
			 		$celtutor=$tutor->celular;
			 		$tratutor=$tutor->lugartra;
			 		$ocututor=$tutor->ocupacion;
			 	}
			 	if($direccion!=null){
			 		$direcciones=$direccion->zona."-".$direccion->calle."-".$direccion->nro;
			 	}
			$this->excel->getActiveSheet()->getStyle("A{$contador}:X{$contador}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->setCellValue("A{$contador}", $estud->codigo);
	        	$this->excel->getActiveSheet()->setCellValue("B{$contador}", $estud->appaterno);
	        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", $estud->apmaterno);
	        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", $estud->nombre);
			$this->excel->getActiveSheet()->setCellValue("E{$contador}", $padres);
			$this->excel->getActiveSheet()->setCellValue("F{$contador}", $celpadre);
			$this->excel->getActiveSheet()->setCellValue("G{$contador}", $ocupadre);
			$this->excel->getActiveSheet()->setCellValue("H{$contador}", $trapadre);
	        	$this->excel->getActiveSheet()->setCellValue("I{$contador}", $madres);
			$this->excel->getActiveSheet()->setCellValue("J{$contador}", $celmadre);
			$this->excel->getActiveSheet()->setCellValue("K{$contador}", $ocumadre);
			$this->excel->getActiveSheet()->setCellValue("L{$contador}", $tramadre);
			$this->excel->getActiveSheet()->setCellValue("M{$contador}", $tutores);
			$this->excel->getActiveSheet()->setCellValue("N{$contador}", $celtutor);
			$this->excel->getActiveSheet()->setCellValue("O{$contador}", $ocututor);
			$this->excel->getActiveSheet()->setCellValue("P{$contador}", $tratutor);
			$this->excel->getActiveSheet()->setCellValue("Q{$contador}", $direcciones);
			$hogar="NO";
			if($servicio->hogar=='Propia'){
				$hogar="SI";
			}
			$this->excel->getActiveSheet()->setCellValue("R{$contador}", $hogar);
			$caneria="NO";
			if($servicio->caneria){
				$caneria="SI";
			}
			$bano="NO";
			if($servicio->banio){
				$bano="SI";
			}
			$alca="NO";
			if($servicio->alcantarillado){
				$alca="SI";
			}
			$elec="NO";
			if($servicio->electricidad){
				$elec="SI";
			}
			$basura="NO";
			if($servicio->basura){
				$basura="SI";
			}
			$inter="SI";
			foreach ($internets as $internet) {
				$internet=$internet->id_inter;
			}
			if($internet==5){
				$inter="NO";
			}
			$this->excel->getActiveSheet()->setCellValue("S{$contador}", $caneria);
			$this->excel->getActiveSheet()->setCellValue("T{$contador}", $bano);
			$this->excel->getActiveSheet()->setCellValue("U{$contador}", $alca);
			$this->excel->getActiveSheet()->setCellValue("V{$contador}", $elec);
			$this->excel->getActiveSheet()->setCellValue("W{$contador}", $basura);
			$this->excel->getActiveSheet()->setCellValue("X{$contador}", $inter);
	        	$x++;
			}
		}
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$niveles->nivel}_{$niveles->nombre}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}
	public function export_exel($id)
	{
		$id=str_replace("%20", " ", $id);
		$ids=explode('W', $id, -1);
		//print_r($ids);
		//exit();
		$gestion=$ids[0];
		$cursos=$ids[1];
		$nivel=str_replace("MA%C3%91ANA", "MAÑANA", $ids[3]);
		$colegio=str_replace("TECNICO HUMANISTICO DON BOSCO", "TEC. HUM. DON BOSCO", $ids[2]);
		$list=$this->estud->get_print_estud_pdf($gestion,$cursos);
		$curso=$this->estud->get_print_curso_pdf($cursos);

		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('direccion');
        
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
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(28);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);

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

        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador).":H".($contador));
        $this->excel->getActiveSheet()->getStyle("A{$contador}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'LISTA DE ESTUDIANTES');
       
        	 $contador++;
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'U.E.');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", $colegio);
        //$contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'NIVEL');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $nivel);
        $contador++;
        $this->excel->getActiveSheet()->getStyle("C{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'GESTION');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", $gestion);
        
        $this->excel->getActiveSheet()->getStyle("F{$contador}")->applyFromArray($estilo); 
      	 $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'AÑO DE ESCOR.');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", $curso->curso);
        $contador++;$contador++;

        //Le aplicamos negrita a los títulos de la cabecera.
        $this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($estilo);

       

        //Definimos los títulos de la cabecera.
        $this->excel->getActiveSheet()->setCellValue("A{$contador}", 'NUM');
        $this->excel->getActiveSheet()->setCellValue("B{$contador}", 'RUDE');
        $this->excel->getActiveSheet()->setCellValue("C{$contador}", 'CI');
        $this->excel->getActiveSheet()->setCellValue("D{$contador}", 'COD');
        $this->excel->getActiveSheet()->setCellValue("E{$contador}", 'PATERNO');
        $this->excel->getActiveSheet()->setCellValue("F{$contador}", 'MATERNO');
        $this->excel->getActiveSheet()->setCellValue("G{$contador}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("H{$contador}", 'GEN');
    
        $x = 9;
		 foreach ($list as $estud) {
		 	$contador++;
		 
		 	$this->excel->getActiveSheet()->getStyle("A{$contador}:H{$contador}")->applyFromArray($estilobor);
		    $this->excel->getActiveSheet()->setCellValue("A{$contador}", $x);
		    $this->excel->getActiveSheet()->setCellValue("B{$contador}", ' '.$estud->rude);
        	$this->excel->getActiveSheet()->setCellValue("C{$contador}", ' '.$estud->ci);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador}", ' '.$estud->codigo);
        	$this->excel->getActiveSheet()->setCellValue("E{$contador}", $estud->appaterno);
        	$this->excel->getActiveSheet()->setCellValue("F{$contador}", $estud->apmaterno);
        	$this->excel->getActiveSheet()->setCellValue("G{$contador}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("H{$contador}", $estud->genero);
        	$x++;
		 }
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "{$curso->corto}_{$nivel}_{$colegio}_{$gestion}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
	}
	public function printxls($id)
	{
		/*
		require_once APPPATH."/third_party/phpexcel/phpexcel.php";
		require_once APPPATH."/third_party/phpexcel/PHPExcel/Writer/Excel2007.php";
		$list=$this->estud->get_print_estud_pdf($id);
		$curso=$this->estud->get_print_curso_pdf($id);

		//print_r($curso->corto);

		
		$this->objPHPExcel=new PHPExcel();
		$this->objPHPExcel->getProperties()->setCreator("");
		$this->objPHPExcel->getProperties()->setLastModifieldBy("");
		$this->objPHPExcel->getProperties()->setTitle("");
		$this->objPHPExcel->getProperties()->setSubject("");
		$this->objPHPExcel->getProperties()->setDescription("");
		
		
		$this->objPHPExcel->setActiveSheetIndex(0);

		$this->objPHPExcel->getActiveSheet()->SetCellValue('A1','IDEST');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('B1','RUDE');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('C1','CI');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('D1','CODIGO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('E1','PATERNO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('F1','MATERNO');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('G1','NOMBRES');
		$this->objPHPExcel->getActiveSheet()->SetCellValue('H1','GENERO');

		$row=2;

		foreach ($list as $estud) {
			
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->rude);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->ci);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->codigo);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->appaterno);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->apmaterno);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->nombres);
			$this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$row,$estud->genero);

			$row++;
		}

		$filename="Lista Alumnos -".$curso->corto."- ".$curso->nivel." -".$curso->gestion.".xlsx";
		$this->objPHPExcel->getActiveSheet()->setTitle("Lista Alumnos ".$curso->corto);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$writer=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$writer->save('php://output');
		exit;
		*/


	}
	

}
