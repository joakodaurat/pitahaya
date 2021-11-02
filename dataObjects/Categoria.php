<?php
/**
 * Table Definition for categoria
 */
require_once 'DB/DataObject.php';

class DataObjects_Categoria extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'categoria';           // table name
    public $cat_id;                          // int(11)  not_null primary_key auto_increment group_by
    public $cat_nombre;                      // varchar(128)  not_null
    public $cat_baja;                        // tinyint(1)  not_null group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Categoria',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function getCategorias($id=false) {

        $do_categorias = DB_DataObject::factory('categoria');
        if($id){
            $do_categorias -> cat_id = $id;
            $do_categorias -> find(true);
        } else {
            $do_categorias -> find();
        }

        return $do_categorias;
    }
    function alta_categoria ($objeto) {
        $do_categorias = DB_DataObject::factory('categoria');
        $do_categorias -> cat_nombre = $objeto['input_categoria'];
        $do_categorias -> cat_baja = 0;
        return $do_categorias -> insert();
    }

    function edit_categoria ($objeto) {
        $do_categorias = DB_DataObject::factory('categoria');
        $do_categorias -> cat_id = $objeto['edit_categoria_id'];
        $do_categorias -> find(true);
        
        $do_categorias -> cat_nombre = $objeto['input_categoria_edit'];
        $do_categorias -> cat_baja = $objeto ['tipoEstado'];
        $respuesta = $do_categorias -> update();

        return $respuesta;

    }

    function prodSinStock() {
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_cat_id = $this -> cat_id;
        $do_productos -> find();

        $respuesta = 0;

        while($do_productos -> fetch()){
            $respuesta += $do_productos -> getStock();
        }

        return $respuesta;
    }




}

