<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>
    <div class="row">
        <div class="col-xs-12">
            <p class="text-center p-10">
                El proceso de sincronización, descargará la información de vendedores, articulos, clientes y rubros. 
                Este proceso puede demorar varios minutos, dependiendo de la cantidad de registros.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 div-img-sync">
            <img alt="sync" src="<?=base_url('assets/img/icon/sync.svg');?>">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 div-btn-sync">
            <?php
            if($error){
                echo '
                    <span class="text-center bg-danger">
                        '.$error_message.'
                    </span>
                ';
            }else{
            ?>
            <button type="button" class="btn btn-success" id="btn_start_sync">Sincronizar</button>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
    $this->load->view('public/modal_sync');
?>