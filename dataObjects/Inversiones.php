<?php
/**
 * Table Definition for inversiones
 */
require_once 'DB/DataObject.php';

class DataObjects_Inversiones extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'inversiones';         // table name
    public $inversion_id;                    // int(11)  not_null primary_key auto_increment group_by
    public $inversion_concepto;              // varchar(256)  not_null
    public $inversion_fh;                    // datetime(19)  
    public $inversion_monto;                 // int(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Inversiones',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
            function getInversiones($desde = false,$hasta = false) {

        if($desde && $hasta){
            $this -> whereAdd('inversion_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('inversion_id DESC');
        $this -> find();

        return $this;
    }
}
