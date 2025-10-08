<?php

use PHPUnit\Framework\TestCase;

class FakeUtility
{
    public $redirectCalled = false;

    public function redirectNoLogin()
    {
        $this->redirectCalled = true;
    }
}

class FakeModelDb
{
    private $orders;
    private $items;
    public $deletedTables = array();

    public function __construct(array $orders, array $items)
    {
        $this->orders = $orders;
        $this->items = $items;
    }

    public function ejecutarConsulta($sql, $return = true)
    {
        $normalized = ltrim($sql);

        if (stripos($normalized, 'DELETE FROM pedidosItems') === 0) {
            $this->deletedTables[] = 'pedidosItems';
            return 'OK';
        }

        if (stripos($normalized, 'DELETE FROM PEDIDOS') === 0) {
            $this->deletedTables[] = 'PEDIDOS';
            return 'OK';
        }

        if (stripos($normalized, 'FROM PEDIDOS WHERE') !== false) {
            return $this->orders;
        }

        if (stripos($normalized, 'FROM pedidosItems') !== false) {
            $orderId = 0;
            if (preg_match('/idPedido\s*=\s*(\d+)/i', $normalized, $matches)) {
                $orderId = (int) $matches[1];
            }

            return isset($this->items[$orderId]) ? $this->items[$orderId] : array();
        }

        return array();
    }
}

class FakeApiClient
{
    public $strJson = '';
    public $method = '';
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function sendComprobantes()
    {
        return $this->response;
    }
}

class OrderTestController extends Order
{
    private $apiClient;

    public function __construct($model, $apiClient, $utility)
    {
        $this->model_db = $model;
        $this->apiClient = $apiClient;
        $this->utl = $utility;
    }

    protected function createApiClient()
    {
        return $this->apiClient;
    }
}

class OrderSendOrdersTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['test_log_messages'] = array();
    }

    private function createOrderRow($overrides = array())
    {
        $defaults = array(
            '_id' => 10,
            'codCliente' => 'C001',
            'idVendedor' => 'V001',
            'fecha' => '20230101123045',
            'totalNeto' => 150.5,
            'totalFinal' => 160.5,
            'facturar' => 1,
            'incluirEnReparto' => 0,
        );

        return (object) array_merge($defaults, $overrides);
    }

    private function createItemRow($overrides = array())
    {
        $defaults = array(
            'idPedido' => 10,
            'idArticulo' => 'A001',
            'cantidad' => 3,
            'importeUnitario' => 50.17,
            'porcDescuento' => 0,
            'total' => 150.51,
        );

        return (object) array_merge($defaults, $overrides);
    }

    public function testSendOrdersSuccessClearsTablesAndOutputsOk()
    {
        $orders = array($this->createOrderRow());
        $items = array(
            10 => array(
                $this->createItemRow(),
            ),
        );

        $model = new FakeModelDb($orders, $items);
        $api = new FakeApiClient('1');
        $utility = new FakeUtility();

        $controller = new OrderTestController($model, $api, $utility);

        ob_start();
        $controller->sendOrders();
        $output = ob_get_clean();

        $this->assertTrue($utility->redirectCalled);
        $this->assertSame('OK', $output);

        $payload = json_decode($api->strJson, true);
        $this->assertCount(1, $payload);
        $this->assertEquals('setPedidos/', $api->method);
        $this->assertTrue($payload[0]['facturar']);
        $this->assertFalse($payload[0]['incluirenreparto']);
        $this->assertContains('pedidosItems', $model->deletedTables);
        $this->assertContains('PEDIDOS', $model->deletedTables);
    }

    public function testSendOrdersReturnsFriendlyMessageWhenApiReturnsZero()
    {
        $orders = array($this->createOrderRow());
        $items = array(10 => array($this->createItemRow()));

        $model = new FakeModelDb($orders, $items);
        $api = new FakeApiClient('0');
        $utility = new FakeUtility();

        $controller = new OrderTestController($model, $api, $utility);

        ob_start();
        $controller->sendOrders();
        $output = ob_get_clean();

        $this->assertSame('Hubo un error al enviar los pedidos. Intente nuevamente.', $output);
        $this->assertEmpty($model->deletedTables);
    }

    public function testSendOrdersPassesThroughErrorMessageFromApi()
    {
        $orders = array($this->createOrderRow());
        $items = array(10 => array($this->createItemRow()));

        $model = new FakeModelDb($orders, $items);
        $errorResponse = json_encode(array('error' => true, 'message' => 'Error remoto'));
        $api = new FakeApiClient($errorResponse);
        $utility = new FakeUtility();

        $controller = new OrderTestController($model, $api, $utility);

        ob_start();
        $controller->sendOrders();
        $output = ob_get_clean();

        $this->assertSame('Error remoto', $output);
        $this->assertEmpty($model->deletedTables);

        $this->assertNotEmpty($GLOBALS['test_log_messages']);
        $lastLog = end($GLOBALS['test_log_messages']);
        $this->assertEquals('error', $lastLog['level']);
    }
}
