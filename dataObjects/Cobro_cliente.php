<?php
/**
 * Table Definition for cobro_cliente
 */
require_once 'DB/DataObject.php';

class DataObjects_Cobro_cliente extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'cobro_cliente';       // table name
    public $cobro_id;                        // int(11)  not_null primary_key auto_increment group_by
    public $cobro_fh;                        // datetime(19)  not_null
    public $cobro_cliente_id;                // int(11)  not_null group_by
    public $cobro_monto_total;               // float(11)  not_null group_by
    public $cobro_usuario_id;                // int(11)  not_null group_by
    public $cobro_forma_pago;                // int(11)  not_null group_by
    public $cobro_cheque_id;                 // int(11)  group_by
    public $cobro_bono_id;                   // int(11)  group_by
    public $cobro_observacion;               // varchar(512)  
    public $cobro_baja_fh;                   // datetime(19)  
    public $cobro_transferencia_id;          // int(11)  group_by
    public $cobro_deposito_id;               // int(11)  group_by
    public $cobro_monto_total_dolar;         // float(11)  not_null group_by
    public $cobro_vuelto_dolar;              // float(11)  group_by
    public $cobro_vuelto_pesos;              // float(11)  group_by
    public $cobro_pago_dolar;                // float(11)  group_by
    public $cobro_pago_pesos;                // float(11)  group_by
    public $cobro_pago_tarjeta;              // float(11)  group_by

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Cobro_cliente',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoCobro($objeto) {
        $do_ultima_caja = DB_DataObject::factory('caja');
        $ultima_caja = $do_ultima_caja -> getUltimaCaja();

        $objeto['cobro_fh'] = date("Y-m-d H:i:s");
        $this -> cobro_fh = date("Y-m-d H:i:s");
        $this -> cobro_cliente_id = $objeto['input_id_cliente']; 
        $this -> cobro_forma_pago = $objeto['combo_fpago'];
        $this -> cobro_usuario_id = $_SESSION['usuario']['id'];
        $this -> cobro_observacion = $objeto['input_obs_pago'];

        if($objeto['combo_fpago'] == 1){ // efectivo
            $this -> cobro_monto_total = $objeto['saldo_final_total'];
            $this -> cobro_pago_pesos = $objeto['input_monto_contado'];
            $this -> cobro_vuelto_pesos = $objeto['cambio_en_pesos'];
            $ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo + $objeto['input_monto_contado'] - $objeto['cambio_en_pesos'];
        }
        if($objeto['combo_fpago'] == 2){ // dolar
            $this -> cobro_monto_total = $objeto['saldo_final_total'];
            $this -> cobro_monto_total_dolar = $objeto['total_dolar'];
            $this -> cobro_pago_dolar = $objeto['input_monto_dolar'];
            $this -> cobro_vuelto_dolar = $objeto['pagodolares_cambio_dolares'];
            $this -> cobro_vuelto_pesos = $objeto['pagodolares_cambio_pesos'];
            $ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo - $objeto['pagodolares_cambio_pesos'];
            $ultima_caja -> caja_pagos_dolar = $ultima_caja -> caja_pagos_dolar + $objeto['input_monto_dolar'] - $objeto['pagodolares_cambio_dolares'];
        }
        if ($objeto['combo_fpago'] == 3) { // tarjeta
            $this -> cobro_monto_total = $objeto['input_monto_tarjeta'];
            $ultima_caja -> caja_pagos_tarjeta = $ultima_caja -> caja_pagos_tarjeta + $objeto['input_monto_tarjeta'];
        }
        if ($objeto['combo_fpago'] == 4) { // multiple
            $this -> cobro_monto_total = $objeto['saldo_final_total'];
            $this -> cobro_pago_dolar = $objeto['input_monto_dolares_multiple'];
            $this -> cobro_pago_pesos = $objeto['input_monto_pesos_multiple'];
            $this -> cobro_pago_tarjeta = $objeto['input_monto_tarjeta_multiple'];
            $ultima_caja -> caja_pagos_tarjeta = $ultima_caja -> caja_pagos_tarjeta + $objeto['input_monto_tarjeta_multiple'];
            $ultima_caja -> caja_pagos_dolar = $ultima_caja -> caja_pagos_dolar + $objeto['input_monto_dolares_multiple'];
            $ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo + $objeto['input_monto_pesos_multiple'];
        }
        $ultima_caja -> update();
        $id_cobro = $this -> insert();
        if($objeto['input_id_cliente'] != 9999) {
            $cc = DB_DataObject::factory('cliente_cuenta_corriente');
            $id_cc = $cc -> cargarCobro($objeto, $id_cobro);
        }

        return $id_cobro;
    }

    function getCobros($desde = false,$hasta = false) {

        $do_usuario = DB_DataObject::factory('usuario');
        $do_cliente = DB_DataObject::factory('cliente');
        $do_cheque = DB_DataObject::factory('cheque');
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($do_usuario);
        $this -> joinAdd($do_cliente);
        $this -> joinAdd($forma_pago);
        $this -> joinAdd($do_cheque,'left');

        if($desde && $hasta){
            $this -> whereAdd('cobro_fh BETWEEN "'.$desde.'" AND "'.$hasta.' 23:59:59"');
        }

        $this -> whereAdd('cobro_baja_fh IS NULL') ;
        $this -> orderBy('cobro_id DESC');
        $this -> find();

        return $this;
    }

    function getListadoCobrosDiarios($fecha) {
        $do_cliente = DB_DataObject::factory('cliente');
        $do_cheque = DB_DataObject::factory('cheque');

        $this -> joinAdd($do_cliente);
        $this -> joinAdd($do_cheque,'left');

        $dia = date('Y-m-d',strtotime($fecha));

        $desde = $dia.' 00:00:00';
        $hasta = $dia.' 23:59:59';

        if($desde && $hasta){
            $this -> whereAdd('cobro_fh BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        }

        $this -> whereAdd('cobro_baja_fh IS NULL') ;
        $this -> orderBy('cobro_id DESC');
        $this -> find();

        while($this -> fetch()) {
            switch ($this -> cobro_forma_pago) {
                case 1:
                    $operacion = 'Contado';
                    break;
                case 2:
                    $operacion = 'Cheque';
                    break;
            }

            $respuesta[$this -> cobro_id]['nro'] = $this -> cliente_id;
            $respuesta[$this -> cobro_id]['cli'] = $this -> cliente_nombre;
            $respuesta[$this -> cobro_id]['item'] = $operacion;
            $respuesta[$this -> cobro_id]['pago'] = $this -> cobro_monto_total;

        }

        return $respuesta;
    }

    function getIngresosCaja($fhInicio,$fhCierre=false) {
        if(!$fhCierre){
            $fhCierre = date('Y-m-d H:i:s');
        }
        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($forma_pago);
        $this -> whereAdd('cobro_fh BETWEEN "'.$fhInicio.'" AND  "'.$fhCierre.'"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){

            if($this -> cobro_forma_pago == 2 ){ //pago en dolares
               $total[$this -> fp_desc] += 0;
               $total[$this -> fp_desc] += $this -> cobro_monto_total_dolar;
            }else {
               $total[$this -> fp_desc] += 0;
               $total[$this -> fp_desc] += $this -> cobro_monto_total;
            }
         $suma += $this ->  cobro_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }

    function getIngresosHistorialCaja($fh) {

        $forma_pago = DB_DataObject::factory('forma_pago');

        $this -> joinAdd($forma_pago);
        $this -> whereAdd('cobro_fh LIKE "%'.$fh.'%"');

        $this -> find();
        $suma = 0;

        while($this -> fetch()){
            $total[$this -> fp_desc] += 0;
            $total[$this -> fp_desc] += $this -> cobro_monto_total;
            $suma += $this ->  cobro_monto_total;
        }
        $total['Total'] = $suma;

        return $total;
    }
    function eliminarCobro($id) {
        $do_ultima_caja = DB_DataObject::factory('caja');
        $ultima_caja = $do_ultima_caja -> getUltimaCaja();
        // encuentro el cobro asociado
        $db_cobro = DB_DataObject::factory('cobro_cliente');
        $db_cobro -> cobro_id  = $id;
        $db_cobro -> find(true);
        // restauro el efectivo de la caja
        if($db_cobro -> cobro_forma_pago == 1){ // efectivo
            $ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo - $db_cobro -> cobro_pago_pesos + $db_cobro -> cobro_vuelto_pesos;
        }
        if($db_cobro -> cobro_forma_pago == 2){ // dolar
            $ultima_caja -> caja_pagos_efectivo = $ultima_caja -> caja_pagos_efectivo + $db_cobro -> cobro_vuelto_pesos;
            $ultima_caja -> caja_pagos_dolar = $ultima_caja -> caja_pagos_dolar - $db_cobro -> cobro_pago_dolar + $db_cobro -> cobro_vuelto_dolar;
        }
        if($db_cobro -> cobro_forma_pago == 3){ // tarjeta
            $ultima_caja -> caja_pagos_tarjeta = $ultima_caja -> caja_pagos_tarjeta - $db_cobro -> cobro_monto_total;
        }
        $ultima_caja -> update();
        //elimino el cobro
        $db_cobro -> delete();
    }

}
