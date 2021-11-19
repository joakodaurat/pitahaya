<?php
/**
 * Table Definition for caja
 */
require_once 'DB/DataObject.php';

class DataObjects_Caja extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'caja';                // table name
    public $caja_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $caja_fh_inicio;                  // datetime(19)  not_null
    public $caja_fh_cierre;                  // datetime(19)  
    public $caja_monto_inicio;               // float(9)  not_null group_by
    public $caja_monto_cierre;               // float(9)  group_by
    public $caja_usua_inicio;                // int(11)  not_null group_by
    public $caja_usua_cierre;                // int(11)  group_by
    public $caja_estado;                     // int(11)  not_null group_by
    public $caja_pagos_ft;                   // float(11)  group_by
    public $caja_monto_dolar_inicio;         // float(9)  not_null group_by
    public $caja_monto_dolar_cierre;         // float(9)  not_null group_by
    public $caja_valor_dolar;                // float(9)  group_by
    public $caja_pagos_dolar;                // float(9)  group_by
    public $caja_pagos_efectivo;             // float(9)  group_by
    public $caja_pagos_tarjeta;              // float(9)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Caja',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    // Retorna true si la caja se abrio hoy. 
    // Sirve para validar que no se puedan cargar ventas, pagos, cobros o gastos sin la caja abierta.
    function cajaAbiertaHoy(){
        $this -> orderBy('caja_id DESC');
        $this -> find(true);

        if($this -> caja_estado == 1) { // Abierta
            $resp = true;
        } elseif($this -> caja_estado == 2) { // Cerrada
            $resp = false;
        }

        return $resp;
    }

    function getUltimaCaja(){

        $this -> orderBy('caja_id DESC');
        $this -> find(true);

        return $this;
    }

    function getEstado(){
        $caja_estado = DB_DataObject::factory('caja_estado');
        $caja_estado -> ce_id = $this -> caja_estado;

        $caja_estado -> find(true);

        return $caja_estado -> ce_nombre;
    }

    function abrirCaja($objeto){
        $this -> caja_fh_inicio = date('Y-m-d H:i:s');
        $this -> caja_usua_inicio = $_SESSION['usuario']['id'];
        $this -> caja_monto_inicio = $objeto['monto_inicial'];
        $this -> caja_monto_dolar_inicio = $objeto['monto_inicial_dolar'];
        $this -> caja_valor_dolar = $objeto['valor_dolar'];
        $this -> caja_estado = 1;

        $id = $this -> insert();

        return $id;
    }

    function CerrarCaja($objeto){
        $this -> caja_id = $objeto['caja_id'];
        $this -> find(true);

        $this -> caja_fh_cierre = date('Y-m-d H:i:s');
        $this -> caja_usua_cierre = $_SESSION['usuario']['id'];
        $this -> caja_estado = 2;

        $this -> update();

        return $this -> caja_id;
    }

    function getDatos($fh){
        $cobro_cliente = DB_DataObject::factory('cobro_cliente');
        $respuesta = $cobro_cliente -> getMontoACobrar($fh);

        return $respuesta;
    }
// este get devuelve el ingreso en efectivo dsps de tal hora.
    function getIngresosHora($fh){

        $cobro_cliente = DB_DataObject::factory('cobro_cliente');
        $respuesta = $cobro_cliente -> getMontoACobrarHora($fh);

        return $respuesta;
    }



    function getCajasDelDia($fh){
        $this -> whereAdd('caja_fh_inicio LIKE "%'.$fh.'%"');
        $this -> find();

        return $this;
    }

    function getAperturasDiarias($fh){
        $do_caja = DB_DataObject::factory('caja'); 
        $do_caja -> whereAdd('caja_fh_inicio LIKE "%'.$fh.'%"');
        $do_caja -> find();
       
        return $do_caja;
    }


     function getIngresosCaja(){
        $cobro_cliente = DB_DataObject::factory('cobro_cliente');
        return $cobro_cliente -> getIngresosCaja($this -> caja_fh_inicio, $this -> caja_fh_cierre);

    }

    function getEgresosCaja(){
        $pago_proveedores = DB_DataObject::factory('pago_proveedor');
        $pagos =  $pago_proveedores -> getIngresosCaja($this -> caja_fh_inicio, $this -> caja_fh_cierre);

        $do_gastos = DB_DataObject::factory('gasto');
        $gastos =  $do_gastos -> getTotalGastoCaja($this -> caja_fh_inicio, $this -> caja_fh_cierre);

        return $pagos + $gastos;
    }
        function getcajas($desde = false,$hasta = false) {

        if($desde && $hasta){
            $this -> whereAdd('caja_fh_inicio BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> orderBy('caja_id DESC');
        $this -> find();

        return $this;
    }
}
