<?php
/**
 * Table Definition for color
 */
require_once 'DB/DataObject.php';

class DataObjects_Color extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'color';               // table name
    public $color_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $color_nombre;                    // varchar(256)  not_null
    public $color_codigo;                    // varchar(256)  
    public $color_baja;                      // int(11)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Color',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getColorPorNombre($nombre){
        $this -> color_nombre = $nombre;
        $this -> find(true);

        return $this -> color_id;
    }

    function getColoresJson(){
        $this -> color_baja = 0;
        $this -> find();

        $resp = array();

        while ($this -> fetch()){
            $resp[$this -> color_id] = $this -> color_nombre;
        }

        return json_encode( $resp );
    }

    function getColores($id=false) {

        $do_colores = DB_DataObject::factory('color');
        if($id){
            $do_colores -> color_id = $id;
            $do_colores -> find(true);
        } else {
            $do_colores -> find();
        }

        return $do_colores;
    }
    function alta_color ($objeto) {
        $do_colores = DB_DataObject::factory('color');
        $do_colores -> color_nombre = $objeto['input_color'];
        $do_colores -> color_codigo = $objeto['input_color_codigo'];
        $do_colores -> color_baja = 0;
        return $do_colores -> insert();
    }

    function edit_color ($objeto) {
        $do_colores = DB_DataObject::factory('color');
        $do_colores -> color_id = $objeto['edit_color_id'];
        $do_colores -> find(true);
        
        $do_colores -> color_nombre = $objeto['input_color_edit'];
        $do_colores -> color_codigo = $objeto['input_codigo_edit'];
        $do_colores -> color_baja = $objeto ['tipoEstado'];
        $respuesta = $do_colores -> update();

        return $respuesta;

    }
}
