<?php
/**
 * Table Definition for marca_categoria
 */
require_once 'DB/DataObject.php';

class DataObjects_Marca_categoria extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'marca_categoria';     // table name
    public $marcacat_id;                     // int(11)  not_null primary_key auto_increment group_by
    public $marcacat_marca_id;               // int(11)  not_null multiple_key group_by
    public $marcacat_categoria_id;           // int(11)  not_null multiple_key group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Marca_categoria',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
