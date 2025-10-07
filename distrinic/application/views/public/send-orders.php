<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>
    <div class="row">
        <div class="col-xs-12">
            <p class="text-center p-10">
                Este proceso enviará los pedidos, las tareas y las cobranzas pendientes, una vez confirmada la recepcion del servidor
                los eliminará de la base local.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 div-img-sync">
            <img alt="sent order" src="<?=base_url('assets/img/icon/sent-order.svg');?>">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 div-btn-sync">
            <?php
            $disabled = "";
            if($QtyPending == 0){
                $disabled = ' disabled="disabled" ';
            }
            ?>
            <button type="button" class="btn btn-success" id="btn_send_orders" <?php echo $disabled;?>>Enviar Pendientes</button>
            <?php
            if($QtyPending == 0){
                echo '<small class="small-center p-10">No hay pedidos, cobranzas o tareas pendientes.</small>';
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            <?php
            if($pendientePedidos > 0) {
                echo '<p style="margin:0;"><small>Pedidos pendientes: '.$pendientePedidos.'</small></p>';
            }
            if($pendienteCobranzas > 0) {
                echo '<p style="margin:0;"><small>Cobranzas pendientes: '.$pendienteCobranzas.'</small></p>';
            }
            if($pendienteTareas > 0) {
                echo '<p style="margin:0;"><small>Tareas pendientes: '.$pendienteTareas.'</small></p>';
            }
            ?>
        </div>
    </div>
    
</div>

<?php
    $this->load->view('public/modal_sendOrders');
?>