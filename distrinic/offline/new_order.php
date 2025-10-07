<?php
    include_once('header.php');
?>

<div class="container">
    <?php
        $newOrder = true;
        include('mod-back.php');

		if(!isset($clasePrecio)){
			$clasePrecio = "";
		}
    ?>

    <div class="row">
        <div class="col-xs-12">
            <small>Resumen del pedido</small>
            <p id="resumen_pedido">
            	<a href="javascript:void(0);" id="sel_cliente_order">Seleccionar Cliente</a>            
            </p>
            <input type="text" readonly="readonly" id="idCustomerHidden" value="" style="display:none;" />
            <input type="text" readonly="readonly" id="idOrderHidden" value="" style="display:none;" />
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
                // $clasePrecio = ($clasePrecio == "") ? "1" : $clasePrecio;
                for($i = 1; $i < 11; $i++){
                    // $selCP = ($clasePrecio == $i) ? " selected " : "";
                    echo '<option '.$selCP.' value="'.$i.'">Clase '.$i.'</option>';
                }
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
            <button id="btn_check_product"><img alt="ok" src="../assets/img/icon/check.svg"></button>
            <button id="btn_search_prod"><img alt="buscar" src="../assets/img/icon/buscar.svg"></button>
        </div>
    </div>

    <br />
    <div class="row" id="list_order_products">
        <?php
            //echo $this->utl->getMaskCart();
           
	        $noMuestraTotales = (defined('NOMUESTRA_TOTALES_PEDIDOS')) ? NOMUESTRA_TOTALES_PEDIDOS : "0";

			$colUnitario = '3';
			$coloff = '4';
			$coltot = '3';
			$colDesc = 'col-xs-12';

	        $mask = "";

	        if($noMuestraTotales == '1'){
	        	$colUnitario = '4';
	        	$coloff = '6';
	        	$mask = '
		        <div class="row cab-order-products">
		            <div class="col-xs-6 text-right"><span>PROD</span></div>
		            <div class="col-xs-2 text-right"><span>CANT</span></div>
		            <div class="col-xs-4 text-right"><span>UNIT</span></div>
		        </div>
		        ';
	        }else{
		        $mask = '
		        <div class="row cab-order-products">
		            <div class="col-xs-4 text-right"><span>PROD</span></div>
		            <div class="col-xs-2 text-right"><span>CANT</span></div>
		            <div class="col-xs-3 text-right"><span>UNIT</span></div>
		            <div class="col-xs-3 text-right"><span>TOTAL</span></div>
		        </div>
		        ';
	        }

	        $mask.='<div class="row" id="row_detalle_order"></div>';
	        
	        $totalFinal = 0; $items = 0;

	        $mask.= '
	            <div class="row div-tot-order">
	                <div class="col-xs-4" id="cant_items">Items: '.$items.'</div>
	            ';

	            if($noMuestraTotales == '0'){
	            	$dispTot = "block";
	            }else{
	            	$dispTot = "none";
	            }
            	
            	$mask.='
	                <div class="col-xs-3 text-right" style="display:'.$dispTot.';">Total :</div>
	                <div class="col-xs-5 text-right" id="total_order" style="display:'.$dispTot.';">
	                $ '.number_format($totalFinal, 2).'
	                </div>
	        	';
	        	
	        $mask.='
	            </div>
	        ';

	        echo $mask;
        ?>

    </div>    
    <div class="row">
        <div class="col-xs-12">
            <small style="display:block;" class="bg-warning text-center">Toque un producto para eliminarlo.</small>
        </div>
    </div>    
</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function(event) {

		clase_precio = <?php echo $clasePrecio; ?>
		
		colUnitario = "<?php echo $colUnitario;?>";
		coloff = "<?php echo $coloff;?>";
		coltot = "<?php echo $coltot;?>";
		colDesc = "<?php echo $colDesc;?>";
    	preSale = true;

    	<?php 
    	if(isset($_GET['idpedido'])){
    		if($_GET['idpedido'] != ''){
    			?>
    			alert('es modificacion');
    			<?php
    		}
    	}
    	?>
    });
</script>

<?php
	include('modal_order_products.php');
	include('modal_order_addProduct.php');
    include_once('footer.php');
?>