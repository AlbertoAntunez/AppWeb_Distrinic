<div class="container">
    <?php
        $this->load->view('public/mod-back');
        $utl = new Funciones();
    ?>

    
    <div class="row">
    	<div class="col-md-6">
    		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_getRubros">
			  Sel. Rubros
			</button>   		
    	</div>

    	<div class="col-md-6">
    		
    	</div>
    </div>


    <div class="row">    
        <?php
            echo $utl->getMaskListProducts_PriceList($products);
        ?>
    </div>
</div>

<?php
$this->load->view('public/modal_getRubros');
//$this->load->view('public/modal_getPriceClass');
?>