<?php
    if(!isset($rutaAnt)){
        $rutaAnt = base_url();
    }

    if(isset($newOrder)){
    ?>
    <div class="row cabeceraMain">
        <div class="col-xs-5 div-btn-regresar">
            <!--<a class="a-btn-regresar" href="<?=$rutaAnt;?>">< Regresar</a>-->
        </div>
        <?php
        if($idOrder>0){
        ?>
        <div class="col-xs-2 col-xs-offset-1 bot-new-order text-right">
            <a href="javascript:void(0);" id="new_order_delete"><img id="img_new_order_delete" alt="delete" src="<?php echo base_url('assets/img/icon/delete.svg');?>" ></a>
        </div>
        <?php
        }else{
            echo '<div class="col-xs-2 col-xs-offset-1 bot-new-order text-right"></div>';
        }
        ?>
        <div class="col-xs-2 bot-new-order text-right">
            <a href="javascript:void(0);" id="new_order_ok"><img id="img_new_order_ok" alt="ok" src="<?php echo base_url('assets/img/icon/tick.svg');?>" ></a>
        </div>
        <div class="col-xs-2 bot-new-order text-right">
            <a href="javascript:void(0);" id="new_order_cancel"><img id="img_new_order_cancel" alt="cancel" src="<?php echo base_url('assets/img/icon/cancel.svg');?>" ></a>
        </div>
    </div>
    <?php
    }else{
    ?>
    <div class="row cabeceraMain">
        <div class="col-xs-5 div-btn-regresar">
            <a class="a-btn-regresar btn btn-sm btn-primary" href="<?=$rutaAnt;?>">< Regresar</a>
        </div>
        <div class="col-xs-offset-3 col-xs-4 div-btn-regresar text-right">
            <a class="a-btn-regresar btn btn-sm btn-primary" href="<?=base_url();?>">Inicio</a>
        </div>
    </div>
    <?php
    }
?>
