<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/*class configuracion_helper{
	protected $ci;

	public function __construct(){
		$this->ci =& get_instance();
		$this->ci->load->model('model_db'); 

		$this->ci->config->set_item('COND_IVA', $this->ci->model_db->cfg("cond_iva"));
		$this->ci->config->set_item('NOMBRE_EMPRESA', $this->ci->model_db->cfg("nombre"));
 		
		$this->ci->config->set_item('CUENTA_EF', "98");
		//echo $this->config->item('COND_IVA');
	}*/


if(!function_exists('cfg')) {
	function cfg($clave){
		$ci =& get_instance();
		$ci->load->model('model_db','mdb');

		$valor = $ci->mdb->cfg($clave);

		return $valor;
	}
}

if(!function_exists('grabaCfg')){
	function grabaCfg($clave, $valor){
		$ci =& get_instance();
		$ci->load->model('model_db','mdb');

		$ci->mdb->grabaCfg($clave, $valor);
	}
}

if(!function_exists('getCuentaEF')) {
	function getCuentaEF(){
		return "98";
	}
}

if(!function_exists('getCantDecimales')) {
	function getCantDecimales(){
		$ci =& get_instance();
		$ci->load->model('model_db','mdb');

		$cant = $ci->mdb->cfg("cant_decimales");

		if($cant==0 || $cant==''){
			$cant = 2;
		}		

		return $cant;
	}
}

if(!function_exists('getSimboloMoneda')) {
	function getSimboloMoneda(){
		$ci =& get_instance();
		$ci->load->model('model_db','mdb');

		$ret = $ci->mdb->cfg("simbolo_moneda");

		if($ret==''){
			$ret = "$";
		}		

		return $ret;
	}
}

if(!function_exists('imprimirComprobante')) {
	function imprimirComprobante($tc,$suc,$nro,$let,$cuenta=''){
		$data= array();
		$ci =& get_instance();
		$ci->load->model('model_db','mdb');

		$data["cpteDetOf"] = $ci->mdb->abrirRecordset("SELECT * FROM ve_cpte_insumos where tc='$tc' and idcomprobante='$suc$nro$let' and oferta=1");
   		$data["cpteDet"] = $ci->mdb->abrirRecordset("SELECT * FROM ve_cpte_insumos where tc='$tc' and idcomprobante='$suc$nro$let' and (oferta=0 or oferta is null)");
   		$data["cpteCab"] = $ci->mdb->abrirRecordset("SELECT * FROM ve_cpte where tc='$tc' and idcomprobante='$suc$nro$let'");

		   /*
		$data["cpteDetOf"] = $ci->mdb->abrirRecordset("SELECT t1.*,t2.proveedor,t2.cod_proveedor FROM ve_cpte_insumos t1 LEFT JOIN vt_articulos t2 ON t1.codigo = t2.codigo Where t1.tc='$tc' and t1.idcomprobante='$suc$nro$let' and t1.oferta=1 and (t2.cod_proveedor<>'2' or t2.cod_proveedor='' or t2.cod_proveedor is null) LIMIT 0,100");
   		$data["cpteDet"] = $ci->mdb->abrirRecordset("SELECT t1.*,t2.proveedor,t2.cod_proveedor FROM ve_cpte_insumos t1 LEFT JOIN vt_articulos t2 ON t1.codigo = t2.codigo Where t1.tc='$tc' and t1.idcomprobante='$suc$nro$let' and (t1.oferta=0 or t1.oferta is null) and (t2.cod_proveedor<>'2' or t2.cod_proveedor='' or t2.cod_proveedor is null) LIMIT 0,100");
		$data["cpteOtros"] = $ci->mdb->abrirRecordset("SELECT t1.*,t2.proveedor,t2.cod_proveedor FROM ve_cpte_insumos t1 LEFT JOIN vt_articulos t2 ON t1.codigo = t2.codigo Where t1.tc='$tc' and t1.idcomprobante='$suc$nro$let' and t2.cod_proveedor='2' LIMIT 0,100");
		   */

   		if($cuenta==''){
   			$cuenta = $data["cpteCab"][0]->cuenta;
   		}

   		$data["cliente"] = $ci->mdb->get("vt_clientes","codigo",$cuenta,false);
   		$data["fechaCpte"] = $fecha=date("d/m/Y",  strtotime($data["cpteCab"][0]->fecha));
   		 
   		$data["tc"] = $tc;
   		$data["idcomprobante"] = $suc.$nro.$let;
   		$data["sucursal"] = $suc;
   		$data["numero"] = $nro;
   		$data["letra"] = $let;
   		$data["empresa"] = $ci->mdb->cfg("nombre");
   		$data["direccion"] = $ci->mdb->cfg("direccion");

   		$data["nombre_cpte"] = $ci->mdb->getDato("descripcion","ta_cptes","tc",$tc," sucursal='$suc' and letra='$let'");

   		$data["dec"] = getCantDecimales();
   		$data["mon"] = getSimboloMoneda();

   		//$ci->load->view('templates/header',$data);
   		$ci->load->view('templates/export/'.strtolower($tc).'_template',$data);
   		//$ci->load->view('templates/footer');

   		$html = $ci->output->get_output();
	
		//$html_content = $html;
		//setBasePath(): Define el path para incluir CSS e imágenes externos
		//$basePath (string) – El path que utilizaremos para cargar contenido externo
		
		
		$ci->pdf->setBasePath(base_url());
		$ci->pdf->loadHtml($html);
		$ci->pdf->setPaper('A4', 'portrait');
		$ci->pdf->render();
		$ci->pdf->stream($tc.$suc.$nro.$let.".pdf", array("Attachment"=>0));
		$ci->pdf->download($tc.$suc.$nro.$let.".pdf");
	}
}



if(!function_exists('fichaArticulo')) {
	function fichaArticulo($codigo){
		$data= array();
		$ci =& get_instance();
		$ci->load->model('model_db','mdb');

   		$data['artic'] = $ci->mdb->get("vt_articulos","codigo",$codigo);
		//$data['img_artic'] = $this->mdb->get("ma_articulos_imagenes","codigo",$codigo);

   		$data["empresa"] = $ci->mdb->cfg("nombre");
   		$data["direccion"] = $ci->mdb->cfg("direccion");

   		$data["dec"] = getCantDecimales();
   		$data["mon"] = getSimboloMoneda();

   		$ci->load->view('templates/header',$data);
   		$ci->load->view('templates/export/ficha_articulo');
   		$ci->load->view('templates/footer');

   		$html = $ci->output->get_output();
	
		//$html_content = $html;
		//setBasePath(): Define el path para incluir CSS e imágenes externos
		//$basePath (string) – El path que utilizaremos para cargar contenido externo
		/*
		$ci->pdf->setBasePath(base_url());
		$ci->pdf->loadHtml($html);
		$ci->pdf->setPaper('A4', 'portrait');
		$ci->pdf->render();
		$ci->pdf->stream("FC.pdf", array("Attachment"=>0));*/
	}
}

if(!function_exists('PrintPriceList')) {
	function PrintPriceList(){
		set_time_limit(100);
		$data= array();
		$ci =& get_instance();
		$ci->load->model('model_db');
		$data["productos"] = $ci->model_db->getPriceListPrint();
 		
 		$pdf_priceList = "list_price";

   		// $ci->load->view('templates/header',$data);
   		$ci->load->view('pdf_templates/'.$pdf_priceList, $data);
   		// $ci->load->view('templates/footer');

   		$html = $ci->output->get_output();
		
		$ci->pdf->setBasePath(base_url());
		$ci->pdf->loadHtml($html);
		$ci->pdf->setPaper('A4', 'portrait');
		$ci->pdf->render();
		$ci->pdf->stream("Lista_de_precios.pdf", array("Attachment"=>0));
		$ci->pdf->download("Lista_de_precios.pdf");
	}

}

if(!function_exists('PrintCtaCte')) {
	function PrintCtaCte($cuenta, $data){
		set_time_limit(100);
		//$data= array();
		$ci =& get_instance();
		//$ci->load->model('model_db');
		//$data["productos"] = $ci->model_db->getPriceListPrint();
 		
 		//$pdf_priceList = "list_price_vespucio";

   		//$ci->load->view('templates/header',$data);
   		$ci->load->view('pdf_templates/ctacte', $data);
   		//$ci->load->view('templates/footer');

   		$html = $ci->output->get_output();
	
		//$html_content = $html;
		//setBasePath(): Define el path para incluir CSS e imágenes externos
		//$basePath (string) – El path que utilizaremos para cargar contenido externo
		
		
		$ci->pdf->setBasePath(base_url());
		$ci->pdf->loadHtml($html);
		$ci->pdf->setPaper('A4', 'portrait');
		$ci->pdf->render();
		$ci->pdf->stream("Cuenta_corriente.pdf", array("Attachment"=>0));
		$ci->pdf->download("Cuenta_corriente.pdf");
	}

}