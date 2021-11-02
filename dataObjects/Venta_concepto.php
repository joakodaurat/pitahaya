<?php
/**
 * Table Definition for venta_concepto
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta_concepto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta_concepto';      // table name
    public $vc_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $vc_venta_id;                     // int(11)  not_null group_by
    public $vc_tipo;                         // int(11)  not_null group_by
    public $vc_observacion;                  // varchar(256)  
    public $vc_fh;                           // datetime(19)  
    public $vc_monto;                        // int(11)  group_by
    public $vc_op_id;                        // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta_concepto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoConcepto($objeto) {
        $this -> vc_venta_id = $objeto['concepto_venta_id'];
        $this -> vc_tipo = $objeto['combo_tipo'];
        $this -> vc_fh = $objeto['concepto_fh'];
        $this -> vc_monto = $objeto['input_monto'];
        $this -> vc_observacion = $objeto['input_obs_concepto'];

        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $objeto['concepto_venta_id'];
        $venta -> find(true);
        $params['input_id_cliente'] = $venta -> venta_cliente_id;
        $params['concepto_venta_id'] = $objeto['concepto_venta_id'];
        $params['nota_ccop_tipo'] = 1;  
        $params['input_monto'] = $objeto['input_monto'];  
        $params['nota_fh'] = $objeto['concepto_fh']; 
        $params['input_obs_nota'] = $objeto['input_obs_concepto']; 

        if($objeto['combo_tipo'] == 1 || $objeto['combo_tipo'] == 2) {          //NOTA DE CREDITO - DEBITO
            if($objeto['combo_tipo'] == 1) {
                $params['combo_tipo'] = 'NC';
            }elseif($objeto['combo_tipo'] == 2){
                $params['combo_tipo'] = 'ND';
            }            
            $notas = DB_DataObject::factory('notas');
            $id_nota = $notas -> nuevaNotaDesdeConcepto($params);
            $this -> vc_op_id = $id_nota;
        }elseif($objeto['combo_tipo'] == 4){                                    // FACTURACION
            $cc_cliente = DB_DataObject::factory('cliente_cuenta_corriente');
            $cc_cliente -> cargarFacturacion($venta -> venta_cliente_id,$venta -> venta_id,$objeto['input_monto']);

        }elseif($objeto['combo_tipo'] == 5){                                    // DEVOLUCION MERCADERIA
            $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            $nc_dev = $dev_mercaderia -> devolverMercaderia($objeto);
        }
        $this -> vc_op_id = $nc_dev;     //Guardo el Id de la operacion
        $id = $this -> insert();
    }
/* funcion vieja
   function getTipo($id){
        $do_tipo = DB_DataObject::factory('venta_concepto_tipo');
       $do_tipo -> vc_tipo_id = $this -> vc_tipo;
       $do_tipo -> find(true);
         
        return $do_tipo -> vc_tipo_nombre;
   }
*/
    function getTipo($id){
         $do_tipo = DB_DataObject::factory('venta_concepto_tipo');
         $do_tipo -> vc_tipo_id = $this -> vc_tipo;
         $do_tipo -> find(true);
        
         if($this -> vc_tipo == 5){
             $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
             $dev_mercaderia -> dev_id = $this -> vc_op_id;
             $dev_mercaderia -> find(true);
             $do_producto = DB_DataObject::factory('producto');
             $producto = $do_producto -> getProductos($dev_mercaderia -> dev_prod_id);
             $texto = '(Cantidad:'.$dev_mercaderia -> dev_cantidad.' - '.$producto -> prod_nombre.')';
             
         }else{
            $texto = '';
         }
         
         return $do_tipo -> vc_tipo_nombre.$texto;
    }


}
