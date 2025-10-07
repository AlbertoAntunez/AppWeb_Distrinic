<?php
Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db'); 
    }

    public function index(){
        if ($this->input->post()) {
            $primerIngreso = false;

            $pass   =  $this->input->post('password');
            $user   =  $this->input->post('codigo');
            $dbName = "baseDeDatos_".trim($user).".db3";

            $this->crearArchivoDB_ConnString(trim($user));

            $usuario_data = array(
                'id'            => 1,
                'codigo'        => 1,
                'nombre'        => "",
                'logueado'      => false,
                'primerIngreso' => false,
                'dbgroup'       => 'vendedor'.trim($user),
            );

            $this->session->set_userdata($usuario_data);

            //VERIFICO EXISTENCIA DE BASE, SI NO LA CREO
            if(!file_exists(__DIR__ . "/../../db/".$dbName)){
                //CREO LA BASE
                if($this->CreateDB($dbName)){
                    $primerIngreso = true;
                }else{
                    echo "no creo, error";
                }                
            }else{
                $this->CreateDB($dbName);
                //SI LA BASE EXISTE, ENTONCES VERIFICO 
                //QUE HAYA AL MENOS 1 VENDEDOR, SI NO HAY, ES PRIMER INGRESO
                if(!$this->model_db->hayVendedores()){
                    $primerIngreso = true;
                }else{
                    $primerIngreso = false;
                }
            }
            
            if($primerIngreso){                
                $usuario_data = array(
                    'id' => $user,
                    'codigo' => $user,
                    'nombre' => $user,
                    'logueado' => true,
                    'primerIngreso' => true,
                    'dbgroup' => 'vendedor'.$user,
                );
                $this->session->set_userdata($usuario_data);
  
                header("Location: ".base_url());
            }else{           
                $usuario = $this->model_db->iniciarSesion($user, $pass);
                
                if ($usuario) {  
        	
                    $usuario_data = array(
                        'id' => $usuario->idVendedor,
                        'codigo' => $usuario->idVendedor,
                        'nombre' => $usuario->nombre,
                        'logueado' => true,
                        'primerIngreso' => false,
                        'dbgroup' => 'vendedor'.$user,
                    );
                    $this->session->set_userdata($usuario_data);
                    header("Location: ".base_url());

                } else {
                    header("Location: ".base_url()."?status=ERROR");
                    //ERROR DE LOGUEO
                }
            }
        } else {
            //$this->login();
        }

    }

    function crearArchivoDatosDB($dbName){
        
        $dir = FCPATH;
        unlink($dir."/datos_db.php");

        $archivo = fopen($dir."datos_db.php","w+");

        if( $archivo == false ) {
	    	echo "Error al crear el archivo";
	    }else{
            fwrite($archivo, "<?php \r\n");
            fwrite($archivo, "define('DB_NAME_VDOR','".$dbName."');\r\n");
            fwrite($archivo, "?>");
            fflush($archivo);
        }
        fclose($archivo);
    }

    function CreateDB($name){
        // $bd = new SQLite3("./db/".$name);
        // $bd->busyTimeout(20000);
        // $bd->exec($this->getStructureDB());

        $bd = new SQLite3(__DIR__ . "/../../db/".$name);

        $bd->exec('CREATE TABLE IF NOT EXISTS ARTICULOS (_id INTEGER PRIMARY KEY, idArticulo TEXT, descripcion TEXT, idRubro TEXT, idFamilia TEXT, iva NUMERIC, impuestosInternos NUMERIC, exento NUMERIC, precio1 NUMERIC, precio2 NUMERIC, precio3 NUMERIC, precio4 NUMERIC, precio5 NUMERIC, precio6 NUMERIC, precio7 NUMERIC, precio8 NUMERIC, precio9 NUMERIC, precio10 NUMERIC);');
        
        $bd->exec('CREATE TABLE IF NOT EXISTS clientes (
            "_id" INTEGER,
            "codigo" TEXT,
            "codigoOpcional" TEXT,
            "razonSocial" TEXT,
            "calleNroPisoDpto" TEXT,
            "localidad" TEXT,
            "cuit" TEXT,
            "iva" NUMERIC,
            "claseDePrecio" NUMERIC,
            "porcDto" NUMERIC,
            "cpteDefault" TEXT,
            "idVendedor" TEXT
        , "telefono" TEXT, "email" TEXT);');

        $bd->exec('CREATE TABLE IF NOT EXISTS depositos (_id INTEGER PRIMARY KEY, idDeposito TEXT, descripcion TEXT);');
        $bd->exec('CREATE TABLE IF NOT EXISTS PEDIDOS (_id INTEGER PRIMARY KEY, codCliente TEXT, fecha TEXT, idVendedor TEXT, totalNeto NUMERIC, totalFinal NUMERIC, transferido NUMERIC, gpsX NUMERIC,gpsY NUMERIC, facturar NUMERIC, incluirEnReparto NUMERIC, claseDePrecio NUMERIC);');
        $bd->exec('CREATE TABLE IF NOT EXISTS pedidosItems (_id INTEGER PRIMARY KEY, idPedido INTEGER, idArticulo TEXT, cantidad REAL, importeUnitario REAL, porcDescuento REAL, total REAL, transferido INTEGER);');
        $bd->exec('CREATE TABLE IF NOT EXISTS RUBROS (_id INTEGER PRIMARY KEY, idRubro TEXT, descripcion TEXT);');
        $bd->exec('CREATE TABLE IF NOT EXISTS FAMILIAS (_id INTEGER PRIMARY KEY, idFamilia TEXT, descripcion TEXT);');
        $bd->exec('CREATE TABLE IF NOT EXISTS VENDEDORES (_id INTEGER PRIMARY KEY, idVendedor TEXT, nombre TEXT, codigoDeValidacion TEXT);');
        $bd->exec('CREATE TABLE IF NOT EXISTS cobranzas (_id INTEGER PRIMARY KEY, fecha TEXT, cuenta TEXT, importe REAL, mp TEXT, observacion TEXT, idvendedor TEXT, tc TEXT);');
        $bd->exec('CREATE TABLE IF NOT EXISTS visitas (cliente TEXT NOT NULL,observaciones TEXT,nombre TEXT,lunes INTEGER,martes INTEGER,miercoles INTEGER,jueves INTEGER,viernes INTEGER,sabado INTEGER,domingo INTEGER);');
        $bd->exec('CREATE TABLE IF NOT EXISTS mediosPago (_id INTEGER PRIMARY KEY, codigo TEXT NOT NULL, descripcion TEXT) ');
        $bd->exec('CREATE TABLE IF NOT EXISTS tareas (_id INTEGER PRIMARY KEY, cliente TEXT, fecha TEXT, firma BLOB, obs TEXT, servicio TEXT)');
        $bd->exec('CREATE TABLE IF NOT EXISTS servicios (_id INTEGER PRIMARY KEY, codigo TEXT, descripcion TEXT)');

        if(file_exists(__DIR__ . "/../../db/".$name)){
            return true;
        }else{
            return false;
        }

    }
   
    // function getStructureDB(){
    //     $structure = '
    //     PRAGMA foreign_keys = off;
    //     BEGIN TRANSACTION;

    //     CREATE TABLE IF NOT EXISTS ARTICULOS (_id INTEGER PRIMARY KEY, idArticulo TEXT, descripcion TEXT, idRubro TEXT, idFamilia TEXT, iva NUMERIC, impuestosInternos NUMERIC, exento NUMERIC, precio1 NUMERIC, precio2 NUMERIC, precio3 NUMERIC, precio4 NUMERIC, precio5 NUMERIC, precio6 NUMERIC, precio7 NUMERIC, precio8 NUMERIC, precio9 NUMERIC, precio10 NUMERIC);

    //     CREATE TABLE IF NOT EXISTS clientes (
    //         "_id" INTEGER,
    //         "codigo" TEXT,
    //         "codigoOpcional" TEXT,
    //         "razonSocial" TEXT,
    //         "calleNroPisoDpto" TEXT,
    //         "localidad" TEXT,
    //         "cuit" TEXT,
    //         "iva" NUMERIC,
    //         "claseDePrecio" NUMERIC,
    //         "porcDto" NUMERIC,
    //         "cpteDefault" TEXT,
    //         "idVendedor" TEXT
    //     , "telefono" TEXT, "email" TEXT);


    //     CREATE TABLE depositos (_id INTEGER PRIMARY KEY, idDeposito TEXT, descripcion TEXT);
    //     CREATE TABLE PEDIDOS (_id INTEGER PRIMARY KEY, codCliente TEXT, fecha TEXT, idVendedor TEXT, totalNeto NUMERIC, totalFinal NUMERIC, transferido NUMERIC, gpsX NUMERIC,gpsY NUMERIC, facturar NUMERIC, incluirEnReparto NUMERIC, claseDePrecio NUMERIC);
    //     CREATE TABLE pedidosItems (_id INTEGER PRIMARY KEY, idPedido INTEGER, idArticulo TEXT, cantidad REAL, importeUnitario REAL, porcDescuento REAL, total REAL, transferido INTEGER);
    //     CREATE TABLE RUBROS (_id INTEGER PRIMARY KEY, idRubro TEXT, descripcion TEXT);
    //     CREATE TABLE FAMILIAS (_id INTEGER PRIMARY KEY, idFamilia TEXT, descripcion TEXT);
    //     CREATE TABLE VENDEDORES (_id INTEGER PRIMARY KEY, idVendedor TEXT, nombre TEXT, codigoDeValidacion TEXT);
    //     CREATE TABLE cobranzas (_id INTEGER PRIMARY KEY, fecha TEXT, cuenta TEXT, importe REAL, mp TEXT, observacion TEXT, idvendedor TEXT);

    //     CREATE TABLE visitas (cliente TEXT NOT NULL,observaciones TEXT,nombre TEXT,lunes INTEGER,martes INTEGER,miercoles INTEGER,jueves INTEGER,viernes INTEGER,sabado INTEGER,domingo INTEGER);
        
    //     COMMIT TRANSACTION;
    //     PRAGMA foreign_keys = on;
    //     ';

    //     return $structure;
    // }

    function crearArchivoDB_ConnString($idVdor){

        $arch = file(FCPATH.'datos_db.php');
        $d="";

        $buscado = "vendedor".$idVdor;
        $encontro = false;
        foreach ($arch as $linea) {
            $d.=$linea;
            if(strpos($linea, $buscado)> 0){
                $encontro = true; 
            }
            if($encontro){
                 break;
            }
        }

        if(!$encontro){
            $path = FCPATH.'datos_db.php';            
            $file = fopen($path, 'w'); 
            
            foreach ($arch as $linea) {
                if($linea == "?>"){
                    fwrite($file, $this->getMaskConnString($idVdor));
                    fwrite($file, "?>");
                }else{
                    fwrite($file, $linea);
                }
            }

            fclose($file); 
        }
        
    }

    function getMaskConnString($vdor){
        $mask = "
\$db['vendedor".trim($vdor)."'] = array(
    'dsn'	=> '',
    'hostname' => '',
    'username' => '',
    'password' => '',
    'database' => './db/baseDeDatos_".trim($vdor).".db3',
    'dbdriver' => 'sqlite3',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
";

        return $mask;
    }
}
