<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  
require_once APPPATH."/libraries/PHPExcel_Worksheet_MemoryDrawing.php"; 
class Drawing extends PHPExcel_Worksheet_MemoryDrawing {
    public function __construct() {
        parent::__construct();
    }
}