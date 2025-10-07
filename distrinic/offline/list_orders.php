<?php
	include_once('header.php');

?>

<div class="container">
    <?php
    	include('mod-back.php');
    	//include('./application/vies/public/mod-back.php');
        //$this->load->view('public/mod-back');
    ?>

    <div class="row ftbot-orders">
        <div class="col-xs-7 bot-orders">
            <p>Lista de Pedidos</p>
        </div>
        <div class="col-xs-5 bot-orders">
            <a class="btn btn-default" id="btn_new_order_1" href="new_order.php">Nuevo pedido</a>            
        </div>    
    </div>

    <div class="row" id="list_orders">
    	<?php
    	$noMuestraTotales = (defined('NOMUESTRA_TOTALES_PEDIDOS')) ? NOMUESTRA_TOTALES_PEDIDOS : '0';
    	//$noMuestraTotales = ($noMuestraTotales == '1') ? true : false;

        $colRazon = '';
        $colRazon = ($noMuestraTotales == '1') ? "col-xs-10 " : "col-xs-7 ";
    	?>
    </div>
    <br />
    <div class="row">
        <div class="col-xs-12">
            <small style="display:block;" class="bg-warning text-center">Toque un pedido para eliminarlo.</small>
        </div>
    </div>
    <!--<div class="row">
        <div class="col-xs-12">
            <small style="display:block;" class="bg-warning text-center">Toque un pedido para modificarlo.</small>
        </div>
    </div>-->  

</div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
    	noMuestraTotales = <?php echo ($noMuestraTotales == '0') ? "false" : "true";?>;
    	colRazon = "<?php echo $colRazon;?>";
        loadFromLocalStorage('pedidos','');
    });
</script>

<?php
	include('footer.php');
?>