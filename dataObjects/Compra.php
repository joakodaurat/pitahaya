<?php
/**
 * Table Definition for compra
 */
require_once 'DB/DataObject.php';

class DataObjects_Compra extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'compra';              // table name
    public $compra_id;                       // int(11)  not_null primary_key auto_increment group_by
    public $compra_fh;                       // datetime(19)  not_null
    public $compra_prov_id;                  // int(11)  not_null group_by
    public $compra_monto_total;              // float(11)  not_null group_by
    public $compra_usuario_id;               // int(11)  not_null group_by
    public $compra_observacion;              // blob(65535)  blob
    public $compra_tipo_operacion;           // varchar(256)  
    public $compra_lugar;                    // varchar(256)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Compra',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE



   /* 
    * function nuevaCompra($objeto)
    *   1. Crea una nueva compra
    *   2. Sumar stock
    *   3. Asigna productos al detalle de la compra
    *   4. Agregar proveedor en caso de no seleccionar
    *   6. Cargo la cuenta corriente.
    */


    function nuevaCompra($objeto) {

        // 1. Crea una nueva compra
        $compra = DB_DataObject::factory('compra');
        $compra -> compra_fh = date("Y-m-d H:i:s");
        $compra -> compra_prov_id = 99;  
        $compra -> compra_monto_total = $objeto['saldo_final_total'];
        $compra -> compra_usuario_id = $_SESSION['usuario']['id'];
        $compra -> compra_observacion = $objeto['input_observacion_compra'];
        $compra -> compra_tipo_operacion = "Entrada";
        $compra -> compra_lugar = "Tienda";

        $id_compra = $compra -> insert();

        // 2. Suma stock
        // 3. Asigna productos al detalle de la compras
        $suma_peso = 0;
        foreach ($objeto['prod'] as $p) {
            $producto = DB_DataObject::factory('producto'); 
            $prod = $producto -> getProductos($p['id']);
            if($prod -> sumarStock($id_compra, $p['id'], $p['cantidad'], $p['talle'])) {

                $detalle = DB_DataObject::factory('compra_detalle');
                $detalle -> detalle_compra_id = $id_compra;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_color = $p['color'];
                $detalle -> detalle_prod_talle = $p['talle'];
                $detalle -> detalle_prod_cant = $p['cantidad'];
                $detalle -> detalle_prod_precio_u = $p['precio_kg'];
                $detalle -> detalle_prod_tipo = $p['tipo'];
                $detalle -> insert();

            }
        }

        $compra_e = DB_DataObject::factory('compra');
        $compra_e -> compra_id = $id_compra;
        $compra_e -> find(true);
        $compra_e -> update();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarCompra($objeto, $id_compra);

        return $id_compra;
    }
     function nuevaentradabodega($objeto) {
        //print_r($objeto);exit;
        // 1. Crea una nueva compra
        $compra = DB_DataObject::factory('compra');
        $compra -> compra_fh = date("Y-m-d H:i:s");
        $compra -> compra_prov_id = 99;  
        $compra -> compra_usuario_id = $_SESSION['usuario']['id'];
        $compra -> compra_observacion = $objeto['input_observacion_compra'];
        $compra -> compra_tipo_operacion = "Entrada";
        $compra -> compra_lugar = "Bodega";

        $id_compra = $compra -> insert();

        // 2. Suma stock
        // 3. Asigna productos al detalle de la compras
        $suma_peso = 0;
        foreach ($objeto['prod'] as $p) {
            $producto = DB_DataObject::factory('producto'); 
            $prod = $producto -> getProductos($p['id']);
            if($prod -> sumarStockBodega($id_compra, $p['id'], $p['cantidad'], $p['talle'])) {

                $detalle = DB_DataObject::factory('compra_detalle');
                $detalle -> detalle_compra_id = $id_compra;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_color = $p['color'];
                $detalle -> detalle_prod_talle = $p['talle'];
                $detalle -> detalle_prod_cant = $p['cantidad'];
                $detalle -> detalle_prod_precio_u = $p['precio_kg'];
                $detalle -> detalle_prod_tipo = $p['tipo'];
                $detalle -> insert();

            }
        }

        $compra_e = DB_DataObject::factory('compra');
        $compra_e -> compra_id = $id_compra;
        $compra_e -> find(true);
        $compra_e -> update();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarCompra($objeto, $id_compra);

        return $id_compra;
    }

    function nuevaSalida($objeto) {

        // 1. Crea una nueva compra
        $compra = DB_DataObject::factory('compra');
        $compra -> compra_fh = date("Y-m-d H:i:s");
        $compra -> compra_usuario_id = $_SESSION['usuario']['id'];
        $compra -> compra_observacion = $objeto['input_observacion_compra'];
        $compra -> compra_tipo_operacion = "Salida";
        $compra -> compra_lugar = "Tienda";

        $id_compra = $compra -> insert();

        // 2. Suma stock
        // 3. Asigna productos al detalle de la compras
     
        foreach ($objeto['prod'] as $p) {
            $producto = DB_DataObject::factory('producto'); 
            $prod = $producto -> getProductos($p['id']);
            $respuesta = is_array($prod -> restarStock($p['talle'], $p['cantidad']));
            if($respuesta) {

                $detalle = DB_DataObject::factory('compra_detalle');
                $detalle -> detalle_compra_id = $id_compra;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_color = $p['color'];
                $detalle -> detalle_prod_talle = $p['talle'];
                $detalle -> detalle_prod_cant = $p['cantidad'];
                $detalle -> detalle_prod_precio_u = $p['precio_kg'];
                $detalle -> detalle_prod_tipo = $p['tipo'];
                $detalle -> insert();

            }
        }

        $compra_e = DB_DataObject::factory('compra');
        $compra_e -> compra_id = $id_compra;
        $compra_e -> find(true);
        $compra_e -> update();

        $cc = DB_DataObject::factory('proveedor_cuenta_corriente');
        $id_cc = $cc -> cargarCompra($objeto, $id_compra);

        return $id_compra;
    }



    /* 
    * function getCompra($id = false)
    *   Devuelve una o todas las compras (dependiendo si tiene ID o no), con join a:
    *   Usuario que creó la compra
    *   Proveedor
    *   Estado de compra
    */
    function getCompra($id = false) {
        $do_compras = DB_DataObject::factory('compra');

        if($id){
            $do_compras -> compra_id = $id;
        }

        $do_usuario = DB_DataObject::factory('usuario');
        $do_proveedor = DB_DataObject::factory('proveedor');
        
        $do_compras -> joinAdd($do_usuario,"LEFT");
        $do_compras -> joinAdd($do_proveedor,"LEFT");


        if($id){
            $do_compras -> find(true);


        } else {
            $do_compras -> orderBy('compra_id DESC');
            $do_compras -> find();
        }
        return $do_compras;
    }

    function getCompras($desde = false,$hasta = false) {

        $do_usuario = DB_DataObject::factory('usuario');

        $this -> joinAdd($do_usuario);

        if($desde && $hasta){
            $this -> whereAdd('compra_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('compra_id DESC');
        $this -> find();

        return $this;
    }

    function getDetalleString() {
        $detalle = DB_DataObject::factory('compra_detalle');
        $detalle -> detalle_compra_id = $this -> compra_id;
        $producto = DB_DataObject::factory('producto');
        $detalle -> joinAdd($producto);
        $detalle -> find();

        $respuesta = '';
        $i = 0;
        while ($detalle -> fetch()) {
            if($i == 0) {
                $respuesta .= $detalle -> detalle_prod_cant . 'x ' . $detalle -> prod_nombre;
            } else {
                $respuesta .= ', ' . $detalle -> detalle_prod_cant . 'x ' . $detalle -> prod_nombre;
            }
            $i ++;
        }

        return $respuesta;
    }

    function getComprasEstadisticos($f_desde = false,$f_hasta = false) {
        $respuesta = array();

        if(!$f_desde) {
            $this -> compra_fh > date('Y-01-01 00:00:00');
        } else {
            $this -> whereAdd('compra_fh BETWEEN "'.$f_desde.'" AND "'.$f_hasta.'"');
        }

        $usr = DB_DataObject::factory('usuario'); 
        $this -> joinAdd($usr);

        $this -> find();

        while ($this -> fetch()) {
            $respuesta['compra_'.$this -> compra_id]['fecha'] = $this -> compra_fh;
            $respuesta['compra_'.$this -> compra_id]['concepto'] = 'Compra #'.$this -> compra_id.' ('.$this -> getDetalleString().')';
            $respuesta['compra_'.$this -> compra_id]['monto'] = 0 - $this -> compra_monto_total;
            $respuesta['compra_'.$this -> compra_id]['observacion'] = $this -> compra_observacion;
            $respuesta['compra_'.$this -> compra_id]['usuario'] = $this -> usua_nombre;
            $respuesta['compra_'.$this -> compra_id]['color'] = '#f7e3e0';
        }

        return $respuesta;
    }

    function getComprasPorMes() {
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
        
        for ($i=9; $i > 0; $i--) { 
            $k = $i - 1;
            $fecha_mesactual -> modify("-$k month");
            $obj = clone $this;
            $mes = $fecha_mesactual -> format('m');
            $anio = $fecha_mesactual -> format('Y');
            $obj -> whereAdd('MONTH(compra_fh) = '.$mes.' AND YEAR(compra_fh) = '.$anio);
            $obj -> find();
            $respuesta['mes'][] = strftime('%b',strtotime($fecha_mesactual -> format('Y-m-d')));
            while($obj -> fetch()){
                $respuesta['cantidad'][$i] += $obj -> compra_monto_total;  
            }
            $fecha_mesactual -> modify("+$k month");
        }

        return $respuesta;
    }

}