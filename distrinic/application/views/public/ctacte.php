<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>

    <div class="row">
        <div class="col-xs-12 box-customer">
            <div class="col-xs-2">
                <img alt="receipt" src="<?php echo base_url('assets/img/icon/receipt.svg');?>" />
            </div>
            <div class="col-xs-10">
                <p class="cod"><?php echo $codigo;?></p>
                <p><?php echo $customerName;?></p>
                <input type="text" readonly="readonly" id="idCustomer_ctacte" value="<?php echo $codigo;?>" style="display:none;">
            </div>
        </div>
    </div>

    <div id="list_invoices">
    <?php
        if($error){
            echo '
                <p class="text-center bg-danger">
                    '.$error_message.'
                </p>
            ';
        }
        if(!empty($list)): foreach($list as $row):
            if($row->sucNroLetra == "Saldo Anterior:" || $row->sucNroLetra == "Saldo Actual:"){
                $className = " rowSaldo";
            }else{
                $className = "";
            }
    ?>
        <div class="row box-ctacte">
            <div class="col-xs-12 <?php echo $className;?>">
                <div class="col-xs-12"><?php echo $row->detalle;?></div>                
                <div class="col-xs-7"><small><?php echo $row->tc . " " . $row->sucNroLetra;?></small></div>                
                <div class="col-xs-5 text-right"><small>$ <?php echo $row->importe;?></small></div>
                <div class="col-xs-6"><small><?php echo $row->fecha;?></small></div>
            </div>
        </div>    
    <?php
    endforeach; else:
        echo '<p class="text-center">Sin datos</p>';
    endif;
    ?>
    </div>

</div>
