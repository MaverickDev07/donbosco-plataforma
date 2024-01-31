<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
   require_once APPPATH."/libraries/PHPExcel.php"; 
 
    //Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
    class Estillo extends PHPExcel {
        public function __construct() {
            parent::__construct();
        }

        function etilloBa(){
          $estilo = array(
              'font'  => array(
                  'bold'  => true,
                  'size'  => 10,
                  'color' => array('rgb' => 'ffffff'),
                  //'startcolor' => array('rgb' => '00B050'),
                  'name'  => 'Verdana' ),
              'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '045aaa') ),
              'borders' => array(
                  'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN)),
              'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
          return $estilo;
        }

        function etilloBor(){
            $estilobor = array(
		            'borders' => array(
                      'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN)),
		            'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'd7eafa')),
		            'alignment' => array(
                      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,));
            return $estilobor;
      }
    }

?>
