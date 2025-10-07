<div class="container" style="overflow:hidden;">
    <?php
    $this->load->view('public/mod-back');
    ?>

    <style>
        #sel_customer_history option {
            font-size: 0.8em !important;
        }
    </style>

    <div class="row">
        <div class="col-xs-6 p-10">
            <a href="<?php echo base_url('payments/new'); ?>" class="btn btn-sm btn-success">Nueva cobranza</a>
        </div>
        <div class="col-xs-6 text-right p-10">
            <button class="btn btn-info btn-sm" id="btn_search_payments">Buscar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center p-10">
            <h4>Consulta</h4>
        </div>

        <?php
        if (isset($_GET['message'])) {
            if ($_GET['message'] == 'ok') {
                $msg = "Cobranza cargada correctamente";
            }else if ($_GET['message'] == 'delete') {
                $msg = "Cobranza eliminada correctamente";
            }

            echo '
            <div class="row">
                <div class="col-xs-12 text-center p-10">
                    <p class="bg-success">'.$msg.'</p>
                </div>
            </div>
            ';
        }
        ?>
        <div class="col-xs-6">
            <label for="fechaD">Desde</label>
            <input class="form-control" type="date" name="fechaD" id="fechaD">
        </div>

        <div class="col-xs-6">
            <label for="fechaH">Hasta</label>
            <input class="form-control" type="date" name="fechaH" id="fechaH">
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Cuenta</label>
                <select class="form-control select2" name="sel_customer_history" id="sel_customer_history">
                    <option value=""> - </option>
                    <?php
                    foreach ($customers as $row) {
                        echo '<option value="' . $row->codigo . '">' . $row->codigo . ' - ' . $row->razonSocial . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <br />

    <div class="row" id="payment_list">
        <?php
        echo $cobranzas_pendientes;
        ?>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center bg-warning" style="margin-top:1rem;">
            <small>Toque una cobranza para eliminarla. <br>Solo se podran eliminar las pendientes de sincronizar.</small>
        </div>
    </div>

</div>