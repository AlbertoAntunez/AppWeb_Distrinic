<!DOCTYPE html>
<html style="margin: 0;font-size: 0.9em;">
<head>
	<title>Lista de Precios</title>

</head>

<body style=" padding: 20px;">
	<div style="text-align: center;">
		<table style="border-collapse: collapse; width: 100%;">
			<thead>
				<tr>
				<th style="text-align:center; border: 1px solid #000; font-size: 13px; font-weight: lighter; padding: 1px;">Código</th>
				<th style="text-align:center; border: 1px solid #000; font-size: 13px; font-weight: lighter; padding: 1px;">Descripción</th>
				<th style="text-align:center; border: 1px solid #000; font-size: 13px; font-weight: lighter; padding: 1px;">Lista1</th>
				<th style="text-align:center; border: 1px solid #000; font-size: 13px; font-weight: lighter; padding: 1px;">Lista2</th>
				<th style="text-align:center; border: 1px solid #000; font-size: 13px; font-weight: lighter; padding: 1px;">Lista3</th>
				</tr>
			</thead>

			<tbody>
				<?php
				$descripcion = ""; $rubro =""; $rubroAnt = ""; $famAnt = ""; $familia = "";
				foreach ($productos as $row) {
					$descripcion = ""; $rubro ="";
					
		            $rubro = $row->rubro;
			        $rubro = preg_replace('([^A-Za-z0-9 ])', '', $rubro);

			        $familia = $row->idFamilia;

			        if($rubroAnt != $row->idRubro){
			        	if($rubroAnt != ''){
			        		echo '<div style="page-break-before:always;"></div>';
			        	}

				        echo '
				        <tr style="background-color:#71E14A; text-align:center; font-size: 12px;">
				        	<th colspan="5" >'.$rubro.'</td>
				        </tr>
				        ';
			    	}

			    	if($famAnt != $row->idFamilia){
				        echo '
				        <tr style="background-color:#8C8E8B;  text-align:center; font-size: 12px;">
				        	<th colspan="5">'.$row->idFamilia. ' ' .$row->familia.'</td>
				        </tr>
				        ';
			    	}

			    	echo '
			    	<tr>
			    		<td style="font-size: 10px;">'.$row->idArticulo.'</td>
			    		<td style="font-size: 10px;">'.$row->descripcion.'</td>
			    		<td style="font-size: 10px; text-align:center;">'.$row->precio1.'</td>
			    		<td style="font-size: 10px; text-align:center;">'.$row->precio2.'</td>
			    		<td style="font-size: 10px; text-align:center;">'.$row->precio3.'</td>
			    	</tr>
			    	';

					$rubroAnt = $row->idRubro;
					$famAnt = $row->idFamilia;
					
					
				}
				//var_dump($productos);
				?>		
			</tbody>
		</table>


	</div>

</body>
</html>