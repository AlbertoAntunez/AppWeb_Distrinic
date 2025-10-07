<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('model_db'); 
        $this->utl = new Funciones(); 
        $this->load->library('pdf'); 
    }

    public function index() {
        //Muestro la pantalla de cobranzas
        $mask = '';

        // $data['local_pay'] = $this->model_db->getPayments();
        $data['customers'] = $this->model_db->getCustomers(5000);

        
        $datos = $this->model_db->getCobranzas();
        $mask .= $this->_getMaskPyaments($datos);

        if($mask != ''){
            $mask = '<div class="col-xs-12"><p class="bg-warning text-center">Pendientes de sincronización</p></div>' . $mask;
        }

        $data['cobranzas_pendientes'] = $mask;
        
        $this->load->view('templates/header',$data);
        $this->load->view('public/payments/payments');
        $this->load->view('templates/footer');
    }


    public function new() {
        /*
        Pantalla para nueva cobranza.
        Puede recibir por get 2 parametros
        opcion (determina si viene de ruta_diaria)
        cuenta (carga la cuenta ingresada)
        */
        if(isset($_GET['opcion'])){
            if($_GET['opcion'] == 'ruta'){
                $data['rutaAnt'] = base_url('vendors/ruta_diaria');
            }
        } else {
            $data['rutaAnt'] = base_url('payments');
        }

        $data['customers']      = $this->model_db->getCustomers(5000);
        $data['cuenta_sel']     = isset($_GET['cuenta']) ? $_GET['cuenta'] : ''; 
        $data['error']          = false;
        $data['medios_pago']    = $this->model_db->getMediosPago();

        $this->load->view('templates/header',$data);
        $this->load->view('public/payments/payments_new');
        $this->load->view('templates/footer');
    }

    public function save() {
        $this->utl->redirectNoLogin();

        $fecha      = $this->input->post("fecha");
        $cuenta     = $this->input->post("cuenta");
        $mp         = $this->input->post("mp");
        $importe    = $this->input->post("importe");
        $obs        = $this->input->post("obs");
        $tc         = $this->input->post("tc");

        $mp = (empty($mp) || $mp == "") ? "E" : $mp; //Por defecto tomo efectivo

        if(empty($fecha) || empty($cuenta) || empty($importe)) {
            echo "Debe ingresar todos los datos";
            exit();
        }

        date_default_timezone_set(TIMEZONE);
        $id_vendedor = $this->session->userdata('codigo');

        $data = array(
            'fecha'         => $fecha,
            'cuenta'        => $cuenta,
            'mp'            => $mp,
            'importe'       => $importe,
            'observacion'   => $obs,
            'idvendedor'    => $id_vendedor,
            'tc'            => $tc 
        );

        $res = $this->model_db->insertar($data, "cobranzas", false);
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

    public function delete(){
        $this->utl->redirectNoLogin();

        $id = $this->input->post("id");

        if($id > 0) {
            $res = $this->model_db->eliminar("cobranzas", "_id", $id);
            
            echo True;
        }else{
            echo False;
        }

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
        $mask .= $this->_getMaskPyaments($data);

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
            $mask .= $this->_getMaskPyaments($final, false);
        }
        echo $mask;

    }

    private function _getMaskPyaments($data, $local = true) {
        $mask   = '';
        $total  = 0;

        foreach($data as $row){
            $fecha = '';
            
            $razon = '';

            $total += $row->totalFinal;

            if(isset($row->razon)){
                $razon = $row->razon;
            }else{
                $razon = $this->model_db->getCustomerName($row->cuenta);
            }

            $fecha = $row->fecha;
            date_default_timezone_set(TIMEZONE);
            $fecha = strftime("%d/%m/%G", strtotime($fecha));

            $mask .= '
            
            <div class="col-xs-12 item-historial">
                <div class="col-xs-2 col-xs-offset-0">
                    <img style="max-width:100%;" alt="sent order" src="'.base_url('assets/img/icon/payment.svg').'" >
                </div>
                <div class="col-xs-10">    
                    <div class="col-xs-12">
                        <p class="title_customer">
                            <a href="#" id="'.$row->id.'" class="item_payment">'.$razon.'</a>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <small>'.$fecha.' ('.$row->tc.')</small>
                    </div>

                    <div class="col-xs-6 text-right">
                        <small>$'.$row->totalFinal.'</small>
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
                <b>$ '.number_format($total,2).'</b>
            </div>
            <div class="col-xs-1">
            </div>';
        }

        return $mask;
    }

    
    public function sendPayments() {
        $this->utl->redirectNoLogin();

        $api = new Lib_Apiws();
        $rs = $this->model_db->ejecutarConsulta("SELECT _id, tc, fecha, cuenta, importe, mp, observacion, idvendedor FROM cobranzas ORDER BY _id", true);
    
        $json_cobranzas = array();

        foreach($rs as $row) {

            $cobranza = array(
                'fecha'         => $row->fecha,
                'cuenta'        => $row->cuenta,
                'mp'            => $row->mp,
                'importe'       => $row->importe,
                'observacion'   => $row->observacion,
                'idvendedor'    => $row->idvendedor,
                'tc'            => $row->tc
            );

            array_push($json_cobranzas, $cobranza);
        }

        if($json_cobranzas) {
            $api->strJson = json_encode($json_cobranzas);
            $api->method = "setCobranzas/";
            $res = $api->sendComprobantes();

            if($res == 1){
                $this->model_db->ejecutarConsulta("DELETE FROM cobranzas");
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