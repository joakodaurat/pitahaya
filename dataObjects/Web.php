<?php
/**
 * Table Definition for web
 */
require_once 'DB/DataObject.php';

class DataObjects_Web extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'web';                 // table name
    public $icono;                           // varchar(255)  not_null
    public $titulo;                          // varchar(255)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Web',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
