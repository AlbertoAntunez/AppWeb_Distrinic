
<div class="modal fade" id="modalSync" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle">Sincronizando</h4>
        <small>Este proceso puede demorar según la cantidad de registros. No cierre el navegador ni cancele este proceso.</small>
      </div>
      <div class="modal-body" id="modalProductsPreSaleProduct_body">

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_categories">
            <img alt="wait" style="width:30px; height:auto;" src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Rubros (<?php echo $cantCategories;?>)</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_families">
            <img alt="wait" style="width:30px; height:auto;" src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Familias (<?php echo $cantFamilies;?>)</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_vendors">
            <img alt="wait" style="width:30px; height:auto;" src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Vendedores (<?php echo $cantVendors;?>)</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_visitas">
            <img alt="wait" style="width:30px; height:auto;" src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Datos visitas</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_customers">
            <img alt="wait" style="width:30px; height:auto;" src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Clientes (<?php echo $cantCustomers;?>)</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_products">
            <img alt="wait" style="width:30px; height:auto;"  src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Articulos (<?php echo $cantProducts;?>)</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_mp">
            <img alt="wait" style="width:30px; height:auto;"  src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Medios de Pago</h4>
          </div>          
        </div>

        <div class="row">
          <div class="col-xs-1 col-xs-offset-2" id="div_img_servicios">
            <img alt="wait" style="width:30px; height:auto;"  src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-offset-1 col-xs-7">
            <h4>Servicios</h4>
          </div>          
        </div>

        <br />

        <div class="row">
          <div class="col-xs-12">
            <p id="msgStatus" class="text-center"></p>
          </div>

          <div class="col-xs-12 text-center" id="status_offline_sync">

          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_sync_close" style="display:none; width:100%;">Cerrar</button>
        <!--<button type="button" class="btn btn-secondary" id="btn_addProduct_accept">Aceptar</button>-->
      </div>
    </div>
  </div>
</div>