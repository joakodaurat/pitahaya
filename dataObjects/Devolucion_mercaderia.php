<?php
/**
 * Table Definition for devolucion_mercaderia
 */
require_once 'DB/DataObject.php';

class DataObjects_Devolucion_mercaderia extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'devolucion_mercaderia';    // table name
    public $dev_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $dev_prod_id;                     // int(11)  not_null group_by
    public $dev_fh;                          // datetime(19)  not_null
    public $dev_monto;                       // float(7)  not_null group_by
    public $dev_cantidad;                    // int(11)  not_null group_by
    public $dev_cliente_id;                  // int(11)  group_by
    public $dev_prov_id;                     // int(11)  group_by
    public $dev_obs;                         // varchar(254)  
    public $dev_rest_stock;                  // int(1)  group_by
    public $dev_venta_id;                    // int(11)  group_by
    public $dev_venta_detalle_id;            // int(11)  group_by
    public $dev_compra_id;                   // int(11)  group_by
    public $dev_compra_detalle_id;           // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Devolucion_mercaderia',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE


    function devolverMercaderia($objeto){
       //print_r($objeto);exit;
        $this -> dev_prod_id = $objeto['combo_prod_dev'];
        $this -> dev_fh = date('Y-m-d H:i:s');
        $this -> dev_monto = $objeto['input_costo_total'];
        $this -> dev_cantidad = $objeto['input_cantidad_dev'];
        $this -> dev_obs = $objeto['input_obs_concepto'];
        $this -> dev_rest_stock = $objeto['input_restaurar_stock'];

        if($objeto['input_id_cliente']){
            $this -> dev_cliente_id = $objeto['input_id_cliente'];
            $this -> dev_venta_id = $objeto['concepto_venta_id'];
            $this -> dev_venta_detalle_id = $objeto['concepto_venta_detalle_id'];
            $param['combo_tipo'] = "NC";    //Genero una nota de credito al cliente por el monto de la devolucion
            $param['nota_tipo'] = 5;    //Genero una nota de credito al cliente por el monto de la devolucion
            $param['input_id_cliente'] = $objeto['input_id_cliente'];
            $param['input_obs_nota'] = "Devolucion de mercaderia en venta: ".$objeto['concepto_venta_id'];


            //Sumo la cantidad de mercaderia devuelta, para que la proxima no exceda el total vendido

            $do_venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
            $do_venta_detalle_stock -> vds_venta_detalle_id = $objeto['concepto_venta_detalle_id'];
            $do_venta_detalle_stock -> find(true);
            $id_vds = $do_venta_detalle_stock -> vds_prodstock_id;

            $do_venta_detalle_stock -> vds_cant_dev = $do_venta_detalle_stock -> vds_cant_dev + $objeto['input_cantidad_dev'];
            $do_venta_detalle_stock -> update();


            //Restauro Stock

            if($objeto['input_restaurar_stock']){
                $do_producto_stock = DB_DataObject::factory('producto_stock');
                $do_producto_stock -> ps_id = $id_vds;
                $do_producto_stock -> find(true);

                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad + $objeto['input_cantidad_dev'];
                $do_producto_stock -> update();
            }else{
                //Perdida de mercaderia
            }

        }elseif($objeto['concepto_prov_id']){
            $this -> dev_prov_id = $objeto['concepto_prov_id'];
            $this -> dev_compra_id = $objeto['concepto_compra_id'];
            $this -> dev_compra_detalle_id = $objeto['concepto_compra_detalle_id'];
            $param['combo_tipo'] = 6;    //Genero una nota de debito al proveedor por el monto de la devolucion
            $param['nota_tipo'] = "ND";    //Genero una nota de debito al proveedor por el monto de la devolucion
            $param['input_id_prov'] = $objeto['concepto_prov_id'];
            $param['input_obs_concepto'] = "Devolucion de mercaderia en compra: ".$objeto['concepto_compra_id'];
            $param['concepto_fh'] = date('Y-m-d H:i:s');

            //Sumo la cantidad de mercaderia devuelta, para que la proxima no exceda el total vendido

            $do_compra_detalle = DB_DataObject::factory('compra_detalle');
            $do_compra_detalle -> detalle_id = $objeto['concepto_compra_detalle_id'];
            $do_compra_detalle -> find(true);
            //$id_detalle_compra = $do_compra_detalle -> detalle_id;

            $do_compra_detalle -> detalle_prod_dev = $do_compra_detalle -> detalle_prod_dev + $objeto['input_cantidad_dev'];
            $do_compra_detalle -> update();


            //Resto Stock

            if($objeto['input_restaurar_stock']){
                $do_producto_stock = DB_DataObject::factory('producto_stock');
                $do_producto_stock -> whereAdd('ps_compra_id = '.$objeto['concepto_compra_id'].' AND ps_producto_id = '.$objeto['combo_prod_dev']);
                $do_producto_stock -> find(true);

                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $objeto['input_cantidad_dev'];
                $do_producto_stock -> update();
            }
        }

        


        $id_dm = $this -> insert();

        $param['input_monto'] = $objeto['input_costo_total']; 
        $param['nota_fh'] = date('Y-m-d H:i:s');
        $param['id_dm'] = $id_dm;

        $do_nota = DB_DataObject::factory('notas');
        if($objeto['input_id_cliente']){
            $ND = $do_nota -> nuevaNotaDesdeConcepto($param);
        }else{
            $NC = $do_nota -> notaProvDesdeCompra($param);
        }
        return $id_dm;


    }
}
