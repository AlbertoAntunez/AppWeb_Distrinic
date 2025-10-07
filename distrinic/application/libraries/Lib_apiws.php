<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_Apiws{
    protected $ci;
    public $method;
    public $strJson;

    function consumeWebService(){
        /*
        Consume métodos GET de API
        */
        try {
            set_time_limit(60);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('API_KEY: '.API_KEY));
            curl_setopt($ch, CURLOPT_URL, SERVER_API.$this->_verificaBarra(SERVER_API).$this->method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $res = "";

            if(($res = curl_exec($ch)) === false){
                // $response = "Error en la conexión con el servidor";
                $response = $this->_errorResponse("Error en la conexión con el servidor");
            }else{
                $response = $res;
            }
            curl_close($ch);
        } catch(Exception $e) {
            $response = $this->_errorResponse("Error al comunicarse con el web service.\n" . $e->getMessage() . "\n");
            // $response = "Error al comunicarse con el web service.\n" . $e->getMessage() . "\n";
        }

		return $response;
		
    }

    public function sendComprobantes() {
        /*
        Consume métodos POST de API
        Envia los comprobantes al servidor.
        $this->method = saveCobranza    | Envia la cobranza
        $this->method = setPedidos      | Envio los pedidos
        */

        if($this->strJson == "" || $this->method == ""){
            return "";
        }

        try {
            $url = SERVER_API.$this->_verificaBarra(SERVER_API).$this->method;
            $ch = curl_init($url);

            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('API_KEY: '.API_KEY));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->strJson);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = array();
            $headers[0] = 'Content-Type: application/json';
            $headers[1] = 'API_KEY: '.API_KEY;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // $response = curl_exec($ch);

            if(($res = curl_exec($ch)) === false){
                // $response = "Error en la conexión con el servidor";
                $response = $this->_errorResponse("Error en la conexión con el servidor");
            }else{
                $response = $res;
            }

            curl_close($ch);
        } catch(Exception $e) {
            $response = $this->_errorResponse("Error al enviar comprobante(s).\n" . $e->getMessage() ."\n");
            // $result = "Error al enviar comprobante(s).\n" . $e->getMessage() ."\n";
        }

        return $response;
    }

    private function _errorResponse($msg) {
        return json_encode(array(
                'error'     => true,
                'message'   => $msg    
            ));
    }

    private function _verificaBarra($string){
        return substr($string, -1) != "/" ? "/" : "";
	}

    // function sendCobranzas() {
    //     if($this->strJson == ""){
    //         return "";
    //     }

    //     try {
    //         $url = SERVER_API.$this->verificaBarra(SERVER_API).'saveCobranza'; //'http://localhost:57112/api/setPedidos/';
    //         $ch = curl_init($url);

    //         curl_setopt($ch, CURLOPT_POST, TRUE);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $this->strJson);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //         $headers = array();
    //         $headers[] = 'Content-Type: application/json';
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //         $result = curl_exec($ch);

    //         curl_close($ch);
    //     } catch(Exception $e){
    //         $result = "Error al guardar la cobranza.\n" . $e->getMessage() ."\n";
    //     }

    //     return $result;
    // }

    // function sendOrdersAPI(){
    //     if($this->strJson == ""){
    //         return "";
    //     }

	// 	$url = SERVER_API.$this->verificaBarra(SERVER_API).'setPedidos/'; //'http://localhost:57112/api/setPedidos/';
    //     $ch = curl_init($url);

    //     curl_setopt($ch, CURLOPT_POST, TRUE);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $this->strJson);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     $headers = array();
    //     $headers[] = 'Content-Type: application/json';
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //     $result = curl_exec($ch);

    //     curl_close($ch);

    //     return $result;
	// }
    
    
}

?>