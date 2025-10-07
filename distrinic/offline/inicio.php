<!--<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 

  <title>Alfa - Mobile</title>

  <meta name="description" content="Bienvenidos a AlfaSysmobile, donde podrás cargar tus pedidos estes donde estes y sincronizarlos 
  con tu sistema Alfa Gestion">
  <base href=""/>

  <meta name="theme-color" content="#1B6CD3">
  <meta name="MobileOptimized" content="width">
  <meta name="HandheldFriendly" content="true">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <link rel="apple-touch-icon" href="./assets/img/alfaicon.png">
  <link rel="apple-touch-startup-image" href="./assets/img/alfaicon.png">

  <link rel="manifest" href="./manifest.json">



  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
  <meta http-equiv="pragma" content="no-cache" />

  <link rel="stylesheet" type="text/css" href="./assets/css/estilo.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/estilo_iphone.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/loader.css">
  <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
-->

<?php
  include_once('header.php');
?>

<div class="container">
  <div class="row cabeceraMain">

    <div class="col-xs-10">
      <div class="titulo_cab">
        <p>Alfa Net - SysMobile <small> OFFLINE </small></p>
      </div>
    </div>

    <div class="col-xs-2">
      <div class="configItem">
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 text-center bg-danger">
      <p>Modo OFFLINE</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="divMnuPrincipal">
        <ul class="mnuPrincipal">
          <li><a href="list_orders.php"><img alt="order" src="../assets/img/icon/order.svg">&nbsp;<span>Pedidos</span></a></li>
          <li><a href="articulos.php"><img alt="barcode" src="../assets/img/icon/barcode.svg">&nbsp;<span>Articulos</span></a></li>
          <li><a href="#"><img alt="customers" src="../assets/img/icon/customers.svg">&nbsp;<span>Clientes</span></a></li>
          <li><a href="https://wa.me/+5491151480148" target="_blank"><img alt="whatsapp" src="../assets/img/icon/whatsapp.svg">&nbsp;<span>Soporte</span></a></li>
          <li><a href="../" ><img alt="exit" src="../assets/img/icon/cancel.svg">&nbsp;<span>Salir</span></a></li>               
        </ul>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 text-right">

    </div>
  </div>

</div>

<!--
</body>
</html>
-->
<?php
  include_once('footer.php');
?>