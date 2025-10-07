<div class="container">
  <div class="row cabeceraMain">

    <div class="col-xs-10">
      <div class="titulo_cab">
        <?php
        $usaOffline = (defined('USA_OFFLINE')) ? USA_OFFLINE : false;
        $lver = ($version < 1) ? " BETA" : "";
        ?>
        <p>Alfa Net - SysMobile <small>v. <?php echo $version . $lver; ?></small></p>
      </div>
    </div>

    <div class="col-xs-2">
      <div class="configItem">
        <a href="javascript:void(0);" id="a_config_init">
          <img class="img-responsive" alt="settings" src="<?= base_url('assets/img/icon/settings.svg'); ?>">
        </a>
      </div>
    </div>

  </div>

  <?php
  $qty = ($QtyPending > 0) ? "(" . $QtyPending . ")" : "";
  ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="divMnuPrincipal">
        <ul class="mnuPrincipal">
          <li><a href="<?php echo base_url('inicio/sync'); ?>"><img alt="sync" src="<?php echo base_url('assets/img/icon/sync.svg'); ?>">&nbsp;<span><?php echo TXT_SINCRONIZAR; ?></span></a></li>

          <?php
          if (!$this->session->userdata('primerIngreso')) {
          ?>
            <li><a href="<?php echo base_url('vendors/ruta_diaria'); ?>"><img alt="sync" src="<?php echo base_url('assets/img/icon/maps.svg'); ?>">&nbsp;<span>Ruta Diaria</span></a></li>

            <li><a href="<?php echo base_url('inicio/list_orders'); ?>"><img alt="order" src="<?php echo base_url('assets/img/icon/order.svg'); ?>">&nbsp;<span><?php echo TXT_PEDIDOS; ?></span></a></li>
            <li>
              <a href="<?php echo base_url('inicio/send_pending'); ?>">
                <img alt="sent order" src="<?php echo base_url('assets/img/icon/sent-order.svg'); ?>">
                &nbsp;<span><?php echo TXT_ENVIAR_PENDIENTES . ' ' . $qty; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo base_url('products/list'); ?>"><img alt="barcode" src="<?php echo base_url('assets/img/icon/barcode.svg'); ?>">&nbsp;<span><?php echo TXT_ARTICULOS; ?></span></a>
            </li>
            <li>
              <a href="<?php echo base_url('customers/list'); ?>"><img alt="customers" src="<?php echo base_url('assets/img/icon/customers.svg'); ?>">&nbsp;<span><?php echo TXT_CLIENTES; ?></span></a>
            </li>

            <li>
              <a href="<?php echo base_url('payments/index'); ?>"><img alt="payments" src="<?php echo base_url('assets/img/icon/payment.svg'); ?>">&nbsp;<span><?php echo TXT_COBRANZAS; ?></span></a>
            </li>
			<!--
            <li>
              <a href="<?php echo base_url('task/index'); ?>"><img alt="payments" src="<?php echo base_url('assets/img/icon/task.svg'); ?>">&nbsp;<span><?php echo TXT_TAREAS; ?></span></a>
            </li>
			-->
            <li>
              <a href="<?php echo base_url('order/history'); ?>"><img alt="key history" src="<?php echo base_url('assets/img/icon/receipt.svg'); ?>">&nbsp;<span><?php echo TXT_HIST_PEDIDOS; ?></span></a>
            </li>

            <?php
            if ($usaOffline) {
            ?>
              <li><a href="javascript:void(0);" id="btn_sync_offline"><img alt="orders history" src="<?php echo base_url('assets/img/icon/sync.svg'); ?>">&nbsp;<span id="btn_sync_offline"><?php echo TXT_DATOS_OFFLINE; ?></span></a><small id="datos_offline"></small></li>
          <?php
            }
          }
          ?>

          <?php
          if (MODO_DESARROLLADOR) {
            echo '
            <li>
              <a href="#" class="dev-btn-key"><img alt="key" src="' . base_url('assets/img/icon/key.svg') . '">&nbsp;<span>Serie</span></a>
            </li>
            ';
          }

          ?>

          <li><a href="https://wa.me/+5491151480148" rel="noopener" target="_blank"><img alt="whatsapp" src="<?php echo base_url('assets/img/icon/whatsapp.svg'); ?>">&nbsp;<span>Soporte</span></a></li>

          <li><a href="<?php echo base_url('inicio/salir'); ?>"><img alt="exit" src="<?php echo base_url('assets/img/icon/cancel.svg'); ?>">&nbsp;<span>Salir</span></a></li>

        </ul>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 text-right">
      <?php
      if ($this->session->userdata('nombre') != '') {
        echo '<small> Usuario : ' . $this->session->userdata('nombre') . '</small>';
      }
      ?>
    </div>
  </div>


  <div class="row">
    <div class="col-xs-12 text-right">
      <?php
      $mensaje = !$usaOffline ? "Desactivado" : "Activo";

      echo '<small>Offline : ' . $mensaje . '</small>';
      ?>
      <span id="status_offline"></span>
    </div>
  </div>

</div>

<div class="modal fade" id="modal_syncOffline" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body" id="modal_syncOffline_body">
        <div class="row">
          <div class="col-xs-12 text-primary text-center">
            <h4>Sincronizando pedidos Offline</h4>
            <p>Aguarde por favor</p>
            <img src="<?php echo base_url('assets/img/ajax-loader.gif'); ?>">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include 'modal_key.php';
?>

<?php
if ($usaOffline) {
?>
  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
      verificarPedidosOffline();
      verificaDatosOffline();
    });
  </script>
<?php
}
?>