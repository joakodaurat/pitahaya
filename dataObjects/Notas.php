<?php
/**
 * Table Definition for notas
 */
require_once 'DB/DataObject.php';

class DataObjects_Notas extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'notas';               // table name
    public $nota_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $nota_cliente_id;                 // int(11)  not_null group_by
    public $nota_prov_id;                    // int(11)  group_by
    public $nota_tipo;                       // varchar(2)  not_null
    public $nota_monto;                      // float(11)  not_null group_by
    public $nota_fh;                         // datetime(19)  
    public $nota_alta_fh;                    // datetime(19)  
    public $nota_observacion;                // varchar(256)  
    public $nota_ccop_tipo;                  // int(11)  group_by
    public $nota_ccop_id;                    // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Notas',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevaNota($objeto) {
        if($objeto['input_id_cliente']){
            $this -> nota_cliente_id = $objeto['input_id_cliente'];
        }
        if($objeto['input_id_prov']){
            $this -> nota_prov_id = $objeto['input_id_prov'];
        }
        $this -> nota_tipo = $objeto['combo_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['nota_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_nota'];
        $id = $this -> insert();

        if($objeto['input_id_cliente']){
            $cc = DB_DataObject::factory('cliente_cuenta_corriente');
            $id_cc = $cc -> cargarNota($objeto, $id);
        }

        if($objeto['input_id_prov']){
            $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
            $id_cc = $cc -> cargarNota($objeto, $id);   
        }
        
        return $id;
    }

    function nuevaNotaDesdeConcepto($objeto) {
        $this -> nota_cliente_id = $objeto['input_id_cliente'];
        $this -> nota_tipo = $objeto['nota_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['nota_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_nota'];
        
        if($objeto['id_dm']){
            $this -> nota_ccop_tipo = 11;
            $this -> nota_ccop_id = $objeto['id_dm'];
            
        }else{
            $this -> nota_ccop_tipo = $objeto['nota_ccop_tipo'];
            $this -> nota_ccop_id = $objeto['concepto_venta_id'];
        }
        $id = $this -> insert();

        $cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function getNotas($desde = false,$hasta = false) {

        $do_cliente = DB_DataObject::factory('cliente');
        $do_proveedor = DB_DataObject::factory('proveedor');

        $this -> joinAdd($do_cliente,"LEFT");
        $this -> joinAdd($do_proveedor,"LEFT");

        if($desde && $hasta){
            $this -> whereAdd('nota_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('nota_id DESC');
        $this -> find();

        return $this;
    }


    function notaProvDesdeCompra($objeto) {
        $this -> nota_prov_id = $objeto['input_id_prov'];
        $this -> nota_tipo = $objeto['nota_tipo'];
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        if($objeto['id_dm']){
            $this -> nota_ccop_tipo = 11;
            $this -> nota_ccop_id = $objeto['id_dm'];
            
        }else{
            $this -> nota_ccop_tipo = 2;
            $this -> nota_ccop_id = $objeto['compra_id'];
        }

        $id = $this -> insert();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function notaProvChequeRechazado($objeto) {
        $this -> nota_prov_id = $objeto['input_id_prov'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }
    function notaTransportistaChequeRechazado($objeto) {
        $this -> nota_tranportista_id = $objeto['input_id_tranportista'];
        $this -> nota_tipo = 'NC';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 4;        //Operacion por la cual genero una NC, en este caso por un pago 
        $this -> nota_ccop_id = $objeto['pago_id'];
        $id = $this -> insert();

        $cc = DB_DataObject::factory('transportista_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }

    function notaClienteChequeRechazado($objeto) {
        $this -> nota_cliente_id = $objeto['input_id_cliente'];
        $this -> nota_tipo = 'ND';
        $this -> nota_monto = $objeto['input_monto'];
        $this -> nota_fh = $objeto['concepto_fh'];
        $this -> nota_alta_fh = date('Y-m-d H:i:s');
        $this -> nota_observacion = $objeto['input_obs_concepto'];
        $this -> nota_ccop_tipo = 3;
        $this -> nota_ccop_id = $objeto['cobro_id'];
        $id = $this -> insert();

        $objeto['nota_fh'] = date('Y-m-d H:i:s');
        
        $cc = DB_DataObject::factory('cliente_cuenta_corriente');
        $id_cc = $cc -> cargarNota($objeto, $id);

        return $id;
    }
}
