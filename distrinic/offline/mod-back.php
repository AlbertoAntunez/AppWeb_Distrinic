<?php
    $idOrder = 0;
    if(!isset($rutaAnt)){
        $rutaAnt = "../offline/inicio.php";
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
            <a href="javascript:void(0);" id="new_order_delete"><img alt="delete" src="../assets/img/icon/delete.svg" ></a>
        </div>
        <?php
        }else{
            echo '<div class="col-xs-2 col-xs-offset-1 bot-new-order text-right"></div>';
        }
        ?>
        <div class="col-xs-2 bot-new-order text-right">
            <a href="javascript:void(0);" id="new_order_ok"><img alt="ok" src="../assets/img/icon/tick.svg" ></a>
        </div>
        <div class="col-xs-2 bot-new-order text-right">
            <a href="javascript:void(0);" id="new_order_cancel"><img alt="cancel" src="../assets/img/icon/cancel.svg" ></a>
        </div>
    </div>
    <?php
    }else{
    ?>
    <div class="row cabeceraMain">
        <div class="col-xs-5 div-btn-regresar">
            <a class="a-btn-regresar" href="<?=$rutaAnt;?>">< Regresar</a>
        </div>
        <div class="col-xs-offset-3 col-xs-4 div-btn-regresar text-right">
            <!--<a class="a-btn-regresar" href="/">Inicio</a>-->
        </div>
    </div>
    <?php
    }
?>
