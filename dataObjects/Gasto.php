<?php
/**
 * Table Definition for gasto
 */
require_once 'DB/DataObject.php';

class DataObjects_Gasto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'gasto';               // table name
    public $gasto_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $gasto_fh;                        // datetime(19)  not_null
    public $gasto_concepto;                  // varchar(255)  not_null
    public $gasto_monto_total;               // float(11)  not_null group_by
    public $gasto_usuario_id;                // int(11)  not_null group_by
    public $gasto_caja_id;                   // int(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Gasto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getGasto($id = false) {
        $do_gastos = DB_DataObject::factory('gasto');

        if($id){
            $do_gastos -> gasto_id = $id;
        }

        $do_usuario = DB_DataObject::factory('usuario');
        $do_gastos -> joinAdd($do_usuario);

        $do_proveedor = DB_DataObject::factory('proveedor'); 
        $do_gastos -> joinAdd($do_proveedor);

        if($id){
            $do_gastos -> find(true);
        } else {
            $do_gastos -> orderBy('gasto_id DESC');
            $do_gastos -> find();
        }
        return $do_gastos;
    }

    function getGastosEstadisticos($f_desde = false,$f_hasta = false) {
        $respuesta = array();

        if(!$f_desde) {
            $this -> gasto_fh > date('Y-01-01 00:00:00');
        } else {
            $this -> whereAdd('gasto_fh BETWEEN "'.$f_desde.'" AND "'.$f_hasta.'"');
        }

        $usr = DB_DataObject::factory('usuario'); 
        $this -> joinAdd($usr);

        $this -> find();

        while ($this -> fetch()) {
            $respuesta['gasto_'.$this -> gasto_id]['fecha'] = $this -> gasto_fh;
            $respuesta['gasto_'.$this -> gasto_id]['concepto'] = 'Gasto #'.$this -> gasto_id.' ('.$this -> gasto_concepto.')';
            $respuesta['gasto_'.$this -> gasto_id]['monto'] = 0 - $this -> gasto_monto_total;
            $respuesta['gasto_'.$this -> gasto_id]['observacion'] = $this -> gasto_observacion;
            $respuesta['gasto_'.$this -> gasto_id]['usuario'] = $this -> usua_nombre;
            $respuesta['gasto_'.$this -> gasto_id]['color'] = '#f7e3e0';
        }

        return $respuesta;
    }

    function getGastosPorMes() {
        $respuesta = array();

        $respuesta['cantidad'][9] = 0;
        $respuesta['cantidad'][8] = 0;
        $respuesta['cantidad'][7] = 0;
        $respuesta['cantidad'][6] = 0;
        $respuesta['cantidad'][5] = 0;
        $respuesta['cantidad'][4] = 0;
        $respuesta['cantidad'][3] = 0;
        $respuesta['cantidad'][2] = 0;
        $respuesta['cantidad'][1] = 0;

        $fecha_mesactual = new DateTime();
        
        for ($i=5; $i > 0; $i--) { 
            $k = $i - 1;
            $fecha_mesactual -> modify("-$k month");
            $obj = clone $this;
            $mes = $fecha_mesactual -> format('m');
            $anio = $fecha_mesactual -> format('Y');
            $obj -> whereAdd('MONTH(gasto_fh) = '.$mes.' AND YEAR(gasto_fh) = '.$anio);
            $obj -> find();
            $respuesta['mes'][] = strftime('%b',strtotime($fecha_mesactual -> format('Y-m-d')));
            while($obj -> fetch()){
                $respuesta['cantidad'][$i] += $obj -> gasto_monto_total;  
            }
            $fecha_mesactual -> modify("+$k month");
        }

        return $respuesta;
    }

     function getGastosCaja($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }
        $this -> whereAdd('gasto_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');

        $this -> find();

        $suma = 0;
        while($this -> fetch()){
            $total[$this -> tg_nombre] += $this ->  gasto_monto_total;
            $suma += $this ->  gasto_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }

     function getGastosHistorialCaja($fh) {
     
        $tipo_gasto = DB_DataObject::factory('tipo_gasto'); 
        $this -> joinAdd($tipo_gasto);
        $this -> whereAdd('gasto_fh  LIKE "%'.$fh.'%"');

        $this -> find();

        $suma = 0;
        while($this -> fetch()){
            $total[$this -> tg_nombre] += $this ->  gasto_monto_total;
            $suma += $this ->  gasto_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }
    
    

     function getConcepto() {

        $tipo_gasto = DB_DataObject::factory('tipo_gasto'); 
        $tipo_gasto -> tg_id = $this -> gasto_concepto;
        $tipo_gasto -> find(true);

        return $tipo_gasto -> tg_nombre;
    }
}
