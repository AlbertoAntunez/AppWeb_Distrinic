<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
{
    /** @var Model_db */
    protected $model_db;

    /** @var Funciones */
    protected $utl;

    /** @var CI_Cart */
    protected $cart;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_db');
        $this->utl = new Funciones();
    }

    function deleteOrder()
    {
        $this->utl->redirectNoLogin();
        $idOrder = $this->input->post("idOrder");

        $this->model_db->eliminar("pedidosItems", "idPedido", $idOrder);
        $this->model_db->eliminar("PEDIDOS", "_id", $idOrder);
    }

    function deleteOrder_product()
    {
        $this->utl->redirectNoLogin();
        $rowid = $this->input->post("rowid");

        if ($this->cart->remove($rowid)) {
            echo $this->utl->getMaskCart();
        } else {
            echo "ERROR";
        }
    }

    // function sendOrders(){
    //     $this->utl->redirectNoLogin();

    //     $api = new Lib_Apiws();     
    //     $rs = $this->model_db->ejecutarConsulta("SELECT a._id,a.codCliente,a.idVendedor,a.fecha,a.totalNeto,a.totalFinal,a.facturar,a.incluirEnReparto, b.idArticulo,b.cantidad,b.importeUnitario,b.porcDescuento,b.total FROM PEDIDOS a LEFT JOIN pedidosItems b on a._id = b.idPedido WHERE a.Transferido=0 ORDER BY a._id", true);

    //     $idPedido = 0; $idPedidoAnt = 0;
    //     $stringJson = "";
    //     $primerProd = false;
    //     $stringJson = ""; $facturar = 'false'; $reparto = 'false';

    //     $json_pedidos = array();

    //     foreach($rs as $row){


    //         $idPedido = $row->_id;
    //         $facturar = ($row->facturar == 1) ? 'true' : 'false'; 
    //         $reparto = ($row->incluirEnReparto == 1) ? 'true' : 'false';

    //         if($idPedido != $idPedidoAnt){
    //             if($stringJson!=""){
    //                 $stringJson.=']'; //Cierro detalle
    //                 $stringJson.='},'; //Cierro pedido
    //             }else{
    //                 $stringJson='[';
    //             }

    //             $stringJson.= '
    //             {
    //                 "idPedido": '.$row->_id.',
    //                 "idCliente":"'.$row->codCliente.'",
    //                 "idVendedor":"'.$row->idVendedor.'",
    //                 "fecha":"'.$row->fecha.'",
    //                 "totalNeto": '.$row->totalNeto.',
    //                 "totalFinal": '.$row->totalFinal.',
    //                 "facturar": '.$facturar.',
    //                 "incluirEnReparto":'.$reparto.',
    //             ';

    //             $pedido = array(
    //                 'idPedido'          => $row->_id,
    //                 'idCliente'         => $row->codCliente,
    //                 'idVendedor'        => $row->idVendedor,
    //                 'fecha'             => $row->fecha,
    //                 'totalNeto'         => $row->totalNeto,
    //                 'totalFinal'        => $row->totalFinal,
    //                 'facturar'          => $facturar,
    //                 'incluirEnReparto'  => $reparto,
    //                 'detallePedido'     => array(),
    //             );

    //             // array_push($json_pedidos, $pedido);

    //             $primerProd = true;
    //         }else{
    //             $primerProd = false;
    //         }

    //         $stringJson.= ($primerProd) ? '"detallePedido": [' : ',';

    //         $stringJson.='
    //         {
    //             "idPedido": '.$row->_id.',
    //             "idArticulo": "'.$row->idArticulo.'",
    //             "cantidad": '.$row->cantidad.',
    //             "importeUnitario": '.$row->importeUnitario.',
    //             "porcDto": '.$row->porcDescuento.',
    //             "total": '.$row->total.'
    //         }
    //         ';

    //         $pitem = array(
    //             'idPedido'          => $row->_id,
    //             'idArticulo'        => $row->idArticulo,
    //             'cantidad'          => $row->cantidad,
    //             'importeUnitario'   => $row->importeUnitario,
    //             'porcDto'           => $row->porcDescuento,
    //             'total'             => $row->total
    //         );

    //         array_push($pedidoItem, $pitem);

    //         $idPedidoAnt = $idPedido;

    //     }

    //     if($stringJson!=""){
    //         $stringJson.=']'; //Cierro detalle
    //         $stringJson.='}'; //Cierro pedido
    //         $stringJson.=']'; //Cierro todo

    //         echo $stringJson;
    //         // $api->strJson = $stringJson;
    //         // $res = $api->sendOrdersAPI();

    //         // if($res == 1){
    //         //     $this->model_db->ejecutarConsulta("DELETE FROM pedidosItems");
    //         //     $this->model_db->ejecutarConsulta("DELETE FROM PEDIDOS");
    //         //     echo "OK";
    //         // }else{
    //         //     echo $res; // "Hubo un error. Intente nuevamente";
    //         // }

    //     }else{
    //         //no hay pedidos o no se procesaron
    //         echo "No hay pedidos pendientes para enviar.";
    //     }
    // }

    function sendOrders()
    {
        $this->utl->redirectNoLogin();

        $api = $this->createApiClient();
        $rs = $this->model_db->ejecutarConsulta("SELECT _id,codCliente,idVendedor,fecha,totalNeto,totalFinal,facturar,incluirEnReparto FROM PEDIDOS WHERE Transferido=0 ORDER BY _id", true);

        $json_pedidos = array();

        foreach ($rs as $row) {

            $pedido = array(
                'idPedido'          => $row->_id,
                'idCliente'         => $row->codCliente,
                'idVendedor'        => $row->idVendedor,
                'fecha'             => $row->fecha,
                'totalneto'         => $row->totalNeto,
                'totalfinal'        => $row->totalFinal,
                'facturar'          => ($row->facturar == 1),
                'incluirenreparto'  => ($row->incluirEnReparto == 1),
                'detallepedido'     => array(),
            );

            $pedidoItems = array();

            $rsItems = $this->model_db->ejecutarConsulta("SELECT * FROM pedidosItems WHERE idPedido=" . $row->_id, true);
            foreach ($rsItems as $rowItem) {
                $pitem = array(
                    'idPedido'          => $row->_id,
                    'idArticulo'        => $rowItem->idArticulo,
                    'cantidad'          => $rowItem->cantidad,
                    'importeUnitario'   => $rowItem->importeUnitario,
                    'porcDto'           => $rowItem->porcDescuento,
                    'total'             => $rowItem->total
                );
                array_push($pedidoItems, $pitem);
            }

            $pedido['detallePedido'] = $pedidoItems;
            array_push($json_pedidos, $pedido);
        }

        if ($json_pedidos) {

            log_message('debug', 'sendOrders - pedidos preparados: ' . print_r($json_pedidos, true));

            $api->strJson = json_encode($json_pedidos);
            $api->method = "setPedidos/";
            $res = $api->sendComprobantes();
            $resString = is_string($res) ? trim($res) : (string) $res;

            log_message('debug', 'sendOrders - respuesta API: ' . $resString);

            if ($resString === '1') {
                $this->model_db->ejecutarConsulta("DELETE FROM pedidosItems");
                $this->model_db->ejecutarConsulta("DELETE FROM PEDIDOS");
                echo "OK";
            } else {
                $response = json_decode($resString);
                if (is_object($response) && isset($response->error)) {
                    log_message('error', 'sendOrders - API devolvió error: ' . $response->message);
                    echo $response->message;
                } elseif ($resString === '0') {
                    $message = 'Hubo un error al enviar los pedidos. Intente nuevamente.';
                    log_message('error', 'sendOrders - API devolvió código de error genérico (0).');
                    echo $message;
                } else {
                    log_message('error', 'sendOrders - respuesta inesperada: ' . $resString);
                    echo $resString; // "Hubo un error. Intente nuevamente";
                }
            }
        } else {
            //no hay pedidos o no se procesaron
            log_message('debug', 'sendOrders - no hay pedidos pendientes para enviar');
            echo "OK"; //"No hay pedidos pendientes para enviar.";
        }
    }

    protected function createApiClient()
    {
        return new Lib_Apiws();
    }

    public function saveOrderOffline()
    {
        $this->utl->redirectNoLogin();

        $p = $this->input->post('pedidos');

        $hayError = false;
        $idOrder = 0;
        $idVdor = $this->session->userdata('codigo');
        $final = json_decode($p);
        $cantidad = count($final);

        $clp = $this->session->userdata('claseP');
        $clp = ($clp == "") ? "1" : $clp;

        date_default_timezone_set(TIMEZONE);
        $fecha = date("Ymdhis");

        foreach ($final as $r) {

            $idOrder = 0;

            $data = array(
                'codCliente'        => $r->codCliente,
                'fecha'             => $fecha,
                'idVendedor'        => $idVdor,
                'totalNeto'         => $r->totalFinal,
                'totalFinal'        => $r->totalFinal,
                'transferido'       => 0,
                'gpsX'              => 0,
                'gpsY'              => 0,
                'facturar'          => 0,
                'claseDePrecio'     => $clp,
            );

            $idOrder = $this->model_db->insertar($data, "PEDIDOS", true);

            if ($idOrder > 0) {
                //INSERTO DETALLE
                foreach ($r->detalle as $d) {
                    $data = array(
                        'idPedido'          => $idOrder,
                        'idArticulo'        => $d->idArticulo,
                        'cantidad'          => $d->cantidad,
                        'importeUnitario'   => $d->importeUnitario,
                        'porcDescuento'     => 0,
                        'total'             => $d->total,
                        'transferido'       => 0,
                    );

                    $error = $this->model_db->insertar($data, "pedidosItems", false);
                    $hayError = ($error != "OK") ? true : false;
                }
            } else {
                //ERROR
                $hayError = true;
            }

            if ($hayError) {
                echo "Hubo un error";
            }
        }

        if (!$hayError) {
            echo "OK";
        } else {
            echo "ERROR";
        }
    }

    public function saveOrder()
    {
        $this->utl->redirectNoLogin();
        $idCustomer = $this->input->post("idCustomer");
        $idOrder = $this->input->post("idOrder");

        $isUpdate = ($idOrder == 0) ? false : true;

        $hayError = false;

        date_default_timezone_set(TIMEZONE);
        $fecha = date("Ymdhis");
        $idVdor = $this->session->userdata('codigo');
        $total = 0;

        foreach ($this->cart->contents() as $row) {
            $total += $row["subtotal"];
        }

        $clp = $this->session->userdata('claseP');
        $clp = ($clp == "") ? "1" : $clp;

        if (!$isUpdate) {
            //Nuevo pedido
            $data = array(
                'codCliente'    => $idCustomer,
                'fecha'         => $fecha,
                'idVendedor'    => $idVdor,
                'totalNeto'     => $total,
                'totalFinal'    => $total,
                'transferido'   => 0,
                'gpsX'          => 0,
                'gpsY'          => 0,
                'facturar'      => 0,
                'claseDePrecio' => $clp,
            );
            $idOrder = $this->model_db->insertar($data, "PEDIDOS", true);
        } else {
            $this->model_db->ejecutarConsulta("DELETE FROM pedidosItems where idPedido=" . $idOrder, false);

            $dataCab = array(
                'totalNeto'     => $total,
                'totalFinal'    => $total,
                'claseDePrecio' => $clp,
            );

            $res = $this->model_db->actualizar($dataCab, "PEDIDOS", "_id", $idOrder);
        }

        if ($idOrder > 0) {
            //INSERTO DETALLE
            foreach ($this->cart->contents() as $row) {
                $data = array(
                    'idPedido'          => $idOrder,
                    'idArticulo'        => $row["id"],
                    'cantidad'          => $row["qty"],
                    'importeUnitario'   => $row["price"],
                    'porcDescuento'     => 0,
                    'total'             => $row["subtotal"],
                    'transferido'       => 0,
                );

                $error = $this->model_db->insertar($data, "pedidosItems", false);

                $hayError = ($error != "OK") ? true : false;
            }
        } else {
            $hayError = true;
        }

        if ($hayError) {
            echo "Hubo un error";
        } else {
            echo ""; //OK
        }
    }

    function history()
    {

        $data['customers'] = $this->model_db->getCustomers(5000);
        $this->load->view('templates/header', $data);
        $this->load->view('public/order_history');
        $this->load->view('templates/footer');
    }

    function getHistoryCpte()
    {
        $key = $this->input->post("key");
        $datos = explode("|", $key);

        $tc = $datos[0];
        $idcpte = $datos[1];

        $api = new Lib_Apiws();
        $api->method = "getPedidoDetalle/" . $tc . "/" . $idcpte;
        $res = $api->consumeWebService();

        $cantidad = 0;

        if ($res != '[]') {
            $final = json_decode($res);
            if (isset($final->error)) {
                echo '
                    <p class="text-center bg-danger">
                        ' . $final->message . '
                    </p>
                ';
                exit();
            }

            $cantidad = count($final);
        }



        if ($cantidad == 0) {
            echo '<p class="text-center">Sin resultados</p>';
        }

        $paso = false;
        $mask = '';
        $importeTotal = 0;

        foreach ($final as $row) {
            $fecha = "";

            if (!$paso) {
                $paso = true;
                $fecha = $row->fecha;
                date_default_timezone_set(TIMEZONE);
                $fecha = strftime("%d/%m/%G", strtotime($fecha));

                $mask = '
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-8">
                            <p>' . $tc . ' ' . $idcpte . '</p>
                        </div>

                        <div class="col-xs-4">
                            <small>' . $fecha . '</small>
                        </div>

                        <div class="col-xs-12">
                            <p>(' . $row->cuenta . ') ' . $row->nombre . '</p>
                        </div>
                    </div>

                    <div class="row" style="border-bottom:1px solid #e1e1e1;">
                        <div class="col-xs-12">
                            <h4>Detalle</h4>
                        </div>
                    </div>
                ';

                $importeTotal = $row->totalcpte;
            }

            //Por cada articulo devuelto
            $mask .= '
            <div class="row" style="border-bottom: 1px solid #e1e1e1; padding:10px 0;">
                <div class="col-xs-12">
                    <small>' . $row->idarticulo . ' - ' . $row->descripcion . '</small>
                </div>
                <div class="col-xs-6">
                    <small>' . $row->cantidad . ' x $ ' . $row->importe . '</small>                   
                </div>
                <div class="col-xs-6 text-right">
                    <small>$ ' . $row->total . '</small>                    
                </div>
            </div>
            ';
        }

        if ($mask != '') {

            $mask .= '
                <br />
                <div class="row">
                    <div class="col-xs-4">
                    <b>Total : </b>
                    </div>
                    <div class="col-xs-8 text-right">
                    <b>$ ' . $importeTotal . '</b>
                    </div>
                </div>
            ';

            $mask .= "</div>";
        }

        echo $mask;
    }

    function searchOrderHistory()
    {
        $this->utl->redirectNoLogin();
        $idCustomer = $this->input->post("idCustomer");
        $fhd = $this->input->post("fhd");
        $fhh = $this->input->post("fhh");

        $idCustomer = ($idCustomer == '') ? '-1' : $idCustomer;

        date_default_timezone_set(TIMEZONE);

        $fhd = ($fhd != '') ? strftime("%G%m%d", strtotime($fhd)) : date("Ymd");
        $fhh = ($fhh != '') ? strftime("%G%m%d", strtotime($fhh)) : date("Ymd");

        var_dump("getPedidos/" . $this->session->userdata('codigo') . "/" . $fhd . "/" . $fhh . "/" . $idCustomer);

        $api = new Lib_Apiws();
        $api->method = "getPedidos/" . $this->session->userdata('codigo') . "/" . $fhd . "/" . $fhh . "/" . $idCustomer;
        $res = $api->consumeWebService();

        $cantidad = 0;

        if ($res != '[]') {
            $final = json_decode($res);
            if (isset($final->error)) {
                echo '
                    <p class="text-center bg-danger">
                        ' . $final->message . '
                    </p>
                ';
                exit();
            } else {
                $cantidad = count($final);
            }
        }


        if ($cantidad == 0) {
            echo '<p class="text-center">Sin resultados</p>';
        }

        $mask = '';
        foreach ($final as $row) {
            $fecha = "";

            $fecha = $row->fecha;
            date_default_timezone_set(TIMEZONE);
            $fecha = strftime("%d/%m/%G", strtotime($fecha));

            $mask .= '
            <div class="col-xs-12 item-historial">
                <div class="col-xs-2 col-xs-offset-0">
                    <img style="max-width:100%;" alt="sent order" src="' . base_url('assets/img/icon/sent-order.svg') . '" >
                </div>
                <div class="col-xs-10">    
                    <div class="col-xs-12">
                        <p class="title_customer">
                            <a href="javascript:void(0);" name="' . $row->tc . '|' . $row->idcomprobante . '" class="item_historyOrder">' . $row->razon . '</a>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <small>' . $fecha . '</small>
                    </div>

                    <div class="col-xs-6 text-right">
                        <small>$' . $row->totalFinal . '</small>
                    </div>
                </div>
            </div>
            ';
        }
        echo $mask;
    }
}
