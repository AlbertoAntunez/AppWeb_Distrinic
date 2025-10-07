<div class="container">
    <?php
        $this->load->view('public/mod-back');

        $vdorCustomer = (defined('VENDEDOR_CLIENTES_PROPIOS')) ? VENDEDOR_CLIENTES_PROPIOS : "0";

        if($vdorCustomer == '1'){
            if($this->session->userdata('codigo') == $vendorCustomer ){
            ?>

            <div class="row p-10">
                <div class="col-xs-6">
                    <a style="width:100%;" href="<?=base_url('customers/viewCtaCte/'.$idCustomer);?>" class="btn btn-sm btn-info">Cuenta Corriente</a>
                </div>

                <div class="col-xs-6">
                    <a style="width:100%;" target="_blank" href="<?=base_url('customers/viewCtaCte/'.$idCustomer.'/-/-/true');?>" class="btn btn-sm btn-info">Cta. Cte. PDF</a>
                </div>
            </div>
            <?php
            }
        }else{
        ?>
        <div class="row p-10">
            <div class="col-xs-6">
                <a style="width:100%;" href="<?=base_url('customers/viewCtaCte/'.$idCustomer);?>" class="btn btn-sm btn-info">Cuenta Corriente</a>
            </div>
            
            <div class="col-xs-6">
                <a style="width:100%;" target="_blank" href="<?=base_url('customers/viewCtaCte/'.$idCustomer.'/-/-/true');?>" class="btn btn-sm btn-info">Cta. Cte. PDF</a>
            </div>
        </div>

        
        <?php        
        }

    ?>


    <?php
    if(!empty($customer)): foreach($customer as $row):
        
        if($row["telefono"] != '') {
            echo '
            <div class="row p-10">
                <div class="col-xs-12">
                    <a href="https://wa.me/'.$row["telefono"].'" class="btn btn-sm whatsapp" target="_blank">
                        <img style="max-width:1.6rem;" src="'.base_url('assets/img/icon/whatsapp.svg').'"> Whatsapp al '.$row["telefono"].'
                        </a>
                </div>
            </div>
            ';
        }

    ?>
    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Código</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Código" readonly="readonly" value="<?php echo $row["codigo"];?>"></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Código Opcional</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Opcional" readonly="readonly" value="<?php echo $row["codigoOpcional"];?>"></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Razon Social</label></div>
        <div class="col-xs-9">
            <textarea class="form-control" placeholder="Razon social" readonly="readonly"><?php echo $row["razonSocial"];?></textarea>
        </div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Dirección</label></div>
        <div class="col-xs-9"><textarea class="form-control" placeholder="Dirección" readonly="readonly"><?php echo $row["calleNroPisoDpto"];?></textarea></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Localidad</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Localidad" readonly="readonly" value="<?php echo $row["localidad"];?>"></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>CUIT</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="CUIT" readonly="readonly" value="<?php echo $row["cuit"];?>"></div>
    </div>
    
    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>IVA</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="IVA" readonly="readonly" value="<?php echo $row["iva"];?>"></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Telefono</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Tel" readonly="readonly" value="<?php echo $row["telefono"];?>"></div>
    </div>

    <div class="row p-10 fProduct">
        <div class="col-xs-3"><label>Email</label></div>
        <div class="col-xs-9"><input type="text" class="form-control" placeholder="Email" readonly="readonly" value="<?php echo $row["email"];?>"></div>
    </div>
    

    <?php
    endforeach; else:    
    endif;
    ?>
        
  

</div>