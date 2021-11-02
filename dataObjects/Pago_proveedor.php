<?php
/**
 * Table Definition for pago_proveedor
 */
require_once 'DB/DataObject.php';

class DataObjects_Pago_proveedor extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'pago_proveedor';      // table name
    public $pago_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $pago_fh;                         // datetime(19)  not_null
    public $pago_prov_id;                    // int(11)  not_null group_by
    public $pago_compra_id;                  // int(11)  group_by
    public $pago_monto_total;                // float(11)  not_null group_by
    public $pago_usuario_id;                 // int(11)  not_null group_by
    public $pago_forma_pago;                 // int(2)  not_null group_by
    public $pago_observacion;                // varchar(256)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Pago_proveedor',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoPago($objeto) {
        // Guardo el pago
        // Genero asiento en CC
        // Creo cheque de ser necesario

        $this -> pago_fh = date("Y-m-d H:i:s");
        $this -> pago_prov_id = $objeto['input_id_proveedor'];
        $this -> pago_forma_pago = $objeto['combo_fpago'];
        $this -> pago_usuario_id = $_SESSION['usuario']['id'];
        if($objeto['compra_id']){
            $this -> pago_compra_id = $objeto['compra_id'];
        }
        $this -> pago_observacion = $objeto['input_obs_pago'];

        if($objeto['combo_fpago'] == 1){  
            $objeto['input_monto_total'] =  $objeto['input_monto_contado'];
        }elseif($objeto['combo_fpago'] == 2){
            $objeto['input_monto_total'] =  $objeto['input_monto_mercado'];
        }elseif($objeto['combo_fpago'] == 3){
            $objeto['input_monto_total'] =  $objeto['input_monto_credito'];
        }elseif($objeto['combo_fpago'] == 4){
            $objeto['input_monto_total'] =  $objeto['input_monto_debito'];
        }

        $this -> pago_monto_total = $objeto['input_monto_total']; 
        $id_pago = $this -> insert();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarPago($objeto, $id_pago);

        if($id_cc) {
            return $id_pago;
        } else {
            return "ERROR CC";
        }
    }

    function getPagos($desde = false,$hasta = false) {

        $do_usuario = DB_DataObject::factory('usuario');
        $do_proveedor = DB_DataObject::factory('proveedor');
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_proveedor);
        $this -> joinAdd($forma_pago);

        if($desde && $hasta){
            $this -> whereAdd('pago_fh BETWEEN "'.$desde.'" AND "'.$hasta.' 23:59:59"');
        }

        $this -> orderBy('pago_id DESC');
        $this -> find();

        return $this;
    } 

    function getFormaPago($id) {

        $forma_pago = DB_DataObject::factory('forma_pago');
        $forma_pago -> fp_id = $id;
        $forma_pago -> find(true);

        return $forma_pago -> fp_desc;
       
    } 

    function getPagosCaja($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }

        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($forma_pago);
        $this -> whereAdd('pago_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total[$this -> fp_desc] += $this -> pago_monto_total;
            $suma += $this ->  pago_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }
    function getPagosHistorialCaja($fh) {

        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($forma_pago);
        $this -> whereAdd('pago_fh LIKE "%'.$fh.'%"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total[$this -> fp_desc] += $this -> pago_monto_total;
            $suma += $this ->  pago_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }  

}
