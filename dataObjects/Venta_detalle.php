<?php
/**
 * Table Definition for venta_detalle
 */
require_once 'DB/DataObject.php';

class DataObjects_Venta_detalle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'venta_detalle';       // table name
    public $detalle_id;                      // int(11)  not_null primary_key auto_increment group_by
    public $detalle_venta_id;                // int(11)  not_null group_by
    public $detalle_prod_id;                 // int(11)  not_null group_by
    public $detalle_prod_cant;               // int(11)  not_null group_by
    public $detalle_prod_precio_u;           // float(11)  not_null group_by
    public $detalle_prod_total_venta;        // float(11)  group_by
    public $detalle_prod_total_sindescuento;    // float(11)  group_by
    public $detalle_prod_talle_id;           // int(11)  group_by
    public $detalle_prod_color_id;           // int(11)  group_by
    public $detalle_cant_devueltos;          // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Venta_detalle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
