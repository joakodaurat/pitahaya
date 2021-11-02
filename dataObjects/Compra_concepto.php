<?php
/**
 * Table Definition for compra_concepto
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra_concepto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra_concepto';     // table name
    public $cc_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $cc_compra_id;                    // int(11)  not_null group_by
    public $cc_tipo;                         // int(11)  not_null group_by
    public $cc_observacion;                  // varchar(245)  
    public $cc_fh;                           // datetime(19)  
    public $cc_monto;                        // float(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra_concepto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoConcepto($objeto) {
        $this -> cc_compra_id = $objeto['concepto_compra_id'];
        $this -> cc_tipo = $objeto['combo_tipo'];
        $this -> cc_fh = date('Y-m-d H:i:s');
        $this -> cc_monto = $objeto['input_monto'];
        $this -> cc_observacion = $objeto['input_obs_concepto'];

        $compra = DB_DataObject::factory('compra');
        $compra -> compra_id = $objeto['concepto_compra_id'];
        $compra -> find(true);

        $params['input_id_prov'] = $compra -> compra_prov_id;
        $params['input_id_transp'] = $compra -> compra_transp_id;
        $params['compra_id'] = $objeto['concepto_compra_id'];
        $params['input_monto'] = $objeto['input_monto'];  
        $params['concepto_fh'] = date('Y-m-d H:i:s'); 
        $params['input_obs_concepto'] = $objeto['input_obs_concepto']; 
        $params['combo_tipo']= $objeto['combo_tipo'];

        if($objeto['combo_tipo'] == 7 ) {      //Impuesto
            $compra -> compra_concepto_impuestos = $compra -> compra_concepto_impuestos + $objeto['input_monto'];
            $compra -> update();
        }

        if($objeto['combo_tipo'] == 1 ) {      //Costo Descarga
            $params['combo_tipo_nombre'] = 'Costo Descarga';
            $params['input_cantidad'] = $objeto['input_cantidad'];
            $params['input_costo_unitario'] = $objeto['input_costo_unitario'];
            $descarga = DB_DataObject::factory('descarga');
            $id_op = $descarga -> nuevaDescarga($params);
            $compra -> compra_concepto_descargas = $compra -> compra_concepto_descargas + ($objeto['input_cantidad'] * $objeto['input_costo_unitario']);
            $compra -> update();
        }

        if($objeto['combo_tipo'] == 2){        //Entrega Transportista
            $params['combo_tipo_nombre'] = 'Entrega Transportista';    
            $params['combo_fpago']= 1;  //Efectivo
            $params['input_obs_pago'] = $objeto['input_obs_concepto']; 
            $pago_transp = DB_DataObject::factory('pago_transportista');
            $id_op = $pago_transp -> nuevoPago($params);
        }

        if($objeto['combo_tipo'] == 3){        //costo Flete
            $params['combo_tipo_nombre'] = 'Costo Flete';  
            $params['input_cantidad'] = $objeto['input_cantidad'];
            $params['input_costo_unitario'] = $objeto['input_costo_unitario'];
            $flete = DB_DataObject::factory('flete');
            $id_op = $flete -> nuevoFlete($params);
            $compra -> compra_concepto_fletes = $compra -> compra_concepto_fletes + ($objeto['input_cantidad'] * $objeto['input_costo_unitario']);
            $compra -> update();         
        }

        if($objeto['combo_tipo'] == 5){        //Nota Credito
            $params['combo_tipo_nombre'] = 'Nota Credito';    
            $params['nota_tipo'] = 'NC';  
            $notas = DB_DataObject::factory('notas');
            $id_op = $notas -> notaProvDesdeCompra($params);
        }

        if($objeto['combo_tipo'] == 6){        //Nota Debito
            $params['combo_tipo_nombre'] = 'Nota Debito';    
            $params['nota_tipo'] = 'ND';  
            $notas = DB_DataObject::factory('notas');
            $id_op = $notas -> notaProvDesdeCompra($params);
        }

        if($objeto['combo_tipo'] == 8){                                    // DEVOLUCION MERCADERIA
            $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
            $id_op = $dev_mercaderia -> devolverMercaderia($objeto);
        }

        $this -> cc_op_id = $id_op;     //Guardo el Id de la operacion

        $id_concepto = $this -> insert();
    }

    function getTipo($id){
         $do_tipo = DB_DataObject::factory('compra_concepto_tipo');
         $do_tipo -> cc_tipo_id = $this -> cc_tipo;
         $do_tipo -> find(true);
        
         if($this -> cc_tipo == 8){
             $dev_mercaderia = DB_DataObject::factory('devolucion_mercaderia');
             $dev_mercaderia -> dev_id = $this -> cc_op_id;
             $dev_mercaderia -> find(true);
             $do_producto = DB_DataObject::factory('producto');
             $producto = $do_producto -> getProductos($dev_mercaderia -> dev_prod_id);
             $texto = '(Cantidad:'.$dev_mercaderia -> dev_cantidad.' - '.$producto -> prod_nombre.')';
             
         }else{
            $texto = '';
         }
         
         return $do_tipo -> cc_tipo_nombre.$texto;
    }
}
