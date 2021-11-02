<?php
/**
 * Table Definition for proveedor_cuenta_corriente
 */
require_once 'DB/DataObject.php';

class DataObjects_Proveedor_cuenta_corriente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'proveedor_cuenta_corriente';    // table name
    public $ccte_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $ccte_proveedor_id;               // int(11)  not_null group_by
    public $ccte_fh;                         // datetime(19)  not_null
    public $ccte_operacion_tipo;             // int(11)  not_null group_by
    public $ccte_operacion_id;               // int(11)  not_null group_by
    public $ccte_importe;                    // float(11)  not_null group_by
    public $ccte_saldo_actual;               // float(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Proveedor_cuenta_corriente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function cargarPago($objeto,$id_pago) {
        $cc_ant = DB_DataObject::factory('proveedor_cuenta_corriente');
        $cc_ant -> ccte_proveedor_id = $objeto['input_id_proveedor'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $monto = $objeto['input_monto_total'];
        
        $this -> ccte_proveedor_id = $objeto['input_id_proveedor']; 
        $this -> ccte_fh = date("Y-m-d",strtotime($objeto['pago_fh']));
        $this -> ccte_operacion_tipo = 4;
        $this -> ccte_operacion_id = $id_pago;
        $this -> ccte_importe = $monto;
        $this -> ccte_saldo_actual = $saldo_anterior + $monto;
        $id = $this -> insert();
        return $id;
    }

    function cargarCompra($objeto,$id_compra) {
        $cc_ant = DB_DataObject::factory('proveedor_cuenta_corriente');
        $cc_ant -> ccte_proveedor_id = $objeto['input_id_prov'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_proveedor_id = $objeto['input_id_prov']; 
        $this -> ccte_fh = date("Y-m-d",strtotime($objeto['compra_fh']));
        $this -> ccte_operacion_tipo = 2;
        $this -> ccte_operacion_id = $id_compra;
        $this -> ccte_importe = $objeto['saldo_final_total'];
        $this -> ccte_saldo_actual = $saldo_anterior - $objeto['saldo_final_total'];
        $id = $this -> insert();
        return $id;
    }

     function cargarNota($objeto,$id_nota) {
        $cc_ant = DB_DataObject::factory('proveedor_cuenta_corriente');
        $cc_ant -> ccte_proveedor_id = $objeto['input_id_prov'];
        $cc_ant -> orderBy('ccte_id DESC');
        $cc_ant -> find(true);

        $saldo_anterior = $cc_ant -> ccte_saldo_actual;

        $this -> ccte_proveedor_id = $objeto['input_id_prov']; 
        $this -> ccte_fh = date("Y-m-d",strtotime($objeto['concepto_fh']));
        $this -> ccte_operacion_tipo = $objeto['combo_tipo'];
        $this -> ccte_operacion_id = $id_nota;
        $this -> ccte_importe = $objeto['input_monto'];
        if($objeto['combo_tipo'] == 6){     //Nota de Debito
            $this -> ccte_saldo_actual = $saldo_anterior + $objeto['input_monto'];
        }elseif($objeto['combo_tipo'] == 5){    //Nota de Credito
            $this -> ccte_saldo_actual = $saldo_anterior - $objeto['input_monto'];
        }
        $id = $this -> insert();
        return $id;
    }

    function proveedorGetCC($id, $desde, $hasta){
        $hasta = str_replace('/', '-', $hasta);
        $desde = str_replace('/', '-', $desde);

        $f_hasta = new DateTime($hasta);  
        $h = date_format($f_hasta,'Y-m-d 23:59:59');

        $f_desde = new DateTime($desde);  
        $d = date_format($f_desde,'Y-m-d 00:00:00');

        $ops = DB_DataObject::factory('cuenta_corriente_operacion');

        $this -> ccte_proveedor_id = $id;
        $this -> whereAdd('ccte_fh BETWEEN "'.$d.'" AND "'.$h.'"');
        
        $this -> orderBy('ccte_id DESC');
        $this -> joinAdd($ops);
        $this -> find();

        return $this;
    }

    function getUltimaCC($id) {
        $respuesta = array();

        $this -> ccte_proveedor_id = $id;
        $this -> orderBy('ccte_id DESC');
        $this -> find(true);
        
        return $this;
    }  

    function getTipoPago(){
        $pago = DB_DataObject::factory('pago_proveedor');
        $fpago = DB_DataObject::factory('forma_pago');
        $pago -> joinAdd($fpago);
        $pago -> pago_id = $this -> ccte_operacion_id;
        $pago -> find(true);

        return ($pago -> fp_desc);
    } 

}
