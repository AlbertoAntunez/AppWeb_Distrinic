<div class="container">
    <?php
    $this->load->view('public/mod-back');
    $hoy = date('Y-m-d');
    $selected = "";
    ?>
    <style>
        #sel_customer_history option {
            font-size: 0.8em !important;
        }
    </style>
    <div class="row">
        <div class="col-xs-12 p-10 text-center">
            <h3>Nueva Tarea</h3>
        </div>
    </div>

    <?php
    if ($error) {
        echo '<p class="text-center bg-danger">' . $error_message . '</p>';
    }

    ?>

    <div class="row">
        <div class="col-xs-12 p-10">
            <label for="fechaD">Fecha</label>
            <input class="form-control" type="date" value="<?php echo $hoy; ?>" name="date_new_task" id="date_new_task">
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Cuenta</label>
                <select class="form-control select2" name="sel_customer_new_task" id="sel_customer_new_task">
                    <option value=""> - </option>
                    <?php
                    foreach ($customers as $row) {
                        $selected = "";
                        if ($row->codigo == $cuenta_sel) {
                            $selected = "selected";
                        }
                        echo '<option ' . $selected . ' value="' . $row->codigo . '">' . $row->codigo . ' - ' . $row->razonSocial . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Servicio</label>
                <select class="form-control select2" name="sel_service_new_task" id="sel_service_new_task">
                    <option value=""> - </option>
                    <?php
                    foreach ($servicios as $row) {
                        echo '<option value="' . $row->codigo . '">' . $row->codigo . ' - ' . $row->descripcion . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="form-group">
                <label>Observación</label>
                <textarea class="form-control" id="obs_new_task"></textarea>
            </div>
        </div>

 
        <style>
            canvas {
                width: 100%;
                height: 300px;
                background-color: #fff;
                border: 1px solid #e1e1e1;

                /*
                background-color: #0D0909;*/
            }
        </style>
        <div class="col-xs-12">
            <input type="hidden" id="imagenFirma" name="imagenFirma">
            <label>Firma</label>            
            <canvas id="pizarra"></canvas>
            <button id="btn_clear_firma" class="btn btn-sm btn-warning">Limpiar firma</button>
        </div>

        <div class="col-xs-6 col-xs-offset-6 text-right">
            <button class="btn btn-info" id="btn-save-task">Grabar</button>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12" id="loader_new_task">

        </div>
    </div>

</div>