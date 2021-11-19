<?php
/**
 * Table Definition for tipo_talle
 */
require_once 'DB/DataObject.php';

class DataObjects_Tipo_talle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tipo_talle';          // table name
    public $talle_id;                        // int(11)  not_null primary_key group_by
    public $talle_nombre;                    // varchar(10)  not_null
    public $talle_baja;                      // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Tipo_talle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
