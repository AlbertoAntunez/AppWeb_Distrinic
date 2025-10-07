<div class="container">
    <?php
        $this->load->view('public/mod-back');
        $hoy = date('Y-m-d');
        $selected = "";
    ?>
    <style>
        #sel_customer_history option{
            font-size: 0.8em !important;
        }
    </style>
    <div class="row">
        <div class="col-xs-12 p-10 text-center">
            <h3>Nueva cobranza</h3>
        </div>
    </div>

    <?php
    if($error) {
        echo '<p class="text-center bg-danger">'.$error_message.'</p>';
    }

    ?>

    <div class="row">

        <div class="col-xs-12 p-10">
            <label for="fechaD">Fecha</label>
            <input class="form-control" type="date" value="<?php echo $hoy;?>" name="date_new_payment" id="date_new_payment">
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label for="tipocb">Comprobante</label>
                <select class="form-control" name="sel_tipocb" id="sel_tipocb">
                    <option value="CB">Cobranza</option>
                    <option value="CBFP">Cobranza Proforma</option>
                </select>
            </div>
        </div>
        
        <div class="col-xs-12">
            <div class="form-group">
                <label>Cuenta</label>
                <select class="form-control select2" name="sel_customer_new_payment" id="sel_customer_new_payment">
                    <option value = ""> - </option>
                    <?php
                    foreach($customers as $row){
                        $selected = "";
                        if($row->codigo == $cuenta_sel){
                            $selected = "selected";
                        }
                        echo '<option '.$selected.' value="'.$row->codigo.'">'.$row->codigo . ' - ' .$row->razonSocial.'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Medio de Pago</label>
                <select class="form-control" name="sel_mp_new_payment" required id="sel_mp_new_payment">
                    <?php

                    if(count($medios_pago) > 1) {
                        echo '<option value=""> - </option>';
                    }

                    foreach($medios_pago as $mp){                
                        echo '
                        <option value="'.$mp->codigo.'">'.$mp->descripcion.'</option>
                        ';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Importe abonado</label>
                <input type="number" name="imp_payment" id="imp_payment" required class="form-control">
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Observación</label>
                <textarea class="form-control" id="obs_new_payment"></textarea>
            </div>
        </div>

        <div class="col-xs-6 col-xs-offset-6 text-right">
            <button class="btn btn-info" id="btn-save-payment">Grabar</button>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12" id="loader_new_payment">
        
        </div>
    </div>
    
</div> 
