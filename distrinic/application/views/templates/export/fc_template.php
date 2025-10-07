

<div class="container" style="background-color: white;">
	<div class="row" style="border-bottom: 1px solid black;">
		<div class="col-md-4 col-lg-3 col-xs-4 col-sm-3 text-center">
			<img class="img-responsive" style="max-width: 100%;" src="<?php echo base_url();?>assets/img/zentux.png">
			<p><?=$empresa;?></p>
			<p><?=$direccion;?></p>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1">
			
		</div>
		<div class="col-md-1 col-lg-1 col-xs-1 col-sm-1">
			<div class="text-center" style="padding: 10px; background-color: black; color: white; width: 100%; height: 40px;  font-weight: 700; ">
				<p style="font-size:30px !important;"><?=$letra;?></p>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1">
			
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
			<h3 style="font-size: 20px !important;"><?=strtoupper($nombre_cpte);?></h3>
			<h3 style="line-height: 1px; font-size: 20px !important;"><?=$sucursal;?>-<?=$numero.$letra;?></h3>
			<h5 style="/*margin-top: 24px;*/ font-size: 15px !important;">FECHA : 10/12/2019</h5>
		</div>
		
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-left" style="margin-top: 43px; line-height: 5px;">
			<p>CUIT : 27228073259</p>
			<p>IIBB : 272281-9</p>
			<p>Inicio de Act. : 31/05/2018</p>
		</div>
	</div>

	<?php
	foreach ($cliente as $row) {
	?>
	<div class="row" style="border-bottom: 1px solid black;">
		<div class="col-md-4 col-lg-4 col-xs-6">
			
			<p>Cuenta : <?=$row->codigo . " " . $row->razon;?></p>		
			<p>Documento : <?=$row->tipodoc . " " . $row->nro_doc;?></p>
			<p>Condicion Vta. : <?=$row->condicion;?></p>
			<p>Situación Iva : <?=$row->desciva;?></p>
			
		</div>
		
		<div class="col-lg-8 col-md-8 col-xs-6">
			<p>Domicilio : <?=$row->domicilio . " " . $row->dom_numero;?></p>
			<p>Localidad : <?=$row->localidad . " (" . $row->cp . ")";?></p>				
		</div>

	</div>
	<?php
	}
	?>
	<?php
		$total = 0;
	?>

	<div class="row">
		<div class="col-md-12 col-lg-12 col-xs-12">
			<table class="table" style="width:100%;">
				<thead>
					<tr>
						<th><h4>Codigo</h4></th>
						<th><h4>Descripción</h4></th>
						<th class="text-right"><h4>Cantidad</h4></th>
						<th class="text-right"><h4>Unitario</h4></th>
						<th class="text-right"><h4>Desc. %</h4></th>
						<th class="text-right"><h4>Total</h4></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($cpteDet as $row) {
					?>
					<tr>				
						<td><?php echo $row->codigo;?></td>
						<td><?php echo $row->descripcion;?></td>
						<td class="text-right"><?=$row->cantidad;?></td>
						<td class="text-right"><?=$mon . " " . number_format($row->importe,$dec);?></td>
						<td class="text-right"><?=$row->descuento;?></td>
						<td class="text-right"><?=$mon . " " . number_format($row->total,$dec);?></td>
					</tr>
					<?php
					$total+=$row->total;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-7 col-xs-offset-5" style="border:1px solid black;">
			<p>Subtotal : </p>
			<p>Iva 21% : </p>
			<p>Iva 10,5% : </p>
			<p>Total : <?=$mon . " " . number_format($total,$dec);?></p>
		</div>
	</div>
</div>

<!--
<div class="container" style="background-color: white;">
	<div class="row">
		<div class="col-xs-6 col-lg-6">
			
			<a href="https://twitter.com/tahirtaous">
			<img class="img-responsive" style="max-width: 400px;" src="<?php echo base_url();?>assets/img/zentux.png">
			</a>
			
		</div>

		<div class="col-xs-6 col-lg-6 text-right">
			<h1>INVOICE</h1>
			<h1><small>Invoice #001</small></h1>
		</div>
	</div>

	<hr>
	<?php

	//print_r($cpteCab);
	$razon_social = ""; $cuenta = ""; $total = 0;
	$domicilio = "";
	foreach ($cpteCab as $row) {
		$razon_social = $row->razon_social;
		$cuenta = $row->cuenta;
		$total = $row->total;
	}

	//print_r($cliente);
	foreach ($cliente as $rowa) {
		$domicilio = $rowa->domicilio . " " . $rowa->dom_numero;
		
	}

	?>


  	<div class="row">
		<div class="col-xs-5 col-lg-5">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>From: <a href="#">Your Name</a></h4>
				</div>
				<div class="panel-body">
					<p>
					Address <br>
					details <br>
					more <br>
					</p>
				</div>
			</div>
		</div>
		<div class="col-xs-5 col-xs-offset-2 col-lg-5 col-lg-offset-2 text-right">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>To : <a href="#"><?php echo $razon_social;?></a></h4>
				</div>
				<div class="panel-body">
					<p>
					<?php echo $domicilio;?> <br>
					details <br>
					more <br>
					</p>
				</div>
			</div>
		</div>
    </div>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th><h4>Codigo</h4></th>
				<th><h4>Descripción</h4></th>
				<th><h4>Cantidad</h4></th>
				<th><h4>Unitario</h4></th>
				<th><h4>Descuento</h4></th>
				<th><h4>Total</h4></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($cpteDet as $row) {
			?>
			<tr>				
				<td><?php echo $row->codigo;?></td>
				<td><?php echo $row->descripcion;?></td>
				<td class="text-right"><?php echo $row->cantidad;?></td>
				<td class="text-right">$<?php echo $row->importe;?></td>
				<td class="text-right"><?php echo $row->descuento;?></td>
				<td class="text-right">$<?php echo $row->total;?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>




      <div class="row text-right">
  <div class="col-xs-2 col-xs-offset-8">
    <p>
      <strong>
        Sub Total : <br>
        TAX : <br>
        Total : <br>
      </strong>
    </p>
  </div>
  <div class="col-xs-2">
    <strong>
      $1200.00 <br>
      N/A <br>
      $<?php echo $total;?> <br>
    </strong>
  </div>
</div>
<div class="row">
  <div class="col-xs-5">
    <div class="panel panel-info">
  <div class="panel-heading">
    <h4>Bank details</h4>
  </div>
  <div class="panel-body">
    <p>Your Name</p>
    <p>Bank Name</p>
    <p>SWIFT : -------</p>
    <p>Account Number : 12345678</p>
    <p>IBAN : ------</p>
  </div>
</div>
  </div>
  <div class="col-xs-7">
    <div class="span7">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h4>Contact Details</h4>
    </div>
    <div class="panel-body">
      <p>
          Email : you@example.com <br><br>
          Mobile : +92123456789 <br> <br>
          Twitter  : <a href="https://twitter.com/tahirtaous">@TahirTaous</a> | SitePoint : <a href="http://www.sitepoint.com/author/ttaous/">http://www.sitepoint.com/author/ttaous/</a>
      </p>
      <h4><small>payment should be mabe by Bank Transfer</h4>
    </div>
  </div>
</div>
  </div>
</div>

</div>-->