
<div class="modal fade" id="modalSendOrders" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLongTitle">Enviando pedidos</h4>
        <small>Este proceso puede demorar según la cantidad de pedidos, cobranzas o tareas y el tráfico de red. No cierre el navegador ni cancele este proceso.</small>
      </div>
      <div class="modal-body" id="modalSendOrder_body">

        <div class="row">
          <div class="col-xs-2 col-xs-offset-3" id="div_img_sendOrder">
            <img alt="wait" style="width:30px; height:auto;" src="<?php echo base_url('assets/img/icon/timer.svg');?>">
          </div>
          <div class="col-xs-3">
            <h4>Enviando</h4>
          </div>          
        </div>

        <br />

        <div class="row">
          <div class="col-xs-12">
            <p id="msgStatus_order" class="text-center"></p>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btn_sendorder_close" style="display:none; width:100%;">Cerrar</button>
        <!--<button type="button" class="btn btn-secondary" id="btn_addProduct_accept">Aceptar</button>-->
      </div>
    </div>
  </div>
</div>