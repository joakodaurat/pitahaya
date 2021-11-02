<?php
	require_once('../config/web.config');
	require_once(AUTHFILE);
	require_once(CFG_PATH.'/data.config');
	require_once(INC_PATH.'/pear.inc');
	require_once(INC_PATH.'/comun.php');

	$fecha_actual = new DateTime();	
	$f_desde =  $fecha_actual -> modify("first day of this month");

	$inversiones = DB_DataObject::factory('inversiones');
	$caja = DB_DataObject::factory('caja');


	if(!$_GET['fecha_desde']){
		$do_caja = $caja -> getcajas($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$do_inversiones = $inversiones -> getInversiones($f_desde -> format('Y-m-d'),date('Y-m-d H:i:s'));
		$campoFecha = date_format($f_desde,'d/m/Y').' - '.date('d/m/Y');


	} else {
		$do_caja = $caja -> getcajas($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$do_inversiones =  $inversiones -> getinversiones($_GET['fecha_desde'],$_GET['fecha_hasta']);
		$campoFecha = date('d/m/Y',strtotime($_GET['fecha_desde'])).' - '.date('d/m/Y',strtotime($_GET['fecha_hasta']));
	}
   $ganancia = array();

  while($do_caja -> fetch()){

 		$gastos = DB_DataObject::factory('gasto');
		$cobro = DB_DataObject::factory('cobro_cliente'); 	

  		$total_gastos = $gastos -> getGastosCaja($do_caja -> caja_fh_inicio, $do_caja -> caja_fh_cierre);
    	$ganancia[date('d/m/Y',strtotime($do_caja -> caja_fh_inicio))]['gastos'] += $total_gastos['Total'];
    	$total_cobros = $cobro -> getIngresosCaja($do_caja -> caja_fh_inicio, $do_caja -> caja_fh_cierre);
    	$ganancia[date('d/m/Y',strtotime($do_caja -> caja_fh_inicio))]['ingresos'] += $total_cobros['Total'];
		}

	require_once('public/balance.html');
	exit;
?>