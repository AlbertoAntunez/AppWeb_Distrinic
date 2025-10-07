<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db'); 
        $this->utl = new Funciones(); 
        $this->load->library('pdf'); 
    }

    public function viewCtaCte($id,$fhd="",$fhh="", $print = false){

        $vdorCustomer = (defined('VENDEDOR_CLIENTES_PROPIOS')) ? VENDEDOR_CLIENTES_PROPIOS : "0";

        if($vdorCustomer == '1'){
            if($this->session->userdata('codigo') != $this->model_db->getInfoCustomer($id, 'idVendedor')){
                header("Location: ".base_url('customers/view/'.$id));
                die();
            }
        }

        if($fhd == "" || $fhd == "-"){
            $fhd = "19900101";
        }
        if($fhh == "" || $fhh == "-"){
            //$fhh = "21000101";
            $fhh = date("Ymd");
        }

        ini_set('memory_limit', '-1');
        $api = new Lib_Apiws();
        
        if(isset($_GET['opcion'])){
            if($_GET['opcion'] == 'ruta'){
                $data['rutaAnt'] = base_url('vendors/ruta_diaria');
            }
        } else {
            $data['rutaAnt'] = base_url('customers/view/'.$id);
        }
        $data['codigo'] = $id;
        $data['customerName'] = $this->model_db->getCustomerName($id);

        if($print){
            $api->method = "getCtaCte/".$id."/".$fhd."/".$fhh."/1";
        }else{
            $api->method = "getCtaCte/".$id."/".$fhd."/".$fhh."/0";
        }
        $res = $api->consumeWebService();
        $lista = json_decode($res);

        if(isset($lista->error)){
            $data['error'] = true;
            $data['error_message'] = $lista->message;
        } else {
            $data['error'] = false;
            /*Ordeno el array por el campo orden, que en api se determina por la fecha*/
            $this->utl->array_sort_by($lista, 'orden');
            $data['list'] = $lista;
        }
        
        if($print){
            PrintCtaCte($id, $data);
        }else{
            $this->load->view('templates/header', $data);
            $this->load->view('public/ctacte');
            $this->load->view('templates/footer');            
        }

    }

    public function list(){
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url();
        $data['customers'] = $this->model_db->getCustomers(30);
        $data['preSale'] = false;
    
        $this->load->view('templates/header',$data);
        $this->load->view('public/list_customers');
        $this->load->view('templates/footer');
    }

    public function view($id){
        $this->utl->redirectNoLogin();
        $data['rutaAnt'] = base_url('customers/list');
        $data['customer'] = $this->model_db->getCustomer($id);
        $data['vendorCustomer'] = $this->model_db->getInfoCustomer($id, 'idVendedor');
        $data['idCustomer'] = $id;
        
        $this->load->view('templates/header',$data);
        $this->load->view('public/customer');
        $this->load->view('templates/footer');
    }

    public function list_customersPreSale(){
        $this->utl->redirectNoLogin();
        $this->cart->destroy();
        $data['rutaAnt'] = base_url('inicio/list_orders');
        $data['customers'] = $this->model_db->getCustomers(30);
        $data['localidades'] = $this->model_db->getLocalidades();
        $data['preSale'] = true;
    
        $this->load->view('templates/header',$data);
        $this->load->view('public/list_customers');
        $this->load->view('templates/footer');
    }

    public function getQueryCustomers(){
        $query = $this->input->post("query");
        $localidad = $this->input->post("localidad");

        if($query == ""){
            $response = $this->model_db->getCustomers(30, $localidad);
        }else{
            $response = $this->model_db->getCustomersByQuery($query, $localidad);
        }

        echo json_encode($response);
    }



}