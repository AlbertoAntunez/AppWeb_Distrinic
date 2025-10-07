<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>
    
    <style>
        #sel_customer_history option{
            font-size: 0.8em !important;
        }

    </style>
    <h4 class="text-center">Historial de pedidos</h4>
    <div class="row">
        <div class="col-xs-6">
            <label for="fechaD">Desde</label>
            <input class="form-control" type="date" name="fechaD" id="fechaD">
        </div>

        <div class="col-xs-6">
            <label for = "fechaH">Hasta</label>
            <input class="form-control" type="date" name="fechaH" id="fechaH">
        </div>
        
        <div class="col-xs-12">
            <div class="form-group">
                <label>Cuenta</label>
                <select class="form-control select2" name="sel_customer_history" id="sel_customer_history">
                    <option value = ""> - </option>
                    <?php
                    foreach($customers as $row){
                        echo '<option value="'.$row->codigo.'">'.$row->codigo . ' - ' .$row->razonSocial.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-xs-offset-6 text-right">
            <button class="btn btn-info" id="btn_search_history">Buscar</button>
        </div>
    </div>

    <br />
    <style>
        
    </style>

    <div class="row" id="history_list">
        <!--<div class="col-xs-12 item-historial">
            <div class="col-xs-2 col-xs-offset-0">
                <img style="max-width:100%;" alt="sent order" src="<?=base_url('assets/img/icon/sent-order.svg');?>" >
            </div>
            <div class="col-xs-10">    
                <div class="col-xs-12">
                    <p class="title_customer"><a href="javascript:void(0);" name="NP|999900000001X" class="item_historyOrder">ETHEL MABEL PEREYRA - PANIFICADORA MARCOS DE SANTIS UNO</a></p>
                </div>
                <div class="col-xs-6">
                    <small>17/09/2020 </small>
                </div>

                <div class="col-xs-6 text-right">
                    <small> $20312.915</small>
                </div>
            </div>
        </div>
        -->

        

        
    <?php
/*
        if(!empty($orders)): foreach($orders as $row): 
            $fecha = "";

            $fecha = $row->fecha;
            date_default_timezone_set(TIMEZONE);
            $fecha = strftime("%d/%m/%G", strtotime($fecha));
        ?>

            <div class="col-xs-12 f-item">
                <a href="<?php echo base_url("inicio/newOrder/".$row->codCliente."/".$row->_id);?>" class="item">
                    <div class="col-xs-2" style="padding:0;">
                        <img alt="sent order" src="<?=base_url('assets/img/icon/sent-order.svg');?>" >
                    </div>
                    <div class="col-xs-7 col-name-order" style="padding:0;">
                        <p class="nameCliente"><?php echo $row->razonSocial;?></p>
                        <small class="order-fh"><?php echo $fecha;?></small>
                    </div>
                    <div class="col-xs-3 col-total-order">
                        <small>$<?php echo $row->totalFinal;?></small>
                    </div>
                </a>
            </div>

        <?php
        endforeach; else:
        endif;
        */
        ?>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <small style="display:block;" class="bg-warning text-center">Toque un pedido para visualizarlo.</small>
        </div>
    </div>  

</div> 

<?php
$this->load->view('public/modal_historyorder_detail');
?>