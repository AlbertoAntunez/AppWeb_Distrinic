<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db');
    }

    public function ruta_diaria() {

        $data['visitas'] = $this->model_db->getVisitasVendedor();

        $data['cuenta_sel'] = isset($_GET['cuenta']) ? $_GET['cuenta'] : ''; 

        $this->load->view('templates/header', $data);
        $this->load->view('public/vendors/ruta');
        $this->load->view('templates/footer');
    }



}