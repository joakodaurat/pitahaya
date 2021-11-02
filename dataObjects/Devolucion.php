<?php
/**
 * Table Definition for devolucion
 */
require_once 'DB/DataObject.php';

class DataObjects_Devolucion extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'devolucion';          // table name
    public $d_id;                            // int(11)  not_null primary_key auto_increment group_by
    public $d_venta_id;                      // int(11)  not_null group_by
    public $d_usr_id;                        // int(11)  not_null group_by
    public $d_fh;                            // datetime(19)  not_null
    public $d_diferencia;                    // float(11)  not_null group_by
    public $d_pago_id;                       // int(11)  group_by
    public $d_nota_id;                       // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Devolucion',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevaDevolucion($o) {
        // 1. Volver a sumar el stock de los productos a devolver. 
        // 2. Marcarlos como devueltos en la venta_detalle.
        // 3. Cargar un pago o NC dependiendo de la diferencia.
        // 4. Cargar la devolución con sus datos.
        // 5. Cargar el detalle de la devolución.
        // 6. Devolver ID de venta, tipo e ID de cobro/nota.

        // 3.
            if($o['diferencia_total'] < 0) {
                // Cargo un pago
                $o['input_monto_mercado']=  $o['input_monto_contado'];
                $o['input_monto_credito']=  $o['input_monto_contado'];
                $o['input_monto_debito']=  $o['input_monto_contado'];
                $co = DB_DataObject::factory('cobro_cliente');
                $id_co = $co -> nuevoCobro($o);
            }

            if($o['diferencia_total'] > 0) {
                // Cargo una NC
                $nc = DB_DataObject::factory('notas');
                $id_nc = $nc -> nuevaNota($o);
            }

        // 4.
            $this -> d_venta_id = $o['venta_id'];
            $this -> d_usr_id = $_SESSION['usuario']['id'];
            $this -> d_fh = date('Y-m-d H:i:s');
            $this -> d_diferencia = $o['diferencia_total'];
            if($o['diferencia_total'] < 0) {
                $this -> d_pago_id = $id_co;
            }
            if($o['diferencia_total'] > 0) {
                $this -> d_nota_id = $id_nc;
            }

            $id_devolucion = $this -> insert();
            
        // 1 y 2.
            foreach ($o['devuelto'] as $vd => $det) {
                // En $vd recibo venta_detalle_id. Busco el venta_detalle_stock -> producto_stock y sumo la cantidad.
                    $vds = DB_DataObject::factory('venta_detalle_stock');
                    $vds -> vds_venta_detalle_id = $vd;
                    $vds -> find();

                    $aux_cant = $det['cant']; // 150

                    while ($vds -> fetch()) {
                        $cant_disponible = $vds -> vds_prod_cant - $vds -> vds_cant_devuelta; //100
                        if($cant_disponible > 0){
                            if($aux_cant >= $cant_disponible){
                                // Cargo el detalle de devolucion.
                                $aux_cant -= $cant_disponible; // 55
                                $dd = DB_DataObject::factory('devolucion_detalle');;
                                $id_det = $dd -> nuevoDetalleDevuelto($id_devolucion,$vds -> vds_prodstock_id,$vds -> vds_venta_detalle_id,$vds -> vds_prod_cant);
                                $vds -> vds_cant_devuelta = $vds -> vds_cant_devuelta + $cant_disponible;
                                $vds -> update();
                            } else {
                                // Cargo el detalle de devolucion.
                                $dd = DB_DataObject::factory('devolucion_detalle');;
                                $id_det = $dd -> nuevoDetalleDevuelto($id_devolucion,$vds -> vds_prodstock_id,$vds -> vds_venta_detalle_id,$aux_cant);
                                $vds -> vds_cant_devuelta = $vds -> vds_cant_devuelta + $aux_cant;
                                $vds -> update();
                                $aux_cant = 0;
                            }
                        }
                    }

                // En venta_detalle sumo las cantidades devueltas.
                    $ve_d = DB_DataObject::factory('venta_detalle');
                    $ve_d -> detalle_id = $vd;
                    $ve_d -> find(true);

                    $ve_d -> detalle_cant_devueltos = $ve_d -> detalle_cant_devueltos + $det['cant'];
                    $ve_d -> update();
            }
        

        // 5.
            foreach ($o['detalle'] as $prod => $det) {
                $tipos = explode(',',$det['tipo']);

                $talle = DB_DataObject::factory('talle');
                $talle_id = $talle -> getTallePorNombre($tipos[1]);

                $color = DB_DataObject::factory('color');
                $color_id = $color -> getColorPorNombre($tipos[0]);

                $producto = DB_DataObject::factory('producto'); 
                $prod_s = $producto -> getProductos($prod);
                $arreglo = $prod_s -> restarStock($talle_id,$color_id,$det['cant']);

                $dd = DB_DataObject::factory('devolucion_detalle');
                $id_det = $dd -> nuevoDetalleCambio($id_devolucion,$arreglo,$det);
            }

        // 6.
            return $id_devolucion;

    }

    function getCambiadosPorVenta($id){
        $this -> d_venta_id = $id;
        $this -> find();

        while ($this -> fetch()) {
            $devolucion_detalle = DB_DataObject::factory('devolucion_detalle');
            $devolucion_detalle -> whereAdd('detalle_devolucion_id = '.$this -> d_id.' AND detalle_vd_id IS NULL');
            $devolucion_detalle -> find();

            while ($devolucion_detalle -> fetch()) {
                $prod = DB_DataObject::factory('producto');
                $datos_prod = $prod -> getDatosPorPS($devolucion_detalle -> detalle_ps_id);
                $datos_prod -> cant_devueltos = $devolucion_detalle -> detalle_prod_cant;
                $respuesta[] = $datos_prod;
            }
        }

        return $respuesta;

    }

    function getNotasYPagosPorVenta($id){
        // $this -> d_venta_id = $id;
        $notas = DB_DataObject::factory('notas');
        $cobros = DB_DataObject::factory('cobro_cliente');
        $formas = DB_DataObject::factory('forma_pago');

        $cobros -> joinAdd($formas, 'LEFT');

        $this -> joinAdd($notas, 'LEFT');
        $this -> joinAdd($cobros, 'LEFT');

        $this -> whereAdd('d_venta_id = '.$id);

        $this -> find();
        while ($this -> fetch()) {
            if($this -> d_pago_id){
                $respuesta[$this -> d_id]['cobro_id'] = $this -> cobro_id;
                $respuesta[$this -> d_id]['concepto'] = 'Cobro N. CO00'.$this -> cobro_id;
                $respuesta[$this -> d_id]['monto'] = $this -> cobro_monto_total;
                $respuesta[$this -> d_id]['info'] = $this -> fp_desc;
            }

            if($this -> d_nota_id){
                $respuesta[$this -> d_id]['nota_credito_id'] = $this -> nota_id;
                $respuesta[$this -> d_id]['concepto'] = 'Nota de Crédito N. NC00'.$this -> nota_id;
                $respuesta[$this -> d_id]['monto'] = $this -> nota_monto;
                $respuesta[$this -> d_id]['info'] = '';
            }
        }

        return $respuesta;

    }
}
