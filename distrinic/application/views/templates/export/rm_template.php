
<!DOCTYPE html>
<html style="margin: 0;font-size: 0.9em;">
<head>
	<title></title>
	<!--<style type="text/css">

	html {
		margin: 0;
		font-size: 0.9em;
	}

	body{
		padding-right: 15px;
		padding-left: 15px;
		margin-right: 10px;
		margin-left: 10px;
	}
	.table thead>tr>th{
		border-right: 1px solid;
	}

	.container{
		width: 100%;
	}


	.text-left{
		text-align: left;
	}

	.text-right{
		text-align: right;
	}

	.text-center{
		text-align: center;
	}

	p{
		font-size: 0.7em;
	}

	.table{
		width: 100%;
		border-collapse: collapse;
	}

	.table>thead>tr>th{
		padding: 1px;
		font-size: 0.8em;
		border:  1px solid;
	}

	.table>tbody>tr>td{
		font-size: 0.7em;
	}

	h5{
		font-size: 0.8em;
	}
</style>-->
</head>

<body style="background-color: white; padding-right: 15px;padding-left: 15px;margin-right: 10px;margin-left: 10px;">
<div class="container" style="background-color: white; width: 100%;">
	<div class="row" style="border-bottom: 1px solid black;">
		<div class="col-md-6 col-lg-3 col-xs-4 col-sm-3 text-center" style="width:50%; float: left; text-align: center;">
			<img class="img-responsive" style="max-width: 100%; max-height: 150px;" src="<?php echo base_url();?>assets/img/logo_empresa.jpeg">
			<!--<p><?=$empresa;?></p>
			<p><?=$direccion;?></p>-->
		</div>
		<!--
		<div class="col-lg-1 col-md-1 col-sm-1">
			
		</div>
		<div class="col-md-1 col-lg-1 col-xs-1 col-sm-1">
			<div class="text-center" style="padding: 10px; background-color: black; color: white; width: 100%; height: 40px;  font-weight: 700; ">
				<p style="font-size:30px !important;"><?=$letra;?></p>
			</div>
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1">
			
		</div>-->
		<div class="col-lg-3 col-md-6 col-sm-3 col-xs-3" style="">
			<h3 style="font-size: 20px !important;"><?=strtoupper($nombre_cpte);?></h3>
			<h3 style="line-height: 1px; font-size: 20px !important;"><?=$sucursal;?>-<?=$numero.$letra;?></h3>
			<h5 style="/*margin-top: 24px;*/ font-size: 15px !important;">FECHA : <?=$fechaCpte;?></h5>
			<p style="font-size: 0.7em;">Comprobante no válido como factura</p>
		</div>
		<!--
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 text-left" style="margin-top: 43px; line-height: 5px;">
			<p>CUIT : 27228073259</p>
			<p>IIBB : 272281-9</p>
			<p>Inicio de Act. : 31/05/2018</p>
		</div>-->
	</div>
<br />
	<?php
	foreach ($cliente as $row) {
	?>
	<div class="row" style="border-bottom: 1px solid black;">
		<div class="col-md-4 col-lg-4 col-xs-6" style="width: 50%; float: left;">
			
			<p style="font-size: 0.7em;">Señor (es) : <?=$row->codigo . " " . $row->razon;?></p>		
			<p style="font-size: 0.7em;">Documento : <?=$row->tipodoc . " " . $row->nro_doc;?></p>
			<!--<p>Condicion Vta. : <?=$row->condicion;?></p>
			<p>Situación Iva : <?=$row->desciva;?></p>-->
			
		</div>
		
		<div class="col-lg-8 col-md-8 col-xs-6">
			<p style="font-size: 0.7em;">Domicilio : <?=$row->domicilio . " " . $row->dom_numero;?></p>
			<p style="font-size: 0.7em;">Localidad : <?=$row->localidad . " (" . $row->cp . ")";?></p>				
		</div>

	</div>
	<?php
	}
	?>
	<?php
		$total = 0;
		$totGral = 0;
	?>
	<br />
	<div class="row">
		<div class="col-md-12 col-lg-12 col-xs-12">
			<table class="table" style="width:100%; width: 100%;border-collapse: collapse;">
				<thead>
					<tr>						
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">CODIGO</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">DESCRIPCIÓN</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">CANT</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">UNITARIO</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">DESC. %</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">IMPORTE DESC</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">UNITARIO</th>
						<th class="text-center" style="text-align: center; padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">TOTAL</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($cpteDet as $row) {
						$importeDesc = 0;
						$unitDesc = 0;

						if($row->descuento>0){
							$importeDesc = (($row->importe*$row->descuento)/100);
							$unitDesc=$row->importe - (($row->importe*$row->descuento)/100);
						}else{
							$importeDesc = 0;
							$unitDesc=$row->importe;
						}
					?>
					<tr>				
						<td style="font-size: 0.7em;"><?php echo $row->codigo;?></td>
						<td style="font-size: 0.7em;"><?php echo $row->descripcion;?></td>
						<td class="text-center" style="text-align: center;font-size: 0.7em;"><?=$row->cantidad;?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$mon . " " . number_format($row->importe,$dec);?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$row->descuento;?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$mon . " " . number_format($importeDesc,$dec);?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$mon . " " . $unitDesc;?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$mon . " " . number_format($row->total,$dec);?></td>
					</tr>
					<?php
					$total+=$row->total;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-xs-7 col-xs-offset-5" style="border:1px solid black; padding-right: 10px;">
			<h4 class="text-right" style="text-align: right;">Total : <?=$mon . " " . number_format($total,$dec);?></h4>
			<p class="text-right" style="font-size: 0.7em; text-align: right;">Los precios cotizados no incluyen IVA</p>
		</div>
	</div>
	<br />
	
	<?php
		$totGral +=$total;
		$total = 0;

		if(count($cpteDetOf)>0){


	?>
	<br />
	<div class="row">
		<div class="col-md-12 col-lg-12 col-xs-12">
			<h5 style="margin:0;font-size: 0.8em;">OFERTAS</h5>
			<table class="table" style="width:100%;">
				<thead>
					<tr>
						<th class="text-center" style="text-align: center;padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">CODIGO</th>
						<th class="text-center" style="text-align: center;padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">DESCRIPCIÓN</th>
						<th class="text-center" style="text-align: center;padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">CANT</th>
						<th class="text-center" style="text-align: center;padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">UNITARIO</th>
						<th class="text-center" style="text-align: center;padding: 1px;font-size: 0.7em;border:  1px solid;border-right: 1px solid;">TOTAL</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($cpteDetOf as $row) {
						$importeDesc = 0;
						$unitDesc = 0;

						if($row->descuento>0){
							$importeDesc = (($row->importe*$row->descuento)/100);
							$unitDesc=$row->importe - (($row->importe*$row->descuento)/100);
						}else{
							$importeDesc = 0;
							$unitDesc=$row->importe;
						}
					?>
					<tr>				
						<td style="font-size: 0.7em;"><?php echo $row->codigo;?></td>
						<td style="font-size: 0.7em;"><?php echo $row->descripcion;?></td>
						<td class="text-center" style="text-align: center;font-size: 0.7em;"><?=$row->cantidad;?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$mon . " " . $unitDesc;?></td>
						<td class="text-right" style="text-align: right;font-size: 0.7em;"><?=$mon . " " . number_format($row->total,$dec);?></td>
					</tr>
					<?php
					$total+=$row->total;
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<br />
	<div class="row">
		<div class="col-xs-7 col-xs-offset-5" style="border:1px solid black; padding-right: 10px;">
			<h4 class="text-right" style="text-align: right;">Total : <?=$mon . " " . number_format($total,$dec);?></h4>
			<p class="text-right" style="font-size: 0.7em; text-align: right;">Los precios cotizados no incluyen IVA</p>
		</div>
	</div>
	<?php $totGral +=$total; 
	}
	?>
	<br />

	<div class="row">
		<div class="col-xs-7 col-xs-offset-5" style=" padding-right: 10px;">
			<h4 class="text-right" style="text-align: right;">TOTAL : <?=$mon . " " . number_format($totGral,$dec);?></h4>
		</div>
	</div>
</div>
</body>
</html>