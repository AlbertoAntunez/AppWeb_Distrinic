<?php
Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db'); 
        $this->load->library('cart');
        $this->utl = new Funciones();
     }

    public function index(){

        $update = $this->verificaActualizacion();

        if($this->session->userdata('dbgroup')==""){
            $usuario_data = array(
                'id'            => 1,
                'codigo'        => 1,
                'nombre'        => "",
                'logueado'      => false,
                'primerIngreso' => false,
                'dbgroup'       => 'vendedor1',
                'claseP'        => '1',
            );

            $this->session->set_userdata($usuario_data);
        }

        if($this->session->userdata('logueado')){
            $this->inicio();
        }else{
            $data["version"] = $this->getVersion();
            $this->load->view('templates/header',$data);
            $this->load->view('public/login');
            $this->load->view('templates/footer');
        }
    }
    
	public function inicio(){

        if(!defined('SERVER_API')){
            $this->config();
            return;
        }

        if(!$this->session->userdata('primerIngreso')){
            $pendiente = 0;
            $pendiente = $this->model_db->getQtyPendingSend('pedidos');
            $pendiente+= $this->model_db->getQtyPendingSend('cobranzas');
            $pendiente+= $this->model_db->getQtyPendingSend('tareas');
            $data['QtyPending'] = $pendiente; //$this->model_db->getQtyPendingSend();
        }else{
            $data['QtyPending'] = 0;
        }

        $data["version"] = $this->getVersion();

        $this->load->view('templates/header', $data);
        $this->load->view('public/index');
        $this->load->view('templates/footer');
    }

    public function config(){

        $data['rutaAnt'] = base_url();
        $this->load->view('templates/header',$data);
        $this->load->view('public/config');
        $this->load->view('templates/footer');
    }
    
    public function sync(){

        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url();

        $api = new Lib_Apiws();
        $api->method = "getRegistros";
        $res = $api->consumeWebService();

        $registros = json_decode($res);

        if(isset($registros->error)){
            $data['error']          = true;
            $data['error_message']  = $registros->message;
        }else {
            $data['error'] = false;

            foreach($registros as $reg){
                switch($reg->tabla){
                    case "wsSysMobileArticulos":
                        $data['cantProducts'] = $reg->cantidadRegistros;
                    break;
                    case "wsSysMobileClientes":
                        $data['cantCustomers'] = $reg->cantidadRegistros;
                    break;
                    case "wsSysMobileRubros":
                        $data['cantCategories'] = $reg->cantidadRegistros;
                    break;
                    case "wsSysMobileVendedores":
                        $data['cantVendors'] = $reg->cantidadRegistros;
                    break;
                    default:
                    break;
                }
            } 
        }

        $data['cantFamilies'] = '...';
        
        $this->load->view('templates/header',$data);
        $this->load->view('public/sync');
        $this->load->view('templates/footer');
    }

    public function list_orders(){
        
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url();
        $data['orders'] = $this->model_db->getOrdersPending();

        $this->load->view('templates/header',$data);
        $this->load->view('public/list_orders');
        $this->load->view('templates/footer');
    }

    public function send_pending(){

        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url();

        $pendientePedidos   = $this->model_db->getQtyPendingSend('pedidos');
        $pendienteCobranzas = $this->model_db->getQtyPendingSend('cobranzas');
        $pendienteTareas    = $this->model_db->getQtyPendingSend('tareas');
        
        $data['QtyPending'] = $pendientePedidos + $pendienteCobranzas + $pendienteTareas;
        $data['pendienteTareas']    = $pendienteTareas;
        $data['pendienteCobranzas'] = $pendienteCobranzas;
        $data['pendientePedidos']   = $pendientePedidos;

        $this->load->view('templates/header',$data);
        $this->load->view('public/send-orders');
        $this->load->view('templates/footer');
    }

    public function grabarArchivoConfiguracion(){

        $this->utl->redirectNoLogin();
        $server_api = $this->input->post('idWs');
        $modifica_clase = ($this->input->post('cfg_modificaClase') == "on") ? "1" : "0";
        $vdorCustomerOnly = ($this->input->post('cfg_vendorCustomerOnly') == "on") ? "1" : "0";
        $stockOnlineConsultas = ($this->input->post('cfg_stockOnlineConsultas') == "on") ? "1" : "0";
        $artsPorConsulta = $this->input->post('cfg_articulosPorConsulta');

        $rutaImagenesProductos = $this->input->post('cfg_rutaImagenesProductos');
        $muestraImagenesProductos = ($this->input->post('cfg_muestraImagenesProductos') == "on") ? "1" : "0";
        $stockcomprmasreal = ($this->input->post('cfg_stockcomprmasreal') == "on") ? "1" : "0";
        $noMuestraTotPedidos = ($this->input->post('cfg_noMuestraTotalesPedidos') == "on") ? "1" : "0";

        $usaOffline = ($this->input->post('cfg_usaoffline') == "on") ? "1" : "0";
        
        //AGREGO BARRA AL FINAL
        if($rutaImagenesProductos != ''){
            if(substr($rutaImagenesProductos, -1) != '/'){
                $rutaImagenesProductos.= '/';
            }
        }

        $artsPorConsulta = ($artsPorConsulta == "" || $artsPorConsulta == "0") ? 20 : $artsPorConsulta;
        $dir = FCPATH;

        unlink($dir."/configuracion.php");

        $archivo = fopen($dir."configuracion.php","w+");

        if( $archivo == false ) {
	    	echo "Error al crear el archivo";
	    }else{
            fwrite($archivo, "<?php \r\n");
            fwrite($archivo, "define('SERVER_API','".$server_api."');\r\n");
            fwrite($archivo, "define('MODIFICA_CLASEP','".$modifica_clase."');\r\n");
            fwrite($archivo, "define('VENDEDOR_CLIENTES_PROPIOS','".$vdorCustomerOnly."');\r\n");
            fwrite($archivo, "define('STOCK_ONLINE_CONSULTAS','".$stockOnlineConsultas."');\r\n");
            fwrite($archivo, "define('ARTS_POR_CONSULTA','".$artsPorConsulta."');\r\n");
            fwrite($archivo, "define('MUESTRA_IMAGENES_PRODUCTOS','".$muestraImagenesProductos."');\r\n");
            fwrite($archivo, "define('RUTA_IMAGENES_PRODUCTOS','".$rutaImagenesProductos."');\r\n");
            fwrite($archivo, "define('STOCK_COMPROMETIDO_MAS_REAL','".$stockcomprmasreal."');\r\n");
            fwrite($archivo, "define('NOMUESTRA_TOTALES_PEDIDOS','".$noMuestraTotPedidos."');\r\n");
            fwrite($archivo, "define('USA_OFFLINE','".$usaOffline."');\r\n");
            fwrite($archivo, "?>");
            fflush($archivo);
        }
        fclose($archivo);

        header("Location: ".base_url());
        die();
    }
 
    public function newOrder($customer, $idOrder = 0){
        $this->utl->redirectNoLogin();
        if($idOrder > 0){
            $this->cart->destroy();
            $items = $this->model_db->getOrderItems_DB($idOrder);

            foreach($items as $row){
                $data = array(
                    'id'   	=> $row->idArticulo,
                    'qty'	=> $row->cantidad,
                    'price'	=> $row->importeUnitario,
                    'name'  => "asd",                    
                );
                $this->cart->insert($data);
            }
            $data['clasePrecio'] = $this->model_db->getPriceClassOrder($idOrder);
        }else{
            $data['clasePrecio'] = $this->model_db->getInfoCustomer($customer, "claseDePrecio");
        }

        $data['idOrder'] = $idOrder;
        if(isset($_GET['opcion'])){
            if($_GET['opcion'] == 'ruta'){
                $data['rutaAnt'] = base_url('vendors/ruta_diaria');
            }
        }else{
            $data['rutaAnt'] = base_url('inicio/list_orders');
        }

        $data['customer'] = $customer;
        $data['customerName'] = $this->model_db->getCustomerName($customer);

        $this->load->view('templates/header',$data);
        $this->load->view('public/new_order');
        $this->load->view('templates/footer');
    }

    function escribirLog($texto){
        $file = fopen(FCPATH."log.txt", "a");
        fwrite($file, $texto . PHP_EOL);
        fclose($file);
    }

    function salir(){
        $this->session->sess_destroy();
        header("Location: ".base_url());
    }

    function cambiaClasePrecio(){
        $clase = $this->input->post("clase");
        $preSale = $this->input->post("preSale");

        if($clase == "" || $clase == 0 || $clase == "0"){
            $clase = "1";
        }

        $usuario_data = array(
            'id'            => $this->session->userdata('id'),
            'codigo'        => $this->session->userdata('codigo'),
            'nombre'        => $this->session->userdata('nombre'),
            'logueado'      => $this->session->userdata('logueado'),
            'primerIngreso' => $this->session->userdata('primerIngreso'),
            'dbgroup'       => $this->session->userdata('dbgroup'),
            'claseP'        => $clase,
        );

        $this->session->set_userdata($usuario_data);

        if($preSale){
            //ACTUALIZO EL CARRO
            $this->updateCart($clase);
            echo $this->utl->getMaskCart();
        }else{
            $products = $this->model_db->getProducts();
            echo $this->utl->getMaskListProducts($products, $preSale);
        }
    }

    function updateCart($clase){
        foreach ($this->cart->contents() as $row) {
            $data = array(
                'rowid' => $row["rowid"],
                'price' => $this->model_db->getPriceByClass($row["id"], $clase)
            );
            $this->cart->update($data);
        }
    }

    function reloadModalProdSale(){
        $dataAdic['rutaAnt'] ="";
        $dataAdic['products'] = $this->model_db->getProducts();
        $dataAdic['preSale'] = true;
        $dataAdic['showTopBar'] = false;

        echo $this->load->view('public/list_products', $dataAdic, TRUE);
    }

    function verificarActualizacionLogin(){
        $update = $this->verificaActualizacion();
        echo $update;
    }

    function verificaActualizacion(){
        /*
        STATUS 
            0 => Error
            1 => Actualizo
            2 => No hay actualizacion
        */
    
        try{
            header('Access-Control-Allow-Origin: *');
            $res = @file_get_contents("http://www.alfagestion.com.ar/updates_mobile/update.json");

            if($res === false){
                $response = 0;
                return 0;
            }else{
                $response = $res;
            }
        
        }catch (Exception $e){
            $response = 0;
            return 0;
        }
        
        if($response == "ERROR"){ return; }
        
        $version = $this->getVersion();
        
        $json = json_decode($response, TRUE);

        $status = 2; $paso = "";
        $versionUpdate = $json["version"];
       
        if($version < $versionUpdate){            
            $status = 1;
            //RECORRO Y DESCARGO
            foreach($json["files"] as $item){
                try{
                    
                    $texto = @file_get_contents("http://www.alfagestion.com.ar/updates_mobile/".$item["archivo"]);
                    
                    if($texto!=""){
                    
                        $dirname = dirname(FCPATH.$item["destino"]);
                
                        if (!is_dir($dirname)) {
                            mkdir($dirname, 0755, true);
                        }
                        
                        $file = fopen(FCPATH.$item["destino"], "w");
                        fwrite($file, $texto);
                        fclose($file);
                    }else{
                        $status = 0;
                        break;
                    }

                }catch (Exception $e){
                    $status = 0;
                    break;
                }
            }
        }

        if($status == 1){
            $file = fopen(FCPATH."updates/last_version.txt", "w");            
            fwrite($file, $versionUpdate);
            fclose($file);
        }
        
        return $status;
    }

    function getVersion(){
        //LEO EL ARCHIVO QUE GUARDA LA ULTIMA VERSION
        if(!file_exists(FCPATH."updates/last_version.txt")){
            $version = 0;
            $file = fopen(FCPATH."updates/last_version.txt", "w");
            fwrite($file, $version);
            fclose($file);
        }else{
            $file = fopen(FCPATH."updates/last_version.txt", "r");
            $version = fgets($file);
            fclose($file);
        }

        return $version;
    }
}