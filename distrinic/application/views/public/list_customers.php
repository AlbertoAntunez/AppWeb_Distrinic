<div class="container">
    <?php
        $this->load->view('public/mod-back');
    ?>
    <div class="row p-10">
        <?php
        if($preSale){
            echo '<p class="products-title">Nuevo Pedido</p><small class="txt_sel_cli">Seleccione un cliente</small>';
        }else{
            echo '<p class="products-title">Clientes</p>';
        }
        ?>        
    </div>

    <?php

    if($preSale){
    ?>
    <input id="preSale" type="text" style="display:none" value="1">
    <div class="row p-10">
        <div class="col-xs-12"><label>Elija localidad</label></div>
        <div class="col-xs-12">
            <select id="sel_loc" class="form-control select2">
                <option value=""> - </option>
                <?php
                foreach($localidades as $lRow){
                    echo '<option value="'.$lRow->localidad.'">'.$lRow->localidad.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    }else{
    ?>
    <input id="preSale" type="text" style="display:none" value="0">
    <select style="display:none;" id="sel_loc" class="form-control">
        <option value=""></option>
    </select>
    <?php
    }
    ?>

    <div class="row">
        <div class="col-xs-12">
            <form class="formBusqueda" action="">
                <div class="col-xs-9">
                    <input type="text" id="txt_bsq_customers" placeholder="descripcion"/>
                </div>

                <div class="col-xs-3">
                    <button id="a_bsq_customers">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row" id="list_customers">

    <?php

    if(!empty($customers)): foreach($customers as $row):
        $desc = $row->razonSocial;
        $desc = ($desc == "") ? "-" : $desc; 

        if(strlen($desc) > 45){
            $desc = substr($desc, 0, 45) . "...";
        }
    ?>

    <div class="col-xs-12 f-product">
        <?php
        if($preSale){ ?>
            <a href="<?=base_url('inicio/newOrder/'.$row->codigo);?>" class="product">
        <?php
        }else{ ?>
            <a href="<?=base_url('customers/view/'.$row->codigo);?>" class="product">
        <?php
        }
        ?>
            <div class="col-xs-2">
                <img alt="customer" src="<?=base_url('assets/img/icon/customers.svg');?>" >
            </div>
            <div class="col-xs-10 col-name-product">
                <p class="nameCustomer"><?php echo $desc;?></p>
            </div>
            <div class="col-xs-6 col-xs-offset-2 col-id-product">
                <small><?php echo $row->codigo?></small>
            </div>            
        </a>
    </div>

    <?php
    endforeach; else:
        
    endif;

    ?>
        
    </div>

</div>