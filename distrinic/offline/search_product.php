<?php
	require_once('../configuracion.php');
	require_once('conn.php');
	require_once('Funciones.php');

	$paso = false;
	if(isset($_GET["query"])){
		$query = $_GET["query"];
    	$preSale = $_GET["preSale"];	
		$paso = true;
	}
	
    if(!$paso){
    	return;
    }

    if($query == ""){
        $response = getProducts($conn);
    }else{
        $response = getProductsByQuery($query, $conn);
    }

    $res = getMaskListProducts($response, $preSale);

    //print_r($response);
    echo $res;

    function getProducts($conn){

	    $artsPorConsulta = (!defined('ARTS_POR_CONSULTA')) ? "" : ARTS_POR_CONSULTA;
	    if($artsPorConsulta == "" || $artsPorConsulta == 0) { $artsPorConsulta = 20; }

	    $clp = ""; //$this->session->userdata('claseP');
	    $clp = ($clp == "") ? "precio1" : "precio".$clp;

	    $query = $conn->query("SELECT idArticulo,descripcion,".$clp." as precio FROM ARTICULOS ORDER BY descripcion ASC LIMIT 0,".$artsPorConsulta);

	    return $query;

	   }

	function getProductsByQuery($b, $conn){

		$clp = ""; //$this->session->userdata('claseP');
		$clp = ($clp == "") ? "precio1" : "precio".$clp;

		$artsPorConsulta = (!defined('ARTS_POR_CONSULTA')) ? "" : ARTS_POR_CONSULTA;
		if($artsPorConsulta == "" || $artsPorConsulta == 0) { $artsPorConsulta = 20; }

		$query = $conn->query("SELECT idArticulo,descripcion,".$clp." as precio FROM ARTICULOS WHERE descripcion like '%".$b."%' ORDER BY descripcion ASC LIMIT 0,".$artsPorConsulta);
		return $query;

  	}
  ?>