<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db'); 
        $this->utl = new Funciones(); 
        $this->load->library('pdf'); 
    }

    public function index() {
        //Muestro la pantalla de tareas
        $mask = '';

        $data['customers'] = $this->model_db->getCustomers(5000);
        
        $datos = $this->model_db->getTareas();
        $mask .= $this->_getMaskTasks($datos);

        if($mask != ''){
            $mask = '<div class="col-xs-12"><p class="bg-warning text-center">Pendientes de sincronización</p></div>' . $mask;
        }

        $data['tareas_pendientes'] = $mask;
        
        $this->load->view('templates/header',$data);
        $this->load->view('public/tasks/list');
        $this->load->view('templates/footer');
    }

    function temp() {

        $res = $this->model_db->temp();
        
        $response = array('error' => false, 'base' => $res);
        
        print_r(json_encode($response));

    }


    public function new() {
        /*
        Pantalla para nueva tarea.
        Puede recibir por get 1 parametro
        cuenta (carga la cuenta ingresada)
        */
        
        $data['rutaAnt']    = base_url('payments');
        $data['customers']  = $this->model_db->getCustomers(5000);
        $data['cuenta_sel'] = isset($_GET['cuenta']) ? $_GET['cuenta'] : ''; 
        $data['error']      = false;
        $data['servicios']  = $this->model_db->getServicios();        

        $this->load->view('templates/header',$data);
        $this->load->view('public/tasks/new');
        $this->load->view('templates/footer');
    }

    public function save() {
        $this->utl->redirectNoLogin();

        $fecha      = $this->input->post("fecha");
        $cuenta     = $this->input->post("cuenta");
        $firma      = $this->input->post("firma");
        $obs        = $this->input->post("obs");
        $service    = $this->input->post("service");

        if(empty($fecha) || empty($cuenta)) {
            echo "Debe ingresar todos los datos";
            exit();
        }

        date_default_timezone_set(TIMEZONE);
        // $id_vendedor = $this->session->userdata('codigo');

        $data = array(
            'fecha'     => $fecha,
            'cliente'   => $cuenta,
            'firma'     => $firma,
            'obs'       => $obs,
            'servicio'  => $service
        );

        // $api = new Lib_Apiws();
        // $api->strJson = json_encode($data);
        // $api->method = "saveCobranza";
        // $res = $api->sendComprobantes();

        $res = $this->model_db->insertar($data, "tareas", false);
        if($res != "OK"){
            //ERROR
            $response = array('error' => true, 'message' => $res);
            
        }else {
            $response = array(
                'status' => 'ok'
            );
        }

        print_r(json_encode($response));

    }

    public function searchPayments(){
        /*
        Busca cobranzas.
        Primero busca en la base local (sin sincronizar)
        Y luego mediante API (Desde el servidor)
        */
        $this->utl->redirectNoLogin();
        $idCustomer = $this->input->post("idCustomer");
        $fhd        = $this->input->post("fhd");
        $fhh        = $this->input->post("fhh");
        $mask       = "";

        $idCustomer = $idCustomer == '' ? '-1' : $idCustomer;

        date_default_timezone_set(TIMEZONE);
        $fhd = $fhd != '' ? strftime("%G%m%d", strtotime($fhd)) : date("Ymd");
        $fhh = $fhh != '' ? strftime("%G%m%d", strtotime($fhh)) : date("Ymd");

        $mask .= '<div class="col-xs-12"><p class="bg-warning text-center">Pendientes de sincronización</p></div>';
        
        $data = $this->model_db->getCobranzas($fhd, $fhh,$idCustomer);
        $mask .= $this->_getMaskTasks($data);

        $api = new Lib_Apiws();
        $api->method = "getCobranzas/".$this->session->userdata('codigo')."/".$fhd."/".$fhh."/".$idCustomer;
        $res = $api->consumeWebService();

        $cantidad = 0;

        if($res!= '[]'){
            $final = json_decode($res);
            if(isset($final->error)){
                echo '
                    <p class="text-center bg-danger">
                        '.$final->message.'
                    </p>
                ';
                exit();
            }
            $cantidad = count($final);
        }
        if($cantidad == 0){
            $mask .='<br><br><div class="col-xs-12"><p class="text-center bg-warning">Sin resultados remotos</p></div>';
        }else{
            $mask .= '<div class="col-xs-12 item-historial"><p class="bg-success text-center">Cargadas en servidor</p></div>';
            $mask .= $this->_getMaskTasks($final);
        }
        echo $mask;

    }

    private function _getMaskTasks($data) {
        $mask   = '';
        $total  = 0;

        foreach($data as $row){
            $fecha = '';
            $razon = '';

            // $total += $row->totalFinal;
            $razon = $this->model_db->getCustomerName($row->cliente);
            

            $fecha = $row->fecha;
            date_default_timezone_set(TIMEZONE);
            $fecha = strftime("%d/%m/%G", strtotime($fecha));

            $mask .= '
            
            <div class="col-xs-12 item-historial">
                <div class="col-xs-2 col-xs-offset-0">
                    <img style="max-width:100%;" alt="sent order" src="'.base_url('assets/img/icon/task.svg').'" >
                </div>
                <div class="col-xs-10">    
                    <div class="col-xs-12">
                        <p class="title_customer" style="margin:0;">
                            <a href="#" name="" class="item_payment">'.$razon.'</a>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <small>'.$fecha.'</small>
                    </div>
                </div>
            </div>
            ';
        }

        if($total > 0) {
            $mask .= '
            <div class="col-xs-offset-2 col-xs-4">
                <b>Total</b>
            </div>
            <div class="col-xs-5 text-right">
                <b></b>
            </div>
            <div class="col-xs-1">
            </div>';
        }

        return $mask;
    }

    public function sendTasks() {
        $this->utl->redirectNoLogin();

        $api = new Lib_Apiws();
        $rs = $this->model_db->ejecutarConsulta("SELECT _id, fecha, cliente, firma, obs, servicio FROM tareas ORDER BY _id", true);
    
        $json_tareas = array();
        $idvendedor = $this->session->userdata('codigo');

        foreach($rs as $row) {

            $tarea = array(
                'fecha'         => $row->fecha,
                'cuenta'        => $row->cliente,
                'observacion'   => $row->obs,
                'firma'         => $row->firma,
                'idtarea'       => $row->servicio,
                'idvendedor'    => $idvendedor,
            );

            array_push($json_tareas, $tarea);
        }

        if($json_tareas) {
            $api->strJson = json_encode($json_tareas);
            $api->method = "setTareas/";
            $res = $api->sendComprobantes();

            if($res == 1){
                $this->model_db->ejecutarConsulta("DELETE FROM tareas");
                echo "OK";
            }else{
                $response = json_decode($res);
                if(isset($response->error)){
                    echo $response->message;                    
                }else{
                    echo $res; // "Hubo un error. Intente nuevamente";
                }
            }
        }else{
            // No hay cobranzas pendientes de enviar
            echo "OK";
        }
    }
    


}