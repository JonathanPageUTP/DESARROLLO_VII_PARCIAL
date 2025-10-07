<?php
// Archivo: clases.php



//Creamos la interface Inventariable
interface Inventariable{
    //cremos el metodo obtenerInformacionInventario
    public function obtenerInformacionInventario(): string;

}
abstract class Producto {
    public $id;
    public $nombre;
    public $descripcion;
    public $estado;
    public $stock;
    public $fechaIngreso;
    public $categoria;

    public function __construct($datos) {
        foreach ($datos as $clave => $valor) {
            if (property_exists($this, $clave)) {
                $this->$clave = $valor;
            }
        }
    }

    public function getId () {return $this -> id;}
   public function getNombre () {return $this -> nombre;}
   public function getDescripcion () {return $this -> descripcion;}
   public function getEstado () {return $this -> estado;}
   public function getStock () {return $this -> stock;}
   public function getfechaIngreso () {return $this -> fechaIngreso;}
   public function getCategoria () {return $this -> categoria;}

   abstract public function obtenerInformacionInventario(): string;
}


//Creación de las clases que heredan la clases Producto
class ProductoElectronico extends Producto{
    public $garantiaMeses;

    public function __construct($datos) {
        parent::__construct($datos);
        $this->garantiaMeses = $datos['garantiaMeses'];
    }
}

class ProductoAlimento extends Producto{
    public $fechaVencimiento;

    public function __construct($datos) {
        parent::__construct($datos);
        $this->fechaVencimiento = $datos['fechaVencimiento'];
    }
}

class ProductoRopa extends Producto{
    public $talla;

    public function __construct($datos) {
        parent::__construct($datos);
        $this->talla = $datos['talla'];
    }
}

class GestorInventario {
    private $items = [];
    private $rutaArchivo = 'productos.json';

    public function obtenerTodos() {
        if (empty($this->items)) {
            $this->cargarDesdeArchivo();
        }
        return $this->items;
    }

    private function cargarDesdeArchivo() {
        if (!file_exists($this->rutaArchivo)) {
            return;
        }
        
        $jsonContenido = file_get_contents($this->rutaArchivo);
        $arrayDatos = json_decode($jsonContenido, true);
        
        if ($arrayDatos === null) {
            return;
        }
        
        foreach ($arrayDatos as $datos) {
            $this->items[] = new Producto($datos);
        }
    }

    private function persistirEnArchivo() {
        $arrayParaGuardar = array_map(function($item) {
            return get_object_vars($item);
        }, $this->items);
        
        file_put_contents(
            $this->rutaArchivo, 
            json_encode($arrayParaGuardar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function obtenerMaximoId() {
        if (empty($this->items)) {
            return 0;
        }
        
        $ids = array_map(function($item) {
            return $item->id;
        }, $this->items);
        
        return max($ids);
    }
}