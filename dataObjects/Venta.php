<?php
/**
 * Table Definition for venta
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta';               // table name
    public $venta_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $venta_fh;                        // datetime(19)  not_null
    public $venta_cliente_id;                // int(11)  not_null group_by
    public $venta_forma_pago_id;             // int(11)  not_null group_by
    public $venta_estado_id;                 // int(11)  not_null group_by
    public $venta_monto_total;               // float(11)  not_null group_by
    public $venta_usuario_id;                // int(11)  not_null group_by
    public $venta_monto_sindescuento;        // float(11)  not_null group_by
    public $venta_observacion;               // blob(65535)  blob
    public $venta_baja_fh;                   // datetime(19)  
    public $venta_cobro_id;                  // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE


function nuevaVentaPorCaja($objeto,$id_cobro) {
        // 1. Crea una nueva venta
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_fh = date("Y-m-d H:i:s");
        $venta -> venta_vendedor_id = $objeto['input_id_vendedor'];
        $venta -> venta_usuario_id = $_SESSION['usuario']['id'];
        $venta -> venta_observacion = $objeto['input_observacion_venta'];
        $venta -> venta_estado_id = 2; // Saldada
        $venta -> venta_forma_pago_id = $_POST['combo_fpago'];
        $venta -> venta_descuento_porc = $objeto['porc_descuento'];
        $venta -> venta_descuento_monto = $objeto['cuantodescuento'];
        $venta -> venta_monto_total = $objeto['saldo_final_total'];
        $venta -> venta_monto_sindescuento = $objeto['saldo_final_total_sindescuento'];
        $venta -> venta_cobro_id = $id_cobro;
        $id_venta = $venta -> insert();
        // 2. Resta stock
        // 3. Asigna productos al detalle de la venta
        foreach ($objeto['detalle'] as $p) {
            $tipos = explode(',',$p['tipo']);

            $talle = DB_DataObject::factory('talle');
            $talle_id = $talle -> getTallePorNombre($tipos[0]);

            $producto = DB_DataObject::factory('producto'); 
            $prod = $producto -> getProductos($p['id']);

            $arreglo = $prod -> restarStock($talle_id,$p['cant']);

            if($arreglo['productos']) {
                $detalle = DB_DataObject::factory('venta_detalle');
                $detalle -> detalle_venta_id = $id_venta;
                $detalle -> detalle_prod_id = $p['id'];
                $detalle -> detalle_prod_cant = $p['cant'];
                $detalle -> detalle_prod_precio_u = $p['precio'];
                $detalle -> detalle_prod_talle_id = $talle_id;
                $detalle -> detalle_prod_total_venta = $p['total'];
                $detalle -> detalle_prod_total_sindescuento = $p['total_sindescuento'];

                $det_id = $detalle -> insert();

                // Guarda el producto_stock -> ps_id en venta_detalle_stock
                foreach ($arreglo['productos'] as $k => $v) {
                    $venta_detalle_stock = DB_DataObject::factory('venta_detalle_stock');
                    $venta_detalle_stock -> vds_venta_detalle_id = $det_id; 
                    $venta_detalle_stock -> vds_prodstock_id = $k;
                    $venta_detalle_stock -> vds_prod_cant = $v;
                    $venta_detalle_stock -> insert();
                }
            }

        }

        //Agrego la venta a la CC
        if($objeto['input_id_cliente']){
            $cc_cliente = DB_DataObject::factory('cliente_cuenta_corriente');
            $cc_cliente -> cargarVenta($objeto,$id_venta,$objeto['saldo_final_total']);
        }

        $respuesta = array();
        $respuesta['id'] = $id_venta;

        $cliente = DB_DataObject::factory('cliente');
        $respuesta['nombre'] = $cliente -> getClientes($objeto['input_id_cliente']) -> cliente_nombre;
        $respuesta['monto'] = $objeto['saldo_final_total'];

        return $respuesta;
        }

    function getVenta($id) {
        $do_ventas = DB_DataObject::factory('venta');

        if($id){
            $do_ventas -> venta_id = $id;
        }

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario);
        $do_ventas -> joinAdd($do_cliente);
        $do_ventas -> joinAdd($do_venta_estado);
        $do_ventas -> joinAdd($do_venta_forma_pago);

        if($id){
            $do_ventas -> find(true);
        } else {
            $do_ventas -> whereAdd('venta_estado_id != 3');
            $do_ventas -> whereAdd('venta_baja_fh IS NULL AND venta_archivada_fh IS NULL') ;
            $do_ventas -> orderBy('venta_id DESC');
            $do_ventas -> find();
        }
        return $do_ventas;
    }

    function getVentasPendientes($desde = false,$hasta = false) {
        //DB_DataObject::debugLevel(1);
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }
        $do_ventas -> whereAdd('venta_estado_id = 1');
        $do_ventas -> whereAdd('venta_baja_fh IS NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }

     function getVentasSaldadas($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_estado_id != 1');
        $do_ventas -> whereAdd('venta_baja_fh IS NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }
    function getVentas($desde = false,$hasta = false) {
        $do_ventas = DB_DataObject::factory('venta');


        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_venta_estado = DB_DataObject::factory('venta_estado');
        $do_venta_forma_pago = DB_DataObject::factory('venta_forma_pago');

        $do_ventas -> joinAdd($do_usuario,"LEFT");
        $do_ventas -> joinAdd($do_cliente,"LEFT");
        $do_ventas -> joinAdd($do_venta_estado,"LEFT");
        $do_ventas -> joinAdd($do_venta_forma_pago,"LEFT");

        if($desde && $hasta){
            $do_ventas -> whereAdd('venta_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $do_ventas -> whereAdd('venta_baja_fh IS NULL') ;
        $do_ventas -> orderBy('venta_id DESC');
        $do_ventas -> find();

        return $do_ventas;
    }

    function getDetalleString() {

        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id = $this -> venta_id;
        $producto = DB_DataObject::factory('producto');
        $categoria = DB_DataObject::factory('categoria');
        $marca = DB_DataObject::factory('marca');

        $producto -> joinAdd($categoria);
        $producto -> joinAdd($marca);
        $detalle -> joinAdd($producto);
        $detalle -> find();

        $respuesta = '';
        $arreglo = array();

        while ($detalle -> fetch()) {
            $arreglo[$detalle -> detalle_prod_id]['nombre'] = $detalle -> cat_nombre .' '. $detalle -> marca_nombre;
            $arreglo[$detalle -> detalle_prod_id]['cant'] += $detalle -> detalle_prod_cant;
        }

        $i = 0;
        foreach ($arreglo as $prod) {
            if($i) {
                $respuesta .= ', ';
            }
            $respuesta .= ''.$prod['cant'].' '.$prod['nombre'];
            $i ++;
        }

        return $respuesta;
    }
    function getDetalleArticuloPrecio() {

        $detalle = DB_DataObject::factory('venta_detalle');
        $detalle -> detalle_venta_id = $this -> venta_id;
        $producto = DB_DataObject::factory('producto');

        $detalle -> joinAdd($producto);
        $detalle -> find();

        $respuesta = '';
        $arreglo = array();

        while ($detalle -> fetch()) {
            $arreglo[$detalle -> detalle_prod_id]['nombre'] = $detalle -> prod_nombre;
            $arreglo[$detalle -> detalle_prod_id]['cant'] += $detalle -> detalle_prod_cant;
            $arreglo[$detalle -> detalle_prod_id]['precio'] = $detalle -> prod_precio;
        }

        $i = 0;
        foreach ($arreglo as $prod) {
            if($i) {
                $respuesta .= '<br> ';
            }
            $respuesta .= ''.$prod['cant'].'x '.$prod['nombre'].' - $'.$prod['precio'];
            $i ++;
        }

        return $respuesta;
    }


    function eliminarVenta($objeto) {
        $venta = DB_DataObject::factory('venta');
        $venta -> venta_id = $objeto['edit_venta_id'];
        $venta -> find(true);

        $venta -> venta_baja_fh = date("Y-m-d H:i:s");
        $venta -> venta_estado_id = 3;

        // Traigo todo el detalle 
        $det_old = DB_DataObject::factory('venta_detalle'); 
        $det_old -> detalle_venta_id = $venta -> venta_id;
        $det_old -> find();

        while ($det_old -> fetch()) {
            $vds = DB_DataObject::factory('venta_detalle_stock');
            $vds -> vds_venta_detalle_id = $det_old -> detalle_id;
            $vds -> find();

            while ($vds -> fetch()) {
                $ps = DB_DataObject::factory('producto_stock');
                $ps -> ps_id = $vds -> vds_prodstock_id;
                $ps -> find(true);
                $ps -> ps_cantidad = $ps -> ps_cantidad +  $vds -> vds_prod_cant;

                $ps -> update();
            }
        }

        $cc_cliente = DB_DataObject::factory('cliente_cuenta_corriente');
        $cc_cliente -> anularVenta($venta -> venta_cliente_id, $venta -> venta_id, $venta -> venta_monto_total); 
        $venta -> update();
        // elimino el cobro asociado a la venta
        $db_cobro = DB_DataObject::factory('cobro_cliente');
        $db_cobro -> eliminarCobro($venta -> venta_cobro_id);
        
        return $venta -> venta_id;
        
    }

    function getTotalBultos($fh) {

        $total_bultos = 0;

        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $this -> joinAdd($do_venta_detalle);

        $this -> whereAdd('venta_estado_id != 3 and venta_fh LIKE "%'.$fh.'%"');
        $this -> find();

        while($this -> fetch()){
            $total_bultos += $this -> detalle_prod_cant;
        }

        return $total_bultos;
    }
// Funcion para caja actual
    function getBultosDiariosCaja($fi,$ff=false) {
        if(!$ff){
            $ff = date('Y-m-d H:i:s');
        }
        $total_ventas = 0;
        $total_bultos = 0;
        $total_devueltos = 0;

        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $this -> joinAdd($do_venta_detalle);

        $this -> whereAdd('venta_estado_id != 3 and venta_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        
        $this -> find();

        while($this -> fetch()){
            $total_bultos += $this -> detalle_prod_cant;
        }

        $devolucion = DB_DataObject::factory('devolucion');
        $devolucion -> whereAdd('d_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        $devolucion -> find();

        $tv = DB_DataObject::factory('venta');
        $total = $tv -> getTotalVentasCaja($fi,$ff);

        $respuesta['Ventas'] = $total;
        $respuesta['Bultos'] = $total_bultos;
        $respuesta['Devueltos'] = $devolucion -> N;

        return $respuesta;

    }
// Funcion para historial de caja
    function getBultosDiariosHistorialCaja($fh) {
     
        $total_ventas = 0;
        $total_bultos = 0;
        $total_devueltos = 0;

        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $this -> joinAdd($do_venta_detalle);

        $this -> whereAdd('venta_estado_id != 3 and venta_fh LIKE "%'.$fh.'%"');
      
        $this -> find();

        while($this -> fetch()){
            $total_bultos += $this -> detalle_prod_cant;
        }

        $devolucion = DB_DataObject::factory('devolucion');
        $devolucion -> whereAdd('d_fh LIKE "%'.$fh.'%"');
        $devolucion -> find();

        $tv = DB_DataObject::factory('venta');
        $total = $tv -> getTotalVentas($fh);

        $respuesta['Ventas'] = $total;
        $respuesta['Bultos'] = $total_bultos;
        $respuesta['Devueltos'] = $devolucion -> N;

        return $respuesta;

    }




    function getTotalVentasCaja($fi,$ff) {
        $this -> whereAdd('venta_estado_id != 3 and venta_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        $this -> find();

        return $this -> N;
    }

    function getTotalVentas($fh) {
        $this -> whereAdd('venta_estado_id != 3 and venta_fh LIKE "%'.$fh.'%"');
        $this -> find();

        return $this -> N;
    }

    function getDetalleFormaPago(){
        $do_forma_pago = DB_DataObject::factory('forma_pago');
        $do_forma_pago -> fp_id = $this -> venta_forma_pago_id;
        $do_forma_pago -> find(true);
        return $do_forma_pago -> fp_desc;
    }
// devuelve la cantidad de productos vendidos x dia
    function getTotalVentasxProducto($fi,$ff) {
        $this -> whereAdd('venta_estado_id != 3 and venta_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_producto = DB_DataObject::factory('producto');
        $do_venta_detalle -> joinAdd($do_producto);
        $do_venta_detalle -> find();

        $this -> joinAdd($do_venta_detalle);
        $this -> joinAdd($do_producto);
        $this -> find();
        $detalle = $this;

        $respuesta = '';
        $arreglo = array();
        while ($detalle -> fetch()) {

            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u ]['nombre'] = $detalle -> prod_nombre;
            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u]['precio_unitario'] = $detalle -> detalle_prod_precio_u;
            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u]['cant'] += $detalle -> detalle_prod_cant;
            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u]['precio'] += $detalle -> detalle_prod_precio_u;
        }
        sort($arreglo);
        $respuesta .= '<table id="tablaventasxproducto" class="table table-hover table-bordered results z-depth-2 tabla-excel" style="margin-top: 0px;display:none">
        <thead>
          <tr class="header-tabla-caja">
            <th>Articulo</th>
            <th>Total</th>        
             </tr>
        </thead>';

        foreach ($arreglo as $prod) {

            $respuesta .= '<tr>';
          
            $respuesta .= '<td>';
       
            $respuesta .= ''.$prod['cant'].'x$'.$prod['precio_unitario'].' - '.$prod['nombre'].'';
            $respuesta .= '</td> ';

            $respuesta .= '<td>';
            $respuesta .= ' $'.$prod['precio'];
            $respuesta .= '</td> ';

            $respuesta .= '</tr> ';
            $total += $prod['precio'];
        }
        $respuesta .= '<tr>';
        $respuesta .= '<th style="text-align: right;">Total</th>';
        $respuesta .= '<th>$'.$total.'</th>';
        $respuesta .= '</tr>';
        $respuesta .='</tbody></table>';
        return $respuesta;
    }

    // devuelve la cantidad de productos vendidos x dia (sin tabla para mail)
    function getTotalVentasxProductoSinTabla($fi,$ff) {
        $this -> whereAdd('venta_fh BETWEEN "'.$fi.'" AND  "'.$ff.'"');
        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_producto = DB_DataObject::factory('producto');
        $do_venta_detalle -> joinAdd($do_producto);
        $do_venta_detalle -> find();

        $this -> joinAdd($do_venta_detalle);
        $this -> joinAdd($do_producto);
        $this -> find();
        $detalle = $this;

        $respuesta = '';
        $arreglo = array();
        while ($detalle -> fetch()) {

            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u ]['nombre'] = $detalle -> prod_nombre;
            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u]['precio_unitario'] = $detalle -> detalle_prod_precio_u;
            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u]['cant'] += $detalle -> detalle_prod_cant;
            $arreglo[$detalle -> detalle_prod_id . $detalle -> detalle_prod_precio_u]['precio'] += $detalle -> detalle_prod_precio_u;
        }
        sort($arreglo);

        foreach ($arreglo as $prod) {
       
            $respuesta .= '<p>'.$prod['cant'].'x$'.$prod['precio_unitario'].' - '.$prod['nombre'].' -Total:$'.$prod['precio'].'</p>';
            $total += $prod['precio'];
        }
        $respuesta .= '<p>Total: $'.$total.'</p>';
        return $respuesta;
    }

}