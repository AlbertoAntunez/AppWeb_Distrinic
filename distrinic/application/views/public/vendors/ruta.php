<div class="container">
    <?php
    $this->load->view('public/mod-back');
    $dia = date('N'); //Esto devuelve del 1 al 7 (lunes a domingo)
    $nombreDia = "";

    switch ($dia) {
      case 1: $nombreDia = 'Lunes'; break;
      case 2: $nombreDia = 'Martes'; break;
      case 3: $nombreDia = 'Miercoles'; break;
      case 4: $nombreDia = 'Jueves'; break;
      case 5: $nombreDia = 'Viernes'; break;
      case 6: $nombreDia = 'Sabado'; break;
      case 7: $nombreDia = 'Domingo'; break;    
      default:
        $nombreDia = 'Lunes';
        break;
    }
    ?>

    <div class="row">
        <div class="col-xs-12 text-center">
            <h4>Ruta diaria preestablecida</h4>
            <p><?php echo $nombreDia . ' ' . date('d/m/Y'); ?></p>
        </div>
    </div>
    
    <?php
    $pendiente = true;    
    if(!empty($visitas)) : foreach ($visitas as $visita):

        $active         = "";
        $txtPendiente   = "";
        $dirMaps        = "";
        $pendiente      = $this->model_db->pendienteVisita($visita->cliente);
        $datosCliente   = $this->model_db->getCustomer($visita->cliente)[0];
        
        $direccion = ltrim(rtrim($datosCliente['calleNroPisoDpto'])) . ', ' . ltrim(rtrim($datosCliente['localidad']));

        $direccion = str_replace("."," ",$direccion);
        $direccion = str_replace("-"," ",$direccion);

        $dirMaps = str_replace(",","+",$direccion);
        $dirMaps = str_replace(" ","+",$dirMaps);
        $dirMaps = str_replace("++","+",$dirMaps);

        if($cuenta_sel != ''){
            $active = $visita->cliente == $cuenta_sel ? $active = "item-display": '';
        }
        
        if($pendiente){
            $txtPendiente = '<span class="badge">Pendiente</span>';
        }else{
            $txtPendiente = '<span class="badge badge-visitado">Visitado</span>';
        }

        echo '
        <div class="row item-ruta '.$active.'">
            <div class="col-xs-12 text-right">
                <small class="badge-close">'.$txtPendiente.'</small>
                <br>
                <img class="img-show-more" src="'.base_url('assets/img/icon/plusg.svg').'">
            </div>

            <div class="col-xs-12">
                '.$visita->nombre.'
            </div>

            <div class="col-xs-12">
        ';

        if($direccion != ''){
            echo '<small>'.$direccion.'</small><br>';
        }

        if($datosCliente['telefono'] != ''){
            echo '
            <small>'.$datosCliente['telefono'].'</small><br>
            <small>
                <a href="https://wa.me/'.$datosCliente["telefono"].'">
                    <img style="max-width:1rem;" src="'.base_url('assets/img/icon/whatsapp.svg').'"> Whatsapp al '.$datosCliente['telefono'].'
                </a>
            </small>
            <br><br>
            ';
        }

        if($visita->observaciones != ''){
            echo '<small>Observaciones : '.$visita->observaciones.'</small><br>';
        }
        
        echo '
            </div>
            
            <div class="col-xs-12">
                <a href="'.base_url('/inicio/newOrder/'.$visita->cliente.'?opcion=ruta').'" class="btn-new-order-ruta new-order-ruta">
                    <img src="'.base_url('assets/img/icon/plus.svg').'">
                    Nuevo pedido    
                </a>
                
                <a href="'.base_url('/payments/new?opcion=ruta&cuenta='.$visita->cliente).'" class="btn-new-order-ruta new-order-ruta">
                    <img src="'.base_url('assets/img/icon/payment.svg').'">
                    Cobranza    
                </a>
                
                <a href="'.base_url('/customers/viewCtaCte/'.$visita->cliente.'?opcion=ruta').'" class="btn-new-order-ruta new-order-ruta">
                    <img src="'.base_url('assets/img/icon/customers.svg').'">
                    Cuenta Corriente    
                </a>

                <a href="geo:0,0?q='.$dirMaps.'" class="btn-new-order-ruta map-ruta">
                    <img src="'.base_url('assets/img/icon/map.svg').'">
                    Localizar    
                </a>
            
                
            </div>
            
        </div>
        
        ';

    endforeach; else:
        echo '<p class="text-center bg-success">No tiene rutas preestablecidas.<br>Configurelas desde el maestro de vendedores.</p>';
    endif;
    

        // echo "<pre>";
        // print_r($visitas);
        // echo "</pre>";

    ?>

    
</div> 
