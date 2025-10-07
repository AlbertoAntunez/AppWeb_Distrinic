<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>
    
    <style>
        #sel_customer_history option{
            font-size: 0.8em !important;
        }

    </style>

    <div class="row">
        <div class="col-xs-6 p-10">
            <a href="<?php echo base_url('task/new');?>" class="btn btn-sm btn-success">Nueva Tarea</a>
        </div>
        <div class="col-xs-6 text-right p-10">
            <button class="btn btn-info btn-sm" id="btn_search_tasks">Buscar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center p-10">
            <h4>Consulta</h4>
        </div>

        <?php
        if(isset($_GET['message'])) {
            if($_GET['message'] == 'ok'){
                echo '
                <div class="row">
                    <div class="col-xs-12 text-center p-10">
                        <p class="bg-success">Tarea cargada correctamente</p>
                    </div>
                </div>
                ';
            }
        }
        ?>
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
    </div>

    <br />
   
    <div class="row" id="tasks_list">  
        <?php
        echo $tareas_pendientes;
        ?>
    </div>
    
</div> 
