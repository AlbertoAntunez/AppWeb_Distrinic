<div class="container">
    <?php
        $datosAdic['newOrder'] = true;
        $this->load->view('public/mod-back', $datosAdic);
        if($idOrder>0){            
            echo '
                <div class="row">
                    <div class="col-xs-12 text-center text-success bg-success">
                    EDICIÓN DE PEDIDO
                    </div>
                </div>
            ';
        }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <small>Resumen del pedido</small>
            <p><?=$customer . " - " . $customerName;?></p>
            <input type="text" readonly="readonly" id="idCustomerHidden" value="<?php echo $customer;?>" style="display:none;" />
            <input type="text" readonly="readonly" id="idOrderHidden" value="<?php echo $idOrder;?>" style="display:none;" />
        </div>
    </div>
    <?php
        $disableDivPriceClass = (MODIFICA_CLASEP == "1") ? "" : " disabledDiv ";
    ?>
    <div class="row div_price_class <?php echo $disableDivPriceClass;?>">
        <div class="col-xs-5">
            <small>Clase de precio</small> 
        </div>
        <div class="col-xs-6">            
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
    
    <div class="row">
        <div class="col-xs-6">
            <label>Cod.: <input id="txt_newOrder_prod" type="text" placeholder="Cod. producto"/></label>
        </div>
        <div class="col-xs-2" id="load_div_order">

        </div>
        <div class="col-xs-4 btns-newOrder">
            <button id="btn_check_product"><img id="img_btn_check_product" alt="ok" src="<?php echo base_url('assets/img/icon/check.svg');?>"></button>
            <button id="btn_search_prod"><img id="img_btn_search_prod" alt="buscar" src="<?php echo base_url('assets/img/icon/buscar.svg');?>"></button>
        </div>
    </div>

    <br />
    <div class="row" id="list_order_products">
        <?php
            echo $this->utl->getMaskCart();
        ?>
        <!--
        <div class="row cab-order-products">
            <div class="col-xs-4 text-right"><span>PROD</span></div>
            <div class="col-xs-2 text-right"><span>CANT</span></div>
            <div class="col-xs-3 text-right"><span>UNIT</span></div>
            <div class="col-xs-3 text-right"><span>TOTAL</span></div>
        </div>-->
        <?php
        /*
            $totalFinal = 0; $items = 0;
            foreach ($this->cart->contents() as $row) {
                $desc = $this->model_db->getProductDescription($row["id"]);
                $totalFinal+=$row["subtotal"];
                $items++;
                echo '
                <!--<a href="javascript:void(0);" id="del_product_order" name="'.$row["rowid"].'">-->
				<div class="row">					
					<div class="col-xs-12" id="del_product_order" name="'.$row["rowid"].'">
						<a href="javascript:void(0);" class="del_product_order" name="'.$row["rowid"].'">'
						.$desc.
						'</a>
					</div>					
				</div>
                <div class="row detail-order-products">
                    <div class="col-xs-2 text-right col-xs-offset-4"><span>'.$row["qty"].'</span></div>
                    <div class="col-xs-3 text-right"><span>'.number_format($row["price"], 2).'</span></div>
                    <div class="col-xs-3 text-right"><span>'.number_format($row["subtotal"], 2).'</span></div>
                </div>
                <!--</a>-->
                ';
            }
            
            echo '
                <div class="row div-tot-order">
                    <div class="col-xs-4">Items: '.$items.'</div>
                    <div class="col-xs-3 text-right">Total :</div>
                    <div class="col-xs-5 text-right">
                    $ '.number_format($totalFinal, 2).'
                    </div>
                </div>
            ';   
            */         
        ?>
    </div>    
    <div class="row">
        <div class="col-xs-12">
            <small style="display:block;" class="bg-warning text-center">Toque un producto para eliminarlo.</small>
        </div>
    </div>    
</div>

<?php

$this->load->view('public/modal_order_products');
$this->load->view('public/modal_order_addProduct');

?>