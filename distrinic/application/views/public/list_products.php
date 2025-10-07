<div class="container">
    <?php
        if($showTopBar){
            $this->load->view('public/mod-back');
        }
        $utl = new Funciones();
    ?>

    <div class="row">
        <div class="col-xs-12">
        <?php
        if(!$preSale){            
            echo '<p class="products-title">Articulos</p>';
            echo '<a target="_blank" class="text-center btn btn-success btn_download_listp" href="'.base_url('products/print_pricelist').'">Descargar lista de precios</a>';
        }
        ?>
        </div>
    </div>
    
    <?php
    if(!$preSale){
        $disableDivPriceClass = (MODIFICA_CLASEP == "1") ? "" : " disabledDiv ";
    ?>
    <div class="row div_price_class <?php echo $disableDivPriceClass;?>">
        <div class="col-xs-5 col-xs-offset-1">
            <small>Clase de precio</small> 
        </div>
        <div class="col-xs-5">            
            <select class="form-control" style="height: 27px;font-size: 80%;" id="sel_clase_precio">
                <?php 
                $selCP = "";
                $clasePrecio = ($clasePrecio == "") ? "1" : $clasePrecio;
                for($i = 1; $i < 11; $i++){
                    $selCP = ($clasePrecio == $i) ? " selected " : "";
                    echo '<option '.$selCP.' value="'.$i.'">Clase '.$i.'</option>';
                }

                $usuario_data = array(
                    'id' => $this->session->userdata('id'),
                    'codigo' => $this->session->userdata('codigo'),
                    'nombre' => $this->session->userdata('nombre'),
                    'logueado' => $this->session->userdata('logueado'),
                    'primerIngreso' => $this->session->userdata('primerIngreso'),
                    'dbgroup' => $this->session->userdata('dbgroup'),
                    'claseP' => $clasePrecio,
                );

                $this->session->set_userdata($usuario_data);
                ?>
            </select>
        </div>
    </div>
    <?php
    }else{
        $clasePrecio = $this->session->userdata('claseP');
        $clasePrecio = ($clasePrecio == "") ? "1" : $clasePrecio;
    }
    ?>

    <input type="text" style="display: none;" name="nclaseprecio" id="nclaseprecio" value="<?php echo $clasePrecio;?>">

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
    
        <?php
            echo $utl->getMaskListProducts($products, $preSale);
        ?>

    <?php
    /*if(!empty($products)): foreach($products as $row):
        
        $desc = $row->descripcion;
        $desc = ($desc == "") ? "Articulo sin descripción" : $desc; 
    
        if(strlen($desc) > 38){
            $desc = substr($desc, 0, 38) . "...";
        }
    ?>
    
    <div class="col-xs-12 f-product">
        <div class="col-xs-2">
            <img alt="barcode" src="<?php echo base_url('assets/img/icon/barcode.svg');?>" />
        </div>
        <div class="col-xs-10 col-name-product">
            <p class="nameProduct">
                <?php
                if($preSale){
                    echo '<a href="javascript:void(0);" id="a_prod_preSale" name="'.$row->idArticulo.'" class="product">';
                }else{
                    echo '<a href="'.base_url('products/view/'.$row->idArticulo).'" class="product">';
                }
                ?>
                    <?php echo $desc;?>
                </a>
            </p>
        </div>
        <div class="col-xs-6 col-xs-offset-2 col-id-product">
            <small><?php echo $row->idArticulo;?></small>
        </div>
        <div class="col-xs-4 col-price-product">
            <?php
            //echo '<small>$'.$row->precio.'</small>';
            echo '<small>Stock : '.$utl->getStockReal($row->idArticulo).'</small>';
            ?>
        </div>
    </div>
    <?php
    endforeach; else:
        echo '<p class="text-center">Sin datos</p>';
    endif;

    */?>
        
    </div>

</div>