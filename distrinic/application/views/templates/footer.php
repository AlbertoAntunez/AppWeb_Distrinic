
		<?php

		// include __DIR__ . '/../public/modal_connect_api.php';

		?>

        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
        <script src="<?php echo base_url(); ?>assets/js/jquery1.12.4.min.local.js"></script>
    	<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>        
        <script type="module" src="<?php echo base_url(); ?>assets/js/funciones.js?modified=<?php echo filemtime("./assets/js/funciones.js");?>"></script>
        

		<!-- Select con busqueda -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<!-- Fin select busqueda -->


        <script src="<?php echo base_url();?>upup.min.js"></script>
		<script>

		//UpUp.debug();

			UpUp.start({
				'cache-version': 'v11',
				'content-url': 'offline/login.html',
				'assets': [
					'offline/inicio.php',
					'assets/bootstrap/css/bootstrap.min.css', 
					'assets/css/estilo.css',
					'assets/css/loader.css', 
					'assets/css/estilo_iphone.css', 
					'assets/img/icon/cancel.svg',
					'assets/img/icon/order.svg',
					'assets/img/icon/barcode.svg',
					'assets/img/icon/customers.svg',
					'assets/img/icon/sent-order.svg',
					'assets/img/icon/whatsapp.svg',	
					'assets/img/icon/check.svg',	
					'assets/img/icon/buscar.svg',
					'assets/img/icon/delete.svg',
					'assets/img/icon/tick.svg',	
					'assets/img/logo.png',
					'offline/funciones.js',
					'assets/bootstrap/js/bootstrap.min.js',
					'assets/js/jquery1.12.4.min.local.js',	  		
					'offline/articulos.php',
					'offline/list_orders.php',
					'offline/header.php',
					'offline/footer.php',
					'offline/Funciones.php',
					'offline/conn.php',
					'configuracion.php',
					'offline/search_product.php',
					'offline/new_order.php',
					'offline/mod-back.php',
					'offline/modal_order_products.php',
					'offline/modal_order_addProduct.php',
					'offline/modal_view_product.php'
				] 
			});

			jQuery(document).ready(function($){
				$(document).ready(function() {
					$('.select2').select2();
				});
			});
		</script>
	</body>
</html>


 <!--
 	'offline/json/articulos.json',
		  		'offline/json/clientes.json',
		  		'offline/json/rubros.json',
		  		'offline/json/pedidos.json',
		  		-->