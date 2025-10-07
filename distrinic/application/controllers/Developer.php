<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Developer extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
    }

    public function getSerie() {

        if(MODO_DESARROLLADOR != 'NO'){
            return;
            exit();
        }

        $key = $this->input->post("key");

        $api = new Lib_Apiws();
        $api->method = "serie/".$key;
        $res = $api->consumeWebService();
        $lista = json_decode(json_encode($res), true);

        print_r($lista);

    }

}