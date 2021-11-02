<?php
/**
 * Table Definition for marca
 */
require_once 'DB/DataObject.php';

class DataObjects_Marca extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'marca';               // table name
    public $marca_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $marca_nombre;                    // varchar(128)  not_null
    public $marca_baja;                      // tinyint(1)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Marca',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

      function getMarcas($id=false) {

        $do_marcas = DB_DataObject::factory('marca');
        if($id){
            $do_marcas -> marca_id = $id;
            $do_marcas -> find(true);
        } else {
            $do_marcas -> find();
        }

        return $do_marcas;
    }
    function alta_marca ($objeto) {
        $do_marcas = DB_DataObject::factory('marca');
        $do_marcas -> marca_nombre = $objeto['input_marca'];
        $do_marcas -> marca_baja = 0;
        return $do_marcas -> insert();
    }

    function edit_marca ($objeto) {
        $do_marcas = DB_DataObject::factory('marca');
        $do_marcas -> marca_id = $objeto['edit_marca_id'];
        $do_marcas -> find(true);
        
        $do_marcas -> marca_nombre = $objeto['input_marca_edit'];
        $do_marcas -> marca_baja = $objeto ['tipoEstado'];
        $respuesta = $do_marcas -> update();

        return $respuesta;

    }
}
