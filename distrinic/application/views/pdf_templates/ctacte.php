<div class="container">
    
    <div class="row">
        <div class="col-xs-12 box-customer">
            
            <div class="col-xs-10" style="text-align: center;">
                <h1>Cuenta Corriente</h1>
                <small>Pendientes</small>
                <h2 class="cod"><?php echo $codigo . ' - ' . $customerName;?></h2>
            </div>
        </div>
    </div>
    <?php
    if($error){
        echo '
            <br><br>
            <p>
                No se definió una API KEY correcta.<br>
                No podrá sincronizar hasta ingresar una correcta.<br>
                Comuníquese con el encargado de sistemas.
            </p>
        ';
    }
    ?>
    <div id="list_invoices">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Comprobante</th>
                    <th>Detalle</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
    <?php
        $fila = 1;
        
        if(!empty($list)): foreach($list as $row):
                if($row->sucNroLetra == "Saldo Anterior:" || $row->sucNroLetra == "Saldo Actual:"){
                    $style = " background: #000; color: #fff;";
                }else{                
                    $style = "";
                }

                if($style == ""){
                    if($fila == 1){
                        $style = " background: #fff; color: #000;";
                        $fila = 0;
                    }else{
                        $fila = 1;
                        $style = " background: #C1C1C1; color: #000;";
                    }
                }

                echo '
                    <tr style="border-bottom: 1px solid #000;'.$style.'">
                        <td>'.$row->fecha.'</td>
                        <td>'.$row->tc . ' ' . $row->sucNroLetra.'</td>
                        <td>'.$row->detalle.'</td>
                        <td style="text-align:right;">'.$row->importe.'</td>
                    </tr>
                ';    

        endforeach; else:
            echo '<tr><th colspan=4>Sin datos</th></tr>';
        endif;
        
    ?>
            </tbody>
        </table>
    </div>

</div>