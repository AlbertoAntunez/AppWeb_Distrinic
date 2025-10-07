<?php 

class Model_db extends CI_Model{

	public function __construct() {
    parent::__construct();    
  }

  function asignaConexionVendedor(){
    $this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
  }
   

  public function getMediosPago() {
    $this->asignaConexionVendedor();
    $query = $this->db2->query("SELECT * FROM mediosPago");
    return $query->result();
  }

  public function getServicios() {
    $this->asignaConexionVendedor();
    $query = $this->db2->query("SELECT * FROM servicios");
    return $query->result();
  }

  public function getCobranzas($fhd = '', $fhh = '', $cuenta = '') {
    $this->asignaConexionVendedor();
    $sql = "";
    $wh  = "";
    
    if($cuenta != '' && $cuenta != '-1'){
      $wh = " cuenta = '".$cuenta."'";
    }

    date_default_timezone_set(TIMEZONE);
    $fhd = $fhd != '' ? strftime("%G-%m-%d", strtotime($fhd)) : date("Y-m-d");
    $fhh = $fhh != '' ? strftime("%G-%m-%d", strtotime($fhh)) : date("Y-m-d");

    if($wh != '') {
      $wh .= " and ";
    }

    $wh .= " (fecha>='".$fhd."' and fecha<='".$fhh."') "; 
    
    $query = $this->db2->query(
      "SELECT _id as id,fecha,cuenta,importe as totalFinal,mp,observacion,idvendedor,tc, '2' as idcomprobante FROM cobranzas
      WHERE ".$wh
    );
    return $query->result();

  }

  public function temp() {
    $this->asignaConexionVendedor();
    
    $query = $this->db2->query(
      "SELECT * FROM tareas"
    );
    return $query->result();

  }

  public function getTareas($fhd = '', $fhh = '', $cuenta = '') {
    $this->asignaConexionVendedor();
    $sql = "";
    $wh  = "";
    
    if($cuenta != '' && $cuenta != '-1'){
      $wh = " cuenta = '".$cuenta."'";
    }

    date_default_timezone_set(TIMEZONE);
    $fhd = $fhd != '' ? strftime("%G-%m-%d", strtotime($fhd)) : date("Y-m-d");
    $fhh = $fhh != '' ? strftime("%G-%m-%d", strtotime($fhh)) : date("Y-m-d");

    if($wh != '') {
      $wh .= " and ";
    }

    $wh .= " (fecha>='".$fhd."' and fecha<='".$fhh."') "; 
    
    $query = $this->db2->query(
      "SELECT * FROM tareas
      WHERE ".$wh
    );
    return $query->result();

  }

  public function getCustomers($top=100, $localidad = ""){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $this->db2->order_by("cuenta","ASC");
    $this->db2->limit($top);

    if($localidad!=""){
      $this->db2->where('localidad', $localidad);
    }

    $vdorCustomer = (defined('VENDEDOR_CLIENTES_PROPIOS')) ? VENDEDOR_CLIENTES_PROPIOS : "0";

    if($vdorCustomer == 1){
      $this->db2->where('idVendedor', $_SESSION['codigo']);
    }

    $query = $this->db2->get("clientes");

    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            $data[] = $row;
        }            
        return $data;
    }

    return false;
  }

  public function getCustomer($id){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $this->db2->select('*');
    $this->db2->from('clientes');
    $this->db2->where('codigo',$id);
    $query = $this->db2->get();
    return ($query->num_rows() > 0)?$query->result_array():FALSE;
  }

  public function getCustomersByQuery($b, $localidad = ""){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $this->db2->select('*');
    $this->db2->from('clientes');
    $this->db2->like('razonSocial', $b);
    if($localidad!=""){
      $this->db2->where('localidad', $localidad);
    }
    $vdorCustomer = (defined('VENDEDOR_CLIENTES_PROPIOS')) ? VENDEDOR_CLIENTES_PROPIOS : "0";

    if($vdorCustomer == 1){
      $this->db2->where('idVendedor', $_SESSION['codigo']);
    }

    $query = $this->db2->get();
    return ($query->num_rows() > 0)?$query->result_array():FALSE;
  }


  public function getProducts(){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    
    $artsPorConsulta = (!defined('ARTS_POR_CONSULTA')) ? "" : ARTS_POR_CONSULTA;
    if($artsPorConsulta == "" || $artsPorConsulta == 0) { $artsPorConsulta = 20; }

    $this->asignaConexionVendedor();
    $clp = $this->session->userdata('claseP');
    $clp = ($clp == "") ? "precio1" : "precio".$clp;
    
    $query = $this->db2->query("SELECT idArticulo,descripcion,".$clp." as precio FROM ARTICULOS ORDER BY descripcion ASC LIMIT 0,".$artsPorConsulta);
    return $query->result();

    /*
    $this->db2->order_by("descripcion","ASC");
    $this->db2->limit($top);

    $query = $this->db2->get("ARTICULOS");

    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            $data[] = $row;
        }            
        return $data;
    }*/

    //return false;
   }

  public function getProductsByQuery($b){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $clp = $this->session->userdata('claseP');
    $clp = ($clp == "") ? "precio1" : "precio".$clp;
    
    $artsPorConsulta = (!defined('ARTS_POR_CONSULTA')) ? "" : ARTS_POR_CONSULTA;
    if($artsPorConsulta == "" || $artsPorConsulta == 0) { $artsPorConsulta = 20; }

    $query = $this->db2->query("SELECT idArticulo,descripcion,".$clp." as precio FROM ARTICULOS WHERE descripcion like '%".$b."%' ORDER BY descripcion ASC LIMIT 0,".$artsPorConsulta);
    return $query->result();
    /*
    $this->db2->select('*');
    $this->db2->from('ARTICULOS');
    $this->db2->like('descripcion', $b);
    $query = $this->db2->get();
    return ($query->num_rows() > 0)?$query->result_array():FALSE;*/
  }

  public function getProduct($id){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $this->db2->select('*');
    $this->db2->from('ARTICULOS');
    $this->db2->where('idArticulo',$id);
    $query = $this->db2->get();
    return ($query->num_rows() > 0)?$query->result_array():FALSE;
  }

  public function getOrdersPending(){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("SELECT PEDIDOS.*,clientes.razonSocial from PEDIDOS LEFT JOIN clientes on PEDIDOS.codCliente = clientes.codigo where transferido=0 ");
    return $query->result();
  }

  public function getLocalidades(){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();

    $vdorCustomer = (defined('VENDEDOR_CLIENTES_PROPIOS')) ? VENDEDOR_CLIENTES_PROPIOS : "0";

    $wh = "";
    if($vdorCustomer == 1){
      $wh = " WHERE idVendedor ='".$_SESSION['codigo']."' ";
    }

    $query = $this->db2->query("SELECT localidad from clientes ".$wh." group by localidad");
    return $query->result();
  }

  public function getCustomerName($id){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select razonSocial from clientes where codigo='".$id."'");

    $arrNom = $query->result(); 

    if(count($arrNom)>0){
      foreach ($arrNom as $row) {
        $descripcion = $row->razonSocial;
      }
    }else{
      $descripcion = "";
    }
    preg_replace("/[^A-Za-z0-9 ]/", '', $descripcion); 
    return $descripcion;
  }

  public function getInfoCustomer($id, $field){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select ".$field." as campoDevuelto from clientes where codigo='".$id."'");

    $arrNom = $query->result(); 

    if(count($arrNom)>0){
      foreach ($arrNom as $row) {
        $response = $row->campoDevuelto;
      }
    }else{
      $response = "";
    }
    //preg_replace("/[^A-Za-z0-9 ]/", '', $descripcion); 
    return $response;
  }

  public function getPriceByClass($id, $clase){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select precio".$clase." as precio from ARTICULOS where idArticulo='".$id."'");

    $arrNom = $query->result(); 

    if(count($arrNom)>0){
      foreach ($arrNom as $row) {
        $response = $row->precio;
      }
    }else{
      $response = 0;
    }
    return $response;
  }

  public function getProductDescription($id){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select descripcion from articulos where idArticulo='".$id."'");

    $arrNom = $query->result(); 

    if(count($arrNom)>0){
      foreach ($arrNom as $row) {
        $descripcion = $row->descripcion;
      }
    }else{
      $descripcion = "";
    }
    preg_replace("/[^A-Za-z0-9 ]/", '', $descripcion); 
    return $descripcion;
  }

  public function productExists($id, $clase = ""){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();

    if($clase!=""){
      $clp = "precio".$clase;
    }else{
      $clp = $this->session->userdata('claseP');
      $clp = ($clp == "") ? "precio1" : "precio".$clp;
    }

    // echo "CLASE - " .$this->session->userdata('claseP') . " - " . $_SESSION['claseP'];

    $query = $this->db2->query("SELECT descripcion,".$clp." as precio from ARTICULOS where idArticulo='$id'");
		//$result = $query->result();
    return $query->result();
  }

  public function getOrderItems_DB($id){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("SELECT * from pedidosItems where idPedido='$id'");
    return $query->result();
  }

  public function getMaxId(){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select MAX(_id) as id from PEDIDOS");
    $arrNom = $query->result(); 

    if(count($arrNom)>0){
      foreach ($arrNom as $row) {
        $id = $row->id + 1;
      }
    }else{
      $id = 1;
    }
    
    return $id;
  }

  function getPriceClassOrder($id){
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select claseDePrecio as clase from PEDIDOS where _id=".$id);
    $arrNom = $query->result(); 

    $clase = 0;
    if(count($arrNom)>0){
      foreach ($arrNom as $row) {
        $clase = $row->clase;
      }
    }else{
      $clase = 1;
    }
    
    return $clase;
  }

   public function getRubros(){
    $this->asignaConexionVendedor();

    $query = $this->db2->query("SELECT idRubro,descripcion from RUBROS");
    return $query->result();
  }

  function insertar($data,$tabla,$returnID = false){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
		$this->db2->insert($tabla,$data);

    $insert_id = $this->db2->insert_id();

		if($this->db2->affected_rows()==1){
      if($returnID){
        return $insert_id;
      }else{
        return "OK";
      }    
		}else{
			return $this->db2->_error_message();
		}
  }
  
  public function ejecutarConsulta($sql,$retornar = false){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query($sql);
    if ($retornar){
      return $query->result();	
    }
     //return $query->result();
  }
  
  function eliminar($tabla,$campo,$valor){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
		$this->db2->where($campo, $valor);
		$this->db2->delete($tabla);
  }

  function actualizar($data,$tabla,$campo,$valor){
    $this->asignaConexionVendedor();
		$this->db2->where($campo, $valor);
		if($this->db2->update($tabla, $data)==true){
			return "OK";
		}else{
			return "ERROR";
		}
	}
  
  function getQtyPendingSend($table='PEDIDOS', $wh = ''){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();

    if($table == 'PEDIDOS') {
      $wh = ' where transferido=0 ';
    }

    try{
      $query = $this->db2->query("select count(*) as cant from ".$table." " .$wh);
      $arrNom = $query->result(); 

      $cant = 0;
      if(count($arrNom)>0){
        foreach ($arrNom as $row) {
          $cant = $row->cant;
        }
      }else{
        $cant = 0;
      }
    } catch(Exception $e){
      $cant = 0;
    }
    
    return $cant;
  }

  public function iniciarSesion($user,$pass){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();

    $this->db2->select('idVendedor, nombre');
    $this->db2->from('VENDEDORES');
    $this->db2->where('idVendedor', $user);
    $this->db2->where('codigoDeValidacion', $pass);
    $consulta = $this->db2->get();
    $resultado = $consulta->row();
    return $resultado;
  }

  public function hayVendedores(){
    //$this->db2 = $this->load->database($_SESSION['dbgroup'], TRUE);
    $this->asignaConexionVendedor();
    $query = $this->db2->query("select * from VENDEDORES");
    $arrNom = $query->result(); 

    if(count($arrNom)>0){
      return true;
    }else{
      return false;
    }
  }

  public function getPriceListPrint(){
    $this->asignaConexionVendedor();

    $sql = "";

    //if($sql == ""){
      $sql = "SELECT ARTICULOS.idArticulo,ARTICULOS.descripcion,ARTICULOS.PRECIO1,ARTICULOS.PRECIO2,ARTICULOS.PRECIO3,ARTICULOS.PRECIO4,ARTICULOS.PRECIO5,ARTICULOS.PRECIO6,ARTICULOS.PRECIO7,ARTICULOS.PRECIO8,RUBROS.IdRubro,RUBROS.Descripcion as rubro,FAMILIAS.IdFamilia, FAMILIAS.Descripcion as familia from ARTICULOS LEFT JOIN RUBROS  on ARTICULOS.IDRUBRO = RUBROS.IdRubro LEFT JOIN FAMILIAS ON ARTICULOS.idFamilia = FAMILIAS.IdFamilia WHERE ARTICULOS.idrubro<>'' ORDER BY RUBROS.IdRubro,FAMILIAS.IdFamilia,ARTICULOS.IDARTICULO LIMIT 0,500";
    //}

    $query = $this->db2->query($sql);
    return $query->result();

  }

  
  public function getPayments(){
    $this->asignaConexionVendedor();
    $this->db2->order_by("fecha","ASC");
    // $this->db2->limit($top);

    $query = $this->db2->get("cobranzas");

    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            $data[] = $row;
        }            
        return $data;
    }

    return false;
  }

  public function getTasks(){
    $this->asignaConexionVendedor();
    $this->db2->order_by("fecha","ASC");
    // $this->db2->limit($top);

    $query = $this->db2->get("tareas");

    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            $data[] = $row;
        }            
        return $data;
    }

    return false;
  }

  public function getVisitasVendedor(){
    $this->asignaConexionVendedor();
    // $vdor = $this->session->userdata('codigo');
    $dia = date('N'); //Esto devuelve del 1 al 7 (lunes a domingo)
    
    switch ($dia) {
      case 1: $nombreDia = 'lunes'; break;
      case 2: $nombreDia = 'martes'; break;
      case 3: $nombreDia = 'miercoles'; break;
      case 4: $nombreDia = 'jueves'; break;
      case 5: $nombreDia = 'viernes'; break;
      case 6: $nombreDia = 'sabado'; break;
      case 7: $nombreDia = 'domingo'; break;    
      default:
        $nombreDia = 'lunes';
        break;
    }

    $this->db2->select('*');
    $this->db2->from('visitas');
    $this->db2->where($nombreDia, 1);
    $query = $this->db2->get();

    if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
            $data[] = $row;
        }            
        return $data;
    }

    return false;
  }

  public function pendienteVisita($cuenta) {
    /*
    Determina si un cliente esta pendiente de visita.
    Para eso verifica si tiene pedido cargado en la fecha.
    Si tiene, lo marca como visitado.
    */

    $this->asignaConexionVendedor();

    $fecha  = date('Ymd').'000001';
    $fechaH = date('Ymd').'235959';
   
    $query = $this->db2->query("select fecha from PEDIDOS where codCliente='$cuenta' and transferido=0 and (fecha>='$fecha' and fecha<='$fechaH')");
    $response = $query->result(); 

    if(count($response)>0){
      foreach ($response as $row) {
        return false;
      }
    }else{
      return true;
    }
    
  }

}


