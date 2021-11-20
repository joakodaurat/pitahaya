<?php
/**
 * Table Definition for tipo_gasto
 */
require_once 'DB/DataObject.php';

class DataObjects_Tipo_gasto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'tipo_gasto';          // table name
    public $tg_id;                           // int(11)  not_null primary_key auto_increment group_by
    public $tg_nombre;                       // varchar(256)  
    public $tg_baja;                         // int(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Tipo_gasto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
