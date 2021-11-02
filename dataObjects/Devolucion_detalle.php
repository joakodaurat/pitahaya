<?php
/**
 * Table Definition for devolucion_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Devolucion_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'devolucion_detalle';    // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_devolucion_id;           // int(11)  not_null group_by
    public $detalle_ps_id;                   // int(11)  group_by
    public $detalle_vd_id;                   // int(11)  group_by
    public $detalle_prod_cant;               // int(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Devolucion_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoDetalleDevuelto($dev,$ps,$vd,$cant){
        $this -> detalle_devolucion_id = $dev;
        $this -> detalle_ps_id = $ps;
        $this -> detalle_vd_id = $vd;
        $this -> detalle_prod_cant = $cant;

        $ps_sum = DB_DataObject::factory('producto_stock');
        $ps_sum -> ps_id = $ps;
        $ps_sum -> find(true);

        $ps_sum -> ps_cantidad = $ps_sum -> ps_cantidad + $cant;
        $ps_sum -> update();

        $id = $this -> insert();
    }

    function nuevoDetalleCambio($dev,$arr,$dd){
        foreach ($arr['productos'] as $ps => $cant) {
            $this -> detalle_devolucion_id = $dev;
            $this -> detalle_ps_id = $ps;
            $this -> detalle_prod_cant = $cant;
            
            $id = $this -> insert();
        }

        return true;

    }
    
}
