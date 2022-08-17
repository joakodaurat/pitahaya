<?php
/**
 * Table Definition for producto
 */
require_once 'DB/DataObject.php';


class DataObjects_Producto extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'producto';            // table name
    public $prod_id;                         // int(11)  not_null primary_key auto_increment group_by
    public $prod_nombre;                     // varchar(255)  not_null
    public $prod_codigo;                     // bigint(20)  not_null group_by
    public $prod_marca_id;                   // int(11)  not_null group_by
    public $prod_cat_id;                     // int(11)  not_null group_by
    public $prod_baja;                       // tinyint(1)  not_null group_by
    public $prod_precio;                     // float(11)  not_null group_by
    public $prod_img1;                       // varchar(255)  not_null
    public $prod_img2;                       // varchar(255)  not_null
    public $prod_img3;                       // varchar(255)  not_null
    public $prod_img4;                       // varchar(255)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Producto',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    function nuevoProducto($o,$imagen=null){
        $this -> prod_nombre = $o['input_modelo'];
        $this -> prod_cat_id = $o['input_categoria'];
        $this -> prod_baja = 0;
        $this -> prod_precio = $o['input_precio'];
        $this -> prod_img1 = "../imagenes/sinimagen.PNG";

        $id = $this -> insert();
        return $status;
    }





function editar_imagen_producto($id,$imagen=null){
    $do_producto_edit = DB_DataObject::factory('producto');
    $do_producto_edit -> prod_id = $id;
    $do_producto_edit -> find(true);

  function compressImage($source, $destination, $quality) { 
    $imgInfo = getimagesize($source); 
    $mime = $imgInfo['mime']; 
    
    // Create a new image from file 
    switch($mime){ 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
           imagejpeg($image, $destination, $quality);
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            imagepng($image, $destination, $quality);
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            imagegif($image, $destination, $quality);
            break; 
        default: 
            $image = imagecreatefromjpeg($source); 
           imagejpeg($image, $destination, $quality);
    } 
     
     
    // Return compressed image 
    return $destination; 
} 


if ($imagen) {
// File upload path 
$uploadPath = "../imagenes/"; 
 
// If file upload form is submitted 
$status = $statusMsg = ''; 
 
$status = 'error'; 
    if(!empty($imagen["imagen"]["name"])) { 
        // File info 
        $fileName = basename($imagen["imagen"]["name"]);
        $ahora = date("YmdHis"); 
        $imageUploadPath = $uploadPath . $ahora . $fileName ; 
        $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('jpg','png','jpeg','gif','PNG'); 
        if(in_array($fileType, $allowTypes)){ 
            // Image temp source 
            $imageTemp = $imagen["imagen"]["tmp_name"]; 
             
            // Compress size and upload image 
            $tamaño = $imagen["imagen"]["size"];
            if($tamaño > 1000000) {$ubicacion = compressImage($imageTemp, $imageUploadPath, 50);} else {
                move_uploaded_file($imageTemp, $imageUploadPath);
            }
            $do_producto_edit -> prod_img1 = $imageUploadPath;
             

        }else{ 
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
        } 
    }else{ 
        $statusMsg = 'Please select an image file to upload.'; 
    } 
 


        } 


        $do_producto_edit -> update();
        return $id;
    }

 
function agregar_producto($objeto,$archivo=null) {
        $do_producto_add = DB_DataObject::factory('cms_productos');
        $do_producto_add -> prod_id = $objeto['edit_producto'];
        $do_producto_add -> find(true);
        $do_producto_add -> prod_nombre = $objeto['input_nombre'];
        $do_producto_add -> prod_valor_k = $objeto['input_valor'];
        $do_producto_add -> prod_medida =  '["'.$objeto['input_medida'].'"]';
        // $do_producto_add -> prod_prec_ant = $objeto['prod_prec_ant'];
        if ($archivo) {
            $ruta_archivo_s3 = subirArchivo($archivo);
            $do_producto_add -> prod_img = $ruta_archivo_s3;
        }
        $do_producto_add -> prod_cat_id = $objeto['input_cat'];
        $do_producto_add -> prod_historia = $objeto['input_descr'];
        $do_producto_add -> prod_nuevo = $objeto['input_nuevo'];
        $do_producto_add -> prod_promo = $objeto['input_promo'];

        $id_producto_add = $do_producto_add -> insert();

        return $id_producto_add;
    }
    
    function getProductos($id = false){
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_baja = 0;

        $do_categoria = DB_DataObject::factory('categoria');
        
        $do_productos -> joinAdd($do_categoria);

        if($id){
            $do_productos -> prod_id = $id;
            $do_productos -> find(true);
        } else {
            $do_productos -> find();
        }

        return $do_productos;  
    }



    // Devuelve cantidad de productos sin precio.
    function getProductosSinPrecio(){
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_precio = 0;
        $do_productos -> find();
        return $do_productos -> N;  
    }

    function modificarProducto($objeto) {

        $do_producto = DB_DataObject::factory('producto');
        $do_producto -> prod_id = $objeto['prod_id'];
        $do_producto -> find(true);

        $do_producto -> prod_cat_id = $objeto['input_categoria_edit'];
        $do_producto -> prod_nombre = $objeto['input_nombre'];
        $do_producto -> prod_precio = $objeto['input_precio'];

        $id_update = $do_producto -> update();
        return $id_update;
    }

    function restarStock($talle_id,$cant) {

        $id = false;
        $costo_total = 0;

        $respuesta = array();

        $do_producto_stock = DB_DataObject::factory('producto_stock');

        $do_producto_stock -> ps_producto_id = $this -> prod_id;
        $do_producto_stock -> ps_talle_id = $talle_id;
       // $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock -> find();
        

        while ($do_producto_stock -> fetch()) {
           
        if ($do_producto_stock -> ps_cantidad > 0) {  //nuevo if con stock negativo

        

            // if sin stock negativo
            if($do_producto_stock -> ps_cantidad >= $cant) {
                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $cant;
                $id = $do_producto_stock -> update();
                $respuesta['productos'][$do_producto_stock -> ps_id] = $cant;
               $cant = 0;
                break;
            } else { 
                $cant = $cant - $do_producto_stock -> ps_cantidad; 
                $respuesta['productos'][$do_producto_stock -> ps_id] = $do_producto_stock -> ps_cantidad;
                $do_producto_stock -> ps_cantidad = 0;
                $do_producto_stock -> update();
            }
            // if sin stock negativo

        }else {    //nuevo if con stock negativo
                $do_producto_stock -> ps_cantidad = $do_producto_stock -> ps_cantidad - $cant;
                $id = $do_producto_stock -> update();
                $respuesta['productos'][$do_producto_stock -> ps_id] = $cant;

        } //nuevo if con stock negativo


        }
        if($id){
            return $respuesta;
        } else {
            return false;
        }
    }
    function restarStockBodega($talle_id,$cant) {

        $id = false;
        $costo_total = 0;

        $respuesta = array();

        $do_producto_stock = DB_DataObject::factory('producto_stock_bodega');

        $do_producto_stock -> psbodega_producto_id = $this -> prod_id;
        $do_producto_stock -> psbodega_talle_id = $talle_id;
       // $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock -> find();
        

        while ($do_producto_stock -> fetch()) {
           
        if ($do_producto_stock -> psbodega_cantidad > 0) {  //nuevo if con stock negativo

        

            // if sin stock negativo
            if($do_producto_stock -> psbodega_cantidad >= $cant) {
                $do_producto_stock -> psbodega_cantidad = $do_producto_stock -> psbodega_cantidad - $cant;
                $id = $do_producto_stock -> update();
                $respuesta['productos'][$do_producto_stock -> psbodega_id] = $cant;
               $cant = 0;
                break;
            } else { 
                $cant = $cant - $do_producto_stock -> psbodega_cantidad; 
                $respuesta['productos'][$do_producto_stock -> psbodega_id] = $do_producto_stock -> psbodega_cantidad;
                $do_producto_stock -> psbodega_cantidad = 0;
                $do_producto_stock -> update();
            }
            // if sin stock negativo

        }else {    //nuevo if con stock negativo
                $do_producto_stock -> psbodega_cantidad = $do_producto_stock -> psbodega_cantidad - $cant;
                $id = $do_producto_stock -> update();
                $respuesta['productos'][$do_producto_stock -> psbodega_id] = $cant;

        } //nuevo if con stock negativo


        }
        if($id){
            return $respuesta;
        } else {
            return false;
        }
    }

    function sumarStock($compra_id, $producto_id, $pantidad, $talle) {
        $id = false;

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_compra_id = $compra_id;
        $do_producto_stock -> ps_producto_id = $producto_id;
        $do_producto_stock -> ps_talle_id = $talle;
        $do_producto_stock -> ps_cantidad = $pantidad;

        $id = $do_producto_stock -> insert();

        if($id){
            return true;
        } else {
            return false;
        }

    }
    function sumarStockBodega($compra_id, $producto_id, $pantidad, $talle) {
        $id = false;

        $do_producto_stock_bodega = DB_DataObject::factory('producto_stock_bodega');
        $do_producto_stock_bodega -> psbodega_compra_id = $compra_id;
        $do_producto_stock_bodega -> psbodega_producto_id = $producto_id;
        $do_producto_stock_bodega -> psbodega_talle_id = $talle;
        $do_producto_stock_bodega -> psbodega_cantidad = $pantidad;

        $id = $do_producto_stock_bodega -> insert();

        if($id){
            return true;
        } else {
            return false;
        }

    }

    function sumarStockTrasbordo($trasb_id, $producto_id, $cantidad) {
        $id = false;

        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_trasb_id = $trasb_id;
        $do_producto_stock -> ps_producto_id = $producto_id;
        $do_producto_stock -> ps_cantidad = $cantidad;

        $id = $do_producto_stock -> insert();

        if($id){
            return true;
        } else {
            return false;
        }

    }

    function getproductoCompra($id = false){
        $do_productos = DB_DataObject::factory('producto');
        $do_productos -> prod_baja = 0;
        if($id){
            $do_productos -> prod_id = $id;
        }

        $do_categoria = DB_DataObject::factory('categoria');
        $do_marca = DB_DataObject::factory('marca');

        $do_productos -> joinAdd($do_marca);
        $do_productos -> joinAdd($do_categoria);

        if($id){
            $do_productos -> find(true);
        } else {
            $do_productos -> find();
        }
        return $do_productos;  
    }

    function actualizarProveedor($id) {
        $this -> prod_proveedor_id = $id;
        $this -> update();
    }

    function getStock() {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $this -> prod_id;
       // $do_producto_stock -> ps_cantidad > 0;
        $do_producto_stock -> find();

        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }

        return $sum;
    }

    function getStockTotal($id) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
       // $do_producto_stock -> ps_cantidad > 0;
        $do_producto_stock -> find();

        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }

        return $sum;
    }

    function getMenorStock() {

        $respuesta = array();
        $stock = array();
        $productos = array();

        $categoria = DB_DataObject::factory('categoria');

        $this -> whereAdd('prod_baja = 0');
        $this -> joinAdd($categoria);
        $this -> find();

        while($this -> fetch()){ 
            $productos[$this -> prod_id]['nombre'] = $this -> cat_nombre . ' ' . $this -> prod_modelo;
            $productos[$this -> prod_id]['cantidad'] = $this -> getStock();
            $stock[$this -> prod_id] = $this -> getStock();
        }

        asort($stock);
        
        $i=1;
        foreach ($stock as $key => $value) {
            $respuesta['nombre'][$i] = $productos[$key]['nombre'];
            $respuesta['cantidad'][$i] = $value; 
            $i++;
        }
        
        return $respuesta;
    }


    function getStockPorTalle($id) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;

        $talle = DB_DataObject::factory('talle');

        $do_producto_stock -> joinAdd($talle);
        
       // $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock -> find();

        $resp = array();
        $do_producto_stock->orderBy('ps_talle_id ASC');
        $do_producto_stock->find();
        while ($do_producto_stock -> fetch()) {
            $sum[$do_producto_stock -> talle_nombre] += $do_producto_stock -> ps_cantidad;
            $t_aux[$do_producto_stock -> talle_id] = $do_producto_stock -> talle_nombre;
        }

      

        foreach ($t_aux as $key => $value) {
            $talles[] = $value;
        }

        $resp['Stock'] = $sum;
        $resp['Talles'] = $talles;
        return $resp;
    }
    function getStockPorTalleespecifico($id,$talleid) {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $id;
        $do_producto_stock -> ps_talle_id = $talleid;

        $talle = DB_DataObject::factory('talle');

        $do_producto_stock -> joinAdd($talle);
        
       // $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock -> find();
        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }

        return $sum;
    }
        function getStockPorTalleespecificobodega($id,$talleid) {
        $do_producto_stock = DB_DataObject::factory('producto_stock_bodega');
        $do_producto_stock -> ps_producto_id = $id;
        $do_producto_stock -> ps_talle_id = $talleid;

        $talle = DB_DataObject::factory('talle');

        $do_producto_stock -> joinAdd($talle);
        
       // $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock -> find();
        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }

        return $sum;
    }
    function getStockPorTalleBodega($id) {
        $do_producto_stock_bodega = DB_DataObject::factory('producto_stock_bodega');
        $do_producto_stock_bodega -> psbodega_producto_id = $id;

        $talle = DB_DataObject::factory('talle');

        $do_producto_stock_bodega -> joinAdd($talle);
        
       // $do_producto_stock -> whereAdd('ps_cantidad > 0');
        $do_producto_stock_bodega -> find();

        $resp = array();
        $do_producto_stock_bodega->orderBy('psbodega_talle_id ASC');
        $do_producto_stock_bodega->find();
        while ($do_producto_stock_bodega -> fetch()) {
            $sum[$do_producto_stock_bodega -> talle_nombre] += $do_producto_stock_bodega -> psbodega_cantidad;
            $t_aux[$do_producto_stock_bodega -> talle_id] = $do_producto_stock_bodega -> talle_nombre;
        }

      

        foreach ($t_aux as $key => $value) {
            $talles[] = $value;
        }

        $resp['Stock'] = $sum;
        $resp['Talles'] = $talles;
        return $resp;
    }

    
     function getStockFisico() {
        $do_producto_stock = DB_DataObject::factory('producto_stock');
        $do_producto_stock -> ps_producto_id = $this -> prod_id;
       // $do_producto_stock -> ps_cantidad > 0;
        $do_producto_stock -> find();

        $sum = 0;
        while ($do_producto_stock -> fetch()) {
            $sum += $do_producto_stock -> ps_cantidad;            
        }
        //Busco el stock vendido, pero que todavia no se despacho para sumarlo y llegar al stock fisico
        $do_ventas = DB_DataObject::factory('venta');
        $do_venta_detalle = DB_DataObject::factory('venta_detalle');
        $do_ventas -> joinAdd($do_venta_detalle);


        $do_ventas -> whereAdd('venta_estado_id IN (1,2) AND detalle_prod_id='.$this -> prod_id);
        $do_ventas -> find();
        $fisico=0;

        while($do_ventas -> fetch()){
            $fisico += $do_ventas -> detalle_prod_cant;
        }
        
        $sum = $sum + $fisico;

        return $sum;
    }

    function modificarPrecio($o){
        $this -> prod_id = $o['prod_id'];
        $this -> find(true);

        $this -> prod_precio = $o['input_precio'];
        $this -> update();

        return $this -> prod_id;
    }

    //Cambiar precios por categoria marca o a todo

    function modificarPrecios($objeto) {
     $do_productos = DB_DataObject::factory('producto');
     $do_productos -> prod_baja = 0;
        if ($objeto['select_categoria'] && $objeto['input_porcentaje_categoria'] ){ // precios por select_categoria
            //print_r($objeto);exit;
         $multiplicador = $objeto['input_porcentaje_categoria'] / 100;
         $do_productos -> prod_cat_id = $objeto['select_categoria'];
        }

        if ($objeto['select_marca'] && $objeto['input_porcentaje_marca'] ){ // precios por select_categoria
            //print_r($objeto);exit;
         $multiplicador = $objeto['input_porcentaje_marca'] / 100;
         $do_productos -> prod_marca_id = $objeto['select_marca'];
        }
        if ($objeto['input_porcentaje_todo']){
            $multiplicador = $objeto['input_porcentaje_todo'] / 100;

        }
        $do_productos -> find(); 
        while ($do_productos -> fetch()) {
            $porcentaje = $do_productos -> prod_precio * $multiplicador;
            $do_productos -> prod_precio = $do_productos -> prod_precio + $porcentaje ;
            $do_productos -> update();            
        }

        return true;

    }

    function getDatosPorPS($id){
        $ps = DB_DataObject::factory('producto_stock');
        $ps -> ps_id = $id;

        $do_talle = DB_DataObject::factory('talle');
        $do_color = DB_DataObject::factory('color');
        $do_categoria = DB_DataObject::factory('categoria');
        $do_marca = DB_DataObject::factory('marca');
        
        $this -> joinAdd($do_categoria);
        $this -> joinAdd($do_marca);

        $ps -> joinAdd($this);
        $ps -> joinAdd($do_talle);
        $ps -> joinAdd($do_color);

        $ps -> find(true);

        return $ps;
    }

}
