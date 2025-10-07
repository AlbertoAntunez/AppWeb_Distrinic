<?php
foreach ($artic as $row) {
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img src="<?=base_url('assets/images/7792180001665.jpg');?>">
		</div>
		<div class="col-md-9">
			<h2>Ficha de Articulo</h2>
			<h3>#<?=$row["codigo"] . " " . $row["descripcion"];?></h3>
		</div>
	</div>
	
</div>

<?php
}
?>