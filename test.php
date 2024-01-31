<?php
date_default_timezone_set('America/La_Paz');
$fecha = getdate();
$today = $fecha['mday'].'_'.$fecha['mon'].'_'.$fecha['year'].'__'.$fecha['hours'].'_'.$fecha['minutes'].'_'.$fecha['seconds'];
print_r($today);
print_r($fecha);
?>
