
<!DOCTYPE html>
<html style="margin: 0;font-size: 0.9em;">
<head>
	<title></title>
	
</head>

<body style="background-color: white; padding-right: 15px;padding-left: 15px;margin-right: 10px;margin-left: 10px; font-family: Tahoma; height: 100%;">
<div style="background-color: white; width: 100%;">
	<div style="border-bottom: 1px solid #6B6B6BFF;">
		<div style="width:50%; float: left; text-align: center;">
			<!--<img class="img-responsive" style="max-width: 100%; max-height: 150px;" src="<?php echo base_url();?>assets/img/logo_empresa.jpeg">-->
			<!--<p><?=$empresa;?></p>
			<p><?=$direccion;?></p>-->
		</div>
		<div style="text-align: center; line-height: 5px; margin-bottom: 30px;">
			<h3 style="font-size: 20px !important; "><?=strtoupper($nombre_cpte);?></h3>
			<small style="font-size: 0.6em;">Comprobante no válido como factura</small>
		</div>
		<div style="width: 40%; display: inline-block; line-height: 5px;">
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">PRESUPUESTO  NRO.:  </span><?=$sucursal;?>-<?=$numero.$letra;?></p>
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">FECHA :</span> <?=$fechaCpte;?></p>
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">VALIDEZ :</span> 30 días</p>
			<!--<p style="font-size: 0.7em;">Comprobante no válido como factura</p>-->
		</div>
		
		<div style="width:30%; line-height: 5px; display: inline-block; position: absolute; margin-top: -11px;">
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">CUIT : </span> 27228073259</p>
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">IIBB : </span> 272281-9</p>
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">INICIO DE ACT. : </span> 31/05/2018</p>
		</div>
	</div>
	
	<?php
	foreach ($cliente as $row) {
	?>
	<div style="height: 60px; border-bottom: 1px solid #6B6B6BFF;">
		<div style="width: 50%; float: left;">
			
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">SEÑOR (ES) : </span><?=$row->codigo . " " . $row->razon;?></p>		
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">DOCUMENTO : </span><?=$row->tipodoc . " " . $row->nro_doc;?></p>
			<!--<p>Condicion Vta. : <?=$row->condicion;?></p>
			<p>Situación Iva : <?=$row->desciva;?></p>-->
			
		</div>
		
		<div style="float: left;">
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">DOMICILIO : </span><?=$row->domicilio . " " . $row->dom_numero;?></p>
			<p style="font-size: 0.75em !important;"><span style="font-weight: bold;">LOCALIDAD : </span><?=$row->localidad . " (" . $row->cp . ")";?></p>				
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
	<div style="">
		<div>
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
	<div class="row" style="margin-top:100px; width: 100%; border:1px solid black; ">
		<div style="display: inline-block; width: 65%; padding-left: 10px; float: left;">
			<p style="font-size: 0.6em;">El siguiente documento tiene una validez de 30 días. Pasados deberá solicitar nuevamente una cotización.</p>
		</div>
		<div class="col-xs-7 col-xs-offset-5" style="display: inline-block; padding-right: 10px; text-align: right;">
			<h4 class="text-right" style="text-align: right;">Total : <?=$mon . " " . number_format($total,$dec);?></h4>
			<p class="text-right" style="font-size: 0.7em; text-align: right;">Los precios cotizados no incluyen IVA</p>
		</div>
	</div>
	<br />
	
</div>
</body>
</html>