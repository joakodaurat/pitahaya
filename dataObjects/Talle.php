<?php
/**
 * Table Definition for talle
 */
require_once 'DB/DataObject.php';

class DataObjects_Talle extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'talle';               // table name
    public $talle_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $talle_nombre;                    // varchar(256)  not_null
    public $talle_baja;                      // int(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Talle',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getTallePorNombre($nombre){
        $this -> talle_nombre = $nombre;
        $this -> find(true);

        return $this -> talle_id;
    }

    function getTallesJson(){
        $this -> talle_baja = 0;
        $this -> find();

        $resp = array();

        while ($this -> fetch()){
            $resp[$this -> talle_id] = $this -> talle_nombre;
        }

        return json_encode( $resp );
    }

      function getTalles($id=false) {

        $do_talles = DB_DataObject::factory('talle');
        if($id){
            $do_talles -> talle_id = $id;
            $do_talles -> find(true);
        } else {
            $do_talles -> find();
        }

        return $do_talles;
    }
    function alta_talle ($objeto) {
        $do_talles = DB_DataObject::factory('talle');
        $do_talles -> talle_nombre = $objeto['input_talle'];
        $do_talles -> talle_baja = 0;
        return $do_talles -> insert();
    }

    function edit_talle ($objeto) {
        $do_talles = DB_DataObject::factory('talle');
        $do_talles -> talle_id = $objeto['edit_talle_id'];
        $do_talles -> find(true);
        
        $do_talles -> talle_nombre = $objeto['input_talle_edit'];
        $do_talles -> talle_baja = $objeto ['tipoEstado'];
        $respuesta = $do_talles -> update();

        return $respuesta;

    }
}
