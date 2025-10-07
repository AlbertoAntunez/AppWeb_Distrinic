<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db');
        $this->utl = new Funciones();
    }

    function insertSyncDataDB(){
        
        $usaOffline = (defined('USA_OFFLINE')) ? USA_OFFLINE : false;
        $this->utl->redirectNoLogin();
 
        set_time_limit(60);

        $api = new Lib_Apiws();        
        $option = $this->input->post("option");
        $arrayGral = array();

        $tope = 2;
        $isVdor = false;
        $status = "";
        $pagination = false;
        $fileOffline = "";
        
        switch ($option){
            case "getClientesD":
                $table = "clientes";
                $pagination = true;
                $tope = 20;
                $fileOffline = "clientes.json";
            break;
            case "getVendedoresD":
                $table = "VENDEDORES";
                $isVdor = true;
                $fileOffline = "vendedores.json";
            break;   
            case "getRubrosD":
                $table = "RUBROS";
                $fileOffline = "rubros.json";
            break;
            case "getFamiliasD":
                $table = "FAMILIAS";
                $fileOffline = "familias.json";
            break;
            case "getVisitasVendedor":
                $vdor = $this->session->userdata('codigo');
                $dia = date('N');
                
                $option = "getVisitasVendedor/".$vdor."/".$dia;
                $table = "visitas";
                $fileOffline = "visitas.json";
            case "getMediosPago":
                $option = "getMediosPago";
                $table = "mediosPago";
                $fileOffline = "mediosPago.json";
            break;
            case "getServicios":
                $option = "getServicios";
                $table = "servicios";
                $fileOffline = "servicios.json";
            break;
            case "getArticulosD":
                $table = "ARTICULOS";
                $pagination = true;
                if($usaOffline){
                    $tope = 10; //hasta 10 mil registros offline
                }else{
                    $tope = 20;
                }
                
                $fileOffline = "articulos.json";

                if($this->session->userdata('primerIngreso')){
                    $usuario_data = array(
                        'id' => $this->session->userdata('id'),
                        'codigo' => $this->session->userdata('codigo'),
                        'nombre' => $this->session->userdata('nombre'),
                        'logueado' => false,
                        'primerIngreso' => true,
                        'dbgroup' => $this->session->userdata('dbgroup'),
                        'claseP' => '1',
                    );
                    $this->session->set_userdata($usuario_data);
                }
            break;
            default:
                $option = "getRubrosD";
                $table = "RUBROS";
                $fileOffline = "rubros.json";
            break;
        }

        //unlink(FCPATH."offline\articulos.json");

        for($i=1; $i < $tope; $i++){

            if($pagination){
                $api->method = $option."/".$i;
                $res = $api->consumeWebService();
            }else{
                $api->method = $option;
                $res = $api->consumeWebService();
            }

            $final = json_decode($res);
            $cantidad = count($final);
            
            if(($cantidad == 0 && $i>0) || empty($final)) {
                if($pagination){
                    $status = "OK";
                }  else{
                    $status = "OK"; //($i == 1) ? "SALIO ANTES" : "OK";
                }                      
                break;
            }
            
            if(!$isVdor){
                $arrayGral = array_merge($arrayGral, $final);
            }else{
                foreach($final as $items){
                    $data = (object) [
                        'idVendedor' => $items->idVendedor,
                        'nombre' => $items->nombre,
                        'codigoDeValidacion' => $items->codigoValidacion,
                    ];
                    array_push($arrayGral, $data);
                }                    
            }

        }

        if($table == "ARICULOS") {
            echo $cantidad . " - " . $i;
        }

        if(!empty($arrayGral)){
            if($usaOffline){
                $this->save_db_offline($fileOffline, $arrayGral);
            }

            $this->db2 = $this->load->database($this->session->userdata('dbgroup'), TRUE);
            $this->db2->trans_begin();
            $this->model_db->ejecutarConsulta("DELETE FROM ".$table, false);

            $this->db2->insert_batch($table, $arrayGral);

            if ($this->db2->trans_status() === FALSE){
                $this->db2->trans_rollback();
                $status = "ERROR3";
            }else{
                $this->db2->trans_commit();
                $status = "OK";
                
            }
        }
            
        echo $status;     
              
    }

    function save_db_offline($archivo, $dato){
        $dir = FCPATH;
        $archivo = $dir."offline\json\\".$archivo;
        file_put_contents($archivo, json_encode($dato));
    }

    function load_db_offline(){
        $usaOffline = (defined('USA_OFFLINE')) ? USA_OFFLINE : false;

        if(!$usaOffline){
            echo "NO";
            exit();
        }

        $name = $this->input->post("name");
        $getfile = file_get_contents('./offline/json/'.$name.'.json');
        //$jsonfile = json_decode($getfile, true);

        echo $getfile;
    }
    


}