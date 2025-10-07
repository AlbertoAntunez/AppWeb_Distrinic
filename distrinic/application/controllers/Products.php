<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db');
        $this->utl = new Funciones(); 
        $this->load->library('pdf');    
    }

    public function viewStock($id){
        $api = new Lib_Apiws();

        //$func = new Funciones();
        $data['rutaAnt'] = base_url('products/list');
        $data['codigo'] = $id;
        $data['productsDescription'] = $this->model_db->getProductDescription($id);

        $api->method = "getStockD/".$id;
        $res = $api->consumeWebService();
        
        $lista = json_decode($res);

        if(isset($lista->error)){
            $data['error']          = true;
            $data['error_message']  = $lista->message;
        }else{
            $data['error']  = false;
            $data['list']   = json_decode($res);
        }

        $this->load->view('templates/header', $data);
        $this->load->view('public/stock');
        $this->load->view('templates/footer');
    }

    public function getQueryProducts(){
        $query = $this->input->post("query");
        $preSale = $this->input->post("preSale");

        if($query == ""){
            $response = $this->model_db->getProducts();
        }else{
            $response = $this->model_db->getProductsByQuery($query);
        }

        $response = $this->utl->getMaskListProducts($response, $preSale);
        echo $response;
        //echo json_encode($response);
    }

    public function view($id){
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url('products/list');
        $data['product'] = $this->model_db->getProduct($id);
        $data['idProduct'] = $id;

        $this->load->view('templates/header',$data);
        $this->load->view('public/product');
        $this->load->view('templates/footer');
    }

    public function list_productsPreSale($idCustomer, $idOrder=0){
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url('inicio/newOrder/'.$idCustomer."/".$idOrder);
        $data['products'] = $this->model_db->getProducts();
        $data['preSale'] = true;
        $data['showTopBar'] = false;
    
        $this->load->view('templates/header',$data);
        $this->load->view('public/list_products');
        $this->load->view('templates/footer');
    }

    public function list(){
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url();
        $data['preSale'] = false;
        $data['products'] = $this->model_db->getProducts();
        $data['showTopBar'] = true;
    
        $this->load->view('templates/header',$data);
        $this->load->view('public/list_products');
        $this->load->view('templates/footer');
    }

    public function price_list(){
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url();
        $data['showTopBar'] = true;
        $data['products'] = $this->model_db->getProducts();
        $data['rubros'] = $this->model_db->getRubros();
    
        $this->load->view('templates/header', $data);
        $this->load->view('public/price_list');
        $this->load->view('templates/footer');
    }

    public function productExists(){
        $this->utl->redirectNoLogin();
        $codigo = $this->input->post("codigo");
        $clase = $this->input->post("clase");

        $response = $this->model_db->productExists($codigo, $clase);
        echo json_encode($response);
    }

    public function addProduct(){
        $this->utl->redirectNoLogin();
        $codigo = $this->input->post("codigo");
        $cantidad = $this->input->post("cantidad");
        $unit = $this->input->post("unit");
        $desc = "art";//$this->input->post("desc");

        $data = array(
            'id'   	=> $codigo,
            'qty'	=> $cantidad,
            'price'	=> $unit,
            'name'  => $desc,
        );
        
        $this->cart->insert($data);

        echo $this->utl->getMaskCart();
    }

    function getStockActual_byFetch(){
        $codigo = $this->input->post("codigo");
        $stock = $this->utl->getStockReal($codigo);

        if(isset($stock->error)){
            echo "error";
        }else{
            echo $stock;
        }

    }

    function print_pricelist(){
        PrintPriceList();
    }



}