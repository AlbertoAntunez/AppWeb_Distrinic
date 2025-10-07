<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>

    <div class="row ftbot-orders">
        <div class="col-xs-7 bot-orders">
            <p>Lista de Pedidos</p>
        </div>
        <div class="col-xs-5 bot-orders">
            <button id="btn_new_order">Nuevo pedido</button>            
        </div>    
    </div>

    <div class="row" id="list_orders">
    <?php

    if(!empty($orders)): foreach($orders as $row): 
        $fecha = "";

        $fecha = $row->fecha;
        date_default_timezone_set(TIMEZONE);
        $fecha = strftime("%d/%m/%G", strtotime($fecha));

        $noMuestraTotales = (defined('NOMUESTRA_TOTALES_PEDIDOS')) ? NOMUESTRA_TOTALES_PEDIDOS : "0";

        $colRazon = '';
        $colRazon = ($noMuestraTotales == '1') ? 'col-xs-10 ' : 'col-xs-7 ';

    ?>

        <div class="col-xs-12 f-item">
            <a href="<?php echo base_url("inicio/newOrder/".$row->codCliente."/".$row->_id);?>" class="item">
                <div class="col-xs-2" style="padding:0;">
                    <img alt="sent order" src="<?=base_url('assets/img/icon/sent-order.svg');?>" >
                </div>
                <div class="<?php echo $colRazon;?> col-name-order" style="padding:0;">
                    <p class="nameCliente"><?php echo $row->razonSocial;?></p>
                    <small class="order-fh"><?php echo $fecha;?></small>
                </div>
                <?php 
                if($noMuestraTotales == '0'){
                ?>
                <div class="col-xs-3 col-total-order">
                    <small>$<?php echo $row->totalFinal;?></small>
                </div>
                <?php
                }
                ?>
            </a>
        </div>

    <?php
    endforeach; else:
    endif;
    ?>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <small style="display:block;" class="bg-warning text-center">Toque un pedido para modificarlo.</small>
        </div>
    </div>  

</div>