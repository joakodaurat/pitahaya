<?php
/**
 * Table Definition for producto_stock
 */
require_once 'DB/DataObject.php';

class DataObjects_Producto_stock extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'producto_stock';      // table name
    public $ps_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $ps_compra_id;                    // int(11)  group_by
    public $ps_producto_id;                  // int(11)  not_null group_by
    public $ps_costo_u;                      // float(11)  not_null group_by
    public $ps_cantidad;                     // int(11)  not_null group_by
    public $ps_talle_id;                     // int(11)  group_by
    public $ps_color_id;                     // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Producto_stock',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

}
