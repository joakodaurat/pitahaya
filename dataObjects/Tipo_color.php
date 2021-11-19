<?php
/**
 * Table Definition for tipo_color
 */
require_once 'DB/DataObject.php';

class DataObjects_Tipo_color extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tipo_color';          // table name
    public $color_id;                        // int(11)  not_null primary_key group_by
    public $color_nombre;                    // varchar(256)  
    public $color_baja;                      // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Tipo_color',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
