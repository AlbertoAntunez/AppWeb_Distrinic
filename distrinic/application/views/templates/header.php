<!DOCTYPE html>
<html lang="es">
<head>
	<?php header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 

	<title>Alfa - Mobile</title>

	<meta name="description" content="Bienvenidos a AlfaSysmobile, donde podrás cargar tus pedidos estes donde estes y sincronizarlos 
	con tu sistema Alfa Gestion">
	<base href=""/>
	<!-- WPA -->
	<meta name="theme-color" content="#1B6CD3">
	<meta name="MobileOptimized" content="width">
	<meta name="HandheldFriendly" content="true">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

	<link rel="shortcut icon" type="image/png" href="./assets/img/favicon.png">
	<link rel="apple-touch-icon" href="./assets/img/alfaicon.png">
	<link rel="apple-touch-startup-image" href="./assets/img/alfaicon.png">

	<link rel="manifest" href="./manifest.json">

	<!-- END WPA -->

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/estilo.css?modified=<?php echo filemtime("./assets/css/estilo.css");?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/estilo_iphone.css?modified=<?php echo filemtime("./assets/css/estilo_iphone.css");?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/loader.css">
	<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">-->
	<link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<style>
		.loaderWeb{
		    position: fixed;
		    left: 0px;
		    top: 0px;
		    width: 100%;
		    height: 100%;
		    z-index: 9999;
		    background: url('<?=base_url('assets/img/ajax-loader.gif');?>') 50% 50% no-repeat rgb(249,249,249);
		    opacity: .8;
		}
	</style>

	<!-- Select con busqueda -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
	<!-- Fin select busqueda -->

	<!-- <script src="<?php echo base_url(); ?>assets/js/config.js"></script> -->
</head>

<body>
	<?php
	date_default_timezone_set(TIMEZONE);
	?>
	<div class="loaderWeb"></div>