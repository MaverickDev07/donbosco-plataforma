<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rep_estadisticas_contr extends CI_Controller {

	

	public function __construct()
	{
		parent::__construct();		
		//$this->load->helper(array('url', 'form'));
		$this->load->helper('url');
		$this->load->model('Rep_estadisticas_model','estud');
		//$this->load->model('Config_curso_model','curso');
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
		$this->load->view('Rep_estadisticas_view');
		$this->load->view('layouts/fin');
	}
	
	public function ajax_cerrar()
	{
		$this->session->sess_destroy();
		$bu='http://'.$_SERVER['HTTP_HOST'].'/donbosco/';			
		header("Location: ".$bu);
		//echo json_encode(array("status" => TRUE));

	}
			
	public function ajax_get_nivel()
	{
		$list=$this->estud->get_nivel(); //envia
		$data=array();
		$data1=array();
		foreach ($list as $nivel) {			
			$data[] =$nivel->nivel." ".$nivel->turno;		
			$data1[] =$nivel->codigo;					 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
						"data1" => $data1,
				);
		echo json_encode($output);
	}

	public function ajax_get_level()
	{
		$lvl=$this->input->post('lvl');

		
		$list=$this->estud->colegio($lvl); //envia

		$data=array();
		foreach ($list as $level) {			
			$data[] =$level->nombre;			 				 
		}
		$output = array(
						"status" => TRUE,
						"data" => $data,
				);
		echo json_encode($output);
	}

public function Mxcurso($id) //impresion centralizador
	{
		
		$ids=explode('_', $id, -1);
		// print_r($ids);
		// exit();
		$bi=$ids[0]; 
		$codigo=$ids[1];
		$gestion=$ids[2];
		$nivel=$ids[3];
		$cur=$ids[4];
		$cantidad=$ids[5];
		if ($nivel=='SM' OR  $nivel=='ST') {
			$list=$this->estud->cursos();
		}
		if ($nivel=='PM' OR  $nivel=='PT') {
			$list=$this->estud->cursosp();
		}
		 
		$ni=$this->estud->get_niveles($nivel);
		 foreach ($ni as $nis) 
         {
         	$niveles=$nis->nivel;
         	$turno=$nis->turno;
         	$id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
         	$colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
         	$bimestre=$bims->nombre;
         }

         $cur1=$this->estud->get_cursos($cur);
         foreach ($cur1 as $curs) 
         {
         	$cursos=$curs->nombre;
         }

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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
		//exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
      	//$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
      	
      	$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
		$this->excel->getActiveSheet()->getProtection()->setSheet(true); 
      	$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'MEJORES ESTUDIANTES DEL ');
        $contador1++;
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $bimestre);

      	$contador1++;$contador1++;
      	$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GESTION:');
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$gestion);
      	$contador1++;
      	$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NIVEL:');
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niveles.' '.$turno);

      	$this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'UNID. EDU: ');
      	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $colegio);
      	$i=0;
      	foreach ($list as $estud) {
      	
       	$contador1++;
        $contador1++;
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":D".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $estud->nombre);
         $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NOTA');

		$contador1++;
		$x=1;
		$codigos=$estud->codigo."-".$nivel;
		$list1=$this->estud->mxcurso($codigos,$cantidad,$bi);
		 foreach ($list1 as $estud1) {
         	$this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud1->nombres);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud1->nota.",3)");
        	$contador1++;
        	$x++;
        }
       //  $this->excel->createSheet();
      	// $this->excel->setActiveSheetIndex($i);
       //  $this->excel->getActiveSheet()->setTitle('direccion');
       //  $i++;
        }
		
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "MEJORES ESTUDIANTES_{$nivel}__{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }
     public function Cxcurso($id) //impresion centralizador
  {
    
    $ids=explode('_', $id, -1);
    // print_r($ids);
    // exit();
    $bi=$ids[0]; 
    $codigo=$ids[1];
    $gestion=$ids[2];
    $nivel=$ids[3];
    $cur=$ids[4];
    $cantidad=$ids[5];
    if ($nivel=='SM' OR  $nivel=='ST') {
      $list=$this->estud->cursos();
    }
    if ($nivel=='PM' OR  $nivel=='PT') {
      $list=$this->estud->cursosp();
    }
     
    $ni=$this->estud->get_niveles($nivel);
     foreach ($ni as $nis) 
         {
          $niveles=$nis->nivel;
          $turno=$nis->turno;
          $id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
          $colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
          $bimestre=$bims->nombre;
         }

         $cur1=$this->estud->get_cursos($cur);
         foreach ($cur1 as $curs) 
         {
          $cursos=$curs->nombre;
         }

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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
  );
        $titulo = array(
      'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
      'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
    //exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        //$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
        
        $this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
        $this->excel->getActiveSheet()->getProtection()->setSheet(true); 
        $this->drawing->setName('Logotipo1');
        $this->drawing->setDescription('Logotipo1');
        $img = imagecreatefrompng('assets/images/logo.png');
        $this->drawing->setImageResource($img);
        $this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
        $this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $this->drawing->setHeight(80);
        $this->drawing->setCoordinates('A1');
        $this->drawing->setWorksheet($this->excel->getActiveSheet());
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'CUADRO DE HONOR ACUMULADO AL');
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}",$bimestre);

        $contador1++;$contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GESTION:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$gestion);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NIVEL:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niveles.' '.$turno);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'UNID. EDU: ');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $colegio);
        $i=0;
        $contador1++;
        $contador1++;
        foreach ($list as $estud) {
        $contador1++;
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":D".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $estud->nombre);
         $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NOTA');

    $contador1++;
    $x=1;
    $codigos=$estud->codigo."-".$nivel;
    // $list1=$this->estud->cxcurso($codigos,$cantidad,$bi);
    
    if ($nivel=='SM' OR  $nivel=='ST') {
     $list1=$this->estud->cxcurso1($codigos,$cantidad,$bi, $gestion);
     
    }
    if ($nivel=='PM' OR  $nivel=='PT') {
      $list1=$this->estud->cxcurso($codigos,$cantidad,$bi);
    }
     foreach ($list1 as $estud1) {
          $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilobor);
      $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
      $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
          $this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud1->nombres);
          $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud1->nota.",3)");
          $contador1++;
          $x++;
        }
       //  $this->excel->createSheet();
        // $this->excel->setActiveSheetIndex($i);
       //  $this->excel->getActiveSheet()->setTitle('direccion');
       //  $i++;
        }
    
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "CUADRO DE HONOR _{$nivel}_{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }
     public function Axcurso($id) //impresion centralizador
	{
		
		$ids=explode('_', $id, -1);
		// print_r($ids);
		// exit();
		$bi=$ids[0]; 
		$codigo=$ids[1];
		$gestion=$ids[2];
		$nivel=$ids[3];
		$cur=$ids[4];
		$cantidad=$ids[5];
		if ($nivel=='SM' OR  $nivel=='ST') {
			$list=$this->estud->cursos();
		}
		if ($nivel=='PM' OR  $nivel=='PT') {
			$list=$this->estud->cursosp();
		}
		 
		$ni=$this->estud->get_niveles($nivel);
		 foreach ($ni as $nis) 
         {
         	$niveles=$nis->nivel;
         	$turno=$nis->turno;
         	$id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
         	$colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
         	$bimestre=$bims->nombre;
         }

         $cur1=$this->estud->get_cursos($cur);
         foreach ($cur1 as $curs) 
         {
         	$cursos=$curs->nombre;
         }

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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
		//exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
      	//$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
      	
      	$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
		$this->excel->getActiveSheet()->getProtection()->setSheet(true); 
      	$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
		$this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
      	$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":G".($contador1));
      	$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'ESTADISTICA DE APROBECHAMIENTO');
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":G".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $bimestre);

      	$contador1++;$contador1++;
      	$this->excel->getActiveSheet()->getStyle("C{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'GESTION:');
      	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", ' '.$gestion);
      	$contador1++;
      	$this->excel->getActiveSheet()->getStyle("C{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'NIVEL:');
      	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", $niveles.' '.$turno);
        $contador1++;
      	$this->excel->getActiveSheet()->getStyle("C{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'UNID. EDU: ');
      	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", $colegio);
        $contador1++;
        $contador1++;
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", "NUM");
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", "CURSO");
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", "COMU. Y SOC.");
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "CIENCIAS TEC. Y PROD.");
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", "VIDA TIERRA Y TERR.O");
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", "COSMOS Y PENS.");
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", "TOTAL");


      	$i=0;
        $comu=0;
        $pron=0;
        $tierra=0;
        $cos=0;
      	foreach ($list as $estud) {

    		$contador1++;
    		$x=1;
        $final=0;
    		$codigos=$estud->codigo."-".$nivel;
    		$list1=$this->estud->axcurso($codigos,$bi);
        $i++;
    		 foreach ($list1 as $estud1) {
            $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($estilobor);
            $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $i);
            $this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud->nombre);
            if($x==1){
              $this->excel->getActiveSheet()->setCellValue("C{$contador1}", "=ROUND(".$estud1->notas.",2)" );
              $comu=$comu+$estud1->notas;
            }
            if($x==2){
              $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud1->notas.",2)" );
              $pron=$pron+$estud1->notas;
            }
            if($x==3){
              $this->excel->getActiveSheet()->setCellValue("E{$contador1}", "=ROUND(".$estud1->notas.",2)" );
              $tierra=$tierra+$estud1->notas;
            }
            if($x==4){
              $this->excel->getActiveSheet()->setCellValue("F{$contador1}", "=ROUND(".$estud1->notas.",2)" );
              $cos=$cos+$estud1->notas;
            }
            $final=$final+$estud1->notas;
            $this->excel->getActiveSheet()->setCellValue("G{$contador1}","=ROUND(".($final/4).",2)"  );
            	$x++;
            }
        }
        $contador1++;$contador1++;$contador1++;$contador1++;
        $this->excel->getActiveSheet()->getStyle("C{$contador1}:G{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", "COMU. Y SOC.");
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "CIENCIAS TEC. Y PROD.");
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", "VIDA TIERRA Y TERR.O");
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", "COSMOS Y PENS.");
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", "TOTAL");
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("C{$contador1}:G{$contador1}")->applyFromArray($estilobor);
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", "=ROUND(".($comu/$i).",2)");
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".($pron/$i).",2)");
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", "=ROUND(".($tierra/$i).",2)");
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}","=ROUND(". ($cos/$i).",2)");
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", "=ROUND(".((($comu/$i)+($pron/$i)+($tierra/$i)+($cos/$i))/4).",2)");
    
		
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "ESTADISTICA_{$nivel}__{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }
     public function familia($id) //impresion centralizador
  {
    
    $ids=explode('_', $id, -1);
    $bi=$ids[0]; 
    $codigo=$ids[1];
    $gestion=$ids[2];
    $nivel=$ids[3];
    $cantidad=$ids[4];
    if ($nivel=='SM' OR  $nivel=='PM') {
      $niv="M";
    }
    if ($nivel=='ST' OR  $nivel=='PT') {
      $niv="T";
    }
    $familia=$this->estud->familia($niv,$gestion,$cantidad);
    // print_r($familia);
    // exit();
    $ni=$this->estud->get_niveles($nivel);
    
    foreach ($ni as $nis)
    {
      $niveles=$nis->nivel;
      $turno=$nis->turno;
      $id_col=$nis->id_col;
    }

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
            'color' => array('rgb' => '2080e1')
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
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      )
    );
    
    $titulo = array(
      'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
      ),
      'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'fbfcf9')
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
    
    $contador1=3;
    //Le aplicamos ancho las columnas.
    //print_r($ids);
    //exit();
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(23);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(33);
    //$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
        
    $this->drawing->setName('Logotipo1');
    $this->drawing->setDescription('Logotipo1');
    $img = imagecreatefrompng('assets/images/logo.png');
    $this->drawing->setImageResource($img);
    $this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
    $this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $this->drawing->setHeight(80);
    $this->drawing->setCoordinates('A1');
    $this->drawing->setWorksheet($this->excel->getActiveSheet());
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
    $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":G".($contador1));
    $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'ESTADISTICAS DE FAMILIARES');

    $contador1++;$contador1++;
    $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($negra);
    $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'GESTION:');
    $this->excel->getActiveSheet()->setCellValue("E{$contador1}", ' '.$gestion);
    $contador1++;
    $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($negra);
    $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'TURNO:');
    $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $turno);
    $contador1++;
    $contador1++;
    $contador1++;
    $contador1++;
    $contador1++;
    $total=0;
    foreach ($familia as $estud) {
      $total++;
      $padres=$this->estud->padres($estud->id_padre);
      // print_r($padres);
      // exit();
      
      $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($estilo);
      $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":G".($contador1));
      $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'FAMILA '.$padres->appaterno." ".$padres->apmaterno." ".$padres->nombre);
      $contador1++;
      $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($estilo);
      $this->excel->getActiveSheet()->setCellValue("A{$contador1}", "NUM");
      $this->excel->getActiveSheet()->setCellValue("B{$contador1}", "AP. PATERNO");
      $this->excel->getActiveSheet()->setCellValue("C{$contador1}", "AP. MATERNO");
      $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "NOMBRE");
      $this->excel->getActiveSheet()->setCellValue("E{$contador1}", "CURSO");
      $this->excel->getActiveSheet()->setCellValue("F{$contador1}", "NIVEL");
      $this->excel->getActiveSheet()->setCellValue("G{$contador1}", "COLEGIO");
      $contador1++;
      $list1=$this->estud->afamilia($estud->id_padre,$gestion);
      // print_r($list1);
      // exit();
      $i=0;
      foreach ($list1 as $estud1) {
        $i++;
        $ids1=explode('-', $estud1->codigo."-", -1);
        $curso=$ids1[0]; 
        $n=$ids1[1];
        $c=$ids1[2];

        $ni=$this->estud->colegio($n);
        foreach ($ni as $nis) 
        {
          $niveles=$nis->nivel;
          $turno=$nis->turno;
          $colegio=$nis->nombre;
        }
        
        $cur1=$this->estud->get_cursos($curso);
        foreach ($cur1 as $curs)
        {
          $cursos=$curs->nombre;
        }
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($estilobor);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $i);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud1->appaterno);
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $estud1->apmaterno);
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", $estud1->nombre);
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $cursos);
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", $niveles." ".$turno);
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", $colegio);
        $contador1++;
      }
      $contador1++;
    }
    //exit();
    $this->excel->getActiveSheet()->getStyle("D7")->applyFromArray($negra);
    $this->excel->getActiveSheet()->setCellValue("D7", "CANTIDAD DE FAMILIA");
    $this->excel->getActiveSheet()->setCellValue("E7", $total);
    //Le ponemos un nombre al archivo que se va a generar.
    $archivo = "ESTADISTICA DE FAMILIARES_{$nivel}__{$gestion}.xls";
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$archivo.'"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    //Hacemos una salida al navegador con el archivo Excel.
    $objWriter->save('php://output');
  }
  
  public function Bxcurso($id) //impresion centralizador
  {
    
    $ids=explode('_', $id, -1);
    // print_r($ids);
    // exit();
    $bi=$ids[0]; 
    $codigo=$ids[1];
    $gestion=$ids[2];
    $nivel=$ids[3];
    $cur=$ids[4];
    $cantidad=$ids[5];
    if ($nivel=='SM' OR  $nivel=='ST') {
      $list=$this->estud->cursos();
    }
    if ($nivel=='PM' OR  $nivel=='PT') {
      $list=$this->estud->cursosp();
    }
     
    $ni=$this->estud->get_niveles($nivel);
     foreach ($ni as $nis) 
         {
          $niveles=$nis->nivel;
          $turno=$nis->turno;
          $id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
          $colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
          $bimestre=$bims->nombre;
         }

         $cur1=$this->estud->get_cursos($cur);
         foreach ($cur1 as $curs) 
         {
          $cursos=$curs->nombre;
         }

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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
  );
        $titulo = array(
      'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
      'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
    //exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        //$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
        
        $this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
    $this->excel->getActiveSheet()->getProtection()->setSheet(true); 
        $this->drawing->setName('Logotipo1');
    $this->drawing->setDescription('Logotipo1');
    $img = imagecreatefrompng('assets/images/logo.png');
    $this->drawing->setImageResource($img);
    $this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
    $this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $this->drawing->setHeight(80);
    $this->drawing->setCoordinates('A1');
    $this->drawing->setWorksheet($this->excel->getActiveSheet());
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'ESTUDIANTES DE BAJO RENDIMIENTO DEL');
        $contador1++;
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $bimestre);


        $contador1++;$contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GESTION:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$gestion);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NIVEL:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niveles.' '.$turno);

        $this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'UNID. EDU: ');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $colegio);
        $i=0;
        foreach ($list as $estud) {
        
        $contador1++;
        $contador1++;
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":D".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $estud->nombre);
         $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NOTA');

    $contador1++;
    $x=1;
    $codigos=$estud->codigo."-".$nivel;
    $list1=$this->estud->bxcurso($codigos,$cantidad,$bi);
     foreach ($list1 as $estud1) {
          $this->excel->getActiveSheet()->getStyle("A{$contador1}:D{$contador1}")->applyFromArray($estilobor);
      $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
      $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
          $this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud1->nombres);
          $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud1->nota.",3)");
          $contador1++;
          $x++;
        }
       //  $this->excel->createSheet();
        // $this->excel->setActiveSheetIndex($i);
       //  $this->excel->getActiveSheet()->setTitle('direccion');
       //  $i++;
        }
    
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "BAJO RENDIMIENTO_{$nivel}__{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }

     public function estadistica_a($id) //impresion centralizador
  {
    $ids=explode('_', $id, -1);
    // print_r($ids);
    // exit();
    $bi=$ids[0]; 
    $codigo=$ids[1];
    $gestion=$ids[2];
    $nivel=$ids[3];
    $cur=$ids[4];
    $cantidad=$ids[5];
    if ($nivel=='SM' OR  $nivel=='ST') {
      $list=$this->estud->cursos();
    }
    if ($nivel=='PM' OR  $nivel=='PT') {
      $list=$this->estud->cursosp();
    }
     
    $ni=$this->estud->get_niveles($nivel);
     foreach ($ni as $nis) 
         {
          $niveles=$nis->nivel;
          $turno=$nis->turno;
          $id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
          $colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
          $bimestre=$bims->nombre;
         }

         $cur1=$this->estud->get_cursos($cur);
         foreach ($cur1 as $curs) 
         {
          $cursos=$curs->nombre;
         }

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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
  );
        $titulo = array(
      'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
      'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
    //exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
        
        $this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
    $this->excel->getActiveSheet()->getProtection()->setSheet(true); 
        $this->drawing->setName('Logotipo1');
    $this->drawing->setDescription('Logotipo1');
    $img = imagecreatefrompng('assets/images/logo.png');
    $this->drawing->setImageResource($img);
    $this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
    $this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $this->drawing->setHeight(80);
    $this->drawing->setCoordinates('A1');
    $this->drawing->setWorksheet($this->excel->getActiveSheet());
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":S".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'ESTADISTICAS DE '.$bimestre);

        $contador1++;$contador1++;
        $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'GESTION:');
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", ' '.$gestion);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'NIVEL:');
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", $niveles.' '.$turno);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("E{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'UNID. EDU: ');
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", $colegio);
        $i=0;
        $contador1++;$contador1++;$contador1++;$contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:S{$contador1}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("C".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'INSCRITOS');
        $this->excel->setActiveSheetIndex(0)->mergeCells("F".($contador1).":H".($contador1));
        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", 'RETIRADO');
        $this->excel->setActiveSheetIndex(0)->mergeCells("I".($contador1).":K".($contador1));
        $this->excel->getActiveSheet()->setCellValue("I{$contador1}", 'EFECTIVO');
        $this->excel->setActiveSheetIndex(0)->mergeCells("L".($contador1).":O".($contador1));
        $this->excel->getActiveSheet()->setCellValue("L{$contador1}", 'APROBADO');
        $this->excel->setActiveSheetIndex(0)->mergeCells("P".($contador1).":S".($contador1));
        $this->excel->getActiveSheet()->setCellValue("P{$contador1}", 'REPROBADO');
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:S{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NRO');
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GRADO');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", 'M');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'F');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'T');

        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", 'M');
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", 'F');
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", 'T');

        $this->excel->getActiveSheet()->setCellValue("I{$contador1}", 'M');
        $this->excel->getActiveSheet()->setCellValue("J{$contador1}", 'F');
        $this->excel->getActiveSheet()->setCellValue("K{$contador1}", 'T');

        $this->excel->getActiveSheet()->setCellValue("L{$contador1}", 'M');
        $this->excel->getActiveSheet()->setCellValue("M{$contador1}", 'F');
        $this->excel->getActiveSheet()->setCellValue("N{$contador1}", 'T');
        $this->excel->getActiveSheet()->setCellValue("O{$contador1}", '% APROBA');

        $this->excel->getActiveSheet()->setCellValue("P{$contador1}", 'M');
        $this->excel->getActiveSheet()->setCellValue("Q{$contador1}", 'F');
        $this->excel->getActiveSheet()->setCellValue("R{$contador1}", 'T');
        $this->excel->getActiveSheet()->setCellValue("S{$contador1}", '% REPRO.');
        $x=0;
        $inscritoM=0;
        $inscritoF=0;
        $inscritoT=0;
        $reM=0;
        $reF=0;
        $reT=0;
        $efecM=0;
        $efecF=0;
        $efecT=0;
        $promoF=0;
        $promoM=0;
        $promoT=0;
        $promoP=0;
        $reproF=0;
        $reproM=0;
        $reproT=0;
        $reproP=0;

        $cadgeneroM=0;
        $cadgeneroF=0;
        $cadaprobadoM=0;
        $cadaprobadoF=0;
        $cadreprobadoM=0;
        $cadreprobadoF=0;
        $retiradoM=0;
        $retiradoF=0;
        for($i=1;$i<=6;$i++) {
          $contador1++;
          $retiradoM=0;
          $retiradoF=0;
          $x++;
          $this->excel->getActiveSheet()->getStyle("A{$contador1}:S{$contador1}")->applyFromArray($estilobor);
          $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
          $this->excel->getActiveSheet()->setCellValue("B{$contador1}", $i."°");
          $list1=$this->estud->cantidad_genero($nivel,$i,'M');
          foreach ($list1 as $estud1) {$cadgeneroM=$estud1->cantidad; }
          $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $cadgeneroM);
          $inscritoM= $inscritoM+$cadgeneroM;

          $list1=$this->estud->cantidad_genero($nivel,$i,'F');
          foreach ($list1 as $estud1) {$cadgeneroF=$estud1->cantidad; }
          $this->excel->getActiveSheet()->setCellValue("D{$contador1}", $cadgeneroF);
          $inscritoF= $inscritoF+$cadgeneroF;
          $totalgenero=$cadgeneroM+$cadgeneroF;
          $inscritoT= $inscritoT+$totalgenero;
          $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $totalgenero);

          $list1=$this->estud->retirado($nivel,$i,'M',$bi);
          foreach ($list1 as $estud1) {$retiradoM=$estud1->cantidad; }
          $this->excel->getActiveSheet()->setCellValue("F{$contador1}", $retiradoM);
          $reM=$reM+$retiradoM;

          $list1=$this->estud->retirado($nivel,$i,'F',$bi);
          foreach ($list1 as $estud1) {$retiradoF=$estud1->cantidad; }
          $this->excel->getActiveSheet()->setCellValue("G{$contador1}", $retiradoF);
          $reF=$reF+$retiradoF;
          $totalretirado=$retiradoM+$retiradoM;
          $reT=$reT+$totalretirado;
          $this->excel->getActiveSheet()->setCellValue("H{$contador1}", $totalretirado);

          $efectivoM=$cadgeneroM-$retiradoM;
          $efecM=$efecM+$efectivoM;
          $this->excel->getActiveSheet()->setCellValue("I{$contador1}", $efectivoM);

          $efectivoF=$cadgeneroF-$retiradoF;
          $efecF=$efecF+$efectivoF;
          $this->excel->getActiveSheet()->setCellValue("J{$contador1}", $efectivoF);

          $efectivoT=$efectivoM+$efectivoF;
          $efecT=$efecT+$efectivoT;
          $this->excel->getActiveSheet()->setCellValue("K{$contador1}", $efectivoT);

          $list1=$this->estud->reprobado_estudiantes($nivel,$i,'M',$bi);
          $a=0;
          foreach ($list1 as $estud1) {$a++;}
          $cadreprobadoM=$a;
          $reproM=$reproM+$cadreprobadoM;
          $this->excel->getActiveSheet()->setCellValue("P{$contador1}", $cadreprobadoM);

          $list1=$this->estud->reprobado_estudiantes($nivel,$i,'F',$bi);
          $a=0;
          foreach ($list1 as $estud1) {$a++;}
          $cadreprobadoF=$a;
          $reproF=$reproF+$cadreprobadoF;
          $reproT=$reproT+$cadreprobadoF+$cadreprobadoM;
          $this->excel->getActiveSheet()->setCellValue("Q{$contador1}", $cadreprobadoF);
          $this->excel->getActiveSheet()->setCellValue("R{$contador1}", $cadreprobadoF+$cadreprobadoM);
          $reproP=$reproP+((100*($cadreprobadoF+$cadreprobadoM))/$efectivoT);
          $this->excel->getActiveSheet()->setCellValue("S{$contador1}", "=ROUND(".((100*($cadreprobadoF+$cadreprobadoM))/$efectivoT).",1)");

          $cadaprobadoM=$cadgeneroM-$cadreprobadoM-$retiradoM;
          $promoM=$promoM+$cadaprobadoM;
          $this->excel->getActiveSheet()->setCellValue("L{$contador1}", $cadaprobadoM);

          $cadaprobadoF=$cadgeneroF-$cadreprobadoF-$retiradoF;
          $promoF=$promoF+$cadaprobadoF;
          $promoT=$promoT+$cadaprobadoF+$cadaprobadoM;
          $this->excel->getActiveSheet()->setCellValue("M{$contador1}", $cadaprobadoF);
          $this->excel->getActiveSheet()->setCellValue("N{$contador1}", $cadaprobadoF+$cadaprobadoM);
          $promoP=$promoP+((100*($cadaprobadoF+$cadaprobadoM))/$efectivoT);
          $this->excel->getActiveSheet()->setCellValue("O{$contador1}", "=ROUND(".((100*($cadaprobadoF+$cadaprobadoM))/$efectivoT).",1)");
        }
        $contador1++;
        $promoP=$promoP/6;
        $reproP=$reproP/6;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:S{$contador1}")->applyFromArray($estilo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":B".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'TOTAL');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $inscritoM);
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", $inscritoF);
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $inscritoT);

        $this->excel->getActiveSheet()->setCellValue("F{$contador1}", $reM);
        $this->excel->getActiveSheet()->setCellValue("G{$contador1}", $reF);
        $this->excel->getActiveSheet()->setCellValue("H{$contador1}", $reT);

        $this->excel->getActiveSheet()->setCellValue("I{$contador1}", $efecM);
        $this->excel->getActiveSheet()->setCellValue("J{$contador1}", $efecF);
        $this->excel->getActiveSheet()->setCellValue("K{$contador1}", $efecT);

        $this->excel->getActiveSheet()->setCellValue("L{$contador1}", $promoM);
        $this->excel->getActiveSheet()->setCellValue("M{$contador1}", $promoF);
        $this->excel->getActiveSheet()->setCellValue("N{$contador1}", $promoT);
        $this->excel->getActiveSheet()->setCellValue("O{$contador1}", "=ROUND(".($promoP).",1)");

        $this->excel->getActiveSheet()->setCellValue("P{$contador1}", $reproM);
        $this->excel->getActiveSheet()->setCellValue("Q{$contador1}", $reproF);
        $this->excel->getActiveSheet()->setCellValue("R{$contador1}", $reproT);
        $this->excel->getActiveSheet()->setCellValue("S{$contador1}", "=ROUND(".($reproP).",1)");
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "ESTADISTICAS_{$nivel}__{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }

     public function Mxcolegio($id) //impresion centralizador
	{
		
		$ids=explode('_', $id, -1);
		// print_r($ids);
		// exit();
		$bi=$ids[0]; 
		$codigo=$ids[1];
		$gestion=$ids[2];
		$nivel=$ids[3];
		$cur=$ids[4];
		$cantidad=$ids[5];

		$list=$this->estud->mxcolegio($nivel,$cantidad,$bi);

		$ni=$this->estud->get_niveles($nivel);
		 foreach ($ni as $nis) 
         {
         	$niveles=$nis->nivel;
         	$turno=$nis->turno;
         	$id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
         	$colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
         	$bimestre=$bims->nombre;
         }

         
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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
		//exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
      	//$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
      	
      	$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
		$this->excel->getActiveSheet()->getProtection()->setSheet(true); 
      	$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'MEJORES ESTUDIANTES DEL ');
        $contador1++;
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $bimestre);

      	$contador1++;$contador1++;
      	$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GESTION:');
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$gestion);
      	$contador1++;
      	$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NIVEL:');
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niveles.' '.$turno);

      	$this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'UNID. EDU: ');
      	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $colegio);

       	$contador1++;
        $contador1++;
        $contador1++;
     
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NOTA');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'CURSO');

		$contador1++;
		$x=1;
		foreach ($list as $estud) {

         	$this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud->nota.",3)");
        	$cod=explode('-',$estud->codigo, -1);
        	$cur1=$this->estud->get_cursos($cod[0]);
         foreach ($cur1 as $curs) 
         {
         	$cursos=$curs->nombre;
         }

        	
        	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $cursos);
        	$contador1++;
        	$x++;
        }
		
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "MEJORES ESTUDIANTES_{$nivel}__{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }
     public function Cxcolegio($id) //impresion centralizador
  {
    
    $ids=explode('_', $id, -1);
    // print_r($ids);
    // exit();
    $bi=$ids[0]; 
    $codigo=$ids[1];
    $gestion=$ids[2];
    $nivel=$ids[3];
    $cur=$ids[4];
    $cantidad=$ids[5];
    if ($nivel=='SM' OR  $nivel=='ST') {
     $list=$this->estud->cxcolegio1($nivel,$cantidad,$bi);
    }
    if ($nivel=='PM' OR  $nivel=='PT') {
       $list=$this->estud->cxcolegio($nivel,$cantidad,$bi);
    }
   

    $ni=$this->estud->get_niveles($nivel);
     foreach ($ni as $nis) 
         {
          $niveles=$nis->nivel;
          $turno=$nis->turno;
          $id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
          $colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
          $bimestre=$bims->nombre;
         }

         
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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
  );
        $titulo = array(
      'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
      'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
    //exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        //$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
        
        $this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
    $this->excel->getActiveSheet()->getProtection()->setSheet(true); 
        $this->drawing->setName('Logotipo1');
    $this->drawing->setDescription('Logotipo1');
    $img = imagecreatefrompng('assets/images/logo.png');
    $this->drawing->setImageResource($img);
    $this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
    $this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $this->drawing->setHeight(80);
    $this->drawing->setCoordinates('A1');
    $this->drawing->setWorksheet($this->excel->getActiveSheet());
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'CUADRO DE HONOR ACUMULADO AL');
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}",$bimestre);

        $contador1++;$contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GESTION:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$gestion);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NIVEL:');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niveles.' '.$turno);
        $contador1++;
        $this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'UNID. EDU: ');
        $this->excel->getActiveSheet()->setCellValue("C{$contador1}", $colegio);

        $contador1++;
        $contador1++;
        $contador1++;
     
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NOTA');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'CURSO');

    $contador1++;
    $x=1;
    foreach ($list as $estud) {

          $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($estilobor);
      $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
      $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
          $this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud->nombres);
          $this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud->nota.",3)");
          $cod=explode('-',$estud->codigo, -1);
          $cur1=$this->estud->get_cursos($cod[0]);
         foreach ($cur1 as $curs) 
         {
          $cursos=$curs->nombre;
         }
          $this->excel->getActiveSheet()->setCellValue("E{$contador1}", $cursos);
          $contador1++;
          $x++;
        }
    
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "CUADRO DE HONOR_{$nivel}_{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }
      public function Bxcolegio($id) //impresion centralizador
	{
		
		$ids=explode('_', $id, -1);
		// print_r($ids);
		// exit();
		$bi=$ids[0]; 
		$codigo=$ids[1];
		$gestion=$ids[2];
		$nivel=$ids[3];
		$cur=$ids[4];
		$cantidad=$ids[5];

		$list=$this->estud->bxcolegio($nivel,$cantidad,$bi);

		$ni=$this->estud->get_niveles($nivel);
		 foreach ($ni as $nis) 
         {
         	$niveles=$nis->nivel;
         	$turno=$nis->turno;
         	$id_col=$nis->id_col;
         }

         $col=$this->estud->get_colegio($id_col);
         foreach ($col as $cols) 
         {
         	$colegio=$cols->nombre;
         }
         $bim=$this->estud->get_bimestre($bi);
         foreach ($bim as $bims) 
         {
         	$bimestre=$bims->nombre;
         }

         
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
            'color' => array('rgb' => '2080e1')
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
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
	);
        $titulo = array(
    	'font'  => array(
        'bold'  => true,
        'size'  => 15,
        'color' => array('rgb' => '000000'),
        //'startcolor' => array('rgb' => '00B050'),
        'name'  => 'Verdana'
    ),
    	'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'fbfcf9')
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
        $contador1=3;
        //Le aplicamos ancho las columnas.
        //print_r($ids);
		//exit();
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
      	//$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(5);
      	
      	$this->excel->getActiveSheet()->getProtection()->setPassword('chonchon2022'); 
		$this->excel->getActiveSheet()->getProtection()->setSheet(true); 
      	$this->drawing->setName('Logotipo1');
		$this->drawing->setDescription('Logotipo1');
		$img = imagecreatefrompng('assets/images/logo.png');
		$this->drawing->setImageResource($img);
		$this->drawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$this->drawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$this->drawing->setHeight(80);
		$this->drawing->setCoordinates('A1');
		$this->drawing->setWorksheet($this->excel->getActiveSheet());
		$this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
      	$this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
      	$this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'ESTUDIANTES DE BAJO RENDIMIENTO ');
        $contador1++;
    $this->excel->getActiveSheet()->getStyle("A{$contador1}:G{$contador1}")->applyFromArray($titulo);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A".($contador1).":E".($contador1));
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", $bimestre);

      	$contador1++;$contador1++;
      	$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'GESTION:');
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", ' '.$gestion);
      	$contador1++;
      	$this->excel->getActiveSheet()->getStyle("B{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NIVEL:');
      	$this->excel->getActiveSheet()->setCellValue("C{$contador1}", $niveles.' '.$turno);

      	$this->excel->getActiveSheet()->getStyle("D{$contador1}")->applyFromArray($negra);
      	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'UNID. EDU: ');
      	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $colegio);

       	$contador1++;
        $contador1++;
        $contador1++;
     
        $this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($estilo);
        $this->excel->getActiveSheet()->setCellValue("A{$contador1}", 'NUM');
        $this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        $this->excel->getActiveSheet()->setCellValue("B{$contador1}", 'NOMBRES');
        $this->excel->getActiveSheet()->setCellValue("D{$contador1}", 'NOTA');
        $this->excel->getActiveSheet()->setCellValue("E{$contador1}", 'CURSO');

		$contador1++;
		$x=1;
		foreach ($list as $estud) {

         	$this->excel->getActiveSheet()->getStyle("A{$contador1}:E{$contador1}")->applyFromArray($estilobor);
			$this->excel->getActiveSheet()->setCellValue("A{$contador1}", $x);
			$this->excel->setActiveSheetIndex(0)->mergeCells("B".($contador1).":C".($contador1));
        	$this->excel->getActiveSheet()->setCellValue("B{$contador1}", $estud->nombres);
        	$this->excel->getActiveSheet()->setCellValue("D{$contador1}", "=ROUND(".$estud->nota.",3)");
        	$cod=explode('-',$estud->codigo, -1);
        	$cur1=$this->estud->get_cursos($cod[0]);
         foreach ($cur1 as $curs) 
         {
         	$cursos=$curs->nombre;
         }

        	
        	$this->excel->getActiveSheet()->setCellValue("E{$contador1}", $cursos);
        	$contador1++;
        	$x++;
        }
		
        //Le ponemos un nombre al archivo que se va a generar.
        $archivo = "BAJO RENDIMIENTO_{$nivel}__{$gestion}_{$bi}.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$archivo.'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //Hacemos una salida al navegador con el archivo Excel.
        $objWriter->save('php://output');
    
     }
	public function ajax_get_curso()
	{
		$nivel=$this->input->post("nivel");

		//print_r($tablecur."-".$nivel);

		$list=$this->estud->cursos();

		$data=array();
		$data1=array();
		foreach ($list as $curso){
			if($nivel=='PT' OR $nivel=='PM')
			{
				if($curso->codigo!='6C')
				{
					$data[]=$curso->nombre;
					$data1[]=$curso->codigo;
				}
			}
			if($nivel=='ST' OR $nivel=='SM')
			{
				$data[]=$curso->nombre;
				$data1[]=$curso->codigo;
			}
			
		}
		$output=array(
			"status"=>TRUE,
			"data"=>$data,
			"data1"=>$data1,
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


	public function ajax_list_promovidos()
	{
		
		$bimes=$this->input->post('bimes');
		$nivel=$this->input->post('nivel');
		$gestion=$this->input->post('gestion');
		$idcur="CUR-1";

		varonesPro($idcur,$bimes,$gestion,$nivel);
		
		$list=$this->estud->get_datatables_by_all($nivel);

		$data = array();
		$no = $_POST['start'];
		
		foreach ($list as $curso) {
			
			$no++;
			$row = array();

			$idcur=$curso->idcurso;
			$genero='F';
			$c=0;
			//print_r($nivel);

			if(($nivel=='PRIMARIA MAÑANA')OR($nivel=='PRIMARIA TARDE'))
			{
				$idsql="primaria";		
				$varonesPro=$this->estud->notasEst($idsql,$idcur,$bimes,$gestion,$genero);		
				foreach ($varonesPro as $notas) {
					$nota=round(((round((($notas->LENGUAJE+$notas->INGLES)/2),0)+$notas->SOCIALES+$notas->EDUFISICA+ $notas->MUSICA+$notas->ARTPLAST+$notas->MATEMATICAS+$notas->INFORMATICA+$notas->CIENCIAS+$notas->RELIGION)/9),0);
					if($nota>=51) $c=$c+1;	
				}

				print_r($nota->curso."-".$c);
			}
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$c."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$row[] = "<td>".$curso->curso."</td>";
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->estud->count_all(),
						"recordsFiltered" => $this->estud->count_filtered($nivel),
						"data" => $data,
				);

		echo json_encode($output);
		
	}

	

	/*
	public function print($id)
	{
		$this->load->library('pdf');
		$list=$this->estud->get_print_estud_pdf($id);
		$curso=$this->estud->get_print_curso_pdf($id);

		ob_start();
			$this->pdf=new Pdf('Letter');
			$this->pdf->AddPage();
			$this->pdf->AliasNbPages();
			$this->pdf->SetTitle("LISTA DE ALUMNOS");
			$this->pdf->SetFont('Arial','BU',15);
			$this->pdf->Cell(30);
            $this->pdf->Cell(135,8,utf8_decode('LISTA DE ALUMNOS'),0,0,'C');
            $this->pdf->Ln('15');            
            $this->pdf->Cell(30);            
			$this->pdf->setXY(15,45);
			$this->pdf->SetFont('Arial','B',10);
            $this->pdf->Cell(35,5,utf8_decode('ID CURSO: '),0,0,'L');
            $this->pdf->setXY(35,45);
            $this->pdf->SetFont('Arial','',10);
            $this->pdf->Cell(15,5,utf8_decode($id),0,0,'L');
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
            $this->pdf->Cell(55,5,utf8_decode($curso->gestion),0,0,'C');
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
	*/
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
