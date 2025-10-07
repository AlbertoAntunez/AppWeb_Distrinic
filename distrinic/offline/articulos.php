<?php
    include_once('header.php');
?>

<?php
    $clasePrecio;
    $preSale = false;
    $disableDivPriceClass = (MODIFICA_CLASEP == "1") ? "" : " disabledDiv ";
    $showTopBar = false;
?>

<div class="container">
    <?php
        include('mod-back.php');
        if($showTopBar){
            //$this->load->view('public/mod-back');
        }
    ?>

    <div class="row">
        <div class="col-xs-12">
        <?php
        if(!$preSale){            
            echo '<p class="products-title">Articulos</p>';
        }
        ?>
        </div>
    </div>
    
    <?php
    if(!$preSale){
        //$disableDivPriceClass = (MODIFICA_CLASEP == "1") ? "" : " disabledDiv ";
    ?>
    <div class="row div_price_class <?php echo $disableDivPriceClass;?>">
        <div class="col-xs-5 col-xs-offset-1">
            <small>Clase de precio</small> 
        </div>
        <div class="col-xs-5">            
            <select class="form-control" style="height: 27px;font-size: 80%;" id="sel_clase_precio">
                <?php 
                $selCP = "";
                $clasePrecio = "";
                $clasePrecio = ($clasePrecio == "") ? "1" : $clasePrecio;
                for($i = 1; $i < 11; $i++){
                    $selCP = ($clasePrecio == $i) ? " selected " : "";
                    echo '<option '.$selCP.' value="'.$i.'">Clase '.$i.'</option>';
                }
                ?>
            </select>
            
        </div>
    </div>
    <?php
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <form class="formBusqueda" action="">
                <div class="col-xs-9">
                    <input type="text" id="txt_bsq_products" placeholder="descripcion"/>
                </div>

                <div class="col-xs-3">
                    <button id="a_bsq_products">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if($preSale){
        echo '<input id="preSale" type="text" style="display:none" value="1">';
    }else{
        echo '<input id="preSale" type="text" style="display:none" value="0">';
    }
    ?>

    <div class="row" id="list_products">
    
    </div>


    <?php
    include('modal_order_products.php');
    ?>
</div>


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        preSale = false;
        loadFromLocalStorage('articulos','');
    });
</script>

<?php

    include_once('footer.php');
?>