<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funciones{

	var $nro=0;

	function __construct(){
		$this->ci =& get_instance();
	}


	function array_sort_by(&$arrIni, $col, $order = SORT_ASC) {
		$arrAux = array();
		foreach ($arrIni as $key=> $row)
		{
			$arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
			$arrAux[$key] = strtolower($arrAux[$key]);
		}
		array_multisort($arrAux, $order, $arrIni);
	}

	public function agregaCeros($numero,$cantCeros=1){
		$len = 0;
		$numero = intval($numero);
		$len = strlen($numero);

		$tmp = "";
		
		for($i=0;$i<($cantCeros-$len);$i++){
			$tmp.="0";
		}

		return $tmp.$numero;
	}

	public function numeroRandom($parametro=0){
		if($parametro != 1){
			$id = rand();
			$this->nro = $id;
		}
		return $this->nro;
	}

	public function logueado(){
		if(!$this->ci->session->userdata('logueado')){
			return false;
		}else{
			return true;
		}
	}

	public function redirectNoLogin(){
		if(!$this->ci->session->userdata('logueado')){
            header("Location: ".base_url());
            die();
        }
	}

	function verificaBarra($string){
		if(substr($string, -1) != "/" ){
			return "/";
		}else{
			return "";
		}
	}

	function cleanCart(){
        $this->redirectNoLogin();
        $this->ci->cart->destroy();

        if($this->ci->cart->total_items() == 0){
            echo true;
        }else{
            echo false;
        }
    }

	function getMaskCart(){

		$noMuestraTotales = (defined('NOMUESTRA_TOTALES_PEDIDOS')) ? NOMUESTRA_TOTALES_PEDIDOS : "0";
		$showImages = (defined('MUESTRA_IMAGENES_PRODUCTOS')) ? MUESTRA_IMAGENES_PRODUCTOS : "0";

		$colUnitario = '3';
		$coloff = '4';
		$coltot = '3';
		$colDesc = 'col-xs-12';

		if($showImages == '1'){
			$colDesc = 'col-xs-10';
			$coloff = '3';
			$coltot = '4';
		}

        $mask = "";

        if($noMuestraTotales == '1'){
        	$colUnitario = '4';
        	$coloff = '6';
        	$mask = '
	        <div class="row cab-order-products">
	            <div class="col-xs-6 text-right"><span>PROD</span></div>
	            <div class="col-xs-2 text-right"><span>CANT</span></div>
	            <div class="col-xs-4 text-right"><span>UNIT</span></div>
	        </div>
	        ';
        }else{
	        $mask = '
	        <div class="row cab-order-products">
	            <div class="col-xs-4 text-right"><span>PROD</span></div>
	            <div class="col-xs-2 text-right"><span>CANT</span></div>
	            <div class="col-xs-3 text-right"><span>UNIT</span></div>
	            <div class="col-xs-3 text-right"><span>TOTAL</span></div>
	        </div>
	        ';
        }
        
        $totalFinal = 0; $items = 0;
        foreach ($this->ci->cart->contents() as $row) {
            $desc = $this->ci->model_db->getProductDescription($row["id"]);
            $totalFinal+=$row["subtotal"];
            $items++;

            $mask .= '<div class="row">';

			if($showImages == '1'){
				$mask.='	
					<div class="col-xs-2">
						<img style="max-width:100%; height:auto; max-height:40px;" src="'.$this->getImageProduct($row["id"]).'" />
					</div>
				';
			}

			$mask.='		
				<div class="'.$colDesc.'" id="del_product_order" name="'.$row["rowid"].'">
					<a href="javascript:void(0);" class="del_product_order" name="'.$row["rowid"].'">'
					.$desc.
					'</a>
				';


			$mask.='
					<div class="row detail-order-products">
	                    <div class="col-xs-2 text-right col-xs-offset-'.$coloff.'"><span>'.$row["qty"].'</span></div>
	                    <div class="col-xs-'.$colUnitario.' text-right"><span>'.number_format($row["price"], 2).'</span></div>
	                ';

	        if($noMuestraTotales == '0'){
	        	$mask.='<div class="col-xs-'.$coltot.' text-right"><span>'.number_format($row["subtotal"], 2).'</span></div>';
	        }

            $mask.='
                	</div>		
            		';
	        
	        $mask.='
				</div>					
			</div>                
            ';
        }

        $mask.= '
            <div class="row div-tot-order">
                <div class="col-xs-4">Items: '.$items.'</div>
            ';

            if($noMuestraTotales == '0'){
            	$mask.='
	                <div class="col-xs-3 text-right">Total :</div>
	                <div class="col-xs-5 text-right">
	                $ '.number_format($totalFinal, 2).'
	                </div>
	        	';
        	}
        $mask.='
            </div>
        ';

        echo $mask;
	}

	function getMaskListProducts_PriceList($products){
		$desc = "";
		$mask = "";
		$showImages = (defined('MUESTRA_IMAGENES_PRODUCTOS')) ? MUESTRA_IMAGENES_PRODUCTOS : "0";
		$stockOnlineConsulta = (defined('STOCK_ONLINE_CONSULTAS')) ? STOCK_ONLINE_CONSULTAS : "0";

		if(!empty($products)): foreach($products as $row):
			
			$desc = $row->descripcion;
			$desc = ($desc == "") ? "Articulo sin descripción" : $desc; 
		
			if(strlen($desc) > 38){
				$desc = substr($desc, 0, 38) . "...";
			}
		
		$mask .= '
		<div class="col-xs-12 f-product">
			<div class="col-xs-2">
		';

		if($showImages == '1'){
			$mask.='<img alt="product" src="'.$this->getImageProduct($row->idArticulo).'" />';
		}else{
			$mask.='<img alt="barcode" src="'.base_url('assets/img/icon/barcode.svg').'" />';
		}
		
		$mask.='
			</div>
			<div class="col-xs-10 col-name-product">
				<p class="nameProduct">';

			
		$mask.= '<a href="'.base_url('products/view/'.$row->idArticulo).'" class="product">';
			

		$mask .= $desc.'
					</a>
				</p>
			';
		if($stockOnlineConsulta == "1"){
			$mask.='
			<div class="col-xs-5 col-id-product">
				<small>#'.$row->idArticulo.'</small>
			</div>
			<div class="col-xs-3 col-id-product">
				<small>'.$this->getStockReal($row->idArticulo).'</small>
			</div>
			<div class="col-xs-4 col-price-product">
				<small>$'.$row->precio.'</small>
			</div>
			';
		}else{
			$mask.='
			<div class="row">
			<div class="col-xs-8 col-id-product">
				<small>#'.$row->idArticulo.'</small>
			</div>
			<div class="col-xs-4 col-price-product">
				<small>$'.$row->precio.'</small>
			</div>
			</div>
			';
		}
		
		$mask.='
			</div>
		</div>
		';
		endforeach; else:
			$mask = '<p class="text-center">Sin datos</p>';
		endif;

		return $mask;
	}
	
	function getMaskListProducts($products, $preSale){

		$desc = "";
		$mask = "";
		$showImages = (defined('MUESTRA_IMAGENES_PRODUCTOS')) ? MUESTRA_IMAGENES_PRODUCTOS : "0";
		$stockOnlineConsulta = (defined('STOCK_ONLINE_CONSULTAS')) ? STOCK_ONLINE_CONSULTAS : "0";

		if(!empty($products)): foreach($products as $row):
			
			$desc = $row->descripcion;
			$desc = ($desc == "") ? "Articulo sin descripción" : $desc; 
		
			if(strlen($desc) > 38){
				$desc = substr($desc, 0, 38) . "...";
			}
		
		$mask .= '
		<div class="col-xs-12 f-product">
			<div class="col-xs-2">
		';

		if($showImages == '1'){
			$mask.='<img alt="product" src="'.$this->getImageProduct($row->idArticulo).'" />';
		}else{
			$mask.='<img alt="barcode" src="'.base_url('assets/img/icon/barcode.svg').'" />';
		}
		
		$mask.='
			</div>
			<div class="col-xs-10 col-name-product">
				<p class="nameProduct">';

			if($preSale){
				$mask.= '<a href="javascript:void(0);" id="a_prod_preSale" name="'.$row->idArticulo.'" class="product">';
			}else{
				$mask.= '<a href="'.base_url('products/view/'.$row->idArticulo).'" class="product">';
			}

		$mask .= $desc.'
					</a>
				</p>
			';
		if($stockOnlineConsulta == "1"){
			$mask.='
			<div class="col-xs-5 col-id-product">
				<small>#'.$row->idArticulo.'</small>
			</div>
			<div class="col-xs-3 col-id-product">
				<small>'.$this->getStockReal($row->idArticulo).'</small>
			</div>
			<div class="col-xs-4 col-price-product">
				<small>$'.$row->precio.'</small>
			</div>
			';
		}else{
			$mask.='
			<div class="row">
			<div class="col-xs-8 col-id-product">
				<small>#'.$row->idArticulo.'</small>
			</div>
			<div class="col-xs-4 col-price-product">
				<small>$'.$row->precio.'</small>
			</div>
			</div>
			';
		}
		
		$mask.='
			</div>
		</div>
		';
		endforeach; else:
			$mask = '<p class="text-center">Sin datos</p>';
		endif;

		return $mask;
	}

	function getStockReal($id){
		$api = new Lib_Apiws();
		
		$api->method = "getStockD/".$id;
		$response = $api->consumeWebService();
		$stockcomprmasreal = (defined('STOCK_COMPROMETIDO_MAS_REAL')) ? STOCK_COMPROMETIDO_MAS_REAL : "0";
		//echo SERVER_API.$api->verificaBarra(SERVER_API).$api->method;

		$res = json_decode($response);
		
		if(isset($res->error)) {
			return '<span class="bg-danger">'.$res->message.'<span>';
			exit();
		}

		$comprometido = 0;
		$stock = 0;

		if(!empty($res)): foreach($res as $row):

            switch($row->idDeposito){
                case "@REAL":
                    $stock = number_format($row->stock, 2);
                    break;
                case "@COMPROMETIDO":
                	$comprometido = number_format($row->stock, 2);
                	break;
                default:
					//$stock = 1;
                    break;
			}
			
			
		endforeach; else:
			return 2;
		endif;

		$stkReturn = 0;
		//Hacer configuracion para que si el stock es negativo, muestre 0 (asi lo usa distrinic)
		if($stockcomprmasreal == "1"){
			$stkReturn = number_format($stock - $comprometido, 2);
		}else{
			$stkReturn = $stock;
		}

		if($stkReturn < 0){
			$stkReturn = 0;
		}

		return $stkReturn;
	}

	function getImageProduct($id){
		
		$rutaImg = (defined('RUTA_IMAGENES_PRODUCTOS')) ? RUTA_IMAGENES_PRODUCTOS : "";

		if($rutaImg == ''){
			// Tomo imagen local
			if(file_exists(FCPATH.'assets/images/thumbs/'.$id.'.gif')){
				return base_url('assets/images/thumbs/'.$id.'.gif');					
			}else{
				return base_url('assets/img/alfaicon.png');
			}
		}else{
			
			if($this->remote_file_exists($rutaImg.$id.'.gif')){
				return $rutaImg.$id.'.gif';
			}else{
				return base_url('assets/img/alfaicon.png');
			}
		}
	}

	function remote_file_exists($file){
		$ch = curl_init($file);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($retcode==200 || $retcode==302){
			return true;
		}else{
			return false;
		}
	}

}