<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Config_helper{
	protected $ci;

	public function __construct(){
		$this->ci =& get_instance();
		$this->ci->load->model('model_db'); 

		$this->ci->config->set_item('COND_IVA', $this->ci->model_db->cfg("cond_iva"));
		$this->ci->config->set_item('NOMBRE_EMPRESA', $this->ci->model_db->cfg("nombre"));

		

		
		//echo $this->config->item('COND_IVA');
	}
}